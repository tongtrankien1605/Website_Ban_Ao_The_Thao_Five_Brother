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
                        <h1>Danh sách cần duyệt </h1>
                    </div>
                    <div class="col-sm-4">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.skus.index') }}">Danh sách sản
                                    phẩm</a></li>
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
                        <div class="input-group-append">
                            <button id="select-all-btn" class="btn btn-default">Chọn tất cả</button>
                            <button id="deselect-all-btn" class="btn btn-default">Bỏ chọn tất cả</button>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th class="text-center">Chọn</th>
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
                                    Ngày tạo
                                </th>
                                <th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1" aria-label="Engine version: activate to sort column ascending">
                                    Status
                                </th>
                                <th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1" aria-label="CSS grade: activate to sort column ascending" width="100px">
                                    Hành
                                    động</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($skuses as $skus)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="sku-checkbox" value="{{ $skus->id }}">
                                    </td>
                                    <td class="text-wrap" tabindex="0">
                                        <ul>
                                            <li>{{ $skus->skuses->name }}
                                            </li>
                                            <li><strong>Barcode: </strong>{{ $skus->skuses->barcode }}</li>
                                        </ul>
                                    </td>
                                    <td class="text-center">
                                        <img src="{{ Storage::url($skus->skuses->image) }}" width="100px" alt="">
                                    </td>
                                    <td class=" text-center">
                                        {{ $skus->skuses->inventories->quantity }}
                                    </td>
                                    <td class="dtr-control sorting_1" tabindex="0">
                                        <ul>
                                            <li><strong>Số lượng nhập: </strong>
                                                {{ $skus->quantity }}
                                            </li>
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
                                    <td class=" text-center">
                                        {{ $skus->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class=" text-center">
                                        <span class="badge bg-danger">{{ $skus->status }}</span>
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
                <form method="POST" action="{{ route('admin.confirm') }}" enctype="multipart/form-data"
                    style="height: 37.6px">
                    @csrf
                    <input type="hidden" name="ids" id="ids">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" id="submit-btn" disabled>Duyệt</button>
                    </div>

                    <br>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll(".sku-checkbox");
            const selectAllBtn = document.getElementById("select-all-btn");
            const deselectAllBtn = document.getElementById("deselect-all-btn");
            const submitButton = document.getElementById("submit-btn");
            const hiddenInput = document.getElementById("ids");

            let checkedIds = [];

            function toggleButton() {
                submitButton.disabled = checkedIds.length === 0;
            }
            // Cập nhật trạng thái checkbox từ sessionStorage
            checkboxes.forEach((checkbox) => {
                if (checkedIds.includes(checkbox.value)) {
                    checkbox.checked = true;
                }

                checkbox.addEventListener("change", function() {
                    if (this.checked) {
                        checkedIds.push(this.value);
                    } else {
                        checkedIds = checkedIds.filter(id => id !== this.value);
                    }
                    hiddenInput.value = checkedIds.join(",");
                    toggleButton();
                });
            });

            // Xử lý chọn tất cả (chỉ chọn checkbox đang hiển thị)
            selectAllBtn.addEventListener("click", function() {
                checkedIds = [];
                checkboxes.forEach((checkbox) => {
                    if (checkbox.offsetParent !== null) { // Kiểm tra xem có hiển thị không
                        checkbox.checked = true;
                        checkedIds.push(checkbox.value);
                    }
                });
                hiddenInput.value = checkedIds.join(",");
                toggleButton();
            });

            // Xử lý bỏ chọn tất cả (chỉ bỏ chọn checkbox đang hiển thị)
            deselectAllBtn.addEventListener("click", function() {
                checkboxes.forEach((checkbox) => {
                    if (checkbox.offsetParent !== null) { // Kiểm tra xem có hiển thị không
                        checkbox.checked = false;
                    }
                });
                checkedIds = checkedIds.filter(id => {
                    const checkbox = document.querySelector(`.sku-checkbox[value="${id}"]`);
                    return checkbox && checkbox.offsetParent === null; // Giữ lại các checkbox ẩn
                });
                hiddenInput.value = checkedIds.join(",");
                toggleButton();
            });
        });

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
