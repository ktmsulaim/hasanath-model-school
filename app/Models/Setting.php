<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'value'];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('settings');
        });
        static::deleted(function () {
            Cache::forget('settings');
        });
        static::updated(function () {
            Cache::forget('settings');
        });
        static::created(function () {
            Cache::forget('settings');
        });
    }
}
