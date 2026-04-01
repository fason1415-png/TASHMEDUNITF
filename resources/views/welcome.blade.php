<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ShifoReyting AI') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f2f5fb;
            --ink: #143b6b;
            --muted: #5f7393;
            --line: #d8e0ed;
            --card: #ffffff;
            --accent: #2f6ef8;
            --accent-soft: rgba(47, 110, 248, 0.12);
            --good: #17a672;
            --warn: #f59e0b;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #0f1725;
                --ink: #dbeafe;
                --muted: #9fb1cd;
                --line: #263347;
                --card: #141f33;
                --accent: #60a5fa;
                --accent-soft: rgba(96, 165, 250, 0.16);
                --good: #34d399;
                --warn: #fbbf24;
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background:
                radial-gradient(circle at 10% 0%, rgba(47, 110, 248, 0.16), transparent 45%),
                radial-gradient(circle at 90% 10%, rgba(23, 166, 114, 0.14), transparent 40%),
                var(--bg);
            color: var(--ink);
            font-family: "Manrope", sans-serif;
        }

        .container {
            width: min(1200px, calc(100% - 32px));
            margin: 0 auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 28px 0 14px;
        }

        .brand {
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.08rem;
            letter-spacing: 0.02em;
            font-weight: 700;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .lang-switch {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(6px);
        }

        .lang-link {
            border-radius: 9px;
            padding: 7px 10px;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--muted);
            text-decoration: none;
            transition: 160ms ease;
            white-space: nowrap;
        }

        .lang-link:hover {
            color: var(--ink);
            background: rgba(47, 110, 248, 0.08);
        }

        .lang-link.active {
            color: #ffffff;
            background: linear-gradient(120deg, #2f6ef8, #2457d1);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            padding: 10px 16px;
            font-weight: 700;
            text-decoration: none;
            border: 1px solid var(--line);
            transition: 160ms ease;
            color: var(--ink);
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(6px);
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 26px rgba(20, 59, 107, 0.08);
        }

        .btn-primary {
            border-color: transparent;
            color: #ffffff;
            background: linear-gradient(120deg, #2f6ef8, #2457d1);
        }

        .hero {
            padding: 8px 0 36px;
            animation: rise 560ms ease;
        }

        .hero h1 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(2rem, 5vw, 3.4rem);
            line-height: 1.1;
            letter-spacing: -0.02em;
            max-width: 950px;
        }

        .hero p {
            margin: 14px 0 0;
            color: var(--muted);
            font-size: clamp(1rem, 2vw, 1.2rem);
            max-width: 760px;
        }

        .grid {
            margin-top: 24px;
            display: grid;
            grid-template-columns: 1.55fr 0.95fr;
            gap: 22px;
        }

        .card {
            border: 1px solid var(--line);
            border-radius: 22px;
            background: var(--card);
            box-shadow: 0 14px 42px rgba(15, 23, 42, 0.08);
        }

        .dashboard {
            padding: 16px;
        }

        .dash-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .dash-title {
            font-weight: 800;
            font-size: 1.02rem;
        }

        .dash-sub {
            margin-top: 4px;
            font-size: 0.85rem;
            color: var(--muted);
        }

        .filters {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pill {
            padding: 5px 9px;
            border-radius: 999px;
            border: 1px solid var(--line);
            font-size: 0.74rem;
            color: var(--muted);
            background: #fff;
        }

        @media (prefers-color-scheme: dark) {
            .pill {
                background: rgba(20, 31, 51, 0.75);
            }
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 12px;
        }

        .stat {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 9px;
            background: rgba(255, 255, 255, 0.65);
        }

        @media (prefers-color-scheme: dark) {
            .stat {
                background: rgba(20, 31, 51, 0.62);
            }
        }

        .stat-label {
            font-size: 0.72rem;
            color: var(--muted);
        }

        .stat-value {
            margin-top: 4px;
            font-size: 1.4rem;
            font-weight: 800;
            line-height: 1;
        }

        .stat-diff {
            margin-top: 3px;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--good);
        }

        .viz {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 10px;
        }

        .panel {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 9px;
            min-height: 160px;
            background: rgba(255, 255, 255, 0.65);
        }

        @media (prefers-color-scheme: dark) {
            .panel {
                background: rgba(20, 31, 51, 0.62);
            }
        }

        .panel h4 {
            margin: 0 0 8px;
            font-size: 0.76rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .bars {
            display: flex;
            align-items: flex-end;
            height: 122px;
            gap: 5px;
        }

        .bars span {
            flex: 1;
            border-radius: 6px 6px 2px 2px;
            background: linear-gradient(180deg, rgba(47, 110, 248, 0.94), rgba(47, 110, 248, 0.46));
        }

        .bars span:nth-child(odd) {
            background: linear-gradient(180deg, rgba(141, 161, 197, 0.92), rgba(141, 161, 197, 0.45));
        }

        .ring-wrap {
            display: grid;
            gap: 8px;
            grid-template-columns: repeat(2, 1fr);
        }

        .ring-card {
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 7px;
        }

        .ring {
            margin: 2px auto 6px;
            width: 62px;
            height: 62px;
            border-radius: 999px;
            background: conic-gradient(var(--accent) 0 56%, #b8c4d8 56% 100%);
            position: relative;
        }

        .ring::after {
            content: "";
            position: absolute;
            inset: 11px;
            border-radius: 999px;
            background: var(--card);
        }

        .ring-label {
            text-align: center;
            font-size: 0.69rem;
            color: var(--muted);
        }

        .dept {
            margin-top: 8px;
            display: grid;
            gap: 6px;
        }

        .dept-row {
            display: grid;
            grid-template-columns: 70px 1fr;
            align-items: center;
            gap: 7px;
            font-size: 0.69rem;
            color: var(--muted);
        }

        .dept-line {
            height: 8px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--accent), rgba(47, 110, 248, 0.2));
        }

        .trend {
            margin-top: 10px;
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 9px;
            background: rgba(255, 255, 255, 0.65);
        }

        @media (prefers-color-scheme: dark) {
            .trend {
                background: rgba(20, 31, 51, 0.62);
            }
        }

        .sparkline {
            margin-top: 10px;
            width: 100%;
            height: 56px;
        }

        .doctor {
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background:
                linear-gradient(160deg, rgba(47, 110, 248, 0.08), transparent 44%),
                linear-gradient(200deg, rgba(23, 166, 114, 0.08), transparent 58%),
                var(--card);
        }

        .doctor h3 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(1.4rem, 2vw, 2rem);
        }

        .doctor ul {
            margin: 18px 0 0;
            padding-left: 1.2rem;
            display: grid;
            gap: 8px;
            font-size: clamp(1rem, 1.5vw, 1.2rem);
            color: var(--ink);
        }

        .subline {
            margin: 28px auto 10px;
            text-align: center;
            color: var(--muted);
            font-size: clamp(1.15rem, 2.2vw, 2rem);
            max-width: 900px;
        }

        .feature-grid {
            margin-top: 18px;
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .feature {
            border: 1px solid var(--line);
            background: var(--card);
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        }

        .feature h4 {
            margin: 0;
            font-size: 1rem;
            font-weight: 800;
        }

        .feature p {
            margin: 8px 0 0;
            color: var(--muted);
            line-height: 1.55;
            font-size: 0.92rem;
        }

        .footer-cta {
            margin: 24px 0 38px;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 980px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .viz {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .container {
                width: min(1200px, calc(100% - 20px));
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
                padding: 18px 0 10px;
            }

            .actions {
                width: 100%;
            }

            .lang-switch {
                width: 100%;
                overflow-x: auto;
                justify-content: flex-start;
            }

            .dash-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .filters {
                width: 100%;
                overflow-x: auto;
                flex-wrap: nowrap;
                padding-bottom: 2px;
            }

            .pill {
                white-space: nowrap;
            }

            .btn {
                width: 100%;
            }

            .hero {
                padding: 6px 0 26px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .dashboard {
                padding: 12px;
            }

            .doctor {
                padding: 18px;
            }

            .feature {
                padding: 14px;
            }
        }
    </style>
</head>
<body>
@php
    $availableLocales = ['uz_latn', 'ru', 'en'];
    $currentLang = app()->getLocale();
@endphp
<div class="container">
    <header class="topbar">
        <div class="brand">ShifoReyting AI</div>
        <div class="actions">
            <div class="lang-switch">
                @foreach ($availableLocales as $localeCode)
                    <a
                        class="lang-link {{ $currentLang === $localeCode ? 'active' : '' }}"
                        href="{{ request()->fullUrlWithQuery(['lang' => $localeCode]) }}"
                    >
                        {{ __('ui.locale.'.$localeCode) }}
                    </a>
                @endforeach
            </div>
            <a class="btn" href="{{ route('filament.admin.auth.login', ['lang' => $currentLang]) }}">{{ __('ui.landing.admin_login') }}</a>
            <a class="btn btn-primary" href="{{ url('/admin').'?lang='.$currentLang }}">{{ __('ui.landing.management_panel') }}</a>
        </div>
    </header>

    <section class="hero">
        <h1>{{ __('ui.landing.hero_title') }}</h1>
        <p>
            {{ __('ui.landing.hero_subtitle') }}
        </p>

        <div class="grid">
            <article class="card dashboard">
                <div class="dash-head">
                    <div>
                        <div class="dash-title">{{ __('ui.landing.dashboard_title') }}</div>
                        <div class="dash-sub">{{ __('ui.landing.dashboard_sub') }}</div>
                    </div>
                    <div class="filters">
                        <span class="pill">{{ __('ui.landing.filter_today') }}</span>
                        <span class="pill">{{ __('ui.landing.filter_week') }}</span>
                        <span class="pill">{{ __('ui.landing.filter_month') }}</span>
                    </div>
                </div>

                <div class="stats">
                    <div class="stat">
                        <div class="stat-label">{{ __('ui.landing.patients') }}</div>
                        <div class="stat-value">29,206</div>
                        <div class="stat-diff">+3.49%</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">{{ __('ui.landing.inpatients') }}</div>
                        <div class="stat-value">13,955</div>
                        <div class="stat-diff">+2.88%</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">{{ __('ui.landing.outpatients') }}</div>
                        <div class="stat-value">13,955</div>
                        <div class="stat-diff">+2.88%</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">{{ __('ui.landing.complaints') }}</div>
                        <div class="stat-value">49</div>
                        <div class="stat-diff" style="color:var(--warn)">+0.42%</div>
                    </div>
                </div>

                <div class="viz">
                    <div class="panel">
                        <h4>{{ __('ui.landing.patients_trend') }}</h4>
                        <div class="bars" aria-hidden="true">
                            <span style="height:38%"></span>
                            <span style="height:62%"></span>
                            <span style="height:54%"></span>
                            <span style="height:70%"></span>
                            <span style="height:46%"></span>
                            <span style="height:76%"></span>
                            <span style="height:58%"></span>
                            <span style="height:83%"></span>
                            <span style="height:63%"></span>
                            <span style="height:67%"></span>
                            <span style="height:51%"></span>
                            <span style="height:74%"></span>
                        </div>
                    </div>

                    <div class="panel">
                        <h4>{{ __('ui.landing.patients_by_gender') }}</h4>
                        <div class="ring-wrap">
                            <div class="ring-card">
                                <div class="ring"></div>
                                <div class="ring-label">{{ __('ui.landing.female') }} 55%</div>
                            </div>
                            <div class="ring-card">
                                <div class="ring" style="background: conic-gradient(#3bb07d 0 44%, #b8c4d8 44% 100%);"></div>
                                <div class="ring-label">{{ __('ui.landing.male') }} 45%</div>
                            </div>
                        </div>
                        <div class="dept">
                            <div class="dept-row"><span>Cardiology</span><span class="dept-line" style="width:86%"></span></div>
                            <div class="dept-row"><span>ICU</span><span class="dept-line" style="width:74%"></span></div>
                            <div class="dept-row"><span>Neurology</span><span class="dept-line" style="width:61%"></span></div>
                        </div>
                    </div>
                </div>

                <div class="trend">
                    <h4>{{ __('ui.landing.bed_occupancy_rate') }}</h4>
                    <svg class="sparkline" viewBox="0 0 360 56" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Bed Occupancy Trend">
                        <path d="M6 38C24 36 42 34 60 31C78 28 96 27 114 30C132 33 150 29 168 24C186 20 204 18 222 20C240 22 258 24 276 21C294 18 312 16 330 19C344 21 352 23 354 24" stroke="#2f6ef8" stroke-width="3" stroke-linecap="round"/>
                        <path d="M6 38C24 36 42 34 60 31C78 28 96 27 114 30C132 33 150 29 168 24C186 20 204 18 222 20C240 22 258 24 276 21C294 18 312 16 330 19C344 21 352 23 354 24" stroke="url(#fade)" stroke-width="12" stroke-linecap="round" opacity="0.18"/>
                        <defs>
                            <linearGradient id="fade" x1="0" y1="0" x2="360" y2="0">
                                <stop stop-color="#2f6ef8"/>
                                <stop offset="1" stop-color="#17a672"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
            </article>

            <aside class="card doctor">
                <h3>{{ __('ui.landing.doctor_panel_title') }}</h3>
                <ul>
                    <li>{{ __('ui.landing.doctor_item_1') }}</li>
                    <li>{{ __('ui.landing.doctor_item_2') }}</li>
                    <li>{{ __('ui.landing.doctor_item_3') }}</li>
                    <li>{{ __('ui.landing.doctor_item_4') }}</li>
                    <li>{{ __('ui.landing.doctor_item_5') }}</li>
                </ul>
            </aside>
        </div>

        <p class="subline">{{ __('ui.landing.subline') }}</p>

        <div class="feature-grid">
            <article class="feature">
                <h4>{{ __('ui.landing.feature_1_title') }}</h4>
                <p>{{ __('ui.landing.feature_1_text') }}</p>
            </article>
            <article class="feature">
                <h4>{{ __('ui.landing.feature_2_title') }}</h4>
                <p>{{ __('ui.landing.feature_2_text') }}</p>
            </article>
            <article class="feature">
                <h4>{{ __('ui.landing.feature_3_title') }}</h4>
                <p>{{ __('ui.landing.feature_3_text') }}</p>
            </article>
        </div>

        <div class="footer-cta">
            <a class="btn btn-primary" href="{{ url('/admin').'?lang='.$currentLang }}">{{ __('ui.landing.open_dashboard') }}</a>
            <a class="btn" href="{{ route('filament.admin.auth.login', ['lang' => $currentLang]) }}">{{ __('ui.landing.admin_login') }}</a>
        </div>
    </section>
</div>
</body>
</html>

