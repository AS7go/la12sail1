<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container mt-6">
        <div class="row">
            <div class="col-md-12">
                {{-- Кнопка "Добавить пост" --}}
                <a href="{{ route('add-post') }}" class="btn btn-outline-success mb-3">Add post</a>

                {{-- Кнопка "Скрыть/Показать удаленные посты" --}}
                {{-- Логика кнопки зависит от текущего состояния запроса 'show_deleted' --}}
                <a href="{{ route('dashboard', ['show_deleted' => request('show_deleted') ? 0 : 1]) }}"
                   class="btn btn-outline-secondary mb-3">
                    {{ request('show_deleted') ? 'Hide deleted posts' : 'Show deleted posts' }}
                </a>

                {{-- Перебираем посты, которые были переданы в представление --}}
                @foreach ($posts as $post)
                    {{-- Отображаем пост только если он не удален ИЛИ если запрошен показ удаленных постов --}}
                    {{-- Добавляем класс 'border-danger' для удаленных постов и 'mb-3' для отступа --}}
                    @if(!$post->trashed() || request('show_deleted'))
                        <div class="card mb-3 {{ $post->trashed() ? 'border-danger' : '' }}">
                            <h5 class="card-header">
                                {{ $post->name }}
                                {{-- Добавляем значок "Удалено" для мягко удаленных постов --}}
                                @if($post->trashed())
                                    <span class="badge bg-danger">Deleted</span>
                                @endif
                            </h5>
                            <div class="card-body">
                                {{-- <p>{{ $post->created_at }}</p>
                                <p>{{ $post->text }}</p> --}}

                                <p>Created {{ $post->created_at }}</p>
                                <p>Updated {{ $post->updated_at }}</p> 
                                <p>{{ $post->text }}</p>
 
                                {{-- Кнопки действий: Edit, Delete, Restore --}}
                                {{-- Если пост мягко удален, показываем только кнопку "Restore" --}}
                                @if ($post->trashed())
                                    {{-- Форма для кнопки "Restore" --}}
                                    <form action="{{ route('restore-post', $post->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning">Restore</button>
                                    </form>

                                    {{-- Форма для кнопки "Полное Удаление" --}}
                                    <form action="{{ route('force-delete-post', $post->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Вы уверены, что хотите НАВСЕГДА удалить этот пост?')">
                                        @csrf
                                        @method('DELETE') {{-- Обязательно используем DELETE метод --}}
                                        <button type="submit" class="btn btn-outline-danger">Force Delete</button>
                                    </form>
                                @else
                                    {{-- Если пост НЕ удален, показываем "Edit" и "Delete" --}}
                                    <a href="{{ route('edit-post', $post->id) }}" class="btn btn-outline-primary">Edit</a>

                                    {{-- Форма для кнопки "Delete" --}}
                                    <form action="{{ route('delete-post', $post->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                        @csrf
                                        @method('DELETE') {{-- Используем метод DELETE для удаления --}}
                                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach

            </div>
        </div>
    </div>

</x-app-layout>


