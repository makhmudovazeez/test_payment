<?php

namespace App\Http\Requests;

use App\Models\Payment;
use App\Service\MerchantService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class MerchantRequest extends FormRequest
{
    public function prepareForValidation()
    {
        if ($this->header('Content-Type') === MerchantService::FORM_DATA) {
            $this->merge([
                'header' => $this->header('Authorization')
            ]);
        }
    }

    public function rules(): array
    {
        if ($this->header('Content-Type') === MerchantService::APPLICATION_JSON) {
            return [
                'merchant_id' => ['required', 'numeric'],
                'payment_id' => ['required', 'numeric'],
                'status' => ['required', Rule::in(Payment::STATUSES)],
                'amount' => ['required', 'numeric'],
                'amount_paid' => ['required', 'numeric'],
                'timestamp' => ['required', 'numeric'],
                'sign' => ['required', 'string']
            ];
        }
        return [
            'project' => ['required', 'numeric'],
            'invoice' => ['required', 'numeric'],
            'status' => ['required', Rule::in(Payment::STATUSES)],
            'amount' => ['required', 'numeric'],
            'amount_paid' => ['required', 'numeric'],
            'rand' => ['required', 'string'],
            'header' => ['required', 'string']
        ];
    }

    public function messages()
    {
        return [
            'header.required' => 'Authorization should contain sign',
            'header.string' => 'Authorization sign shoudl be string'
        ];
    }
}
