<?php

namespace Database\Seeders;

use App\Models\RankingSubmission;
use App\Models\RankingSubmissionItem;
use App\Models\RankingTopic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubmissionSeeder extends Seeder
{
    /**
     * Seed realistic rankings while preserving ranking rules and Borda points logic.
     */
    public function run(): void
    {
        $users = User::query()->get(['id']);
        $topics = RankingTopic::query()->with('candidates:id,topic_id')->get();

        if ($users->isEmpty() || $topics->isEmpty()) {
            return;
        }

        // Targets 400-600 submissions with 30 users and 40 topics.
        // 15 topics per user => 450 submissions total.
        $topicsPerUser = 15;

        DB::transaction(function () use ($users, $topics, $topicsPerUser): void {
            foreach ($users as $user) {
                $selectedTopics = $topics->shuffle()->take($topicsPerUser);

                foreach ($selectedTopics as $topic) {
                    $candidateIds = $topic->candidates->pluck('id')->all();
                    $candidateCount = count($candidateIds);

                    // Skip malformed topics safely.
                    if ($candidateCount < 3) {
                        continue;
                    }

                    // Ranking size between 3 and all available candidates.
                    $selectedCount = random_int(3, $candidateCount);

                    // Ensure unique candidates and randomized order.
                    shuffle($candidateIds);
                    $rankedCandidateIds = array_slice($candidateIds, 0, $selectedCount);

                    $submission = RankingSubmission::query()->create([
                        'user_id' => $user->id,
                        'topic_id' => $topic->id,
                    ]);

                    // Same point logic as SubmissionController:
                    // points = (totalSelected + 1) - position
                    foreach ($rankedCandidateIds as $index => $candidateId) {
                        $position = $index + 1;
                        $points = ($selectedCount + 1) - $position;

                        RankingSubmissionItem::query()->create([
                            'ranking_submission_id' => $submission->id,
                            'ranking_candidate_id' => $candidateId,
                            'position' => $position,
                            'points' => $points,
                        ]);
                    }
                }
            }
        });
    }
}
