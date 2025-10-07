<?php

namespace App\Http\Controllers;

use App\Events\OrderProcessed;
use App\Http\Requests\checkoutRequest;
use App\Jobs\ProcessCheckout;
use App\Models\Cart;
use App\Models\DetailOrder;
use App\Models\InStock;
use App\Models\Order;
use App\Models\UserInFo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\AssignOp\Minus;

class checkoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $cartItems = Cart::where('user_id', Auth::user()->id)->with('detailProduct', function ($query) {
            $query->with('product')->with('images', function ($query) {
                $query->orderByDesc('id');
            });
        })->get();

        $userInfo = UserInFo::where('user_id', Auth::user()->id)->first();



        return view('pages.checkout', compact('cartItems', 'userInfo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(checkoutRequest $request)
    {
        //
        if ($request->isMethod('POST')) {
            $params = $request->except('_token');

            $cart = Cart::where('user_id', Auth::user()->id)->with('detailProduct')->get();

            if ($cart->isEmpty()) {
                broadcast(new OrderProcessed('failed', 'Giỏ hàng trống!', Auth::user()->id));
                return redirect()->route('home')->with('error', 'Vui lòng thêm giỏ hàng!');
            }

            $totalAmount = $cart->sum(function ($item) {
                return $item->quantity * $item->detailProduct->price;
            });



            $params['total_amount'] = $totalAmount;

            if ($params['payment'] === 'online') {
                try {
                    //code...
                    DB::beginTransaction();
                    $order = Order::create([
                        'user_id' => Auth::user()->id,
                        'customer_name' => $params['name'],
                        'phone' => $params['phone'],
                        'address' => $params['address'] . ' ' . $params['ward'] . ' ' . $params['provinces'],
                        'note' => $params['note'] ?? null,
                        'shipping_fee' => $params['shipping_fee'] ?? 0,
                        'total_amount' =>  $totalAmount + $params['shipping_fee'],
                        'payment_method' => 0,
                        'payment_status' => 0,
                    ]);
                    if (!$order) {
                        DB::rollBack();
                        return back()->with('error', "Vui lòng thử lại!");
                    }

                    ProcessCheckout::dispatch(
                        Auth::user(),
                        $params,
                        $cart,
                        $order->id
                    )->onQueue('checkout');

                    DB::commit();
                    // return redirect()->route('checkout.pay-online', ['vnpay', $order->id]);
                    // return redirect()->route('home');
                    // broadcast(new OrderProcessed('failed', '', Auth::user()->id));
                } catch (\Throwable $th) {
                    //throw $th;
                    DB::rollBack();
                    // return back()->with('error', "Vui lòng thử lại!");
                    broadcast(new OrderProcessed('failed', $th->getMessage(), Auth::user()->id));
                }
            } else {
                try {
                    //code...
                    ProcessCheckout::dispatch(
                        Auth::user(),
                        $params,
                        $cart,
                        $order->id ?? null
                    )->onQueue('checkout');
                    // broadcast(new OrderProcessed($order->id ?? 1, 'success', 'Đặt hàng thành công!', $trackingId));
                    // return back();
                    // return redirect()->route('thank-you');
                } catch (\Throwable $th) {
                    //throw $th;
                    broadcast(new OrderProcessed('failed', $th->getMessage(), Auth::user()->id));
                }
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($type, $orderID)
    {
        //
        $order = Order::findOrFail($orderID);
        if ($type === 'vnpay') {

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $startTime = date("YmdHis");
            $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

            $vnp_TxnRef = $order->id; //Mã giao dịch thanh toán tham chiếu của merchant
            $vnp_Amount = $order->total_amount; // Số tiền thanh toán
            $vnp_Locale = 'vi'; //Ngôn ngữ chuyển hướng thanh toán
            $vnp_BankCode = ''; //Mã phương thức thanh toán
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; //IP Khách hàng thanh toán

            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => env('vnp_TmnCode'),
                "vnp_Amount" => $vnp_Amount * 100,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => "Thanh toan GD:" . $vnp_TxnRef,
                "vnp_OrderType" => "other",
                "vnp_ReturnUrl" => env('vnp_Returnurl'),
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ExpireDate" => $expire
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }

            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = env('vnp_Url') . "?" . $query;
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, env('vnp_HashSecret')); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

            header('Location: ' . $vnp_Url);
            die();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
