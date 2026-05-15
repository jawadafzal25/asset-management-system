<?php

namespace App\Observers;

use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Model;

/**
 * BaseObserver
 *
 * Every model observer extends this.
 * Just define $module in the child class and all CRUD events are logged automatically.
 *
 * Usage in child:
 *   protected string $module = 'Employee';
 */
abstract class BaseObserver
{
    protected string $module = 'Unknown';

    /**
     * Fires AFTER a record is created.
     */
    public function created(Model $model): void
    {
        ActivityLogService::logCreated($this->module, $model);
    }

    /**
     * Fires AFTER a record is updated.
     * Laravel tracks dirty (changed) attributes automatically.
     * We store the original (before) values and the new (after) values.
     */
    public function updated(Model $model): void
    {
        // $model->getOriginal() gives values BEFORE the update
        // $model->toArray()     gives values AFTER the update
        ActivityLogService::logUpdated(
            $this->module,
            $model,
            $model->getOriginal()   // old values captured by Eloquent before save
        );
    }

    /**
     * Fires AFTER a record is deleted (or soft-deleted).
     */
    public function deleted(Model $model): void
    {
        ActivityLogService::logDeleted($this->module, $model);
    }

    /**
     * Fires AFTER a soft-deleted record is restored.
     */
    public function restored(Model $model): void
    {
        ActivityLogService::log('restored', $this->module, [
            'entity_type' => class_basename($model),
            'entity_id'   => $model->getKey(),
            'new_values'  => $model->toArray(),
        ]);
    }
}
