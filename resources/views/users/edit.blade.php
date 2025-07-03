<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="container mt-6">
        <div class="row">
            <div class="col-md-12">
                
                <form method="post" action="{{route('users.update', $user->id)}}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text" name="name" value="{{$user->name}}" class="form-control mb-3" id="exampleInputEmail1">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="text" name="email" value="{{$user->email}}" class="form-control mb-3" id="exampleInputEmail1" readonly>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlSelect2">Role</label>
                        <select name="role_id" class="form-control mb-2" id="exampleFormControlSelect2">
                            @foreach($roles as $role)
                                <option value="{{$role['id']}}" 
                                	@if($user->hasRole($role['name'])) 
                                		selected 
                                	@endif>{{$role['name']}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                     
                    <button type="submit" class="btn btn-outline-success mb-2">Update User</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
