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
            Actions\Action::make('generateAiCandidates')
                ->label('Generate Candidates (AI)')
                ->icon('heroicon-o-sparkles')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Generate Candidates with Gemini AI')
                ->modalDescription('This will automatically generate and insert 8 candidate items with descriptions and real images matching this topic using OpenRouter Gemini.')
                ->action(function () {
                    $topic = $this->record;
                    
                    $openrouterKey = env('OPENROUTER_API_KEY');
                    if (empty($openrouterKey)) {
                        \Filament\Notifications\Notification::make()
                            ->title('OpenRouter Key is missing in .env')
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    $response = \Illuminate\Support\Facades\Http::withToken($openrouterKey)
                        ->withHeaders([
                            'HTTP-Referer' => 'http://127.0.0.1:8000',
                            'X-Title' => 'RankIt Admin',
                        ])
                        ->post('https://openrouter.ai/api/v1/chat/completions', [
                            'model' => 'google/gemini-2.5-flash-lite',
                            'messages' => [
                                [
                                    'role' => 'user',
                                    'content' => "Generate exactly 8 popular candidate items for the ranking topic: \"{$topic->title}\". Description: \"{$topic->description}\".
Return ONLY a valid JSON array of objects. Do not include markdown code block wrappers (like ```json), just return the raw JSON array string.
Each object in the array MUST have the following keys:
- 'name': the name of the candidate item.
- 'description': a short description of this item.
- 'unsplash_query': a specific 2-3 word keyword search query to fetch a photo of this item on Unsplash (e.g. \"margherita pizza\" or \"wasabi sushi\").",
                                ]
                            ],
                        ]);
                        
                    if (!$response->successful()) {
                        \Filament\Notifications\Notification::make()
                            ->title('API request failed: ' . $response->status())
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    $content = $response->json('choices.0.message.content');
                    $content = preg_replace('/^```(?:json)?|```$/m', '', trim($content));
                    $candidates = json_decode($content, true);
                    
                    if (!is_array($candidates)) {
                        \Filament\Notifications\Notification::make()
                            ->title('Invalid AI JSON response')
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    $unsplashKey = env('UNSPLASH_ACCESS_KEY');
                    
                    foreach ($candidates as $cand) {
                        $name = $cand['name'] ?? '';
                        $desc = $cand['description'] ?? '';
                        $query = $cand['unsplash_query'] ?? $name;
                        
                        $imageUrl = null;

                        // 1. Try Wikipedia PageImages API via Search Generator (highly accurate, case-insensitive, fuzzy matching)
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
                        
                        // 2. Fallback to Unsplash
                        if (empty($imageUrl) && $unsplashKey) {
                            try {
                                $uRes = \Illuminate\Support\Facades\Http::get("https://api.unsplash.com/search/photos", [
                                    'query' => $query,
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
                        
                        // Insert into database
                        \App\Models\RankingCandidate::create([
                            'topic_id' => $topic->id,
                            'name' => $name,
                            'description' => $desc,
                            'image_url' => $imageUrl,
                        ]);
                    }
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Successfully generated 8 candidate items!')
                        ->success()
                        ->send();
                        
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $topic->id]));
                }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
