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
              <strong class="card-title">List store</strong>
              <!-- Button trigger modal -->
              <a href="{{ route('admin.store-location.create') }}" class="btn btn-success">
                Create
              </a>
            </div>

            @if ($storeLocations->isNotEmpty())
                <div class="card-body">
                  <table id="bootstrap-data-table" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Adress</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($storeLocations as $storeLocation)
                        <tr>
                          <td>{{ $storeLocation->id }}</td>
                          <td>{{ $storeLocation->address }}</td>
                          <td class="d-flex" style="gap: 10px; align-items: center;">
                            <a href="{{ route('admin.store-location.edit', $storeLocation->id) }}"
                              class="btn btn-primary">Edit</a>
                            <form onsubmit="handleDeleteStoreLocation(event)"
                              action="{{ route('admin.store-location.destroy', $storeLocation->id) }}" method="post">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>

                  @if ($storeLocations->count() > 0)
                    <div class="d-flex justify-content-center mt-4">
                      {{ $storeLocations->links('pagination::bootstrap-4') }}
                    </div>
                  @endif



                </div>
              </div>
            @else
            <h1>không có cửa hàng nào!</h1>

          @endif
        </div>



      </div>
    </div><!-- .animated -->
  </div>

@endsection

@push('script')

  <script>
    function handleDeleteStoreLocation(e) {
      e.preventDefault();
      const isDelete = confirm('Are you sure delete user?');
      if (isDelete) {
        e.target.submit();
      }
    }
  </script>

@endpush