<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingCandidate extends Model
{
    use HasFactory;
    protected $fillable = [
        'topic_id',
        'name',
        'description',
        'image_url'
    ];

    public function topic()
    {
        return $this->belongsTo(RankingTopic::class, 'topic_id');
    }

    public function submissionItems()
    {
        return $this->hasMany(
            RankingSubmissionItem::class,
            'ranking_candidate_id'
        );
    }
}
