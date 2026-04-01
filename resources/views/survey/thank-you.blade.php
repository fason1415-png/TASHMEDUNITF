<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('survey.thank_you') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
<div class="mx-auto flex min-h-screen max-w-md items-center px-4 py-8">
    <div class="w-full rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm">
        <h1 class="text-xl font-semibold">{{ __('survey.thank_you') }}</h1>
        <p class="mt-2 text-sm text-slate-600">{{ __('survey.thank_you_desc') }}</p>
        <a href="{{ $backUrl }}" class="mt-6 inline-block rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">
            {{ __('survey.back') }}
        </a>
    </div>
</div>
</body>
</html>

