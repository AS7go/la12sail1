<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-6">
        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-outline-success mb-3">Add post</button>
                <div class="card">
                    <h5 class="card-header">card 1</h5>
                    <div class="card-body">
                        <h5 class="card-title">title card 1</h5>
                        <p class="card-text mb-3">Text card 1</p>
                        <a href="#" class="btn btn-outline-primary">Edit</a>
                        <a href="#" class="btn btn-outline-danger">Delete</a>
                        <button type="submit" class="btn btn-outline-warning">Restore</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
