<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container mt-6">
        <div class="row">
            <div class="col-md-12">

                <a href="{{ route('users.create') }}" class="btn btn-outline-success mb-2">Add new user</a>

                @foreach ($users as $user)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            User Name: <strong>{{ $user->name }}</strong>
                        </div>
                        <div class="card-body">
                            <p class="card-text mb-2">
                                Roles:
                                @if ($user->roles->isNotEmpty())
                                    @foreach ($user->roles as $role)
                                        <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No roles assigned</span>
                                @endif
                            </p>
                            <p class="card-text">
                                Permissions:
                                {{-- Выводим все разрешения пользователя (прямые и через роли) --}}
                                @php
                                    // Получаем все разрешения пользователя (прямые и через роли)
                                    $allPermissions = $user->getAllPermissions();
                                @endphp
                                @if ($allPermissions->isNotEmpty())
                                    @foreach ($allPermissions as $permission)
                                        <span class="badge bg-info text-dark me-1">{{ $permission->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No permissions assigned</span>
                                @endif

                            </p>
                            <div class="mt-3">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" 
                                    style="display: inline;" onsubmit="return confirmDelete()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

</x-app-layout>
