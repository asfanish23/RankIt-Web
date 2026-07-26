<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Filament\Resources\RankingTopicResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TopicsRelationManager extends RelationManager
{
    protected static string $relationship = 'topics';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Topic Title')
                    ->placeholder('e.g., Best Shounen Anime, Top Action Movies')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->placeholder('Describe this ranking topic...')
                    ->rows(3),
                Forms\Components\Hidden::make('created_by')
                    ->default(fn () => auth()->id()),
                Forms\Components\Hidden::make('visibility')
                    ->default('public'),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('manage')
                    ->label('Manage Candidates')
                    ->icon('heroicon-m-cog-6-tooth')
                    ->color('warning')
                    ->url(fn ($record) => RankingTopicResource::getUrl('edit', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
