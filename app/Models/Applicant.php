<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $appends = ['code'];

    protected $centres = [
        ['Dubhri', '04/02/2024'],
        ['Goraimari', '10/02/2024'],
        ['Karupetia', '11/02/2024'],
        ['Guwahati', '18/02/2024'],
        ['Bongaigaon', '24/02/2024'],
        ['Manipur', '24/02/2024'],
        ['Manipur', '25/02/2024'],
        ['Tripura', '29/02/2024'],
        ['Karimganj', '02/03/2024'],
        ['Baisha - Barmara DH Campus', '03/03/2024'],
        ['Baisha - Barmara DH Campus', '07/03/2024'],
    ];

    private function code($centre_id)
    {
        $centre = $this->centres[$centre_id - 1][0] ?? 'DH';
        return strtoupper(substr($centre ?? 'DH', 0, 2));
    }

    public function getCodeAttribute()
    {
        return $this->code($this->attributes['examcentre']);
    }
}
