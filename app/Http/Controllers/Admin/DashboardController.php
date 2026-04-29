<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\Doctor;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = auth('admin')->user();

        if ($admin->isSuperAdmin()) {
            $stats = [
                'hospitals'        => Hospital::count(),
                'doctors'          => Doctor::count(),
                'bookings_today'   => Booking::whereDate('booked_at', today())->count(),
                'bookings_month'   => Booking::whereMonth('booked_at', now()->month)->count(),
                'pending_bookings' => Booking::where('status', 'pending')->count(),
            ];
            $recentBookings = Booking::with(['doctor.department', 'hospital', 'user', 'slot'])
                ->latest('booked_at')->limit(10)->get();
        } else {
            $hId = $admin->hospital_id;
            $stats = [
                'doctors'          => Doctor::where('hospital_id', $hId)->count(),
                'bookings_today'   => Booking::where('hospital_id', $hId)->whereDate('booked_at', today())->count(),
                'bookings_month'   => Booking::where('hospital_id', $hId)->whereMonth('booked_at', now()->month)->count(),
                'pending_bookings' => Booking::where('hospital_id', $hId)->where('status', 'pending')->count(),
            ];
            $recentBookings = Booking::with(['doctor.department', 'user', 'slot'])
                ->where('hospital_id', $hId)
                ->latest('booked_at')->limit(10)->get();
        }

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
}