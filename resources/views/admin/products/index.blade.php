@extends('admin.layouts.index')

@section('title')
    Products
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
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
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <input type="text" class="form-control" placeholder="Search Product">
                    </div>
                    <div class="col-md-3 mb-3">
                        <select class="form-select" id="categoryFilter">
                            <option value="">Category</option>
                            <option value="men">Men</option>
                            <option value="women">Women</option>
                            <option value="kids">Kids</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <select class="form-select" id="priceFilter">
                            <option value="">Price</option>
                            <option value="low">Low to High</option>
                            <option value="high">High to Low</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Filter</button>
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
                                <th>PRODUCT NAME</th>
                                <th>CATEGORY</th>
                                <th>PRICE</th>
                                <th>SALE PRICE</th>
                                <th>STOCK</th>
                                <th>STATUS</th>
                                <th>VIEW</th>
                                <th>PUBLISHED</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" 
                                             class="me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        <span>{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $product->product_category }}</td>
                                <td>₫{{ number_format($product->price) }}</td>
                                <td>₫{{ number_format($product->sale_price ?? $product->price) }}</td>
                                <td>{{ $product->sum_quantity_variant ?? 0 }}</td>
                                <td>
                                    <span class="badge {{ $product->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->status ? 'Selling' : 'Draft' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.product.show', $product->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               {{ $product->status ? 'checked' : '' }}
                                               onchange="updateStatus({{ $product->id }})">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admin.product.edit', $product->id) }}" 
                                           class="btn btn-sm btn-warning me-2">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger" 
                                                onclick="deleteProduct({{ $product->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

@push('scripts')
<script>
    // Select all functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.product-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Update status function
    function updateStatus(productId) {
        // Implement status update logic
    }

    // Delete product function
    function deleteProduct(productId) {
        if(confirm('Are you sure you want to delete this product?')) {
            // Implement delete logic
        }
    }
</script>
@endpush
@endsection
