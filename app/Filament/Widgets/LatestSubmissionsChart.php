<?php

namespace App\Filament\Widgets;

use App\Models\RankingSubmission;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class LatestSubmissionsChart extends ChartWidget
{
    protected static ?string $heading = 'Recent Voting Activity';
    protected static string $color = 'primary';

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = RankingSubmission::whereDate('created_at', $date->toDateString())->count();
            
            $data[] = $count;
            $labels[] = $date->format('M d');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Votes Submitted',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
