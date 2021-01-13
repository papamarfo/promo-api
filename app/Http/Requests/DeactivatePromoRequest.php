<?php

namespace App\Http\Requests;

use App\Rules\PromoCodeRule;
use Illuminate\Foundation\Http\FormRequest;

class DeactivatePromoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', new PromoCodeRule],
        ];
    }
}
