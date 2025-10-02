@component('mail::message')
# Cảm ơn bạn đã đặt hàng tại Apple Store!

Xin chào {{ $order['customer_name'] }},

Chúng tôi rất cảm ơn bạn vì đã tin tưởng và đặt hàng tại **Apple Store**. Đơn hàng của bạn đã được ghi nhận và chúng tôi
sẽ sớm xử lý để giao đến bạn trong thời gian sớm nhất.

**Thông tin đơn hàng:**
- Mã đơn hàng: {{ $order['id'] }}
- Ngày đặt hàng: {{ $order['created_at'] }}
- Tổng giá trị: {{ number_format($order['total_amount'], 0, ',', '.') . 'đ' }}

{{-- @foreach ($order_items as $item)
Tên sản phẩm: {{ $item->name }}
Số lượng: {{ $item->quantity }}
Giá: {{ $item->price }}
@endforeach --}}

Nếu bạn có bất kỳ câu hỏi nào hoặc cần hỗ trợ thêm, vui lòng liên hệ với bộ phận chăm sóc khách hàng của chúng tôi.

Một lần nữa, cảm ơn bạn đã mua sắm tại Apple Store!

Trân trọng,
**Apple Store**
@endcomponent