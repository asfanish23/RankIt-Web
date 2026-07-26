<?php

namespace App\Filament\Resources\CandidateSuggestionResource\Pages;

use App\Filament\Resources\CandidateSuggestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCandidateSuggestions extends ListRecords
{
    protected static string $resource = CandidateSuggestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
