<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Promo;
use Illuminate\Support\Arr;
use App\Http\Resources\PromoResource;
use App\Http\Requests\StorePromoRequest;
use App\Http\Controllers\PromoController;
use App\Http\Requests\VerifyPromoRequest;
use App\Http\Resources\VerifiedPromoResource;
use App\Rules\PromoCodeRule;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PromoControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_index_returns_paginated_promos_with_recently_created_last()
    {
        $event = Event::factory()->has(Promo::factory()->count(50))->create();

        $oldPromos = Promo::query()->oldest()->take(15)->get()->map(function ($value) {
            return json_decode((new PromoResource($value))->toJson(), true);
        })->toArray();

        $response = $this->get(route('events.promos.index', $event->id));

        $response->assertOk()
            ->assertJsonCount(15, 'data')
            ->assertJson(['data' => $oldPromos]);
    }

    public function test_store_uses_form_request_meant_for_promo_validation()
    {
        $this->assertActionUsesFormRequest(
            PromoController::class,
            'store',
            StorePromoRequest::class
        );
    }

    public function test_validation_rule_for_store_promo_request_is_correct()
    {
        $this->assertValidationRules([
            'code' => ['required', new PromoCodeRule],
            'amount' => ['required', 'numeric'],
            'radius' => ['required', 'numeric'],
        ], (new StorePromoRequest())->rules());
    }

    public function test_store_returns_response_after_generating_and_saving_promo()
    {
        $promo = Promo::factory()->raw();
        $eventId = Arr::pull($promo, 'event_id');

        $response = $this->postJson(route('events.promos.store', $eventId), $promo);

        $response->assertCreated()
            ->assertJson(['data' => $promo]);
    }

    public function test_promo_can_be_deactivated()
    {
        $promo = Promo::factory()->create();

        $response = $this->postJson(route('promos.deactivate'), [
            'code' => $promo->code
        ]);

        $response->assertNoContent();

        $this->assertFalse(
            Promo::query()->where('code', $promo->code)->first()->active,
            'Promo code is not deactivated.'
        );
    }

    public function test_active_only_returns_active_promos()
    {
        $event = Event::factory()
            ->has(Promo::factory()->count(50))
            ->has(Promo::factory()->count(50)->inactive())
            ->create();

        $oldActivePromos = Promo::query()
            ->where('event_id', $event->id)
            ->where('active', true)
            ->oldest()
            ->take(15)
            ->get()
            ->map(function ($value) {
                return json_decode((new PromoResource($value))->toJson(), true);
            })
            ->toArray();

        $response = $this->getJson(route('events.promos.active', $event->id));

        $response->assertOk()
            ->assertJsonCount(15, 'data')
            ->assertJson(['data' => $oldActivePromos]);
    }

    public function test_verify_uses_form_request_meant_for_promo_validation()
    {
        $this->assertActionUsesFormRequest(
            PromoController::class,
            'verify',
            VerifyPromoRequest::class
        );
    }

    public function test_validation_rule_for_verify_promo_request_is_correct()
    {
        $this->assertValidationRules([
            'code' => ['required', new PromoCodeRule],
            'origin.lat' => ['required', 'numeric',],
            'origin.lng' => ['required', 'numeric',],
            'destination.lat' => ['required', 'numeric',],
            'destination.lng' => ['required', 'numeric',],
        ], (new VerifyPromoRequest())->rules());
    }

    public function test_verify_validates_promo_and_gives_a_response()
    {
        $promo = Promo::factory()->create()->refresh();

        $response = $this->postJson(route('promos.verify'), $requestData = [
            'code' => $promo->code,
            'origin' => [
                'lat' => $promo->event->coordinate->getLat(),
                'lng' => $promo->event->coordinate->getLng(),
            ],
            'destination' => [
                'lat' => $this->faker->latitude,
                'lng' => $this->faker->longitude,
            ],
        ]);

        $polyline = $promo->createPolyline($requestData['origin'], $requestData['destination']);

        $response->assertOk()
            ->assertJson(['data' => json_decode((new VerifiedPromoResource($promo, $polyline))->toJson(), true)]);
    }

    public function test_verify_shows_error_when_promo_is_expired()
    {
        $promo = Promo::factory()
            ->for(Event::factory()->past())
            ->create()
            ->refresh();

        $response = $this->postJson(route('promos.verify'), $requestData = [
            'code' => $promo->code,
            'origin' => [
                'lat' => $promo->event->coordinate->getLat(),
                'lng' => $promo->event->coordinate->getLng(),
            ],
            'destination' => [
                'lat' => $this->faker->latitude,
                'lng' => $this->faker->longitude,
            ],
        ]);

        $response->assertOk()
            ->assertJson(['expired' => 'Promo code has expired since event has ended.']);
    }

    public function test_verify_shows_error_when_promo_is_deactivated()
    {
        $promo = Promo::factory()->inactive()->create()->refresh();

        $response = $this->postJson(route('promos.verify'), $requestData = [
            'code' => $promo->code,
            'origin' => [
                'lat' => $promo->event->coordinate->getLat(),
                'lng' => $promo->event->coordinate->getLng(),
            ],
            'destination' => [
                'lat' => $this->faker->latitude,
                'lng' => $this->faker->longitude,
            ],
        ]);

        $response->assertOk()
            ->assertJson(['inactive' => 'Promo code has been deactivated.']);
    }
}
