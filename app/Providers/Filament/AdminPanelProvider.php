<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Register;
use App\Filament\Widgets\CuponsGeradosStats;
use App\Filament\Widgets\NotasENumerosStats;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Tables\Table;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Njxqlus\FilamentProgressbar\FilamentProgressbarPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        Table::configureUsing(function (Table $table) {
            $table->paginationPageOptions([10, 25, 50, 100]); // Não inclui -1, que é o "Ver todos"
        });
        
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->passwordReset()
            ->registration(Register::class)
            ->authGuard('admin')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->plugins([
                FilamentProgressbarPlugin::make()->color('#c0ff01'),
                // FilamentBackgroundsPlugin::make(),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                \FilipFonal\FilamentLogManager\FilamentLogManager::make(),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->sidebarCollapsibleOnDesktop()
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                NotasENumerosStats::class,
            ])
            ->resources([
                config('filament-logger.activity_resource')
            ])
            ->databaseNotifications()
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
                // Adicionando o middleware customizado para verificar se o usuário é admin
                // \App\Http\Middleware\CheckAdmin::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
