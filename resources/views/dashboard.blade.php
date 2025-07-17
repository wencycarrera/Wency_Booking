@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="//unpkg.com/alpinejs" defer></script>

<div x-data="{ showBookings: false }" class="min-h-screen bg-gradient-to-tr from-purple-200 via-pink-200 to-purple-300 py-6 px-3 sm:px-4 flex justify-center items-start">
    <div class="w-full max-w-md sm:max-w-3xl space-y-6">

        <!-- Success message -->
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded-lg border border-green-300 text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- Welcome -->
        <div class="bg-gradient-to-r from-pink-300 via-purple-300 to-pink-200 shadow-md rounded-xl p-6 border border-pink-300">
            <h3 class="text-2xl font-bold text-purple-700">Welcome, {{ Auth::user()->name }}!</h3>
            <p class="text-purple-600 mt-2 text-base">Below is your dashboard summary:</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            <!-- Total Bookings (click to toggle bookings list) -->
            <div 
                @click="showBookings = !showBookings" 
                class="bg-white border-l-4 border-pink-400 shadow-md p-5 rounded-lg cursor-pointer hover:bg-pink-50 transition"
                title="Click to toggle your bookings"
                role="button" tabindex="0"
                @keydown.enter.prevent="showBookings = !showBookings"
                @keydown.space.prevent="showBookings = !showBookings"
            >
                <h4 class="text-purple-700 font-semibold text-lg">Total Bookings</h4>
                <p class="text-2xl font-bold text-pink-500 mt-1">{{ $totalBookings }}</p>
            </div>

            <!-- Total Users (Clickable Card without list) -->
            <a href="{{ route('users.index') }}" class="block group" title="View all users">
                <div class="bg-white border-l-4 border-purple-400 shadow-md p-5 rounded-lg hover:bg-purple-50 transition cursor-pointer">
                    <h4 class="text-purple-700 font-semibold text-lg group-hover:text-purple-900">Total Users</h4>
                    <p class="text-2xl font-bold text-purple-500 group-hover:text-purple-700">{{ $totalUsers }}</p>
                </div>
            </a>
        </div>

        <!-- Booking List: Hidden initially -->
        <div
            x-show="showBookings"
            x-transition
            class="bg-gradient-to-r from-purple-100 via-pink-100 to-purple-100 shadow-md rounded-xl p-6 border border-purple-200"
            style="display: none;"
        >
            <h3 class="text-xl font-semibold text-purple-600 mb-5">Your Bookings</h3>

            @if ($bookings->count())
                <div class="space-y-4">
                    @foreach ($bookings as $booking)
                        <div class="border border-purple-300 rounded-md p-4 bg-white hover:shadow-lg transition duration-300">
                            <h4 class="text-lg font-bold text-purple-700">{{ $booking->title }}</h4>
                            <p class="text-purple-600 text-sm mt-1">{{ $booking->description }}</p>
                            <p class="text-xs text-purple-500 mt-2">
                                📅 {{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y h:i A') }}
                            </p>

                            <div class="mt-3 flex space-x-4">
                                <a href="{{ route('bookings.edit', $booking->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-semibold underline"
                                >
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('bookings.destroy', $booking->id) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this booking?');"
                                      class="inline"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold underline">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-sm text-purple-500 italic">You don’t have any bookings yet.</p>
            @endif
        </div>

    </div>
</div>
@endsection
