@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gradient-to-tr from-purple-200 via-pink-100 to-purple-300 py-12 px-4">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl p-10 border border-pink-200">

        <h1 class="text-3xl font-bold text-center text-purple-700 mb-8">Edit User</h1>

        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-3 mb-4 rounded-lg border border-red-300 text-center">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-3 mb-4 rounded-lg border border-red-300">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block font-medium text-purple-700 mb-1">Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="w-full border border-purple-300 bg-white text-purple-900 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 focus:border-pink-400"
                />
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block font-medium text-purple-700 mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    class="w-full border border-purple-300 bg-white text-purple-900 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 focus:border-pink-400"
                />
                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Submit & Cancel -->
            <div class="pt-4 flex justify-between items-center">
                <a href="{{ route('users.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-xl transition">
                    Cancel
                </a>

                <button type="submit"
                        class="bg-pink-500 hover:bg-pink-600 text-white font-semibold py-3 px-8 rounded-xl transition shadow-md">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
