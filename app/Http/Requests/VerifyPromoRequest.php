<?php

namespace App\Http\Requests;

use App\Rules\PromoCodeRule;
use Illuminate\Foundation\Http\FormRequest;

class VerifyPromoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', new PromoCodeRule],
            'origin.lat' => ['required', 'numeric',],
            'origin.lng' => ['required', 'numeric',],
            'destination.lat' => ['required', 'numeric',],
            'destination.lng' => ['required', 'numeric',],
        ];
    }
}
