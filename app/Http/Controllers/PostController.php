<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Post::latest('published_at')->paginate(6);
        // dd($data);
        return view('client.blog', compact('data'));
    }
    // public function indexMain()
    // {
    //     $data = Post::latest('published_at')->limit(2)->get();
    //     dd($data);
    //     return view('client.index', compact('data'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $data = Post::latest('published_at')->limit(3)->get();
        // dd($data,$post);
        return view('client.single-blog', compact(['post', 'data']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
