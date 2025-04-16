@extends('admin.layouts.index')
@section('title')
    Danh sách thuộc tính
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Danh sách thuộc tính</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.product_attribute.create') }}">Thêm mới thuộc tính</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="example1"
                                                class="table table-bordered table-striped dataTable dtr-inline"
                                                aria-describedby="example1_info">
                                                <thead>
                                                    <tr>
                                                        <th class="sorting sorting_asc" tabindex="0"
                                                            aria-controls="example1" rowspan="1" colspan="1"
                                                            aria-sort="ascending"
                                                            aria-label="Rendering engine: activate to sort column descending">
                                                            Tên thuộc tính
                                                        </th>
                                                        <th class="sorting sorting_asc" tabindex="0"
                                                            aria-controls="example1" rowspan="1" colspan="1"
                                                            aria-sort="ascending"
                                                            aria-label="Rendering engine: activate to sort column descending">
                                                            Các giá trị
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="example1"
                                                            rowspan="1" colspan="1"
                                                            aria-label="Engine version: activate to sort column ascending">
                                                            Ngày tạo
                                                        </th>
                                                        <th class="sorting text-nowrap" tabindex="0"
                                                            aria-controls="example1" rowspan="1" colspan="1"
                                                            aria-label="Engine version: activate to sort column ascending">
                                                            Hành động
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($attributes as $attribute)
                                                        <tr>
                                                            <td class="dtr-control sorting_1" tabindex="0"
                                                                style="width:300px">
                                                                {{ $attribute->name }}
                                                            </td>
                                                            <td>
                                                                <ul>
                                                                    @foreach ($attributeValues[$attribute->id] as $attributeValue)
                                                                        <li>{{ $attributeValue->value }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </td>
                                                            <td style="width:150px">{{ $attribute->created_at }}</td>
                                                            <td class="text-center text-nowrap" style="width: 1px">
                                                                <a href="{{ route('admin.product_attribute.edit', $attribute->id) }}"
                                                                    class="btn btn-warning">
                                                                    <svg stroke="currentColor" fill="none"
                                                                        stroke-width="2" viewBox="0 0 24 24"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        height="1em" width="1em"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                                        </path>
                                                                        <path
                                                                            d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                                        </path>
                                                                    </svg>
                                                                </a>
                                                                {{-- <form
                                                                    action="{{ route('admin.product_attribute.destroy', $attribute->id) }}"
                                                                    method="post"
                                                                    onsubmit="return confirm('Bạn có chắc muốn xóa thuộc tính này?')"
                                                                    style="display:inline;">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                                                </form> --}}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            {{ $attributes->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
