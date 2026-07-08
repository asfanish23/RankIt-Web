<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed ~10 realistic categories for demo purposes.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Football',
                'description' => 'Legends, clubs, tactics, iconic eras, and football culture.',
                'image_url' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&q=80&w=900',
            ],
            [
                'name' => 'Movies',
                'description' => 'Blockbusters, directors, classics, and fan-favorite films.',
                'image_url' => 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&q=80&w=900',
            ],
            [
                'name' => 'Anime',
                'description' => 'Shonen hits, iconic villains, openings, and unforgettable arcs.',
                'image_url' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?auto=format&fit=crop&q=80&w=900',
            ],
            [
                'name' => 'Gaming',
                'description' => 'Open worlds, RPGs, mobile hits, and timeless franchises.',
                'image_url' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&q=80&w=900',
            ],
            [
                'name' => 'Food',
                'description' => 'Regional specialties, comfort dishes, desserts, and street food.',
                'image_url' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80&w=900',
            ],
            [
                'name' => 'Cars',
                'description' => 'Supercars, reliability, EVs, and favorite automotive brands.',
                'image_url' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&q=80&w=900',
            ],
            [
                'name' => 'Travel Destinations',
                'description' => 'Beaches, nature escapes, food cities, and bucket-list trips.',
                'image_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&q=80&w=900',
            ],
            [
                'name' => 'Programming Languages',
                'description' => 'Most used, most loved, backend, and mobile development languages.',
                'image_url' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&q=80&w=900',
            ],
            [
                'name' => 'Smartphones',
                'description' => 'Flagships, camera phones, budget options, and ecosystem battles.',
                'image_url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&q=80&w=900',
            ],
            [
                'name' => 'Universities',
                'description' => 'Top campuses for engineering, global reputation, and student life.',
                'image_url' => 'https://images.unsplash.com/photo-1498243691581-b145c3f54a5a?auto=format&fit=crop&q=80&w=900',
            ],
        ];

        foreach ($categories as $category) {
            Category::query()->create($category);
        }
    }
}
