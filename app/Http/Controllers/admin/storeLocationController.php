<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\StoreLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class storeLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $storeLocations = StoreLocation::orderByDesc('id')->paginate(10);
        return view('admin.pages.storeLocaion.index', compact('storeLocations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.pages.storeLocaion.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        if ($request->isMethod('POST')) {
            $params = $request->except('_token');

            try {
                //code...
                DB::beginTransaction();
                StoreLocation::create([
                    'address' => $params['address']
                ]);
                DB::commit();
                return redirect()->route('admin.store-location.index')->with('success', 'Thêm thành công!');
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollBack();
                return back()->with('error', 'Thêm thất bại!');
            }
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
        $storeLocation = StoreLocation::findOrFail($id);
        return view('admin.pages.storeLocaion.edit', compact('storeLocation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        if ($request->isMethod('PUT')) {
            $params = $request->except('_token', '_method');
            $storeLocation = StoreLocation::findOrFail($id);
            try {
                //code...
                DB::beginTransaction();
                $storeLocation->update([
                    'address' => $params['address']
                ]);
                DB::commit();
                return redirect()->route('admin.store-location.index')->with('success', 'Cập nhật thành công!');
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
        $storeLocation = StoreLocation::findOrFail($id);

        try {
            //code...
            DB::beginTransaction();
            $storeLocation->delete();
            DB::commit();
            return back()->with('success', 'Xóa thành công!');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return back()->with('error', 'Xóa thất bại!');
        }
    }
}
