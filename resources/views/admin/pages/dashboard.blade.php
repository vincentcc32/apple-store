@extends('admin.layouts.app')

@push('style')

@endpush

@section('content')

  <h1>dashboard</h1>

  <div class="container mt-5">
    <div class="row">
      <!-- Card tổng user -->
      <div class="col-md-4 col-lg-3 mb-4">
        <div class="card shadow-sm bg-success color-white">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted color-white">Tổng số người dùng</h6>
              <h3 id="totalUsers" class="font-weight-bold">{{ $totalUser }}</h3>
            </div>
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
              style="width:50px;height:50px;">
              👤
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-lg-3 mb-4">
        <div class="card shadow-sm bg-success color-white">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted color-white">Tổng số sản phẩm</h6>
              <h3 id="totalUsers" class="font-weight-bold">{{ $totalProduct }}</h3>
            </div>
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
              style="width:50px;height:50px;">
              📦
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-lg-3 mb-4">
        <div class="card shadow-sm bg-success color-white">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted color-white">Tổng đơn hàng</h6>
              <h3 id="totalUsers" class="font-weight-bold">{{ $totalOrder }}</h3>
            </div>
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
              style="width:50px;height:50px;">
              🛍️
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-lg-3 mb-4">
        <div class="card shadow-sm bg-success color-white">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted color-white">Tổng danh mục</h6>
              <h3 id="totalUsers" class="font-weight-bold">{{ $totalCategory }}</h3>
            </div>
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
              style="width:50px;height:50px;">
              🏷️
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-lg-3 mb-4">
        <div class="card shadow-sm bg-success color-white">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted color-white">Tổng cửa hàng</h6>
              <h3 id="totalUsers" class="font-weight-bold">{{ $totalStore }}</h3>
            </div>
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
              style="width:50px;height:50px;">
              🏠
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs d-flex justify-content-center mt-3" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab">Orders</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab">Users</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="tab3-tab" data-toggle="tab" href="#tab3" role="tab">Price</a>
      </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content mt-3">
      <div class="tab-pane fade show active" id="tab1" role="tabpanel">
        <canvas id="total-order-by-month"></canvas>
      </div>
      <div class="tab-pane fade" id="tab2" role="tabpanel">
        <canvas id="total-user-by-month"></canvas>
      </div>
      <div class="tab-pane fade" id="tab3" role="tabpanel">
        <canvas id="price-by-month"></canvas>
      </div>
    </div>


  </div>


@endsection

@push('script')


  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    const totalOrderByMonthElement = document.getElementById('total-order-by-month');
    new Chart(totalOrderByMonthElement, {
      type: 'bar',
      data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'july', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
          label: 'Order by month',
          data: @json(array_values($orderTotalByMonthData)),
          borderWidth: 1,
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(255, 205, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(201, 203, 207, 0.2)',
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(255, 205, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
          ],
          borderColor: [
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)',
            'rgb(201, 203, 207)',
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
          ],
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });


  </script>

  <script>
    const totalUserByMonthElement = document.getElementById('total-user-by-month');
    new Chart(totalUserByMonthElement, {
      type: 'bar',
      data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'july', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
          label: 'User by month',
          data: @json(array_values($userTotalByMonthData)),
          borderWidth: 1,
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(255, 205, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(201, 203, 207, 0.2)',
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(255, 205, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
          ],
          borderColor: [
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)',
            'rgb(201, 203, 207)',
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
          ],
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });


  </script>

  <script>
    const priceByMonthElement = document.getElementById('price-by-month');
    new Chart(priceByMonthElement, {
      type: 'bar',
      data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'july', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
          label: 'Price by month',
          data: @json(array_values($priceByMonthData)),
          borderWidth: 1,
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(255, 205, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(201, 203, 207, 0.2)',
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(255, 205, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
          ],
          borderColor: [
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)',
            'rgb(201, 203, 207)',
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
          ],
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });


  </script>

@endpush