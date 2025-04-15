@extends('admin.layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Chi Tiết Bài Viết</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Danh Sách Bài Viết</a></li>
                            <li class="breadcrumb-item active">Chi Tiết Bài Viết</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $post->title }}</h3>
                </div>
                <div class="card-body">
                    <p><strong>Tác Giả:</strong> {{ $post->author }}</p>
                    <p><strong>Ngày Xuất Bản:</strong> {{ \Carbon\Carbon::parse($post->published_at)->format('d/m/Y') }}</p>
                    <p><strong>Mô tả:</strong> {!! $post->short_description !!}</p>

                    @if ($post->image)
                        <div class=" d-flex align-items-center justify-content-center mb-3">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="img-fluid mb-3"
                                height="150px">
                        </div>
                    @endif

                    <div>{!! $post->content !!}</div>



                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Quay Lại</a>
                </div>
            </div>
        </div>
    </div>
@endsection
