<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ShifoReyting — Rahmat!</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f766e 0%, #115e59 40%, #134e4a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            -webkit-font-smoothing: antialiased;
        }
        .card {
            background: #fff;
            border-radius: 28px;
            padding: 48px 32px;
            text-align: center;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.6s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .check-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0d9488, #14b8a6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            animation: scaleIn 0.5s ease 0.2s both;
        }
        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
        .check-circle svg {
            width: 40px;
            height: 40px;
            stroke: #fff;
            stroke-width: 3;
            fill: none;
        }
        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }
        p {
            font-size: 15px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            background: linear-gradient(135deg, #0d9488, #0f766e);
            color: #fff;
            text-decoration: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .back-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(13, 148, 136, 0.4);
        }
        .footer {
            margin-top: 32px;
            font-size: 12px;
            color: #cbd5e1;
        }
    </style>
</head>
<body>
@php
    $locale = app()->getLocale();
    $title = ['uz_latn' => 'Rahmat!', 'uz_cyrl' => 'Раҳмат!', 'ru' => 'Спасибо!', 'en' => 'Thank you!'][$locale] ?? 'Rahmat!';
    $desc = [
        'uz_latn' => 'Fikr-mulohazangiz biz uchun juda muhim. Sizning javoblaringiz xizmat sifatini yaxshilashga yordam beradi.',
        'uz_cyrl' => 'Фикр-мулоҳазангиз биз учун жуда муҳим. Сизнинг жавобларингиз хизмат сифатини яхшилашга ёрдам беради.',
        'ru' => 'Ваше мнение очень важно для нас. Ваши ответы помогут нам улучшить качество обслуживания.',
        'en' => 'Your feedback is very important to us. Your answers will help us improve our service quality.',
    ][$locale] ?? 'Fikr-mulohazangiz biz uchun juda muhim.';
    $backText = ['uz_latn' => 'Bosh sahifaga qaytish', 'uz_cyrl' => 'Бош саҳифага қайтиш', 'ru' => 'Вернуться на главную', 'en' => 'Back to main page'][$locale] ?? 'Bosh sahifaga qaytish';
@endphp

<div class="card">
    <div class="check-circle">
        <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    </div>
    <h1>{{ $title }}</h1>
    <p>{{ $desc }}</p>
    <a href="{{ $backUrl }}" class="back-btn">
        ← {{ $backText }}
    </a>
    <div class="footer">ShifoReyting AI &copy; {{ date('Y') }}</div>
</div>
</body>
</html>
