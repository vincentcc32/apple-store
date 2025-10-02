<div class="swiper mySwiper">
  <div class="swiper-wrapper">
    <div class="swiper-slide">
      <img src={{ asset('banner/banner1.png') }} alt="">
    </div>
    <div class="swiper-slide">
      <img src={{ asset('banner/banner2.png') }} alt="">
    </div>
    <div class="swiper-slide">
      <img src={{ asset('banner/banner3.png') }} alt="">
    </div>
    <div class="swiper-slide">
      <img src={{ asset('banner/banner4.png') }} alt="">
    </div>
    <div class="swiper-slide">
      <img src={{ asset('banner/banner5.png') }} alt="">
    </div>
    <div class="swiper-slide">
      <img src={{ asset('banner/banner6.png') }} alt="">
    </div>
    <div class="swiper-slide">
      <img src={{ asset('banner/banner7.png') }} alt="">
    </div>
  </div>
  <div class="swiper-pagination"></div>
</div>

<script type="module">

  var swiper = new Swiper(".mySwiper", {
    pagination: {
      el: ".swiper-pagination",
    },
    loop: true,
    autoplay: {
      delay: 3000, // thời gian giữa các slide (ms)
      disableOnInteraction: false, // không dừng khi người dùng tương tác
    },
  });
</script>