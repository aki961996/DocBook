<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;

// ─────────────────────────────────────────────────────────
// SlotController  →  admin manages doctor availability
// ─────────────────────────────────────────────────────────
class SlotController extends Controller
{
    public function index(Doctor $doctor)
    {
        $this->authorizeDoctor($doctor);

        $slots = DoctorSlot::where('doctor_id', $doctor->id)
            ->whereDate('slot_date', '>=', now()->toDateString())
            ->orderBy('slot_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn ($s) => $s->slot_date->format('Y-m-d'));

        return view('admin.slots.index', compact('doctor', 'slots'));
    }

    /**
     * Bulk create slots for a doctor (e.g. Mon-Fri 09:00-17:00, 30-min each).
     */
    public function bulkCreate(Request $request, Doctor $doctor)
    {
        $this->authorizeDoctor($doctor);

        $data = $request->validate([
            'from_date'      => 'required|date|after_or_equal:today',
            'to_date'        => 'required|date|after_or_equal:from_date',
            'start_time'     => 'required|date_format:H:i',
            'end_time'       => 'required|date_format:H:i|after:start_time',
            'duration_mins'  => 'required|integer|in:15,20,30,45,60',
            'weekdays'       => 'required|array|min:1',
            'weekdays.*'     => 'integer|between:0,6', // 0=Sun, 6=Sat
        ]);

        $from     = Carbon::parse($data['from_date']);
        $to       = Carbon::parse($data['to_date']);
        $created  = 0;

        for ($date = $from->copy(); $date->lte($to); $date->addDay()) {
            if (!in_array($date->dayOfWeek, $data['weekdays'])) {
                continue;
            }

            $start = Carbon::createFromFormat('H:i', $data['start_time']);
            $end   = Carbon::createFromFormat('H:i', $data['end_time']);

            while ($start->copy()->addMinutes($data['duration_mins'])->lte($end)) {
                DoctorSlot::firstOrCreate(
                    [
                        'doctor_id'  => $doctor->id,
                        'slot_date'  => $date->toDateString(),
                        'start_time' => $start->format('H:i:s'),
                    ],
                    [
                        'end_time' => $start->copy()->addMinutes($data['duration_mins'])->format('H:i:s'),
                    ]
                );
                $start->addMinutes($data['duration_mins']);
                $created++;
            }
        }

        return redirect()
            ->route('admin.slots.index', $doctor)
            ->with('success', "{$created} slots created.");
    }

    public function destroy(Doctor $doctor, DoctorSlot $slot)
    {
        $this->authorizeDoctor($doctor);
        abort_if($slot->is_booked, 422, 'Cannot delete a booked slot.');
        $slot->delete();

        return back()->with('success', 'Slot removed.');
    }

    public function toggleBlock(DoctorSlot $slot)
    {
        $slot->update(['is_blocked' => !$slot->is_blocked]);
        return response()->json(['blocked' => $slot->is_blocked]);
    }

    private function authorizeDoctor(Doctor $doctor): void
    {
        $admin = auth('admin')->user();
        if ($admin->isHospitalAdmin()) {
            abort_unless($admin->hospital_id === $doctor->hospital_id, 403);
        }
    }
}
