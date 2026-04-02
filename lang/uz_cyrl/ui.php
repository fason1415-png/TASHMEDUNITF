<?php

return [
    'locale' => [
        'uz_latn' => "O'zbekcha",
        'uz_cyrl' => 'Узбекча',
        'ru' => 'Русский',
        'en' => 'English',
    ],

    'landing' => [
        'admin_login' => 'Админ кириш',
        'management_panel' => 'Раҳбарият панели',
        'hero_title' => 'Раҳбарият учун дашборд, врач учун шахсий натижа',
        'hero_subtitle' => 'Реал вақтли кўрсаткичлар, бўлимлар кесими, трендлар ва AI хулосалар билан бошқарув қарорларини тезроқ қабул қилинг.',

        'dashboard_title' => 'Шифохона кўрсаткичлари',
        'dashboard_sub' => 'Сана: 1-январ - 31-июл | Мутахассис: Барчаси | Шифокор: Барчаси',
        'filter_today' => 'Бугун',
        'filter_week' => 'Ҳафта',
        'filter_month' => 'Ой',

        'patients' => 'Беморлар',
        'inpatients' => 'Стационар',
        'outpatients' => 'Амбулатор',
        'complaints' => 'Шикоятлар',
        'patients_trend' => 'Беморлар тренди',
        'patients_by_gender' => 'Жинс бўйича тақсимот',
        'female' => 'Аёл',
        'male' => 'Эркак',
        'bed_occupancy_rate' => 'Ётоқ бандлик даражаси',

        'doctor_panel_title' => 'Врач кўрсаткичлари',
        'doctor_item_1' => 'Ўз рейтинги ва quality score',
        'doctor_item_2' => 'Ҳафталик трендлар',
        'doctor_item_3' => 'Кучли ва заиф томонлар',
        'doctor_item_4' => 'AI summary ва coaching тавсиялар',
        'doctor_item_5' => 'Рағбат eligibility',

        'subline' => 'Реал вақтли кўрсаткичлар, бўлимлар кесими ва трендлар',

        'feature_1_title' => 'Филиал кесимидаги назорат',
        'feature_1_text' => 'Ҳар бир branch ва department натижасини алоҳида кўринг, паст sample бўйича warning ва ишончлилик кўрсаткичи билан.',
        'feature_2_title' => 'Шикоят heatmap',
        'feature_2_text' => 'Критик feedbackларни severity бўйича ажратинг, SLA муддатларини кузатинг ва тезкор эскалация ишга туширинг.',
        'feature_3_title' => 'Рағбат ва KPI',
        'feature_3_text' => 'Per-clinic weightларга асосланган scoring engine билан доктор bonus eligibilityсини автоматик ҳисобланг.',

        'open_dashboard' => 'Дашбордни очиш',
    ],

    'executive' => [
        'title' => 'Раҳбарият панели',
        'subtitle' => 'Реал вақтли кўрсаткичлар, бўлимлар кесими ва трендлар.',
        'live_badge' => 'LIVE ANALYTICS',
        'updated_at' => 'Янгиланди: :time',

        'monthly_feedback' => 'Ойлик feedback',
        'avg_confidence' => 'Ўртача confidence',
        'scan_count' => 'Scan сони',
        'critical_alerts' => 'Критик alertлар',

        'feedback_trend' => 'Feedback тренди',
        'top_departments' => 'Топ бўлимлар',
        'no_data' => 'Ҳозирча етарли data йўқ.',

        'doctor_panel_title' => 'Врач кўрсаткичлари',
        'doctor_item_1' => 'Ўз рейтинги ва quality score',
        'doctor_item_2' => 'Ҳафталик трендлар',
        'doctor_item_3' => 'Кучли ва заиф томонлар',
        'doctor_item_4' => 'AI summary ва coaching тавсиялар',
        'doctor_item_5' => 'Рағбат eligibility: :rate% conversion',
        'doctor_report_period' => 'Ҳисобот даври: :from - :to',
        'doctor_kpi_analyzed' => 'Таҳлил қилинган врачлар',
        'doctor_kpi_quality' => 'Ўртача quality score',
        'doctor_kpi_risk' => 'Рискдаги врачлар',
        'doctor_kpi_bonus' => 'Bonus conversion',
        'doctor_chart_quality' => 'Quality score',
        'doctor_chart_confidence' => 'Confidence score',
        'doctor_chart_trend' => 'Тренд динамикаси',
        'doctor_chart_risk' => 'Риск таҳлили',
        'doctor_tab_top' => 'Топ натижалар',
        'doctor_tab_risk' => 'Риск назорати',
        'doctor_tab_growth' => 'Ўсиш',
        'doctor_feedback_short' => 'Feedback',
        'doctor_quality_short' => 'Quality',
        'doctor_confidence_short' => 'Confidence',
        'doctor_alerts_short' => 'Алерт',
        'doctor_trend_short' => 'Тренд',
        'doctor_no_data' => 'Танланган давр учун врач ҳисоботлари топилмади.',
        'unknown_doctor' => 'Номаълум врач',
    ],

    'realtime' => [
        'feedback_today' => 'Бугунги feedback',
        'feedback_today_desc' => 'Охирги 24 соат',
        'avg_score_7d' => '7 кунлик ўртача балл',
        'avg_score_7d_desc' => 'Confidence-adjusted score',
        'flagged' => 'Flag қилинганлар',
        'flagged_desc' => 'Модерация талаб этади',
        'scan_conversion' => 'Scan -> Response',
        'scan_conversion_desc' => ':converted/:scans conversion',
        'critical' => 'Критик alertлар',
        'critical_desc' => 'Open high/critical',
    ],

    'trend' => [
        'heading' => 'Feedback ва сифат тренди (30 кун)',
        'description' => 'Кунлик жавоблар сони ва confidence score',
        'responses' => 'Жавоблар',
        'avg_confidence' => 'Ўртача confidence',
    ],

    'alerts' => [
        'heading' => 'Критик шикоятлар',
        'doctor' => 'Шифокор',
        'branch' => 'Филиал',
        'opened' => 'Очилган',
    ],

    'roles' => [
        'admin' => 'Админ',
        'analyst' => 'Таҳлилчи',
    ],
];
