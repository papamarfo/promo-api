<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VerifiedPromoResource extends JsonResource
{
    protected $polyline;

    public function __construct($resource, $polyline)
    {
        $this->polyline = $polyline;
        
        parent::__construct($resource);
    }

    public function toArray($request)
    {
        return [
            'promo' => [
                'id' => $this->id,
                'code' => $this->code,
                'amount' => $this->amount,
                'active' => $this->active,
                'radius' => $this->radius,
                'event' => $this->event,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'polyline' => $this->polyline,
        ];
    }
}
