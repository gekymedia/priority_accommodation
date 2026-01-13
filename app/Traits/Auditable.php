<?php
namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'entity' => class_basename($model),
                'entity_id' => $model->id,
                'description' => class_basename($model) . " #{$model->id} was created",
                'new_values' => $model->getAttributes()
            ]);
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']); // Remove updated_at from changes
            
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'entity' => class_basename($model),
                'entity_id' => $model->id,
                'description' => class_basename($model) . " #{$model->id} was updated",
                'old_values' => array_intersect_key($model->getOriginal(), $changes),
                'new_values' => $changes
            ]);
        });

        static::deleted(function ($model) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'entity' => class_basename($model),
                'entity_id' => $model->id,
                'description' => class_basename($model) . " #{$model->id} was deleted",
                'old_values' => $model->getOriginal()
            ]);
        });
    }
}