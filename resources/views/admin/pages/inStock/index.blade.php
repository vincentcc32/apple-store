@extends('admin.layouts.app')

@push('style')

@endpush

@section('content')

  <div class="content">
    <div class="animated fadeIn">
      <div class="row">

        <div class="col-md-12">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          @if (session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif

          @if (session('error'))
            <div class="alert alert-danger">
              {{ session('error') }}
            </div>
          @endif

          <div class="card">
            <div class="card-header d-flex" style="justify-content: space-between; align-items: center;">
              <strong class="card-title">Add stock</strong>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.in-stocks.update', [$detailID, $storeID]) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                  <label for="stock" class="font-weight-bold">Stock</label>
                  <input type="text" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror"
                    value="{{ old('stock') }}" required>
                  @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <button type="submit" class="btn btn-primary">Add</button>
              </form>
            </div>
          </div>

        </div>


      </div>
    </div><!-- .animated -->
  </div>

@endsection

@push('script')


@endpush