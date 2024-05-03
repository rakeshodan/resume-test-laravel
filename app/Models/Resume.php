<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resume extends Model
{
    use HasFactory;
    protected $fillable = [
        'profile_url', 
        'name', 
        'role',
        'location',
        'description_title',
        'description_text',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function socialLinkMapper() {
        return $this->hasMany(ResumeSocialLinkMapper::class);
    }

    public function groupMapper() {
        return $this->hasMany(ResumeGroupMapper::class);
    }
    
}
