@extends('layouts/app')

@push('style')

@endpush

@section('content')


  <div class="container mx-auto">

    <section class="text-center pt-10 mt-10 flex justify-center items-center gap-1 text-white">
      <i class="fa-brands fa-apple text-4xl"></i>
      <h1 class="text-4xl text-white font-bold ">Order</h1>
    </section>
    <div class="flex justify-center items-center gap-10 mt-10">
      <form action="" method="post">
        @csrf
        <select name="filter_status" id="" onchange="handleChangeStatus(event)">
          <option {{ $filterStatus === 'all' ? 'selected' : '' }} value="all">Tất cả</option>
          <option {{ $filterStatus === '1' ? 'selected' : '' }} value="1">Chờ xử lý</option>
          <option {{ $filterStatus === '2' ? 'selected' : '' }} value="2">Đang giao</option>
          <option {{ $filterStatus === '3' ? 'selected' : '' }} value="3">Hoàn thành</option>
          <option {{ $filterStatus === '4' ? 'selected' : '' }} value="4">Đã hủy</option>
        </select>
      </form>
      <form action="" method="post" class="w-1/4">
        @csrf
        <input onkeydown="handleSearch(event)" type="text" name="search" placeholder="Tìm kiếm mã đơn hàng..." id=""
          class="w-full">
      </form>
    </div>
    @if ($orders->isNotEmpty())

      <section class="mt-10 overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow-md overflow-hidden text-sm">
          <thead class="bg-gray-100 text-gray-600 uppercase text-left">
            <tr>
              <th class="px-6 py-3">#</th>
              <th class="px-6 py-3">Tổng tiền</th>
              <th class="px-6 py-3">Tình trạng</th>
              <th class="px-6 py-3">Trạng thái</th>
              <th class="px-6 py-3">Hành động</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            @foreach ($orders as $order)
              <tr class="border-b hover:bg-gray-50">
                <td class="px-6 py-4">{{ $order->id }}</td>
                <td class="px-6 py-4">{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                <td class="px-6 py-4 {{ $order->payment_status ? 'text-green-600' : 'text-red-600' }}"
                  id="payment-status-{{ $order->id  }}">
                  {{ $order->payment_status ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                </td>
                <td class="px-6 py-4" id="order-status-{{ $order->id  }}">
                  @if ($order->status === 1)
                    <span class="text-yellow-600 font-semibold">Chờ xử lý</span>
                  @elseif ($order->status === 2)
                    <span class="text-green-600 font-semibold">Đang giao</span>
                  @elseif ($order->status === 3)
                    <span class="text-blue-600 font-semibold">Hoàn thành</span>
                  @elseif ($order->status === 4)
                    <span class="text-red-600 font-semibold">Đã hủy</span>
                  @endif
                </td>
                <td class="px-6 py-4 flex gap-5">
                  <button onclick="openModal({{ $order }})" class="text-blue-600 hover:underline font-medium">Chi
                    tiết</button>
                  @if ($order->status === 1)
                    <form class="block" action="{{ route('orders.update', $order->id) }}" method="post"
                      onsubmit="handleCancelOrder(event)">
                      @csrf
                      @method('PUT')
                      <button class="text-red-600 hover:underline font-medium ml-2" id="btn-cancel-order-{{ $order->id }}">Hủy
                        đơn</button>
                    </form>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </section>
      @if ($orders->count() > 0)
        <div class="d-flex justify-content-center mt-4">
          {{ $orders->links() }}
        </div>


        <!-- Modal duy nhất -->
        <div id="order-modal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
          <div class="bg-white w-full max-w-2xl rounded-lg p-6 relative">
            <button onclick="closeModal()"
              class="absolute top-2 right-3 text-gray-600 hover:text-black text-xl">&times;</button>
            <h2 class="text-xl font-semibold mb-4">Chi tiết đơn hàng #<span id="modal-order-id"></span></h2>

            <ul class="text-sm space-y-2">
              <li><strong>Khách hàng:</strong> <span id="modal-customer-name"></span></li>
              <li><strong>Phone:</strong> <span id="modal-phone"></span></li>
              {{-- <li><strong>Tổng tiền:</strong> <span id="modal-total-amount"></span> đ</li> --}}
              <li><strong>Trạng thái:</strong> <span id="modal-status"></span></li>
              <li><strong>Ngày đặt:</strong> <span id="modal-created-at"></span></li>
            </ul>

            <table class="w-full text-sm border border-gray-300">
              <thead class="bg-gray-100">
                <tr>
                  <th class="border px-3 py-2 text-left">Sản phẩm</th>
                  <th class="border px-3 py-2 text-center">Số lượng</th>
                  <th class="border px-3 py-2 text-right">Đơn giá</th>
                  <th class="border px-3 py-2 text-right">Thành tiền</th>
                  <th class="border px-3 py-2 text-right">Đánh giá</th>
                </tr>
              </thead>
              <tbody id="modal-products">
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>

            <div id="modal-financial-info">

            </div>

            <div class="mt-6 text-right">
              <button onclick="closeModal()"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Đóng</button>
            </div>
          </div>
        </div>
      @endif
    @else
      không có hóa đơn nào!!!
    @endif


@endsection

  @push('script')

    <script>
      function openModal(order) {
        // console.log(order);

        // Thông tin đơn hàng cơ bản
        document.getElementById('modal-order-id').textContent = order['id'];
        document.getElementById('modal-customer-name').textContent = order['customer_name'] || 'Không có tên';
        document.getElementById('modal-phone').textContent = order['phone'];

        // Ngày đặt
        const createdAt = new Date(order['created_at']).toLocaleDateString('vi-VN', {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
          hour: 'numeric',
          minute: 'numeric',
          second: 'numeric'
        });
        document.getElementById('modal-created-at').textContent = createdAt;

        // Trạng thái đơn hàng
        const statusSpan = document.getElementById('modal-status');
        let statusText = '';
        let statusClass = '';
        switch (String(order['status'])) {
          case '1':
            statusText = 'Chờ xử lý';
            statusClass = 'text-yellow-600 font-semibold';
            break;
          case '2':
            statusText = 'Đang giao';
            statusClass = 'text-green-600 font-semibold';
            break;
          case '3':
            statusText = 'Hoàn thành';
            statusClass = 'text-blue-600 font-semibold';
            paymentText = 'Đã thanh toán';
            paymentClass = 'text-green-600';
            break;
          case '4':
            statusText = 'Đã hủy';
            statusClass = 'text-red-600 font-semibold';
            // document.getElementById('btn-cancel-order-' + order['id']).innerHTML = 'Chưa thanh toán';
            // document.getElementById('btn-cancel-order-' + order['id']).classList.add('text-red-600');
            break;
          default:
            statusText = 'Không xác định';
            statusClass = '';
        }
        statusSpan.textContent = statusText;
        statusSpan.className = statusClass;

        // Render bảng sản phẩm
        let tableBodyContent = '';
        // console.log(order);

        order['detail_orders'].forEach(detail => {
          const dp = detail['detail_product'];
          const product = dp['product'];
          const total = detail['quantity'] * dp['price'];

          tableBodyContent += `
                                                        <tr>
                                                          <td class="border px-3 py-2 text-left">
                                                            <div>
                                                              <div class="font-semibold">${product['title']}</div>
                                                              <div class="text-xs text-gray-500">${dp['color'].split('#')[0]} - ${dp['version']}</div>
                                                            </div>
                                                          </td>
                                                          <td class="border px-3 py-2 text-center">${detail['quantity']}</td>
                                                          <td class="border px-3 py-2 text-right">${new Intl.NumberFormat('vi-VN').format(dp['price'])} đ</td>
                                                          <td class="border px-3 py-2 text-right">${new Intl.NumberFormat('vi-VN').format(total)} đ</td>
                      ${order['status'] === 3 ? `
                            <td>
                              <a class="text-blue-600 hover:underline cursor-pointer" 
                                 href="/rating/${detail['id']}/${detail['detail_product_id']}">
                                 Đánh giá
                              </a>
                            </td>
                          ` : ""}
                                                        </tr >
                                              `;
        });
        document.getElementById('modal-products').innerHTML = tableBodyContent;

        // Thêm thông tin tài chính
        const shippingFee = order['shipping_fee'] || 0;
        const totalAmount = order['total_amount'] || 0;
        const paymentStatus = order['payment_status'] === 1 ? 'Đã thanh toán' : 'Chưa thanh toán';
        const paymentMethod = order['payment_method'] === 1 ? 'Tiền mặt' : 'Online';

        // Tạo dòng hiển thị dưới bảng
        const financialInfo = `<div class= "text-sm space-y-2 mt-4" >
                                          <p><strong>Phí vận chuyển:</strong> ${new Intl.NumberFormat('vi-VN').format(shippingFee)} đ</p>
                                          <p><strong>Tổng tiền:</strong> ${new Intl.NumberFormat('vi-VN').format(totalAmount)} đ</p>
                                          <p >
                                            <strong >Trạng thái thanh toán:</strong> 
                                            <span class="${order['payment_status'] === 1 ? 'text-green-600' : 'text-red-600'}">${paymentStatus}</span> 
                                            </p>
                                          <p><strong>Hình thức thanh toán:</strong> ${paymentMethod}</p>
                                        </div >
                                      `;

        // Chèn vào sau bảng
        document.querySelector('#modal-financial-info').innerHTML = financialInfo;

        // Hiển thị modal
        document.getElementById('order-modal').classList.remove('hidden');
        document.getElementById('order-modal').classList.add('flex');
      }
      function closeModal() {
        document.getElementById('order-modal').classList.add('hidden');
        document.getElementById('order-modal').classList.remove('flex');
      }
      const orderModal = document.getElementById('order-modal'); if (orderModal) { orderModal.addEventListener('click', function (e) { if (e.target === this) closeModal(); }); }

    </script>


    <script>
      function handleChangeStatus(e) {
        const value = e.target.value;
        const formSelec = e.target.form;
        formSelec.submit();

      }

      function handleSearch(e) {
        if (e.key === 'Enter') {
          e.preventDefault(); // chỉ chặn khi nhấn Enter

          const value = e.target.value.trim();
          const formSearch = e.target.form;

          if (value !== '') {
            formSearch.submit();
          }
        }
      }

      function handleCancelOrder(e) {
        e.preventDefault();

        const isCancelOrder = confirm('Bạn muốn hủy đơn hàng này?');
        if (isCancelOrder) {
          e.target.submit();
        }
      }

    </script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const userId = @json(Auth::user()->id);
        // console.log(userId);

        window.Echo.private(`order-status.${userId}`)
          .listen('.OrderStatusEvent', (e) => {
            // console.log('Received event:', e);


            if (e.status === 'success') {
              statusText = '';
              statusClass = '';
              switch (String(e.orderStatus)) {
                case '1':
                  statusText = 'Chờ xử lý';
                  statusClass = 'text-yellow-600 font-semibold';
                  break;
                case '2':
                  statusText = 'Đang giao';
                  statusClass = 'text-green-600 font-semibold';
                  break;
                case '3':
                  statusText = 'Hoàn thành';
                  statusClass = 'text-blue-600 font-semibold';

                  break;
                case '4':
                  statusText = 'Đã hủy';
                  statusClass = 'text-red-600 font-semibold';
                  break;
                default:
                  statusText = 'Không xác định';
                  statusClass = '';
              }
              const orderStatusSpan = document.getElementById('order-status-' + e.orderId);
              const paymentStatusSpan = document.getElementById('payment-status-' + e.orderId);
              if (paymentStatusSpan && e.orderStatus === 3) {
                paymentStatusSpan.innerHTML = `
                            <span class="text-green-600" > Đã thanh toán</span >
                           `;
              } else {
                paymentStatusSpan.innerHTML = `
                            <span class="text-red-600" > Chưa thanh toán</span >`

              }
              if (orderStatusSpan) {
                orderStatusSpan.innerHTML = `
                    < span class="${statusClass} font-semibold" > ${statusText}</ >
                                                                                                  `;

              }
              const btnCancelOrder = document.getElementById('btn-cancel-order-' + e.orderId);
              if (btnCancelOrder) {
                btnCancelOrder.remove();

              }

            }
          });
      });
    </script>


  @endpush