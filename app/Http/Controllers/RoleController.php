<?php

namespace App\Http\Controllers; // Пространство имен контроллеров.

use Spatie\Permission\Models\Role; // Импорт модели Role из пакета Spatie.
use Spatie\Permission\Models\Permission; // Импорт модели Permission.
use Illuminate\Validation\Rule; // Импорт класса Rule для валидации.
use Illuminate\Http\Request; // Импорт класса Request.

class RoleController extends Controller // Объявление класса RoleController.
{
    /**
     * Отображает список всех ролей, исключая 'super-user'.
     */
    public function index() // Метод для отображения списка ролей.
    {
        // Получает все роли, кроме 'super-user', для отображения.
        $roles = Role::orderBy('name')->where('name', '!=', 'super-user')->get();

        return view('roles.index', compact('roles')); // Возвращает представление 'roles.index'.
    }

    /**
     * Отображает форму для создания новой роли.
     */
    public function create() // Метод для отображения формы создания роли.
    {
        $permissions = Permission::orderBy('name')->get(); // Получает все разрешения.

        return view('roles.create', compact([ // Возвращает представление 'roles.create'.
            'permissions'
        ]));
    }

    /**
     * Сохраняет новую роль, предотвращая создание роли 'super-user'.
     */
    public function store(Request $request) // Метод для сохранения новой роли.
    {
        $request->validate([ // Валидация входящих данных.
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions'=>'required',
            'permissions.*'=>'required|integer|exists:permissions,id',
        ]);

        // Проверка: предотвращает создание роли с именем 'super-user'.
        if ($request->name === 'super-user') {
            return redirect()->back()->withInput()->with('error', 'The "super-user" role cannot be created.'); // Перенаправляет с ошибкой.
        }

        $newRole = Role::create([ // Создает новую роль.
            'name'=>$request->name,
        ]);
        $permissions = Permission::whereIn('id', $request->permissions)->get(); // Получает выбранные разрешения.

        $newRole->syncPermissions($permissions); // Синхронизирует разрешения с ролью.

        return redirect()->route('roles.index')->with('success', "Role ({$newRole->name}) added!"); // Перенаправляет с сообщением.
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role) // Метод для отображения одной роли (не реализован).
    {
        //
    }

    /**
     * Отображает форму для редактирования существующей роли, предотвращая доступ к 'super-user'.
     */
    public function edit(Role $role) // Метод для отображения формы редактирования роли.
    {
        // Защита: предотвращает редактирование роли 'super-user' (выбросит 404).
        $role = Role::where('name', '!=', 'super-user')->findOrFail($role->id);

        $permissions = Permission::orderBy('name')->get(); // Получает все разрешения.

        return view('roles.edit', compact([ // Возвращает представление 'roles.edit'.
            'permissions',
            'role',
        ]));
    }

    /**
     * Обновляет существующую роль, предотвращая изменение на 'super-user' и обновление самой 'super-user'.
     */
    public function update(Request $request, Role $role) // Метод для обновления роли.
    {
        // Защита: предотвращает обновление роли 'super-user' (выбросит 404).
        $role = Role::where('name', '!=', 'super-user')->findOrFail($role->id);

        $request->validate([ // Валидация входящих данных.
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions'=>'required',
            'permissions.*'=>'required|integer|exists:permissions,id',
        ]);

        // Проверка: предотвращает изменение имени роли на 'super-user'.
        if ($request->name === 'super-user') {
            return redirect()->back()->withInput()->with('error', 'The role name "super-user" cannot be assigned.'); // Перенаправляет с ошибкой.
        }

        $role->update([ // Обновляет имя роли.
            'name'=>$request->name,
        ]);
        $permissions = Permission::whereIn('id', $request->permissions)->get(); // Получает выбранные разрешения.
        $role->syncPermissions($permissions); // Синхронизирует разрешения.

        return redirect()->route('roles.index')->with('success', "Role ({$role->name}) updated!"); // Перенаправляет с сообщением.
    }

    /**
     * Удаляет роль, предотвращая удаление 'super-user'.
     */
    public function destroy(Role $role) // Метод для удаления роли.
    {
        // Защита: предотвращает удаление роли "super-user".
        if ($role->name === 'super-user') {
            return redirect()->route('roles.index')->with('error', 'The "super-user" role cannot be deleted.'); // Перенаправляет с ошибкой.
        }

        $role->delete(); // Удаляет роль.

        return redirect()->route('roles.index')->with('success', "Role ({$role->name}) deleted!"); // Перенаправляет с сообщением.
    }
}
