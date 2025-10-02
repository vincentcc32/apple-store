@extends('admin.layouts.app')

@push('style')
  <!-- Bạn có thể thêm CSS tùy chỉnh của bạn tại đây -->
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
              <strong class="card-title">Create Product</strong>
            </div>

            <div class="card-body">
              <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                  <label for="title" class="font-weight-bold">Title</label>
                  <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                    value="{{ old('title') }}" required>
                  @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="description" class="font-weight-bold">Description</label>
                  <textarea name="description" id="description" rows="6"
                    class="form-control @error('description') is-invalid @enderror">
        {{ old('description') }}
        </textarea>
                  @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="thumbnail" class="font-weight-bold">Thumbnail</label>
                  <input onchange="loadFile(event)" type="file" name="thumbnail" id="thumbnail"
                    class="form-control-file @error('thumbnail') is-invalid @enderror" accept="image/*" required>
                  <img id="output" class="mt-4" style="width: 50%" />
                  @error('thumbnail')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="category" class="font-weight-bold">Category</label>
                  <select name="category" id="category" class="form-control @error('category') is-invalid @enderror"
                    required>
                    <!-- Assuming categories variable is passed -->
                    @foreach ($categories as $category)
                      <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                      </option>
                    @endforeach
                  </select>
                  @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>


                <div class="form-group">
                  <label for="status" class="font-weight-bold">Stutus</label>
                  <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                    <option value="1">Kinh doanh</option>
                    <option value="0">Ngừng sản xuất</option>
                  </select>
                  @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
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
  <script src="https://cdn.tiny.cloud/1/sqn1s6t2fd9lf0mw0arhxuno28dchskazwb0kri3br6cr853/tinymce/7/tinymce.min.js"
    referrerpolicy="origin"></script>

  <script>
    let loadFile = function (event) {
      let output = document.getElementById('output');
      output.src = URL.createObjectURL(event.target.files[0]);
      output.onload = function () {
        URL.revokeObjectURL(output.src); // free memory
      }
    };
  </script>



  <script>
    document.addEventListener('DOMContentLoaded', function () {
      tinymce.init({
        selector: 'textarea#description',
        height: 800,
        plugins: ['advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount'],
        toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
        setup: function (editor) {
          editor.on('init', function () {
            // Set old('content') into TinyMCE if available
            @if(old('content'))
              editor.setContent(`{!! old('content') !!}`);
            @endif
        })
        }
      });
    });
  </script>
@endpush