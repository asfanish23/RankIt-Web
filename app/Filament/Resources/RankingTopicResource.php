<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RankingTopicResource\Pages;
use App\Filament\Resources\RankingTopicResource\RelationManagers;
use App\Models\RankingTopic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RankingTopicResource extends Resource
{
    protected static ?string $model = RankingTopic::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make('Topic Details')
                            ->description('Configure the title and descriptions of this ranking topic.')
                            ->icon('heroicon-o-document-text')
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Topic Title')
                                    ->placeholder('e.g., Best Anime of 2026, Top Pizza Toppings')
                                    ->prefixIcon('heroicon-o-tag')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Textarea::make('description')
                                    ->label('Description')
                                    ->placeholder('Describe this ranking topic...')
                                    ->rows(4),
                            ]),

                        Forms\Components\Section::make('Settings')
                            ->description('Assign this topic to a category.')
                            ->icon('heroicon-o-cog')
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->prefixIcon('heroicon-o-folder')
                                    ->required(),

                                Forms\Components\Hidden::make('created_by')
                                    ->default(fn () => auth()->id()),

                                Forms\Components\Hidden::make('visibility')
                                    ->default('public'),
                            ]),
                    ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CandidatesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRankingTopics::route('/'),
            'create' => Pages\CreateRankingTopic::route('/create'),
            'edit' => Pages\EditRankingTopic::route('/{record}/edit'),
        ];
    }
}
