@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>Wishlist</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="{{ route('index') }}">Home</a></li>
                        <li><a href="{{ route('index_wishlist') }}">Wishlist</a></li>
                    </ul>

                </div>
            </div>
        </div>
    </div><!-- Page Banner Section End -->

    <!-- Page Section Start -->
    <div class="page-section section section-padding">
        <div class="container">

            <form action="#">
                <div class="row">
                    <div class="col-12">
                        <div class="cart-table table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="pro-thumbnail">Image</th>
                                        <th class="pro-title">Product</th>
                                        {{-- <th class="pro-price">Price</th>
                                        <th class="pro-quantity">Quantity</th> --}}
                                        <th class="pro-subtotal">Total</th>
                                        <th class="pro-remove">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($wishlists as $wl)
                                    <tr> 
                                        <td class="pro-thumbnail"><a href="{{route('product.show',$wl->product->id)}}"><img
                                            src="{{Storage::url($wl->product->image)}}" alt="" /></a>
                                </td>
                                <td class="pro-title"><a href="">{{$wl->product->name}}</a></td>
                                {{-- <td class="pro-price"><span class="amount">$25</span></td>
                                <td class="pro-quantity">
                                    <div class="pro-qty"><input type="text" value="1"></div>
                                </td> --}}
                                <td class="pro-add-cart"><a href="#">Add to cart</a></td>
                                <td class="pro-remove"><a href="{{route('delete_wishlist',$wl->product->id)}}">×</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div><!-- Page Section End -->
@endsection
