@extends('admin.layouts.app')

@push('style')

@endpush

@section('content')

  <div class="content">
    <div class="animated fadeIn">
      <div class="row">

        <div class="col-md-12">
          @if ($errors->any())
            <p style="font-size: 1.25rem; color: #e53e3e; margin-top: 1rem; font-weight: bold;">
              Thêm không thành công!
            </p>
          @endif

          @if (session('success'))
            <p style="font-size: 1.25rem; color: #38a169; margin-top: 1rem; font-weight: bold;">
              {{ session('success') }}
            </p>
          @endif

          @if (session('error'))
            <p style="font-size: 1.25rem; color: #e53e3e; margin-top: 1rem; font-weight: bold;">
              {{ session('error') }}
            </p>
          @endif
          <div class="card">
            <div class="card-header d-flex" style="justify-content: space-between; align-items: center;">
              <strong class="card-title">List user</strong>
              <!-- Button trigger modal -->
              <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                Create
              </a>
            </div>
            <div class="my-3 px-3">
              <form action="" method="POST" class="form-inline justify-content-center">
                @csrf
                <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Search users...">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Search</button>
                  </div>
                </div>
              </form>
            </div>

            @if ($users->isNotEmpty())
                <div class="card-body">
                  <table id="bootstrap-data-table" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($users as $user)
                        <tr>
                          <td>{{ $user->id }}</td>
                          <td>{{ $user->name }}</td>
                          <td>{{ $user->email }}</td>
                          <td class="{{ $user->role === 1 ? 'text-success' : 'text-danger' }}">
                            {{ $user->role === 1 ? 'Admin' : 'User' }}
                          </td>
                          <td class="d-flex" style="gap: 10px; align-items: center;">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">Edit</a>
                            <form onsubmit="handleDeleteUser(event)" action="{{ route('admin.users.destroy', $user->id) }}"
                              method="post">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>

                  @if ($users->count() > 0)
                    <div class="d-flex justify-content-center mt-4">
                      {{ $users->links('pagination::bootstrap-4') }}
                    </div>
                  @endif



                </div>
              </div>
            @else
            <h1>không có người dùng nào!</h1>

          @endif
        </div>



      </div>
    </div><!-- .animated -->
  </div>

@endsection

@push('script')

  <script>
    function handleDeleteUser(e) {
      e.preventDefault();
      const isDelete = confirm('Are you sure delete user?');
      if (isDelete) {
        e.target.submit();
      }
    }
  </script>

@endpush