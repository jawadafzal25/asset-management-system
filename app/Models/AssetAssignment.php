<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\OrganizationScoped;

class AssetAssignment extends Model
{
    use HasFactory, OrganizationScoped;

    protected $primaryKey = 'assignment_id';

    protected $fillable = [
        'asset_id',
        'employee_id',
        'assigned_by',
        'quantity',
        'assign_date',
        'return_date',
        'status',
        'organization_id'
    ];

    /**
     * Relationships
     */

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by'); 
    }
}