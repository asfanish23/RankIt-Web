<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RankingSubmissionItem;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function show($topicId)
    {
        $leaderboard = \App\Models\RankingCandidate::select(
                'ranking_candidates.id as candidate_id',
                'ranking_candidates.name',
                'ranking_candidates.description',
                'ranking_candidates.image_url',
                DB::raw('COALESCE(SUM(ranking_submission_items.points), 0) as total_points'),
                DB::raw('COUNT(ranking_submission_items.id) as votes_count')
            )
            ->leftJoin('ranking_submission_items', 'ranking_candidates.id', '=', 'ranking_submission_items.ranking_candidate_id')
            ->where('ranking_candidates.topic_id', $topicId)
            ->groupBy(
                'ranking_candidates.id',
                'ranking_candidates.name',
                'ranking_candidates.description',
                'ranking_candidates.image_url'
            )
            ->orderByDesc('total_points')
            ->get();

        return response()->json($leaderboard);
    }
}