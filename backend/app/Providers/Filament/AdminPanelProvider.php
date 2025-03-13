<?php

namespace App\Providers\Filament;

<<<<<<< HEAD
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
=======
use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
>>>>>>> b2b2cf4189ef85c0e5dfea594b9c8d7fd0f7d831

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
<<<<<<< HEAD
                'primary' => Color::Emerald,
            ])
            ->navigationItems([
                NavigationItem::make('Escritorio')
                    ->icon('heroicon-o-home')
                    ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                    ->url(fn (): string => route('filament.admin.pages.dashboard'))
                    ->badge('Panel de Control', color: 'success')
                    ->sort(1),
                NavigationItem::make('Usuarios')
                    ->icon('heroicon-o-users')
                    ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.users.*'))
                    ->url(fn (): string => route('filament.admin.resources.users.index'))
                    ->badge('Gestión de Usuarios', color: 'info')
                    ->sort(2),
                NavigationItem::make('Cerrar sesión')
                    ->url('http://localhost:3000/users/login')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->group('Cuenta')
                    ->sort(100)
                    ->badge('Salir del sistema', color: 'danger')
            ])
            ->sidebarCollapsibleOnDesktop()
            ->brandName('Panel Administrativo')
=======
                'primary' => Color::Amber,
            ])
>>>>>>> b2b2cf4189ef85c0e5dfea594b9c8d7fd0f7d831
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
<<<<<<< HEAD
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
=======
            ->middleware(['web'])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->maxContentWidth('full')
            ->renderHook(
                'panels::user-menu.before',
                fn () => view('filament.custom.user-menu')
            )
            ->navigationItems([
                \Filament\Navigation\NavigationItem::make('Cerrar sesión')
                    ->url('http://localhost:3000/users/login')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->group('Cuenta')
            ])
            ->sidebarCollapsibleOnDesktop();
    }
} 
>>>>>>> b2b2cf4189ef85c0e5dfea594b9c8d7fd0f7d831
