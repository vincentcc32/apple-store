<aside id="left-panel" class="left-panel">
  <nav class="navbar navbar-expand-sm navbar-default">
    <div id="main-menu" class="main-menu collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="active">
          <a href="{{ route('admin.dashboard') }}"><i class="menu-icon fa fa-laptop"></i>Dashboard </a>
        </li>
        <li class="menu-title">Products</li><!-- /.menu-title -->
        <li class="menu-item-has-children dropdown">
          <a href="{{ route('admin.products.index') }}" class="dropdown-toggle"> <i
              class="menu-icon fa fa-cogs"></i>List</a>

        </li>
        <li class="menu-item-has-children dropdown">
          <a href="{{ route('admin.products.create') }}" class="dropdown-toggle"> <i
              class="menu-icon fa fa-cogs"></i>Create</a>

        </li>

        <li class="menu-title">Categories</li><!-- /.menu-title -->

        <li class="menu-item-has-children dropdown">
          <a href="{{ route('admin.categories.index') }}"> <i class="menu-icon fa fa-tasks"></i>List</a>
        </li>
        <li class="menu-item-has-children dropdown">
          <a href="{{ route('admin.categories.create') }}"> <i class="menu-icon fa fa-tasks"></i>Create</a>
        </li>
        <li class="menu-title">Users</li><!-- /.menu-title -->
        <li class="menu-item-has-children dropdown">
          <a href="{{ route('admin.users.index') }}"> <i class="menu-icon fa fa-users"></i>List</a>
        </li>
        <li class="menu-item-has-children dropdown">
          <a href="{{ route('admin.users.create') }}"> <i class="menu-icon fa fa-street-view"></i>Create</a>
        </li>

        <li class="menu-title">Store locations</li>

        <li class="menu-item-has-children dropdown">
          <a href="{{ route('admin.store-location.index') }}"> <i class="menu-icon fa fa-street-view"></i>List</a>
        </li>

        <li class="menu-title">Orders</li>

        <li class="menu-item-has-children dropdown">
          <a href="{{ route('admin.orders.index') }}"> <i class="menu-icon fa fa-street-view"></i>List</a>
        </li>

      </ul>
    </div><!-- /.navbar-collapse -->
  </nav>
</aside>