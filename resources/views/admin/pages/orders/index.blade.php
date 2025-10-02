@extends('admin.layouts.app')

@push('style')
  <style>
    .custom-modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1050;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  </style>
@endpush

@section('content')

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

  <div class="d-flex justify-content-center align-items-center mt-4">
    <form action="" method="post" class="mx-3">
      @csrf
      <select name="filter_status" class="form-control" onchange="handleChangeStatus(event)">
        <option {{ $filterStatus === 'all' ? 'selected' : '' }} value="all">Tất cả</option>
        <option {{ $filterStatus === '1' ? 'selected' : '' }} value="1">Chờ xử lý</option>
        <option {{ $filterStatus === '2' ? 'selected' : '' }} value="2">Đang giao</option>
        <option {{ $filterStatus === '3' ? 'selected' : '' }} value="3">Hoàn thành</option>
        <option {{ $filterStatus === '4' ? 'selected' : '' }} value="4">Đã hủy</option>
      </select>
    </form>
    <form action="" method="post" class="mx-3 col-md-3">
      @csrf
      <input onkeydown="handleSearch(event)" type="text" name="search" placeholder="Tìm kiếm mã đơn hàng..."
        class="form-control">
    </form>
  </div>

  @if ($orders->isNotEmpty())

    <section class="mt-4">
      <table class="table table-bordered table-hover table-sm bg-white">
        <thead class="thead-light text-uppercase">
          <tr>
            <th>#</th>
            <th>Tổng tiền</th>
            <th>Tình trạng</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($orders as $order)
            <tr>
              <td>{{ $order->id }}</td>
              <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
              <td class="{{ $order->payment_status === 1 ? 'text-success' : 'text-danger' }}"
                id="payment-status-{{ $order->id }}">
                {{ $order->payment_status ? 'Đã thanh toán' : 'Chưa thanh toán' }}
              </td>
              <td>
                <form action="{{ route('admin.orders.update', $order->id) }}" method="post">
                  @csrf
                  @method('PUT')
                  <select name="status" id="select-status-{{ $order->id }}" onChange="handleStatus(event)">
                    <option {{ $order->status === 1 ? 'selected' : ($order->status - 1 > 0 ? 'disabled' : '') }} value="1">Chờ
                      xử
                      lý</option>
                    <option {{ $order->status === 2 ? 'selected' : ($order->status - 2 > 0 ? 'disabled' : '') }} value="2">Đang
                      giao</option>
                    <option {{ $order->status === 3 ? 'selected' : ($order->status - 3 > 0 ? 'disabled' : '') }} value="3">Hoàn
                      thành</option>
                    <option {{ $order->status === 4 ? 'selected' : ($order->status - 4 > 0 ? 'disabled' : '') }} value="4">Đã
                      hủy</option>
                  </select>
                </form>
              </td>
              <td>
                <button onclick="openModal({{ $order }})" class="btn btn-link text-primary p-0">Chi tiết</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </section>

    @if ($orders->count() > 0)
      <div class="d-flex justify-content-center mt-4">
        {{ $orders->links('pagination::bootstrap-4') }}
      </div>


      <div id="order-modal" class="custom-modal-overlay d-none">
        <div class="modal-dialog modal-lg">
          <div class="modal-content p-4 position-relative">
            <button type="button" class="close position-absolute" style="top: 10px; right: 15px;" onclick="closeModal()">
              <span>&times;</span>
            </button>

            <h5 class="modal-title mb-4">Chi tiết đơn hàng #<span id="modal-order-id"></span></h5>

            <ul class="list-unstyled small mb-4">
              <li><strong>Khách hàng:</strong> <span id="modal-customer-name"></span></li>
              <li><strong>Phone:</strong> <span id="modal-phone"></span></li>
              <li><strong>Trạng thái:</strong> <span id="modal-status"></span></li>
              <li><strong>Ngày đặt:</strong> <span id="modal-created-at"></span></li>
            </ul>

            <div class="table-responsive">
              <table class="table table-bordered table-sm">
                <thead class="thead-light">
                  <tr>
                    <th>Sản phẩm</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-right">Thành tiền</th>
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
            </div>

            <div id="modal-financial-info" class="small mt-3"></div>

            <div class="text-right mt-4">
              <button onclick="closeModal()" class="btn btn-primary">Đóng</button>
            </div>
          </div>
        </div>
      </div>



    @endif
  @else
    Chưa có hóa đơn nào
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
          statusClass = 'text-primary-600 font-semibold';
          break;
        case '4':
          statusText = 'Đã hủy';
          statusClass = 'text-red-600 font-semibold';
          break;
        default:
          statusText = 'Không xác định';
          statusClass = '';
      }
      statusSpan.textContent = statusText;
      statusSpan.className = statusClass;

      // Render bảng sản phẩm
      let tableBodyContent = '';
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
                                          </tr>
                                        `;
      });
      document.getElementById('modal-products').innerHTML = tableBodyContent;

      // Thêm thông tin tài chính
      const shippingFee = order['shipping_fee'] || 0;
      const totalAmount = order['total_amount'] || 0;
      const paymentStatus = order['payment_status'] === 1 ? 'Đã thanh toán' : 'Chưa thanh toán';
      const paymentMethod = order['payment_method'] === 1 ? 'Tiền mặt' : 'Online';

      // Tạo dòng hiển thị dưới bảng
      const financialInfo = `
                                        <div class="text-sm space-y-2 mt-4">
                                          <p><strong>Phí vận chuyển:</strong> ${new Intl.NumberFormat('vi-VN').format(shippingFee)} đ</p>
                                          <p><strong>Tổng tiền:</strong> ${new Intl.NumberFormat('vi-VN').format(totalAmount)} đ</p>
                                          <p><strong class="text-black">Trạng thái thanh toán:</strong>
                                            <span  class="${paymentStatus === 'Đã thanh toán' ? 'text-success' : 'text-danger'}"> ${paymentStatus}</span></p>
                                          <p><strong>Hình thức thanh toán:</strong> ${paymentMethod}</p>
                                        </div>
                                      `;

      // Chèn vào sau bảng
      document.querySelector('#modal-financial-info').innerHTML = financialInfo;

      // Hiển thị modal
      document.getElementById('order-modal').classList.remove('d-none');
    }
    function closeModal() { document.getElementById('order-modal').classList.add('d-none'); }
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

    function handleStatus(e) {
      const value = e.target.value;
      const formSelec = e.target.form;
      formSelec.submit();
    }

  </script>


  <script>
    document.addEventListener('DOMContentLoaded', function () {

      window.Echo.private(`admin.order-status`)
        .listen('.OrderStatusEvent', (e) => {
          console.log(e);


          if (e.status === 'success') {
            const selectStatus = document.getElementById(`select-status-${e.orderId}`);
            if (selectStatus) {

              selectStatus.value = e.orderStatus;
              for (const option of selectStatus.options) {
                if (option.value !== selectStatus.value) {
                  option.disabled = true;
                }
              }
            }
            if (e.orderStatus === 4) {
              const paymentMethod = document.getElementById('payment-status-' + e.orderId)
              if (paymentMethod) {
                paymentMethod.textContent = 'Chưa thanh toán';
                paymentMethod.setAttribute('style', 'color: red !important');
              }

            }
          }
        });

    });
  </script>

@endpush