<?php
namespace Database\Seeders;

use App\Models\Department;
use App\Models\Hospital;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'General Medicine',
            'Gynaecology & Obstetrics',
            'Paediatrics',
            'Orthopaedics',
            'Cardiology',
            'Dermatology',
            'ENT',
            'Ophthalmology',
            'Neurology',
            'Urology',
            'Psychiatry',
            'Oncology',
            'Dental',
            'Physiotherapy',
        ];

        Hospital::all()->each(function (Hospital $hospital) use ($departments) {
            foreach ($departments as $name) {
                Department::firstOrCreate([
                    'hospital_id' => $hospital->id,
                    'name'        => $name,
                ]);
            }
        });

        $this->command->info('Departments seeded for all hospitals.');
    }
}