<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\CourtBooking;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    public function index(Request $r)
    {
        $data = $r->validate(['date' => 'nullable|date_format:Y-m-d|after_or_equal:today']);
        $date = $data['date'] ?? now()->toDateString();

        return view('journey.booking', ['courts' => Court::all(), 'date' => $date,
            'reserved' => CourtBooking::where('date', $date)->get(),
            'mine' => $r->user() ? CourtBooking::with('court')->where('user_id', $r->user()->id)->orderByDesc('date')->paginate(10) : null]);
    }

    public function store(Request $r)
    {
        $data = $r->validate(['court_id' => 'required|exists:courts,id', 'date' => 'required|date_format:Y-m-d|after_or_equal:today', 'hour' => 'required|integer|between:8,21']);
        if (now()->gte(Carbon::parse($data['date'])->hour((int) $data['hour']))) {
            return back()->withErrors(['hour' => 'Jam ini sudah lewat.']);
        }
        try {
            CourtBooking::create($data + ['user_id' => $r->user()->id, 'price' => Court::findOrFail($data['court_id'])->price]);
        } catch (UniqueConstraintViolationException $e) {
            return back()->withErrors(['hour' => 'Slot baru saja dipesan. Silakan pilih jam lain.']);
        }

        return back()->with('success', 'Booking dikonfirmasi. Pembayaran dilakukan di lokasi.');
    }

    public function cancel(Request $r, CourtBooking $booking)
    {
        abort_unless($booking->user_id === $r->user()->id, 403);
        abort_if(now()->gte(Carbon::parse($booking->date)->hour($booking->hour)), 422, 'Booking yang sudah dimulai tidak dapat dibatalkan.');
        $booking->delete();

        return back()->with('success', 'Booking dibatalkan; slot tersedia kembali.');
    }
}
