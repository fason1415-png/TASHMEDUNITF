<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Login as AdminLogin;
use App\Http\Middleware\ResolveTenantContext;
use App\Http\Middleware\SetLocale;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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

class DoctorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('doctor')
            ->path('doctor')
            ->login(AdminLogin::class)
            ->brandName('ShifoReyting | Shifokor')
            ->sidebarWidth('17rem')
            ->maxContentWidth('full')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/DoctorPanel/Resources'), for: 'App\Filament\DoctorPanel\Resources')
            ->discoverPages(in: app_path('Filament/DoctorPanel/Pages'), for: 'App\Filament\DoctorPanel\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/DoctorPanel/Widgets'), for: 'App\Filament\DoctorPanel\Widgets')
            ->widgets([])
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
