@extends('client.layouts.master')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section"
        style="background-image: url(/client/assets/images/blog/single-blog-page-title.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col text-center">

                    <h1>Lates and new Trens for baby fashion</h1>

                </div>
            </div>
        </div>
    </div><!-- Page Banner Section End -->

    <!-- Blog Section Start -->
    <div class="blog-section section section-padding">
        <div class="container">
            <div class="row row-30 mbn-40">

                <div class="col-xl-9 col-lg-8 col-12 order-1 order-lg-2 mb-40">
                    <div class="single-blog">
                        <div class="image-wrap">
                            {{-- <h4 class="date">{{ $post->published_month }} <span>{{ $post->published_day }}</span></h4> --}}
                            <h4 class="date">{{ $post->published_at }}</h4>
                            {{-- <a class="image" href="single-blog.html"><img
                                    src="/client/assets/images/blog/single-blog.jpg" alt=""></a> --}}
                            <a class="image" href="{{ route('post.show', $post) }}"><img
                                    src="{{ Storage::url($post->image) }}" alt="image post" ></a>
                        </div>
                        <div class="content">
                            <ul class="meta">
                                <li><a href="#"><img src="/client/assets/images/blog/blog-author-1.jpg"
                                            alt="Blog Author">{{ $post->author }}</a></li>
                                <li><a href="#">25 Likes</a></li>
                                <li><a href="#">05 Views</a></li>
                            </ul>
                            <div class="desc">
                                <p>{{ $post->content }}</p>
                                {{-- <blockquote class="blockquote">
                                    <p>Jadusona is one of the most of a exclusive Baby shop in the enim ipsam voluptatem
                                        quia voluptas sit aspernatur aut odit aut fugit, sed quia res eos qui ratione
                                        voluptatem sequi Neque porro quisquam est.</p>
                                    <span>Arif Khan - Designer</span>
                                </blockquote>
                                <p>Jadusona is one of the most of a exclusive Baby shop in the enim ipsam voluptatem quia
                                    voluptas sit aspernatur aut odit aut fugit, sed quia res eos qui ratione voluptatem
                                    sequi Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur,
                                    adipisci velit, sed quia non numquam eius modi tempora inform enim ipsam voluptatem quia
                                    voluptas sit aspernatur aut odit aut fugit.</p> --}}
                            </div>

                            <div class="blog-footer row mt-45">

                                <div class="post-tags col-lg-6 col-12 mv-15">
                                    <h4>Tags:</h4>
                                    <ul class="tag">
                                        <li><a href="#">New</a></li>
                                        <li><a href="#">brand</a></li>
                                        <li><a href="#">black</a></li>
                                        <li><a href="#">white</a></li>
                                        <li><a href="#">chire</a></li>
                                        <li><a href="#">table</a></li>
                                        <li><a href="#">Lorem</a></li>
                                        <li><a href="#">ipsum</a></li>
                                        <li><a href="#">dolor</a></li>
                                        <li><a href="#">sit</a></li>
                                        <li><a href="#">amet</a></li>
                                    </ul>
                                </div>

                                <div class="post-share col-lg-6 col-12 mv-15">
                                    <h4>Share:</h4>
                                    <ul class="share">
                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
                                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                        <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="comment-wrap mt-40">

                        <h3>3 Comments</h3>
                        <ul class="comment-list">
                            <li>
                                <div class="single-comment">
                                    <div class="image"><img src="/client/assets/images/blog/author-1.jpg" alt="">
                                    </div>
                                    <div class="content">
                                        <h4>Frank Warren</h4>
                                        <span>29/06/2022 &nbsp;&nbsp;-<a href="#">replay</a></span>
                                        <p>orem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                            incidi ut labore et dolo magna aliqua. Ut enim ad minim veniam, quis nostrud
                                            exercitation.</p>
                                    </div>
                                </div>
                                <ul class="child-comment">
                                    <li>
                                        <div class="single-comment">
                                            <div class="image"><img src="/client/assets/images/blog/author-3.jpg" alt="">
                                            </div>
                                            <div class="content">
                                                <h4>Ronald Black</h4>
                                                <span>29/06/2022 &nbsp;&nbsp;-<a href="#">replay</a></span>
                                                <p>orem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                    tempor incidi ut labore et dolo magna aliqua. Ut enim ad minim veniam,
                                                    quis nostrud exercitation.</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <div class="single-comment">
                                    <div class="image"><img src="/client/assets/images/blog/author-2.jpg" alt="">
                                    </div>
                                    <div class="content">
                                        <h4>Beverly Cook</h4>
                                        <span>29/06/2022 &nbsp;&nbsp;-<a href="#">replay</a></span>
                                        <p>orem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                            incidi ut labore et dolo magna aliqua. Ut enim ad minim veniam, quis nostrud
                                            exercitation.</p>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <h3>Leave a Comment</h3>
                        <div class="comment-form">
                            <form action="#">
                                <div class="row row-10">
                                    <div class="col-md-6 col-12 mb-20"><input placeholder="Name" type="text"></div>
                                    <div class="col-md-6 col-12 mb-20"><input placeholder="Email" type="email"></div>
                                    <div class="col-12 mb-20">
                                        <textarea placeholder="Message"></textarea>
                                    </div>
                                    <div class="col-12"><input value="submit" type="submit"></div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-12 order-2 order-lg-1 mb-40">

                    <div class="sidebar">
                        <h4 class="sidebar-title">Category</h4>
                        <ul class="sidebar-list">
                            <li><a href="#">Shart <span class="num">18</span></a></li>
                            <li><a href="#">Pants <span class="num">09</span></a></li>
                            <li><a href="#">T-Shart <span class="num">05</span></a></li>
                            <li><a href="#">Tops <span class="num">03</span></a></li>
                            <li><a href="#">Kid's Clothes <span class="num">15</span></a></li>
                            <li><a href="#">Watch <span class="num">07</span></a></li>
                            <li><a href="#">Accessories <span class="num">02</span></a></li>
                        </ul>
                    </div>

                    <div class="sidebar">
                        <h4 class="sidebar-title">Archive</h4>
                        <ul class="sidebar-list">
                            <li><a href="#">July 2022</a></li>
                            <li><a href="#">June 2022</a></li>
                            <li><a href="#">May 2022</a></li>
                            <li><a href="#">April 2022</a></li>
                            <li><a href="#">March 2022</a></li>
                            <li><a href="#">February 2022</a></li>
                        </ul>
                    </div>

                    <div class="sidebar">
                        <h4 class="sidebar-title">Lastest Blog</h4>
                        <div class="sidebar-blog-wrap">
                            @foreach ($data as $post)
                                <div class="sidebar-blog">
                                    <a href="{{ route('post.show', $post->id) }}" class="image"><img
                                            src="{{ Storage::url($post->image) }}" alt=""></a>
                                    <div class="content">
                                        <a href="{{ route('post.show', $post->id) }}" class="title">{{$post->title}}</a>
                                        <span class="date">{{$post->published_at}}</span>
                                    </div>
                                </div>
                            @endforeach
                            {{-- <div class="sidebar-blog">
                                <a href="single-blog.html" class="image"><img src="/client/assets/images/blog/blog-2.jpg"
                                        alt=""></a>
                                <div class="content">
                                    <a href="{{ route('post.show',$post) }}" class="title">New Collection New Trend all New
                                        Style</a>
                                    <span class="date">25 may</span>
                                </div>
                            </div>
                            <div class="sidebar-blog">
                                <a href="single-blog.html" class="image"><img src="/client/assets/images/blog/blog-3.jpg"
                                        alt=""></a>
                                <div class="content">
                                    <a href="{{ route('post.show',$post) }}" class="title">Lates and new Trens for baby
                                        fashion</a>
                                    <span class="date">25 may</span>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                    <div class="sidebar">
                        <h3 class="sidebar-title">Tags</h3>
                        <ul class="sidebar-tag">
                            <li><a href="#">New</a></li>
                            <li><a href="#">brand</a></li>
                            <li><a href="#">black</a></li>
                            <li><a href="#">white</a></li>
                            <li><a href="#">chire</a></li>
                            <li><a href="#">table</a></li>
                            <li><a href="#">Lorem</a></li>
                            <li><a href="#">ipsum</a></li>
                            <li><a href="#">dolor</a></li>
                            <li><a href="#">sit</a></li>
                            <li><a href="#">amet</a></li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </div><!-- Blog Section End -->
@endsection