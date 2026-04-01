<?php

return [
    'locale' => [
        'uz_latn' => "O'zbekcha",
        'uz_cyrl' => 'Узбекча',
        'ru' => 'Русский',
        'en' => 'English',
    ],

    'landing' => [
        'admin_login' => 'Вход в админку',
        'management_panel' => 'Панель руководства',
        'hero_title' => 'Дашборд для руководства, персональный результат для каждого врача',
        'hero_subtitle' => 'Принимайте управленческие решения быстрее с помощью метрик в реальном времени, срезов по отделам, трендов и AI-выводов.',

        'dashboard_title' => 'Показатели клиники',
        'dashboard_sub' => 'Период: 1 янв - 31 июл | Специалист: Все | Врач: Все',
        'filter_today' => 'Сегодня',
        'filter_week' => 'Неделя',
        'filter_month' => 'Месяц',

        'patients' => 'Пациенты',
        'inpatients' => 'Стационар',
        'outpatients' => 'Амбулаторно',
        'complaints' => 'Жалобы',
        'patients_trend' => 'Тренд пациентов',
        'patients_by_gender' => 'Распределение по полу',
        'female' => 'Женщины',
        'male' => 'Мужчины',
        'bed_occupancy_rate' => 'Заполняемость коек',

        'doctor_panel_title' => 'Панель врача',
        'doctor_item_1' => 'Личный рейтинг и quality score',
        'doctor_item_2' => 'Недельные тренды',
        'doctor_item_3' => 'Сильные и слабые стороны',
        'doctor_item_4' => 'AI summary и coaching рекомендации',
        'doctor_item_5' => 'Право на бонус',

        'subline' => 'Метрики в реальном времени, срезы по отделам и тренды',

        'feature_1_title' => 'Контроль по филиалам',
        'feature_1_text' => 'Смотрите результаты по каждому филиалу и отделу с предупреждением о малой выборке и индикатором надежности.',
        'feature_2_title' => 'Тепловая карта жалоб',
        'feature_2_text' => 'Группируйте критичные отзывы по severity, контролируйте SLA и запускайте эскалации вовремя.',
        'feature_3_title' => 'Бонусы и KPI',
        'feature_3_text' => 'Автоматически считайте eligibility врача по бонусам на основе весов scoring engine клиники.',

        'open_dashboard' => 'Открыть дашборд',
    ],

    'executive' => [
        'title' => 'Панель руководства',
        'subtitle' => 'Метрики в реальном времени, срезы по отделам и тренды.',
        'live_badge' => 'АНАЛИТИКА LIVE',
        'updated_at' => 'Обновлено: :time',

        'monthly_feedback' => 'Отзывы за месяц',
        'avg_confidence' => 'Средний confidence',
        'scan_count' => 'Количество сканов',
        'critical_alerts' => 'Критические алерты',

        'feedback_trend' => 'Тренд feedback',
        'top_departments' => 'Топ отделов',
        'no_data' => 'Пока недостаточно данных.',

        'doctor_panel_title' => 'Панель врача',
        'doctor_item_1' => 'Личный рейтинг и quality score',
        'doctor_item_2' => 'Недельные тренды',
        'doctor_item_3' => 'Сильные и слабые стороны',
        'doctor_item_4' => 'AI summary и coaching рекомендации',
        'doctor_item_5' => 'Право на бонус: :rate% conversion',
        'doctor_report_period' => 'Период отчета: :from - :to',
        'doctor_kpi_analyzed' => 'Врачей в анализе',
        'doctor_kpi_quality' => 'Средний quality score',
        'doctor_kpi_risk' => 'Врачи в риске',
        'doctor_kpi_bonus' => 'Bonus conversion',
        'doctor_tab_top' => 'Топ результаты',
        'doctor_tab_risk' => 'Контроль рисков',
        'doctor_tab_growth' => 'Рост',
        'doctor_feedback_short' => 'Отзывы',
        'doctor_quality_short' => 'Качество',
        'doctor_confidence_short' => 'Confidence',
        'doctor_alerts_short' => 'Алерты',
        'doctor_trend_short' => 'Тренд',
        'doctor_no_data' => 'По выбранному периоду нет отчетов врачей.',
        'unknown_doctor' => 'Неизвестный врач',
    ],

    'realtime' => [
        'feedback_today' => 'Отзывы за сегодня',
        'feedback_today_desc' => 'Последние 24 часа',
        'avg_score_7d' => 'Средний балл за 7 дней',
        'avg_score_7d_desc' => 'Confidence-adjusted score',
        'flagged' => 'Отмеченные ответы',
        'flagged_desc' => 'Требует модерации',
        'scan_conversion' => 'Scan -> Response',
        'scan_conversion_desc' => ':converted/:scans conversion',
        'critical' => 'Критические алерты',
        'critical_desc' => 'Открытые high/critical',
    ],

    'trend' => [
        'heading' => 'Тренд feedback и качества (30 дней)',
        'description' => 'Дневное количество ответов и confidence score',
        'responses' => 'Ответы',
        'avg_confidence' => 'Средний confidence',
    ],

    'alerts' => [
        'heading' => 'Критические жалобы',
        'doctor' => 'Врач',
        'branch' => 'Филиал',
        'opened' => 'Открыто',
    ],

    'roles' => [
        'admin' => 'Админ',
        'analyst' => 'Аналитик',
    ],
];
