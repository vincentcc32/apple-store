<header id="header" class="header">
  <div class="top-left">
    <div class="navbar-header" style="display: flex">
      <h1><a href={{ route('admin.dashboard') }}>Apple Store</h1>
      <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
    </div>
  </div>
  <div class="top-right">
    <div class="header-menu" style="display: flex; align-items: center;">

      @if (Auth::user()->id === 1)
        <div>
          <a style="font-size: 25px" href="{{ route('admin.message.index') }}">
            <i class="ti-comment"></i>
          </a>
        </div>
      @endif

      <div class="user-area dropdown float-right">
        <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-user" style="font-size: 28px"></i>
        </a>


        <div class="user-menu dropdown-menu">
          <p>Hello {{ Auth::user()->name }}</p>
          <form action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit " class="btn ps-0"
              style="outline: none; background: transparent; font-size: 13px; padding-left: 0;">
              Logout
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</header>