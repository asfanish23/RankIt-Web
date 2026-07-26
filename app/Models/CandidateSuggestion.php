<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateSuggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'user_id',
        'name',
        'description',
        'status',
    ];

    public function topic()
    {
        return $this->belongsTo(RankingTopic::class, 'topic_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
