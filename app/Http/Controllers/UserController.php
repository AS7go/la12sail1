<?php

namespace App\Http\Controllers; // Пространство имен контроллеров.

use App\Models\User; // Импорт модели User.
use Illuminate\Http\Request; // Импорт класса Request для обработки запросов.
use Spatie\Permission\Models\Role; // Импорт модели Role из пакета Spatie.
use Illuminate\Support\Facades\Hash; // Импорт фасада Hash для хеширования паролей.
use Spatie\Permission\Models\Permission; // Импорт модели Permission (для getAllPermissions()).

class UserController extends Controller // Объявление класса UserController, наследующего базовый Controller.
{
    /**
     * Отображает список всех пользователей, исключая 'super-user'.
     */
    public function index() // Метод для отображения списка пользователей.
    {
        // Получает всех пользователей, кроме 'super-user', сортируя по дате создания.
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'super-user');
        })->orderBy('created_at', 'desc')->get();

        return view('users.index', compact([ // Возвращает представление 'users.index' с данными пользователей.
            'users'
        ]));
    }

    /**
     * Отображает форму для создания нового пользователя, исключая 'super-user' из списка ролей.
     */
    public function create() // Метод для отображения формы создания пользователя.
    {
        // Получает все роли, кроме 'super-user', для выпадающего списка.
        $roles = Role::where('name', '!=', 'super-user')->orderBy('name')->get();

        return view('users.create', compact([ // Возвращает представление 'users.create' с данными ролей.
            'roles',
        ]));
    }

    /**
     * Сохраняет нового пользователя, предотвращая присвоение роли 'super-user'.
     */
    public function store(Request $request) // Метод для сохранения нового пользователя.
    {
        $request->validate([ // Валидация входящих данных формы.
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        $selectedRole = Role::find($request->role_id); // Находит выбранную роль по ID.

        // Проверяет, не пытается ли пользователь присвоить роль 'super-user'.
        if ($selectedRole && $selectedRole->name === 'super-user') {
            return redirect()->back()->withInput()->with('error', 'The "super-user" role cannot be assigned.'); // Перенаправляет с ошибкой.
        }

        $user = User::create([ // Создает нового пользователя в базе данных.
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Хеширует пароль.
        ]);

        $user->assignRole($selectedRole->name); // Присваивает выбранную роль пользователю.

        // Перенаправляет на список пользователей с сообщением.
        return redirect()->route('users.index')->with('success', "User ({$user->name}) created."); 
    }

    /**
     * Метод для отображения одного пользователя (не реализован).
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Отображает форму для редактирования пользователя, исключая 'super-user' из списка ролей
     * и предотвращая редактирование самого 'super-user'.
     */
    public function edit(User $user) // Метод для отображения формы редактирования пользователя.
    {
        // Защита: предотвращает редактирование пользователя с ролью 'super-user'.
        if ($user->hasRole('super-user')) {
            // Перенаправляет с ошибкой.
            return redirect()->route('users.index')->with('error', 'The "super-user" cannot be edited through this interface.'); 
        }

        // Получает все роли, кроме 'super-user', для выпадающего списка.
        $roles = Role::where('name', '!=', 'super-user')->orderBy('name')->get();

        return view('users.edit', compact([ // Возвращает представление 'users.edit' с данными пользователя и ролей.
            'user',
            'roles',
        ]));
    }

    /**
     * Обновляет пользователя, предотвращая изменение на роль 'super-user'
     * и запрещая обновление самого 'super-user'.
     */
    public function update(Request $request, User $user) // Метод для обновления данных пользователя.
    {
        // Защита: предотвращает обновление пользователя с ролью 'super-user'.
        if ($user->hasRole('super-user')) {
            // Перенаправляет с ошибкой.
            return redirect()->route('users.index')->with('error', 'The "super-user" cannot be updated through this interface.');
        }

        $request->validate([ // Валидация входящих данных формы.
            'name'=>'required|max:255',
            'role_id'=>'required|integer|exists:roles,id',
        ]);

        $selectedRole = Role::find($request->role_id); // Находит выбранную роль по ID.

        // Проверяет, не пытается ли пользователь присвоить роль 'super-user' при обновлении.
        if ($selectedRole && $selectedRole->name === 'super-user') {
            return redirect()->back()->withInput()->with('error', 'The "super-user" role cannot be assigned.'); // Перенаправляет с ошибкой.
        }

        $user->update([ // Обновляет имя пользователя.
            'name'=>$request->name,
        ]);

        $user->syncRoles([$selectedRole->name]); // Синхронизирует роли пользователя.

        // Перенаправляет на users.index с сообщением.
        return redirect()->route('users.index')->with('success', "User ({$user->name}) updated.");
        // return redirect()->back()->with('success', "User ({$user->name}) updated."); // Перенаправляет назад с сообщением.
    }

    /**
     * Удаляет пользователя, предотвращая удаление 'super-user'.
     */
    public function destroy(User $user) // Метод для удаления пользователя.
    {
        // Защита: предотвращает удаление пользователя с ролью 'super-user'.
        if ($user->hasRole('super-user')) {
            return redirect()->route('users.index')->with('error', 'The "super-user" cannot be deleted.'); // Перенаправляет с ошибкой.
        }

        $userName = $user->name; // Сохраняет имя пользователя для сообщения.
        $user->delete(); // Удаляет пользователя.

        // Перенаправляет на список пользователей с сообщением.
        return redirect()->route('users.index')->with('success', "User ({$userName}) deleted.");
    }
}
