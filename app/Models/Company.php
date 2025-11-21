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
        'approved',
        'updated_at',
        'update_status',
        'workers_count',
        'tax',
        'income',
        'expense',
        'simple_tax',
        'tax_issues'
    ];
    public $timestamps = false;
}
