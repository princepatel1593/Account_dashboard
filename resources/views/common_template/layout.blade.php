<!-- resources/views/common_template/layout.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Admin Panel')</title>
    <!-- Add CSS and JS includes here -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS (CDN) -->
    <link rel="stylesheet" type="text/css" href="../../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../../assets/css/bootstrap-responsive.min.css">
    <link rel="stylesheet" type="text/css" href="../../../assets/styles.css">
    @yield('styles') <!-- This loads styles only when provided -->

</head>
    
<body>
    @include('common_template.header')

    <div class="container-fluid">
        <div class="row-fluid">
            @include('common_template.sidebar')

            <div class="span9" id="content">
                @yield('content')  <!-- Page content goes here -->
            </div>
        </div>
    </div>

    <hr>
    @include('common_template.footer')
    <script src="{{ asset('assets/js/jquery-1.9.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script> --}}
    {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}

    @yield('scripts')
</body>
</html>
