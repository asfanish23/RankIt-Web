<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CandidateSuggestionResource\Pages;
use App\Models\CandidateSuggestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CandidateSuggestionResource extends Resource
{
    protected static ?string $model = CandidateSuggestion::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?string $navigationLabel = 'Suggestions';

    protected static ?string $pluralModelLabel = 'Candidate Suggestions';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->disabled()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->disabled()
                    ->rows(3),
                Forms\Components\Select::make('topic_id')
                    ->relationship('topic', 'title')
                    ->disabled()
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->disabled()
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->disabled()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Suggested Candidate')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('topic.title')
                    ->label('Ranking Topic')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Suggested By')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Suggestion')
                    ->modalDescription('This will convert the suggestion into a live candidate and fetch a cover photo automatically.')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update(['status' => 'approved']);

                        $unsplashKey = env('UNSPLASH_ACCESS_KEY');
                        $imageUrl = null;

                        // 1. Try Wikipedia PageImages API via Search Generator (highly accurate, case-insensitive, fuzzy matching)
                        try {
                            $wRes = \Illuminate\Support\Facades\Http::withHeaders([
                                'User-Agent' => 'RankItApp/1.0 (contact@rankit.com) PHP/8.1'
                            ])->get("https://en.wikipedia.org/w/api.php", [
                                'action' => 'query',
                                'generator' => 'search',
                                'gsrsearch' => $record->name,
                                'gsrlimit' => 1,
                                'prop' => 'pageimages',
                                'format' => 'json',
                                'pithumbsize' => 600,
                                'redirects' => 1,
                            ]);
                            if ($wRes->successful()) {
                                $wData = $wRes->json();
                                if (!empty($wData['query']['pages'])) {
                                    $pages = $wData['query']['pages'];
                                    $firstPage = reset($pages);
                                    if (!empty($firstPage['thumbnail']['source'])) {
                                        $imageUrl = $firstPage['thumbnail']['source'];
                                    }
                                }
                            }
                        } catch (\Exception $e) {}

                        // 2. Fallback to Unsplash
                        if (empty($imageUrl) && $unsplashKey) {
                            try {
                                $uRes = \Illuminate\Support\Facades\Http::get("https://api.unsplash.com/search/photos", [
                                    'query' => $record->name,
                                    'per_page' => 1,
                                    'client_id' => $unsplashKey,
                                ]);
                                if ($uRes->successful()) {
                                    $uData = $uRes->json();
                                    if (!empty($uData['results'][0]['urls']['regular'])) {
                                        $imageUrl = $uData['results'][0]['urls']['regular'];
                                    }
                                }
                            } catch (\Exception $e) {}
                        }

                        // 3. Fallback to default placeholder image
                        if (empty($imageUrl)) {
                            $imageUrl = asset('images/def.png');
                        }

                        // Create candidate
                        \App\Models\RankingCandidate::create([
                            'topic_id' => $record->topic_id,
                            'name' => $record->name,
                            'description' => $record->description,
                            'image_url' => $imageUrl,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Suggestion approved and converted to candidate!')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Suggestion')
                    ->modalDescription('Are you sure you want to reject this candidate suggestion?')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update(['status' => 'rejected']);

                        \Filament\Notifications\Notification::make()
                            ->title('Suggestion rejected')
                            ->danger()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCandidateSuggestions::route('/'),
        ];
    }
}
