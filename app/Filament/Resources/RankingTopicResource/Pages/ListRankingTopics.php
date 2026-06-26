<?php

namespace App\Filament\Resources\RankingTopicResource\Pages;

use App\Filament\Resources\RankingTopicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRankingTopics extends ListRecords
{
    protected static string $resource = RankingTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
