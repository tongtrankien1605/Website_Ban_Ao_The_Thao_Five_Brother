<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '/')</title>

    @include('admins.layout.css')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <header>
            <!-- Preloader -->
            @include('admins.layout.preloader')


            <!-- Navbar -->
            @include('admins.layout.navbar')
            <!-- /.navbar -->
        </header>


        <main>
            <!-- Main Sidebar Container -->
            @include('admins.layout.sidebar')


            <!-- Content Wrapper. Contains page content -->
            @yield('content')

        </main>


        @include('admins.layout.footer')

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    @include('admins.layout.js')

</body>

</html>
