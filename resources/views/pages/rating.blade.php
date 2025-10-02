@extends('layouts/app')

@push('style')

@endpush

@section('content')


  <div class="container mx-auto">
    <h1 class="text-3xl font-bold text-center text-white my-6">Đánh giá</h1>

    <form
      action="{{!isset($rating->id) ? route('rating.store', [$detailOrderID, $detailProductID]) : route('rating.update', $rating->id) }}"
      method="post" class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md space-y-5">
      @csrf

      @if (isset($rating->id))
        @method('PUT')
      @endif
      <!-- Star Rating -->
      <div class="flex justify-center text-yellow-400 text-3xl space-x-2 cursor-pointer">
        <i class="fas fa-star hover:scale-125 transition-transform duration-200" data-num="1"></i>
        <i class="{{!isset($rating->id) ? 'far' : ($rating->rating >= 2 ? 'fas' : 'far') }} fa-star hover:scale-125 transition-transform duration-200"
          data-num="2"></i>
        <i class="{{!isset($rating->id) ? 'far' : ($rating->rating >= 3 ? 'fas' : 'far') }} fa-star hover:scale-125 transition-transform duration-200"
          data-num="3"></i>
        <i class="{{!isset($rating->id) ? 'far' : ($rating->rating >= 4 ? 'fas' : 'far') }} fa-star hover:scale-125 transition-transform duration-200"
          data-num="4"></i>
        <i class="{{!isset($rating->id) ? 'far' : ($rating->rating >= 5 ? 'fas' : 'far') }} fa-star hover:scale-125 transition-transform duration-200"
          data-num="5"></i>
        <input type="hidden" name="rating" value="{{ !isset($rating->id) ? 1 : $rating->rating }}">
      </div>

      <!-- Comment -->
      <div>
        <label for="comment" class="block text-gray-700 font-medium mb-1">Bình luận của bạn</label>
        <textarea name="content" id="comment" rows="4" placeholder="Viết đánh giá tại đây..."
          class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-yellow-400">{{ !isset($rating->id) ? '' : $rating->content }}</textarea>
      </div>

      <!-- Submit Button -->
      <div class="text-center">
        <button type="submit"
          class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2 px-6 rounded-full transition-colors duration-200 ">
          Gửi
        </button>

        @if (isset($rating->id))
          <button onclick="handleDeleteRating(event)"
            class="bg-red-400 hover:bg-red-500 text-white font-semibold py-2 px-6 rounded-full transition-colors duration-200 ">
            Xóa
          </button>
        @endif

      </div>

    </form>

    @if (isset($rating->id))
      <form id="form-delete" action="{{ route('rating.destroy', $rating->id) }}" method="post">
        @csrf
        @method('DELETE')
      </form>
    @endif
  </div>


@endsection

@push('script')
  <script>
    const allstars = document.querySelectorAll('.fa-star')
    const ratingInput = document.querySelector('input[name="rating"]');
    allstars.forEach(star => {
      star.onclick = () => {
        let starlevel = star.getAttribute('data-num')
        console.log(starlevel);
        allstars.forEach(el => { //loop through stars again to compare the clicked star to all other stars
          if (starlevel < el.getAttribute('data-num') && el.getAttribute('data-num') != 1) {
            el.classList.remove('fas')
            el.classList.add('far')
            ratingInput.value = starlevel;
          }
          else {
            el.classList.remove('far')
            el.classList.add('fas')
            ratingInput.value = starlevel;
          }
        })
      }

    });


  </script>

  <script>

    function handleDeleteRating(e) {
      e.preventDefault();
      isDelete = confirm('Bạn có chắc chắn muốn xóa đánh giá này?');

      if (isDelete) {
        const formDelete = document.getElementById('form-delete');
        console.log(formDelete);

        formDelete.submit();
      }

    }


  </script>


@endpush