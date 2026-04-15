<?php
use App\Services\UserServices;
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('dist/img/favicon.ico') }}" type="image/x-icon">

    <title>
        {{ config('app.name') }}
        @if (isset($title))
            {{ ' | ' . $title }}
        @endif

    </title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <!-- overlayScrollbars -->
    <link href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}" rel="stylesheet">
    <!-- Theme style -->

    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.css') }}">
    <link href="{{ asset('dist/css/adminlte.min.css') }}" rel="stylesheet">

    <!-- Latest compiled and minified CSS -->
    {{-- <link rel="stylesheet" href = "{{ asset('dist/bootstrap-select/css/bootstrap-select.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('dist/font-awesome/css/font-awesome.min.css') }}">

    @livewireStyles
</head>
{{--  <body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed"> --}}

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

    <div class="wrapper  @if (UserServices::GetUserRightAccess('show-on-small-device')) @else hide-on-small @endif">
        <!-- Preloader -->
        @livewire('Layouts.Header')
        @livewire('Layouts.MainSidebar')

        {{ $slot }}
        {{-- <aside class="control-sidebar control-sidebar-dark">
        </aside> --}}

        {{-- @livewire('Layouts.Footer') --}}
    </div>



    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>

    <!-- PAGE PLUGINS -->
    <!-- jQuery Mapael -->

    <script src="{{ asset('plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>

    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>


    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>

    @livewireScripts
    @livewire('ChangePasswordModal');
    @livewire('CalculatorModal')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const pushMenuButton = document.querySelector('[data-widget="pushmenu"]');
            if (pushMenuButton && window.innerWidth >= 600) {
                // Check if the screen width is 768px or smaller
                pushMenuButton.click(); // Simulate a click on the button
            }
        });
    </script>

</body>

</html>
