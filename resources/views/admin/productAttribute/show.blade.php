@extends('admin.layouts.index')

@section('title')
    Chi tiết sản phẩm biến thể
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Chi tiết sản phẩm biến thể</h3>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Tên</label>
                                    <p>{{ $skus->name }}</p>
                                </div>
                                <div class="form-group">
                                    <label>giá</label>
                                    <p>{{ $skus->price }}</p>
                                </div>
                                <div class="form-group">
                                    <label>số lượng</label>
                                    <p>{{ $skus->quantity }}</p>
                                </div>
                                <div class="form-group">
                                    <label>barcode</label>
                                    <p>
                                        {{ $skus->barcode }}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label>create at</label>
                                    <p>{{ $skus->created_at }}</p>
                                </div>
                                <div class="form-group">
                                    <label>ngày sửa</label>
                                    <p>{{ $skus->updated_at }}</p>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('admin.product.product_attribute.index', $product) }}"
                                        class="btn btn-success">
                                        Quay lại</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
