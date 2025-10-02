<header class=" bg-black px-5">
  <div class="container mx-auto flex justify-between items-center gap-5 py-4 text-white">
    <div class="md:h-10 md:w-10">
      <img src="/APR_logo.webp" alt="" class="w-full h-full hidden md:block">
      <i class="fa-solid fa-bars md:hidden icon__bars cursor-pointer"></i>
    </div>
    <div class="flex-1/3 hidden md:block">
      <ul class="flex justify-between items-center gap-4">
        <li class="px-4"><a href="{{ route('home') }}">Home</a></li>
        <li class="px-4"><a href="{{ route('iphone') }}">Iphone</a></li>
        <li class="px-4"><a href="{{ route('macbook') }}">Mac</a></li>
        <li class="px-4"><a href="{{ route('ipad') }}">Ipad</a></li>
        <li class="px-4"><a href="{{ route('watch') }}">Watch</a></li>
      </ul>
    </div>
    <div class="flex items-center gap-5">
      {{-- icon search --}}
      <i class="fa-solid fa-magnifying-glass icon__search cursor-pointer "></i>
      {{-- icon cart --}}
      <a href="{{route('cart')}}"
        onclick="{{ Auth::user() ? 'this.submit();' : 'event.preventDefault(); alert("Vui lòng đăng nhập!")' }}">
        <i class="fa-solid fa-cart-shopping"></i>
      </a>
      <div class="relative">
        <!-- Icon người dùng -->
        <i class="fa-solid fa-circle-user cursor-pointer"
          onclick="document.getElementById('dropdown').classList.toggle('hidden')"></i>

        <!-- Dropdown -->
        <div id="dropdown"
          class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-lg shadow-lg z-10 hidden text-black">
          @if (Auth::user())
            <ul>
              <li class="px-4 py-2 cursor-pointer hover:bg-gray-200">
                <p class="truncate">Hello {{ Auth::user()->name }}</p>
              </li>
              <li class="px-4 py-2 cursor-pointer hover:bg-gray-200">
                <a class="block" href="{{ route('profile.edit') }}">Profile</a>
              </li>
              <li class="px-4 py-2 cursor-pointer hover:bg-gray-200">
                <a class="block" href="{{ route('orders') }}">Orders</a>
              </li>
              @if (Auth::user()->role === 1)
                <li class="px-4 py-2 cursor-pointer hover:bg-gray-200">
                  <a class="block" href="{{ route('admin.dashboard') }}">admin</a>
                </li>
              @endif

              <li class="px-4 py-2 cursor-pointer hover:bg-gray-200">
                <form action="{{ route('logout') }}" method="post">
                  @csrf
                  <button type="submit" class="block">
                    Logout
                  </button>

                </form>
              </li>
            </ul>
          @else
            <ul>
              <li class="px-4 py-2 cursor-pointer hover:bg-gray-200">
                <a class="block" href="{{ route('login') }}">Login</a>
              </li>
              <li class="px-4 py-2 cursor-pointer hover:bg-gray-200">
                <a class="block" href="{{ route('register') }}">
                  Register
                </a>
              </li>
            </ul>
          @endif
        </div>
      </div>
    </div>
  </div>
  <div
    class="fixed bottom-0 top-0 left-0 w-2/3 bg-black py-5 transform -translate-x-full transition-transform duration-300 z-50">
    <div class="flex justify-between items-center px-4">
      <img src="/APR_logo.webp" alt="" class="w-10 h-10">
      <i class="fa-solid fa-xmark text-white p-2 text-2xl cursor-pointer closeMenu"></i>
    </div>
    <ul class="text-white mt-5 px-4">
      <li class="py-2"><a href="{{ route('home') }}">Home</a></li>
      <li class="py-2"><a href="{{ route('iphone') }}">Iphone</a></li>
      <li class="py-2"><a href="{{ route('macbook') }}">Mac</a></li>
      <li class="py-2"><a href="{{ route('ipad') }}">Ipad</a></li>
      <li class="py-2"><a href="{{ route('watch') }}">Watch</a></li>
    </ul>
  </div>

</header>