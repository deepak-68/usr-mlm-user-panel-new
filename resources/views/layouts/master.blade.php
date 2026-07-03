<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>{{ config('app.name') }} | @yield('title', 'User Panel') </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
   
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ url ('assets/images/favicon.ico')}}">

    <!-- plugin css -->
    <link href="{{ url ('assets/libs/jsvectormap/jsvectormap.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="{{ url ('assets/js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ url ('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ url ('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/treant-js/1.0/Treant.css">
    <link href="{{ url ('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')

</head>

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
    
    @include('components.top-header')
    @yield('content')
    @include('components.sidebar')

    </div>
 <!-- JAVASCRIPT -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ url ('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ url ('assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{ url ('assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{ url ('assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{ url ('assets/js/plugins.js')}}"></script>

    <!-- apexcharts -->
    <script src="{{ url ('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

    <!-- Vector map-->
    <script src="{{ url ('assets/libs/jsvectormap/jsvectormap.min.js')}}"></script>
    <script src="{{ url ('assets/libs/jsvectormap/maps/world-merc.js')}}"></script>

    <!-- Dashboard init -->
    <script src="{{ url ('assets/js/pages/dashboard.init.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/treant-js/1.0/Treant.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>


    <!-- App js -->
    <script src="{{ url ('assets/js/app.js')}}"></script>
    <script>
        function showToast(message, type = 'success') {
            const toast = document.getElementById('mainToast');
            const toastMessage = document.getElementById('toastMessage');
            toast.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info');
            toast.classList.add(type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : type === 'warning' ? 'bg-warning' : 'bg-info');
            toastMessage.textContent = message;
            new bootstrap.Toast(toast, { delay: 4000 }).show();
        }
    </script>
    @stack('scripts')
</body>

</html>
