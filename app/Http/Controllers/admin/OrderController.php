<?php

namespace App\Http\Controllers\admin;

use App\Events\OrderStatusBroadcast;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search = null;
        $filterStatus = 'all';
        if ($request->has('search')) {
            $search = $request->input('search');
        }

        if ($request->has('filter_status')) {
            $filterStatus = $request->filter_status;
        }

        $orders = Order::with(['detailOrders', 'detailOrders.detailProduct.product.category', 'detailOrders.detailProduct.product'])->orderBy('id', 'desc');


        if ($filterStatus !== 'all') {
            $orders = $orders->where('status', $filterStatus);
        }

        if ($search) {
            $orders = $orders->where('id', 'like', '%' . $search . '%');
        }

        $orders = $orders->paginate(10)->appends(['filterStatus' => $filterStatus]);

        // dd($orders);

        return view('admin.pages.orders.index', compact('orders', 'filterStatus'));
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

        $order = Order::findOrFail($id);
        // dd($order->status, $request->input('status'));
        if ($order->staus >= (int) $request->input('status')) {
            return back()->with('error', 'Vui lòng thử lại sau');
        }
        $order->status = (int) $request->status;
        $order->save();
        // dd($order->status);
        if ($order->status === 3) {

            $order->payment_status = 1;
            $order->save();
        }

        if ($order->status === 4) {
            $order->payment_status = 0;
            $order->save();
        }
        // dd($order);
        broadcast(new OrderStatusBroadcast($order->id, 'success', 'Shop đã cập nhật đơn hàng: ' . $order->id, $order->status, $order->user_id, 'user'));
        return back()->with('success', 'Cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
