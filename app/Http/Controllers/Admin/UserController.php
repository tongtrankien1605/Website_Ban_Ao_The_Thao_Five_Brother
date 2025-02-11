<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private User $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $users = User::select('users.*', 'roles.user_role')->join('roles', function ($q) {
            $q->on('roles.id', '=', 'users.role');
            $q->whereNull('roles.deleted_at');
        })->latest('users.updated_at')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::whereNull('deleted_at')->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $newUser = new User();
            $newUser->name = $request->name;
            $newUser->phone_number = $request->phone_number;
            $newUser->email = $request->email;
            $newUser->password = Hash::make($request->password);
            $newUser->gender = $request->gender;
            $newUser->birthday = $request->birthday;
            $newUser->role = $request->role;

            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('public/avatars');
                $newUser->avatar = str_replace('public/', '', $avatarPath);
            }
            $newUser->save();

            return redirect()->route('admin.user.index')->with('success', 'Thêm người dùng thành công!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra!');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::whereNull('deleted_at')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            $user->name = $request->name;
            $user->phone_number = $request->phone_number;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->birthday = $request->birthday;
            $user->role = $request->role;

            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    Storage::delete('public/' . $user->avatar);
                }
                $avatarPath = $request->file('avatar')->store('public/avatars');
                $user->avatar = str_replace('public/', '', $avatarPath);
            }

            $user->save();

            return redirect()->route('admin.user.index')->with('success', 'Cập nhật người dùng thành công!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra!');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return back()->with('success', true);
        } catch (\Throwable $th) {
            dd($th);
            return back()
                ->with('success', false)
                ->with('error', $th->getMessage());
        }
    }
    public function forceDestroy(User $user)
    {
        try {
            $user->delete();
            if (!empty($user->avatar) && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }
            return back()->with('success', true);
        } catch (\Throwable $th) {
            return back()
                ->with('success', false)
                ->with('error', $th->getMessage());
        }
    }

    public function indexDelete()
    {
        $users = User::onlyTrashed()->select('users.*', 'roles.user_role')->join('roles', function ($q) {
            $q->on('roles.id', '=', 'users.role');
            $q->whereNull('roles.deleted_at');
        })->latest('users.updated_at')->paginate(20);
        return view('admin.users.indexDelUser', compact('users'));
    }
}
