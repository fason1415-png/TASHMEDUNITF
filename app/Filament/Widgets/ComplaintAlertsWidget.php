<?php

namespace App\Filament\Widgets;

use App\Models\Escalation;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ComplaintAlertsWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = '30s';

    public function table(Table $table): Table
    {
        $clinicId = auth()->user()?->isSuperAdmin() ? null : auth()->user()?->clinic_id;
        $doctorId = auth()->user()?->hasRole('doctor') ? auth()->user()?->doctor_id : null;

        return $table
            ->heading(__('ui.alerts.heading'))
            ->query(
                Escalation::query()
                    ->with(['doctor', 'branch'])
                    ->when($clinicId, fn (Builder $query) => $query->where('clinic_id', $clinicId))
                    ->when($doctorId, fn (Builder $query) => $query->where('doctor_id', $doctorId))
                    ->whereIn('severity', ['high', 'critical'])
                    ->whereIn('status', ['open', 'in_progress'])
                    ->latest('opened_at')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('severity')
                    ->badge()
                    ->color(fn (string $state) => $state === 'critical' ? 'danger' : 'warning'),
                TextColumn::make('title')->wrap(),
                TextColumn::make('doctor.full_name')
                    ->label(__('ui.alerts.doctor'))
                    ->placeholder('N/A'),
                TextColumn::make('branch.name')
                    ->label(__('ui.alerts.branch'))
                    ->placeholder('N/A'),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('opened_at')
                    ->dateTime('d M, H:i')
                    ->label(__('ui.alerts.opened')),
            ]);
    }
}

