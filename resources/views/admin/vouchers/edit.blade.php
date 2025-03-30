@extends('admin.layouts.index')
@section('title')
    Sửa Voucher
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Sửa Voucher</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="post" enctype="multipart/form-data" action="{{ route('admin.vouchers.update',$voucher->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="code">Mã voucher</label>
                                        <input type="text" class="form-control" id="code" name="code"
                                            placeholder="Enter code" value="{{$voucher->code}}">
                                        @error('code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="discount_type">Loại giảm giá</label>
                                        <select class="form-control" id="discount_type" name="discount_type">
                                            <option value="percentage" {{ $voucher->discount_type == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
            <option value="fixed" {{ $voucher->discount_type == 'fixed' ? 'selected' : '' }}>Tiền mặt</option>
                                        </select>
                                        @error('discount_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="discount_value">Giá trị giảm giá</label>
                                        <input type="number" class="form-control" id="discount_value" name="discount_value"
                                            placeholder="Enter Phone number" value="{{$voucher->discount_value}}">
                                        @error('discount_value')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="max_discount_amount">Giảm giá tối đa</label>
                                        <input type="number" class="form-control" id="max_discount_amount" name="max_discount_amount" 
                                               value="{{ old('max_discount_amount', $voucher->max_discount_amount ?? '') }}">
                                    </div>                                    
                                    <div class="form-group">
                                        <label for="total_usage">Số lần sử dụng</label>
                                        <input type="number" class="form-control" id="total_usage" name="total_usage"
                                        value="{{$voucher->total_usage}}">
                                        @error('total_usage')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="start_date">Ngày bắt đầu</label>
                                        <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                                        value="{{$voucher->start_date}}">
                                        @error('start_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="end_date">Ngày kết thúc</label>
                                        <input type="datetime-local" class="form-control" id="end_date" name="end_date"
                                            placeholder="end_date" value="{{$voucher->end_date}}">
                                        @error('end_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Trạng thái</label>
                                        <input type="checkbox" value="1" class="form-control" id="status"
                                            name="status" {{ $voucher->status ? 'checked' : '' }}>
                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @if ($voucher->status==0)
                                        Hết hiệu lực
                                    @else
                                            Còn hiệu lực          
                            @endif  
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-danger">Quay
                                            lại</a>
                                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection




{{-- 


    <h1>Chỉnh sửa Voucher</h1>
    
    <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Mã Voucher:</label>
        <input type="text" name="code" value="{{ $voucher->code }}" required><br>

        <label>Loại giảm giá:</label>
        <select name="discount_type">
            <option value="percentage" {{ $voucher->discount_type == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
            <option value="fixed" {{ $voucher->discount_type == 'fixed' ? 'selected' : '' }}>Tiền mặt</option>
        </select><br>

        <label>Giá trị giảm giá:</label>
        <input type="number" step="0.01" name="discount_value" value="{{ $voucher->discount_value }}" required><br>

        <label>Tổng số lần sử dụng:</label>
        <input type="number" name="total_usage" value="{{ $voucher->total_usage }}" required><br>

        <label>Ngày bắt đầu:</label>
        <input type="datetime-local" name="start_date" value="{{ date('Y-m-d\TH:i', strtotime($voucher->start_date)) }}" required><br>

        <label>Ngày kết thúc:</label>
        <input type="datetime-local" name="end_date" value="{{ date('Y-m-d\TH:i', strtotime($voucher->end_date)) }}" required><br>

        <label>Trạng thái:</label>
        <input type="checkbox" name="status" value="1" {{ $voucher->status ? 'checked' : '' }}>

        <br>

        <button type="submit">Cập nhật</button>
    </form>

    <a href="{{ route('admin.vouchers.index') }}">Quay lại danh sách</a> --}}

