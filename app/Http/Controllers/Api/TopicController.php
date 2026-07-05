<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RankingTopic;
use App\Models\RankingCandidate;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index()
    {
        return RankingTopic::with('category')->get();
    }

    public function show($id)
    {
        return RankingTopic::with('candidates')->findOrFail($id);
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