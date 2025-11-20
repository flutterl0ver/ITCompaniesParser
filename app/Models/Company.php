<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'inn',
        'name',
        'address',
        'ogrn',
        'approved'
    ];
    public $timestamps = false;
}
