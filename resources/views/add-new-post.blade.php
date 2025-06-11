<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="container mt-6">
        <div class="row">
            <div class="col-md-12">


                {{-- Вывод статуса --}}
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{route('store-post')}}">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Title</label>
                        {{-- <input type="text" name="name" class="form-control" id="exampleInputEmail1" required> --}}
                        <input type="text" name="name" class="form-control" id="exampleInputEmail1">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Text</label>
                        <textarea name="text" class="form-control mb-2" id="exampleFormControlTextarea1" rows="10"></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-success mb-2">Add New Post</button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>