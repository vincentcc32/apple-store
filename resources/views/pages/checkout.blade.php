@extends('layouts/app')

@push('style')
@endpush

@section('content')

  <div class="container mx-auto px-4 max-w-5xl">
    <section class="text-center py-8 flex justify-center items-center gap-2 text-white ">
      <i class="fa-solid fa-cart-shopping text-4xl"></i>
      <h1 class="text-4xl font-bold">Checkout</h1>
    </section>

    <section class="mt-10 bg-white p-6 rounded-lg shadow-md grid grid-cols-1 md:grid-cols-2 gap-6">
      {{-- Form thông tin khách hàng --}}
      <div>
        <h2 class="text-xl font-semibold mb-4">Thông tin khách hàng</h2>
        <form action="{{ route('checkout.store') }}" method="post" class="space-y-4" id="checkout-form">
          @csrf
          <div>
            <label class="block font-medium">Họ tên</label>
            <input type="text" name="name" value="{{ Auth::user()->name }}"
              class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-500 @error('name') border-red-500 bg-red-50 text-red-700 @else border-gray-300 @enderror"
              required>
            @error('name')
              <p class="invalid-feedback" style="font-size: 0.875rem; color: #e53e3e; font-weight: bold;">
                {{ $message }}
              </p>
            @enderror
          </div>
          <div>
            <label class="block font-medium">Số điện thoại</label>
            <input type="text" name="phone" value="{{ $userInfo->PhoneNumber }}" class="w-full border rounded px-3 py-2
            @error('phone') border-red-500 bg-red-50 text-red-700 @else border-gray-300 @enderror" required>
            @error('phone')
              <p class="invalid-feedback" style="font-size: 0.875rem; color: #e53e3e; font-weight: bold;">
                {{ $message }}
              </p>
            @enderror
          </div>
          <input type="hidden" name="shipping_fee" id="shipping_fee" value="0">
          <div>
            <label class="block font-medium">Địa chỉ</label>
            <div class="flex gap-3">
              <select name="provinces" id="provinces" required>
                <option value="">Chọn tỉnh thành...</option>
              </select>
              <select name="ward" id="ward" required>
                <option value="">Chọn phường/xã...</option>
              </select>
            </div>
          </div>
          <div>
          </div>
          <div>
            <label class="block font-medium">Số nhà/tên đường/khu vực</label>
            <textarea name="address"class="w-full border rounded px-3 py-2
            @error('address') border-red-500 bg-red-50 text-red-700 @else border-gray-300 @enderror
            " rows="3" required>
          {{ $userInfo->Address }}
          </textarea>
          </div>
          <div>
            <label class="block font-medium">Note</label>
            <textarea name="note" value="{{ old('note') }}" class="w-full border  rounded px-3 py-2
            @error('note') border-red-500 bg-red-50 text-red-700 @else border-gray-300 @enderror" rows="3">
          {{ old('address') }}
          </textarea>
          </div>
          <div>
            <label >Hình thức thánh toán</label>
            <select name="payment" class="w-full border border-gray-300 rounded px-3 py-2">
              <option value="cash">Tiền mặt</option>
              <option value="online">Online(VNPay)</option>
            </select>
          </div>
        </form>
      </div>

      {{-- Chi tiết đơn hàng --}}
      <div>
        <h2 class="text-xl font-semibold mb-4">Đơn hàng của bạn</h2>
        <div class="space-y-4">
          {{-- Vòng lặp qua sản phẩm trong giỏ --}}
          @php
            $totalPrice = 0;
          @endphp
          @foreach($cartItems as $item)
            @php
              $totalPrice += $item->detailProduct->sale_price * $item->quantity;
            @endphp
            <div class="border-b py-2">
              <div class="w-1/5">
                <img src="{{ Storage::url($item->detailProduct->images->first()->path) }}" alt="" class="w-full">
              </div>
              <div class="flex justify-between pb-2">
                <div>
                  <p class="font-semibold">{{ $item->detailProduct->product->title }}</p>
                  <p class="text-sm text-gray-500">Số lượng: {{ $item->quantity }}</p>
                </div>
                <div>
                  <p>{{ number_format($item->detailProduct->sale_price * $item->quantity, 0, ',', '.') }}₫</p>
                </div>
              </div>
            </div>
          @endforeach

          <div class="flex justify-between pt-4 font-bold text-lg">
            <span>Phí vận chuyển:</span>
            <span id="ship">0₫</span>
          </div>
          <p id="time_ship"></p>
          <div class="flex justify-between font-bold text-lg">
            <span>Tổng cộng:</span>
            <span id="total_price"
              data-total_price="{{ $totalPrice }}">{{ number_format($totalPrice, 0, ',', '.') }}₫</span>
          </div>
          {{-- Nút đặt hàng --}}
        <button id="btn-checkout" onclick="hanldeOrder(event)"
          class="w-full mt-4 bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition">
          Đặt hàng
        </button>
        </div>
      </div>
    </section>
  </div>

@endsection

@push('script')

  @vite(['resources/js/checkout.js'])

 <script>
      document.addEventListener("DOMContentLoaded", function () {
         let userId = @json(Auth::user()->id ?? null);
         
        // console.log("Tracking ID:", trackingId);

        if (userId) {
          console.log("Tracking ID:", userId);
          if (window.Echo) {
            window.Echo.leave(`orders.${userId}`);
          window.Echo.private(`orders.${userId}`)
          .subscribed(() => console.log('✅ Subscribed to orders.' + userId))
            .listen('.OrderEvent', (e) => {
              console.log("Nhận event:", e);

              if (e.status === 'success') {
                if(e.message.split(' ')[0] === 'vnpay'){
                  window.location.href = `/checkout/pay/vnpay/${e.message.split(' ')[1]}`;
                }else{
                  window.location.href = "/thank-you/" ;
                }
              } else {
                alert(e.message);
                window.location.href = "/";
              }
            });
        } else {
          console.error("Echo chưa được khởi tạo");
        }
        }
      });

    </script>


<script>

  const fetchDataOrder = async (formData) => {
      try {
        
        const response = await fetch('checkout/store' , {
          method: 'POST',
          body: formData

        });
        // const data = await response.json();
        document.querySelector('#btn-checkout').disabled = true;
        document.querySelector('#btn-checkout').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';


      } catch (error) {
        console.log(error);
        
      }
    }

  function hanldeOrder(e){
    e.preventDefault();
    const form = document.querySelector('#checkout-form');
    const formData = new FormData(form);
 if (!form.checkValidity()) {
    form.reportValidity(); // Hiển thị lỗi cho người dùng
    return;
  }
  fetchDataOrder(formData);

    
  }

</script>

@endpush