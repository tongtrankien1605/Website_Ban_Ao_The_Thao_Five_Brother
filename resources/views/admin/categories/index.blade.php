@extends('admin.layouts.index')
@section('title')
    Danh sách Danh mục
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Danh sách Danh mục</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                            <li class="breadcrumb-item active"> <a href="{{ route('admin.category.create') }}">Thêm mới danh
                                    mục</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <div class="col-12">
            <div class="card" style="height: 700px; width:1250px">
                <div class="card-header">
                    <div class="card-tools">
                        {{-- <form action="{{ route('admin.category.search') }}" method="GET" class="pb-3">
                            <div id="example1_filter" class="dataTables_filter"><label>Search:<input type="search"
                                        class="form-control form-control-sm" placeholder="" aria-controls="example1"
                                        name="keyword"></label>
                                <button class="btn btn-success" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </form> --}}
                        <form action="{{ route('admin.category.search') }}" method="GET"
                            class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="Search"
                                value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th class="sorting sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1" aria-sort="ascending">
                                    Id
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                                    Tên danh mục
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                                    Mô tả</th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                                    Ảnh
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                                    Trạng thái</th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                                    Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $cate)
                                <tr class="odd">
                                    <td class="dtr-control sorting_1" tabindex="0">
                                        {{ $cate->id }}
                                    </td>
                                    <td>{{ $cate->name }}</td>
                                    <td>{{ Str::limit($cate->description, 20) }}</td>
                                    <td>
                                        @if ($cate->image)
                                            <img src="{{ Storage::url($cate->image) }}" width="100px" height="80px"
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
                                            class="btn btn-success mx-2">
                                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24"
                                                stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ Route('admin.category.destroy', $cate->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Có chắc chắn xóa chứ?')"
                                                class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                {{ $categories->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: "{{ session('success') }}",
                confirmButtonText: 'Đóng'
            });
        @elseif (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: "{{ session('error') }}",
                confirmButtonText: 'Đóng'
            });
        @endif
    </script>
@endsection
