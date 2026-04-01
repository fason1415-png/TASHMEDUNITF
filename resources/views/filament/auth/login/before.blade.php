@php
    $currentLocale = app()->getLocale();
    $availableLocales = array_keys(config('shiforeyting.supported_locales', []));

    $loginError = $errors->first('data.email') ?: $errors->first();
@endphp

<div class="sr-login-layout">
    <aside class="sr-login-brand">
        <div class="sr-login-brand-top">
            <span class="sr-login-brand-logo">SR</span>
            <span class="sr-login-brand-badge">{{ __('ui.landing.dashboard_title') }}</span>
        </div>

        <h2 class="sr-login-brand-title">{{ __('ui.landing.hero_title') }}</h2>
        <p class="sr-login-brand-subtitle">{{ __('ui.landing.hero_subtitle') }}</p>

        <ul class="sr-login-brand-points">
            <li>{{ __('ui.landing.feature_1_title') }}</li>
            <li>{{ __('ui.landing.feature_2_title') }}</li>
            <li>{{ __('ui.landing.feature_3_title') }}</li>
        </ul>
    </aside>

    <section class="sr-login-auth">
        <div class="sr-login-locales">
            @foreach ($availableLocales as $localeCode)
                @php
                    $labelKey = 'ui.locale.'.$localeCode;
                    $label = __($labelKey);

                    if ($label === $labelKey) {
                        $label = strtoupper($localeCode);
                    }
                @endphp

                <a
                    class="sr-login-locale-link {{ $currentLocale === $localeCode ? 'is-active' : '' }}"
                    href="{{ request()->fullUrlWithQuery(['lang' => $localeCode]) }}"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <h1 class="sr-login-title">{{ __('filament-panels::auth/pages/login.heading') }}</h1>
        <p class="sr-login-subtitle">{{ __('ui.executive.subtitle') }}</p>

        @if (filled($loginError))
            <div class="sr-login-alert">{{ __('filament-panels::auth/pages/login.messages.failed') }}</div>
        @endif

        <div class="sr-login-role-list">
            <span class="sr-login-role is-active">{{ __('ui.roles.admin') }}</span>
            <span class="sr-login-role">{{ __('ui.roles.analyst') }}</span>
        </div>
