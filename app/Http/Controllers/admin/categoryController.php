<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\categoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::orderByDesc('id')->paginate(10);
        // dd(($categories));

        return view('admin.pages.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.pages.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(categoryRequest $request)
    {
        //
        if ($request->isMethod('POST')) {

            $params = $request->except('_token');

            $categorySlug = Str::slug($params['name']);

            $originalSlug = $categorySlug;
            $count = 1;

            // Nếu slug đã tồn tại, thêm số đếm vào cuối slug
            while (Category::where('slug', $categorySlug)->exists()) {
                $categorySlug = $originalSlug . '-' . $count++;
            }
            try {
                DB::beginTransaction();
                Category::create([
                    'name' => $params['name'],
                    'slug' => $categorySlug
                ]);
                DB::commit();
                return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
            } catch (\Throwable $th) {
                DB::rollBack();
                return back()->with('error', 'Thêm danh mục thất bại!');
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
    public function edit(string $slug)
    {
        //
        $category = Category::where('slug', $slug)->first();
        // dd($category->slug);
        if (!$category) {
            return redirect()->route('admin.categories.index')->with('error', 'Danh mục không tồn tại!.');
        }
        return view('admin.pages.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(categoryRequest $request, string $slug)
    {
        //
        if ($request->isMethod('PUT')) {
            $params = $request->except('_token', '_method');
            $category = Category::where('slug', $slug)->first();
            if (!$category) {
                return redirect()->route('admin.categories.index')->with('error', 'Danh mục không tồn tại!');
            }
            $slug = Str::slug($params['name']);
            // Kiểm tra xem slug đã tồn tại trong cơ sở dữ liệu chưa
            $originalSlug = $slug;
            $count = 1;

            // Nếu slug đã tồn tại, thêm số đếm vào cuối slug
            while (Category::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            try {
                DB::beginTransaction();
                $category->update([
                    'name' => $params['name'],
                    'slug' => $slug
                ]);
                $category->save();
                DB::commit();
                return redirect()->route('admin.categories.index')->with('success', 'Cập nhật thành công!');
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
    public function destroy(string $slug)
    {
        //
        $category = Category::where('slug', $slug)->first();
        // dd($category);
        if (!$category) {
            return back()->with('error', 'Danh mục không tồn tại!');
        }
        try {
            //code...
            DB::beginTransaction();
            $category->delete();
            DB::commit();
            return back()->with('success', 'Xóa thành công!');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return back()->with('error', 'Xóa thất bại!');
        }
    }
}
