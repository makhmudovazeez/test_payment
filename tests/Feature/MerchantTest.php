<?php

namespace Tests\Feature;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class MerchantTest extends TestCase
{
    public function test_media_type_error() {
        $body = [
            'project' => 816,
            'invoice' => 73,
            'status' => Payment::STATUS_COMPLETED,
            'amount' => 700,
            'amount_paid' => 700,
            'rand' => 'SNuHufEJ'
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'd84eb9036bfc2fa7f46727f101c73c73'
        ])->post('api/merchant', $body);

        $response->assertStatus(ResponseAlias::HTTP_UNSUPPORTED_MEDIA_TYPE);
    }

    public function test_success_application_json() {
        $this->artisan('cache:clear');

        $body = [
            'merchant_id' => config('merchant.id'),
            'payment_id' => 13,
            'status' => Payment::STATUS_COMPLETED,
            'amount' => 500,
            'amount_paid' => 500,
            'timestamp' => Carbon::now()->timestamp,
            'sign' => 'f027612e0e6cb321ca161de060237eeb97e46000da39d3add08d09074f931728'
        ];

        $response = $this->postJson('api/merchant', $body);

        unset($body['sign']);
        ksort($body);
        $comparison = hash('sha256', implode(':', $body) . config('merchant.key'));

        $response->assertStatus(200)->assertJsonPath('result', $comparison);
    }

    public function test_limit_application_json() {
        $body = [
            'merchant_id' => config('merchant.id'),
            'payment_id' => 13,
            'status' => Payment::STATUS_COMPLETED,
            'amount' => 500,
            'amount_paid' => 500,
            'timestamp' => Carbon::now()->timestamp,
            'sign' => 'f027612e0e6cb321ca161de060237eeb97e46000da39d3add08d09074f931728'
        ];

        $response = $this->postJson('api/merchant', $body);

        $response->assertStatus(ResponseAlias::HTTP_TOO_MANY_REQUESTS);
    }

    public function test_success_form_data() {
        $this->artisan('cache:clear');

        $body = [
            'project' => 816,
            'invoice' => 73,
            'status' => Payment::STATUS_COMPLETED,
            'amount' => 700,
            'amount_paid' => 700,
            'rand' => 'SNuHufEJ'
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'multipart/form-data',
            'Authorization' => 'd84eb9036bfc2fa7f46727f101c73c73'
        ])->post('api/merchant', $body);

        ksort($body);
        $comparison = md5(implode('.', $body) . config('merchant.key'));

        $response->assertStatus(ResponseAlias::HTTP_OK)->assertJsonPath('result', $comparison);
    }

    public function test_limit_form_data() {
        $body = [
            'project' => 816,
            'invoice' => 73,
            'status' => Payment::STATUS_COMPLETED,
            'amount' => 700,
            'amount_paid' => 700,
            'rand' => 'SNuHufEJ'
        ];

        $response = $this->withHeaders([
            'Content-Type' => 'multipart/form-data',
            'Authorization' => 'd84eb9036bfc2fa7f46727f101c73c73'
        ])->post('api/merchant', $body);

        $response->assertStatus(ResponseAlias::HTTP_TOO_MANY_REQUESTS);
    }
}
