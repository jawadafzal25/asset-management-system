<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\OrganizationScoped;

class Category extends Model
{
    use SoftDeletes, OrganizationScoped;

    protected $fillable = [
        'name',
        'description',
        'status',
        'organization_id'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
