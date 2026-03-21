<?php

namespace App\Filament\App\Resources\SupportTickets\Pages;

use App\Filament\App\Resources\SupportTickets\SupportTicketResource;
use App\Models\SupportTicket;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSupportTicket extends CreateRecord
{
    protected static string $resource = SupportTicketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tenant = tenant();
        $user = Auth::user();

        $data['title'] = SupportTicket::generateTitle($data['category'], $data['message']);
        $data['tenant_id'] = $tenant?->id;
        $data['tenant_domain'] = $tenant?->domains?->first()?->domain ?? request()->getHost();
        $data['user_name'] = $user?->name;
        $data['user_email'] = $user?->email;
        $data['status'] = SupportTicket::STATUS_NEW;
        $data['is_read'] = false;
        $data['customer_notified'] = true;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
