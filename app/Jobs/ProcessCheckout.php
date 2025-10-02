<?php

namespace App\Jobs;

use App\Events\OrderProcessed;
use App\Events\UserOrderBroadcast;
use App\Models\Cart;
use App\Models\DetailOrder;
use App\Models\InStock;
use App\Models\Order;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessCheckout implements ShouldQueue
{
    use  Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user, $params, $cart, $orderID;
    /**
     * Create a new job instance.
     */
    public function __construct($user, $params = [],  $cart, $orderID = null)
    {
        //
        $this->user = $user;
        $this->params = $params;
        $this->cart   = $cart;
        $this->orderID   = $orderID;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //

        try {
            //code...
            DB::beginTransaction();

            $order = null;
            if ($this->cart->isEmpty()) {
                throw new Exception('Giỏ hàng trống!');
            }
            // xử lý nếu có orderID(đã tạo order)
            if ($this->orderID) {
                $order = Order::find($this->orderID);
                if (!$order) {
                    DB::commit();
                    throw new Exception('Order not found');
                }

                // kiểm tra xem tổng số lượng trong kho có đủ để mua không
                foreach ($this->cart as $item) {
                    $inStocks = InStock::where('detail_product_id', $item->detail_product_id)->sum('stock');
                    if ($inStocks < $item->quantity) {
                        // nếu không đủ xóa sp trong giỏ hàng của khách hàng
                        Cart::destroy($this->cart->pluck('id'));
                        $order->delete();
                        Log::error('Order', ['error' => $order]);
                        DB::commit();
                        throw new Exception('Kho không đủ hàng!');
                    }
                }
                // nếu kho đủ hàng, trừ stockk trong kho
                foreach ($this->cart as $item) {

                    $inStocks = InStock::where('detail_product_id', $item->detail_product_id)->get();

                    $remaining = $item->quantity;

                    foreach ($inStocks as $inStock) {
                        if ($remaining <= 0) break;

                        if ($inStock->stock >= $remaining) {
                            // Kho đủ hàng
                            $inStock->stock -= $remaining;
                            $inStock->save();
                            $remaining = 0;
                        } else {
                            // Kho không đủ, trừ hết kho rồi chuyển sang kho tiếp theo
                            $remaining -= $inStock->stock;
                            $inStock->stock = 0;
                            $inStock->save();
                        }
                    }
                    // sau khi có order tạo chi tiết order
                    DetailOrder::create([
                        'order_id' => $order->id,
                        'detail_product_id' => $item->detail_product_id,
                        'quantity' => $item->quantity,
                        'sum_price' => $item->quantity * $item->detailProduct->price,
                    ]);
                    // xóa giỏ hàng khi thành công
                    Cart::destroy($this->cart->pluck('id'));
                }

                broadcast(new OrderProcessed('success', 'vnpay ' . $order->id, $this->user->id));
                checkoutEmail::dispatch($this->user, $order)->onQueue('checkout-email');
            } else {
                // kiểm tra xem tổng số lượng trong kho có đủ để mua không
                foreach ($this->cart as $item) {
                    $inStocks = InStock::where('detail_product_id', $item->detail_product_id)->sum('stock');
                    if ($inStocks < $item->quantity) {
                        // nếu không đủ xóa sp trong giỏ hàng của khách hàng
                        Cart::destroy($this->cart->pluck('id'));
                        DB::commit();
                        throw new Exception('Kho không đủ hàng!!');
                    }
                }

                // tạo order
                $order = Order::create([
                    'user_id' =>  $this->user->id,
                    'customer_name' => $this->params['name'],
                    'phone' => $this->params['phone'],
                    'address' => $this->params['address'] . ' ' . $this->params['ward'] . ' ' . $this->params['provinces'],
                    'note' => $this->params['note'] ?? null,
                    'shipping_fee' => $this->params['shipping_fee'] ?? 0,
                    'total_amount' =>  $this->params['total_amount'] + $this->params['shipping_fee'],
                    'payment_method' =>  1,
                ]);

                // nếu kho đủ hàng, trừ stockk trong kho
                foreach ($this->cart as $item) {

                    $inStocks = InStock::where('detail_product_id', $item->detail_product_id)->get();

                    $remaining = $item->quantity;

                    foreach ($inStocks as $inStock) {
                        if ($remaining <= 0) break;

                        if ($inStock->stock >= $remaining) {
                            // Kho đủ hàng
                            $inStock->stock -= $remaining;
                            $inStock->save();
                            $remaining = 0;
                        } else {
                            // Kho không đủ, trừ hết kho rồi chuyển sang kho tiếp theo
                            $remaining -= $inStock->stock;
                            $inStock->stock = 0;
                            $inStock->save();
                        }
                    }
                    // sau khi có order tạo chi tiết order
                    DetailOrder::create([
                        'order_id' => $order->id,
                        'detail_product_id' => $item->detail_product_id,
                        'quantity' => $item->quantity,
                        'sum_price' => $item->quantity * $item->detailProduct->first()->price ?? 0,
                    ]);
                    // xóa giỏ hàng khi thành công
                    Cart::destroy($this->cart->pluck('id'));
                }
                broadcast(new OrderProcessed('success', 'Đặt hàng thành công!', $this->user->id));
                broadcast(new UserOrderBroadcast($order->id, 'success'));
                checkoutEmail::dispatch($this->user, $order)->onQueue('checkout-email');
            }
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            // nếu lỗi bắn ra thông báo.
            // Log::info('trickingID', ['trackingId' => $this->trackingId]);
            DB::commit();
            Log::error('Checkout Job Failed', ['error' => $th->getMessage()]);

            broadcast(new OrderProcessed('failed', $th->getMessage(), $this->user->id));
            // throw $th;
        }
    }
}
