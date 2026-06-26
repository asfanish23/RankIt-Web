<?php

namespace App\Filament\Resources\RankingCandidateResource\Pages;

use App\Filament\Resources\RankingCandidateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRankingCandidate extends EditRecord
{
    protected static string $resource = RankingCandidateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
