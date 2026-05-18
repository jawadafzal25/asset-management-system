<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\OrganizationScoped;

class Asset extends Model
{
    use SoftDeletes, OrganizationScoped;

    /**
     * Allowed status values for an asset.
     */
    const STATUSES = ['available', 'assigned', 'maintenance', 'inactive'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'asset_name',
        'asset_code',
        'category_id',
        'department_id',
        'brand',
        'total_quantity',
        'remaining_quantity',
        'purchase_date',
        'status',
        'asset_image',
        'invoice_image',
        'organization_id',
    ];

    /**
     * Attribute casting for proper type handling.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_date'      => 'date',
        'total_quantity'     => 'integer',
        'remaining_quantity' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * An asset belongs to a category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * An asset belongs to a department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * An asset can have many assignments.
     * Placeholder relationship — AssetAssignment model to be implemented separately.
     */
    public function assignments(): HasMany
    {
        // AssetAssignment model will be created in the Assignment Module.
        // Using a deferred binding approach to avoid breaking the app if model doesn't exist yet.
        return $this->hasMany(\App\Models\AssetAssignment::class, 'asset_id');
    }

    /**
     * An asset can have many maintenance requests.
     * Placeholder relationship — MaintenanceRequest model to be implemented separately.
     */
    public function maintenances(): HasMany
{
    return $this->hasMany(
        Maintenance::class,
        'asset_id'
    );
}

    // -------------------------------------------------------------------------
    // Business Logic Helpers
    // -------------------------------------------------------------------------

    /**
     * Calculate how many units of this asset are currently assigned.
     * assigned_quantity = total_quantity - remaining_quantity
     */
    public function getAssignedQuantityAttribute(): int
    {
        return $this->total_quantity - $this->remaining_quantity;
    }
}
