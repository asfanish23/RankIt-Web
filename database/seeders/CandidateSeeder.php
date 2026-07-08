<?php

namespace Database\Seeders;

use App\Models\RankingCandidate;
use App\Models\RankingTopic;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    /**
     * Seed realistic candidates (6-10 per topic) to reach ~320 candidates total.
     */
    public function run(): void
    {
        $topicCandidates = [
            'Best Football Player of All Time' => ['Lionel Messi', 'Cristiano Ronaldo', 'Diego Maradona', 'Pele', 'Zinedine Zidane', 'Ronaldinho', 'Ronaldo Nazario', 'Johan Cruyff'],
            'Best Premier League Club' => ['Manchester United', 'Manchester City', 'Liverpool', 'Arsenal', 'Chelsea', 'Tottenham Hotspur', 'Leicester City', 'Newcastle United'],
            'Greatest Football Manager' => ['Sir Alex Ferguson', 'Pep Guardiola', 'Carlo Ancelotti', 'Jose Mourinho', 'Arsene Wenger', 'Johan Cruyff', 'Jurgen Klopp', 'Arrigo Sacchi'],
            'Best Goalkeeper Ever' => ['Gianluigi Buffon', 'Iker Casillas', 'Manuel Neuer', 'Petr Cech', 'Edwin van der Sar', 'Lev Yashin', 'Alisson Becker', 'Thibaut Courtois'],

            'Best Marvel Movie' => ['Avengers: Endgame', 'Avengers: Infinity War', 'Captain America: The Winter Soldier', 'Spider-Man: No Way Home', 'Black Panther', 'Guardians of the Galaxy', 'Iron Man', 'Thor: Ragnarok'],
            'Best Christopher Nolan Film' => ['The Dark Knight', 'Inception', 'Interstellar', 'Oppenheimer', 'Memento', 'The Prestige', 'Dunkirk', 'Batman Begins'],
            'Best Animated Movie' => ['Spirited Away', 'Your Name', 'Toy Story 3', 'Coco', 'Into the Spider-Verse', 'How to Train Your Dragon', 'The Lion King', 'Up'],
            'Best Sci-Fi Movie' => ['Interstellar', 'Blade Runner 2049', 'The Matrix', 'Arrival', 'Dune', 'Inception', '2001: A Space Odyssey', 'Star Wars: The Empire Strikes Back'],

            'Best Shonen Anime' => ['One Piece', 'Naruto', 'Bleach', 'Attack on Titan', 'Fullmetal Alchemist: Brotherhood', 'Hunter x Hunter', 'Jujutsu Kaisen', 'Demon Slayer'],
            'Best Anime Villain' => ['Johan Liebert', 'Madara Uchiha', 'Sosuke Aizen', 'Meruem', 'Frieza', 'Griffith', 'Dio Brando', 'Hisoka'],
            'Best Anime Opening' => ['Gurenge', 'Unravel', 'Silhouette', 'Kaikai Kitan', 'The Rumbling', 'Blue Bird', 'Again', 'A Cruel Angel\'s Thesis'],
            'Best Anime Studio' => ['Studio Ghibli', 'MAPPA', 'Ufotable', 'Wit Studio', 'Bones', 'Madhouse', 'Kyoto Animation', 'A-1 Pictures'],

            'Best Open World Game' => ['The Witcher 3', 'Elden Ring', 'Red Dead Redemption 2', 'The Legend of Zelda: Breath of the Wild', 'Grand Theft Auto V', 'Skyrim', 'Horizon Forbidden West', 'Cyberpunk 2077'],
            'Best RPG' => ['Baldur\'s Gate 3', 'The Witcher 3', 'Persona 5 Royal', 'Final Fantasy VII Rebirth', 'Divinity: Original Sin 2', 'Skyrim', 'Chrono Trigger', 'Mass Effect 2'],
            'Best Mobile Game' => ['Mobile Legends: Bang Bang', 'PUBG Mobile', 'Genshin Impact', 'Call of Duty: Mobile', 'Clash Royale', 'Pokemon GO', 'Honkai: Star Rail', 'Among Us'],
            'Best Multiplayer Game' => ['Valorant', 'Counter-Strike 2', 'League of Legends', 'Dota 2', 'Fortnite', 'Overwatch 2', 'Apex Legends', 'Rocket League'],

            'Best Malaysian Food' => ['Nasi Lemak', 'Char Kway Teow', 'Roti Canai', 'Satay', 'Laksa', 'Nasi Kandar', 'Mee Goreng', 'Ayam Percik'],
            'Best Dessert' => ['Cheesecake', 'Tiramisu', 'Brownie Sundae', 'Pavlova', 'Creme Brulee', 'Mango Sticky Rice', 'Chocolate Lava Cake', 'Ice Cream'],
            'Best Fast Food Restaurant' => ['McDonald\'s', 'KFC', 'Burger King', 'Subway', 'Five Guys', 'A&W', 'Texas Chicken', 'Domino\'s Pizza'],
            'Best Street Food' => ['Satay', 'Taco', 'Kebab', 'Takoyaki', 'Rojak', 'Hot Dog', 'Corn Dog', 'Chicken Rice Ball'],

            'Best Supercar' => ['Ferrari SF90', 'Lamborghini Revuelto', 'McLaren 750S', 'Porsche 911 Turbo S', 'Bugatti Chiron', 'Koenigsegg Jesko', 'Aston Martin Valkyrie', 'Pagani Huayra'],
            'Most Reliable Car Brand' => ['Toyota', 'Honda', 'Lexus', 'Mazda', 'Subaru', 'Hyundai', 'Kia', 'Volvo'],
            'Best Electric Vehicle' => ['Tesla Model 3', 'Tesla Model Y', 'Hyundai Ioniq 5', 'Kia EV6', 'Porsche Taycan', 'BYD Seal', 'BMW i4', 'Mercedes EQE'],
            'Best Daily Driver Sedan' => ['Toyota Camry', 'Honda Accord', 'Mazda 6', 'Hyundai Sonata', 'Volkswagen Passat', 'BMW 3 Series', 'Mercedes C-Class', 'Audi A4'],

            'Best Beach Destination' => ['Bali', 'Maldives', 'Phuket', 'Boracay', 'Langkawi', 'Santorini', 'Maui', 'Palawan'],
            'Best City for Food Tourism' => ['Tokyo', 'Bangkok', 'Penang', 'Istanbul', 'Seoul', 'Barcelona', 'Singapore', 'Osaka'],
            'Best Nature Destination' => ['Banff', 'Swiss Alps', 'Iceland', 'New Zealand South Island', 'Patagonia', 'Yosemite', 'Jeju Island', 'Lake Bled'],
            'Best Honeymoon Destination' => ['Maldives', 'Santorini', 'Bali', 'Kyoto', 'Paris', 'Phuket', 'Swiss Alps', 'Lake Como'],

            'Most Loved Programming Language' => ['Python', 'TypeScript', 'Go', 'Rust', 'JavaScript', 'Kotlin', 'Java', 'Dart'],
            'Best Language for Backend' => ['Go', 'Java', 'C#', 'Node.js', 'Python', 'Rust', 'PHP', 'Kotlin'],
            'Best Language for Mobile Development' => ['Dart', 'Kotlin', 'Swift', 'TypeScript', 'Java', 'C#', 'JavaScript', 'Objective-C'],
            'Best Language for Data Science' => ['Python', 'R', 'Julia', 'Scala', 'MATLAB', 'SQL', 'Java', 'Rust'],

            'Best Flagship Smartphone' => ['iPhone 17 Pro', 'Samsung Galaxy S26 Ultra', 'Google Pixel 10 Pro', 'OnePlus 14', 'Xiaomi 16 Pro', 'HONOR Magic 8 Pro', 'OPPO Find X9 Pro', 'Vivo X200 Pro'],
            'Best Camera Phone' => ['Samsung Galaxy S26 Ultra', 'Google Pixel 10 Pro', 'iPhone 17 Pro Max', 'Xiaomi 16 Ultra', 'Vivo X200 Pro', 'OPPO Find X9 Pro', 'Huawei P70 Pro', 'Sony Xperia 1 VII'],
            'Best Budget Smartphone' => ['Redmi Note 15 Pro', 'Samsung Galaxy A56', 'Nothing Phone 3a', 'POCO X8', 'realme GT Neo 8', 'Moto G Power 2026', 'Infinix Zero', 'Tecno Camon'],
            'Best Gaming Smartphone' => ['ASUS ROG Phone 10', 'RedMagic 11', 'iPhone 17 Pro', 'Samsung Galaxy S26 Ultra', 'Xiaomi Black Shark 7', 'OnePlus 14', 'iQOO 14', 'Lenovo Legion Phone 4'],

            'Best University for Engineering' => ['MIT', 'Stanford University', 'Caltech', 'ETH Zurich', 'NUS', 'Tsinghua University', 'Imperial College London', 'Georgia Tech'],
            'Best University in Asia' => ['NUS', 'Tsinghua University', 'Peking University', 'University of Tokyo', 'HKU', 'KAIST', 'NTU Singapore', 'Seoul National University'],
            'Best University Campus Life' => ['UCLA', 'University of Melbourne', 'University of British Columbia', 'University of Manchester', 'University of Sydney', 'Nanyang Technological University', 'Monash University', 'University of Toronto'],
            'Best University for Computer Science' => ['MIT', 'Stanford University', 'Carnegie Mellon University', 'UC Berkeley', 'ETH Zurich', 'NUS', 'University of Oxford', 'National Taiwan University'],
        ];

        $topics = RankingTopic::query()->get();

        foreach ($topics as $topic) {
            $candidateNames = $topicCandidates[$topic->title] ?? [];
            if (empty($candidateNames)) {
                continue;
            }

            foreach ($candidateNames as $name) {
                RankingCandidate::query()->create([
                    'topic_id' => $topic->id,
                    'name' => $name,
                    'description' => $name . ' is a strong contender in this community ranking topic.',
                    'image_url' => 'https://picsum.photos/seed/' . urlencode(strtolower(str_replace(' ', '-', $topic->title . '-' . $name))) . '/600/600?blur=0',
                ]);
            }
        }
    }
}
