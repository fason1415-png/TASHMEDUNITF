<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = array_keys(config('shiforeyting.supported_locales', []));
        $queryLocale = $this->normalizeLocale((string) $request->query('lang'));

        if ($queryLocale && in_array($queryLocale, $supportedLocales, true) && $request->hasSession()) {
            $request->session()->put('app_locale', $queryLocale);
        }

        $locale = $queryLocale;

        if (! $locale && $request->hasSession()) {
            $locale = $this->normalizeLocale((string) $request->session()->get('app_locale', ''));
        }

        if (! $locale && $request->user()?->preferred_language) {
            $locale = $this->normalizeLocale($request->user()->preferred_language);
        }

        if (! $locale) {
            $locale = $this->normalizeLocale($this->mapFromAcceptLanguage($request->header('Accept-Language', '')));
        }

        if (! in_array($locale, $supportedLocales, true)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }

    private function mapFromAcceptLanguage(string $header): string
    {
        $lowered = strtolower($header);

        return match (true) {
            str_contains($lowered, 'uz-cyrl'),
            str_contains($lowered, 'uz_uz@cyrillic'),
            str_contains($lowered, 'uz-uz-cyrl') => 'uz_cyrl',
            str_contains($lowered, 'uz') => 'uz_latn',
            str_contains($lowered, 'ru') => 'ru',
            default => 'en',
        };
    }

    private function normalizeLocale(string $locale): string
    {
        $normalized = strtolower(str_replace('-', '_', trim($locale)));

        return match ($normalized) {
            'uz', 'uz_uz', 'uz_latn' => 'uz_latn',
            'uz_cyrl', 'uz_uz_cyrl', 'uz_uz@cyrillic' => 'uz_cyrl',
            'ru', 'ru_ru' => 'ru',
            'en', 'en_us', 'en_gb' => 'en',
            default => $normalized,
        };
    }
}
