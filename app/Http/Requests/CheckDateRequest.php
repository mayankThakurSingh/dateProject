<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class CheckDateRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

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
            'start_date' => ['bail', 'required', 'date',
                function (string $attribute, mixed $value, Closure $fail) {
                    /**
                     * Convert the input values to Carbon instances
                     * Check if the current day is a Sunday (Carbon uses ISO-8601 where Sunday is day 7)
                     */
                    $date = Carbon::parse($value);
                    if ($date->dayOfWeek == Carbon::SUNDAY) {
                        $fail("The {$attribute} can not be sunday.");
                    }
                },
            ],
            'end_date' => ['bail', 'required', 'date', 'after:start_date', 'after_or_equal:'.now()->addYears(2)->format('Y-m-d'),
                function (string $attribute, mixed $value, closure $fail) {
                    /**
                     * Convert the input values to Carbon instances
                     */
                    $startDate = Carbon::parse(request()->input('start_date'));
                    $endDate = Carbon::parse($value);

                    /**
                     * Check if the difference between start and end dates is between 2 and 5 years
                     */
                    $yearDifference = $startDate->diffInYears($endDate);
                    if ($yearDifference >= 5 || $yearDifference < 2) {
                        $fail('The end date must be between 2 and 5 years apart from the start date.');
                    }
                },
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'start_date.date' => 'The start date must be a valid date with format d-m-y',
            'end_date.date' => 'The end date must be a valid date with format d-m-y',
            'end_date.after_or_equal' => 'The end date must be between 2 and 5 years apart from the start date',
        ];
    }
}
