@extends('admin.layouts.index')
@section('content')
    <section class="content">
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Quản lý danh mục sản phẩm</h1><br>
                            <a href="{{ route('admin.category.create') }}" class="btn btn-primary mb-3">Thêm danh mục</a>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Danh mục</li>
                            </ol>
                        </div>
                    </div>
                    <!-- Hiển thị lỗi -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Hiển thị thông báo -->
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content-header -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="dt-buttons btn-group flex-wrap">
                                                    <button class="btn btn-secondary buttons-copy buttons-html5"
                                                        tabindex="0" aria-controls="example1"
                                                        type="button"><span>Copy</span></button>
                                                    <button class="btn btn-secondary buttons-csv buttons-html5"
                                                        tabindex="0" aria-controls="example1"
                                                        type="button"><span>CSV</span></button>
                                                    <button class="btn btn-secondary buttons-excel buttons-html5"
                                                        tabindex="0" aria-controls="example1"
                                                        type="button"><span>Excel</span></button>
                                                    <button class="btn btn-secondary buttons-pdf buttons-html5"
                                                        tabindex="0" aria-controls="example1"
                                                        type="button"><span>PDF</span></button>
                                                    <button class="btn btn-secondary buttons-print" tabindex="0"
                                                        aria-controls="example1" type="button"><span>Print</span></button>
                                                    <div class="btn-group">
                                                        <button
                                                            class="btn btn-secondary buttons-collection dropdown-toggle buttons-colvis"
                                                            tabindex="0" aria-controls="example1" type="button"
                                                            aria-haspopup="true"><span>Column
                                                                visibility</span><span
                                                                class="dt-down-arrow"></span></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <form action="{{ route('admin.category.search') }}" method="GET" class="pb-3">
                                                    <div id="example1_filter" class="dataTables_filter"><label>Search:<input
                                                                type="search" class="form-control form-control-sm"
                                                                placeholder="" aria-controls="example1" name="keyword"></label>
                                                            <button class="btn btn-success" type="submit"><i
                                                                    class="fa fa-search"></i></button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="example1"
                                                    class="table table-bordered table-striped dataTable dtr-inline"
                                                    aria-describedby="example1_info">
                                                    <thead>
                                                        <tr>
                                                            <th class="sorting sorting_asc" tabindex="0"
                                                                aria-controls="example1" rowspan="1" colspan="1"
                                                                aria-sort="ascending">
                                                                Id
                                                            </th>
                                                            <th class="sorting" tabindex="0" aria-controls="example1"
                                                                rowspan="1" colspan="1">
                                                                Name
                                                            </th>
                                                            <th class="sorting" tabindex="0" aria-controls="example1"
                                                                rowspan="1" colspan="1">
                                                                Description</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example1"
                                                                rowspan="1" colspan="1">
                                                                Image
                                                            </th>
                                                            <th class="sorting" tabindex="0" aria-controls="example1"
                                                                rowspan="1" colspan="1">
                                                                Is_active</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example1"
                                                                rowspan="1" colspan="1">
                                                                Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($categories as $cate)
                                                            <tr class="odd">
                                                                <td class="dtr-control sorting_1" tabindex="0">
                                                                    {{ $cate->id }}</td>
                                                                <td>{{ $cate->name }}</td>
                                                                <td>{{ $cate->description }}</td>
                                                                <td>
                                                                    @if ($cate->image)
                                                                        <img src="{{ Storage::url($cate->image) }}"
                                                                            width="100px" height="80px"
                                                                            alt="Ảnh danh mục">
                                                                    @else
                                                                        Không có ảnh
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($cate->is_active)
                                                                        <span class="badge badge-success">Hoạt động</span>
                                                                    @else
                                                                        <span class="badge badge-danger">Không hoạt
                                                                            động</span>
                                                                    @endif
                                                                </td>

                                                                <td class="d-flex">
                                                                    <a href="{{ Route('admin.category.edit', $cate->id) }}"
                                                                        class="btn btn-success mx-2">Edit</a>
                                                                    <form
                                                                        action="{{ Route('admin.category.destroy', $cate->id) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button
                                                                            onclick="return confirm('Có chắc chắn xóa chứ?')"
                                                                            class="btn btn-danger">Delete</button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th rowspan="1" colspan="1">Id</th>
                                                            <th rowspan="1" colspan="1">Name</th>
                                                            <th rowspan="1" colspan="1">Description</th>
                                                            <th rowspan="1" colspan="1">Image</th>
                                                            <th rowspan="1" colspan="1">Is_active</th>
                                                            <th rowspan="1" colspan="1">Action</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                {{ $categories->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    @endsection