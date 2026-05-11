<?php

<<<<<<< HEAD
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
=======

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
>>>>>>> 9daf7c3d30378bb4cb3c2c9ba0c1e39c8b3d0cf7
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
<<<<<<< HEAD
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * A department can have many assets.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'department_id');
=======
    protected $primaryKey = 'department_id';
    protected $fillable = ['department_name'];

    /**
     * Get all employees belonging to this department
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'department_id', 'department_id');
>>>>>>> 9daf7c3d30378bb4cb3c2c9ba0c1e39c8b3d0cf7
    }
}
