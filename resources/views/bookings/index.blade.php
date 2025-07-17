@extends('layouts.app')

@section('title', 'View Details of Booking')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="//unpkg.com/alpinejs" defer></script>

<div class="min-h-screen bg-gradient-to-tr from-purple-200 via-pink-200 to-purple-300 py-12 px-4 sm:px-6 flex justify-center">
    <div class="w-full max-w-5xl space-y-8">

        <!-- Page Title -->
        <h1 class="text-4xl font-extrabold text-center text-purple-700 mb-6 border-b-4 border-pink-400 inline-block pb-2 mx-auto">
            View Details of Booking
        </h1>

        <!-- Bookings Table Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-purple-300 p-8 overflow-x-auto">
            <table class="min-w-full table-auto border-collapse border border-purple-300 text-sm">
                <thead class="bg-purple-200 text-purple-800 font-semibold uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-3 border border-purple-300 text-left">#</th>
                        <th class="px-6 py-3 border border-purple-300 text-left">Title</th>
                        <th class="px-6 py-3 border border-purple-300 text-left">Date & Time</th>
                        <th class="px-6 py-3 border border-purple-300 text-left">Booked By</th>
                        <th class="px-6 py-3 border border-purple-300 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-purple-900">
                    @forelse ($bookings as $index => $booking)
                        <tr class="hover:bg-purple-50 even:bg-purple-50 transition-colors duration-200">
                            <td class="px-6 py-4 border border-purple-300">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 border border-purple-300">{{ $booking->title }}</td>
                            <td class="px-6 py-4 border border-purple-300">{{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y h:i A') }}</td>
                            <td class="px-6 py-4 border border-purple-300">{{ $booking->user->name }}</td>
                            <td class="px-6 py-4 border border-purple-300 text-center">
                                <a href="{{ route('bookings.show', $booking) }}" 
                                   class="inline-block px-4 py-1 text-sm font-semibold text-white bg-pink-500 rounded-lg hover:bg-pink-600 transition">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-400 italic">
                                No bookings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
