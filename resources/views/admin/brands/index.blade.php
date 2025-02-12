@extends('admin.layouts.index')
@section('title')
    Danh sách Thương hiệu
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Danh sách thương hiệu</h3>

                                {{-- <div class="card-tools">
                                    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary btn-sm">Thêm mới</a>
                                </div> --}}

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tên thương hiệu</th>
                                            <th>Ngày tạo</th>
                                            <th>Ngày cập nhật</th>
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
                                                <td class="d-flex justify-content-around align-items-center text-nowrap">
                                                    <a class="btn btn-success btn-sm"
                                                        href="{{ route('admin.brands.show', $brand->id) }}">Xem</a>
                                                    <a class="btn btn-primary btn-sm"
                                                        href="{{ route('admin.brands.edit', $brand->id) }}">Sửa</a>
                                                    <form action="{{ route('admin.brands.destroy', $brand->id) }}"
                                                        method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa thương hiệu này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {{ $brands->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
