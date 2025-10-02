<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\InStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class inStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($detailID, $storeID)
    {
        //
        return view('admin.pages.inStock.index', compact('detailID', 'storeID'));
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
    public function update(Request $request, string $detailID, string $storeID)
    {
        //
        if ($request->isMethod('PUT')) {
            $params = $request->except('_token', '_method');

            try {
                //code...
                DB::beginTransaction();

                $inStock = InStock::where('detail_product_id', $detailID)
                    ->where('store_location_id', $storeID)
                    ->first();
                if ($inStock) {
                    $inStock->stock = $params['stock'];
                    $inStock->save();
                }
                DB::commit();
                return redirect()->route('admin.products.index')->with('success', 'Cập nhật thành công!');
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollBack();
                return back()->with('error', 'Cập nhật thất bại!');
            }
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
