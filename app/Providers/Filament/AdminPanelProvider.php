<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Pages\Auth\CustomLogin::class)
            ->colors([
                'primary' => Color::Cyan,
                'danger' => Color::Red,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
            ])
            ->maxContentWidth('7xl')
            ->sidebarCollapsibleOnDesktop()
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): \Illuminate\Support\HtmlString => new \Illuminate\Support\HtmlString('
                    <style>
                        /* Light Mode: Dark Topbar */
                        html:not(.dark) .fi-topbar { 
                            background-color: #111827 !important; 
                            border-bottom: none !important;
                        }
                        html:not(.dark) .fi-topbar button, 
                        html:not(.dark) .fi-topbar a,
                        html:not(.dark) .fi-topbar span,
                        html:not(.dark) .fi-topbar svg {
                            color: #e5e7eb !important;
                        }
                        html:not(.dark) .fi-topbar input {
                            background-color: #374151 !important;
                            color: #ffffff !important;
                            border-color: #4b5563 !important;
                        }
                        html:not(.dark) .fi-topbar input::placeholder {
                            color: #9ca3af !important;
                        }

                        /* Dark Mode: Topbar */
                        .dark .fi-topbar { 
                            background-color: #0f172a !important; 
                            border-bottom: 1px solid #1e293b !important;
                        }
                        .dark .fi-topbar button, 
                        .dark .fi-topbar a,
                        .dark .fi-topbar span,
                        .dark .fi-topbar svg {
                            color: #cbd5e1 !important;
                        }
                        .dark .fi-topbar input {
                            background-color: #1e293b !important;
                            color: #e2e8f0 !important;
                            border-color: #334155 !important;
                        }
                        .dark .fi-topbar input::placeholder {
                            color: #64748b !important;
                        }

                        /* Light Mode: Sidebar Hover & Active */
                        html:not(.dark) .fi-sidebar-item-button:hover {
                            background-color: rgba(6, 182, 212, 0.08) !important;
                        }
                        html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-button {
                            background-color: rgba(6, 182, 212, 0.12) !important;
                            border-radius: 0.5rem;
                        }

                        /* Dark Mode: Sidebar Hover & Active */
                        .dark .fi-sidebar-item-button:hover {
                            background-color: rgba(6, 182, 212, 0.15) !important;
                        }
                        .dark .fi-sidebar-item-active .fi-sidebar-item-button {
                            background-color: rgba(6, 182, 212, 0.2) !important;
                            border-radius: 0.5rem;
                        }
                    </style>
                ')
            )
            ->font('Poppins')
            ->brandName('SEMUDAH')
            ->favicon(asset('SEMUDAH-LOGO-3-Favicon.png'))
            ->brandLogo(fn () => view('filament.logo'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                //
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
