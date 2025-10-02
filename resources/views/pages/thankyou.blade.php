@extends('layouts/app')

@push('style')

@endpush

@section('content')


  <div class="container mx-auto">

    <section class="text-center pt-10 mt-10 flex justify-center items-center gap-1 text-white">
      <i class="fa-brands fa-apple text-4xl"></i>
      <h1 class="text-4xl text-white font-bold ">Thank you</h1>
    </section>

    <section class="pt-10 mt-10 px-4 text-center text-gray-800 dark:text-white max-w-2xl mx-auto">
      <p class="text-lg md:text-xl leading-relaxed">
        Cảm ơn bạn đã đặt hàng tại <span class="font-semibold text-blue-600">Apple Store</span>! Chúng tôi rất trân trọng
        sự tin tưởng của bạn và sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.
      </p>

      <p class="mt-6 text-md md:text-lg italic text-gray-600 dark:text-gray-300">
        Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ với đội ngũ chăm sóc khách hàng của chúng tôi.
      </p>

      <p class="mt-8 text-sm text-gray-500 dark:text-gray-400">
        — Đội ngũ Apple Store
      </p>

      <a href="{{ route('home') }}"
        class="inline-block mt-10 bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:bg-blue-700 transition-all duration-300 ease-in-out transform hover:scale-105">
        Quay về trang chủ
      </a>
    </section>


@endsection

  @push('script')

  @endpush