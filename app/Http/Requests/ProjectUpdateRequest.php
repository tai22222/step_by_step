<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
      logger("==========ProjectUpdateRequest=============");
      logger($this->all());
      logger("==========ProjectUpdateRequest=============");

      return [
        // プロジェクト関連のバリデーションルール
        'project.title' => 'required|string|max:255',
        'project.category_id' => 'required|integer|min:0',
        'project.content' => 'required|string',
        'project.estimated_time' => 'required|integer|min:0',
        
        // ステップ関連のバリデーションルール
        // 'steps' は必須、配列である必要がある
        'steps' => 'required|array',
        'steps.*.title' => 'required|string|max:255',
        'steps.*.content' => 'required|string',
        'steps.*.estimated_time' => 'required|integer|min:0',
      ];
    }
}
