<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ShifoReyting AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
@php
    $locale = app()->getLocale();
    $pickText = function (mixed $value, string $fallback = '') use ($locale): string {
        if (is_array($value)) {
            return (string) ($value[$locale] ?? $value['uz_latn'] ?? $value['ru'] ?? $value['en'] ?? collect($value)->first() ?? $fallback);
        }

        return (string) ($value ?? $fallback);
    };
    $formAction = $qrCode
        ? route('survey.submit', ['token' => $qrCode->token])
        : route('survey.submit-shortlink', ['slug' => $survey->slug]);
@endphp

<div class="mx-auto max-w-2xl px-3 py-4 sm:px-4 sm:py-6">
    <div class="mb-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="mb-2 flex flex-wrap items-start justify-between gap-2">
            <h1 class="text-lg font-semibold">ShifoReyting AI</h1>
            <span id="timer" class="shrink-0 rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">00:45</span>
        </div>
        <p class="text-sm text-slate-600">{{ $pickText($survey->title, 'Fikr-mulohaza') }}</p>
        @if($qrCode?->doctor)
            <p class="mt-2 text-xs text-slate-500">
                {{ $qrCode->doctor->full_name }} · {{ $qrCode->doctor->specialty }}
            </p>
        @endif
        <div class="mt-3 flex flex-wrap gap-2">
            @foreach($supportedLocales as $code => $label)
                <a
                    href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}"
                    class="rounded-full border px-2.5 py-1 text-xs {{ app()->getLocale() === $code ? 'border-emerald-600 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <form action="{{ $formAction }}" method="post" class="space-y-4 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm sm:p-4">
        @csrf
        <input type="hidden" name="language" value="{{ app()->getLocale() }}">
        <input type="hidden" name="channel" value="{{ $sourceChannel }}">

        @foreach($survey->questions as $question)
            <div class="rounded-xl border border-slate-200 p-3 sm:p-4">
                <label class="mb-2 block text-sm font-medium">
                    {{ $pickText($question->title) }}
                    @if($question->is_required)<span class="text-rose-500">*</span>@endif
                </label>

                @if($question->type === 'rating')
                    <div class="grid grid-cols-5 gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer rounded-lg border border-slate-300 px-2 py-2 text-center text-sm hover:bg-slate-50">
                                <input type="radio" class="sr-only" name="answers[{{ $question->key }}]" value="{{ $i }}" {{ $question->is_required ? 'required' : '' }}>
                                {{ $i }}
                            </label>
                        @endfor
                    </div>
                @elseif(in_array($question->type, ['yes_no', 'recommend'], true))
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                        <label class="cursor-pointer rounded-lg border border-slate-300 px-3 py-2 text-center text-sm hover:bg-slate-50">
                            <input type="radio" class="sr-only" name="answers[{{ $question->key }}]" value="1" {{ $question->is_required ? 'required' : '' }}>
                            {{ __('survey.yes') }}
                        </label>
                        <label class="cursor-pointer rounded-lg border border-slate-300 px-3 py-2 text-center text-sm hover:bg-slate-50">
                            <input type="radio" class="sr-only" name="answers[{{ $question->key }}]" value="0" {{ $question->is_required ? 'required' : '' }}>
                            {{ __('survey.no') }}
                        </label>
                    </div>
                @elseif($question->type === 'single_choice')
                    <div class="space-y-2">
                        @foreach($question->options as $option)
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-300 px-3 py-2 text-sm hover:bg-slate-50">
                                <input type="radio" name="answers[{{ $question->key }}]" value="{{ $option->value }}" {{ $question->is_required ? 'required' : '' }}>
                                <span>{{ $pickText($option->label) }}</span>
                            </label>
                        @endforeach
                    </div>
                @elseif($question->type === 'nps')
                    <div class="overflow-x-auto">
                        <div class="grid min-w-[20rem] grid-cols-11 gap-1 sm:min-w-0">
                            @for($i = 0; $i <= 10; $i++)
                                <label class="cursor-pointer rounded border border-slate-300 py-2 text-center text-xs hover:bg-slate-50">
                                    <input type="radio" class="sr-only" name="answers[{{ $question->key }}]" value="{{ $i }}" {{ $question->is_required ? 'required' : '' }}>
                                    {{ $i }}
                                </label>
                            @endfor
                        </div>
                    </div>
                @elseif($question->type === 'severity')
                    <select name="answers[{{ $question->key }}]" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" {{ $question->is_required ? 'required' : '' }}>
                        <option value="">--</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                @elseif($question->type === 'comment')
                    <textarea
                        name="answers[{{ $question->key }}]"
                        rows="3"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                        placeholder="{{ __('survey.comment_placeholder') }}"
                    ></textarea>
                @endif
            </div>
        @endforeach

        <div class="rounded-xl border border-slate-200 p-3">
            <label class="inline-flex items-center gap-2 text-sm">
                <input id="callback_requested" type="checkbox" name="callback_requested" value="1">
                {{ __('survey.callback_request') }}
            </label>
            <input
                id="callback_contact"
                type="text"
                name="callback_contact"
                class="mt-2 hidden w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                placeholder="{{ __('survey.callback_contact_placeholder') }}"
            >
        </div>

        <button id="submit_btn" type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
            {{ __('survey.submit') }}
        </button>
    </form>
</div>

<script>
    const callback = document.getElementById('callback_requested');
    const callbackInput = document.getElementById('callback_contact');
    const timerEl = document.getElementById('timer');
    const submitBtn = document.getElementById('submit_btn');

    callback.addEventListener('change', () => {
        callbackInput.classList.toggle('hidden', !callback.checked);
        if (callback.checked) callbackInput.focus();
    });

    let remaining = 45;
    const interval = setInterval(() => {
        remaining--;
        const mm = String(Math.floor(remaining / 60)).padStart(2, '0');
        const ss = String(remaining % 60).padStart(2, '0');
        timerEl.textContent = `${mm}:${ss}`;

        if (remaining <= 0) {
            clearInterval(interval);
            timerEl.classList.remove('bg-emerald-100', 'text-emerald-700');
            timerEl.classList.add('bg-amber-100', 'text-amber-700');
        }
    }, 1000);

    document.querySelector('form').addEventListener('submit', () => {
        submitBtn.setAttribute('disabled', 'disabled');
        submitBtn.classList.add('opacity-70');
    });
</script>
</body>
</html>
