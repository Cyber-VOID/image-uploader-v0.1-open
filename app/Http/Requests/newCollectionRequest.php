<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class newCollectionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'collection_name' => 'string|required',
            'ispublic' => 'in:1,0|required',
            'imgs' => ['bail', 'required'],
            'imgs.*' => ['bail', 'required', 'image', 'mimes:png,jpg,jpeg', 'max:10240'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
