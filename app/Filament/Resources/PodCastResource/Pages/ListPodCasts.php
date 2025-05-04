<?php

namespace App\Filament\Resources\PodCastResource\Pages;

use App\Filament\Resources\PodCastResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPodCasts extends ListRecords
{
    protected static string $resource = PodCastResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
