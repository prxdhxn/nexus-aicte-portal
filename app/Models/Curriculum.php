<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Curriculum extends Model
{
    protected $fillable = ['title', 'description', 'deadline', 'sme_id', 'tags', 'versions'];

    protected $casts = [
        'deadline' => 'datetime',
        'versions' => 'array',
    ];

    public function sme()
    {
        return $this->belongsTo(User::class, 'sme_id');
    }

    public function adoptions()
    {
        return $this->hasMany(Adoption::class);
    }

    public function saveVersion(string $changedBy): void
    {
        $versions = $this->versions ?? [];
        $versions[] = [
            'title'       => $this->title,
            'description' => $this->description,
            'deadline'    => $this->deadline?->toIso8601String(),
            'saved_at'    => now()->toIso8601String(),
            'saved_by'    => $changedBy,
            'version_num' => count($versions) + 1,
        ];
        $this->update(['versions' => $versions]);
    }
}
