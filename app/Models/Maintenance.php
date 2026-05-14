<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maintenance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'asset_id',
        'reported_by',
        'issue_description',
        'maintenance_status',
        'reported_date'
    ];

    public function asset()
    {
        return $this->belongsTo(
            Asset::class
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'reported_by'
        );
    }
}