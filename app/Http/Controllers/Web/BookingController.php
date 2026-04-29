<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DoctorSlot;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(Request $request)
    {
        $bookings = Booking::with(['doctor.department', 'hospital', 'slot'])
            ->where('user_id', auth('web')->id())
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest('booked_at')
            ->paginate(10);

        return view('web.bookings.index', compact('bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id'     => 'required|exists:doctors,id',
            'slot_id'       => 'required|exists:doctor_slots,id',
            'patient_name'  => 'required|string|max:255',
            'patient_phone' => 'required|string|max:20',
            'patient_age'   => 'nullable|integer|min:0|max:120',
            'patient_gender'=> 'nullable|string',
            'reason'        => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $slot = DoctorSlot::lockForUpdate()->findOrFail($request->slot_id);

            abort_if($slot->is_booked || $slot->is_blocked, 422, 'This slot is no longer available.');
            abort_if($slot->doctor_id !== $request->doctor_id, 422, 'Slot mismatch.');

            $doctor = Doctor::findOrFail($request->doctor_id);

            Booking::create([
                'user_id'        => auth('web')->id(),
                'doctor_id'      => $doctor->id,
                'hospital_id'    => $doctor->hospital_id,
                'slot_id'        => $slot->id,
                'patient_name'   => $request->patient_name,
                'patient_phone'  => $request->patient_phone,
                'patient_age'    => $request->patient_age,
                'patient_gender' => $request->patient_gender,
                'reason'         => $request->reason,
                'status'         => 'pending',
            ]);

            $slot->update(['is_booked' => true]);
        });

        return redirect()->route('bookings.index')
            ->with('success', 'Appointment booked! We will confirm shortly.');
    }
}
