<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Resources\SupportTickets\SupportTicketResource;
use BackedEnum;
use Filament\Pages\Page;

class SupportPage extends Page
{
    protected string $view = 'filament-panels::pages.page';

    protected static ?string $navigationLabel = 'Destek';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-lifebuoy';
    protected static ?int $navigationSort = 200;

    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public function mount(): void
    {
        redirect(SupportTicketResource::getUrl('index'));
    }
}
