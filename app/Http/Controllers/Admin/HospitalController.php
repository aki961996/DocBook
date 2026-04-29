<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HospitalController extends Controller
{
    /**
     * List all hospitals.
     * super_admin → all | hospital_admin → own only
     */
    public function index(Request $request)
    {
        $query = Hospital::withCount(['doctors', 'departments', 'bookings'])
            ->latest();

        // Hospital admin sees only their own hospital
        if (auth('admin')->user()->isHospitalAdmin()) {
            $query->where('id', auth('admin')->user()->hospital_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%');
        }

        $hospitals = $query->paginate(15)->withQueryString();

        return view('admin.hospitals.index', compact('hospitals'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $this->authorizeSuperAdmin();
        return view('admin.hospitals.create');
    }

    /**
     * Store a new hospital.
     */
    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'address'     => 'required|string',
            'city'        => 'required|string|max:100',
            'state'       => 'required|string|max:100',
            'pincode'     => 'required|string|max:10',
            'phone'       => 'required|string|max:20',
            'email'       => 'nullable|email',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('hospitals/logos', 'public');
        }

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(4);

        $hospital = Hospital::create($data);

        return redirect()
            ->route('admin.hospitals.index')
            ->with('success', "Hospital \"{$hospital->name}\" created successfully.");
            
    }

    /**
     * Show edit form.
     */
    public function edit(Hospital $hospital)
    {
        $this->authorizeHospitalAccess($hospital);
        return view('admin.hospitals.edit', compact('hospital'));
    }

    /**
     * Update hospital.
     */
    public function update(Request $request, Hospital $hospital)
    {
        $this->authorizeHospitalAccess($hospital);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'address'     => 'required|string',
            'city'        => 'required|string|max:100',
            'state'       => 'required|string|max:100',
            'pincode'     => 'required|string|max:10',
            'phone'       => 'required|string|max:20',
            'email'       => 'nullable|email',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|max:2048',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($hospital->logo) {
                Storage::disk('public')->delete($hospital->logo);
            }
            $data['logo'] = $request->file('logo')->store('hospitals/logos', 'public');
        }

        $hospital->update($data);

        return redirect()
            ->route('admin.hospitals.index')
            ->with('success', 'Hospital updated successfully.');
    }

    /**
     * Soft-delete hospital.
     */
    public function destroy(Hospital $hospital)
    {
        $this->authorizeSuperAdmin();
        $hospital->delete();

        return redirect()
            ->route('admin.hospitals.index')
            ->with('success', 'Hospital deleted.');
    }

    /**
     * Toggle active status (AJAX-friendly).
     */
    public function toggleStatus(Hospital $hospital)
    {
        $this->authorizeSuperAdmin();
        $hospital->update(['is_active' => !$hospital->is_active]);

        return response()->json([
            'status'  => $hospital->is_active,
            'message' => 'Status updated.',
        ]);
    }

    // ── Helpers ───────────────────────────────────────────────

    private function authorizeSuperAdmin(): void
    {
        abort_unless(auth('admin')->user()->isSuperAdmin(), 403, 'Super admin only.');
    }

    private function authorizeHospitalAccess(Hospital $hospital): void
    {
        $admin = auth('admin')->user();
        if ($admin->isHospitalAdmin()) {
            abort_unless($admin->hospital_id === $hospital->id, 403);
        }
    }
}
