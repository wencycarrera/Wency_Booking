<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingViewController extends Controller
{
    /**
     * Display the user's bookings and booked dates for the calendar.
     */
    public function index()
    {
        $user = Auth::user();

        $bookings = Booking::where('user_id', $user->id)
            ->orderBy('booking_date')
            ->get();

        // Send full info for calendar rendering
        $bookedDates = $bookings->map(function ($booking) {
            return [
                'booking_date' => Carbon::parse($booking->booking_date)->toIso8601String(),
                'title' => $booking->title,
            ];
        });

        return view('bookings.index', [
            'bookings' => $bookings,
            'bookedDates' => $bookedDates,
        ]);
    }

    /**
     * Show the booking creation form with booked dates.
     * If session flag 'resetBookingDates' is set (new user),
     * send empty bookedDates to reset the calendar.
     */
    public function create()
    {
        if (session('resetBookingDates')) {
            $bookedDates = [];  // No booked dates shown
            session()->forget('resetBookingDates');  // Clear flag so next visits show normal data
        } else {
            $bookedDates = Booking::orderBy('booking_date')
                ->get()
                ->map(function ($booking) {
                    return [
                        'booking_date' => Carbon::parse($booking->booking_date)->toIso8601String(),
                        'title' => $booking->title,
                    ];
                });
        }

        return view('bookings.create', compact('bookedDates'));
    }

    /**
     * Store a new booking.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'booking_date' => 'required|date|after_or_equal:now',
        ]);

        Booking::create([
            'user_id' => Auth::id(),
            ...$request->only(['title', 'description', 'booking_date']),
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking created successfully!');
    }

    /**
     * Show one booking.
     */
    public function show(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $bookedDates = [[
            'booking_date' => Carbon::parse($booking->booking_date)->toIso8601String(),
            'title' => $booking->title,
        ]];

        return view('bookings.show', compact('booking', 'bookedDates'));
    }

    /**
     * Show the edit form for a booking.
     */
    public function edit(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $bookedDates = Booking::where('id', '!=', $booking->id)
            ->get()
            ->map(function ($b) {
                return [
                    'booking_date' => Carbon::parse($b->booking_date)->toIso8601String(),
                    'title' => $b->title,
                ];
            });

        return view('bookings.edit', compact('booking', 'bookedDates'));
    }

    /**
     * Update a booking.
     */
    public function update(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'booking_date' => 'required|date|after_or_equal:now',
        ]);

        $booking->update($request->only(['title', 'description', 'booking_date']));

        return redirect()->route('dashboard')->with('success', 'Booking updated!');
    }

    /**
     * Delete a booking.
     */
    public function destroy(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking deleted!');
    }

    /**
     * Ensure the booking belongs to the logged-in user.
     */
    private function authorizeBooking(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
