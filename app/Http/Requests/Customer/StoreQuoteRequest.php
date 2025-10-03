<?php

namespace App\Http\Requests\Customer;

use App\Models\Services;
use App\Services\CommonCrudService;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|unique:quote,email|email|max:255',
            'phone' => 'required|digits_between:7,20',
            'address' => 'required|string|max:500',
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:now',
            'duration' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
            'price' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'name.string' => 'Name must be a valid text.',
            'name.max' => 'Name cannot exceed 255 characters.',

            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot exceed 255 characters.',

            'phone.required' => 'Please enter your phone number.',
            'phone.string' => 'Phone number must be valid text.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',

            'address.required' => 'Please enter your address.',
            'address.string' => 'Address must be valid text.',
            'address.max' => 'Address cannot exceed 500 characters.',

            'service_id.required' => 'Please select a service.',
            'service_id.exists' => 'Selected service is invalid.',

            'booking_date.required' => 'Please select a booking date and time.',
            'booking_date.date' => 'Booking date must be a valid date.',
            'booking_date.after_or_equal' => 'Booking date and time must be now or in the future.',

            'duration.required' => 'Please enter the duration in hours.',
            'duration.integer' => 'Duration must be a number.',
            'duration.min' => 'Duration must be at least 1 hour.',

            'notes.string' => 'Notes must be text only.',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
        ];
    }

    public function prepareForValidation()
    {
        if($this->service_id && $this->duration) {
            $common = new CommonCrudService();
            $service = $common->selectWithId(Services::class,$this->service_id,['price_per_hour']);
            $generated_price = $service->price_per_hour * $this->duration;
            $this->merge([
                'price' => $generated_price
            ]);
        }
    }
}
