<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExamPeriodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $periodId = $this->route('examPeriod')?->id;

        return [
            'academic_year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'season' => ['required', Rule::in(['autumn', 'spring'])],
            'session_type' => ['required', Rule::in(['normal', 'rattrapage'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'label' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['sometimes', 'boolean'],
            'auto_publish_exams' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'academic_year.required' => 'L\'année universitaire est requise',
            'academic_year.integer' => 'L\'année universitaire doit être un nombre entier',
            'academic_year.min' => 'L\'année universitaire doit être supérieure à 2000',
            'academic_year.max' => 'L\'année universitaire doit être inférieure à 2100',
            'season.required' => 'La saison est requise',
            'season.in' => 'La saison doit être "automne" ou "printemps"',
            'session_type.required' => 'Le type de session est requis',
            'session_type.in' => 'Le type de session doit être "normal" ou "rattrapage"',
            'start_date.required' => 'La date de début est requise',
            'start_date.date' => 'La date de début doit être une date valide',
            'end_date.required' => 'La date de fin est requise',
            'end_date.date' => 'La date de fin doit être une date valide',
            'end_date.after_or_equal' => 'La date de fin doit être après ou égale à la date de début',
            'label.max' => 'Le libellé ne peut pas dépasser 255 caractères',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères',
        ];
    }

    /**
     * Prepare data for validation
     */
    protected function prepareForValidation(): void
    {
        $data = [];

        // Convert checkboxes to boolean
        $data['is_active'] = $this->has('is_active') && $this->input('is_active') == '1';
        $data['auto_publish_exams'] = $this->has('auto_publish_exams') && $this->input('auto_publish_exams') == '1';

        // Auto-generate label if not provided
        if (empty($this->input('label'))) {
            $sessionType = $this->input('session_type') === 'normal' ? 'Session Normale' : 'Session Rattrapage';
            $season = $this->input('season') === 'autumn' ? 'Automne' : 'Printemps';
            $year = $this->input('academic_year');

            $data['label'] = "{$sessionType} - {$season} {$year}";
        }

        $this->merge($data);
    }
}
