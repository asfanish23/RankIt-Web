<?php

namespace App\Filament\Resources\RankingTopicResource\Pages;

use App\Filament\Resources\RankingTopicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRankingTopic extends EditRecord
{
    protected static string $resource = RankingTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
