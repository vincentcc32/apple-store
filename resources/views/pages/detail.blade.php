@extends('layouts/app')

@push('style')

  <style>
    .swiper {
      width: 100%;
      height: 300px;
      margin-left: auto;
      margin-right: auto;
    }

    .swiper-slide {
      background-size: cover;
      background-position: center;
    }

    .mySwiper2 {
      height: 80%;
      width: 100%;
    }

    .mySwiper {
      height: 20%;
      box-sizing: border-box;
      padding: 10px 0;
    }

    .mySwiper .swiper-slide {
      width: 25%;
      height: 100%;
      opacity: 0.4;
    }

    .mySwiper .swiper-slide-thumb-active {
      opacity: 1;
    }

    .swiper-slide img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: contain;
    }
  </style>

@endpush

@section('content')

  <div class="px-3 md:px-0 container mx-auto">
    <div class="grid grid-cols-12 mt-10 gap-5">

      <div class="col-span-12 md:col-span-6 h-[70vh]">

        <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper2">
          <div class="swiper-wrapper">
            @foreach ($detailProduct['detail_products'][$key][$index]['images'] as $image)
              <div class="swiper-slide">
                <img src="{{ Storage::url($image['path']) }}" />
              </div>
            @endforeach

          </div>
          <div class="swiper-button-next text-gray-500"></div>
          <div class="swiper-button-prev text-gray-500"></div>
        </div>
        <div thumbsSlider="" class="swiper mySwiper">
          <div class="swiper-wrapper">
            @foreach ($detailProduct['detail_products'][$key][$index]['images'] as $image)
              <div class="swiper-slide">
                <img src="{{ Storage::url($image['path']) }}" />
              </div>
            @endforeach
          </div>
        </div>

      </div>

      <div class="col-span-12 md:col-span-6 text-white">
        <h1 class="text-3xl font-bold text-uppercase mb-2">
          {{ $detailProduct['title'] . ' ' . $detailProduct['detail_products'][$key][$index]['version'] }}
        </h1>
        <div class="mb-2">
          <p>{{ number_format($detailProduct['detail_products'][$key][$index]['sale_price']) }}₫
            @if ($detailProduct['detail_products'][$key][$index]['sale_price'] != $detailProduct['detail_products'][$key][$index]['price'])
              <span
                class="line-through text-gray-400 ml-2">{{ number_format($detailProduct['detail_products'][$key][$index]['price']) }}₫</span>
            @endif
          </p>
        </div>
        @if ($key !== 'version')
          <p class="font-bold mb-2">Dung lượng</p>
          <div class="mb-2 flex gap-5">
            @foreach ($detailProduct['detail_products'] as $keyFor => $item)
              <form action="" method="post">
                @csrf
                <input type="hidden" name="key" value="{{ $keyFor }}" />
                <button type="submit"
                  class="px-4 py-2 bg-[#2f3033] text-white rounded-lg hover:bg-gray-600 active:bg-[#1c1c1d] active:border-[#535353] focus:outline-none"
                  style="{{ $key == $keyFor ? 'background: #1c1c1d; border: 1px solid #fff' : '' }}">
                  {{ $keyFor}}
                </button>
              </form>
            @endforeach
          </div>
        @endif
        <p class="font-bold mb-2">Màu:
          <span>{{ explode('#', $detailProduct['detail_products'][$key][$index]['color'])[0] }}</span>
        </p>
        <div class="mb-2 flex gap-4">
          @foreach ($detailProduct['detail_products'][$key] as $indexFor => $item)

            <form action="" method="post">
              @csrf
              <input type="hidden" name="key" value="{{ $key }}" />
              <input type="hidden" name="index" value="{{ $indexFor }}" />
              <button type="submit"
                class="rounded-full w-10 h-10 p-2 cursor-pointer {{ $indexFor == $index ? 'border-2 border-blue-700' : '' }}"
                style="background-color: #{{ explode('#', $detailProduct['detail_products'][$key][$indexFor]['color'])[1] }}">
              </button>
            </form>

          @endforeach
        </div>
        <form action="{{ route('cart.store') }}" method="post"
          onsubmit="{{ Auth::user() ? 'this.submit();' : 'event.preventDefault(); alert("Vui lòng đăng nhập để mua hàng")' }}">
          @csrf
          <input type="hidden" name="detail_product_id"
            value="{{ $detailProduct['detail_products'][$key][$index]['id'] }}">
          @if ($quantityInStore > 0)
            <button type="submit" class="block bg-blue-500 p-5 w-full rounded font-bold mt-5">Thêm vào giỏ hàng</button>

          @else
            <p class="block text-center bg-yellow-400 text-white p-5 w-full rounded font-bold mt-5">Tạm hết hàng</p>
          @endif
        </form>

      </div>

    </div>

    <div class="w-full max-w-xl mx-auto mt-8 text-white">
      <!-- Tabs Header -->
      <div class="flex border-b border-gray-300">
        <button class="tab-button px-4 py-2 border-b-2 border-transparent hover:text-blue-600 active-tab"
          data-tab="tab1">Mô tả</button>
        <button class="tab-button px-4 py-2 border-b-2 border-transparent hover:text-blue-600" data-tab="tab2">Thông số kĩ
          thuật</button>
        <button class="tab-button px-4 py-2 border-b-2 border-transparent hover:text-blue-600" data-tab="tab3">Đánh
          giá</button>
      </div>

      <!-- Tabs Content -->
      <div class="tab-content mt-4" id="tab1">
        <div class="mt-4 pb-4 px-2 bg-white text-black w-full">
          {!!  $detailProduct['description'] !!}
        </div>
      </div>
      <div class="tab-content hidden mt-4" id="tab2">
        <div>
          <table class="table-auto w-full bg-white border-t border-b border-gray-300 text-black">
            <tbody>
              @foreach ($detailProduct['detail_products'][$key][$index]['specifications'] as $spec)
                <tr class="border-t border-b border-gray-300">
                  <td class="px-4 py-2">{{ $spec['spec_name'] }}</td>
                  <td class="px-4 py-2">{{ $spec['spec_value'] }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>

        </div>
      </div>
      <div class="tab-content hidden mt-4" id="tab3">

        <div>
          <p class="my-2">Trung bình đánh giá: {{ $averageRating }} / 5 <i class="fa-solid fa-star"
              style="color: #FFD43B;"></i></p>
          @foreach ($review as $item)
            <div class="mb-4">
              <div class="border-b">
                <h2 class="truncate font-bold text-xl">{{ $item->detailOrder->order->user->name }}</h2>
                <div class="flex gap-2 my-2">
                  @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $item->rating)
                      <i class="fa-solid fa-star" style="color: #FFD43B;"></i>
                    @else
                      <i class="fa-regular fa-star"></i>
                    @endif
                  @endfor
                </div>
                <p class="text-lg py-3">{{ $item->content }}</p>
              </div>

            </div>
          @endforeach

        </div>

      </div>
    </div>

  </div>

@endsection

@push('script')


  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".mySwiper", {
      spaceBetween: 10,
      slidesPerView: 4,
      freeMode: true,
      watchSlidesProgress: true,
    });
    var swiper2 = new Swiper(".mySwiper2", {
      spaceBetween: 10,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      thumbs: {
        swiper: swiper,
      },
    });
  </script>

  <script>
    const tabButtons = document.querySelectorAll(".tab-button");
    const tabContents = document.querySelectorAll(".tab-content");

    tabButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        const tab = btn.getAttribute("data-tab");

        // Remove active class from all buttons
        tabButtons.forEach(b => b.classList.remove("active-tab", "text-blue-600", "border-blue-600"));
        // Hide all tab contents
        tabContents.forEach(content => content.classList.add("hidden"));

        // Show the clicked tab content
        document.getElementById(tab).classList.remove("hidden");
        // Highlight active button
        btn.classList.add("active-tab", "text-blue-600", "border-blue-600");
      });
    });
  </script>


@endpush