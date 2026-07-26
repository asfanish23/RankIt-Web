<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CandidateSuggestion;
use App\Models\RankingTopic;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function store(Request $request, $topicId)
    {
        $topic = RankingTopic::findOrFail($topicId);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $suggestion = CandidateSuggestion::create([
            'topic_id' => $topic->id,
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Candidate suggestion submitted successfully!',
            'suggestion' => $suggestion
        ], 201);
    }
}
