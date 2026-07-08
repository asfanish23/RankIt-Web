<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\RankingTopic;
use App\Models\User;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Seed 35-40 topics (4 per category here => 40 topics total).
     */
    public function run(): void
    {
        $userIds = User::query()->pluck('id')->all();

        $topicsByCategory = [
            'Football' => [
                ['title' => 'Best Football Player of All Time', 'description' => 'Ranking all-time football legends by impact, skill, and consistency.'],
                ['title' => 'Best Premier League Club', 'description' => 'Comparing Premier League clubs by dominance, trophies, and legacy.'],
                ['title' => 'Greatest Football Manager', 'description' => 'Managers ranked by tactical innovation, silverware, and longevity.'],
                ['title' => 'Best Goalkeeper Ever', 'description' => 'Top goalkeepers ranked by reflexes, consistency, and big-game performances.'],
            ],
            'Movies' => [
                ['title' => 'Best Marvel Movie', 'description' => 'Community ranking of the strongest Marvel movies.'],
                ['title' => 'Best Christopher Nolan Film', 'description' => 'Nolan movies ranked by storytelling, execution, and rewatch value.'],
                ['title' => 'Best Animated Movie', 'description' => 'Animated films ranked by emotion, visuals, and cultural impact.'],
                ['title' => 'Best Sci-Fi Movie', 'description' => 'Sci-fi films ranked by worldbuilding, originality, and influence.'],
            ],
            'Anime' => [
                ['title' => 'Best Shonen Anime', 'description' => 'Most influential and entertaining shonen titles.'],
                ['title' => 'Best Anime Villain', 'description' => 'Villains ranked by writing, motive, and screen presence.'],
                ['title' => 'Best Anime Opening', 'description' => 'Iconic anime openings ranked by music and visuals.'],
                ['title' => 'Best Anime Studio', 'description' => 'Studios ranked by consistency, quality, and iconic works.'],
            ],
            'Gaming' => [
                ['title' => 'Best Open World Game', 'description' => 'Open world games ranked by freedom, depth, and immersion.'],
                ['title' => 'Best RPG', 'description' => 'RPGs ranked by story, systems, and long-term replayability.'],
                ['title' => 'Best Mobile Game', 'description' => 'Mobile games ranked by quality, longevity, and player experience.'],
                ['title' => 'Best Multiplayer Game', 'description' => 'Multiplayer titles ranked by fun factor and competitiveness.'],
            ],
            'Food' => [
                ['title' => 'Best Malaysian Food', 'description' => 'Beloved Malaysian dishes ranked by flavor and popularity.'],
                ['title' => 'Best Dessert', 'description' => 'Classic and modern desserts ranked by crowd appeal.'],
                ['title' => 'Best Fast Food Restaurant', 'description' => 'Fast-food chains ranked by taste, value, and consistency.'],
                ['title' => 'Best Street Food', 'description' => 'Street-food favorites ranked by authenticity and flavor.'],
            ],
            'Cars' => [
                ['title' => 'Best Supercar', 'description' => 'Supercars ranked by design, speed, and engineering excellence.'],
                ['title' => 'Most Reliable Car Brand', 'description' => 'Car brands ranked by long-term reliability and ownership experience.'],
                ['title' => 'Best Electric Vehicle', 'description' => 'EVs ranked by range, performance, and charging ecosystem.'],
                ['title' => 'Best Daily Driver Sedan', 'description' => 'Sedans ranked by comfort, value, and practicality.'],
            ],
            'Travel Destinations' => [
                ['title' => 'Best Beach Destination', 'description' => 'Beach destinations ranked by scenery and traveler satisfaction.'],
                ['title' => 'Best City for Food Tourism', 'description' => 'Food cities ranked by culinary diversity and quality.'],
                ['title' => 'Best Nature Destination', 'description' => 'Nature escapes ranked by landscape, activities, and overall experience.'],
                ['title' => 'Best Honeymoon Destination', 'description' => 'Romantic destinations ranked by atmosphere and memorable experiences.'],
            ],
            'Programming Languages' => [
                ['title' => 'Most Loved Programming Language', 'description' => 'Languages ranked by developer satisfaction and productivity.'],
                ['title' => 'Best Language for Backend', 'description' => 'Backend languages ranked by ecosystem, performance, and maintainability.'],
                ['title' => 'Best Language for Mobile Development', 'description' => 'Languages ranked by mobile tooling and developer experience.'],
                ['title' => 'Best Language for Data Science', 'description' => 'Languages ranked by data ecosystem and real-world usability.'],
            ],
            'Smartphones' => [
                ['title' => 'Best Flagship Smartphone', 'description' => 'Current flagship phones ranked by all-around quality.'],
                ['title' => 'Best Camera Phone', 'description' => 'Phones ranked by photo and video performance.'],
                ['title' => 'Best Budget Smartphone', 'description' => 'Budget phones ranked by value and day-to-day reliability.'],
                ['title' => 'Best Gaming Smartphone', 'description' => 'Phones ranked by sustained gaming performance and thermal management.'],
            ],
            'Universities' => [
                ['title' => 'Best University for Engineering', 'description' => 'Universities ranked by engineering outcomes and research strength.'],
                ['title' => 'Best University in Asia', 'description' => 'Top Asian universities ranked by academics and global reputation.'],
                ['title' => 'Best University Campus Life', 'description' => 'Universities ranked by student life and campus experience.'],
                ['title' => 'Best University for Computer Science', 'description' => 'Universities ranked by CS teaching quality and industry impact.'],
            ],
        ];

        foreach ($topicsByCategory as $categoryName => $topics) {
            $category = Category::query()->where('name', $categoryName)->first();
            if (!$category) {
                continue;
            }

            foreach ($topics as $topic) {
                RankingTopic::query()->create([
                    'category_id' => $category->id,
                    'created_by' => $userIds[array_rand($userIds)],
                    'title' => $topic['title'],
                    'description' => $topic['description'],
                    'visibility' => 'public',
                ]);
            }
        }
    }
}
