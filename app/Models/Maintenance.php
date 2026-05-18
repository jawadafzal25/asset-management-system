<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\OrganizationScoped;

class Maintenance extends Model
{
    use SoftDeletes, OrganizationScoped;

    protected $fillable = [
        'asset_id',
        'reported_by',
        'issue_description',
        'maintenance_status',
        'reported_date',
        'organization_id'
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