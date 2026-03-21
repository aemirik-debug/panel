<?php

namespace App\Filament\App\Resources\SupportTickets;

use App\Filament\App\Resources\SupportTickets\Pages\CreateSupportTicket;
use App\Filament\App\Resources\SupportTickets\Pages\EditSupportTicket;
use App\Filament\App\Resources\SupportTickets\Pages\ListSupportTickets;
use App\Filament\App\Resources\SupportTickets\Schemas\SupportTicketForm;
use App\Filament\App\Resources\SupportTickets\Tables\SupportTicketsTable;
use App\Models\SupportTicket;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLifebuoy;

    protected static ?int $navigationSort = 220;

    public static function getNavigationLabel(): string
    {
        return 'Destek';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'İletişim & Etkileşim';
    }

    public static function getModelLabel(): string
    {
        return 'Destek Talebi';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Destek Talepleri';
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()
            ->where('customer_notified', false)
            ->whereNotNull('admin_reply')
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('tenant_id', tenant()?->id);
    }

    public static function form(Schema $schema): Schema
    {
        return SupportTicketForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupportTicketsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupportTickets::route('/'),
            'create' => CreateSupportTicket::route('/create'),
            'edit' => EditSupportTicket::route('/{record}/edit'),
        ];
    }
}
