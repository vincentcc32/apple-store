<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\createDetailProductRequest;
use App\Models\DetailProduct;
use App\Models\Image;
use App\Models\Product;
use App\Models\Specification;
use App\Models\StoreLocation;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class detailProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug)
    {
        //

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($type)
    {
        //
        $product = Product::where('slug', $type)->firstOrFail();

        return view('admin.pages.detailProduct.create', compact('product', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(createDetailProductRequest $request)
    {
        // Tại đây dữ liệu đã được validate, bạn có thể gọi: $request->params()
        // dd($request);
        if ($request->isMethod('POST')) {
            $params = $request->except('_token');
            $slug = $params['slug'];
            try {
                DB::beginTransaction();

                $detailProduct = DetailProduct::create([
                    'product_id' => $params['product_id'],
                    'price' => $params['price'],
                    'sale_price' => $params['sale_price'],
                    'color' => $params['color'],
                    'version' => $params['version'],
                ]);

                $detailProduct->inStocks()->createMany(array_map(function ($storeLocationId) {
                    return [
                        'store_location_id' => $storeLocationId,
                        'quantity' => 0, // Khởi tạo số lượng ban đầu
                    ];
                }, StoreLocation::pluck('id')->toArray()));

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $path = $image->store('uploads/detailproduct/id_' . $detailProduct->id, 'public');
                        $detailProduct->images()->create([
                            'detail_product_id' => $detailProduct->id,
                            'path' => $path,
                        ]);
                    }
                }

                if (!empty($params['spec_name'][0]) && !empty($params['spec_value'][0])) {
                    for ($i = 0; $i < count($params['spec_name']); $i++) {
                        if (!empty($params['spec_name'][$i]) && !empty($params['spec_value'][$i])) {
                            Specification::create([
                                'spec_name' => $params['spec_name'][$i],
                                'spec_value' => $params['spec_value'][$i],
                                'detail_product_id' => $detailProduct->id
                            ]);
                        }
                    }
                }

                DB::commit();
                return redirect()->route('admin.products.detail', $slug)->with('success', 'Thêm thành công!');
            } catch (\Throwable $th) {
                // throw $th;
                DB::rollBack();
                return back()->with('error', 'Đã xảy ra lỗi khi thêm sản phẩm.');
            }
        }
        abort(404);
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
        // dd($id);
        $detailProduct = DetailProduct::with(['images', 'specifications'])->findOrFail($id);

        // dd($detailProduct);
        return view('admin.pages.detailProduct.edit', compact('detailProduct'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        // dd($request->file('images'));
        if ($request->isMethod('PUT')) {
            $params = $request->except('_token', '_method');
            $detailProduct = DetailProduct::findOrFail($id);

            try {
                //code...

                DB::beginTransaction();
                $detailProduct->update([
                    'price' => $params['price'],
                    'sale_price' => $params['sale_price'],
                    'color' => $params['color'],
                    'version' => $params['version'],
                ]);

                // Xóa ảnh cũ nếu có
                if ($request->has('delete_images')) {
                    foreach ($request->delete_images as $imageId) {
                        $image = Image::find($imageId);
                        if ($image) {
                            Storage::disk('public')->delete($image->path);
                            $image->delete();
                        }
                    }
                }

                // Xử lý ảnh mới và ảnh cập nhật
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $key => $uploadedFile) {
                        $path = $uploadedFile->store('uploads/detailproduct/id_' . $detailProduct->id, 'public');

                        // Chỉ xử lý cập nhật nếu key là ID thực sự tồn tại
                        $imageModel = Image::find($key);
                        if ($imageModel && $imageModel->detail_product_id === $detailProduct->id) {
                            Storage::disk('public')->delete($imageModel->path);
                            $imageModel->update(['path' => $path]);
                        } else {
                            $detailProduct->images()->create(['path' => $path]);
                        }
                    }
                }
                if (!empty($params['spec_name'][0]) && !empty($params['spec_value'][0])) {
                    for ($i = 0; $i < count($params['spec_name']); $i++) {
                        $specName = $params['spec_name'][$i];
                        $specValue = $params['spec_value'][$i];
                        $specId = $params['spec_id'][$i] ?? null;

                        if (!empty($specName) && !empty($specValue)) {
                            if ($specId) {
                                $spec = Specification::find($specId);
                                if ($spec && $spec->detail_product_id == $detailProduct->id) {
                                    $spec->update([
                                        'spec_name' => $specName,
                                        'spec_value' => $specValue,
                                    ]);
                                }
                            } else {
                                Specification::create([
                                    'spec_name' => $specName,
                                    'spec_value' => $specValue,
                                    'detail_product_id' => $detailProduct->id,
                                ]);
                            }
                        } else {
                            if ($specId) {
                                $spec = Specification::find($specId);
                                $spec->delete();
                            }
                        }
                    }
                }


                DB::commit();
                return redirect()->route('admin.products.detail', $detailProduct->product->slug)->with('success', 'Cập nhật thành công!');
            } catch (\Throwable $th) {
                // throw $th;
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
        try {
            DB::beginTransaction();
            $detailProduct = DetailProduct::findOrFail($id);
            $images = $detailProduct->images;
            $specifications = $detailProduct->specifications;

            if ($images) {
                foreach ($images as $image) {
                    if (Storage::disk('public')->exists($image->path)) {
                        Storage::disk('public')->delete($image->path);
                    }
                    $image->delete();
                }
            }
            if ($specifications) {
                foreach ($specifications as $specification) {
                    $specification->delete();
                }
            }
            $detailProduct->delete();
            DB::commit();
            return back()->with('success', 'Xóa thành công!');
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return back()->with('error', 'Xóa thất bại!');
        }
    }
}
