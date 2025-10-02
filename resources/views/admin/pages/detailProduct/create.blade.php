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
              {{ $errors->first() }}
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
              <strong class="card-title">Create product</strong>
            </div>
            <div class="card-body">

              <form action="{{ route('admin.detail-products.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="slug" value="{{$type }}">
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="form-group">
                  <label for="price" class="font-weight-bold">Price</label>
                  <input type="number" min="0" name="price" id="price"
                    class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                  @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="sale_price" class="font-weight-bold">Sale price</label>
                  <input type="number" min="0" name="sale_price" id="sale_price"
                    class="form-control @error('sale_price') is-invalid @enderror" value="{{ old('sale_price') }}"
                    required>
                  @error('sale_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="color" class="font-weight-bold">Color</label>
                  <input type="text" name="color" id="color" class="form-control @error('color') is-invalid @enderror"
                    value="{{ old('color') }}" required>
                  @error('color')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="version" class="font-weight-bold">Version</label>
                  <input type="text" name="version" id="version"
                    class="form-control @error('version') is-invalid @enderror" value="{{ old('version') }}">
                  @error('version')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="images" class="font-weight-bold">Images</label>
                  <input type="file" class="form-control-file @error('images') is-invalid @enderror" id="images"
                    name="images[]" multiple accept="image/*" required>
                  <div id="images-render">

                  </div>
                </div>

                {{-- specfication --}}
                <div class="my-4">
                  <h5 class="mb-3">Specifications</h5>

                  <div id="spec_content">
                    <div class="form-row align-items-end">
                      <div class="form-group col-md-6">
                        <label for="spec_name" class="font-weight-bold">Specification Name</label>
                        <input type="text" name="spec_name[]" id="spec_name"
                          class="form-control @error('spec_name') is-invalid @enderror" value="{{ old('spec_name') }}">
                        @error('spec_name')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="form-group col-md-6">
                        <label for="spec_value" class="font-weight-bold">Specification Value</label>
                        <input type="text" name="spec_value[]" id="spec_value"
                          class="form-control @error('spec_value') is-invalid @enderror" value="{{ old('spec_value') }}">
                        @error('spec_value')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  </div>

                  <button id="spec_btn" type="button" class="btn btn-outline-primary btn-sm mt-2">
                    <i class="fas fa-plus"></i> Add Specification
                  </button>
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
              </form>

            </div>
          </div>


        </div>
      </div><!-- .animated -->
    </div>

@endsection

  @push('script')


    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const inputImages = document.getElementById('images');
        const previewContainer = document.createElement('div');
        inputImages.insertAdjacentElement('afterend', previewContainer);

        let dt = new DataTransfer();

        inputImages.addEventListener('change', function (event) {
          previewContainer.innerHTML = '';
          dt = new DataTransfer(); // reset lại cho mỗi lần chọn ảnh mới

          const files = Array.from(event.target.files);

          files.forEach((file) => {
            const reader = new FileReader();

            reader.onload = function (e) {
              const wrapper = document.createElement('div');
              wrapper.style.position = 'relative';
              wrapper.style.display = 'inline-block';

              const imgElement = document.createElement('img');
              imgElement.src = e.target.result;
              imgElement.style.maxWidth = '150px';
              imgElement.style.margin = '10px';
              imgElement.style.objectFit = 'cover';
              imgElement.style.borderRadius = '6px';
              imgElement.style.border = '1px solid #ccc';

              const closeButton = document.createElement('span');
              closeButton.innerHTML = '×';
              closeButton.style.position = 'absolute';
              closeButton.style.top = '2px';
              closeButton.style.right = '6px';
              closeButton.style.background = 'red';
              closeButton.style.color = 'white';
              closeButton.style.borderRadius = '50%';
              closeButton.style.width = '20px';
              closeButton.style.height = '20px';
              closeButton.style.display = 'flex';
              closeButton.style.justifyContent = 'center';
              closeButton.style.alignItems = 'center';
              closeButton.style.cursor = 'pointer';
              closeButton.style.fontSize = '14px';
              closeButton.title = 'Xóa ảnh';

              // Khi click nút x, xóa ảnh khỏi preview và DataTransfer
              closeButton.addEventListener('click', function () {
                wrapper.remove();

                // Tạo lại DataTransfer mới, bỏ file bị xóa
                const newDt = new DataTransfer();
                Array.from(dt.files).forEach(f => {
                  if (f !== file) {
                    newDt.items.add(f);
                  }
                });

                dt = newDt;
                inputImages.files = dt.files;
              });

              dt.items.add(file);
              inputImages.files = dt.files;

              wrapper.appendChild(imgElement);
              wrapper.appendChild(closeButton);
              previewContainer.appendChild(wrapper);
            };

            reader.readAsDataURL(file);
          });
        });
      });
    </script>



    <script>

      const specContent = document.getElementById('spec_content');
      const specBtn = document.getElementById('spec_btn');
      const specHtml = `
       <div class="form-row align-items-end">
        <div class="form-group col-md-6">
        <label for="spec_name" class="font-weight-bold">Specification Name</label>
        <input type="text" name="spec_name[]" id="spec_name"
        class="form-control @error('spec_name') is-invalid @enderror" value="{{ old('spec_name') }}"
        >
        @error('spec_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        </div>

        <div class="form-group col-md-6">
        <label for="spec_value" class="font-weight-bold">Specification Value</label>
        <input type="text" name="spec_value[]" id="spec_value"
        class="form-control @error('spec_value') is-invalid @enderror" value="{{ old('spec_value') }}"
        >
        @error('spec_value')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        </div>
        </div>
      `;


      specBtn.onclick = () => {
        specContent.insertAdjacentHTML('beforeend', specHtml);
      }

    </script>

  @endpush