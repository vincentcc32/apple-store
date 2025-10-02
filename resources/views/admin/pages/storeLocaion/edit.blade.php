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
        <strong class="card-title">Create category</strong>
        </div>

        <div class="card-body">
        <form action="{{ route('admin.store-location.update', $storeLocation->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="form-group">
          <label for="address" style="font-size: 1rem; font-weight: bold; color: #333;">Address</label>
          <input type="text" name="address" id="address"
            class="form-control @error('address') is-invalid @enderror" value="{{ $storeLocation->address }}"
            required
            style="width: 100%; padding: 8px; font-size: 1rem; border: 1px solid #ccc; border-radius: 4px; margin-top: 8px;">

          @error('address')
        <p class="invalid-feedback" style="font-size: 0.875rem; color: #e53e3e; font-weight: bold;">
        {{ $message }}
        </p>
      @enderror
          </div>

          <button type="submit" class="btn btn-primary">Update</button>
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