<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Adoption extends Model
{
    protected $fillable = ['user_id', 'curriculum_id', 'file_path', 'approval_score', 'feedback'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function institute()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
