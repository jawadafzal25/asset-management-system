<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait OrganizationScoped
{
    public static function bootOrganizationScoped()
    {
        // 1. Automatically set organization_id on model creation
        static::creating(function ($model) {
            if (!$model->organization_id) {
                $user = request()->get('user') ?? auth()->user();
                if ($user) {
                    $model->organization_id = $user->organization_id;
                }
            }
        });

        // 2. Automatically scope all queries by organization_id
        static::addGlobalScope('organization', function (Builder $builder) {
            $user = request()->get('user') ?? auth()->user();
            if ($user && $user->organization_id) {
                $builder->where($builder->getQuery()->from . '.organization_id', $user->organization_id);
            }
        });
    }
}
