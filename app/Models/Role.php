<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($role) {
            if (empty($role->slug)) {
                $role->slug = Str::slug($role->name);
            }
        });

        static::updating(function ($role) {
            if ($role->isDirty('name') && empty($role->slug)) {
                $role->slug = Str::slug($role->name);
            }
        });
    }

    /**
     * Get the users that have this role.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role', 'name');
    }

    /**
     * Scope a query to only include active roles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Add a new role with validation
     */
    public static function add($data)
    {
        // Handle if data is a request object with validated() method
        if (is_object($data) && method_exists($data, 'validated')) {
            $data = $data->validated();
        }

        // Check if role exists with same name
        $existingRole = self::where('name', $data['name'])->first();
        if ($existingRole) {
            abort(response()->json([
                'success' => false,
                'message' => 'Role already exists with this name.',
            ], 409));
        }

        // Check if role exists with same slug
        $existingSlug = self::where('slug', $data['slug'] ?? Str::slug($data['name']))->first();
        if ($existingSlug) {
            abort(response()->json([
                'success' => false,
                'message' => 'Role slug already exists.',
            ], 409));
        }

        return self::create([
            'name' => $data['name'],
            'slug' => $data['slug'] ?? Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
