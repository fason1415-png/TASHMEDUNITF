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
            background:
                linear-gradient(160deg, rgba(47, 110, 248, 0.08), transparent 44%),
                linear-gradient(200deg, rgba(23, 166, 114, 0.08), transparent 58%),
                var(--card);
        }

        .doctor h3 {
            margin: 0 0 16px;
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(1.4rem, 2vw, 2rem);
        }

        .doctor-charts {
            display: grid;
            gap: 14px;
            flex: 1;
        }

        .doctor-chart-block {
            background: rgba(255,255,255,0.7);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 14px 16px;
        }

        .doctor-chart-block h4 {
            margin: 0 0 10px;
            font-size: 0.82rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            color: var(--muted);
        }

        .dc-bar-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .dc-bar-row:last-child {
            margin-bottom: 0;
        }

        .dc-bar-name {
            width: 70px;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--ink);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-shrink: 0;
        }

        .dc-bar-track {
            flex: 1;
            height: 14px;
            background: #edf3fc;
            border-radius: 7px;
            overflow: hidden;
        }

        .dc-bar-fill {
            height: 100%;
            border-radius: 7px;
            transition: width 600ms cubic-bezier(0.22, 1, 0.36, 1);
        }

        .dc-bar-fill.blue { background: linear-gradient(90deg, #2f6ef8, #5a93ff); }
        .dc-bar-fill.teal { background: linear-gradient(90deg, #12a06e, #35c98e); }
        .dc-bar-fill.orange { background: linear-gradient(90deg, #e69a2e, #f0b94d); }

        .dc-bar-val {
            width: 36px;
            text-align: right;
            font-size: 0.76rem;
            font-weight: 800;
            color: var(--ink);
            flex-shrink: 0;
        }

        .dc-mini-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .dc-mini-stat {
            text-align: center;
            background: rgba(47,110,248,0.06);
            border-radius: 10px;
            padding: 10px 6px;
        }

        .dc-mini-stat .val {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--ink);
        }

        .dc-mini-stat .lbl {
            font-size: 0.68rem;
            color: var(--muted);
            margin-top: 2px;
            font-weight: 700;
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

        .section-block {
            margin: 32px 0;
            animation: rise 560ms ease both;
        }

        .section-title {
            text-align: center;
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(1.5rem, 3vw, 2.2rem);
            margin: 0 0 8px;
            letter-spacing: -0.01em;
        }

        .section-sub {
            text-align: center;
            color: var(--muted);
            margin: 0 0 20px;
            font-size: 1rem;
        }

        /* Steps */
        .steps-grid {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-top: 20px;
        }

        .step-card {
            flex: 1;
            max-width: 260px;
            text-align: center;
            padding: 20px 16px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--card);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            position: relative;
        }

        .step-card h4 { margin: 10px 0 6px; font-size: 1rem; }
        .step-card p { margin: 0; font-size: 0.85rem; color: var(--muted); line-height: 1.5; }

        .step-num {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 26px; height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2f6ef8, #17a672);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .step-icon { font-size: 2rem; margin-top: 4px; }

        .step-arrow {
            font-size: 1.4rem;
            color: var(--muted);
            padding: 0 8px;
        }

        /* Panels grid */
        .panels-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-top: 20px;
        }

        .panel-card {
            padding: 22px 18px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: var(--card);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            transition: transform 200ms ease, box-shadow 200ms ease;
        }

        .panel-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.1);
        }

        .panel-card h4 { margin: 10px 0 6px; font-size: 1.05rem; }
        .panel-card p { margin: 0; font-size: 0.88rem; color: var(--muted); line-height: 1.5; }
        .panel-icon { font-size: 2rem; }

        .panel-blue { border-top: 3px solid #2f6ef8; }
        .panel-teal { border-top: 3px solid #17a672; }
        .panel-amber { border-top: 3px solid #f59e0b; }

        /* Patronage */
        .patronage-hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: center;
        }

        .patronage-sub {
            color: var(--muted);
            margin: 8px 0 16px;
            font-size: 1rem;
        }

        .patronage-list { display: grid; gap: 10px; }

        .patronage-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .p-check {
            width: 24px; height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, #17a672, #22d3a8);
            color: #fff;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .patronage-visual {
            display: flex;
            justify-content: center;
        }

        .flow-card {
            display: flex;
            align-items: center;
            gap: 0;
            padding: 20px;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        }

        .flow-step {
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 700;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.6);
            color: var(--muted);
            white-space: nowrap;
        }

        .flow-step.active {
            border-color: #17a672;
            background: rgba(23, 166, 114, 0.1);
            color: #17a672;
        }

        .flow-line {
            width: 24px;
            height: 2px;
            background: var(--line);
            flex-shrink: 0;
        }

        /* Telegram */
        .tg-demo { display: flex; justify-content: center; }

        .tg-phone {
            width: min(380px, 100%);
            border: 1px solid var(--line);
            border-radius: 22px;
            background: var(--card);
            box-shadow: 0 14px 42px rgba(15, 23, 42, 0.08);
            padding: 0;
            overflow: hidden;
        }

        .tg-header {
            padding: 14px 18px;
            font-weight: 800;
            font-size: 0.95rem;
            background: linear-gradient(135deg, #0088cc, #00aaee);
            color: #fff;
        }

        .tg-msg {
            padding: 8px 16px;
            margin: 6px 12px;
            border-radius: 12px;
            font-size: 0.82rem;
            line-height: 1.5;
        }

        .tg-bot {
            background: rgba(47, 110, 248, 0.08);
            border: 1px solid rgba(47, 110, 248, 0.15);
            color: var(--ink);
        }

        .tg-success {
            background: rgba(23, 166, 114, 0.1);
            border-color: rgba(23, 166, 114, 0.25);
            color: #17a672;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .tg-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            padding: 2px 12px 6px;
        }

        .tg-btn {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.76rem;
            font-weight: 700;
            border: 1px solid rgba(0, 136, 204, 0.3);
            background: rgba(0, 136, 204, 0.06);
            color: #0088cc;
            cursor: default;
        }

        .tg-selected {
            background: #0088cc;
            color: #fff;
            border-color: #0088cc;
        }

        .tg-stars { gap: 4px; }

        /* CTA */
        .cta-section {
            text-align: center;
            padding: 40px 20px;
            border-radius: 22px;
            background:
                linear-gradient(135deg, rgba(47, 110, 248, 0.08), rgba(23, 166, 114, 0.08)),
                var(--card);
            border: 1px solid var(--line);
            margin-bottom: 40px;
        }

        .cta-title {
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(1.3rem, 2.5vw, 1.8rem);
            margin: 0 0 16px;
        }

        .cta-buttons { display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; }
        .btn-lg { padding: 14px 28px; font-size: 1rem; border-radius: 14px; }

        @media (max-width: 980px) {
            .steps-grid { flex-direction: column; }
            .step-arrow { transform: rotate(90deg); }
            .step-card { max-width: 100%; }
            .panels-grid { grid-template-columns: 1fr; }
            .patronage-hero { grid-template-columns: 1fr; }
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
                <div class="doctor-charts">
                    <div class="dc-mini-grid">
                        <div class="dc-mini-stat">
                            <div class="val">87.4</div>
                            <div class="lbl">Quality</div>
                        </div>
                        <div class="dc-mini-stat">
                            <div class="val">92.1</div>
                            <div class="lbl">Confidence</div>
                        </div>
                        <div class="dc-mini-stat">
                            <div class="val">+3.2%</div>
                            <div class="lbl">Trend</div>
                        </div>
                    </div>

                    <div class="doctor-chart-block">
                        <h4>Quality Score</h4>
                        <div class="dc-bar-row">
                            <span class="dc-bar-name">Dr. Karimov</span>
                            <div class="dc-bar-track"><div class="dc-bar-fill blue" style="width:94%"></div></div>
                            <span class="dc-bar-val">94.2</span>
                        </div>
                        <div class="dc-bar-row">
                            <span class="dc-bar-name">Dr. Aliyeva</span>
                            <div class="dc-bar-track"><div class="dc-bar-fill blue" style="width:89%"></div></div>
                            <span class="dc-bar-val">89.1</span>
                        </div>
                        <div class="dc-bar-row">
                            <span class="dc-bar-name">Dr. Rashidov</span>
                            <div class="dc-bar-track"><div class="dc-bar-fill blue" style="width:85%"></div></div>
                            <span class="dc-bar-val">85.3</span>
                        </div>
                        <div class="dc-bar-row">
                            <span class="dc-bar-name">Dr. Nodira</span>
                            <div class="dc-bar-track"><div class="dc-bar-fill blue" style="width:78%"></div></div>
                            <span class="dc-bar-val">78.6</span>
                        </div>
                    </div>

                    <div class="doctor-chart-block">
                        <h4>Haftalik trend</h4>
                        <div class="dc-bar-row">
                            <span class="dc-bar-name">Dr. Karimov</span>
                            <div class="dc-bar-track"><div class="dc-bar-fill teal" style="width:82%"></div></div>
                            <span class="dc-bar-val">+5.1</span>
                        </div>
                        <div class="dc-bar-row">
                            <span class="dc-bar-name">Dr. Aliyeva</span>
                            <div class="dc-bar-track"><div class="dc-bar-fill teal" style="width:68%"></div></div>
                            <span class="dc-bar-val">+3.4</span>
                        </div>
                        <div class="dc-bar-row">
                            <span class="dc-bar-name">Dr. Rashidov</span>
                            <div class="dc-bar-track"><div class="dc-bar-fill orange" style="width:30%"></div></div>
                            <span class="dc-bar-val">-1.2</span>
                        </div>
                        <div class="dc-bar-row">
                            <span class="dc-bar-name">Dr. Nodira</span>
                            <div class="dc-bar-track"><div class="dc-bar-fill teal" style="width:55%"></div></div>
                            <span class="dc-bar-val">+2.0</span>
                        </div>
                    </div>
                </div>
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
    </section>

    {{-- How it works --}}
    <section class="section-block" style="animation-delay: 0.1s">
        <h2 class="section-title">{{ __('ui.landing.how_it_works') }}</h2>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-num">1</div>
                <div class="step-icon">&#128241;</div>
                <h4>{{ __('ui.landing.step_1_title') }}</h4>
                <p>{{ __('ui.landing.step_1_text') }}</p>
            </div>
            <div class="step-arrow">&#10132;</div>
            <div class="step-card">
                <div class="step-num">2</div>
                <div class="step-icon">&#11088;</div>
                <h4>{{ __('ui.landing.step_2_title') }}</h4>
                <p>{{ __('ui.landing.step_2_text') }}</p>
            </div>
            <div class="step-arrow">&#10132;</div>
            <div class="step-card">
                <div class="step-num">3</div>
                <div class="step-icon">&#129302;</div>
                <h4>{{ __('ui.landing.step_3_title') }}</h4>
                <p>{{ __('ui.landing.step_3_text') }}</p>
            </div>
        </div>
    </section>

    {{-- 3 Panels --}}
    <section class="section-block" style="animation-delay: 0.15s">
        <h2 class="section-title">{{ __('ui.landing.panels_title') }}</h2>
        <div class="panels-grid">
            <div class="panel-card panel-blue">
                <div class="panel-icon">&#127963;</div>
                <h4>{{ __('ui.landing.panel_ministry') }}</h4>
                <p>{{ __('ui.landing.panel_ministry_desc') }}</p>
            </div>
            <div class="panel-card panel-teal">
                <div class="panel-icon">&#127973;</div>
                <h4>{{ __('ui.landing.panel_clinic') }}</h4>
                <p>{{ __('ui.landing.panel_clinic_desc') }}</p>
            </div>
            <div class="panel-card panel-amber">
                <div class="panel-icon">&#129657;</div>
                <h4>{{ __('ui.landing.panel_doctor') }}</h4>
                <p>{{ __('ui.landing.panel_doctor_desc') }}</p>
            </div>
        </div>
    </section>

    {{-- Patronage --}}
    <section class="section-block" style="animation-delay: 0.2s">
        <div class="patronage-hero">
            <div class="patronage-info">
                <h2 class="section-title" style="text-align:left">{{ __('ui.landing.patronage_title') }}</h2>
                <p class="patronage-sub">{{ __('ui.landing.patronage_subtitle') }}</p>
                <div class="patronage-list">
                    <div class="patronage-item"><span class="p-check">&#10003;</span> {{ __('ui.landing.patronage_1') }}</div>
                    <div class="patronage-item"><span class="p-check">&#10003;</span> {{ __('ui.landing.patronage_2') }}</div>
                    <div class="patronage-item"><span class="p-check">&#10003;</span> {{ __('ui.landing.patronage_3') }}</div>
                    <div class="patronage-item"><span class="p-check">&#10003;</span> {{ __('ui.landing.patronage_4') }}</div>
                </div>
            </div>
            <div class="patronage-visual">
                <div class="flow-card">
                    <div class="flow-step active">Chiqarish</div>
                    <div class="flow-line"></div>
                    <div class="flow-step active">Xabar</div>
                    <div class="flow-line"></div>
                    <div class="flow-step">Tashrif</div>
                    <div class="flow-line"></div>
                    <div class="flow-step">Baholash</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Telegram Bot --}}
    <section class="section-block" style="animation-delay: 0.25s">
        <h2 class="section-title">{{ __('ui.landing.telegram_title') }}</h2>
        <p class="section-sub">{{ __('ui.landing.telegram_subtitle') }}</p>
        <div class="tg-demo">
            <div class="tg-phone">
                <div class="tg-header">ShifoReyting Bot</div>
                <div class="tg-msg tg-bot">Shifokorni baholash uchun klinikani tanlang:</div>
                <div class="tg-buttons">
                    <span class="tg-btn">Toshkent TTA</span>
                    <span class="tg-btn">Samarqand viloyat SB</span>
                </div>
                <div class="tg-msg tg-bot">Shifokorni tanlang:</div>
                <div class="tg-buttons">
                    <span class="tg-btn">Dr. Karimov</span>
                    <span class="tg-btn">Dr. Aliyeva</span>
                </div>
                <div class="tg-msg tg-bot">Baholang (1-5):</div>
                <div class="tg-buttons tg-stars">
                    <span class="tg-btn">1&#11088;</span>
                    <span class="tg-btn">2&#11088;</span>
                    <span class="tg-btn">3&#11088;</span>
                    <span class="tg-btn tg-selected">4&#11088;</span>
                    <span class="tg-btn">5&#11088;</span>
                </div>
                <div class="tg-msg tg-bot tg-success">&#9989; Rahmat! Bahoyingiz qabul qilindi.</div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="section-block cta-section" style="animation-delay: 0.3s">
        <h2 class="cta-title">{{ __('ui.landing.cta_title') }}</h2>
        <div class="cta-buttons">
            <a class="btn btn-primary btn-lg" href="{{ route('filament.admin.auth.login', ['lang' => $currentLang]) }}">{{ __('ui.landing.cta_login') }}</a>
        </div>
    </section>
</div>
</body>
</html>

