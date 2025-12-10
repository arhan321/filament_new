<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Panel;
use App\Models\User;
use App\Models\Product;
use Filament\PanelProvider;
use Filament\Enums\ThemeMode;
use Filament\Support\Enums\Width;
use Filament\Support\Colors\Color;
use Awcodes\Overlook\OverlookPlugin;
use App\Filament\Admin\Pages\Dashboard;
use Filament\Navigation\NavigationGroup;
use Filafly\Icons\Phosphor\PhosphorIcons;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filament\Http\Middleware\Authenticate;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Awcodes\Overlook\Widgets\OverlookWidget;
use Illuminate\Session\Middleware\StartSession;
use App\Filament\Admin\Widgets\LatestAccessLogs;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Jacobtims\FilamentLogger\FilamentLoggerPlugin;
use Caresome\FilamentAuthDesigner\Enums\AuthLayout;
use App\Filament\Admin\Resources\Users\UserResource;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Nagi\FilamentAbyssTheme\FilamentAbyssThemePlugin;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Caresome\FilamentAuthDesigner\Enums\MediaDirection;
use Filament\Http\Middleware\DisableBladeIconComponents;
use App\Filament\Admin\Resources\Products\ProductResource;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;

final class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('web')
            ->spa()
            ->spaUrlExceptions([
                Dashboard::class,
            ])
            ->login()
            ->defaultThemeMode(ThemeMode::Light)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->topbar(false)
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->maxContentWidth(Width::Full)
            ->unsavedChangesAlerts()
            ->databaseTransactions()
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\Filament\Admin\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\Filament\Admin\Pages')
            ->pages([
                // ProductResource::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\Filament\Admin\Widgets')
            ->widgets([
                OverlookWidget::class,
                LatestAccessLogs::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->collapsed(true)
                    ->label('General'),
                NavigationGroup::make()
                    ->collapsed(true)
                    ->label('Administration'),
                NavigationGroup::make()
                    ->collapsed(true)
                    ->label('Products'),
            ])
            ->plugins([
                FilamentAbyssThemePlugin::make(),
                PhosphorIcons::make()->duotone(),
                AuthDesignerPlugin::make()
                    ->login(
                        layout: AuthLayout::Panel,
                        media: 'https://images.pexels.com/photos/466685/pexels-photo-466685.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                        direction: MediaDirection::Left,
                    )->themeToggle(),
                BreezyCore::make()
                    ->myProfile(
                        hasAvatars: true,
                        slug: 'profile',
                        userMenuLabel: 'Profile',
                    )
                    ->enableBrowserSessions(),
                GlobalSearchModalPlugin::make(),
                OverlookPlugin::make()
                    ->sort(2)
                    ->columns([
                        'default' => 4,
                        'sm' => 2,
                        'lg' => 4,
                        'xl' => 6,
                    ])
                    ->includes([
                        UserResource::class,
                    ]),
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 2,
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 2,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 2,
                    ])
                    ->navigationLabel('Roles & Permissions')
                    ->navigationGroup('Administration')
                    ->navigationSort(2)
                    ->navigationIcon(Phosphor::ShieldCheckDuotone),
                FilamentLoggerPlugin::make(),
                FilamentLogViewerPlugin::make()
                    ->navigationGroup('Administration')
                    ->navigationSort(4)
                    ->navigationIcon(Phosphor::FileArchiveDuotone),
                FilamentDeveloperLoginsPlugin::make()
                    ->enabled(app()->environment('local'))
                    ->switchable(true)
                    ->users(fn () => User::pluck('email', 'name')->toArray()),
            ])
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
            ])
            ->viteTheme('resources/css/filament/admin/theme.css');
    }
}
