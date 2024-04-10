<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
      logger("==========ProfileUpdateRequest=============");
      logger($this->all());
      logger("==========ProfileUpdateRequest=============");

        return [
            'name' => ['string', 'max:50'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'icon_image' => ['nullable', 'mimetypes:image/jpeg,image/png', 'max:1024'],
            'introduction' => ['nullable', 'string', 'max:500'],
        ];
    }
}
