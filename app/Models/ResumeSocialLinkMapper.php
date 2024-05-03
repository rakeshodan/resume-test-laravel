<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Resume;
use Illuminate\Support\Facades\Validator;

class ResumeSocialLinkMapper extends Model
{
    use HasFactory;
    protected $table = 'resumes_social_links_mappers';

    protected $fillable = [
        'url', 
        'fav_icon', 
        'title',
        'order',
        'resume_id',
    ];

    public function resume(){
        return $this->belongsTo(Resume::class);
    }
}
