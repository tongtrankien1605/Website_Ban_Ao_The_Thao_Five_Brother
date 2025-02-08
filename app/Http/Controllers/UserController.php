<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::first();
        // dd($user);
        return view('client.my-account', compact('user'));
    }

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
    public function show(User $user)
    {
        $user = Auth::user();
        if (Auth::id() === null) {
            return abort(403);
        }
        // dd($user);

        return view('client.my-account', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // $user = Auth::user();
        // if (Auth::id() === null) {
        //     return abort(403);
        // }
        // // dd($user);

        // return view('client.my-account', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // $data = $request->validate([
        //     'name' => 'required|max:255',
        //     'email' => [
        //         'required',
        //         'email',
        //         Rule::unique('users')->ignore($user->id),
        //     ],
        //     'password' => [
        //         'nullable',
        //         'confirmed',
        //     ],
        // ]);
        // // dd($request->all($data));
        // if (!empty($data['password'])) {
        //     $data['password'] = Hash::make($data['password']);
        // } else {
        //     unset($data['password']);
        // }

        // try {

        //     $this->$user->update($data);
        //     return back()->with('success', true);
        // } catch (\Throwable $th) {
        //     return back()->with('success', false)->with('error', $th->getMessage());
        // }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
