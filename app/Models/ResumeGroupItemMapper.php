<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class ResumeGroupItemMapper extends Model
{
    use HasFactory;
    protected $table = 'resumes_groups_items_mappers';

    protected $fillable = [
        'title', 
        'subtitle_1', 
        'subtitle_2',
        'order',
        'description',
        'resume_group_mapper_id'
    ];
}
