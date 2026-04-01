@php
    $currentLocale = app()->getLocale();
    $availableLocales = array_keys(config('shiforeyting.supported_locales', []));
@endphp

<div class="sr-topbar-locale">
    @foreach ($availableLocales as $localeCode)
        @php
            $labelKey = 'ui.locale.'.$localeCode;
            $label = __($labelKey);
            if ($label === $labelKey) {
                $label = strtoupper($localeCode);
            }
        @endphp
        <a
            class="sr-topbar-locale-link {{ $currentLocale === $localeCode ? 'is-active' : '' }}"
            href="{{ request()->fullUrlWithQuery(['lang' => $localeCode]) }}"
        >
            {{ $label }}
        </a>
    @endforeach
</div>
