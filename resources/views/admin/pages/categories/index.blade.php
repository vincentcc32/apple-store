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
              <strong class="card-title">List categories</strong>
              <!-- Button trigger modal -->
              <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
                Create
              </a>
            </div>


            @if ($categories->isNotEmpty())
                <div class="card-body">
                  <div style="min-width: 500px; overflow-x: hidden;">
                    <table id="bootstrap-data-table" class="table table-striped table-bordered"
                      style="width:100% ; overflow-x: scroll;">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($categories as $category)
                          <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td class="d-flex" style="gap: 5px">
                              <a href="{{ route('admin.categories.edit', $category->slug) }}" class="btn btn-primary">Edit</a>
                              <form action="{{ route('admin.categories.destroy', $category->slug) }}" method="post"
                                onsubmit="handleDeleteCategory(event)">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger">Delete</button>
                              </form>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>

                  @if ($categories->count() > 0)
                    <div class="d-flex justify-content-center mt-4">
                      {{ $categories->links('pagination::bootstrap-4') }}
                    </div>
                  @endif

                </div>

              </div>
            @else
            <h1>không có danh mục nào!</h1>

          @endif
        </div>


      </div>
    </div><!-- .animated -->
  </div>



@endsection

@push('script')

  <script>

    function handleDeleteCategory(e) {
      e.preventDefault();
      const isDelete = confirm('Are you sure you want to delete this category?');
      if (isDelete) {
        e.target.submit();
      }
    }

  </script>

@endpush