<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class searchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $request->except('_token');
        if ($request->has('q')) {
            $q = $request->q;
            $products = Product::where('title', 'like', '%' . $q . '%')
                ->orWhere('category_id', 'like', '%' . $q . '%')
                ->paginate(10)
                ->appends(['q' => $request->q]);
        }

        return view('pages.search', compact('products', 'q'));
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
