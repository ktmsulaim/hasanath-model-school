<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Applicant extends Model
{
    use HasFactory, SoftDeletes;

    const NOT_ORPHAN = 0;

    const ORPHAN = 1;

    const DESTITUTE = 2;

    protected $fillable = [
        'name',
        'dob',
        'guardian',
        'address',
        'mobile',
        'type',
        'class',
        'mother',
        'image',
        'brothers',
        'sisters',
        'uuid',
        'dod_father',
        'status',
        'remarks',
    ];

    protected function type(): Attribute
    {
        return Attribute::make(function ($value) {
            return match ($value) {
                self::ORPHAN => 'Orphan',
                self::DESTITUTE => 'Destitute',
                default => 'No',
            };
        }, function ($value) {
            return match ($value) {
                'Orphan' => self::ORPHAN,
                'Destitute' => self::DESTITUTE,
                '1' => self::ORPHAN,
                '2' => self::DESTITUTE,
                default => self::NOT_ORPHAN,
            };
        });
    }

    protected function code(): Attribute
    {
        return Attribute::make(function () {
            return 'HGC';
        });
    }
}
