<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $primaryKey = 'department_id';
    protected $fillable = ['department_name'];
    /**
     * Get all employees belonging to this department
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'department_id', 'department_id');
    }
}