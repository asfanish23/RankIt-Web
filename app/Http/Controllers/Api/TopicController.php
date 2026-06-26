<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RankingTopic;

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
}