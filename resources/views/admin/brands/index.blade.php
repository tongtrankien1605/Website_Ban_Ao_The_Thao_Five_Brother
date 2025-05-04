@extends('admin.layouts.index')

@section('title')
    Danh sách Thương hiệu
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Danh sách Thương hiệu</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">trang chủ</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.brands.create') }}">Thêm mới thương
                                    hiệu</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <div class="col-12">
            <div class="card" style="height: 700px; width:1250px">
                <div class="card-header">
                    {{-- <h3 class="card-title"></h3> --}}
                    <div class="card-tools">
                        <form action="{{ route('admin.brands.index') }}" method="GET" class="input-group input-group-sm"
                            style="width: 150px;">
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
                                <th>ID</th>
                                <th>Tên thương hiệu</th>
                                <th>Ngày tạo</th>
                                <th>Ngày cập nhật</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($brands as $brand)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $brand->name }}</td>
                                    <td>{{ $brand->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $brand->updated_at->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($brand->status == 1)
                                            <span class="badge badge-success">Hoạt động</span>
                                        @else
                                            <span class="badge badge-danger">Không hoạt động</span>
                                        @endif
                                    <td class="text-nowrap" style="width: 1px">
                                        <div class="d-flex">
                                        <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-warning">
                                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24"
                                                stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                        {{-- <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa thương hiệu này?')"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"><i
                                                    class="bi bi-trash"></i></button>
                                        </form> --}}
                                        <a href="{{ route('admin.brands.show', $brand->id) }}" class="btn btn-info ms-2"><i
                                                class="bi bi-eye"></i></a>
                                        <form action="{{ Route('admin.brand.change_status', $brand->id) }}"
                                            method="post">
                                            @csrf
                                            @method('PUT')
                                            <button
                                                onclick="return confirm('Bạn chắc chắn muốn thay đổi trạng thái thương hiệu?')"
                                                class="btn ms-2 {{ $brand->status ? 'btn-danger' : 'btn-success' }}">
                                                <i class="fas fa-sync-alt"></i></button>
                                        </form>
                                    </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                {{ $brands->links('vendor.pagination.bootstrap-4') }}
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
