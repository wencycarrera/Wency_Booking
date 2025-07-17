@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- FullCalendar CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<div class="min-h-screen bg-gradient-to-tr from-purple-200 via-pink-100 to-purple-300 py-12 px-4">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl p-10 border border-pink-200">

        <h1 class="text-3xl font-bold text-center text-purple-700 mb-8">Edit Booking</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 mb-4 rounded-lg border border-green-300 text-center">
                {{ session('success') }}
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

        <form method="POST" action="{{ route('bookings.update', $booking->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="block font-medium text-purple-700 mb-1">Title</label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title', $booking->title) }}"
                    required
                    class="w-full border border-purple-300 bg-white text-purple-900 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 focus:border-pink-400"
                />
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block font-medium text-purple-700 mb-1">Description</label>
                <textarea
                    name="description"
                    id="description"
                    rows="3"
                    required
                    class="w-full border border-purple-300 bg-white text-purple-900 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 focus:border-pink-400"
                >{{ old('description', $booking->description) }}</textarea>
            </div>

            <!-- Hidden booking_date -->
            <input type="hidden" name="booking_date" id="booking_date" value="{{ \Carbon\Carbon::parse(old('booking_date', $booking->booking_date))->format('Y-m-d H:i') }}">

            <!-- Calendar -->
            <div>
                <label class="block font-medium text-purple-700 mb-2">Choose Appointment Date</label>
                <div id="calendar" class="rounded-xl border border-purple-300 p-4 bg-white shadow-sm"></div>
                @error('booking_date')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Selected Date Display -->
            <div class="mt-6">
                <label class="block font-medium text-purple-700 mb-1">Selected Date</label>
                <input type="text" id="selected_date" class="w-full border border-purple-300 rounded-lg px-4 py-2 bg-purple-50" readonly>
            </div>

            <!-- Time -->
            <div class="mt-4">
                <label class="block font-medium text-purple-700 mb-1">Select Time</label>
                <input type="time" id="selected_time" class="w-full border border-purple-300 rounded-lg px-4 py-2" required>
            </div>

            <!-- Live Preview -->
            <div class="mt-4 text-center text-pink-600 font-semibold text-lg" id="selected-slot">
                {{ old('booking_date', $booking->booking_date) ? 'Selected: ' . \Carbon\Carbon::parse(old('booking_date', $booking->booking_date))->format('F j, Y \a\t g:i A') : 'No slot selected.' }}
            </div>

            <!-- Submit -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-pink-500 hover:bg-pink-600 text-white text-lg font-semibold py-4 rounded-xl transition shadow-md">
                    Update Booking
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const booked = @json($bookedDates); // [{ booking_date: '...', title: '...' }]
    const calendarEl = document.getElementById('calendar');
    const bookingInput = document.getElementById('booking_date');
    const selectedDateInput = document.getElementById('selected_date');
    const selectedTimeInput = document.getElementById('selected_time');
    const preview = document.getElementById('selected-slot');

    let selectedDate = '';
    const currentBooking = bookingInput.value;

    const bookedDateTimes = booked.map(b =>
        new Date(b.booking_date).toISOString().slice(0, 16)
    );

    const fullyBookedDates = {};
    bookedDateTimes.forEach(dt => {
        const day = dt.split('T')[0];
        fullyBookedDates[day] = (fullyBookedDates[day] || 0) + 1;
    });

    const fullDates = Object.entries(fullyBookedDates)
        .filter(([_, count]) => count >= 1)
        .map(([day]) => day);

    const events = booked.map(b => ({
        title: b.title,
        start: b.booking_date,
        display: 'auto',
        backgroundColor: '#f87171',
        borderColor: '#f87171'
    })).concat(
        fullDates.map(date => ({
            start: date,
            display: 'background',
            backgroundColor: '#f3dcdc'
        }))
    );

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        events: events,
        dateClick: function (info) {
            const clickedDate = info.dateStr;

            if (fullDates.includes(clickedDate)) {
                alert('❌ This date is already booked and unavailable.');
                return;
            }

            selectedDate = clickedDate;
            selectedDateInput.value = new Date(clickedDate).toDateString();
            updateBookingDate();
        },
        dayCellDidMount: function (arg) {
            const day = arg.date.toISOString().slice(0, 10);
            if (fullDates.includes(day)) {
                arg.el.style.pointerEvents = 'none';
                arg.el.style.opacity = '0.4';
                arg.el.style.backgroundColor = '#f3dcdc';
            }
        }
    });

    selectedTimeInput.addEventListener('input', updateBookingDate);

    function updateBookingDate() {
        if (selectedDate && selectedTimeInput.value) {
            const fullDateTime = `${selectedDate}T${selectedTimeInput.value}`;
            const formattedForDB = `${selectedDate} ${selectedTimeInput.value}:00`;
            const isBooked = bookedDateTimes.includes(fullDateTime);

            if (isBooked && formattedForDB !== currentBooking) {
                preview.textContent = '❌ This time slot is already booked.';
                preview.classList.add('text-red-600');
                bookingInput.value = '';
            } else {
                preview.textContent = '✅ Selected: ' + new Date(fullDateTime).toLocaleString();
                preview.classList.remove('text-red-600');
                bookingInput.value = formattedForDB;
            }
        }
    }

    // Set initial values
    const initialDate = new Date(currentBooking);
    if (!isNaN(initialDate)) {
        selectedDate = initialDate.toISOString().split('T')[0];
        selectedDateInput.value = initialDate.toDateString();
        selectedTimeInput.value = initialDate.toTimeString().slice(0, 5);
    }

    calendar.render();
});
</script>
@endsection
