<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class pageController extends Controller
{
    //
    function home()
    {
        $iphoneProduct = Product::whereHas('category', function ($query) {
            $query->where('name', 'iphone');
        })->with('category')->with('detailProducts')->take(10)->get();
        // dd($iphoneProduct);
        $mackBookProduct = Product::whereHas('category', function ($query) {
            $query->where('name', 'macbook');
        })->with('category')->with('detailProducts')->take(10)->get();
        $ipadProduct = Product::whereHas('category', function ($query) {
            $query->where('name', 'ipad');
        })->with('category')->with('detailProducts')->take(10)->get();
        $watchProduct = Product::whereHas('category', function ($query) {
            $query->where('name', 'watch');
        })->with('category')->with('detailProducts')->take(10)->get();
        // dd($mackBookProduct);
        return view('pages/home', compact('iphoneProduct', 'mackBookProduct', 'ipadProduct', 'watchProduct'));
    }


    function products(Request $request)
    {
        $title = $request->path();
        $products = Product::whereHas('category', function ($query) use ($title) {
            $query->where('name', $title);
        })->with('category')->with('detailProducts')->paginate(10);
        return view('pages.product', compact('products', 'title'));
    }

    function detail($slug)
    {
        $detailProduct = Product::where('slug', $slug)->with('category')->with('detailProducts', function ($query) {
            $query->orderBy('version')->with('images', function ($query) {
                $query->orderByDesc('id');
            })->with('specifications', function ($query) {
                $query->orderByDesc('id');
            })->with('inStocks');
        })->firstOrFail()->toArray();

        if (!$detailProduct) {
            return view('pages.notFount');
        }

        $detailProductsGrouped = [];

        foreach ($detailProduct['detail_products'] as $detail) {
            $version = $detail['version'] ?? 'version';
            if (!isset($detailProductsGrouped[$version])) {
                $detailProductsGrouped[$version] = [];
            }
            $detailProductsGrouped[$version][] = $detail;
        }

        // Thay thế phần detail_products bằng mảng nhóm
        $detailProduct['detail_products'] = $detailProductsGrouped;

        $index = 0;
        $key = array_keys($detailProduct['detail_products'])[0];


        if (request('index')) {
            $index = request('index');
        }
        if (request('key')) {
            $key = request('key');
        }
        // dd($key, $index);
        // dd($detailProduct);

        $quantityInStore = array_reduce($detailProduct['detail_products'][$key][$index]['in_stocks'], function ($initValue, $item) {
            return $initValue + $item['stock'];
        }, 0);

        $review = Review::with(['detailProduct', 'detailOrder.order.user'])->where('detail_product_id', $detailProduct['detail_products'][$key][$index]['id'])->get();
        // dd($review);
        $oneStar = $review->filter(fn($item) => $item->rating === 1)->count();
        $twoStar = $review->filter(fn($item) => $item->rating === 2)->count();
        $threeStar = $review->filter(fn($item) => $item->rating === 3)->count();
        $fourStar = $review->filter(fn($item) => $item->rating === 4)->count();
        $fiveStar = $review->filter(fn($item) => $item->rating === 5)->count();
        $totalReviews = $review->count();

        $averageRating = 0;
        if ($totalReviews > 0) {
            $averageRating = (
                ($oneStar * 1) +
                ($twoStar * 2) +
                ($threeStar * 3) +
                ($fourStar * 4) +
                ($fiveStar * 5)
            ) / $totalReviews;
        }

        return view('pages/detail', compact('detailProduct', 'index', 'key', 'quantityInStore', 'review', 'averageRating'));
    }
}
