<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '/')</title>

    @include('admin.layouts.css')
</head>

<body class="hold-transition sidebar-mini layouts-fixed">
    <div class="wrapper">

        <header>
            <!-- Preloader -->
            @include('admin.layouts.preloader')


            <!-- Navbar -->
            @include('admin.layouts.navbar')
            <!-- /.navbar -->
        </header>


        <main>
            <!-- Main Sidebar Container -->
            @include('admin.layouts.sidebar')


            <!-- Content Wrapper. Contains page content -->
            @yield('content')

        </main>


        @include('admin.layouts.footer')

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    @include('admin.layouts.js')

</body>

</html>
