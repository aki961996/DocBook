<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\Doctor;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index(Request $request)
    {
        $query = Hospital::active()->withCount(['doctors', 'departments']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $hospitals = $query->paginate(12)->withQueryString();
        $cities    = Hospital::active()->distinct()->pluck('city');

        return view('web.hospitals.index', compact('hospitals', 'cities'));
    }

    public function show(Hospital $hospital, Request $request)
    {
        abort_unless($hospital->is_active, 404);

        $hospital->load('departments');

        $doctorQuery = Doctor::active()
            ->with(['department'])
            ->where('hospital_id', $hospital->id);

        if ($request->filled('dept')) {
            $doctorQuery->where('department_id', $request->dept);
        }

        $doctors = $doctorQuery->get();

        return view('web.hospitals.show', compact('hospital', 'doctors'));
    }
}
