<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingSubmission extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'topic_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(
            RankingTopic::class,
            'topic_id'
        );
    }

    public function items()
    {
        return $this->hasMany(
            RankingSubmissionItem::class,
            'ranking_submission_id'
        );
    }
}