@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<div class="min-h-screen bg-gradient-to-br from-purple-100 via-pink-100 to-purple-200 py-12 px-4 flex justify-center">
    <div class="w-full max-w-3xl bg-white rounded-xl shadow-lg p-8 border border-purple-300">
        <h1 class="text-3xl font-bold text-purple-700 text-center mb-6">Booking Details</h1>

        <div class="space-y-6">
            <!-- Title -->
            <div>
                <label class="text-sm font-semibold text-purple-600">Title</label>
                <div class="mt-1 text-lg text-purple-800">{{ $booking->title }}</div>
            </div>

            <!-- Description -->
            <div>
                <label class="text-sm font-semibold text-purple-600">Description</label>
                <div class="mt-1 text-purple-800">{{ $booking->description }}</div>
            </div>

            <!-- Booking Date & Time -->
            <div>
                <label class="text-sm font-semibold text-purple-600">Booking Date & Time</label>
                <div class="mt-1 text-purple-800">
                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y \a\t h:i A') }}
                </div>
            </div>

            <!-- Calendar -->
            <div class="mt-6">
                <label class="text-sm font-semibold text-purple-600 mb-2 block">Booked Date Highlight</label>
                <div id="calendar" class="rounded-xl border border-purple-300 p-4 bg-white shadow-sm"></div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="{{ route('dashboard') }}"
               class="inline-block px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm">
                ← Back to Dashboard
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    // Only the booked date is disabled and marked
    const bookedDate = new Date(@json($booking->booking_date)).toISOString().slice(0, 10);

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: false,
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        events: [{
            title: 'Booked',
            start: @json($booking->booking_date),
            display: 'background',
            backgroundColor: '#f87171'
        }],
        dayCellDidMount: function (arg) {
            const day = arg.date.toISOString().slice(0, 10);
            if (day === bookedDate) {
                arg.el.style.pointerEvents = 'none';
                arg.el.style.opacity = '0.5';
                arg.el.style.backgroundColor = '#fca5a5';
            }
        }
    });

    calendar.render();
});
</script>
@endsection
