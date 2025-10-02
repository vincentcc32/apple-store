<footer class="bg-gray-900 text-white py-12 mt-40">
  <div class="container mx-auto px-6 sm:px-12">
    <!-- Footer Top: Logo & Links -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">

      <!-- Logo & Brand Name -->
      <div>
        <i class="fa-brands fa-apple text-9xl"></i>
        <p class="text-sm opacity-80">Apple Store - Mua sắm các sản phẩm Apple chính hãng, bảo hành, giao hàng nhanh
          chóng.</p>
      </div>

      <!-- Quick Links -->
      <div>
        <h5 class="text-xl font-semibold mb-4">Liên kết nhanh</h5>
        <ul>
          <li><a href="#" class="text-gray-300 hover:text-white">Trang chủ</a></li>
          <li><a href="#" class="text-gray-300 hover:text-white">Sản phẩm</a></li>
          <li><a href="#" class="text-gray-300 hover:text-white">Khuyến mãi</a></li>
          <li><a href="#" class="text-gray-300 hover:text-white">Liên hệ</a></li>
        </ul>
      </div>

      <!-- Customer Service -->
      <div>
        <h5 class="text-xl font-semibold mb-4">Dịch vụ khách hàng</h5>
        <ul>
          <li><a href="#" class="text-gray-300 hover:text-white">Chính sách bảo hành</a></li>
          <li><a href="#" class="text-gray-300 hover:text-white">Giao hàng & trả hàng</a></li>
          <li><a href="#" class="text-gray-300 hover:text-white">Hỗ trợ khách hàng</a></li>
        </ul>
      </div>

      <!-- Social Media -->
      <div>
        <h5 class="text-xl font-semibold mb-4">Kết nối với chúng tôi</h5>
        <div class="flex space-x-4">
          <a href="#" class="text-gray-300 hover:text-white">
            <i class="fab fa-facebook fa-2x"></i>
          </a>
          <a href="#" class="text-gray-300 hover:text-white">
            <i class="fab fa-twitter fa-2x"></i>
          </a>
          <a href="#" class="text-gray-300 hover:text-white">
            <i class="fab fa-instagram fa-2x"></i>
          </a>
          <a href="#" class="text-gray-300 hover:text-white">
            <i class="fab fa-youtube fa-2x"></i>
          </a>
        </div>
        <div class="mt-5">
          <select class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500
          text-black" name="" id="">
            @foreach ($allStoreLocation as $storeLocation)
              <option class="text-black" value="">{{ $storeLocation->address }}</option>
            @endforeach
          </select>
        </div>
      </div>

    </div>

    <!-- Footer Bottom -->
    <div class="mt-12 border-t border-gray-700 pt-6">
      <p class="text-center text-sm text-gray-400">&copy; 2025 Apple Store. Tất cả quyền được bảo lưu.</p>
    </div>
  </div>
</footer>