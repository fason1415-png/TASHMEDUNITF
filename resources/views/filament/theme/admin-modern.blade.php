<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap');

    :root {
        --sr-bg: #eef3fb;
        --sr-surface: #ffffff;
        --sr-surface-soft: #f6f9ff;
        --sr-border: #d9e3f2;
        --sr-text: #163763;
        --sr-muted: #5f7598;
        --sr-primary: #2f6ef8;
        --sr-primary-strong: #2457d1;
        --sr-teal: #17a672;
        --sr-shadow: 0 16px 42px rgba(17, 33, 63, 0.08);
        --font-family: 'Manrope', 'Segoe UI', sans-serif;
    }

    html.fi {
        --font-family: 'Manrope', 'Segoe UI', sans-serif;
        --mono-font-family: 'JetBrains Mono', 'Consolas', monospace;
    }

    .fi-body {
        background:
            radial-gradient(circle at 6% 0%, rgba(47, 110, 248, 0.12), transparent 34%),
            radial-gradient(circle at 98% 6%, rgba(23, 166, 114, 0.1), transparent 28%),
            var(--sr-bg);
    }

    .fi-layout {
        gap: 0;
    }

    .fi-main,
    .fi-main-ctn,
    .fi-page,
    .fi-page-content,
    .fi-section-content,
    .fi-topbar-start,
    .fi-topbar-end {
        min-width: 0;
    }

    .fi-sidebar.fi-main-sidebar {
        border-right: 1px solid var(--sr-border);
        background:
            radial-gradient(circle at 0% 0%, rgba(47, 110, 248, 0.12), transparent 34%),
            linear-gradient(180deg, #f4f8ff 0%, #eff4ff 100%);
        overscroll-behavior: contain;
    }

    .fi-sidebar-header-ctn {
        padding: 1rem 0.95rem 0.55rem;
    }

    .fi-sidebar-header {
        border: 1px solid var(--sr-border);
        border-radius: 1rem;
        background: rgba(255, 255, 255, 0.9);
        min-height: 4rem;
        padding: 0.5rem 0.7rem;
        box-shadow: 0 8px 20px rgba(13, 37, 77, 0.06);
    }

    .fi-logo {
        font-family: 'Space Grotesk', 'Manrope', sans-serif;
        font-size: 1.32rem;
        letter-spacing: -0.02em;
        color: #14396d;
        font-weight: 700;
    }

    .fi-sidebar-nav {
        padding: 0.45rem 0.9rem 1rem;
        scrollbar-width: none;
        -ms-overflow-style: none;
        overscroll-behavior: contain;
    }

    .fi-sidebar-nav::-webkit-scrollbar {
        width: 0;
        height: 0;
    }

    .fi-sidebar-group {
        margin-bottom: 0.42rem;
    }

    .fi-sidebar-group-label {
        font-size: 0.72rem;
        color: #6780a4;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    .fi-sidebar-item {
        margin-bottom: 0.3rem;
    }

    .fi-sidebar-item-btn {
        min-height: 2.6rem;
        padding-inline: 0.72rem;
        border-radius: 0.9rem;
        border: 1px solid transparent;
        color: #26456f;
        transition: transform 180ms ease, border-color 180ms ease, background-color 180ms ease, box-shadow 180ms ease;
    }

    .fi-sidebar-item-btn:hover {
        border-color: #d8e4f4;
        background: rgba(255, 255, 255, 0.9);
        transform: translateX(2px);
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
        color: #fff;
        border-color: transparent;
        background: linear-gradient(130deg, var(--sr-primary) 0%, #4f85ff 100%);
        box-shadow: 0 12px 24px rgba(47, 110, 248, 0.33);
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label,
    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon {
        color: #fff;
    }

    .fi-main-ctn {
        padding: 0.95rem 1.05rem 1.2rem;
    }

    .fi-topbar-ctn {
        padding: 0.9rem 1.05rem 0;
    }

    .fi-topbar {
        border-radius: 1rem;
        border: 1px solid var(--sr-border);
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 10px 26px rgba(18, 39, 73, 0.07);
        backdrop-filter: blur(8px);
        min-height: 4rem;
        padding-inline: 0.9rem;
    }

    .fi-topbar-start {
        min-width: 0;
    }

    .fi-topbar-end {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.55rem;
        flex-wrap: wrap;
    }

    .fi-topbar .fi-icon-btn {
        border-radius: 0.7rem;
    }

    .fi-page {
        gap: 0.95rem;
    }

    .fi-header {
        border: 1px solid var(--sr-border);
        border-radius: 1.05rem;
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 10px 24px rgba(17, 36, 68, 0.06);
        padding: 0.9rem 1rem;
    }

    .fi-header-heading {
        font-family: 'Space Grotesk', 'Manrope', sans-serif;
        font-size: clamp(1.75rem, 2.1vw, 2.2rem);
        letter-spacing: -0.02em;
        color: #163763;
    }

    .fi-header-subheading {
        margin-top: 0.18rem;
        color: var(--sr-muted);
    }

    .fi-section {
        border: 1px solid var(--sr-border);
        border-radius: 1rem;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 12px 30px rgba(16, 34, 64, 0.05);
    }

    .fi-section-header {
        border-bottom: 1px solid #e6edf8;
        padding: 0.88rem 1rem;
    }

    .fi-section-content {
        padding: 1rem;
    }

    .fi-section-header-heading {
        color: #163763;
        font-weight: 800;
    }

    .fi-section-header-description {
        color: var(--sr-muted);
    }

    .fi-wi-stats-overview {
        gap: 0.85rem;
    }

    .fi-wi-stats-overview-stat {
        border: 1px solid var(--sr-border);
        border-radius: 0.95rem;
        padding: 0.92rem;
        background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.95);
    }

    .fi-wi-stats-overview-stat-label {
        color: #6c83a4;
        font-weight: 700;
    }

    .fi-wi-stats-overview-stat-value {
        color: #102f5a;
        font-size: 2.05rem;
        font-weight: 800;
        line-height: 1;
    }

    .fi-wi-stats-overview-stat-description {
        font-size: 0.78rem;
    }

    .fi-wi-chart .fi-section-content,
    .fi-wi-table .fi-section-content {
        padding: 0.95rem;
    }

    .fi-wi-table .fi-section-content {
        overflow-x: auto;
    }

    .fi-ta {
        border-radius: 0.92rem;
        overflow: hidden;
        border: 1px solid #e2eaf7;
    }

    .fi-ta-header {
        background: #f7faff;
    }

    .fi-ta-row {
        background: #fff;
    }

    .fi-ta-row:nth-child(even) {
        background: #fbfdff;
    }

    .fi-ta-cell,
    .fi-ta-header-cell {
        border-color: #e7edf8;
    }

    .fi-ta-content-ctn {
        overflow-x: auto;
    }

    .fi-ta-table {
        min-width: 760px;
    }

    .fi-badge {
        border-radius: 999px;
    }

    .sr-topbar-locale {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.22rem;
        border-radius: 999px;
        border: 1px solid var(--sr-border);
        background: #f8fbff;
        margin-right: 0.5rem;
        max-width: 100%;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .sr-topbar-locale::-webkit-scrollbar {
        width: 0;
        height: 0;
    }

    .sr-topbar-locale-link {
        text-decoration: none;
        padding: 0.34rem 0.58rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #5f779b;
        transition: color 160ms ease, background-color 160ms ease;
    }

    .sr-topbar-locale-link:hover {
        color: #1a3f73;
        background: #e9f1ff;
    }

    .sr-topbar-locale-link.is-active {
        color: #fff;
        background: linear-gradient(120deg, var(--sr-primary) 0%, #4d86ff 100%);
        box-shadow: 0 8px 16px rgba(47, 110, 248, 0.26);
    }

    .sr-hero {
        border: 1px solid var(--sr-border);
        border-radius: 1.05rem;
        background:
            radial-gradient(circle at 0% 0%, rgba(47, 110, 248, 0.11), transparent 38%),
            radial-gradient(circle at 100% 4%, rgba(23, 166, 114, 0.1), transparent 34%),
            #ffffff;
        padding: 1rem;
        box-shadow: var(--sr-shadow);
        animation: sr-raise 360ms ease;
    }

    .sr-hero-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.9rem;
    }

    .sr-hero-title {
        margin: 0;
        font-family: 'Space Grotesk', 'Manrope', sans-serif;
        font-size: clamp(2rem, 2.4vw, 2.5rem);
        letter-spacing: -0.025em;
        color: #15396b;
        line-height: 1.05;
    }

    .sr-hero-subtitle {
        margin: 0.35rem 0 0;
        color: #5f7598;
        font-size: 0.95rem;
    }

    .sr-hero-meta {
        display: flex;
        flex-direction: column;
        align-items: end;
        gap: 0.42rem;
    }

    .sr-chip {
        border-radius: 999px;
        border: 1px solid #d7e3f5;
        background: rgba(255, 255, 255, 0.95);
        padding: 0.36rem 0.65rem;
        font-size: 0.68rem;
        font-weight: 800;
        color: #5e7698;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .sr-chip-live {
        color: #ffffff;
        border-color: transparent;
        background: linear-gradient(140deg, var(--sr-primary) 0%, var(--sr-primary-strong) 100%);
        box-shadow: 0 10px 18px rgba(36, 87, 209, 0.24);
    }

    .sr-hero-grid {
        margin-top: 0.85rem;
        display: grid;
        gap: 0.85rem;
        grid-template-columns: 1.55fr 0.95fr;
    }

    .sr-panel {
        border-radius: 0.95rem;
        border: 1px solid #dde7f6;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        padding: 0.82rem;
    }

    .sr-stat-grid {
        margin-top: 0.8rem;
        display: grid;
        gap: 0.6rem;
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .sr-stat {
        border: 1px solid #dce6f5;
        border-radius: 0.88rem;
        padding: 0.72rem;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    }

    .sr-stat-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #647d9f;
    }

    .sr-stat-value {
        margin-top: 0.42rem;
        color: #113362;
        font-size: 1.92rem;
        font-weight: 800;
        line-height: 1;
    }

    .sr-stat-value.is-danger {
        color: #d23b49;
    }

    .sr-viz-grid {
        margin-top: 0.72rem;
        display: grid;
        gap: 0.58rem;
        grid-template-columns: 1.3fr 0.7fr;
    }

    .sr-bars {
        display: flex;
        align-items: flex-end;
        gap: 0.35rem;
        height: 122px;
        padding-top: 0.4rem;
    }

    .sr-bars span {
        flex: 1;
        border-radius: 0.42rem 0.42rem 0.18rem 0.18rem;
        background: linear-gradient(180deg, rgba(47, 110, 248, 0.92), rgba(47, 110, 248, 0.45));
    }

    .sr-bars span:nth-child(odd) {
        background: linear-gradient(180deg, rgba(143, 164, 204, 0.9), rgba(143, 164, 204, 0.46));
    }

    .sr-month-labels {
        gap: 0.3rem;
    }

    .sr-month-label {
        flex: 1;
        text-align: center;
    }

    .sr-dept-row {
        display: grid;
        gap: 0.45rem;
        grid-template-columns: 74px 1fr auto;
        align-items: center;
        font-size: 0.72rem;
        color: #5f789d;
    }

    .sr-progress {
        height: 8px;
        border-radius: 999px;
        background: #dde6f4;
        overflow: hidden;
    }

    .sr-progress > span {
        display: block;
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, var(--sr-primary), #35b88e);
    }

    .sr-side {
        border-radius: 1rem;
        border: 1px solid #dce6f5;
        background:
            linear-gradient(150deg, rgba(47, 110, 248, 0.08), transparent 42%),
            #f8fbff;
        padding: 1rem 1.06rem;
        display: flex;
        flex-direction: column;
        min-height: 100%;
    }

    .sr-side-title {
        margin: 0;
        font-family: 'Space Grotesk', 'Manrope', sans-serif;
        font-size: clamp(1.38rem, 1.7vw, 1.8rem);
        letter-spacing: -0.02em;
        color: #163a6b;
    }

    .sr-side-head {
        margin-bottom: 0.82rem;
    }

    .sr-side-subtitle {
        margin: 0.28rem 0 0;
        color: #6580a8;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .sr-doctor-kpis {
        display: grid;
        gap: 0.52rem;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        margin-bottom: 0.8rem;
    }

    .sr-doctor-kpi {
        border: 1px solid #dbe7f7;
        border-radius: 0.82rem;
        background: linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
        padding: 0.58rem 0.62rem;
    }

    .sr-doctor-kpi-label {
        margin: 0;
        color: #6580a8;
        font-size: 0.69rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.01em;
    }

    .sr-doctor-kpi-value {
        margin: 0.25rem 0 0;
        font-size: 1.16rem;
        line-height: 1;
        font-weight: 800;
        color: #173c6e;
    }

    .sr-tab-list {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.42rem;
        margin-bottom: 0.65rem;
    }

    .sr-tab-btn {
        border: 1px solid #d7e4f5;
        border-radius: 999px;
        background: #f5f9ff;
        color: #5f7da6;
        font-size: 0.71rem;
        font-weight: 800;
        padding: 0.34rem 0.2rem;
        transition: background-color 150ms ease, color 150ms ease, border-color 150ms ease, transform 150ms ease;
    }

    .sr-tab-btn:hover {
        color: #1e4478;
        border-color: #c4d7f1;
        transform: translateY(-1px);
    }

    .sr-tab-btn.is-active {
        color: #fff;
        border-color: transparent;
        background: linear-gradient(130deg, var(--sr-primary) 0%, #4e87ff 100%);
        box-shadow: 0 8px 18px rgba(47, 110, 248, 0.28);
    }

    .sr-doctor-list {
        display: grid;
        gap: 0.5rem;
    }

    .sr-doctor-row {
        border: 1px solid #dce7f7;
        border-radius: 0.85rem;
        background: rgba(255, 255, 255, 0.86);
        padding: 0.62rem 0.66rem;
    }

    .sr-doctor-main {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 0.6rem;
    }

    .sr-doctor-name {
        margin: 0;
        color: #173d6f;
        font-size: 0.88rem;
        font-weight: 800;
        line-height: 1.2;
        overflow-wrap: anywhere;
    }

    .sr-doctor-specialty {
        margin: 0;
        color: #6883aa;
        font-size: 0.73rem;
        text-align: end;
    }

    .sr-doctor-metrics {
        margin-top: 0.42rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.42rem;
    }

    .sr-doctor-metrics span {
        border-radius: 999px;
        border: 1px solid #d9e5f6;
        background: #f6faff;
        padding: 0.18rem 0.46rem;
        color: #486a97;
        font-size: 0.69rem;
        font-weight: 700;
    }

    .sr-doctor-flags {
        margin-top: 0.42rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }

    .sr-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        border: 1px solid #d7e3f4;
        background: #f4f8ff;
        padding: 0.2rem 0.48rem;
        font-size: 0.68rem;
        font-weight: 800;
        color: #4b6f9f;
    }

    .sr-badge.is-positive {
        border-color: rgba(18, 159, 108, 0.35);
        background: rgba(24, 167, 116, 0.12);
        color: #0f8d63;
    }

    .sr-badge.is-negative {
        border-color: rgba(215, 76, 96, 0.35);
        background: rgba(215, 76, 96, 0.1);
        color: #c2394f;
    }

    .sr-doctor-empty {
        border: 1px dashed #c8d9ef;
        border-radius: 0.8rem;
        padding: 0.8rem;
        margin: 0;
        text-align: center;
        color: #6380a9;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .sr-chart-tabs {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.36rem;
        margin-bottom: 0.65rem;
    }

    .sr-chart-panel {
        display: grid;
        gap: 0.42rem;
        flex: 1;
        overflow-y: auto;
    }

    .sr-hbar-row {
        display: grid;
        grid-template-columns: 5.5rem 1fr 2.8rem;
        align-items: center;
        gap: 0.5rem;
    }

    .sr-hbar-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #3d5a85;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sr-hbar-track {
        height: 1.2rem;
        background: #edf3fc;
        border-radius: 0.6rem;
        overflow: hidden;
        position: relative;
    }

    .sr-hbar-fill {
        height: 100%;
        border-radius: 0.6rem;
        transition: width 600ms cubic-bezier(0.22, 1, 0.36, 1);
    }

    .sr-hbar-blue {
        background: linear-gradient(90deg, #2f6ef8, #5a93ff);
    }

    .sr-hbar-teal {
        background: linear-gradient(90deg, #12a06e, #35c98e);
    }

    .sr-hbar-green {
        background: linear-gradient(90deg, #22b573, #49d89a);
    }

    .sr-hbar-orange {
        background: linear-gradient(90deg, #e69a2e, #f0b94d);
    }

    .sr-hbar-red {
        background: linear-gradient(90deg, #d74c60, #e86b7d);
    }

    .sr-hbar-value {
        font-size: 0.74rem;
        font-weight: 800;
        color: #173c6e;
        text-align: right;
    }

    .sr-trend-row {
        display: grid;
        grid-template-columns: 5.5rem 1fr 3rem;
        align-items: center;
        gap: 0.5rem;
    }

    .sr-trend-track {
        height: 1.2rem;
        background: #edf3fc;
        border-radius: 0.6rem;
        position: relative;
        overflow: hidden;
    }

    .sr-trend-center {
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 1px;
        background: #b4c8e4;
    }

    .sr-trend-bar {
        position: absolute;
        top: 2px;
        bottom: 2px;
        border-radius: 0.4rem;
        transition: width 600ms cubic-bezier(0.22, 1, 0.36, 1);
    }

    .sr-trend-positive {
        background: linear-gradient(90deg, #22b573, #49d89a);
    }

    .sr-trend-negative {
        background: linear-gradient(270deg, #d74c60, #e86b7d);
    }

    .is-positive-text {
        color: #0f8d63 !important;
    }

    .is-negative-text {
        color: #c2394f !important;
    }

    .fi-wi-widget {
        animation: sr-fade-in 300ms ease;
    }

    @keyframes sr-fade-in {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes sr-raise {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 1200px) {
        .sr-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .sr-doctor-kpis {
            grid-template-columns: 1fr;
        }

        .sr-chart-tabs {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 1024px) {
        .fi-sidebar.fi-main-sidebar {
            max-width: min(90vw, 18.5rem);
        }

        .fi-main-ctn {
            padding: 0.7rem 0.7rem 1rem;
        }

        .fi-topbar-ctn {
            padding: 0.7rem 0.7rem 0;
        }

        .fi-topbar-end {
            gap: 0.45rem;
        }

        .fi-header {
            padding: 0.75rem 0.82rem;
        }

        .fi-section-content {
            padding: 0.82rem;
        }

        .sr-hero-grid {
            grid-template-columns: 1fr;
        }

        .sr-viz-grid {
            grid-template-columns: 1fr;
        }

        .sr-hero-meta {
            align-items: flex-start;
        }

        .fi-ta-table {
            min-width: 690px;
        }
    }

    @media (max-width: 760px) {
        .fi-topbar {
            min-height: auto;
            padding-inline: 0.68rem;
            padding-top: 0.55rem;
            padding-bottom: 0.55rem;
            row-gap: 0.45rem;
            flex-wrap: wrap;
        }

        .fi-topbar-start {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.45rem;
        }

        .fi-topbar-start .fi-logo {
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .fi-topbar-end {
            width: 100%;
            justify-content: space-between;
        }

        .fi-topbar-end > * {
            min-width: 0;
        }

        .fi-header {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.55rem;
        }

        .fi-header-heading {
            font-size: clamp(1.38rem, 8vw, 1.75rem);
        }

        .fi-header-actions-ctn {
            width: 100%;
        }

        .fi-section-header {
            padding: 0.74rem 0.8rem;
        }

        .fi-section-content {
            padding: 0.74rem;
        }

        .fi-wi-chart .fi-section-content {
            overflow-x: auto;
        }

        .fi-wi-stats-overview {
            grid-template-columns: 1fr !important;
        }

        .fi-wi-chart-canvas-ctn {
            min-width: 620px;
        }

        .fi-ta-table {
            min-width: 620px;
        }

        .sr-topbar-locale {
            margin-right: 0;
            padding: 0.2rem;
            width: 100%;
        }

        .sr-topbar-locale-link {
            font-size: 0.67rem;
            padding: 0.28rem 0.45rem;
        }

        .sr-hero {
            padding: 0.82rem;
        }

        .sr-panel {
            padding: 0.72rem;
        }

        .sr-hero-head {
            flex-direction: column;
        }

        .sr-hero-meta {
            width: 100%;
            flex-direction: row;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.35rem;
        }

        .sr-stat-grid {
            grid-template-columns: 1fr;
        }

        .sr-hero-title {
            font-size: clamp(1.62rem, 8vw, 2rem);
        }

        .sr-tab-list {
            grid-template-columns: 1fr;
        }

        .sr-month-label:nth-child(even) {
            display: none;
        }

        .sr-dept-row {
            grid-template-columns: minmax(0, 1fr);
            gap: 0.28rem;
        }

        .sr-dept-row > .sr-progress {
            width: 100%;
        }

        .sr-dept-row > span:last-child {
            justify-self: start;
        }

        .sr-doctor-main {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.24rem;
        }

        .sr-doctor-specialty {
            text-align: start;
        }

        .sr-doctor-metrics span,
        .sr-badge {
            max-width: 100%;
            overflow-wrap: anywhere;
        }
    }

    @media (max-width: 480px) {
        .fi-main-ctn {
            padding: 0.55rem 0.5rem 0.75rem;
        }

        .fi-topbar-ctn {
            padding: 0.55rem 0.5rem 0;
        }

        .fi-wi-chart-canvas-ctn {
            min-width: 520px;
        }

        .fi-ta-table {
            min-width: 560px;
        }

        .sr-topbar-locale-link {
            font-size: 0.64rem;
            padding: 0.26rem 0.42rem;
        }

        .sr-chip {
            font-size: 0.62rem;
            padding: 0.3rem 0.52rem;
        }

        .sr-hero-subtitle {
            font-size: 0.82rem;
        }

        .sr-stat-label {
            font-size: 0.69rem;
        }

        .sr-stat-value {
            font-size: 1.6rem;
        }

        .sr-doctor-kpi-value {
            font-size: 1.02rem;
        }
    }

    .dark .fi-body {
        --sr-bg: #0d1626;
        --sr-surface: #121f35;
        --sr-surface-soft: #162640;
        --sr-border: #25344c;
        --sr-text: #dce9fb;
        --sr-muted: #a4b7d3;
        background:
            radial-gradient(circle at 8% 0%, rgba(47, 110, 248, 0.2), transparent 34%),
            radial-gradient(circle at 100% 5%, rgba(23, 166, 114, 0.18), transparent 30%),
            #0d1626;
    }

    .dark .fi-sidebar.fi-main-sidebar,
    .dark .fi-sidebar-header,
    .dark .fi-topbar,
    .dark .fi-header,
    .dark .fi-section,
    .dark .fi-wi-stats-overview-stat,
    .dark .sr-hero,
    .dark .sr-panel,
    .dark .sr-stat,
    .dark .sr-side {
        border-color: #26364f;
        color: #dce9fb;
    }

    .dark .fi-sidebar.fi-main-sidebar {
        background:
            radial-gradient(circle at 0% 0%, rgba(47, 110, 248, 0.22), transparent 32%),
            linear-gradient(180deg, #111c30 0%, #0f1a2e 100%);
    }

    .dark .fi-sidebar-header,
    .dark .fi-topbar,
    .dark .fi-header,
    .dark .fi-section,
    .dark .fi-wi-stats-overview-stat,
    .dark .sr-hero,
    .dark .sr-panel,
    .dark .sr-stat,
    .dark .sr-side,
    .dark .fi-ta-header,
    .dark .fi-ta-row,
    .dark .fi-ta-row:nth-child(even) {
        background: #111e33;
    }

    .dark .fi-sidebar-item-btn:hover {
        background: #15253e;
        border-color: #2e4465;
    }

    .dark .fi-sidebar-item-btn,
    .dark .fi-sidebar-group-label,
    .dark .fi-header-subheading,
    .dark .fi-section-header-description,
    .dark .fi-wi-stats-overview-stat-label,
    .dark .sr-hero-subtitle,
    .dark .sr-stat-label,
    .dark .sr-dept-row,
    .dark .sr-chip,
    .dark .sr-topbar-locale-link,
    .dark .fi-logo {
        color: #b7c9e2;
    }

    .dark .fi-header-heading,
    .dark .fi-section-header-heading,
    .dark .fi-wi-stats-overview-stat-value,
    .dark .sr-hero-title,
    .dark .sr-stat-value,
    .dark .sr-side-title {
        color: #e7f0ff;
    }

    .dark .sr-side-subtitle,
    .dark .sr-doctor-kpi-label,
    .dark .sr-doctor-specialty,
    .dark .sr-doctor-empty {
        color: #a9bfdc;
    }

    .dark .sr-doctor-kpi,
    .dark .sr-doctor-row,
    .dark .sr-doctor-metrics span,
    .dark .sr-badge,
    .dark .sr-tab-btn,
    .dark .sr-doctor-empty {
        border-color: #2e4465;
        background: #182842;
        color: #bcd0ea;
    }

    .dark .sr-doctor-name,
    .dark .sr-doctor-kpi-value {
        color: #e5efff;
    }

    .dark .sr-tab-btn.is-active {
        border-color: transparent;
        color: #fff;
        background: linear-gradient(130deg, var(--sr-primary) 0%, #4e87ff 100%);
    }

    .dark .sr-badge.is-positive {
        border-color: rgba(45, 188, 134, 0.45);
        background: rgba(45, 188, 134, 0.16);
        color: #62e1b4;
    }

    .dark .sr-badge.is-negative {
        border-color: rgba(239, 109, 132, 0.48);
        background: rgba(239, 109, 132, 0.14);
        color: #ff9daf;
    }

    .dark .sr-hbar-track,
    .dark .sr-trend-track {
        background: #1b2e4a;
    }

    .dark .sr-hbar-label {
        color: #b7c9e2;
    }

    .dark .sr-hbar-value {
        color: #e5efff;
    }

    .dark .sr-trend-center {
        background: #3a5478;
    }

    .dark .sr-topbar-locale {
        border-color: #2d3f5e;
        background: #16253f;
    }

    .dark .sr-topbar-locale-link:hover {
        background: #1f3559;
        color: #deebff;
    }

    .dark .sr-chip {
        background: #162741;
        border-color: #304664;
    }

    .dark .sr-progress {
        background: #243854;
    }

    .dark .fi-ta {
        border-color: #2b3d5b;
    }

    .dark .fi-ta-cell,
    .dark .fi-ta-header-cell {
        border-color: #2b3d5b;
    }
</style>
