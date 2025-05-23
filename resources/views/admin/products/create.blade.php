@extends('admin.layouts.index')
@extends('admin.products.css')
@section('title')
    Thêm mới sản phẩm
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Thêm mới sản phẩm</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="row g-5">
                                <div class="col-12 col-xl-8">
                                    <div class="form-group">
                                        <label for="name">Tên sản phẩm</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Nhập tên sản phẩm..." value="{{ old('name') }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="description" class="form-label">Mô tả sản phẩm</label>
                                        <textarea name="description" class="form-control" rows="5" id="summernote">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Ảnh đại diện</label>
                                        <div>
                                            <img id="imagePreview" class="img-preview mt-2 d-none" width="100"
                                                height="100" style="overflow: hidden">
                                        </div>
                                        <div class="input-group">
                                            <input type="file" class="form-control" id="pwd" name="image"
                                                value="{{ old('image') }}">

                                        </div>
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <h4 class="mb-3">Tải ảnh lên</h4>
                                    <div class="dropzone dropzone-multiple p-3 mb-3 border border-dashed rounded"
                                        id="myDropzone">
                                        <input type="file" name="images[]" multiple class="form-control d-none"
                                            accept="image/*" id="imageInput" value="{{ old('images[]') }}">
                                        <div class="text-center">
                                            <p class="text-body-tertiary text-opacity-85">
                                                Thả ảnh của bạn vào đây <span class="text-body-secondary px-1">hoặc</span>
                                                <button class="btn btn-link p-0" type="button"
                                                    onclick="document.getElementById('imageInput').click();">
                                                    Chọn từ thiết bị
                                                </button>
                                            </p>
                                        </div>

                                        <div id="previewContainer" class="d-flex flex-wrap gap-2 mt-3"></div>
                                        @error('images[]')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div id="createdVariantContainer" class="mt-4">
                                        @php
                                            $oldVariants = old('variants', []);
                                        @endphp

                                        @foreach ($oldVariants as $index => $variant)
                                            <div class="card mb-3 variant-block">
                                                <div
                                                    class="card-header toggle-variant d-flex justify-content-between align-items-center">
                                                    <h5 class="mb-0">{{ $variant['name'] }}</h5>
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger float-end remove-variant">Xóa
                                                        biến thể</button>
                                                </div>
                                                <div class="card-body">
                                                    <label class="form-label">Tên:</label>
                                                    <input type="text" class="form-control"
                                                        name="variants[{{ $index }}][name]"
                                                        value="{{ $variant['name'] }}" readonly>
                                                    @error("variants.$index.name")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                    <input type="hidden" class="form-control"
                                                        name="variants[{{ $index }}][barcode]"
                                                        value="{{ $variant['barcode'] }}" readonly>
                                                    @foreach ($variant['attribute_values'] as $attrValue)
                                                        <input type="hidden"
                                                            name="variants[{{ $index }}][attribute_values][]"
                                                            value="{{ $attrValue }}">
                                                    @endforeach

                                                    {{-- <label class="form-label">Số lượng:</label>
                                                    <input type="number" class="form-control"
                                                        name="variants[{{ $index }}][quantity]"
                                                        value="{{ old("variants.$index.quantity") }}">
                                                    @error("variants.$index.quantity")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                    <label class="form-label">Giá nhập:</label>
                                                    <input type="number" class="form-control"
                                                        name="variants[{{ $index }}][cost_price]"
                                                        value="{{ old("variants.$index.cost_price") }}">
                                                    @error("variants.$index.cost_price")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                    <label class="form-label">Giá bán:</label>
                                                    <input type="number" class="form-control"
                                                        name="variants[{{ $index }}][price]"
                                                        value="{{ old("variants.$index.price") }}">
                                                    @error("variants.$index.price")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                    <label class="form-label">Giá giảm:</label>
                                                    <input type="number" class="form-control"
                                                        name="variants[{{ $index }}][sale_price]"
                                                        value="{{ old("variants.$index.sale_price") }}">
                                                    @error("variants.$index.sale_price")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                    <label class="form-label">Ngày bắt đầu:</label>
                                                    <input type="date" class="form-control"
                                                        name="variants[{{ $index }}][start_date]"
                                                        value="{{ old("variants.$index.start_date") }}">
                                                    @error("variants.$index.start_date")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                    <label class="form-label">Ngày kết thúc:</label>
                                                    <input type="date" class="form-control"
                                                        name="variants[{{ $index }}][end_date]"
                                                        value="{{ old("variants.$index.end_date") }}">
                                                    @error("variants.$index.end_date")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror --}}

                                                    <label class="form-label">Ảnh:</label>
                                                    <input type="file" class="form-control variant-image"
                                                        name="variants[{{ $index }}][image]" accept="image/*"
                                                        value="{{ old("variants.$index.image") }}">
                                                    @error("variants.$index.image")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>

                                <div class="col-12 col-xl-4">
                                    <div class="row g-2">
                                        <div class="col-12 col-xl-12">
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <div class="row gx-3">
                                                        <div class="col-12 col-sm-6 col-xl-12">
                                                            <div class="mb-4">
                                                                <div class="d-flex flex-wrap mb-2">
                                                                    <h5 class="mb-0 text-body-highlight me-2">Danh mục sản
                                                                        phẩm
                                                                    </h5> <br>
                                                                </div>
                                                                <select class="form-control" id="category"
                                                                    name="id_category">
                                                                    <option value="">-- chọn --</option>
                                                                    @foreach ($categories as $category)
                                                                        <option value="{{ $category->id }}"
                                                                            {{ old('id_category') == $category->id ? 'selected' : '' }}>
                                                                            {{ $category->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('id_category')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-xl-12">
                                                            <div class="mb-4">
                                                                <div class="d-flex flex-wrap mb-2">
                                                                    <h5 class="mb-0 text-body-highlight me-2">Thương hiệu
                                                                        sản phẩm</h5>
                                                                    <br>
                                                                </div>
                                                                <select class="form-control" id="brand"
                                                                    name="id_brand">
                                                                    <option value="">-- chọn --</option>
                                                                    @foreach ($brands as $brand)
                                                                        <option value="{{ $brand->id }}"
                                                                            {{ old('id_brand') == $brand->id ? 'selected' : '' }}>
                                                                            {{ $brand->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('id_brand')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-xl-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title mb-0">Biến thể</h4>
                                                    <button type="button" class="btn btn-primary btn-sm float-end"
                                                        id="toggleVariantsBtn">
                                                        Thêm biến thể
                                                    </button>
                                                </div>
                                                <div class="card-body d-none" id="variantsCard">
                                                    <div class="mb-3">
                                                        <label for="attributeSelect" class="form-label">Chọn thuộc
                                                            tính</label>
                                                        <select id="attributeSelect" class="form-select">
                                                            <option value="">Chọn thuộc tính...</option>
                                                            @foreach ($attributes as $key => $value)
                                                                <option value="{{ $key }}">
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div id="attributeContainer" class="row g-3">
                                                    </div>
                                                    <div class="mt-3 text-center">
                                                        <button type="button" class="btn btn-success"
                                                            id="createVariantBtn" disabled>
                                                            Tạo biến thể
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="text-center">
                        <a href="{{ route('admin.product.index') }}" class="btn btn-danger my-2">Quay
                            lại</a>
                        <button type="submit" class="btn btn-primary my-2">Thêm mới</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @include('admin.products.js')
@endsection
<style>
    .content-wrapper {
        min-height: fit-content !important;
    }

    .card-header::after {
        content: none !important;
    }
</style>
