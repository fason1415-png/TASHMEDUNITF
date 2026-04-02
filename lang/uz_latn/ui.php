<?php

return [
    'locale' => [
        'uz_latn' => "O'zbekcha",
        'uz_cyrl' => 'Узбекча',
        'ru' => 'Русский',
        'en' => 'English',
    ],

    'landing' => [
        'admin_login' => 'Admin kirish',
        'management_panel' => 'Rahbariyat paneli',
        'hero_title' => 'Rahbariyat uchun dashboard, vrach uchun shaxsiy natija',
        'hero_subtitle' => 'Real vaqtli korsatkichlar, bolimlar kesimi, trendlar va AI xulosalar bilan boshqaruv qarorlarini tezroq qabul qiling.',

        'dashboard_title' => 'Shifoxona korsatkichlari',
        'dashboard_sub' => 'Sana: 1-yanvar - 31-iyul | Mutaxassis: Barchasi | Shifokor: Barchasi',
        'filter_today' => 'Bugun',
        'filter_week' => 'Hafta',
        'filter_month' => 'Oy',

        'patients' => 'Bemorlar',
        'inpatients' => 'Statsionar',
        'outpatients' => 'Ambulator',
        'complaints' => 'Shikoyatlar',
        'patients_trend' => 'Bemorlar trendi',
        'patients_by_gender' => 'Jins boyicha taqsimot',
        'female' => 'Ayol',
        'male' => 'Erkak',
        'bed_occupancy_rate' => 'Yotoq bandlik darajasi',

        'doctor_panel_title' => "Vrach ko'rsatkichlari",
        'doctor_item_1' => 'Oz reytingi va quality score',
        'doctor_item_2' => 'Haftalik trendlar',
        'doctor_item_3' => 'Kuchli va zaif tomonlar',
        'doctor_item_4' => 'AI summary va coaching tavsiyalar',
        'doctor_item_5' => 'Ragbat eligibility',

        'subline' => 'Real vaqtli korsatkichlar, bolimlar kesimi va trendlar',

        'feature_1_title' => 'Filial kesimidagi nazorat',
        'feature_1_text' => 'Har bir branch va department natijasini alohida koring, past sample boyicha warning va ishonchlilik korsatkichi bilan.',
        'feature_2_title' => 'Shikoyat heatmap',
        'feature_2_text' => 'Kritik feedbacklarni severity boyicha ajrating, SLA muddatlarini kuzating va tezkor eskalatsiya ishga tushiring.',
        'feature_3_title' => 'Ragbat va KPI',
        'feature_3_text' => 'Per-klinika weightlarga asoslangan scoring engine bilan doktor bonus eligibility sini avtomatik hisoblang.',

        'open_dashboard' => 'Dashboardni ochish',

        'how_it_works' => 'Qanday ishlaydi?',
        'step_1_title' => 'QR skanerlang',
        'step_1_text' => 'Bemor palatadagi yoki kabinetdagi QR kodni telefonida skanerlaydi',
        'step_2_title' => 'Baholang',
        'step_2_text' => 'Shifokorga 1 dan 5 gacha yulduz va izoh qoldiring — anonim va xavfsiz',
        'step_3_title' => 'AI tahlil',
        'step_3_text' => 'Sun\'iy intellekt bahoni tahlil qiladi, sentiment va mavzularni aniqlaydi',

        'patronage_title' => 'Patronaj tizimi',
        'patronage_subtitle' => 'Bemor chiqarilgandan keyin ham nazorat davom etadi',
        'patronage_1' => 'Avtomatik oilaviy shifokorga xabar',
        'patronage_2' => '24 soatda tashrif buyurish SLA',
        'patronage_3' => 'GPS bilan tashrif tasdiqlash',
        'patronage_4' => '3 bosqichli eskalatsiya tizimi',

        'telegram_title' => 'Telegram Bot',
        'telegram_subtitle' => '@ShifoReytingBot orqali 30 soniyada baholang',
        'telegram_step_1' => 'Klinika tanlang',
        'telegram_step_2' => 'Shifokor tanlang',
        'telegram_step_3' => 'Baholang',

        'panels_title' => '3 ta panel — har bir rol uchun',
        'panel_ministry' => 'Vazirlik',
        'panel_ministry_desc' => 'Barcha klinikalar, viloyatlar statistikasi, umumiy nazorat',
        'panel_clinic' => 'Klinika',
        'panel_clinic_desc' => 'Shifokorlar, bo\'limlar, QR kodlar, feedback tahlili',
        'panel_doctor' => 'Shifokor',
        'panel_doctor_desc' => 'Shaxsiy reyting, baholar, patronaj tasklari',

        'cta_title' => 'Shifoxona sifatini AI bilan nazorat qiling',
        'cta_button' => 'Bepul sinab ko\'ring',
        'cta_login' => 'Tizimga kirish',
    ],

    'executive' => [
        'title' => 'Rahbariyat paneli',
        'subtitle' => 'Real vaqtli korsatkichlar, bolimlar kesimi va trendlar.',
        'live_badge' => 'LIVE ANALYTICS',
        'updated_at' => 'Yangilandi: :time',

        'monthly_feedback' => 'Oylik feedback',
        'avg_confidence' => "Ortacha confidence",
        'scan_count' => 'Scan soni',
        'critical_alerts' => 'Kritik alertlar',

        'feedback_trend' => 'Feedback trendi',
        'top_departments' => 'Top bolimlar',
        'no_data' => "Hozircha yetarli data yoq.",

        'doctor_panel_title' => "Vrach ko'rsatkichlari",
        'doctor_item_1' => 'Oz reytingi va quality score',
        'doctor_item_2' => 'Haftalik trendlar',
        'doctor_item_3' => 'Kuchli va zaif tomonlar',
        'doctor_item_4' => 'AI summary va coaching tavsiyalar',
        'doctor_item_5' => 'Ragbat eligibility: :rate% conversion',
        'doctor_report_period' => 'Hisobot davri: :from - :to',
        'doctor_kpi_analyzed' => "Tahlil qilingan vrachlar",
        'doctor_kpi_quality' => 'Ortacha quality score',
        'doctor_kpi_risk' => "Riskdagi vrachlar",
        'doctor_kpi_bonus' => "Bonus conversion",
        'doctor_chart_quality' => 'Quality score',
        'doctor_chart_confidence' => 'Confidence score',
        'doctor_chart_trend' => 'Trend dinamikasi',
        'doctor_chart_risk' => 'Risk tahlili',
        'doctor_tab_top' => 'Top natijalar',
        'doctor_tab_risk' => 'Risk nazorati',
        'doctor_tab_growth' => "O'sish",
        'doctor_feedback_short' => 'Feedback',
        'doctor_quality_short' => 'Quality',
        'doctor_confidence_short' => 'Confidence',
        'doctor_alerts_short' => 'Alert',
        'doctor_trend_short' => 'Trend',
        'doctor_no_data' => "Tanlangan davr uchun vrach hisobotlari topilmadi.",
        'unknown_doctor' => "Noma'lum vrach",
    ],

    'realtime' => [
        'feedback_today' => 'Bugungi feedback',
        'feedback_today_desc' => 'Oxirgi 24 soat',
        'avg_score_7d' => '7 kunlik ortacha ball',
        'avg_score_7d_desc' => 'Confidence-adjusted score',
        'flagged' => 'Flag qilinganlar',
        'flagged_desc' => 'Moderatsiya talab etadi',
        'scan_conversion' => 'Scan -> Response',
        'scan_conversion_desc' => ':converted/:scans conversion',
        'critical' => 'Kritik alertlar',
        'critical_desc' => 'Open high/critical',
    ],

    'trend' => [
        'heading' => 'Feedback va sifat trendi (30 kun)',
        'description' => 'Kunlik javoblar soni va confidence score',
        'responses' => 'Javoblar',
        'avg_confidence' => 'Ortacha confidence',
    ],

    'alerts' => [
        'heading' => 'Kritik shikoyatlar',
        'doctor' => 'Shifokor',
        'branch' => 'Filial',
        'opened' => 'Ochilgan',
    ],

    'roles' => [
        'admin' => 'Admin',
        'analyst' => 'Tahlilchi',
    ],
];
