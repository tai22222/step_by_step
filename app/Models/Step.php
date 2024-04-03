<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;

    // user_idとproject_idはcontrollerで追加
    protected $fillable = [
      'title',
      'content',
      'estimated_time',
    ];

    // ステップが属するプロジェクト
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
