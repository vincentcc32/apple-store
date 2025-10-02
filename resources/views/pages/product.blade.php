@extends('layouts/app')

@push('style')

@endpush

@section('content')


  <div class="container mx-auto">

    <section class="text-center pt-10 mt-10 flex justify-center items-center gap-1 text-white">
    <i class="fa-brands fa-apple text-4xl"></i>
    <h1 class="text-4xl text-white font-bold ">{{ $title  }}</h1>
    </section>

    <section class="pt-10 mt-10 px-4">
    @if ($products->isNotEmpty())
    <div class="grid grid-cols-12 gap-4">
      @foreach ($products as $product)
      <div class="col-span-12 sm:col-span-6 md:col-span-4 lg:col-span-3">
      <a class="block w-full text-center" {{ $product->detailProducts->isNotEmpty() ? 'href=' . route('detail', $product->slug) : '' }}>

      <div
      class="rounded-2xl {{ $product->detailProducts->isNotEmpty() ? 'bg-box hover:bg-black ' : 'bg-[#464646] ' }} flex justify-center items-center py-10  transition-all duration-300 cursor-pointer">
      <div class="w-9/12 min-h-[400px] ">
      <img class="w-full h-full" src='{{ Storage::url($product->thumbnail) }}' alt="">
      <h2 class="text-xl font-bold text-white mt-5 truncate">{{ $product->title }}</h2>
      @if ($product->detailProducts->isNotEmpty())
      <div class="text-white mt-5 text-lg block font-bold">
      {{ number_format($product->detailProducts->first()->sale_price) . 'đ'}}
      <span class="line-through text-gray-400 ml-2">
      {{ $product->detailProducts->first()->price !== $product->detailProducts->first()->sale_price ? number_format($product->detailProducts->first()->price) : '' }}đ</span>
      </div>
      <p class="mt-5 text-yellow-500 text-lg">Online giá rẻ quá</p>
      @else
      <p class="mt-5 text-yellow-500 text-lg">Sản phẩm đang cập nhật!</p>
      @endif
      </div>
      </div>
      </a>
      </div>
    @endforeach
    </div>
    @else
    <h1>Không có sản phẩm nào</h1>
    @endif
    </section>
    @if ($products->hasPages())
    <div class="flex justify-center mt-6">
    <div
      class="inline-flex items-center px-4 py-2 bg-white text-sm font-medium text-gray-700 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
      {{ $products->links() }}
    </div>
    </div>
    @endif

  @endsection

  @push('script')

  @endpush