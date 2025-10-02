<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang=""> <!--<![endif]-->

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
  @vite(['resources/css/admin.css'])

  @stack('style')

</head>

<body>
  <!-- Left Panel -->
  @include('admin.layouts.sidebar')
  <!-- /#left-panel -->
  <!-- Right Panel -->
  <div id="right-panel" class="right-panel">
    <!-- Header-->
    @include('admin.layouts.header')
    <!-- /#header -->
    <!-- Content -->
    <div class="content">

      @yield('content')

    </div>
    <!-- /.content -->
    <div class="clearfix"></div>
    <!-- Footer -->
    <footer class="site-footer">
      <div class="footer-inner bg-white">
        <div class="row">
          <div class="col-sm-6">
            Apple Store
          </div>
          <div class="col-sm-6 text-right">
            Designed by <a href="https://colorlib.com">Vincent</a>
          </div>
        </div>
      </div>
    </footer>
    <!-- /.site-footer -->
  </div>
  <!-- /#right-panel -->

  <div id="toastContainer" class="bg-success text-white"
    style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>



  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
  @vite(['resources/js/admin.js'])

  @stack('script')


  <script>
    document.addEventListener("DOMContentLoaded", function () {

      window.Echo.private(`admin.order-status`)
        .listen('.OrderStatusEvent', (e) => {
          // console.log("Nhận event:", e);


          if (e.status === 'success') {
            const toastHTML = `
    <div class="toast px-4 py-2" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
      <div class="toast-header bg-success text-white">
        <strong class="mr-auto">Thông báo</strong>
        <small>Vừa xong</small>
        <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast">&times;</button>
      </div>
      <div class="toast-body">
        ${e.message}
      </div>
    </div>
  `;

            const container = document.getElementById('toastContainer');
            container.innerHTML = toastHTML;

            const toastEl = container.querySelector('.toast');
            toastEl.classList.add('show');

            setTimeout(() => {
              toastEl.classList.remove('show');
              toastEl.remove();
            }, 5000);
          }


          else {
            alert(e.message);
            // window.location.href = "/";
          }
        });
    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {

      window.Echo.private(`user-order`)
        .listen('.UserOrderEvent', (e) => {
          console.log("Nhận event:", e);


          if (e.status === 'success') {
            const toastHTML = `
    <div class="toast px-4 py-2" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
      <div class="toast-header bg-success text-white">
        <strong class="mr-auto">Thông báo</strong>
        <small>Vừa xong</small>
        <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast">&times;</button>
      </div>
      <div class="toast-body">
        Khách hàng đã mua đơn hàng ${e.orderId}
      </div>
    </div>
  `;

            const container = document.getElementById('toastContainer');
            container.innerHTML = toastHTML;

            const toastEl = container.querySelector('.toast');
            toastEl.classList.add('show');

            setTimeout(() => {
              toastEl.classList.remove('show');
              toastEl.remove();
            }, 5000);
          }
        });
    });
  </script>

</body>

</html>