<?php

namespace App\Exports;

use App\Models\Applicant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ApplicantsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Applicant::all()->map(function ($applicant) {
            return [
                $applicant->name,
                $applicant->dob,
                $applicant->guardian,
                $applicant->address,
                $applicant->mobile,
                $applicant->type,
                $applicant->dod_father,
                $applicant->class,
                $applicant->mother,
                $applicant->brothers,
                $applicant->sisters,
                $applicant->status,
                $applicant->remarks,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Date of Birth',
            'Guardian',
            'Address',
            'Mobile',
            'Type',
            'Date of Death of Father',
            'Class',
            'Mother',
            'Brothers',
            'Sisters',
            'Status',
            'Remarks',
        ];
    }
}
