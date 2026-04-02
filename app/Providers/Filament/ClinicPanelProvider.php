<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Login as AdminLogin;
use App\Filament\Widgets\ComplaintAlertsWidget;
use App\Filament\Widgets\ExecutiveHeroWidget;
use App\Filament\Widgets\FeedbackTrendChart;
use App\Filament\Widgets\RealtimeOverview;
use App\Http\Middleware\ResolveTenantContext;
use App\Http\Middleware\SetLocale;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ClinicPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('clinic')
            ->path('clinic')
            ->login(AdminLogin::class)
            ->brandName('ShifoReyting | Klinika')
            ->sidebarWidth('17rem')
            ->maxContentWidth('full')
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Structure')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Feedback')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Patronage')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Rewards')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('System')
                    ->collapsed(),
            ])
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->discoverResources(in: app_path('Filament/ClinicPanel/Resources'), for: 'App\Filament\ClinicPanel\Resources')
            ->discoverPages(in: app_path('Filament/ClinicPanel/Pages'), for: 'App\Filament\ClinicPanel\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/ClinicPanel/Widgets'), for: 'App\Filament\ClinicPanel\Widgets')
            ->widgets([
                ExecutiveHeroWidget::class,
                RealtimeOverview::class,
                FeedbackTrendChart::class,
                ComplaintAlertsWidget::class,
            ])
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn (): string => view('filament.theme.admin-modern')->render(),
            )
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn (): string => view('filament.partials.locale-switcher')->render(),
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): string => view('filament.auth.login.before')->render(),
                AdminLogin::class,
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn (): string => view('filament.auth.login.after')->render(),
                AdminLogin::class,
            )
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn (): string => view('filament.theme.admin-login')->render(),
                AdminLogin::class,
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                SetLocale::class,
                ResolveTenantContext::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
