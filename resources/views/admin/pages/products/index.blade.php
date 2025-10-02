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
              <strong class="card-title">List products</strong>
              <!-- Button trigger modal -->
              <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                Create
              </a>
            </div>
            <div class="my-3 px-3 row " style="align-items: center; gap: 10px; flex-wrap: nowrap;">

              <form action="" method="POST" class="col-md-6" onsubmit="this.submit(); ">
                @csrf
                <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Search products...">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Search</button>
                  </div>
                </div>
              </form>
            </div>

            @if (!empty($search))
              <h1 class="text-center" style="font-size: 30px; font-weight: bold;">{{ $search }}</h1>
            @endif

            @if ($products->isNotEmpty())
                <div class="card-body">
                  <table id="bootstrap-data-table" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Thumbnail</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($products as $product)
                        <tr class="{{ $product->detailProducts->isEmpty() ? 'bg-secondary text-white ' : '' }}">
                          <td>{{ $product->id }}</td>
                          <td>{{ $product->title }}</td>
                          <td>
                            <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->title }}"
                              style="width: 100px; height: 100px;">
                          </td>
                          <td>
                            {{ $product->category->name }}
                          </td>
                          <td class="{{ $product->status ? 'text-success' : 'text-danger' }}">
                            {{ $product->status ? 'Kinh doanh' : 'Ngừng sản xuất' }}
                          </td>
                          <td>
                            <div class="text-center d-flex" style="gap: 10px">
                              <a href="{{ route('admin.products.detail', $product->slug) }}" class="btn btn-primary">Detail</a>
                              <a href="{{ route('admin.products.edit', $product->slug) }}" class=" btn btn-success">Edit</a>
                              <form onsubmit="handleDelete(event)" action="{{ route('admin.products.destroy', $product->id) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>

                  @if ($products->count() > 0)
                    <div class="d-flex justify-content-center mt-4">
                      {{ $products->links('pagination::bootstrap-4') }}
                    </div>
                  @endif

                </div>
              </div>
            @else
            <h1>không có sản phẩm!</h1>

          @endif
        </div>


      </div>
    </div><!-- .animated -->
  </div>

@endsection

@push('script')

  <script>
    function handleDelete(event) {
      event.preventDefault();
      const isDelete = confirm('Are you sure delete?');
      if (isDelete) {
        event.target.submit();
      }
    }
  </script>

@endpush