<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Enums\ReminderIntervals;
use Illuminate\Foundation\Http\FormRequest;

class ReminderUpdateRequest extends FormRequest
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
                'integer',
                'min:1',
                'max:31',
            ],
            'month' =>  [
                'integer',
                'min:1',
                'max:12',
            ],
            'description' => [
                'string',
            ],
            'interval' => [
                Rule::enum(ReminderIntervals::class),
            ],
        ];
    }
}
