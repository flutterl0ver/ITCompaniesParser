<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeState extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'date',
        'avg_workers_count'
    ];
}
