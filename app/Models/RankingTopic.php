<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingTopic extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'created_by',
        'title',
        'description',
        'visibility'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function candidates()
    {
        return $this->hasMany(RankingCandidate::class, 'topic_id');
    }

    public function submissions()
    {
        return $this->hasMany(RankingSubmission::class, 'topic_id');
    }
}