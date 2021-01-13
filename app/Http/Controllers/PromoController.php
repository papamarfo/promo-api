<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeactivatePromoRequest;
use App\Http\Requests\StorePromoRequest;
use App\Http\Requests\VerifyPromoRequest;
use App\Http\Resources\PromoResource;
use App\Http\Resources\VerifiedPromoResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Promo;

class PromoController extends Controller
{
    public function index(Event $event)
    {
        $promos = $event->promos()->paginate();

        return PromoResource::collection($promos);
    }

    public function store(StorePromoRequest $request, Event $event)
    {
        $promo = $event->promos()->create($request->validated());

        return new PromoResource($promo);
    }

    public function deactivate(DeactivatePromoRequest $request)
    {
        $promo = Promo::query()->where('code', $request->validated()['code'])->firstOrFail();

        $promo->update([
            'active' => false,
        ]);

        return response()->noContent();
    }

    public function active(Event $event)
    {
        $activePromos = $event->promos()->getQuery()->where('active', true)->paginate();

        return PromoResource::collection($activePromos);
    }

    public function verify(VerifyPromoRequest $request)
    {
        $validated = $request->validated();

        $promo = Promo::query()->where('code', $validated['code'])->firstOrFail();

        if (!$promo->active) {
            return response()->json(['inactive' => 'Promo code has been deactivated.']);
        }

        if ($promo->isExpired()) {
            return response()->json(['expired' => 'Promo code has expired since event has ended.']);
        }

        if (!$promo->withinRadius($validated['origin']) && !$promo->withinRadius($validated['destination'])) {
            return response()->json(['radius' => 'You are not within the radius of the event.']);
        }

        $polyline = $promo->createPolyline($validated['origin'], $validated['destination']);
        
        return new VerifiedPromoResource($promo, $polyline);
    }
}
