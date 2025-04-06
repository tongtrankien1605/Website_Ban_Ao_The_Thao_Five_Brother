@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>Blog</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="{{ route('index') }}">Home</a></li>
                        <li><a href="{{ route('post.index') }}">Blog</a></li>
                    </ul>

                </div>
            </div>
        </div>
    </div><!-- Page Banner Section End -->

    <!-- Blog Section Start -->
    <div class="blog-section section section-padding">
        <div class="container">
            <div class="row">
                @foreach ($data as $post)
                    <div class="col-lg-6 col-12 mb-50">
                        <div class="blog-item">
                            <div class="image-wrap">
                                <h4 class="date">{{ $post->published_month }} <span>{{$post->published_day}}</span> </h4>
                                {{-- <h4 class="date">{{ $post->published_at }}</h4> --}}
                                {{-- @if($post->image) --}}
                                    <a class="image" href="{{ route('post.show',$post) }}"><img src="{{ Storage::url($post->image) }}" width="100" alt="image post" height="130px"></a>
                                {{-- @endif --}}
                            </div>
                            <div class="content">
                                <h4 class="title"><a href="{{ route('post.show', $post) }}">{{ $post->title }}</a>
                                </h4>
                                <div class="desc">
                                    <p>{{$post->short_description}}</p>
                                </div>
                                <ul class="meta">
                                    <li><a href="#"><img src="/client/assets/images/blog/blog-author-1.jpg"
                                                alt="Blog Author">{{$post->author}}</a></li>
                                    <li><a href="#">25 Likes</a></li>
                                    <li><a href="#">05 Views</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach


                {{-- <div class="col-lg-6 col-12 mb-50">
                    <div class="blog-item">
                        <div class="image-wrap">
                            <h4 class="date">May <span>20</span></h4>
                            <a class="image" href="{{ route('post.show',$post) }}"><img
                                    src="/client/assets/images/blog/blog-2.jpg" alt=""></a>
                        </div>
                        <div class="content">
                            <h4 class="title"><a href="{{ route('post.show',$post) }}">New Collection New Trend all New
                                    Style</a></h4>
                            <div class="desc">
                                <p>Jadusona is one of the most of a exclusive Baby shop in the</p>
                            </div>
                            <ul class="meta">
                                <li><a href="#"><img src="/client/assets/images/blog/blog-author-2.jpg"
                                            alt="Blog Author">Takiya</a></li>
                                <li><a href="#">25 Likes</a></li>
                                <li><a href="#">05 Views</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-12 mb-50">
                    <div class="blog-item">
                        <div class="image-wrap">
                            <h4 class="date">May <span>25</span></h4>
                            <a class="image" href="{{ route('post.show',$post) }}"><img
                                    src="/client/assets/images/blog/blog-3.jpg" alt=""></a>
                        </div>
                        <div class="content">
                            <h4 class="title"><a href="{{ route('post.show',$post) }}">Lates and new Trens for baby
                                    fashion</a>
                            </h4>
                            <div class="desc">
                                <p>Jadusona is one of the most of a exclusive Baby shop in the</p>
                            </div>
                            <ul class="meta">
                                <li><a href="#"><img src="/client/assets/images/blog/blog-author-1.jpg"
                                            alt="Blog Author">Muhin</a></li>
                                <li><a href="#">25 Likes</a></li>
                                <li><a href="#">05 Views</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-12 mb-50">
                    <div class="blog-item">
                        <div class="image-wrap">
                            <h4 class="date">May <span>20</span></h4>
                            <a class="image" href="{{ route('post.show',$post) }}"><img
                                    src="/client/assets/images/blog/blog-4.jpg" alt=""></a>
                        </div>
                        <div class="content">
                            <h4 class="title"><a href="{{ route('post.show',$post) }}">New Collection New Trend all New
                                    Style</a></h4>
                            <div class="desc">
                                <p>Jadusona is one of the most of a exclusive Baby shop in the</p>
                            </div>
                            <ul class="meta">
                                <li><a href="#"><img src="/client/assets/images/blog/blog-author-2.jpg"
                                            alt="Blog Author">Takiya</a></li>
                                <li><a href="#">25 Likes</a></li>
                                <li><a href="#">05 Views</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-12 mb-50">
                    <div class="blog-item">
                        <div class="image-wrap">
                            <h4 class="date">May <span>25</span></h4>
                            <a class="image" href="{{ route('post.show',$post) }}"><img
                                    src="/client/assets/images/blog/blog-1.jpg" alt=""></a>
                        </div>
                        <div class="content">
                            <h4 class="title"><a href="{{ route('post.show',$post) }}">Lates and new Trens for baby
                                    fashion</a></h4>
                            <div class="desc">
                                <p>Jadusona is one of the most of a exclusive Baby shop in the</p>
                            </div>
                            <ul class="meta">
                                <li><a href="#"><img src="/client/assets/images/blog/blog-author-1.jpg"
                                            alt="Blog Author">Muhin</a></li>
                                <li><a href="#">25 Likes</a></li>
                                <li><a href="#">05 Views</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-12 mb-50">
                    <div class="blog-item">
                        <div class="image-wrap">
                            <h4 class="date">May <span>20</span></h4>
                            <a class="image" href="{{ route('post.show',$post) }}"><img
                                    src="/client/assets/images/blog/blog-2.jpg" alt=""></a>
                        </div>
                        <div class="content">
                            <h4 class="title"><a href="{{ route('post.show',$post) }}">New Collection New Trend all New
                                    Style</a></h4>
                            <div class="desc">
                                <p>Jadusona is one of the most of a exclusive Baby shop in the</p>
                            </div>
                            <ul class="meta">
                                <li><a href="#"><img src="/client/assets/images/blog/blog-author-2.jpg"
                                            alt="Blog Author">Takiya</a></li>
                                <li><a href="#">25 Likes</a></li>
                                <li><a href="#">05 Views</a></li>
                            </ul>
                        </div>
                    </div>
                </div> --}}

                <div class="col-12">
                    <ul class="page-pagination">
                        {{$data->links()}}
                        {{-- <li><a href="#"><i class="fa fa-angle-left"></i></a></li>
                        <li class="active"><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#"><i class="fa fa-angle-right"></i></a></li> --}}
                    </ul>
                </div>

            </div>
        </div>
    </div><!-- Blog Section End -->
@endsection
