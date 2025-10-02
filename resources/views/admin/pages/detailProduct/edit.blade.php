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
              <strong class="card-title">{{ $detailProduct->title }}</strong>
            </div>
            <div class="card-body">

              <form action="{{ route('admin.detail-products.update', $detailProduct->id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                  <label for="price" class="font-weight-bold">Price</label>
                  <input type="number" min="0" name="price" id="price"
                    class="form-control @error('price') is-invalid @enderror" value="{{ $detailProduct->price }}"
                    required>
                  @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="sale_price" class="font-weight-bold">Sale price</label>
                  <input type="number" min="0" name="sale_price" id="sale_price"
                    class="form-control @error('sale_price') is-invalid @enderror"
                    value="{{ $detailProduct->sale_price }}" required>
                  @error('sale_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="color" class="font-weight-bold">Color</label>
                  <input type="text" name="color" id="color" class="form-control @error('color') is-invalid @enderror"
                    value="{{ $detailProduct->color }}" required>
                  @error('color')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="version" class="font-weight-bold">Version</label>
                  <input type="text" name="version" id="version"
                    class="form-control @error('version') is-invalid @enderror" value="{{ $detailProduct->version }}">
                  @error('version')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="images" class="font-weight-bold">Images</label>

                  <div id="images-render" class="d-flex flex-wrap">
                    @foreach ($detailProduct->images as $image)
                      <div class="image-wrapper position-relative m-2">
                        <img src="{{ asset('storage/' . $image->path) }}" class="editable-image"
                          data-image-id="{{ $image->id }}" style="max-width: 150px; object-fit: cover; cursor: pointer;">

                        <input type="file" hidden class="image-input" accept="image/*" name="images[{{ $image->id }}]">

                        <!-- Nút xóa ảnh cũ -->
                        <button type="button" class="remove-image-btn" data-id="{{ $image->id }}"
                          style="position: absolute; top: 5px; right: 5px; background: rgba(0,0,0,0.6); color: #fff; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer;">
                          ❌
                        </button>
                      </div>
                    @endforeach
                  </div>

                  <!-- Thêm ảnh mới -->
                  <div class="image-wrapper position-relative m-2">
                    <label for="new-images" class="d-block"
                      style="width: 150px; height: 150px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                      <span class="text-muted">+ Thêm ảnh</span>
                    </label>
                    <input type="file" id="new-images" class="d-none" accept="image/*" multiple>
                  </div>
                  <div id="images-deleted">

                  </div>
                </div>

                {{-- specfication --}}
                <div class="my-4">
                  <h5 class="mb-3">Specifications</h5>
                  <div id="spec_content">
                    @if ($detailProduct->specifications->isNotEmpty())
                      @foreach ($detailProduct->specifications as $spec)
                        <input type="hidden" name="spec_id[]" value="{{ $spec->id }}">
                        <div class="form-row align-items-end">
                          <div class="form-group col-md-6">
                            <label for="spec_name" class="font-weight-bold">Specification Name</label>
                            <input type="text" name="spec_name[]" id="spec_name"
                              class="form-control @error('spec_name') is-invalid @enderror" value="{{ $spec->spec_name }}">
                            @error('spec_name')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>

                          <div class="form-group col-md-6">
                            <label for="spec_value" class="font-weight-bold">Specification Value</label>
                            <input type="text" name="spec_value[]" id="spec_value"
                              class="form-control @error('spec_value') is-invalid @enderror" value="{{ $spec->spec_value }}">
                            @error('spec_value')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
                        </div>
                      @endforeach

                    @endif
                  </div>

                  <button id="spec_btn" type="button" class="btn btn-outline-primary btn-sm mt-2">
                    <i class="fas fa-plus"></i> Add Specification
                  </button>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
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
        const imageWrappers = document.querySelectorAll('.image-wrapper');

        imageWrappers.forEach(wrapper => {
          const img = wrapper.querySelector('.editable-image');
          const input = wrapper.querySelector('.image-input');

          if (img && input) {
            // Khi click vào ảnh, kích hoạt input file
            img.addEventListener('click', () => {
              input.click();
            });

            // Khi chọn ảnh mới
            input.addEventListener('change', function (event) {
              const file = event.target.files[0];
              if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                  img.src = e.target.result; // Thay ảnh hiển thị
                };
                reader.readAsDataURL(file);
              }
            });
          }
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
            class="form-control @error('spec_name') is-invalid @enderror" value="{{ $detailProduct->spec_name }}"
            >
            @error('spec_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>

            <div class="form-group col-md-6">
            <label for="spec_value" class="font-weight-bold">Specification Value</label>
            <input type="text" name="spec_value[]" id="spec_value"
            class="form-control @error('spec_value') is-invalid @enderror" value="{{ $detailProduct->spec_value }}"
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

    <script>
      document.getElementById('new-images').addEventListener('change', function (event) {
        const files = event.target.files;
        const container = document.getElementById('images-render');

        for (let i = 0; i < files.length; i++) {
          const file = files[i];
          const reader = new FileReader();

          reader.onload = function (e) {
            const wrapper = document.createElement('div');
            wrapper.classList.add('image-wrapper', 'position-relative', 'm-2');

            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '150px';
            img.style.objectFit = 'cover';

            const removeBtn = document.createElement('button');
            removeBtn.innerHTML = '❌';
            removeBtn.type = 'button';
            removeBtn.style.position = 'absolute';
            removeBtn.style.top = '5px';
            removeBtn.style.right = '5px';
            removeBtn.style.background = 'rgba(0,0,0,0.6)';
            removeBtn.style.color = '#fff';
            removeBtn.style.border = 'none';
            removeBtn.style.borderRadius = '50%';
            removeBtn.style.width = '24px';
            removeBtn.style.height = '24px';
            removeBtn.style.cursor = 'pointer';

            removeBtn.addEventListener('click', () => {
              wrapper.remove();
            });

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'file';
            hiddenInput.name = 'images[]';
            hiddenInput.accept = 'image/*';
            hiddenInput.style.display = 'none';

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            hiddenInput.files = dataTransfer.files;

            wrapper.appendChild(img);
            wrapper.appendChild(removeBtn);
            wrapper.appendChild(hiddenInput);
            container.appendChild(wrapper);
          };

          reader.readAsDataURL(file);
        }

        event.target.value = '';
      });

      // Xử lý nút ❌ ảnh cũ
      document.querySelectorAll('.remove-image-btn').forEach(btn => {
        btn.addEventListener('click', function () {
          const imageId = this.dataset.id;

          // Tạo input hidden để báo xóa ảnh này
          const deleteInput = document.createElement('input');
          deleteInput.type = 'hidden';
          deleteInput.name = 'delete_images[]';
          deleteInput.value = imageId;

          document.querySelector('#images-deleted').appendChild(deleteInput);

          // Xóa ảnh khỏi giao diện
          this.closest('.image-wrapper').remove();
        });
      });
    </script>





  @endpush