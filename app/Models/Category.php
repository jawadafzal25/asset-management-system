<?php
<<<<<<< HEAD

=======
>>>>>>> 04e7224815277c29a75ed07800ed22c8c90c157d
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\HasMany;
=======
>>>>>>> 04e7224815277c29a75ed07800ed22c8c90c157d

class Category extends Model
{
    use SoftDeletes;

<<<<<<< HEAD
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
     * A category can have many assets.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'category_id');
    }
}
=======
    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
>>>>>>> 04e7224815277c29a75ed07800ed22c8c90c157d
