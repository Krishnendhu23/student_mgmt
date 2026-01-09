<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>@yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token for AJAX -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

  <!-- Scripts -->
  <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>

  <!-- validation -->
  <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>

  <style>
    .sort-icons {
      display: inline-flex;
      flex-direction: column;
      /* stack vertically */
      margin-left: 5px;
      /* spacing from column text */
    }

    .sort-icons i {
      font-size: 10px;
      /* smaller icons */
      line-height: 10px;
      /* reduce spacing */
      color: #ccc;
      /* default inactive */
    }

    .sort-icons i.active {
      color: #007bff;
      /* highlight active sort */
    }
  </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    @include('layouts.header')
    @include('layouts.sidebar')

    <div class="content-wrapper">
      <section class="content pt-3 px-3">
        @yield('content')
      </section>
    </div>

    <footer class="main-footer text-center">
      <strong>Student Management Portal</strong> &copy; {{ date('Y') }}
    </footer>
  </div>

  <!-- Bootstrap 4 -->
  <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>

  <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

  @stack('scripts')
  <script>
    $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
      if (jqxhr.status === 401) {
        // unset the token
        localStorage.removeItem("access_token");

        // Redirect to login page if unauthorized
        window.location.href = "{{ route('login') }}";
      }
    });

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  </script>
</body>

</html>