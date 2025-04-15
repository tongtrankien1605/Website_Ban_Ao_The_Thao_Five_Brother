@extends('admin.layouts.index')

@section('title')
    Products
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid pt-3">
            <!-- Header Section -->
            {{-- <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <button class="btn btn-outline-secondary me-2">
                        <i class="fas fa-file-export"></i> Export
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-file-import"></i> Import
                    </button>
                </div>
                <div class="d-flex">
                    <button class="btn btn-outline-secondary me-2">
                        <i class="fas fa-tasks"></i> Bulk Action
                    </button>
                    <button class="btn btn-danger me-2">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    <a href="{{ route('admin.product.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add Product
                    </a>
                </div>
            </div> --}}

            <!-- Filter Section -->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm">
                        </div>
                        <div class="col-md-3 mb-3">
                            <select class="form-select" id="categoryFilter">
                                <option value="">-- Danh mục --</option>
                                @foreach ($productCategories as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <select class="form-select" id="brandFilter">
                                <option value="">-- Thương hiệu --</option>
                                @foreach ($productBrands as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.product.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Thêm sản phẩm
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>TÊN</th>
                                    <th>DANH MỤC</th>
                                    <th>THƯƠNG HIỆU</th>
                                    <th>GIÁ</th>
                                    <th>BIẾN THỂ</th>
                                    {{-- <th>STOCK</th> --}}
                                    <th>TRẠNG THÁI</th>
                                    <th>XEM</th>
                                    {{-- <th>PUBLISHED</th> --}}
                                    <th>SỬA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr class="product-row" data-name="{{ strtolower($product->name) }}"
                                        data-category="{{ strtolower($product->product_category) }}"
                                        data-brand="{{ strtolower($product->product_brand) }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                                    class="me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                <span>{{ $product->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $product->product_category }}</td>
                                        <td>{{ $product->product_brand }}</td>
                                        <td>{{ number_format($product->avg_inventory_price) }} VND</td>
                                        {{-- <td>{{ number_format($product->sale_price ?? $product->price) }}</td> --}}
                                        <td class=" text-center">{{ $product->count_variant ?? 0 }}</td>
                                        <td>
                                            <span class="badge {{ $product->status ? 'bg-success' : 'bg-danger' }}">
                                                {{ $product->status ? 'Selling' : 'Draft' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.product.show', $product->id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                        {{-- <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" {{ $product->status ? 'checked'
                                                : '' }}
                                                onchange="updateStatus({{ $product->id }})">
                                            </div>
                                        </td> --}}
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <a href="{{ route('admin.product.edit', $product->id) }}"
                                                    class="btn btn-sm btn-warning me-2">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                {{-- <form action="{{ route('admin.product.destroy', $product->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this products?')"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form> --}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links('vendor.pagination.bootstrap-4') }}
            </div> --}}
        </div>
    </div>

    @push('styles')
        <style>
            .card {
                margin-top: 50px;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .table th {
                font-weight: 600;
                color: #4B5563;
            }

            .btn-success {
                background-color: #10B981;
                border-color: #10B981;
            }

            .btn-success:hover {
                background-color: #059669;
                border-color: #059669;
            }

            .form-check-input:checked {
                background-color: #10B981;
                border-color: #10B981;
            }

            .badge {
                padding: 6px 12px;
                border-radius: 20px;
            }

            .bg-success {
                background-color: #D1FAE5 !important;
                color: #059669;
            }

            .bg-danger {
                background-color: #FEE2E2 !important;
                color: #DC2626;
            }
        </style>
    @endpush
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
        const searchInput = document.querySelector('input[placeholder="Tìm kiếm sản phẩm"]');
        const categoryFilter = document.getElementById('categoryFilter');
        const brandFilter = document.getElementById('brandFilter');
        const productRows = document.querySelectorAll('.product-row');

        function normalize(str) {
            return str?.toLowerCase().trim();
        }

        function filterProducts() {
            const searchVal = normalize(searchInput.value);
            const categoryVal = normalize(categoryFilter.options[categoryFilter.selectedIndex].text);
            const brandVal = normalize(brandFilter.options[brandFilter.selectedIndex].text);

            productRows.forEach(row => {
                const name = normalize(row.dataset.name);
                const category = normalize(row.dataset.category);
                const brand = normalize(row.dataset.brand);

                const matchesSearch = !searchVal || name.includes(searchVal);
                const matchesCategory = !categoryFilter.value || category === categoryVal;
                const matchesBrand = !brandFilter.value || brand === brandVal;

                row.style.display = (matchesSearch && matchesCategory && matchesBrand) ? '' : 'none';
            });
        }

        searchInput.addEventListener('keyup', filterProducts);
        categoryFilter.addEventListener('change', filterProducts);
        brandFilter.addEventListener('change', filterProducts);
    </script>
@endsection
