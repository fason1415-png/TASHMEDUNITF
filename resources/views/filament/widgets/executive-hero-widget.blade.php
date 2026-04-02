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

            {{-- TOP 15 Shifokorlar --}}
            <div class="sr-top15" style="grid-column: 1 / -1; margin-top: 8px;" x-data="{ showDoctor: false, doctor: {} }">
                <div class="sr-panel" style="overflow-x: auto;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <p class="sr-stat-label" style="margin: 0; font-size: 14px; font-weight: 700;">
                            TOP 15 — Eng ko'p baho olgan shifokorlar (30 kun)
                        </p>
                        <span style="font-size: 11px; color: #94a3b8;">Jami: {{ $top15Doctors->sum('responses') }} ta javob</span>
                    </div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e2e8f0; text-align: left;">
                                <th style="padding: 8px 6px; color: #64748b; font-weight: 600; font-size: 11px; text-transform: uppercase;">#</th>
                                <th style="padding: 8px 6px; color: #64748b; font-weight: 600; font-size: 11px; text-transform: uppercase;">Shifokor</th>
                                <th style="padding: 8px 6px; color: #64748b; font-weight: 600; font-size: 11px; text-transform: uppercase;">Lavozimi</th>
                                <th style="padding: 8px 6px; color: #64748b; font-weight: 600; font-size: 11px; text-transform: uppercase;">Klinika</th>
                                <th style="padding: 8px 6px; color: #64748b; font-weight: 600; font-size: 11px; text-transform: uppercase;">Bo'lim</th>
                                <th style="padding: 8px 6px; color: #64748b; font-weight: 600; font-size: 11px; text-transform: uppercase; text-align: center;">Javoblar</th>
                                <th style="padding: 8px 6px; color: #64748b; font-weight: 600; font-size: 11px; text-transform: uppercase; text-align: center;">Sifat</th>
                                <th style="padding: 8px 6px; color: #64748b; font-weight: 600; font-size: 11px; text-transform: uppercase; text-align: center;">Ishonch</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($top15Doctors as $i => $doc)
                                @php
                                    $rankColor = match(true) {
                                        $i === 0 => '#f59e0b',
                                        $i === 1 => '#94a3b8',
                                        $i === 2 => '#c2803e',
                                        default => '#e2e8f0',
                                    };
                                    $qualityColor = $doc['quality'] >= 80 ? '#10b981' : ($doc['quality'] >= 60 ? '#f59e0b' : '#ef4444');
                                @endphp
                                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s; cursor: pointer;"
                                    onmouseenter="this.style.background='#f0fdfa'"
                                    onmouseleave="this.style.background='transparent'"
                                    x-on:click="doctor = {{ json_encode($doc) }}; showDoctor = true">
                                    <td style="padding: 10px 6px;">
                                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; background: {{ $rankColor }}; color: {{ $i < 3 ? '#fff' : '#64748b' }}; font-weight: 700; font-size: 11px;">
                                            {{ $i + 1 }}
                                        </span>
                                    </td>
                                    <td style="padding: 10px 6px; font-weight: 600; color: #0f766e; text-decoration: underline; text-decoration-style: dotted;">
                                        {{ $doc['full_name'] }}
                                    </td>
                                    <td style="padding: 10px 6px; color: #64748b;">
                                        {{ str($doc['specialty'])->limit(25) }}
                                    </td>
                                    <td style="padding: 10px 6px; color: #64748b; font-size: 12px;">
                                        {{ str($doc['clinic'])->limit(30) }}
                                    </td>
                                    <td style="padding: 10px 6px; color: #64748b; font-size: 12px;">
                                        {{ str($doc['department'])->limit(20) }}
                                    </td>
                                    <td style="padding: 10px 6px; text-align: center;">
                                        <span style="display: inline-block; background: #dbeafe; color: #1e40af; font-weight: 700; font-size: 12px; padding: 3px 10px; border-radius: 12px;">
                                            {{ $doc['responses'] }}
                                        </span>
                                    </td>
                                    <td style="padding: 10px 6px; text-align: center;">
                                        <span style="display: inline-block; background: {{ $qualityColor }}20; color: {{ $qualityColor }}; font-weight: 700; font-size: 12px; padding: 3px 10px; border-radius: 12px;">
                                            {{ $doc['quality'] }}
                                        </span>
                                    </td>
                                    <td style="padding: 10px 6px; text-align: center; font-weight: 600; color: #334155;">
                                        {{ $doc['confidence'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="padding: 24px; text-align: center; color: #94a3b8;">
                                        Ma'lumot topilmadi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Doctor Detail Modal --}}
                <div x-cloak x-show="showDoctor" x-transition.opacity
                     style="position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);"
                     x-on:click.self="showDoctor = false" x-on:keydown.escape.window="showDoctor = false">
                    <div x-show="showDoctor" x-transition.scale.95
                         style="background: white; border-radius: 20px; width: 480px; max-width: 95vw; box-shadow: 0 25px 60px rgba(0,0,0,0.25); overflow: hidden;">
                        {{-- Modal Header --}}
                        <div style="background: linear-gradient(135deg, #0f766e, #0d9488); padding: 24px; text-align: center; position: relative;">
                            <button x-on:click="showDoctor = false" style="position: absolute; top: 12px; right: 12px; background: rgba(255,255,255,0.2); border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; color: white; font-size: 16px; display: flex; align-items: center; justify-content: center;">✕</button>
                            <div style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center;">
                                <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/>
                                </svg>
                            </div>
                            <h3 style="color: white; font-size: 18px; font-weight: 700; margin: 0;" x-text="doctor.full_name"></h3>
                            <p style="color: rgba(255,255,255,0.8); font-size: 13px; margin: 4px 0 0;" x-text="doctor.specialty"></p>
                        </div>
                        {{-- Modal Body --}}
                        <div style="padding: 24px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                                <div style="background: #f0fdfa; border: 1px solid #99f6e4; border-radius: 12px; padding: 12px;">
                                    <div style="font-size: 10px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Klinika</div>
                                    <div style="font-size: 13px; font-weight: 600; color: #1e293b;" x-text="doctor.clinic"></div>
                                </div>
                                <div style="background: #f0fdfa; border: 1px solid #99f6e4; border-radius: 12px; padding: 12px;">
                                    <div style="font-size: 10px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Bo'lim</div>
                                    <div style="font-size: 13px; font-weight: 600; color: #1e293b;" x-text="doctor.department"></div>
                                </div>
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                                    <div style="font-size: 10px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Filial</div>
                                    <div style="font-size: 13px; font-weight: 600; color: #1e293b;" x-text="doctor.branch || '—'"></div>
                                </div>
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                                    <div style="font-size: 10px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Tajriba</div>
                                    <div style="font-size: 13px; font-weight: 600; color: #1e293b;"><span x-text="doctor.experience || '—'"></span> yil</div>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                                <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 12px;">
                                    <div style="font-size: 10px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">📞 Telefon raqam</div>
                                    <div style="font-size: 14px; font-weight: 700; color: #1e40af;" x-text="doctor.phone && doctor.phone !== '—' ? doctor.phone : 'Kiritilmagan'"></div>
                                </div>
                                <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 12px;">
                                    <div style="font-size: 10px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">💬 Telegram ID</div>
                                    <div style="font-size: 14px; font-weight: 700; color: #1e40af;" x-text="doctor.telegram && doctor.telegram !== '—' ? doctor.telegram : 'Kiritilmagan'"></div>
                                </div>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                                <div style="background: #fefce8; border: 1px solid #fde68a; border-radius: 12px; padding: 12px;">
                                    <div style="font-size: 10px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">🏥 Klinika telefoni</div>
                                    <div style="font-size: 14px; font-weight: 700; color: #92400e;" x-text="doctor.clinic_phone && doctor.clinic_phone !== '—' ? doctor.clinic_phone : 'Kiritilmagan'"></div>
                                </div>
                                <div style="background: #fefce8; border: 1px solid #fde68a; border-radius: 12px; padding: 12px;">
                                    <div style="font-size: 10px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Holati</div>
                                    <div style="font-size: 14px; font-weight: 700; color: #92400e;" x-text="doctor.status || '—'"></div>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 16px;">
                                <div style="text-align: center; background: #dbeafe; border-radius: 12px; padding: 10px;">
                                    <div style="font-size: 20px; font-weight: 800; color: #1e40af;" x-text="doctor.responses"></div>
                                    <div style="font-size: 10px; color: #3b82f6; font-weight: 600;">Javoblar</div>
                                </div>
                                <div style="text-align: center; border-radius: 12px; padding: 10px;"
                                     x-bind:style="'background:' + (doctor.quality >= 80 ? '#dcfce7' : doctor.quality >= 60 ? '#fef3c7' : '#fee2e2')">
                                    <div style="font-size: 20px; font-weight: 800;"
                                         x-bind:style="'color:' + (doctor.quality >= 80 ? '#16a34a' : doctor.quality >= 60 ? '#d97706' : '#dc2626')"
                                         x-text="doctor.quality"></div>
                                    <div style="font-size: 10px; font-weight: 600; color: #64748b;">Sifat bali</div>
                                </div>
                                <div style="text-align: center; background: #f1f5f9; border-radius: 12px; padding: 10px;">
                                    <div style="font-size: 20px; font-weight: 800; color: #334155;" x-text="doctor.confidence"></div>
                                    <div style="font-size: 10px; color: #64748b; font-weight: 600;">Ishonch</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="sr-side" x-data="{ chartTab: 'quality' }">
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

                <div class="sr-chart-tabs" role="tablist">
                    <button type="button" role="tab" class="sr-tab-btn" x-bind:class="{ 'is-active': chartTab === 'quality' }" x-on:click="chartTab = 'quality'">
                        {{ __('ui.executive.doctor_chart_quality') }}
                    </button>
                    <button type="button" role="tab" class="sr-tab-btn" x-bind:class="{ 'is-active': chartTab === 'confidence' }" x-on:click="chartTab = 'confidence'">
                        {{ __('ui.executive.doctor_chart_confidence') }}
                    </button>
                    <button type="button" role="tab" class="sr-tab-btn" x-bind:class="{ 'is-active': chartTab === 'trend' }" x-on:click="chartTab = 'trend'">
                        {{ __('ui.executive.doctor_chart_trend') }}
                    </button>
                    <button type="button" role="tab" class="sr-tab-btn" x-bind:class="{ 'is-active': chartTab === 'risk' }" x-on:click="chartTab = 'risk'">
                        {{ __('ui.executive.doctor_chart_risk') }}
                    </button>
                </div>

                {{-- Quality Score Chart --}}
                <div x-cloak x-show="chartTab === 'quality'" class="sr-chart-panel">
                    @php $maxQuality = max($chartQuality->max('value'), 1); @endphp
                    @forelse ($chartQuality as $item)
                        <div class="sr-hbar-row">
                            <span class="sr-hbar-label">{{ $item['name'] }}</span>
                            <div class="sr-hbar-track">
                                <div class="sr-hbar-fill sr-hbar-blue" style="width: {{ max(round(($item['value'] / $maxQuality) * 100), 5) }}%"></div>
                            </div>
                            <span class="sr-hbar-value">{{ number_format($item['value'], 1) }}</span>
                        </div>
                    @empty
                        <p class="sr-doctor-empty">{{ __('ui.executive.doctor_no_data') }}</p>
                    @endforelse
                </div>

                {{-- Confidence Score Chart --}}
                <div x-cloak x-show="chartTab === 'confidence'" class="sr-chart-panel">
                    @php $maxConf = max($chartConfidence->max('value'), 1); @endphp
                    @forelse ($chartConfidence as $item)
                        <div class="sr-hbar-row">
                            <span class="sr-hbar-label">{{ $item['name'] }}</span>
                            <div class="sr-hbar-track">
                                <div class="sr-hbar-fill sr-hbar-teal" style="width: {{ max(round(($item['value'] / $maxConf) * 100), 5) }}%"></div>
                            </div>
                            <span class="sr-hbar-value">{{ number_format($item['value'], 1) }}</span>
                        </div>
                    @empty
                        <p class="sr-doctor-empty">{{ __('ui.executive.doctor_no_data') }}</p>
                    @endforelse
                </div>

                {{-- Trend Chart --}}
                <div x-cloak x-show="chartTab === 'trend'" class="sr-chart-panel">
                    @php $maxTrend = max($chartTrend->max(fn ($t) => abs($t['value'])), 0.01); @endphp
                    @forelse ($chartTrend as $item)
                        @php
                            $isPositive = $item['value'] >= 0;
                            $barWidth = max(round((abs($item['value']) / $maxTrend) * 50), 3);
                        @endphp
                        <div class="sr-trend-row">
                            <span class="sr-hbar-label">{{ $item['name'] }}</span>
                            <div class="sr-trend-track">
                                @if ($isPositive)
                                    <div class="sr-trend-center"></div>
                                    <div class="sr-trend-bar sr-trend-positive" style="width: {{ $barWidth }}%; left: 50%;"></div>
                                @else
                                    <div class="sr-trend-center"></div>
                                    <div class="sr-trend-bar sr-trend-negative" style="width: {{ $barWidth }}%; right: 50%;"></div>
                                @endif
                            </div>
                            <span class="sr-hbar-value {{ $isPositive ? 'is-positive-text' : 'is-negative-text' }}">{{ $formatTrend($item['value']) }}</span>
                        </div>
                    @empty
                        <p class="sr-doctor-empty">{{ __('ui.executive.doctor_no_data') }}</p>
                    @endforelse
                </div>

                {{-- Risk Chart --}}
                <div x-cloak x-show="chartTab === 'risk'" class="sr-chart-panel">
                    @php $maxRisk = max($chartRisk->max('value'), 1); @endphp
                    @forelse ($chartRisk as $item)
                        @php
                            $riskLevel = $item['value'] > 40 ? 'sr-hbar-red' : ($item['value'] > 20 ? 'sr-hbar-orange' : 'sr-hbar-green');
                        @endphp
                        <div class="sr-hbar-row">
                            <span class="sr-hbar-label">{{ $item['name'] }}</span>
                            <div class="sr-hbar-track">
                                <div class="sr-hbar-fill {{ $riskLevel }}" style="width: {{ max(round(($item['value'] / $maxRisk) * 100), 5) }}%"></div>
                            </div>
                            <span class="sr-hbar-value">{{ number_format($item['value'], 1) }}</span>
                        </div>
                    @empty
                        <p class="sr-doctor-empty">{{ __('ui.executive.doctor_no_data') }}</p>
                    @endforelse
                </div>
            </aside>
        </div>
    </section>
</x-filament-widgets::widget>
