<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RankingTopic;
use App\Models\RankingCandidate;
use App\Models\RankingSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
    public function index()
    {
        return RankingTopic::with('category')->withCount('candidates')->get();
    }

    public function show(Request $request, $id)
    {
        $topic = RankingTopic::findOrFail($id);

        $candidates = \App\Models\RankingCandidate::select(
                'ranking_candidates.*',
                DB::raw('COALESCE(SUM(ranking_submission_items.points), 0) as total_points'),
                DB::raw('COUNT(ranking_submission_items.id) as votes_count')
            )
            ->leftJoin('ranking_submission_items', 'ranking_candidates.id', '=', 'ranking_submission_items.ranking_candidate_id')
            ->where('ranking_candidates.topic_id', $id)
            ->groupBy(
                'ranking_candidates.id',
                'ranking_candidates.topic_id',
                'ranking_candidates.name',
                'ranking_candidates.description',
                'ranking_candidates.image_url',
                'ranking_candidates.created_at',
                'ranking_candidates.updated_at'
            )
            ->orderByDesc('total_points')
            ->get();

        $result = $topic->toArray();
        $result['candidates'] = $candidates->toArray();
        $result['user_ranking'] = [];

        if ($request->has('user_id')) {
            $submission = RankingSubmission::where('topic_id', $id)
                ->where('user_id', $request->user_id)
                ->with('items')
                ->first();

            if ($submission) {
                $result['user_ranking'] = $submission->items
                    ->map(fn($item) => [
                        'candidate_id' => $item->ranking_candidate_id,
                        'position' => $item->position,
                    ])
                    ->values()
                    ->toArray();
            }
        }

        return $result;
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'created_by' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'nullable|in:public,private',
        ]);

        $topic = RankingTopic::create([
            'category_id' => $request->category_id,
            'created_by' => $request->created_by,
            'title' => $request->title,
            'description' => $request->description,
            'visibility' => $request->visibility ?? 'public',
        ]);

        return response()->json($topic->load('category'), 201);
    }

    public function addCandidate(Request $request, $id)
    {
        $topic = RankingTopic::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
        ]);

        $candidate = RankingCandidate::create([
            'topic_id' => $topic->id,
            'name' => $request->name,
            'description' => $request->description,
            'image_url' => $request->image_url,
        ]);

        return response()->json($candidate, 201);
    }
}