@extends('layouts/app')

@push('style')

@endpush

@section('content')


  <div class="container mx-auto">
    <section class="text-center py-8 flex justify-center items-center gap-1 text-white">
      <i class="fa-brands fa-apple text-4xl"></i>
      <h1 class="text-4xl text-white font-bold ">Giỏ hàng</h1>
    </section>



    @if ($cartItems->isNotEmpty())
      <div class="overflow-x-auto bg-white p-4">
        <table class="table-auto w-full border-collapse border border-black text-black bg-white">
          <thead>
            <tr class="text-black">
              <th class="border border-black px-4 py-2">Hình ảnh</th>
              <th class="border border-black px-4 py-2">Tên sản phẩm</th>
              <th class="border border-black px-4 py-2">Số lượng</th>
              <th class="border border-black px-4 py-2">Giá</th>
              <th class="border border-black px-4 py-2">Tổng</th>
              <th class="border border-black px-4 py-2">Hành động</th>
            </tr>
          </thead>
          <tbody>
            @php
              $totalPrice = 0;
            @endphp
            @foreach ($cartItems as $item)
              @php
                $itemTotal = $item->detailProduct->sale_price * $item->quantity;
                $totalPrice += $itemTotal;
              @endphp
              <tr>
                <td class="border border-black px-4 py-2">
                  <img src="{{ Storage::url($item->detailProduct->images->first()->path)}}"
                    alt="{{ $item->detailProduct->product->title }}" class="w-16 h-16 object-cover mx-auto">
                </td>
                <td class="border border-black px-4 py-2">{{ $item->detailProduct->product->title }}</td>
                <td class="border px-4 py-2 text-center flex justify-center items-center">
                  <form action="{{ route('cart.update', $item->id) }}" method="post">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="decrease" value="true">
                    <button type="submit" class="p-4">
                      <i class="fa-solid fa-minus p-2 cursor-pointer"></i>
                    </button>
                  </form>
                  <span id="quantity">
                    {{ $item->quantity }}
                  </span>
                  <form action="{{ route('cart.update', $item->id) }}" method="post">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="increase" value="true">
                    <button class="p-4">
                      <i class="fa-solid fa-plus p-2 cursor-pointer"></i>
                    </button>
                  </form>
                </td>
                <td class="border border-black px-4 py-2">{{ number_format($item->detailProduct->sale_price, 0, ',', '.') }}
                  VND
                </td>
                <td class="border border-black px-4 py-2">{{ number_format($itemTotal, 0, ',', '.') }} VND</td>
                <td class="border border-black px-4 py-2">
                  <form action="{{ route('cart.destroy', $item->id)}}" method="POST" onsubmit="handleDeleteCart(event)">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Xóa</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="text-right mt-4">
        <h2 class="text-xl font-semibold text-white mb-5">Tổng tiền: {{ number_format($totalPrice, 0, ',', '.') }} VND</h2>
        <a href="{{ route('checkout') }}"
          class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mt-5 text-2xl">Thanh toán</a>
      </div>
    @else
      <h1>Không có sản phẩm trong giỏ hàng</h1>
    @endif
  </div>

@endsection

@push('script')


  <script>
    function handleDeleteCart(event) {
      event.preventDefault();
      const isDelete = confirm('Are you sure delete?');
      if (isDelete) {
        event.target.submit();
      }
    }
  </script>
  </script>

@endpush