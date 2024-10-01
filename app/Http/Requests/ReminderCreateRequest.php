<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Enums\ReminderIntervals;
use Illuminate\Foundation\Http\FormRequest;

class ReminderCreateRequest extends FormRequest
{
    /**
     * Get all defined validation rules for this request.
     *
     * @see https://laravel.com/docs/11.x/validation#creating-form-requests
     * @see https://laravel.com/docs/11.x/validation#available-validation-rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'day' => [
                'required',
                'integer',
                'min:1',
                'max:31',
            ],
            'month' =>  [
                'required',
                'integer',
                'min:1',
                'max:12',
            ],
            'description' => [
                'required',
                'string',
            ],
            'interval' => [
                'required',
                Rule::enum(ReminderIntervals::class),
            ],
        ];
    }
}
