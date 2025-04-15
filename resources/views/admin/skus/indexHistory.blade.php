@extends('admin.layouts.index')

@section('title')
    Danh sách sản phẩm
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-8 d-flex">
                        <h1> Lịch sử nhập hàng </h1>
                    </div>
                    <div class="col-sm-4">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.skus.index') }}">Danh sách</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <div class="col-12">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card" style="height: 700px; width:1250px">
                <div class="card-header">
                    <div class="card-tools d-flex align-items-center">
                        <div>
                            <form id="search-form" class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" id="search-input" class="form-control float-right"
                                    placeholder="Nhập từ khóa tìm kiếm..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    Thông tin sản phẩm
                                </th>
                                <th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1" aria-label="Engine version: activate to sort column ascending">
                                    Ảnh đại diện
                                </th>
                                <th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1" aria-label="Engine version: activate to sort column ascending">
                                    Số lượng
                                </th>
                                <th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1" aria-label="Engine version: activate to sort column ascending">
                                    Giá
                                </th>
                                <th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1" aria-label="Engine version: activate to sort column ascending">
                                    Thông tin
                                </th>
                                <th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1" aria-label="Engine version: activate to sort column ascending">
                                    Trạng thái
                                </th>
                                {{-- <th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1" aria-label="CSS grade: activate to sort column ascending" width="100px">
                                    Hành
                                    động</th> --}}
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($skuses as $skus)
                                <tr>
                                    <td class="text-wrap" tabindex="0">
                                        <ul>
                                            <li>{{ $skus->skuses->name }}
                                            </li>
                                            <li><strong>Mã vạch: </strong>{{ $skus->skuses->barcode }}</li>
                                        </ul>
                                    </td>
                                    <td class="text-center">
                                        <img src="{{ Storage::url($skus->skuses->image) }}" width="70px" alt="">
                                    </td>
                                    <td class="">
                                        <ul>
                                            <li><strong>Số lượng nhập: </strong>
                                                {{ $skus->inventory_logs->change_quantity ?? '' }}
                                            </li>
                                            <li><strong>Số lượng trong kho: </strong>
                                                {{ $skus->inventory_logs->new_quantity ?? '' }}
                                            </li>
                                            <li><strong>Số lượng cũ: </strong>
                                                {{ $skus->inventory_logs->old_quantity ?? '' }}
                                            </li>
                                        </ul>
                                    </td>
                                    <td class="dtr-control sorting_1" tabindex="0">
                                        <ul>
                                            <li><strong>Giá nhập: </strong>
                                                {{ $skus->cost_price }}
                                            </li>

                                            <li><strong>Giá bán: </strong>
                                                {{ $skus->price }}
                                            </li>

                                            <li><strong>Giá giảm: </strong>
                                                @if ($skus->sale_price && $skus->discount_start <= Date::now() && $skus->discount_end >= Date::now())
                                                    {{ $skus->sale_price }}
                                                @else
                                                    Không giảm giá
                                                @endif
                                            </li>
                                        </ul>
                                    </td>
                                    <td class="">
                                        <ul>
                                            <li><strong>ngày tạo: </strong>
                                                {{ $skus->created_at->format('d/m/Y') }}
                                            </li>

                                            <li><strong>Ngày nhập: </strong>
                                                {{ $skus->updated_at->format('d/m/Y') }}
                                            </li>

                                            <li><strong>Người tạo: </strong>
                                                {{ $skus->users->name }}
                                            </li>
                                            <li><strong>Người duyệt: </strong>
                                                {{ $skus->approver ? $skus->approver->name : '' }}
                                            </li>
                                        </ul>
                                    </td>
                                    <td class="text-center">
                                        @if ($skus->status == 'Đã duyệt')
                                            <i class="fas fa-check-circle text-success" title="Đã duyệt"></i>
                                        @else
                                            <i class="fas fa-hourglass-half text-warning" title="Đang chờ xử lý"></i>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        {{-- <a href="{{ route('admin.product.skus.show', ['product' => $skus->product_id, 'sku' => $skus->id]) }}"
                                            class="btn btn-info"><i class="bi bi-eye"></i></a> --}}
                                        {{-- <form action="{{ route('admin.product.change_status', $product->id) }}"
                                            method="post"
                                            onsubmit="return confirm('Bạn có chắc muốn disable sản phẩm này?')"
                                            style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-primary"><i
                                                    class="fa-solid fa-repeat"></i></button>
                                        </form> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="text-center pb-3 d-flex align-items-center justify-content-center g-3">
                <a href="{{ route('admin.skus.index') }}" class="btn btn-danger me-3">Quay lại</a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('input#search-input').on("keyup", function() {
                let value = $(this).val().toLowerCase();

                $("table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
@endsection
