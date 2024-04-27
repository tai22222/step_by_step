<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    // user_idはControllerで追加
    protected $fillable = [
      'title',
      'content',
      'category_id',
      'estimated_time',
      'user_id',
      'delete_flg'
    ];

    // プロジェクトを所有するユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // プロジェクトに紐づくステップ(1対多)
    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    // プロジェクトに関連するチャレンジ(1対多)
    public function challenges()
    {
        return $this->hasMany(Challenge::class);
    }

    // プロジェクトのカテゴリ
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    // 進捗を計算しフロント側で{{ projects.progress }}でアクセスできるように
    protected $appends = ['progress'];

    public function getProgressAttribute()
    {
        $totalSteps = $this->steps->count();
        if ($totalSteps === 0) {
            return 0; // ステップが0の場合は進捗0%とする
        }

        $completedSteps = $this->steps->filter(function ($step) {
            return $step->challenges->where('completed_time', null)->isNotEmpty();
        })->count();


        return round(($completedSteps / $totalSteps) * 100, 1);
    }
}
