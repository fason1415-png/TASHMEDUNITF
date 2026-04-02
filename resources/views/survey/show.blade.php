<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ShifoReyting — So'rovnoma</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f766e 0%, #115e59 40%, #134e4a 100%);
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 480px;
            margin: 0 auto;
            padding: 16px 12px 40px;
        }

        /* Header Card */
        .header-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 24px 20px;
            margin-bottom: 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .header-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }
        .header-logo svg {
            width: 36px;
            height: 36px;
        }
        .header-logo-text {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.3px;
        }
        .header-title {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            line-height: 1.5;
        }
        .header-doctor {
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 10px 14px;
        }
        .doctor-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .doctor-avatar svg { width: 22px; height: 22px; }
        .doctor-name {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
        }
        .doctor-specialty {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 1px;
        }

        /* Language switcher */
        .lang-switcher {
            display: flex;
            gap: 6px;
            margin-top: 14px;
            flex-wrap: wrap;
        }
        .lang-btn {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: rgba(255, 255, 255, 0.8);
        }
        .lang-btn:hover { background: rgba(255, 255, 255, 0.15); }
        .lang-btn.active {
            background: #fff;
            color: #0f766e;
            border-color: #fff;
            font-weight: 600;
        }

        /* Timer */
        .timer-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            float: right;
        }
        .timer-badge svg { width: 14px; height: 14px; }

        /* Form Card */
        .form-card {
            background: #fff;
            border-radius: 24px;
            padding: 6px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        .form-inner {
            padding: 20px 16px;
        }

        /* Progress bar */
        .progress-container {
            padding: 16px 16px 0;
        }
        .progress-bar {
            height: 4px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #0d9488, #14b8a6);
            border-radius: 4px;
            transition: width 0.4s ease;
            width: 0%;
        }
        .progress-text {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            font-size: 12px;
            color: #94a3b8;
            font-weight: 500;
        }

        /* Question */
        .question-block {
            padding: 20px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .question-block:last-of-type { border-bottom: none; }
        .question-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            background: linear-gradient(135deg, #0d9488, #14b8a6);
            color: #fff;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .question-title {
            font-size: 15px;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.4;
            margin-bottom: 4px;
        }
        .question-desc {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 14px;
        }
        .required-dot {
            color: #ef4444;
            margin-left: 2px;
        }

        /* Star Rating */
        .star-rating {
            display: flex;
            gap: 8px;
            justify-content: center;
            padding: 8px 0;
        }
        .star-rating label {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .star-rating label:hover { transform: scale(1.15); }
        .star-rating input { display: none; }
        .star-rating svg {
            width: 44px;
            height: 44px;
            fill: #e2e8f0;
            stroke: #cbd5e1;
            stroke-width: 0.5;
            transition: all 0.2s;
        }
        .star-rating label.active svg,
        .star-rating label:hover svg,
        .star-rating label:hover ~ label svg {
            /* JS handles active states */
        }
        .star-label {
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            margin-top: 6px;
            min-height: 20px;
            font-weight: 500;
            transition: color 0.2s;
        }

        /* Yes/No & Recommend */
        .choice-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .choice-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 18px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.25s;
            background: #fff;
        }
        .choice-btn:hover { border-color: #99f6e4; background: #f0fdfa; }
        .choice-btn.selected {
            border-color: #14b8a6;
            background: #f0fdfa;
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.15);
        }
        .choice-btn input { display: none; }
        .choice-icon { font-size: 28px; }
        .choice-text { font-size: 14px; font-weight: 600; color: #334155; }
        .choice-btn.selected .choice-text { color: #0f766e; }

        /* Comment */
        .comment-area {
            width: 100%;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 14px 16px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            min-height: 100px;
            transition: border-color 0.2s;
            outline: none;
            color: #334155;
        }
        .comment-area::placeholder { color: #cbd5e1; }
        .comment-area:focus { border-color: #14b8a6; box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1); }

        /* Callback */
        .callback-section {
            margin-top: 8px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }
        .callback-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #475569;
        }
        .callback-checkbox {
            width: 20px;
            height: 20px;
            accent-color: #0d9488;
            cursor: pointer;
        }
        .callback-input {
            width: 100%;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 14px;
            font-family: inherit;
            margin-top: 12px;
            display: none;
            outline: none;
            transition: border-color 0.2s;
        }
        .callback-input:focus { border-color: #14b8a6; }
        .callback-input.visible { display: block; }

        /* Submit button */
        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #0d9488, #0f766e);
            color: #fff;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 8px;
            font-family: inherit;
            letter-spacing: 0.3px;
            position: relative;
            overflow: hidden;
        }
        .submit-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 25px rgba(13, 148, 136, 0.4); }
        .submit-btn:active { transform: translateY(0); }
        .submit-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }

        /* Severity */
        .severity-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
        }
        .severity-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 12px 4px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            background: #fff;
        }
        .severity-btn:hover { border-color: #99f6e4; }
        .severity-btn.selected { border-color: #14b8a6; background: #f0fdfa; box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.15); }
        .severity-btn input { display: none; }
        .severity-emoji { font-size: 24px; }
        .severity-text { font-size: 10px; color: #64748b; font-weight: 500; text-align: center; }

        /* NPS */
        .nps-grid {
            display: grid;
            grid-template-columns: repeat(11, 1fr);
            gap: 4px;
        }
        .nps-btn {
            padding: 10px 2px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            background: #fff;
            color: #64748b;
        }
        .nps-btn:hover { border-color: #99f6e4; }
        .nps-btn.selected { border-color: #14b8a6; background: #0d9488; color: #fff; }
        .nps-btn input { display: none; }

        /* Single choice */
        .option-list { display: flex; flex-direction: column; gap: 8px; }
        .option-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s;
            background: #fff;
        }
        .option-btn:hover { border-color: #99f6e4; background: #f0fdfa; }
        .option-btn.selected { border-color: #14b8a6; background: #f0fdfa; box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.15); }
        .option-btn input { display: none; }
        .option-radio {
            width: 20px;
            height: 20px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            flex-shrink: 0;
            transition: all 0.2s;
            position: relative;
        }
        .option-btn.selected .option-radio {
            border-color: #0d9488;
            background: #0d9488;
        }
        .option-btn.selected .option-radio::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 8px;
            height: 8px;
            background: #fff;
            border-radius: 50%;
        }
        .option-text { font-size: 14px; font-weight: 500; color: #334155; }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .question-block {
            animation: fadeInUp 0.4s ease forwards;
        }
        .question-block:nth-child(2) { animation-delay: 0.05s; }
        .question-block:nth-child(3) { animation-delay: 0.1s; }
        .question-block:nth-child(4) { animation-delay: 0.15s; }
        .question-block:nth-child(5) { animation-delay: 0.2s; }
        .question-block:nth-child(6) { animation-delay: 0.25s; }
        .question-block:nth-child(7) { animation-delay: 0.3s; }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 12px;
        }

        @media (max-width: 400px) {
            .star-rating svg { width: 36px; height: 36px; }
            .star-rating { gap: 6px; }
            .nps-grid { gap: 2px; }
            .nps-btn { padding: 8px 1px; font-size: 11px; }
        }
    </style>
</head>
<body>
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

    $starLabels = [
        'uz_latn' => ['', 'Yomon', 'Qoniqarsiz', 'O\'rtacha', 'Yaxshi', 'A\'lo'],
        'uz_cyrl' => ['', 'Ёмон', 'Қониқарсиз', 'Ўртача', 'Яхши', 'Аъло'],
        'ru' => ['', 'Плохо', 'Неудовл.', 'Средне', 'Хорошо', 'Отлично'],
        'en' => ['', 'Poor', 'Fair', 'Average', 'Good', 'Excellent'],
    ];
    $labels = $starLabels[$locale] ?? $starLabels['uz_latn'];

    $yesText = ['uz_latn' => 'Ha', 'uz_cyrl' => 'Ҳа', 'ru' => 'Да', 'en' => 'Yes'][$locale] ?? 'Ha';
    $noText = ['uz_latn' => 'Yo\'q', 'uz_cyrl' => 'Йўқ', 'ru' => 'Нет', 'en' => 'No'][$locale] ?? 'Yo\'q';
    $submitText = ['uz_latn' => 'Yuborish', 'uz_cyrl' => 'Юбориш', 'ru' => 'Отправить', 'en' => 'Submit'][$locale] ?? 'Yuborish';
    $callbackText = ['uz_latn' => 'Menga qayta aloqa qiling', 'uz_cyrl' => 'Менга қайта алоқа қилинг', 'ru' => 'Свяжитесь со мной', 'en' => 'Contact me back'][$locale] ?? 'Menga qayta aloqa qiling';
    $callbackPlaceholder = ['uz_latn' => 'Telefon raqamingiz', 'uz_cyrl' => 'Телефон рақамингиз', 'ru' => 'Ваш номер телефона', 'en' => 'Your phone number'][$locale] ?? 'Telefon raqamingiz';
    $commentPlaceholder = ['uz_latn' => 'Fikr va takliflaringizni yozing...', 'uz_cyrl' => 'Фикр ва таклифларингизни ёзинг...', 'ru' => 'Напишите ваши замечания и предложения...', 'en' => 'Write your feedback and suggestions...'][$locale] ?? 'Fikr va takliflaringizni yozing...';
    $progressText = ['uz_latn' => 'savol', 'uz_cyrl' => 'савол', 'ru' => 'вопрос', 'en' => 'question'][$locale] ?? 'savol';

    $severityLabels = [
        'uz_latn' => ['Juda past', 'Past', 'O\'rta', 'Yuqori', 'Juda yuqori'],
        'uz_cyrl' => ['Жуда паст', 'Паст', 'Ўрта', 'Юқори', 'Жуда юқори'],
        'ru' => ['Очень низко', 'Низко', 'Средне', 'Высоко', 'Очень высоко'],
        'en' => ['Very low', 'Low', 'Medium', 'High', 'Very high'],
    ];
    $sevLabels = $severityLabels[$locale] ?? $severityLabels['uz_latn'];
    $sevEmojis = ['😊', '🙂', '😐', '😟', '😠'];

    $totalQuestions = count($survey->questions);
@endphp

<div class="container">
    {{-- Header --}}
    <div class="header-card">
        <div class="header-logo">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 36px; height: 36px; border-radius: 8px; object-fit: contain;">
            @else
                <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="36" height="36" rx="10" fill="rgba(255,255,255,0.2)"/>
                    <path d="M18 11a7 7 0 110 14 7 7 0 010-14z" stroke="white" stroke-width="2" fill="none"/>
                    <path d="M15 18l2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            @endif
            <span class="header-logo-text">ShifoReyting</span>
            <span class="timer-badge">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span id="timer">01:00</span>
            </span>
        </div>
        <p class="header-title">{{ $pickText($survey->description, $pickText($survey->title)) }}</p>

        @if($qrCode?->doctor)
            <div class="header-doctor">
                <div class="doctor-avatar">
                    <svg fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/>
                    </svg>
                </div>
                <div>
                    <div class="doctor-name">{{ $qrCode->doctor->full_name }}</div>
                    @if($qrCode->doctor->specialty)
                        <div class="doctor-specialty">{{ $qrCode->doctor->specialty }}</div>
                    @endif
                </div>
            </div>
        @endif

        <div class="lang-switcher">
            @foreach($supportedLocales as $code => $label)
                <a href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}"
                   class="lang-btn {{ app()->getLocale() === $code ? 'active' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Form --}}
    <div class="form-card">
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <div class="progress-text">
                <span id="progressLabel">0 / {{ $totalQuestions }} {{ $progressText }}</span>
                <span id="progressPercent">0%</span>
            </div>
        </div>

        <form action="{{ $formAction }}" method="post" id="surveyForm">
            @csrf
            <input type="hidden" name="language" value="{{ app()->getLocale() }}">
            <input type="hidden" name="channel" value="{{ $sourceChannel }}">

            <div class="form-inner">
                @foreach($survey->questions as $index => $question)
                    <div class="question-block" data-question="{{ $index }}">
                        <div class="question-number">{{ $index + 1 }}</div>
                        <div class="question-title">
                            {{ $pickText($question->title) }}
                            @if($question->is_required)<span class="required-dot">*</span>@endif
                        </div>
                        @if($pickText($question->description))
                            <div class="question-desc">{{ $pickText($question->description) }}</div>
                        @endif

                        @if($question->type === 'rating')
                            <div class="star-rating" data-key="{{ $question->key }}">
                                @for($i = 1; $i <= 5; $i++)
                                    <label data-value="{{ $i }}">
                                        <input type="radio" name="answers[{{ $question->key }}]" value="{{ $i }}" {{ $question->is_required ? 'required' : '' }}>
                                        <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    </label>
                                @endfor
                            </div>
                            <div class="star-label" data-star-label="{{ $question->key }}"></div>

                        @elseif(in_array($question->type, ['yes_no', 'recommend'], true))
                            <div class="choice-grid">
                                <label class="choice-btn" data-key="{{ $question->key }}">
                                    <input type="radio" name="answers[{{ $question->key }}]" value="1" {{ $question->is_required ? 'required' : '' }}>
                                    <span class="choice-icon">{{ $question->type === 'recommend' ? '👍' : '✅' }}</span>
                                    <span class="choice-text">{{ $yesText }}</span>
                                </label>
                                <label class="choice-btn" data-key="{{ $question->key }}">
                                    <input type="radio" name="answers[{{ $question->key }}]" value="0" {{ $question->is_required ? 'required' : '' }}>
                                    <span class="choice-icon">{{ $question->type === 'recommend' ? '👎' : '❌' }}</span>
                                    <span class="choice-text">{{ $noText }}</span>
                                </label>
                            </div>

                        @elseif($question->type === 'single_choice')
                            <div class="option-list">
                                @foreach($question->options as $option)
                                    <label class="option-btn">
                                        <input type="radio" name="answers[{{ $question->key }}]" value="{{ $option->value }}" {{ $question->is_required ? 'required' : '' }}>
                                        <div class="option-radio"></div>
                                        <span class="option-text">{{ $pickText($option->label) }}</span>
                                    </label>
                                @endforeach
                            </div>

                        @elseif($question->type === 'nps')
                            <div class="nps-grid">
                                @for($i = 0; $i <= 10; $i++)
                                    <label class="nps-btn">
                                        <input type="radio" name="answers[{{ $question->key }}]" value="{{ $i }}" {{ $question->is_required ? 'required' : '' }}>
                                        {{ $i }}
                                    </label>
                                @endfor
                            </div>

                        @elseif($question->type === 'severity')
                            <div class="severity-grid">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="severity-btn">
                                        <input type="radio" name="answers[{{ $question->key }}]" value="{{ $i }}" {{ $question->is_required ? 'required' : '' }}>
                                        <span class="severity-emoji">{{ $sevEmojis[$i - 1] }}</span>
                                        <span class="severity-text">{{ $sevLabels[$i - 1] }}</span>
                                    </label>
                                @endfor
                            </div>

                        @elseif($question->type === 'comment')
                            <textarea
                                name="answers[{{ $question->key }}]"
                                rows="4"
                                class="comment-area"
                                placeholder="{{ $commentPlaceholder }}"
                            ></textarea>
                        @endif
                    </div>
                @endforeach

                {{-- Callback --}}
                <div class="callback-section">
                    <label class="callback-label">
                        <input type="checkbox" id="callback_requested" name="callback_requested" value="1" class="callback-checkbox">
                        {{ $callbackText }}
                    </label>
                    <input type="text" id="callback_contact" name="callback_contact"
                           class="callback-input" placeholder="{{ $callbackPlaceholder }}">
                </div>

                {{-- Submit --}}
                <button type="submit" id="submit_btn" class="submit-btn">
                    {{ $submitText }} →
                </button>
            </div>
        </form>
    </div>

    <div class="footer">
        ShifoReyting AI &copy; {{ date('Y') }}
    </div>
</div>

<script>
    const starLabels = @json($labels);
    const totalQuestions = {{ $totalQuestions }};

    // Star rating
    document.querySelectorAll('.star-rating').forEach(container => {
        const key = container.dataset.key;
        const labels = container.querySelectorAll('label');
        const labelEl = document.querySelector(`[data-star-label="${key}"]`);

        labels.forEach(label => {
            label.addEventListener('click', () => {
                const val = parseInt(label.dataset.value);
                labels.forEach((l, i) => {
                    const svg = l.querySelector('svg');
                    if (i < val) {
                        svg.style.fill = val <= 2 ? '#f59e0b' : val <= 3 ? '#f59e0b' : '#f59e0b';
                        svg.style.fill = '#f59e0b';
                        svg.style.stroke = '#f59e0b';
                        l.classList.add('active');
                    } else {
                        svg.style.fill = '#e2e8f0';
                        svg.style.stroke = '#cbd5e1';
                        l.classList.remove('active');
                    }
                });
                if (labelEl) {
                    labelEl.textContent = starLabels[val] || '';
                    labelEl.style.color = val <= 2 ? '#ef4444' : val <= 3 ? '#f59e0b' : '#10b981';
                }
                updateProgress();
            });
        });
    });

    // Choice buttons (yes/no, recommend)
    document.querySelectorAll('.choice-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const parent = btn.closest('.choice-grid');
            parent.querySelectorAll('.choice-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            updateProgress();
        });
    });

    // Option buttons (single_choice)
    document.querySelectorAll('.option-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const parent = btn.closest('.option-list');
            parent.querySelectorAll('.option-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            updateProgress();
        });
    });

    // NPS buttons
    document.querySelectorAll('.nps-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const parent = btn.closest('.nps-grid');
            parent.querySelectorAll('.nps-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            updateProgress();
        });
    });

    // Severity buttons
    document.querySelectorAll('.severity-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const parent = btn.closest('.severity-grid');
            parent.querySelectorAll('.severity-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            updateProgress();
        });
    });

    // Callback toggle
    const callbackCheckbox = document.getElementById('callback_requested');
    const callbackInput = document.getElementById('callback_contact');
    callbackCheckbox.addEventListener('change', () => {
        callbackInput.classList.toggle('visible', callbackCheckbox.checked);
        if (callbackCheckbox.checked) callbackInput.focus();
    });

    // Progress tracking
    function updateProgress() {
        let answered = 0;
        document.querySelectorAll('.question-block').forEach(block => {
            const radios = block.querySelectorAll('input[type="radio"]');
            const textarea = block.querySelector('textarea');
            if (radios.length && [...radios].some(r => r.checked)) answered++;
            else if (textarea && textarea.value.trim()) answered++;
        });
        const pct = Math.round((answered / totalQuestions) * 100);
        document.getElementById('progressFill').style.width = pct + '%';
        document.getElementById('progressLabel').textContent = answered + ' / ' + totalQuestions + ' {{ $progressText }}';
        document.getElementById('progressPercent').textContent = pct + '%';
    }

    // Comment change tracking
    document.querySelectorAll('.comment-area').forEach(ta => {
        ta.addEventListener('input', () => updateProgress());
    });

    // Timer
    let remaining = {{ $survey->estimated_seconds ?? 60 }};
    const timerEl = document.getElementById('timer');
    const interval = setInterval(() => {
        remaining--;
        const mm = String(Math.floor(remaining / 60)).padStart(2, '0');
        const ss = String(remaining % 60).padStart(2, '0');
        timerEl.textContent = mm + ':' + ss;
        if (remaining <= 0) {
            clearInterval(interval);
            timerEl.textContent = '00:00';
        }
    }, 1000);

    // Submit
    document.getElementById('surveyForm').addEventListener('submit', () => {
        const btn = document.getElementById('submit_btn');
        btn.disabled = true;
        btn.style.opacity = '0.6';
    });
</script>
</body>
</html>
