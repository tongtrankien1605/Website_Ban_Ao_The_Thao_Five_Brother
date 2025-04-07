<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.index') }}" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">5Brother</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : url('dist/img/user2-160x160.jpg') }}"
                    class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('admin.user.show', Auth::user()->id) }}" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
       with font-awesome or any other icon font library -->

                {{-- <i class="metismenu-icon pe-7s-rocket"></i> --}}

                <li class="nav-item menu-close">

                    <a href="{{ Route('admin.index') }}" class="nav-link">
                        <i class="fa-solid fa-rocket"></i>
                        <p>
                            Dashboard

                        </p>
                    </a>

                </li>


                <li class="nav-item menu-close">

                    <a href="{{ route('admin.user.index') }}" class="nav-link">
                        <i class="fa-solid fa-users"></i>
                        <p>
                            Quản lý người dùng
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
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
                    </ul>
                </li>

                <li class="nav-item menu-close">
                    <a href="{{ route('admin.category.index') }}" class="nav-link">
                        <i class="fa-solid fa-table-list"></i>
                        <p>
                            Quản lý danh mục
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
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

                    </ul>
                </li>

                <li class="nav-item menu-close">
                    <a href="{{ route('admin.brands.index') }}" class="nav-link">
                        <i class="fa-solid fa-copyright"></i>
                        <p>
                            Quản lý thương hiệu
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
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
                    </ul>
                </li>

                <li class="nav-item menu-close">
                    <a href="{{ route('admin.brands.index') }}" class="nav-link">
                        <i class="fa-solid fa-sitemap"></i>
                        <p>
                            Quản lý thuộc tính
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
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

                    </ul>
                </li>

                <li class="nav-item menu-close">
                    <a href="{{ route('admin.product.index') }}" class="nav-link">
                        <i class="fa-brands fa-product-hunt"></i>
                        <p>
                            Quản lý sản phẩm
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
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

                    </ul>
                </li>

                <li class="nav-item menu-close">
                    <a href="{{ route('admin.vouchers.index') }}" class="nav-link">
                        <i class="fa-solid fa-ticket"></i>
                        <p>
                            Quản lý Voucher
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
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

                    </ul>
                </li>

                <li class="nav-item menu-close">
                    <a href="{{ route('admin.brands.index') }}" class="nav-link">
                        <i class="fa-solid fa-newspaper"></i>
                        <p>
                            Quản lý bài viết
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
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

                    </ul>
                </li>

                <li class="nav-item menu-close">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link">
                        <p>
                            <i class="fa-solid fa-cart-shopping"></i>
                            Quản lý đơn hàng
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-close">
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
                <li class="nav-item menu-close"><a href="{{ route('admin.refunds.index') }}" class="nav-link">
                        <p><i class="fa-solid fa-undo"></i> Quản lý Yêu cầu hoàn hàng</p>
                    </a></li>
                </li>
                <li class="nav-item menu-close">
                    <a href="{{ route('index') }}" class="nav-link">
                        <p>
                            <i class="fa-solid fa-arrow-left"></i>
                            Quay lại trang client
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<style>
    .main-sidebar {
        min-height: 100% !important;
    }
</style>
