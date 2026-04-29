<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth('admin')->user();

        $query = Booking::with(['doctor.department', 'hospital', 'user', 'slot'])
            ->latest('booked_at');

        if ($admin->isHospitalAdmin()) {
            $query->where('hospital_id', $admin->hospital_id);
        }

        foreach (['status', 'hospital_id', 'doctor_id'] as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->$filter);
            }
        }

        if ($request->filled('date')) {
            $query->whereHas('slot', fn($q) => $q->whereDate('slot_date', $request->date));
        }

        $bookings = $query->paginate(20)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $this->authorizeBooking($booking);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        $request->validate([
            'status'      => 'required|in:confirmed,completed,cancelled',
            'admin_notes' => 'nullable|string',
        ]);

        $booking->update($request->only('status', 'admin_notes'));

        return back()->with('success', 'Booking status updated.');
    }

    private function authorizeBooking(Booking $booking): void
    {
        $admin = auth('admin')->user();
        if ($admin->isHospitalAdmin()) {
            abort_unless($admin->hospital_id === $booking->hospital_id, 403);
        }
    }
}