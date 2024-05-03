<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Resume;
use App\Models\ResumeGroupItemMapper;
use Illuminate\Support\Facades\Validator;

class ResumeGroupMapper extends Model
{
    use HasFactory;
    protected $table = 'resumes_groups_mappers';

    protected $fillable = [
        'title', 
        'order', 
        'type',
        'resume_id',
    ];

    public function resume(){
        return $this->belongsTo(Resume::class);
    }

    public function groupItemMapper(){
        return $this->hasMany(ResumeGroupItemMapper::class);
    }
}
