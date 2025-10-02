<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use App\Models\DetailProduct;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ratingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $detailOrderID, string $detailProductID)
    {
        //
        $rating = Review::where('detail_order_id', $detailOrderID)->where('detail_product_id', $detailProductID)->first();

        // dd($rating);
        return view('pages.rating', compact('rating', 'detailOrderID', 'detailProductID'));
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
    public function store(Request $request, string $detailOrderID, string $detailProductID)
    {
        //
        $params = $request->except('_token');

        DetailOrder::findOrFail($detailOrderID);
        DetailProduct::findOrFail($detailProductID);
        // dd($detailOrderID, $detailProductID);
        try {
            //code...
            DB::beginTransaction();

            Review::create([
                'detail_order_id' => $detailOrderID,
                'detail_product_id' => $detailProductID,
                'rating' => $params['rating'],
                'content' => $params['content'] ?? null,
            ]);

            DB::commit();
            return back()->with('success', 'Cập nhật thành công!');
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            return back()->with('error', 'Cập nhật thất bại!');
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
        //
        $params = $request->except('_token');
        $review = Review::findOrFail($id);
        // dd($params);
        try {
            //code...
            DB::beginTransaction();
            $review->rating = $params['rating'];
            $review->content = $params['content'] ?? null;
            $review->save();
            DB::commit();
            return back()->with('success', 'Cập nhật thành công!');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return back()->with('error', 'Cập nhật thất bại!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $review = Review::findOrFail($id);
        try {
            //code...
            DB::beginTransaction();
            $review->delete();
            DB::commit();
            return back()->with('success', 'Xóa thành công!');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return back()->with('error', 'Xóa thất bại!');
        }
    }
}
