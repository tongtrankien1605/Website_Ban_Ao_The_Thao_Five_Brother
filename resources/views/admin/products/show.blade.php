@extends('admin.layouts.index')

@section('title')
    {{ $product->name }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid p-4">
            <!-- Product Header -->
            <div class="mb-4">
                <div class="text-muted">Trạng thái:
                    @if ($product->status)
                        <span class="text-success">Sản phẩm này đang hiển thị</span>
                    @else
                        <span class="text-danger">Sản phẩm này đang ẩn</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between align-items-start mt-2">
                    <div>
                        <h1 class="h3 mb-2">{{ $product->name }}</h1>
                        {{-- <div class="text-muted">SKU: {{ $product->sku ?? '' }}</div> --}}
                    </div>
                    <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-success">Sửa sản phẩm</a>
                </div>
            </div>

            <!-- Product Info -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="product-image mb-4">
                        <img src="{{ Storage::url($product->image) }}" class="img-fluid rounded border"
                            alt="{{ $product->name }}" style="max-width: 100%; height: auto;">
                    </div>
                    @if ($productImages && count($productImages) > 0)
                        <div class="product-gallery d-flex gap-2 mb-4">
                            @foreach ($productImages as $productImage)
                                <div class="gallery-item">
                                    <img src="{{ Storage::url($productImage->image_url) }}" class="img-thumbnail"
                                        style="width: 80px; height: 80px; object-fit: cover;" alt="">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="product-info">
                        {{-- <div class="mb-4">
                            <div class="h2 mb-2">${{ number_format($product->price ?? 0, 2) }}</div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success-light me-2">In Stock</span>
                                <span class="text-muted">QUANTITY: {{ $product->quantity ?? 0 }}</span>
                            </div>
                        </div> --}}

                        <div class="mb-4">
                            <h5 class="mb-2">Danh mục: <a href="#"
                                    class="text-decoration-none">{{ $category->name }}</a></h5>
                            <div class="d-flex gap-2">
                                {{-- <span class="badge bg-light text-dark">{{ $product->tag ?? 'abc' }}</span> --}}
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-2">Mô tả</h5>
                            <div class="text-muted">
                                {!! $product->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Variants -->
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Danh sách biến thể</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>ẢNH</th>
                                <th>TÊN</th>
                                {{-- <th>SKU</th> --}}
                                <th>MÃ VẠCH</th>
                                <th>GIÁ GỐC</th>
                                <th>GIÁ BÁN</th>
                                <th>GIÁ GIẢM</th>
                                <th>THỜI GIAN</th>
                                <th>SỐ LƯỢNG</th>
                                <th class="text-end">HÀNH ĐỘNG</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skuses as $index => $skus)
                                @php
                                    $getPrice = collect($skus->inventory_entries)->sortByDesc('created_at')->first();
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <img src="{{ Storage::url($skus->image) }}" width="40" height="40"
                                            class="rounded" alt="">
                                    </td>
                                    <td>
                                        <div>{{ $skus->name }}</div>
                                        <div class="text-muted small">{{ $skus->sku }}</div>
                                    </td>
                                    {{-- <td>{{ $skus->sku }}</td> --}}
                                    <td>{{ $skus->barcode }}</td>
                                    <td>{{ number_format($getPrice->cost_price ?? 0) }} VND</td>
                                    <td>{{ number_format($getPrice->price ?? 0) }} VND</td>
                                    @if ($getPrice->sale_price ?? '')
                                        <td>{{ number_format($getPrice->sale_price) }} VND</td>
                                        <td>{{ $getPrice->discount_start->format('d/m/Y') }} -
                                            {{ $getPrice->discount_end->format('d/m/Y') }}</td>
                                    @else
                                        <td>Không giảm giá</td>
                                        <td>Không giảm giá</td>
                                    @endif

                                    <td class=" text-center">{{ $skus->inventories->quantity ?? 0 }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.product.skus.edit', ['product' => $product->id, 'sku' => $skus->id]) }}"
                                                class="btn btn-sm btn-light">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            {{-- <a href="{{ route('admin.product.skus.show', ['product' => $product->id, 'sku' => $skus->id]) }}" 
                                               class="btn btn-sm btn-light">
                                                <i class="fas fa-eye"></i>
                                            </a> --}}
                                            {{-- <form
                                                action="{{ route('admin.skus.change_status', ['product' => $product->id, 'sku' => $skus->id]) }}"
                                                method="post" class="d-inline-block"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái sản phẩm này không?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-light">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </form> --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- @if ($skuses->hasPages())
                    <div class="card-footer border-top bg-white">
                        {{ $skuses->links() }}
                    </div>
                @endif --}}
            </div>
            <div class="py-3 text-center">
                <a href="{{ route('admin.product.index') }}" class="btn btn-danger">Quay lại</a>
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

@push('styles')
    <style>
        .bg-success-light {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .table> :not(caption)>*>* {
            padding: 1rem;
        }

        .btn-light {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }

        .btn-light:hover {
            background-color: #e9ecef;
            border-color: #dde0e3;
        }

        .gallery-item {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            max-height: 500px;
            object-fit: contain;
        }

        .content-wrapper {
            background-color: #f8fafc;
        }
    </style>
@endpush
