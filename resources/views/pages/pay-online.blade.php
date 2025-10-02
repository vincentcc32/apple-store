@extends('layouts.app')

@push('style')
@endpush

@section('content')
  <div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl mx-auto border border-gray-200">
      <h2 class="text-2xl font-semibold text-center text-green-600 mb-6">Phiếu Thanh Toán</h2>

      <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
        <div><span class="font-semibold">Số tiền:</span></div>
        <div>{{ number_format($paymentData['vnp_Amount'] / 100, 0, ',', '.') }} VNĐ</div>

        <div><span class="font-semibold">Mã giao dịch ngân hàng:</span></div>
        <div>{{ $paymentData['vnp_BankTranNo'] ?? 'N/A' }}</div>

        <div><span class="font-semibold">Ngân hàng:</span></div>
        <div>{{ $paymentData['vnp_BankCode'] ?? 'N/A' }}</div>

        <div><span class="font-semibold">Loại thẻ:</span></div>
        <div>{{ $paymentData['vnp_CardType'] ?? 'N/A' }}</div>

        <div><span class="font-semibold">Nội dung thanh toán:</span></div>
        <div>{{ urldecode($paymentData['vnp_OrderInfo']) ?? 'N/A' }}</div>

        <div><span class="font-semibold">Thời gian thanh toán:</span></div>
        <div>
          {{ \Carbon\Carbon::createFromFormat('YmdHis', $paymentData['vnp_PayDate'])->format('d/m/Y H:i:s') }}
        </div>

        <div><span class="font-semibold">Mã đơn hàng:</span></div>
        <div>{{ $paymentData['vnp_TxnRef'] ?? 'N/A' }}</div>

        <div><span class="font-semibold">Mã giao dịch VNPAY:</span></div>
        <div>{{ $paymentData['vnp_TransactionNo'] ?? 'N/A' }}</div>

        <div><span class="font-semibold">Tình trạng thanh toán:</span></div>
        <div>
          @if($paymentData['vnp_ResponseCode'] == '00' && $paymentData['vnp_TransactionStatus'] == '00')
            <span class="text-green-600 font-semibold">Thành công</span>
          @else
            <span class="text-red-600 font-semibold">Thất bại</span>
          @endif
        </div>
      </div>

      <div class="mt-8 text-center">
        <a href="{{ url('/') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
          Quay về trang chủ
        </a>
      </div>
    </div>
  </div>
@endsection

@push('script')
@endpush