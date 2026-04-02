<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

abstract class BaseResource extends Resource
{
    protected static ?string $permission = null;

    public static function getNavigationGroup(): ?string
    {
        $groupKey = static::resolveNavigationGroupKey();

        if (! $groupKey) {
            return parent::getNavigationGroup();
        }

        return __('navigation.groups.'.$groupKey);
    }

    public static function getNavigationSort(): int
    {
        $sortMap = [
            'branch' => 10,
            'clinic' => 20,
            'department' => 30,
            'doctor' => 40,
            'service_point' => 50,
            'qr_code' => 60,
            'survey' => 70,
            'survey_response' => 80,
            'suspicious_flag' => 90,
            'escalation' => 100,
            'reward_rule' => 110,
            'reward' => 120,
            'patient' => 160,
            'discharge' => 170,
            'patronage_task' => 180,
            'patronage_escalation_rule' => 190,
            'subscription' => 200,
            'invoice' => 210,
            'user' => 220,
        ];

        return $sortMap[static::getResourceTranslationKey()] ?? parent::getNavigationSort();
    }

    public static function getModelLabel(): string
    {
        return static::translateResourceLabel('singular') ?? parent::getModelLabel();
    }

    public static function getPluralModelLabel(): string
    {
        return static::translateResourceLabel('plural') ?? parent::getPluralModelLabel();
    }

    public static function getNavigationLabel(): string
    {
        return static::translateResourceLabel('navigation') ?? parent::getNavigationLabel();
    }

    public static function canViewAny(): bool
    {
        return static::authorized();
    }

    public static function canCreate(): bool
    {
        return static::authorized();
    }

    public static function canEdit($record): bool
    {
        return static::authorized();
    }

    public static function canDelete($record): bool
    {
        return static::authorized();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        $model = static::getModel();

        if (! $user || $user->isSuperAdmin()) {
            return $query;
        }

        $modelTable = (new $model())->getTable();
        if (Schema::hasColumn($modelTable, 'clinic_id') && $user->clinic_id) {
            $query->where($modelTable.'.clinic_id', $user->clinic_id);
        }

        if ($user->hasRole('doctor') && Schema::hasColumn($modelTable, 'doctor_id') && $user->doctor_id) {
            $query->where($modelTable.'.doctor_id', $user->doctor_id);
        }

        return $query;
    }

    protected static function authorized(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        if (static::$permission === null) {
            return true;
        }

        return $user->can(static::$permission);
    }

    protected static function getResourceTranslationKey(): string
    {
        return Str::snake(class_basename(static::getModel()));
    }

    protected static function translateResourceLabel(string $type): ?string
    {
        $key = 'resources.'.static::getResourceTranslationKey().'.'.$type;
        $translated = __($key);

        return $translated === $key ? null : $translated;
    }

    protected static function resolveNavigationGroupKey(): ?string
    {
        $groupMap = [
            'branch' => 'structure',
            'clinic' => 'structure',
            'department' => 'structure',
            'doctor' => 'structure',
            'service_point' => 'structure',
            'qr_code' => 'feedback',
            'survey' => 'feedback',
            'survey_response' => 'feedback',
            'suspicious_flag' => 'feedback',
            'escalation' => 'feedback',
            'reward_rule' => 'finance',
            'reward' => 'finance',
            'patient' => 'patronage',
            'discharge' => 'patronage',
            'patronage_task' => 'patronage',
            'patronage_escalation_rule' => 'patronage',
            'subscription' => 'finance',
            'invoice' => 'finance',
            'user' => 'system',
        ];

        return $groupMap[static::getResourceTranslationKey()] ?? null;
    }
}
