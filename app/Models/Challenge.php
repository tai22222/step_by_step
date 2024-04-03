<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
      'completed_time',
      'status',
    ];

    // チャレンジを行うユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // チャレンジが関連するプロジェクト
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
}
