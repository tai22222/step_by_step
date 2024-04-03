<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
    ];

    // カテゴリに属するプロジェクト
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
