@extends('client.layouts.master')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @if(session('success'))
            <div class="alert alert-success">
                <h1>{{ session('success') }}</h1>
                {{-- <p>Your order has been successfully placed. Your order number is {{ $order->order_number }}.</p> --}}
            </div>
        @endif          
        </div>
    </div>
@endsection
