<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    private function allowedHospitals()
    {
        $admin = auth('admin')->user();
        return $admin->isSuperAdmin()
            ? Hospital::active()->pluck('name', 'id')
            : Hospital::where('id', $admin->hospital_id)->pluck('name', 'id');
    }

    public function index(Request $request)
    {
        $admin = auth('admin')->user();
        $query = Doctor::with(['hospital', 'department'])->latest();

        if ($admin->isHospitalAdmin()) {
            $query->where('hospital_id', $admin->hospital_id);
        }

        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $doctors    = $query->paginate(15)->withQueryString();
        $hospitals  = $this->allowedHospitals();

        return view('admin.doctors.index', compact('doctors', 'hospitals'));
    }

    public function create()
    {
        $hospitals   = $this->allowedHospitals();
        $departments = collect(); // loaded dynamically via AJAX on hospital change

        return view('admin.doctors.create', compact('hospitals', 'departments'));
    }

    public function store(Request $request)
    {
        $this->authorizeHospitalAction($request->hospital_id);

        $data = $request->validate([
            'hospital_id'       => 'required|exists:hospitals,id',
            'department_id'     => 'required|exists:departments,id',
            'name'              => 'required|string|max:255',
            'qualification'     => 'required|string|max:255',
            'specialization'    => 'required|string|max:255',
            'experience_years'  => 'required|integer|min:0|max:60',
            'consultation_fee'  => 'required|numeric|min:0',
            'phone'             => 'nullable|string|max:20',
            'email'             => 'nullable|email',
            'bio'               => 'nullable|string',
            'photo'             => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('doctors/photos', 'public');
        }

        Doctor::create($data);

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor added successfully.');
    }

    public function edit(Doctor $doctor)
    {
        $this->authorizeHospitalAction($doctor->hospital_id);

        $hospitals   = $this->allowedHospitals();
        $departments = Department::where('hospital_id', $doctor->hospital_id)
                                  ->active()
                                  ->pluck('name', 'id');

        return view('admin.doctors.edit', compact('doctor', 'hospitals', 'departments'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $this->authorizeHospitalAction($doctor->hospital_id);

        $data = $request->validate([
            'department_id'     => 'required|exists:departments,id',
            'name'              => 'required|string|max:255',
            'qualification'     => 'required|string|max:255',
            'specialization'    => 'required|string|max:255',
            'experience_years'  => 'required|integer|min:0|max:60',
            'consultation_fee'  => 'required|numeric|min:0',
            'phone'             => 'nullable|string|max:20',
            'email'             => 'nullable|email',
            'bio'               => 'nullable|string',
            'photo'             => 'nullable|image|max:2048',
            'is_active'         => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($doctor->photo) {
                Storage::disk('public')->delete($doctor->photo);
            }
            $data['photo'] = $request->file('photo')->store('doctors/photos', 'public');
        }

        $doctor->update($data);

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor updated.');
    }

    public function destroy(Doctor $doctor)
    {
        $this->authorizeHospitalAction($doctor->hospital_id);
        $doctor->delete();

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor deleted.');
    }

    /**
     * AJAX: get departments for a hospital (used in create/edit forms).
     */
    public function departmentsByHospital(Hospital $hospital)
    {
        $departments = Department::where('hospital_id', $hospital->id)
                                  ->active()
                                  ->select('id', 'name')
                                  ->get();

        return response()->json($departments);
    }

    // ── Helpers ───────────────────────────────────────────────

    private function authorizeHospitalAction(string $hospitalId): void
    {
        $admin = auth('admin')->user();
        if ($admin->isHospitalAdmin()) {
            abort_unless($admin->hospital_id === $hospitalId, 403);
        }
    }
}
