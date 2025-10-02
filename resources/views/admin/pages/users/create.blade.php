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
        <strong class="card-title">Create user</strong>
        </div>

        <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
          @csrf
          <div class="form-group">
          <label for="name" style="font-size: 1rem; font-weight: bold; color: #333;">Name</label>
          <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name') }}" required
            style="width: 100%; padding: 8px; font-size: 1rem; border: 1px solid #ccc; border-radius: 4px; margin-top: 8px;">

          @error('name')
        <p class="invalid-feedback" style="font-size: 0.875rem; color: #e53e3e; font-weight: bold;">
        {{ $message }}
        </p>
      @enderror
          </div>

          <div class="form-group">
          <label for="email" style="font-size: 1rem; font-weight: bold; color: #333;">Email</label>
          <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email') }}" required
            style="width: 100%; padding: 8px; font-size: 1rem; border: 1px solid #ccc; border-radius: 4px; margin-top: 8px;">

          @error('email')
        <p class="invalid-feedback" style="font-size: 0.875rem; color: #e53e3e; font-weight: bold;">
        {{ $message }}
        </p>
      @enderror
          </div>
          <div class="form-group">
          <label for="password" style="font-size: 1rem; font-weight: bold; color: #333;">password</label>
          <input type="password" name="password" id="password"
            class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" required
            style="width: 100%; padding: 8px; font-size: 1rem; border: 1px solid #ccc; border-radius: 4px; margin-top: 8px;">

          @error('password')
        <p class="invalid-feedback" style="font-size: 0.875rem; color: #e53e3e; font-weight: bold;">
        {{ $message }}
        </p>
      @enderror
          </div>

          <div class="form-group">
          <label for="password_confirmation" style="font-size: 1rem; font-weight: bold; color: #333;">Confirm
            password</label>
          <input type="password" name="password_confirmation" id="password_confirmation"
            class="form-control @error('password_confirmation') is-invalid @enderror"
            value="{{ old('password_confirmation') }}" required
            style="width: 100%; padding: 8px; font-size: 1rem; border: 1px solid #ccc; border-radius: 4px; margin-top: 8px;">

          @error('password_confirmation')
        <p class="invalid-feedback" style="font-size: 0.875rem; color: #e53e3e; font-weight: bold;">
        {{ $message }}
        </p>
      @enderror
          </div>
          <div class="form-group">
          <label for="role" style="font-size: 1rem; font-weight: bold; color: #333;">Role</label>
          <select name="role" id="role" class="form-control @error('category') is-invalid @enderror" required>
            <option {{ old('role') == 0 ? 'selected' : '' }} value="0">User</option>
            <option {{ old('role') == 1 ? 'selected' : '' }} value="1">Admin</option>
          </select>
          @error('role')
        <p class="invalid-feedback" style="font-size: 0.875rem; color: #e53e3e; font-weight: bold;">
        {{ $message }}
        </p>
      @enderror
          </div>
          <button type="submit" class="btn btn-primary">Create</button>
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