@extends('admin.layouts.index')
@section('title')
    Thêm mới Thộc tính
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Thêm thuộc tính</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.product_attribute.index') }}">Trang danh sách</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <form action="{{ route('admin.product_attribute.store') }}" method="POST">
                                @csrf
                                <div class="card-body">
                                    <div id="attributes-container">
                                        <div class="attribute-group mb-3">
                                            <div class="d-flex justify-content-between mb-3">
                                                <label class="form-label mb-0">Tên thuộc tính</label>
                                            </div>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name') }}">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div class="attribute-values mt-2">
                                                <div class="d-flex gap-2 mb-3 align-items-center">
                                                    <label class="form-label mb-0">Tên giá trị</label>
                                                    <button type="button" class="btn btn-outline-success ms-2 btn-sm"
                                                        onclick="addValue(this)">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                                </div>
                                                @php
                                                    $oldValues = old('values', ['']);
                                                @endphp

                                                @foreach ($oldValues as $index => $value)
                                                    <div class="d-flex mb-2">
                                                        <input type="text" name="values[]" class="form-control"
                                                            value="{{ $value }}">
                                                        <button type="button" class="btn btn-danger ms-2 btn-sm"
                                                            onclick="removeValue(this)">
                                                            <i class="fa-solid fa-minus"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                                @error('values.*')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ route('admin.product_attribute.index') }}"
                                            class="btn btn-danger mt-2">Quay
                                            lại</a>
                                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                                        <div id="variants-container" class="mt-4"></div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script script>
        function addValue(button) {
            let container = button.closest('.attribute-values');

            let newInputGroup = document.createElement('div');
            newInputGroup.classList.add('d-flex', 'mb-2');

            newInputGroup.innerHTML = `
            <input type="text" name="values[]" class="form-control">
            <button type="button" class="btn btn-danger ms-2 btn-sm" onclick="removeValue(this)">
                <i class="fa-solid fa-minus"></i>
            </button>
        `;

            container.appendChild(newInputGroup);
        }

        function removeValue(button) {
            let container = button.closest('.attribute-values');
            if (container.querySelectorAll('input').length > 1) {
                button.parentElement.remove();
            }
        }
    </script>
@endsection
