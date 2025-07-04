<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get(); //все пользователи

        // $users = User::whereDoesntHave('roles', function ($query) { //кроме роли super-user
        // // $users = User::whereHas('roles', function ($query) { // только с ролью super-user (admin, Admin1)
        //     $query->where('name', 'super-user');
        // })->orderBy('created_at', 'desc')->get();

        return view('users.index', compact([
            'users'
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('users.create', compact([
            'roles',
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $role = Role::find($request->role_id);
        $user->assignRole($role->name);

        return redirect()->route('users.index')->with('success', "User ({$user->name}) created.");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
         $roles = Role::orderBy('name')->get();

        return view('users.edit', compact([
            'user',
            'roles',
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'=>'required|max:255',
            'role_id'=>'required|integer|exists:roles,id',
        ]);

        $user->update([
            'name'=>$request->name,
        ]);
        $role = Role::find($request->role_id);

        $user->syncRoles([$role->name]);

        return redirect()->back()->with('success', "User ({$user->name}) updated.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', "User ({$user->name}) deleted.");
    }
}

