<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\Booking;

class HomeController extends Controller
{
    public function index()
    {
        $featuredHospitals = Hospital::active()
            ->withCount(['doctors', 'departments'])
            ->limit(6)->get();

        $featuredDoctors = Doctor::active()
            ->with(['hospital', 'department'])
            ->inRandomOrder()->limit(6)->get();

        $topDepartments = Department::withCount('doctors')
            ->having('doctors_count', '>', 0)
            ->orderByDesc('doctors_count')
            ->limit(12)->get();

        // Unique dept names across all hospitals
        $departments = Department::select('name', 'id')
            ->orderBy('name')->get()->unique('name');

        $cities = Hospital::active()->distinct()->pluck('city');

        $stats = [
            'hospitals'   => Hospital::active()->count(),
            'doctors'     => Doctor::active()->count(),
            'departments' => Department::distinct('name')->count('name'),
            'bookings'    => Booking::count(),
        ];

        return view('web.home', compact(
            'featuredHospitals', 'featuredDoctors',
            'topDepartments', 'departments', 'cities', 'stats'
        ));
    }
}
