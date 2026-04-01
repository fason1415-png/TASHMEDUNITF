<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-2 md:gap-6">
        <section class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900 sm:p-5">
            <h2 class="text-sm font-semibold">{{ __('pages.export_center.doctor_monthly_title') }}</h2>
            <div class="mt-3 space-y-3">
                <label class="block">
                    <span class="text-xs text-gray-500">{{ __('pages.export_center.month') }}</span>
                    <input type="month" wire:model.live="month" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                </label>
                <a
                    href="{{ route('exports.doctor-monthly', ['month' => $month, 'clinic_id' => auth()->user()?->isSuperAdmin() ? request('clinic_id') : null]) }}"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 sm:w-auto"
                >
                    {{ __('pages.export_center.download_excel') }}
                </a>
            </div>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900 sm:p-5">
            <h2 class="text-sm font-semibold">{{ __('pages.export_center.department_ranking_title') }}</h2>
            <div class="mt-3 grid gap-3">
                <label class="block">
                    <span class="text-xs text-gray-500">{{ __('pages.export_center.from') }}</span>
                    <input type="date" wire:model.live="from" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                </label>
                <label class="block">
                    <span class="text-xs text-gray-500">{{ __('pages.export_center.to') }}</span>
                    <input type="date" wire:model.live="to" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                </label>
                <a
                    href="{{ route('exports.department-ranking', ['from' => $from, 'to' => $to, 'clinic_id' => auth()->user()?->isSuperAdmin() ? request('clinic_id') : null]) }}"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 sm:w-auto"
                >
                    {{ __('pages.export_center.download_excel') }}
                </a>
            </div>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900 sm:p-5">
            <h2 class="text-sm font-semibold">{{ __('pages.export_center.complaint_report_title') }}</h2>
            <p class="mt-2 text-xs text-gray-500">{{ __('pages.export_center.complaint_report_desc') }}</p>
            <a
                href="{{ route('exports.complaint-categories', ['from' => $from, 'to' => $to, 'clinic_id' => auth()->user()?->isSuperAdmin() ? request('clinic_id') : null]) }}"
                class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 sm:w-auto"
            >
                {{ __('pages.export_center.download_excel') }}
            </a>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900 sm:p-5">
            <h2 class="text-sm font-semibold">{{ __('pages.export_center.clinic_summary_title') }}</h2>
            <p class="mt-2 text-xs text-gray-500">{{ __('pages.export_center.clinic_summary_desc') }}</p>
            <a
                href="{{ route('exports.clinic-summary-pdf', ['from' => $from, 'to' => $to, 'clinic_id' => auth()->user()?->isSuperAdmin() ? request('clinic_id') : null]) }}"
                class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700 sm:w-auto"
            >
                {{ __('pages.export_center.download_pdf') }}
            </a>
        </section>
    </div>
</x-filament-panels::page>
