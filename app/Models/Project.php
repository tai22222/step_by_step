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
}
