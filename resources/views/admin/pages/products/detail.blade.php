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
              <strong class="card-title">Detail {{ $products->title }}</strong>
              <!-- Button trigger modal -->
              <a href="{{ route('admin.detail-products.create', $products->slug) }}" class="btn btn-success">
                Create
              </a>
            </div>
            <div class="my-3 px-3">
              <form action="" method="post">
                @csrf
                <select name="store_location" id="" onchange="this.form.submit()">
                  <option value="all">Tất cả các cửa hàng</option>
                  @foreach ($storeLocations as $location)
                    <option {{ $location->id == $storeID ? 'selected' : '' }} value="{{ $location->id }}">
                      {{ $location->address }}
                    </option>
                  @endforeach
                </select>
              </form>
            </div>
            @if ($products->detailProducts->isNotEmpty())
                <div class="card-body">
                  <table id="bootstrap-data-table" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Price</th>
                        <th>Sale price</th>
                        <th>Stock</th>
                        <th>Color</th>
                        <th>Version</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($products->detailProducts as $product)
                        <tr>
                          <td>{{ $product->id }}</td>
                          <td>{{  number_format($product->price) . 'đ' }}</td>
                          <td>
                            {{ number_format($product->sale_price) . 'đ' }}
                          </td>
                          <td>
                            @php
                              $totalStock = array_reduce($product->inStocks->toArray(), function ($initValue, $item) {
                                return $initValue += $item['stock'];
                              }, 0)
                            @endphp
                            {{ $totalStock }}
                          </td>
                          <td>
                            {{ explode('#', $product->color)[0] }}
                          </td>
                          <td>
                            {{ $product->version }}
                          </td>
                          <td>
                            <div class="text-center d-flex" style="gap: 10px">
                              @if (!empty($storeID))
                                <a href="{{ route('admin.in-stocks.index', [$product->id, $storeID]) }}"
                                  class=" btn btn-primary">Edit Stock</a>
                              @endif
                              <a href="{{ route('admin.detail-products.edit', $product->id) }}"
                                class=" btn btn-success">Edit</a>
                              <form onsubmit="handleDelete(event)"
                                action="{{ route('admin.detail-products.destroy', $product->id) }}" method="POST">
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

    function handleDelete(e) {
      e.preventDefault();
      const isDelete = confirm('Are you sure delete?');
      if (isDelete) {
        e.target.submit();

      }
    }

  </script>

@endpush