<?php

namespace App\Filament\Widgets;

use App\Models\RankingTopic;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopRankingTopics extends BaseWidget
{
    protected static ?string $heading = 'Most Popular Ranking Topics';
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                RankingTopic::withCount('submissions')
                    ->orderByDesc('submissions_count')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Topic Title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),
                Tables\Columns\TextColumn::make('submissions_count')
                    ->label('Votes Submitted')
                    ->sortable(),
            ]);
    }
}
