<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="container mt-6">
        <div class="row">
            <div class="col-md-12">

                <form method="post" action="{{route('roles.update', $role->id)}}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="exampleInputEmail1">Title</label>
                        <input type="text" name="name" value="{{$role->name}}" class="form-control mb-3" id="exampleInputEmail1">
                    </div>
                    @foreach ($permissions as $permission)
                        <div class="form-group form-check mb-3">
                            <input type="checkbox" value="{{$permission->id}}" @if($role->hasPermissionTo($permission->name)) checked @endif name="permissions[]" class="form-check-input" id="exampleCheck{{ $permission->id }}">
                            <label class="form-check-label" for="exampleCheck{{$permission->id}}">{{$permission->name}}</label>
                        </div>

                    @endforeach

                    <button type="submit" class="btn btn-outline-success mb-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
