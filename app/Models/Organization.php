<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'name',
        'created_by',
    ];

    protected $hidden = [
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the users that belong to the organization
     */
    public function users()
    {
        return $this->hasMany(User::class, 'organization_id', 'id');
    }

    /**
     * Get the user who created the organization
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Create a new organization
     */
    public static function createOrganization(array $data, $createdBy = null)
    {
        return self::create([
            'name' => $data['name'],
            'created_by' => $createdBy,
        ]);
    }

}
