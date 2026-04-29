<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\Hospital;
use App\Models\DoctorSlot;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::active()
            ->with(['hospital', 'department'])
            ->whereHas('hospital', fn($q) => $q->where('is_active', true));

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('specialization', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('city')) {
            $query->whereHas('hospital', fn($q) => $q->where('city', $request->city));
        }

        $doctors     = $query->paginate(12)->withQueryString();
        $departments = Department::orderBy('name')->get()->unique('name');
        $cities      = Hospital::active()->distinct()->pluck('city');

        return view('web.doctors.index', compact('doctors', 'departments', 'cities'));
    }

    public function show(Doctor $doctor)
    {
        abort_unless($doctor->is_active, 404);
        $doctor->load(['hospital', 'department']);

        $slots = DoctorSlot::where('doctor_id', $doctor->id)
            ->where('is_booked', false)
            ->where('is_blocked', false)
            ->whereDate('slot_date', '>=', now()->toDateString())
            ->orderBy('slot_date')->orderBy('start_time')
            ->get()
            ->groupBy(fn($s) => $s->slot_date->format('Y-m-d'));

        return view('web.doctors.show', compact('doctor', 'slots'));
    }
}
