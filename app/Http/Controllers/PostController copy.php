<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index(Request $request) // Объявляем метод index, который будет обрабатывать запросы для отображения списка постов.
    {
        $query = Post::orderBy('created_at', 'desc');

        if ($request->has('show_deleted') && $request->input('show_deleted')) {
            $query->withTrashed(); // Включаем мягко удаленные посты в выборку
        }

        $posts = $query->get();

        return view('dashboard', compact('posts'));
    }

    public function create()
    {
        return view('add-new-post');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'text' => 'required|string',
        ]);

        $post = Post::create($request->all());

        return redirect()->route('dashboard')->with('success', "Post ({$post->name}) added!");

    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);

        return view('edit-new-post', compact(['post']));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'text'=>'required|string',
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());

        return redirect()->route('dashboard')->with('success', "Post ({$post->name}) updated!");
    }


    public function destroy($id) // Объявляем метод destroy для удаления поста, принимающий ID поста из URL.
    {
        $post = Post::findOrFail($id);
        $post->delete();
    
        return redirect()->route('dashboard')->with('success', "Post ({$post->name}) deleted!");
    }
    
    public function restore($id) // Объявляем метод restore для восстановления мягко удаленного поста, принимающий ID поста.
    {
        $post = Post::onlyTrashed()->findOrFail($id); // Находим только среди удаленных
        $post->restore(); // Восстанавливаем пост
    
        return redirect()->route('dashboard')->with('success', "Post ({$post->name}) restored!");
    }
    
    public function forceDelete($id) // Объявляем метод forceDelete для физического удаления поста из базы данных.
    {
        // Находим пост, который был мягко удален.
        // Используем onlyTrashed() для поиска только среди удаленных записей.
        $post = Post::onlyTrashed()->findOrFail($id);
    
        // Получаем имя поста до его полного удаления для сообщения
        $post_name = $post->name;
    
        // Выполняем физическое удаление записи из базы данных.
        $post->forceDelete();
    
        // Перенаправляем на дашборд с сообщением "Пост (название) удален навсегда".
        return redirect()->route('dashboard')->with('success', "Post ({$post_name}) permanently deleted!");
    }
    

}
