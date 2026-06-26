<?php

namespace App\Filament\Resources\RankingCandidateResource\Pages;

use App\Filament\Resources\RankingCandidateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRankingCandidates extends ListRecords
{
    protected static string $resource = RankingCandidateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
