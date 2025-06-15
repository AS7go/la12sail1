<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="container mt-6">
        <div class="row">
            <div class="col-md-12">

                <form method="post" action="{{route('update-post', $post->id)}}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="exampleInputEmail1">Title</label>
                        <input type="text" name="name" value="{{$post->name}}" class="form-control" id="exampleInputEmail1 {{-- required --}}">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Text</label>
                        <textarea name="text" class="form-control mb-2" id="exampleFormControlTextarea1" rows="10">{{$post->text}}</textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-success mb-2">Edit Post</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>