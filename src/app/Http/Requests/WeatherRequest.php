<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WeatherRequest extends FormRequest
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
            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],
            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],
            'date' => [
                'required',
                'date',
                Rule::date()->after(today()->subMonths(2)->startOfMonth()),
                Rule::date()->before(today()->addMonths(2)->endOfMonth()),
            ],
        ];
    }

    protected function passedValidation(): void
    {
        $this->replace(array_merge($this->input(), [
            'date' => new Carbon($this->date)
        ]));
    }
}
