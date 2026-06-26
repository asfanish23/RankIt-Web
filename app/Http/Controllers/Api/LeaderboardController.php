<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RankingSubmissionItem;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function show($topicId)
    {
        $leaderboard = RankingSubmissionItem::select(
                'ranking_candidates.name',
                DB::raw('SUM(points) as total_points')
            )
            ->join(
                'ranking_candidates',
                'ranking_submission_items.ranking_candidate_id',
                '=',
                'ranking_candidates.id'
            )
            ->join(
                'ranking_submissions',
                'ranking_submission_items.ranking_submission_id',
                '=',
                'ranking_submissions.id'
            )
            ->where('ranking_submissions.topic_id', $topicId)
            ->groupBy('ranking_candidates.id', 'ranking_candidates.name')
            ->orderByDesc('total_points')
            ->get();

        return response()->json($leaderboard);
    }
}