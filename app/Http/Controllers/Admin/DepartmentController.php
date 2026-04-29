<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Hospital;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth('admin')->user();
        $query = Department::with('hospital')->withCount('doctors')->latest();

        if ($admin->isHospitalAdmin()) {
            $query->where('hospital_id', $admin->hospital_id);
        }

        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }

        $departments = $query->paginate(20)->withQueryString();
        $hospitals   = $admin->isSuperAdmin()
            ? Hospital::active()->pluck('name', 'id')
            : Hospital::where('id', $admin->hospital_id)->pluck('name', 'id');

        return view('admin.departments.index', compact('departments', 'hospitals'));
    }

    public function create()
    {
        $admin     = auth('admin')->user();
        $hospitals = $admin->isSuperAdmin()
            ? Hospital::active()->pluck('name', 'id')
            : Hospital::where('id', $admin->hospital_id)->pluck('name', 'id');

        return view('admin.departments.create', compact('hospitals'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
            'name'        => 'required|string|max:255',
            'icon'        => 'nullable|image|max:1024',
            'description' => 'nullable|string',
        ]);

        $this->authorizeHospitalAction($data['hospital_id']);

        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon')->store('departments/icons', 'public');
        }

        Department::create($data);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department created.');
    }

    public function edit(Department $department)
    {
        $this->authorizeHospitalAction($department->hospital_id);
        $hospitals = Hospital::active()->pluck('name', 'id');

        return view('admin.departments.edit', compact('department', 'hospitals'));
    }

    public function update(Request $request, Department $department)
    {
        $this->authorizeHospitalAction($department->hospital_id);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'icon'        => 'nullable|image|max:1024',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon')->store('departments/icons', 'public');
        }

        $department->update($data);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department updated.');
    }

    public function destroy(Department $department)
    {
        $this->authorizeHospitalAction($department->hospital_id);

        // Safety: don't delete if doctors exist
        abort_if($department->doctors()->exists(), 422, 'Remove doctors from this department first.');

        $department->delete();

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department deleted.');
    }

    private function authorizeHospitalAction(string $hospitalId): void
    {
        $admin = auth('admin')->user();
        if ($admin->isHospitalAdmin()) {
            abort_unless($admin->hospital_id === $hospitalId, 403);
        }
    }
}
