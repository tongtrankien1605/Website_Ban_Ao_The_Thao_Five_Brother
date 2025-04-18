<aside class="main-sidebar sidebar-light elevation-1">
    <!-- Brand Logo -->
    <a href="{{ route('admin.index') }}" class="brand-link text-dark">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-2">
        <span class="brand-text font-weight-medium">5Brother</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group">
                {{-- <input class="form-control form-control-sidebar border-0" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div> --}}
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
       with font-awesome or any other icon font library -->

                {{-- <i class="metismenu-icon pe-7s-rocket"></i> --}}

                <li class="nav-item">

                    <a href="{{ Route('admin.index') }}" class="nav-link">
                        <i class="fa-solid fa-rocket"></i>
                        <p>
                            Trang chủ

                        </p>
                    </a>

                </li>


                <li class="nav-item">

                    <a href="{{ route('admin.user.index') }}" class="nav-link">
                        <i class="fa-solid fa-users"></i>
                        <p>
                            Quản lý người dùng
                            {{-- <i class="right fas fa-angle-left"></i> --}}
                        </p>
                    </a>
                    {{-- <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.user.index') }}" class="nav-link">
                                <i class="fa-solid fa-list"></i>
                                <p>Danh sách người dùng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.user.create') }}" class="nav-link">
                                <i class="fa-solid fa-plus"></i>
                                <p>Thêm mới người dùng</p>
                            </a>
                        </li>
                    </ul> --}}
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.category.index') }}" class="nav-link">
                        <i class="fa-solid fa-table-list"></i>
                        <p>
                            Quản lý danh mục
                            {{-- <i class="right fas fa-angle-left"></i> --}}
                        </p>
                    </a>
                    {{-- <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.category.index') }}" class="nav-link">
                                <i class="fa-solid fa-list"></i>
                                <p>Danh sách danh mục</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.category.create') }}" class="nav-link">
                                <i class="fa-solid fa-plus"></i>
                                <p>Thêm mới danh mục</p>
                            </a>
                        </li>

                    </ul> --}}
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.brands.index') }}" class="nav-link">
                        <i class="fa-solid fa-copyright"></i>
                        <p>
                            Quản lý thương hiệu
                            {{-- <i class="right fas fa-angle-left"></i> --}}
                        </p>
                    </a>
                    {{-- <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.brands.index') }}" class="nav-link">
                                <i class="fa-solid fa-list"></i>
                                <p>Danh sách thương hiệu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.brands.create') }}" class="nav-link">
                                <i class="fa-solid fa-plus"></i>
                                <p>Thêm mới thương hiệu</p>
                            </a>
                        </li>
                    </ul> --}}
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.product_attribute.index') }}" class="nav-link">
                        <i class="fa-solid fa-sitemap"></i>
                        <p>
                            Quản lý thuộc tính
                            {{-- <i class="right fas fa-angle-left"></i> --}}
                        </p>
                    </a>
                    {{-- <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.product_attribute.index') }}" class="nav-link">
                                <i class="fa-solid fa-list"></i>
                                <p>Danh sách thuộc tính</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.product_attribute.create') }}" class="nav-link">
                                <i class="fa-solid fa-plus"></i>
                                <p>Thêm mới thuộc tính</p>
                            </a>
                        </li>

                    </ul> --}}
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.product.index') }}" class="nav-link">
                        <i class="fa-brands fa-product-hunt"></i>
                        <p>
                            Quản lý sản phẩm
                            {{-- <i class="right fas fa-angle-left"></i> --}}
                        </p>
                    </a>
                    {{-- <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.product.index') }}" class="nav-link">
                                <i class="fa-solid fa-list"></i>
                                <p>Danh sách sản phẩm</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.product.create') }}" class="nav-link">
                                <i class="fa-solid fa-plus"></i>
                                <p>Thêm mới sản phẩm</p>
                            </a>
                        </li>

                    </ul> --}}
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.vouchers.index') }}" class="nav-link">
                        <i class="fa-solid fa-ticket"></i>
                        <p>
                            Quản lý Voucher
                            {{-- <i class="right fas fa-angle-left"></i> --}}
                        </p>
                    </a>
                    {{-- <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.vouchers.index') }}" class="nav-link">
                                <i class="fa-solid fa-list"></i>
                                <p>Danh sách Voucher</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.vouchers.create') }}" class="nav-link">
                                <i class="fa-solid fa-plus"></i>
                                <p>Thêm mới Voucher</p>
                            </a>
                        </li>

                    </ul> --}}
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.posts.index') }}" class="nav-link">
                        <i class="fa-solid fa-newspaper"></i>
                        <p>
                            Quản lý bài viết
                            {{-- <i class="right fas fa-angle-left"></i> --}}
                        </p>
                    </a>
                    {{-- <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.posts.index') }}" class="nav-link">
                                <i class="bi bi-list-columns"></i>
                                <p>Danh sách bài viết</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.posts.create') }}" class="nav-link">
                                <i class="bi bi-folder-plus"></i>
                                <p>Thêm mới bài viết</p>
                            </a>
                        </li>

                    </ul> --}}
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <p>
                            Quản lý đơn hàng
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.skus.index') }}" class="nav-link">
                        <i class="fa-solid fa-warehouse"></i>
                        <p>
                            Nhập lượng sản phẩm
                            {{-- <i class="right fas fa-angle-left"></i> --}}
                        </p>
                    </a>
                    {{-- @if (Auth::user()->role === 3)
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.posts.index') }}" class="nav-link">
                                    <i class="fa-solid fa-square-check"></i>
                                    <p>Duyệt vào kho</p>
                                </a>
                            </li>
                        </ul>
                    @endif

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.posts.index') }}" class="nav-link">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <p>Lịch sử nhập kho</p>
                            </a>
                        </li>
                    </ul> --}}
                <li class="nav-item"><a href="{{ route('admin.refunds.index') }}" class="nav-link">
                        <i class="fa-solid fa-undo"></i>
                        <p> Quản lý Yêu cầu hoàn hàng</p>
                    </a></li>
                <li class="nav-item"><a href="{{ route('admin.orderdispute.index') }}" class="nav-link">
                        <i class="fa-solid fa-exclamation-triangle"></i>
                        <p> Quản lý tranh chấp đơn hàng</p>
                    </a></li>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('index') }}" class="nav-link">
                        <p>
                            <i class="fa-solid fa-arrow-left"></i>
                            Quay lại trang client
                        </p>
                    </a>
                </li> --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<style>
    .main-sidebar {
        background-color: #f8f9fa !important;
        min-height: 100vh !important;
        height: auto;
        overflow-y: auto;
        position: fixed !important;
        top: 0;
        left: 0;
        padding-bottom: 60px;
        /* để chừa khoảng cho footer */
        z-index: 999;
    }

    .sidebar-light {
        background-color: #f8f9fa !important;
    }

    .nav-sidebar .nav-item>.nav-link {
        color: #000 !important;
    }

    .nav-sidebar .nav-item>.nav-link:hover {
        background-color: rgba(0, 0, 0, 0.1) !important;
        color: #000 !important;
    }

    .nav-sidebar .nav-item>.nav-link.active {
        background-color: #e9ecef !important;
        color: #000 !important;
    }

    .nav-treeview>.nav-item>.nav-link {
        color: #000 !important;
        padding-left: 2rem;
    }

    .nav-treeview>.nav-item>.nav-link:hover {
        background-color: rgba(0, 0, 0, 0.1) !important;
    }

    .form-control-sidebar {
        background-color: #fff !important;
        border: 1px solid #dee2e6 !important;
    }

    .btn-sidebar {
        background-color: #fff !important;
        border: 1px solid #dee2e6 !important;
        color: #000 !important;
    }

    .brand-link {
        border-bottom: 1px solid #dee2e6 !important;
        background-color: #f8f9fa !important;
    }

    .user-panel {
        border-bottom: 1px solid #dee2e6 !important;
    }

    .nav-sidebar .nav-link>.right,
    .nav-sidebar .nav-link>p>.right {
        color: #000 !important;
    }

    .sidebar a {
        color: #000 !important;
    }

    .sidebar a:hover {
        text-decoration: none;
    }

    /* Override any dark theme colors */
    [class*="sidebar-dark-"] {
        background-color: #f8f9fa !important;
    }

    [class*="sidebar-dark-"] .nav-sidebar>.nav-item>.nav-link:active,
    [class*="sidebar-dark-"] .nav-sidebar>.nav-item>.nav-link:focus {
        color: #000 !important;
    }

    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active,
    .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
        background-color: #e9ecef !important;
        color: #000 !important;
    }
</style>
