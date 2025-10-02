<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCheckout;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class paymentController extends Controller
{
    //
    function vnPay(Request $request)
    {
        // 
        $request->except('_token');

        try {
            //code...
            DB::beginTransaction();
            $paymentData = $request->all();
            $requiredFields = [
                'vnp_TxnRef',
                'vnp_TransactionNo',
                'vnp_TransactionStatus',
                'vnp_ResponseCode',
                'vnp_Amount',
                'vnp_PayDate',
                'vnp_SecureHash',
            ];

            // Kiểm tra thiếu trường nào
            $missingFields = array_filter($requiredFields, function ($field) use ($paymentData) {
                // DB::rollBack();
                return empty($paymentData[$field]);
            });

            if (!empty($missingFields)) {
                DB::rollBack();
                return redirect()->route('home')->with('error', 'Thiếu dữ liệu thanh toán: ' . implode(', ', $missingFields));
            }

            $cart = Cart::where('user_id', Auth::user()->id)->with('detailProduct')->get();

            if ($paymentData['vnp_TransactionStatus'] == '00' && $paymentData['vnp_ResponseCode'] === '00') {
                $isPayment = Payment::where('order_id', $paymentData['vnp_TxnRef'])->first();
                if (!$isPayment) {


                    $order = Order::findOrFail($paymentData['vnp_TxnRef']); // id của order là txnref
                    $order->payment_status = 1;
                    $order->save();
                }
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'payment_gateway'    => 'vnpay',
                    'bank_code'          => $paymentData['vnp_BankCode'] ?? null,
                    'response_code'      => $paymentData['vnp_ResponseCode'],
                    'transaction_id'     => $paymentData['vnp_TransactionNo'] ?? null,
                    'transaction_status' => $paymentData['vnp_TransactionStatus'],
                    'pay_date'           => \Carbon\Carbon::createFromFormat('YmdHis', $paymentData['vnp_PayDate']),
                ]);
            }
            // ProcessCheckout::dispatch(
            //     Auth::user(),
            //     [],
            //     $cart,
            //     $order->id ?? null
            // )->onQueue('checkout');
            DB::commit();
            return view('pages.pay-online', compact('paymentData'));
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('home')->with('error', 'Thanh toán thất bại.');
        }
    }
}
