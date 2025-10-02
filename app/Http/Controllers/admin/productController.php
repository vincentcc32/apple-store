<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\productRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\StoreLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class productController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $products = Product::with('category')->with('detailProducts')->orderByDesc('id');
        $storeLocations = StoreLocation::all();
        $search = null;
        if ($request->isMethod('POST')) {
            $search = $request->input('search') ?? null;
            if ($search) {
                $products->where('title', 'like', '%' . $search . '%');
            }
        }

        $products = $products->paginate(10);
        // dd($search);
        return view('admin.pages.products.index', compact('products', 'storeLocations', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('admin.pages.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(productRequest $request)
    {
        //
        if ($request->isMethod('POST')) {
            $params = $request->except('_token');

            if ($request->hasFile('thumbnail')) {
                if ($request->file('thumbnail')->isValid()) {
                    // Tạo slug từ title
                    $slug = Str::slug($request->input('title'));  // Chuyển title thành slug

                    // Lấy phần mở rộng của ảnh
                    $extension = $request->file('thumbnail')->getClientOriginalExtension();  // Ví dụ: jpeg, png, jpg

                    // Tạo tên file duy nhất bằng cách thêm timestamp và slug
                    $filename = $slug . '-' . time() . '.' . $extension;

                    // Lưu ảnh vào thư mục 'uploads/products' với tên đã đổi
                    $params['thumbnail'] = $request->file('thumbnail')->storeAs('uploads/products', $filename, 'public');
                } else {
                    return back()->with('error', 'Ảnh không hợp lệ!');
                }
            } else {
                $params['thumbnail'] = null;
            }
            $slug = Str::slug($params['title']);
            // Kiểm tra xem slug đã tồn tại trong cơ sở dữ liệu chưa
            $originalSlug = $slug;
            $count = 1;

            // Nếu slug đã tồn tại, thêm số đếm vào cuối slug
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            try {
                //code...
                DB::beginTransaction();
                Product::create([
                    'title' => $params['title'],
                    'description' => $params['description'],
                    'thumbnail' => $params['thumbnail'],
                    'status' => $params['status'],
                    'slug'  => $slug,
                    'category_id' => $params['category'],
                ]);
                DB::commit();
                return redirect()->route('admin.products.index')->with('success', 'Thêm thành công!');
            } catch (\Throwable $th) {
                DB::rollBack();
                return back()->with('error', 'Thêm thất bại!');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $slug)
    {
        //
        $storeLocations = StoreLocation::all();
        $storeID = null;
        if ($request->has('store_location')) {
            if ($request->input('store_location') !== 'all') {
                $storeID = $request->input('store_location');
            }
        }
        $products = Product::with('category')->where('slug', $slug)->with('detailProducts', function ($query) use ($storeID) {
            $query->with('inStocks', function ($q) use ($storeID) {
                if ($storeID) {
                    $q->where('store_location_id', $storeID);
                }
            });
        })->firstOrFail();
        // dd($products);
        return view('admin.pages.products.detail', compact('products', 'storeLocations', 'storeID'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        //
        $product = Product::where('slug', $slug)->with('category')->first();
        $categories = Category::all();
        if (!$product) {
            return redirect()->route('admin.products.index')->with('error', 'Sản phẩm không tồn tại!');
        }
        return view('admin.pages.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(productRequest $request, string $id)
    {
        //
        if ($request->isMethod('PUT')) {
            $params = $request->except('_token', '_method');
            $product = Product::findOrFail($id);


            if ($request->hasFile('thumbnail')) {
                if ($request->file('thumbnail')->isValid()) {

                    // Xóa ảnh cũ
                    if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                        Storage::disk('public')->delete($product->thumbnail);
                    }


                    // Tạo slug từ title
                    $slug = Str::slug($request->input('title'));  // Chuyển title thành slug

                    // Lấy phần mở rộng của ảnh
                    $extension = $request->file('thumbnail')->getClientOriginalExtension();  // Ví dụ: jpeg, png, jpg

                    // Tạo tên file duy nhất bằng cách thêm timestamp và slug
                    $filename = $slug . '-' . time() . '.' . $extension;

                    // Lưu ảnh vào thư mục 'uploads/products' với tên đã đổi
                    $params['thumbnail'] = $request->file('thumbnail')->storeAs('uploads/products', $filename, 'public');
                } else {
                    return back()->with('error', 'Ảnh không hợp lệ!');
                }
            } else {
                $params['thumbnail'] = $product->thumbnail;
            }
            $slug = Str::slug($params['title']);
            // Kiểm tra xem slug đã tồn tại trong cơ sở dữ liệu chưa
            $originalSlug = $slug;
            $count = 1;

            // Nếu slug đã tồn tại, thêm số đếm vào cuối slug
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            try {
                //code...
                DB::beginTransaction();

                $product->update([
                    'title' => $params['title'],
                    'description' => $params['description'],
                    'thumbnail' => $params['thumbnail'],
                    'status' => $params['status'],
                    'slug' => $slug,
                    'category_id' => $params['category'],
                ]);
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
        $product = Product::findOrFail($id);

        if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        try {
            //code...
            DB::beginTransaction();
            $product->delete();
            DB::commit();
            return back()->with('success', 'Xóa thành công!');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return back()->with('error', 'Xóa thất bại!');
        }
    }
}
