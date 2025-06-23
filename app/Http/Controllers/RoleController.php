<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule; // <-- Добавлен импорт Rule для использования уникального правила

use Illuminate\Http\Request;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $roles=Role::orderBy('name')->where('name', '!=', 'super-user')->get(); // исключаем вывод super-user
        $roles=Role::orderBy('name')->get();

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('roles.create', compact([
            'permissions'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // 'name'=>'required|string|max:255', // Оригинальная строка
            // Рекомендуемое изменение: Добавлено правило 'unique:roles,name'
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions'=>'required',
            'permissions.*'=>'required|integer|exists:permissions,id',
        ]);

        $newRole = Role::create([
            'name'=>$request->name,
        ]);
        $permissions = Permission::whereIn('id', $request->permissions)->get();

        $newRole->syncPermissions($permissions);

        // Перенаправляем на страницу roles.index
        return redirect()->route('roles.index')->with('success', "Role ({$newRole->name}) added!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $role = Role::where('name', '!=', 'super-user')->findOrFail($role->id); //защита от подмены id super-user

        $permissions = Permission::orderBy('name')->get();

        return view('roles.edit', compact([
            'permissions',
            'role',
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            // 'name'=>'required|string|max:255',
            // Правило Rule::unique('roles', 'name')->ignore($role->id) гарантирует, что имя уникально,
            // но позволяет текущей редактируемой роли сохранить свое имя.
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions'=>'required',
            'permissions.*'=>'required|integer|exists:permissions,id',
        ]);

        $role = Role::where('name', '!=', 'super-user')->findOrFail($role->id); //защита от подмены id super-user

        $role->update([
            'name'=>$request->name,
        ]);
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', "Role ({$role->name}) updated!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->name === 'super-user') {
            return redirect()->route('roles.index')->with('error', 'Роль "super-user" не может быть удалена.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', "Role ({$role->name}) deleted!");
    }
}
