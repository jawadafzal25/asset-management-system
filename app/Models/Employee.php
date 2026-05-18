<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\OrganizationScoped;

class Employee extends Model
{
    use OrganizationScoped;

    protected $primaryKey = 'employee_id';
    protected $fillable = [
        'name',
        'father_name',
        'contact_info',
        'email',
        'address',
        'designation',
        'joining_date',
        'salary',
        'status',
        'department_id',
        'organization_id'
    ];


    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
}
