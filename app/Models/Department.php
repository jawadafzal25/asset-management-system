<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\OrganizationScoped;

class Department extends Model
{
    use OrganizationScoped;

    protected $primaryKey = 'department_id';
    protected $fillable = ['department_name', 'organization_id'];
    /**
     * Get all employees belonging to this department
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'department_id', 'department_id');
    }
}
