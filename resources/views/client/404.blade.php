@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>404</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="{{ route('index') }}">Home</a></li>
                        <li><a href="{{ route('404') }}">404</a></li>
                    </ul>

                </div>
            </div>
        </div>
    </div><!-- Page Banner Section End -->

    <!-- Page Section Start -->
    <div class="page-section section section-padding">
        <div class="container">
            <div class="row">

                <div class="col-lg-6 col-md-8 col-12 mx-auto">
                    <div class="error-404">
                        <h1>404</h1>
                        <h2>OPPS! PAGE NOT BE FOUND</h2>
                        <p>Sorry but the page you are looking for does not exist, have been removed, name changed or is
                            temporarity unavailable.</p>
                        <form action="#" class="searchform mb-30">
                            <input type="text" name="search" id="error_search" placeholder="Search...">
                            <button type="submit" class="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
                        <a href="{{route('index')}}" class="back-btn">Back to home page</a>
                    </div>
                </div>

            </div>
        </div>
    </div><!-- Page Section End -->
@endsection
