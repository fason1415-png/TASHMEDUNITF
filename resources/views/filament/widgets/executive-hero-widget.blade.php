<x-filament-widgets::widget>
    @php
        $maxDepartment = max((int) ($topDepartments->max('total') ?? 0), 1);
        $doctorTabs = [
            'top' => $topDoctorReports,
            'risk' => $riskDoctorReports,
            'growth' => $growthDoctorReports,
        ];
        $formatTrend = static function (float $value): string {
            if ($value > 0) {
                return '+'.number_format($value, 2);
            }

            if ($value < 0) {
                return number_format($value, 2);
            }

            return '0.00';
        };
    @endphp

    <section class="sr-hero">
        <div class="sr-hero-head">
            <div>
                <h2 class="sr-hero-title">{{ __('ui.executive.title') }}</h2>
                <p class="sr-hero-subtitle">{{ __('ui.executive.subtitle') }}</p>
            </div>

            <div class="sr-hero-meta">
                <span class="sr-chip sr-chip-live">{{ __('ui.executive.live_badge') }}</span>
                <span class="sr-chip">{{ __('ui.executive.updated_at', ['time' => now()->format('H:i:s')]) }}</span>
            </div>
        </div>

        <div class="sr-hero-grid">
            <article>
                <div class="sr-stat-grid">
                    <div class="sr-stat">
                        <p class="sr-stat-label">{{ __('ui.executive.monthly_feedback') }}</p>
                        <p class="sr-stat-value">{{ number_format($monthResponses) }}</p>
                    </div>

                    <div class="sr-stat">
                        <p class="sr-stat-label">{{ __('ui.executive.avg_confidence') }}</p>
                        <p class="sr-stat-value">{{ number_format($avgConfidence, 1) }}</p>
                    </div>

                    <div class="sr-stat">
                        <p class="sr-stat-label">{{ __('ui.executive.scan_count') }}</p>
                        <p class="sr-stat-value">{{ number_format($scanCount) }}</p>
                    </div>

                    <div class="sr-stat">
                        <p class="sr-stat-label">{{ __('ui.executive.critical_alerts') }}</p>
                        <p class="sr-stat-value {{ $criticalCount > 0 ? 'is-danger' : '' }}">{{ number_format($criticalCount) }}</p>
                    </div>
                </div>

                <div class="sr-viz-grid">
                    <div class="sr-panel">
                        <p class="sr-stat-label">{{ __('ui.executive.feedback_trend') }}</p>

                        <div class="sr-bars" aria-hidden="true">
                            @foreach ($monthBars as $bar)
                                @php
                                    $height = max((int) round(($bar['count'] / $maxBar) * 100), 12);
                                @endphp
                                <span style="height: {{ $height }}%" title="{{ $bar['label'] }}: {{ $bar['count'] }}"></span>
                            @endforeach
                        </div>

                        <div class="sr-month-labels mt-2 flex items-center justify-between text-[11px] text-gray-500 dark:text-gray-400">
                            @foreach ($monthBars as $bar)
                                <span class="sr-month-label">{{ $bar['label'] }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="sr-panel">
                        <p class="sr-stat-label">{{ __('ui.executive.top_departments') }}</p>

                        <div class="mt-3 grid gap-2">
                            @forelse ($topDepartments as $department)
                                @php
                                    $total = (int) $department->total;
                                    $width = max((int) round(($total / $maxDepartment) * 100), 10);
                                @endphp
                                <div class="sr-dept-row">
                                    <span class="truncate">{{ str($department->department?->name ?? 'N/A')->limit(10) }}</span>
                                    <span class="sr-progress"><span style="width: {{ $width }}%"></span></span>
                                    <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $total }}</span>
                                </div>
                            @empty
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('ui.executive.no_data') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </article>

            <aside class="sr-side" x-data="{ doctorTab: 'top' }">
                <div class="sr-side-head">
                    <h3 class="sr-side-title">{{ __('ui.executive.doctor_panel_title') }}</h3>
                    <p class="sr-side-subtitle">
                        {{ __('ui.executive.doctor_report_period', ['from' => $doctorPanelSummary['from'], 'to' => $doctorPanelSummary['to']]) }}
                    </p>
                </div>

                <div class="sr-doctor-kpis">
                    <div class="sr-doctor-kpi">
                        <p class="sr-doctor-kpi-label">{{ __('ui.executive.doctor_kpi_analyzed') }}</p>
                        <p class="sr-doctor-kpi-value">{{ number_format($doctorPanelSummary['analyzed_count']) }}</p>
                    </div>
                    <div class="sr-doctor-kpi">
                        <p class="sr-doctor-kpi-label">{{ __('ui.executive.doctor_kpi_quality') }}</p>
                        <p class="sr-doctor-kpi-value">{{ number_format($doctorPanelSummary['avg_performance'], 1) }}</p>
                    </div>
                    <div class="sr-doctor-kpi">
                        <p class="sr-doctor-kpi-label">{{ __('ui.executive.doctor_kpi_risk') }}</p>
                        <p class="sr-doctor-kpi-value">{{ number_format($doctorPanelSummary['risk_count']) }}</p>
                    </div>
                    <div class="sr-doctor-kpi">
                        <p class="sr-doctor-kpi-label">{{ __('ui.executive.doctor_kpi_bonus') }}</p>
                        <p class="sr-doctor-kpi-value">{{ number_format($conversionRate, 1) }}%</p>
                    </div>
                </div>

                <div class="sr-tab-list" role="tablist">
                    <button
                        type="button"
                        role="tab"
                        class="sr-tab-btn"
                        x-bind:class="{ 'is-active': doctorTab === 'top' }"
                        x-on:click="doctorTab = 'top'"
                    >
                        {{ __('ui.executive.doctor_tab_top') }}
                    </button>

                    <button
                        type="button"
                        role="tab"
                        class="sr-tab-btn"
                        x-bind:class="{ 'is-active': doctorTab === 'risk' }"
                        x-on:click="doctorTab = 'risk'"
                    >
                        {{ __('ui.executive.doctor_tab_risk') }}
                    </button>

                    <button
                        type="button"
                        role="tab"
                        class="sr-tab-btn"
                        x-bind:class="{ 'is-active': doctorTab === 'growth' }"
                        x-on:click="doctorTab = 'growth'"
                    >
                        {{ __('ui.executive.doctor_tab_growth') }}
                    </button>
                </div>

                @foreach ($doctorTabs as $tabName => $reports)
                    <div
                        x-cloak
                        x-show="doctorTab === '{{ $tabName }}'"
                        class="sr-doctor-list"
                    >
                        @forelse ($reports as $report)
                            <article class="sr-doctor-row">
                                <div class="sr-doctor-main">
                                    <p class="sr-doctor-name">{{ $report['doctor_name'] }}</p>
                                    <p class="sr-doctor-specialty">{{ $report['specialty'] }}</p>
                                </div>

                                <div class="sr-doctor-metrics">
                                    <span>{{ __('ui.executive.doctor_feedback_short') }}: {{ number_format($report['responses']) }}</span>
                                    <span>{{ __('ui.executive.doctor_quality_short') }}: {{ number_format($report['quality'], 1) }}</span>
                                    <span>{{ __('ui.executive.doctor_confidence_short') }}: {{ number_format($report['confidence'], 1) }}</span>
                                </div>

                                <div class="sr-doctor-flags">
                                    <span class="sr-badge">
                                        {{ __('ui.executive.doctor_alerts_short') }}: {{ number_format($report['open_alerts']) }}
                                    </span>
                                    <span class="sr-badge {{ $report['trend_delta'] >= 0 ? 'is-positive' : 'is-negative' }}">
                                        {{ __('ui.executive.doctor_trend_short') }}: {{ $formatTrend($report['trend_delta']) }}
                                    </span>
                                </div>
                            </article>
                        @empty
                            <p class="sr-doctor-empty">{{ __('ui.executive.doctor_no_data') }}</p>
                        @endforelse
                    </div>
                @endforeach
            </aside>
        </div>
    </section>
</x-filament-widgets::widget>
