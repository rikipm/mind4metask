<?php

namespace App\Http\Requests;

use App\Enums\CityEnum;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeliverySlotsRequest extends FormRequest
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
            'datetime' => [
                'required',
                'date',
                'date_format:Y-m-d H:i:s',
            ],
            'city' => [
                'required',
                Rule::enum(CityEnum::class),
            ]
        ];
    }

    protected function passedValidation(): void
    {
        $this->replace(array_merge($this->input(), [
            'datetime' => new CarbonImmutable($this->datetime),
        ]));
    }
}
