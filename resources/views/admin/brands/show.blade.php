@extends('admin.layouts.index')

@section('title')
    Chi tiết Thương hiệu
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Chi tiết Thương hiệu</h3>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Tên thương hiệu:</label>
                                    <p>{{ $brand->name }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Ngày tạo:</label>
                                    <p>{{ $brand->created_at->format('d-m-Y') }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Ngày cập nhật:</label>
                                    <p>{{ $brand->updated_at->format('d-m-Y') }}</p>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('admin.brands.index') }}" class="btn btn-danger">Quay lại</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
