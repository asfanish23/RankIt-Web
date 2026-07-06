<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RankingSubmission;
use App\Models\RankingSubmissionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'topic_id' => 'required|exists:ranking_topics,id',
            'rankings' => 'required|array|min:2',
            'rankings.*.candidate_id' => 'required|exists:ranking_candidates,id',
            'rankings.*.position' => 'required|integer|min:1',
        ]);

        $totalSelected = count($request->rankings);
        $positions = [];
        $candidateIds = [];

        foreach ($request->rankings as $ranking) {
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

        sort($positions);
        $expectedPositions = range(1, $totalSelected);

        if ($positions !== $expectedPositions) {
            return response()->json([
                'message' => 'Positions must start from 1 and be continuous (1, 2, 3...).'
            ], 422);
        }

        $isUpdate = false;

        $submission = DB::transaction(function () use ($request, $totalSelected, &$isUpdate) {
            $existingSubmission = RankingSubmission::where('user_id', $request->user_id)
                ->where('topic_id', $request->topic_id)
                ->first();

            if ($existingSubmission) {
                $isUpdate = true;
                $existingSubmission->items()->delete();
                $existingSubmission->delete();
            }

            $submission = RankingSubmission::create([
                'user_id' => $request->user_id,
                'topic_id' => $request->topic_id,
            ]);

            foreach ($request->rankings as $ranking) {
                $points = ($totalSelected + 1) - $ranking['position'];

                RankingSubmissionItem::create([
                    'ranking_submission_id' => $submission->id,
                    'ranking_candidate_id' => $ranking['candidate_id'],
                    'position' => $ranking['position'],
                    'points' => $points,
                ]);
            }

            return $submission;
        });

        return response()->json([
            'message' => $isUpdate ? 'Ranking updated successfully' : 'Ranking submitted successfully',
            'submission_id' => $submission->id
        ], 201);
    }
}
