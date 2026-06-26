<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RankingSubmission;
use App\Models\RankingSubmissionItem;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'topic_id' => 'required|exists:ranking_topics,id',
            'rankings' => 'required|array|min:1',

            'rankings.*.candidate_id' => 'required|exists:ranking_candidates,id',
            'rankings.*.position' => 'required|integer|min:1',
        ]);

        $existingSubmission = RankingSubmission::where('user_id', $request->user_id)
            ->where('topic_id', $request->topic_id)
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'message' => 'You have already submitted a ranking for this topic.'
            ], 409);
        }

        $totalCandidates = count($request->rankings);

        $positions = [];
        $candidateIds = [];

        foreach ($request->rankings as $ranking) {

            if ($ranking['position'] > $totalCandidates) {
                return response()->json([
                    'message' => 'Invalid ranking position.'
                ], 422);
            }

            if (in_array($ranking['position'], $positions)) {
                return response()->json([
                    'message' => 'Duplicate ranking position detected.'
                ], 422);
            }

            $positions[] = $ranking['position'];

            if (in_array($ranking['candidate_id'], $candidateIds)) {
                return response()->json([
                    'message' => 'Duplicate candidate detected.'
                ], 422);
            }

            $candidateIds[] = $ranking['candidate_id'];
        }

        $submission = RankingSubmission::create([
            'user_id' => $request->user_id,
            'topic_id' => $request->topic_id,
        ]);

        foreach ($request->rankings as $ranking) {

            $points = ($totalCandidates + 1) - $ranking['position'];

            RankingSubmissionItem::create([
                'ranking_submission_id' => $submission->id,
                'ranking_candidate_id' => $ranking['candidate_id'],
                'position' => $ranking['position'],
                'points' => $points,
            ]);
        }

        return response()->json([
            'message' => 'Ranking submitted successfully',
            'submission_id' => $submission->id
        ], 201);
    }
}