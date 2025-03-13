<!doctype html>
<html class="no-js" lang="en">


<!-- Mirrored from htmldemo.net/jadusona/jadusona/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 26 Jan 2024 05:25:04 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Jadusona - eCommerce Baby shop Bootstrap5 Template</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="/client/assets/images/favicon.ico">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @extends('client.layouts.partials.css')
    @extends('client.layouts.partials.js')
    @vite('resources/js/app.js')

</head>

<body>

    <div class="main-wrapper">

        <header>

            <!-- Header Section Start -->
            <div class="header-section section">

                @include('client.layouts.partials.header-top')

                @include('client.layouts.partials.header-bottom')

            </div>
            <!-- Header Section End -->

        </header>

        @yield('content')

        @include('client.layouts.partials.brand-section')
        <script>
            window.userId = @json(auth()->id()); // Lấy user ID từ backend
            console.log(window.userId);
        </script>
        <footer>

            @include('client.layouts.partials.footer-top')

            @include('client.layouts.partials.footer-bottom')

        </footer>
    </div>



</body>


<!-- Mirrored from htmldemo.net/jadusona/jadusona/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 26 Jan 2024 05:25:27 GMT -->

</html>
