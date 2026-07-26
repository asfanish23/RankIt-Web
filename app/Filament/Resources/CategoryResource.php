<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make('Basic Details')
                            ->description('Configure the category name and descriptions visible to users.')
                            ->icon('heroicon-o-document-text')
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Category Name')
                                    ->placeholder('e.g., Japanese Food, Sci-Fi Movies')
                                    ->prefixIcon('heroicon-o-tag')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Textarea::make('description')
                                    ->label('Description')
                                    ->placeholder('Briefly describe this category...')
                                    ->rows(4),
                            ]),

                        Forms\Components\Section::make('Category Cover Media')
                            ->description('Provide a stock cover photo or upload your own.')
                            ->icon('heroicon-o-photo')
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\TextInput::make('image_url')
                                    ->label('Cover Photo URL')
                                    ->placeholder('https://images.unsplash.com/...')
                                    ->maxLength(255)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state) => $state)
                                    ->suffixActions([
                                        Forms\Components\Actions\Action::make('searchWikipedia')
                                            ->icon('heroicon-o-globe-alt')
                                            ->tooltip('Search Wikipedia for photo')
                                            ->action(function (Forms\Get $get, Forms\Set $set) {
                                                $name = $get('name');
                                                if (empty($name)) {
                                                    \Filament\Notifications\Notification::make()
                                                        ->title('Please enter the Category Name first')
                                                        ->warning()
                                                        ->send();
                                                    return;
                                                }

                                                $imageUrl = null;
                                                try {
                                                    $wRes = \Illuminate\Support\Facades\Http::withHeaders([
                                                        'User-Agent' => 'RankItApp/1.0 (contact@rankit.com) PHP/8.1'
                                                    ])->get("https://en.wikipedia.org/w/api.php", [
                                                        'action' => 'query',
                                                        'generator' => 'search',
                                                        'gsrsearch' => $name,
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

                                                if ($imageUrl) {
                                                    $set('image_url', $imageUrl);
                                                    \Filament\Notifications\Notification::make()
                                                        ->title('Wikipedia photo found!')
                                                        ->success()
                                                        ->send();
                                                } else {
                                                    \Filament\Notifications\Notification::make()
                                                        ->title("No Wikipedia photo found for '{$name}'")
                                                        ->danger()
                                                        ->send();
                                                }
                                            }),
                                        Forms\Components\Actions\Action::make('searchUnsplash')
                                            ->icon('heroicon-m-magnifying-glass')
                                            ->tooltip('Search Unsplash for image')
                                            ->modalHeading('Search Unsplash')
                                            ->modalSubmitAction(false)
                                            ->modalCancelAction(false)
                                            ->modalContent(fn ($component) => view('filament.unsplash-search', [
                                                'statePath' => $component->getStatePath(),
                                                'name' => $component->getContainer()->getState()['name'] ?? '',
                                            ])),
                                        Forms\Components\Actions\Action::make('browseCloudinary')
                                            ->icon('heroicon-o-folder-open')
                                            ->tooltip('Browse Cloudinary Media Library')
                                            ->modalHeading('Browse Cloudinary')
                                            ->modalSubmitAction(false)
                                            ->modalCancelAction(false)
                                            ->modalContent(function ($component) {
                                                $timestamp = time();
                                                $apiKey = env('CLOUDINARY_API_KEY');
                                                $apiSecret = env('CLOUDINARY_API_SECRET');
                                                $cloudName = env('CLOUDINARY_CLOUD_NAME');
                                                $signature = sha1("timestamp=" . $timestamp . $apiSecret);
                                                
                                                return view('filament.cloudinary-browser', [
                                                    'statePath' => $component->getStatePath(),
                                                    'cloudName' => $cloudName,
                                                    'apiKey' => $apiKey,
                                                    'timestamp' => $timestamp,
                                                    'signature' => $signature,
                                                ]);
                                            }),
                                        Forms\Components\Actions\Action::make('clearImage')
                                            ->icon('heroicon-o-trash')
                                            ->color('danger')
                                            ->tooltip('Remove Photo')
                                            ->action(fn (Forms\Set $set) => $set('image_url', null)),
                                    ]),
                                Forms\Components\FileUpload::make('temp_upload')
                                    ->label('Or Upload Image')
                                    ->image()
                                    ->dehydrated(false)
                                    ->reactive()
                                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                                        if (empty($state)) return;
                                        
                                        $file = is_array($state) ? reset($state) : $state;
                                        
                                        if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                                            $realPath = $file->getRealPath();
                                            $mimeType = $file->getMimeType();
                                            $originalName = $file->getClientOriginalName();
                                        } else if (is_string($file)) {
                                            $realPath = \Illuminate\Support\Facades\Storage::disk('local')->path($file);
                                            $mimeType = mime_content_type($realPath) ?: 'image/jpeg';
                                            $originalName = basename($realPath);
                                        } else {
                                            return;
                                        }

                                        $cloudName = env('CLOUDINARY_CLOUD_NAME');
                                        $uploadPreset = env('CLOUDINARY_UPLOAD_PRESET');
                                        
                                        if (empty($cloudName) || empty($uploadPreset)) {
                                            return;
                                        }
                                        
                                        try {
                                            $ch = curl_init();
                                            curl_setopt($ch, CURLOPT_URL, "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload");
                                            curl_setopt($ch, CURLOPT_POST, true);
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                            
                                            $postFields = [
                                                'upload_preset' => $uploadPreset,
                                                'file' => new \CURLFile($realPath, $mimeType, $originalName),
                                            ];
                                            
                                            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                                            $response = curl_exec($ch);
                                            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                            curl_close($ch);
                                            
                                            if ($statusCode === 200) {
                                                $resData = json_decode($response, true);
                                                if (!empty($resData['secure_url'])) {
                                                    $set('image_url', $resData['secure_url']);
                                                    
                                                    \Filament\Notifications\Notification::make()
                                                        ->title('Uploaded to Cloudinary successfully!')
                                                        ->success()
                                                        ->send();
                                                }
                                            }
                                        } catch (\Exception $e) {}
                                    })
                                    ->saveUploadedFileUsing(function ($file) {
                                        return 'cloudinary-uploaded.jpg';
                                    }),

                                Forms\Components\Placeholder::make('image_preview')
                                    ->label('Cover Photo Preview')
                                    ->content(fn ($get) => $get('image_url') 
                                        ? new \Illuminate\Support\HtmlString('<div class="mt-2"><img src="' . e($get('image_url')) . '" class="w-full rounded-lg shadow-md" style="max-height: 180px; object-fit: cover;" /></div>') 
                                        : new \Illuminate\Support\HtmlString('<div class="mt-2 text-sm text-gray-500 italic">No image preview available</div>')),
                                

                            ]),
                    ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50),

                Tables\Columns\TextColumn::make('image_url')
                    ->limit(40),

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
            RelationManagers\TopicsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
