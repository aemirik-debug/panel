<?php

namespace App\Filament\App\Resources\Portfolios\Pages;

use App\Filament\App\Resources\Portfolios\PortfolioResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePortfolio extends CreateRecord
{
    protected static string $resource = PortfolioResource::class;
}
