<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DetailProduct;
use App\Models\InStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class cartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng.');
        }
        $cartItems = Cart::where('user_id', Auth::user()->id)->with('detailProduct', function ($query) {
            $query->with('product')->with('images', function ($query) {
                $query->orderByDesc('id');
            });
        })->get();
        // dd($cartItems);
        return view('pages.cart', compact('cartItems'));
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
        // dd($request);
        $params = $request->except('_token');
        $detailProduct = DetailProduct::findOrFail($params['detail_product_id']);

        $inStock = InStock::where('detail_product_id', $detailProduct->id)->sum('stock');
        $cart = Cart::where('user_id', Auth::user()->id)->where('detail_product_id', $detailProduct->id)->first();


        try {
            //code...
            DB::beginTransaction();
            // kiểm tra có tồn tại sp trong giỏ chưa, nếu có ++, không thì kiểm tra ở dưới và thêm mới
            if (!$cart) {
                try {
                    //code...
                    Cart::create([
                        'user_id' => Auth::user()->id,
                        'detail_product_id' => $params['detail_product_id'],
                        'quantity' => $params['quantity'] ?? 1,
                    ]);
                    DB::commit();
                    return redirect()->route('cart')->with('success', 'Thêm vào giỏ hàng thành công.');
                } catch (\Throwable $th) {
                    //throw $th;
                    DB::rollback();
                    return back()->with('error', 'Thêm vào giỏ hàng thất bại.');
                }
            }
            // check sl có trong có kho, có đủ không
            if (($cart->quantity + 1) > $inStock) {
                DB::rollBack();
                return back()->with('error', 'Đã đạt đến giới hạn trong kho!');

                // return back()->with('error', 'Đã đạt đến giới hạn trong kho!');
                // return true;
            }

            $cart->quantity++;
            $cart->save();

            DB::commit();
            return redirect()->route('cart')->with('success', 'Thêm vào giỏ hàng thành công.');
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();
            return redirect()->back()->with('error', 'Thêm vào giỏ hàng thất bại.');
        }
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
        $quantity = 0;
        // Kiểm tra số lượng
        if ($request->input('increase') || $request->input('decrease')) {
            $cart = Cart::findOrFail($id);
            if ($request->input('increase')) {
                $quantity = $cart->quantity + 1;

                // check sl có trong có kho, có đủ không
                $inStock = InStock::where('detail_product_id', $cart->detail_product_id)->sum('stock');
                if ($quantity > $inStock) {
                    return back()->with('error', 'Đã đạt đến giới hạn trong kho!');
                }
            } else if ($request->input('decrease')) {
                $quantity = $cart->quantity - 1;
                if ($quantity <= 0) {
                    return back()->with('error', 'Không thể giảm được nữa!');
                }
            }

            try {
                DB::beginTransaction();
                // Tìm cart theo ID

                $cart->quantity = $quantity;
                $cart->save();
                DB::commit();
                return back()->with('success', 'Cập nhật giỏ hàng thành công.');
            } catch (\Throwable $th) {
                DB::rollBack();
                return back()->with('error', 'Cập nhật giỏ hàng thất bại.');
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

        $cart = Cart::findOrFail($id);
        try {
            DB::beginTransaction();
            $cart->delete();
            DB::commit();
            return back()->with('success', 'Xóa thành công.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Xóa thất bại.');
        }
    }
}
