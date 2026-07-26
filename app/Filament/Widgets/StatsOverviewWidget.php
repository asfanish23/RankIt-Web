<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\RankingTopic;
use App\Models\RankingCandidate;
use App\Models\RankingSubmission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Categories', Category::count())
                ->description('Categories created for ranking lists')
                ->descriptionIcon('heroicon-m-folder')
                ->color('primary'),
            Stat::make('Total Topics', RankingTopic::count())
                ->description('Active ranking lists')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),
            Stat::make('Total Candidates', RankingCandidate::count())
                ->description('Total items available to rank')
                ->descriptionIcon('heroicon-m-list-bullet')
                ->color('warning'),
            Stat::make('Total Submissions', RankingSubmission::count())
                ->description('Total ballots submitted by users')
                ->descriptionIcon('heroicon-m-squares-plus')
                ->color('danger'),
        ];
    }
}
