<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait HasClinicTenant
{
    protected static function bootHasClinicTenant(): void
    {
        static::addGlobalScope('clinic_tenant', function (Builder $builder): void {
            $user = Auth::user();
            $model = $builder->getModel();

            if (! $user || ! isset($user->clinic_id) || ! method_exists($user, 'isSuperAdmin') || $user->isSuperAdmin()) {
                return;
            }

            if (! Schema::hasColumn($model->getTable(), 'clinic_id')) {
                return;
            }

            $builder->where($model->qualifyColumn('clinic_id'), $user->clinic_id);
        });

        static::creating(function (Model $model): void {
            $user = Auth::user();

            if (! $user || ! isset($user->clinic_id) || ! method_exists($user, 'isSuperAdmin') || $user->isSuperAdmin()) {
                return;
            }

            if (! Schema::hasColumn($model->getTable(), 'clinic_id')) {
                return;
            }

            if (! $model->getAttribute('clinic_id')) {
                $model->setAttribute('clinic_id', $user->clinic_id);
            }
        });
    }

    public function scopeForClinic(Builder $query, int $clinicId): Builder
    {
        if (! Schema::hasColumn($this->getTable(), 'clinic_id')) {
            return $query;
        }

        return $query->where($this->qualifyColumn('clinic_id'), $clinicId);
    }
}

