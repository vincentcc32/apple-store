@extends('layouts/app')

@push('style')

@endpush

@section('content')
  <section class="banner">
    @include('components/banner')
  </section>

  <div class="container mx-auto px-4">
    <section class="text-center pt-10 mt-10 flex justify-center items-center gap-1 text-white">
      <i class="fa-brands fa-apple text-4xl"></i>
      <h1 class="text-4xl text-white font-bold ">iPhone</h1>
    </section>

    <section class="pt-10 mt-10">
      <div class="swiper mySwiperSlide">
        <div class="swiper-wrapper">
          @if ($iphoneProduct->isNotEmpty())
            @foreach ($iphoneProduct as $iphone)
              <div class="swiper-slide min-h-full">
                <a class="block w-full" {{ $iphone->detailProducts->isNotEmpty() && $iphone->status ? 'href=' . route('detail', $iphone->slug) : '' }}>

                  <div
                    class="rounded-2xl {{ $iphone->detailProducts->isNotEmpty() && $iphone->status ? 'bg-box hover:bg-black ' : 'bg-[#464646] ' }} flex justify-center items-center py-10  transition-all duration-300 cursor-pointer">
                    <div class="w-9/12 min-h-[400px] ">
                      <img class="w-full h-full" src='{{ Storage::url($iphone->thumbnail) }}' alt="">
                      <h2 class="text-xl font-bold text-white mt-5 truncate">{{ $iphone->title }}</h2>
                      @if ($iphone->detailProducts->isNotEmpty() && $iphone->status)
                        <div class="text-white mt-5 text-lg block font-bold">
                          {{ number_format($iphone->detailProducts->first()->sale_price) . 'đ'}}
                          <span class="line-through text-gray-400 ml-2">
                            {{ $iphone->detailProducts->first()->price !== $iphone->detailProducts->first()->sale_price ? number_format($iphone->detailProducts->first()->price) . 'đ' : '' }}</span>
                        </div>
                        <p class="mt-5 text-yellow-500 text-lg">Online giá rẻ quá</p>
                      @else
                        <p class="mt-5 text-yellow-500 text-lg">Sản phẩm đang cập nhật/ngừng kinh doanh</p>
                      @endif
                    </div>
                  </div>
                </a>
              </div>
            @endforeach
          @else
            <h1>Không có sản phẩm nào</h1>
          @endif
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </section>

    <section class="text-center pt-10 mt-10 flex justify-center items-center gap-1 text-white">
      <i class="fa-brands fa-apple text-4xl"></i>
      <h1 class="text-4xl text-white font-bold ">MackBook</h1>
    </section>

    <section class="pt-10 mt-10">
      <div class="swiper mySwiperSlide">
        <div class="swiper-wrapper">
          @if ($mackBookProduct->isNotEmpty())
            @foreach ($mackBookProduct as $mackbook)
              <div class="swiper-slide min-h-full">
                <a class="block w-full" {{ $mackbook->detailProducts->isNotEmpty() ? 'href=' . route('detail', $mackbook->slug) : '' }}>

                  <div
                    class="rounded-2xl {{ $mackbook->detailProducts->isNotEmpty() ? 'bg-box hover:bg-black' : 'bg-[#464646]' }} flex justify-center items-center py-10  transition-all duration-300 cursor-pointer">
                    <div class="w-9/12 min-h-[400px] ">
                      <img class="w-full h-full" src='{{ Storage::url($mackbook->thumbnail) }}' alt="">
                      <h2 class="text-xl font-bold text-white mt-5 truncate">{{ $mackbook->title }}</h2>
                      @if ($mackbook->detailProducts->isNotEmpty())
                        <div class="text-white mt-5 text-lg block font-bold">
                          {{ number_format($mackbook->detailProducts->first()->sale_price) . 'đ'}}
                          <span class="line-through text-gray-400 ml-2">
                            {{ $mackbook->detailProducts->first()->price !== $mackbook->detailProducts->first()->sale_price ? number_format($mackbook->detailProducts->first()->price) : '' }}đ</span>
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
          @else
            <h1>Không có sản phẩm nào</h1>
          @endif
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </section>

    <section class="text-center pt-10 mt-10 flex justify-center items-center gap-1 text-white">
      <i class="fa-brands fa-apple text-4xl"></i>
      <h1 class="text-4xl text-white font-bold ">Ipad</h1>
    </section>

    <section class="pt-10 mt-10">
      <div class="swiper mySwiperSlide">
        <div class="swiper-wrapper">
          @if ($ipadProduct->isNotEmpty())
            @foreach ($ipadProduct as $ipad)
              <div class="swiper-slide min-h-full">
                <a class="block w-full" {{ $ipad->detailProducts->isNotEmpty() ? 'href=' . route('detail', $ipad->slug) : '' }}>

                  <div
                    class="rounded-2xl {{ $ipad->detailProducts->isNotEmpty() ? 'bg-box hover:bg-black' : 'bg-[#464646]' }} flex justify-center items-center py-10  transition-all duration-300 cursor-pointer">
                    <div class="w-9/12 min-h-[400px] ">
                      <img class="w-full h-full" src='{{ Storage::url($ipad->thumbnail) }}' alt="">
                      <h2 class="text-xl font-bold text-white mt-5 truncate">{{ $ipad->title }}</h2>
                      @if ($ipad->detailProducts->isNotEmpty())
                        <div class="text-white mt-5 text-lg block font-bold">
                          {{ number_format($ipad->detailProducts->first()->sale_price) . 'đ'}}
                          <span class="line-through text-gray-400 ml-2">
                            {{ $ipad->detailProducts->first()->price !== $ipad->detailProducts->first()->sale_price ? number_format($ipad->detailProducts->first()->price) : '' }}đ</span>
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
          @else
            <h1>Không có sản phẩm nào</h1>
          @endif
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </section>

    <section class="text-center pt-10 mt-10 flex justify-center items-center gap-1 text-white">
      <i class="fa-brands fa-apple text-4xl"></i>
      <h1 class="text-4xl text-white font-bold ">Watch</h1>
    </section>

    <section class="pt-10 mt-10">
      <div class="swiper mySwiperSlide">
        <div class="swiper-wrapper">
          @if ($watchProduct->isNotEmpty())
            @foreach ($watchProduct as $watch)
              <div class="swiper-slide min-h-full">
                <a class="block w-full" {{ $watch->detailProducts->isNotEmpty() ? 'href=' . route('detail', $watch->slug) : '' }}>

                  <div
                    class="rounded-2xl {{ $watch->detailProducts->isNotEmpty() ? 'bg-box hover:bg-black' : 'bg-[#464646]' }} flex justify-center items-center py-10  transition-all duration-300 cursor-pointer">
                    <div class="w-9/12 min-h-[400px] ">
                      <img class="w-full h-full" src='{{ Storage::url($watch->thumbnail) }}' alt="">
                      <h2 class="text-xl font-bold text-white mt-5 truncate">{{ $watch->title }}</h2>
                      @if ($watch->detailProducts->isNotEmpty())
                        <div class="text-white mt-5 text-lg block font-bold">
                          {{ number_format($watch->detailProducts->first()->sale_price) . 'đ'}}
                          <span class="line-through text-gray-400 ml-2">
                            {{ $watch->detailProducts->first()->price !== $watch->detailProducts->first()->sale_price ? number_format($watch->detailProducts->first()->price) : '' }}đ</span>
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
          @else
            <h1>Không có sản phẩm nào</h1>
          @endif
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </section>

  </div>

@endsection

@push('script')
  <script>
    var swiper = new Swiper(".mySwiperSlide", {
      slidesPerView: 4,
      spaceBetween: 30,
      Loop: true,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      breakpoints: {
        // Khi màn hình nhỏ hơn 1024px
        1024: {
          slidesPerView: 4,
        },
        // Khi màn hình nhỏ hơn 768px
        768: {
          slidesPerView: 3,
        },
        // Khi màn hình nhỏ hơn 480px
        480: {
          slidesPerView: 2,
        },
        // Khi màn hình nhỏ hơn 320px
        320: {
          slidesPerView: 1,
        },
      },
    });
  </script>
@endpush