<?php

namespace App\Http\Controllers;

use App\Events\OrderStatusBroadcast;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class orderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $filterStatus = 'all';
        $search = null;
        if ($request->has('filter_status')) {
            $filterStatus = $request->filter_status;
        }

        if ($request->has('search')) {
            $search = $request->search;
        }

        $orders = Order::where('user_id', Auth::id())
            ->with(['detailOrders.detailProduct.product', 'detailOrders.detailProduct.images'])
            ->orderBy('id', 'desc');

        if ($filterStatus !== 'all') {
            $orders = $orders->where('status', $filterStatus);
        }

        if ($search) {
            $orders = $orders->where('id', 'like', '%' . $search . '%');
        }

        $orders = $orders->paginate(10)->appends(['filterStatus' => $filterStatus]);

        // dd($filterStatus);
        return view('pages.order', compact('orders', 'filterStatus'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        // dd($id);
        $order = Order::findOrFail($id);
        try {
            //code...
            DB::beginTransaction();
            if ($order->status === 1) {
                $order->status = 4;
                $order->payment_status = 0;
                $order->save();

                // hoan tien vnpay

                DB::commit();
                broadcast(new OrderStatusBroadcast($order->id, 'success', 'Khách hàng đã hủy đơn: ' . $order->id, $order->status, $order->user_id, 'admin'));
                return back()->with('success', 'Cập nhật thành công!');
            }
            DB::rollBack();
            return back()->with('error', 'Không thể hủy đơn hàng này!');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return back()->with('error', 'Cập nhật thất bại!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
