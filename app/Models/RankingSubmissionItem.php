<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingSubmissionItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'ranking_submission_id',
        'ranking_candidate_id',
        'position',
        'points'
    ];

    public function submission()
    {
        return $this->belongsTo(
            RankingSubmission::class,
            'ranking_submission_id'
        );
    }

    public function candidate()
    {
        return $this->belongsTo(
            RankingCandidate::class,
            'ranking_candidate_id'
        );
    }
}
