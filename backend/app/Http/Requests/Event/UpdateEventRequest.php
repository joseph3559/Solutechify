<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Will be handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'venue' => ['sometimes', 'string', 'max:255'],
            'event_date' => ['sometimes', 'date', 'after:now'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'max_attendees' => ['sometimes', 'integer', 'min:1'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom validation rules for max_attendees field.
     *
     * @return array
     */
    public function after(): array
    {
        return [
            function ($validator) {
                if ($this->has('max_attendees')) {
                    $event = $this->route('event');
                    if ($this->max_attendees < $event->current_attendees) {
                        $validator->errors()->add(
                            'max_attendees',
                            'Maximum attendees cannot be less than current attendees.'
                        );
                    }
                }
            }
        ];
    }
} 