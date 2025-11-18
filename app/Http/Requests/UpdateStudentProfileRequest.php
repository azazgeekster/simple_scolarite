<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Override the validationData method to intercept data before validation
     */
    public function validationData()
    {
        $data = parent::validationData();

        // Ensure 'family' is always an array to prevent array_replace_recursive error
        if (array_key_exists('family', $data) && !\is_array($data['family'])) {
            $data['family'] = [];
        }

        return $data;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);

        // Fix the family field if it's not an array
        if (isset($data['family']) && !\is_array($data['family'])) {
            $data['family'] = [];
        }

        return $data;
    }

    /**
     * Prepare the data for validation.
     * This is the crucial step to ensure 'family' is always an array type.
     */
    protected function prepareForValidation(): void
    {
        // Get the raw input
        $input = $this->input();

        // If 'family' exists but is NOT an array (e.g., it's '0' or 'false' from a form input),
        // we force it back to an empty array to prevent the array_replace_recursive error.
        if (array_key_exists('family', $input) && !\is_array($input['family'])) {
            $input['family'] = [];
        }

        // Replace the entire request input with cleaned version
        $this->replace($input);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'prenom_ar' => 'nullable|string|max:255',
            'nom_ar' => 'nullable|string|max:255',
            'cin' => 'nullable|string|max:255',
            'sexe' => 'nullable|in:M,F,m,f',
            'tel' => 'nullable|string|max:20',
            'tel_urgence' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date_format:Y-m-d',
            'lieu_naissance' => 'nullable|string|max:255',
            'lieu_naissance_ar' => 'nullable|string|max:255',
            'nationalite' => 'nullable|string|max:10',
            'situation_familiale' => 'nullable|in:Célibataire,Marié,Divorcé,Veuf,célibataire,marié,divorcé,veuf',
            'situation_professionnelle' => 'nullable|in:Étudiant,Salarié,Chômeur,Autre,étudiant,salarié,chômeur,autre',
            'adresse' => 'nullable|string',
            'adresse_ar' => 'nullable|string',
            'pays' => 'nullable|string|max:10',

            // Family information
            // The combination of 'nullable' and 'array' here is also important
            'family' => 'nullable|array',
            'family.father_firstname' => 'nullable|string|max:255',
            'family.father_lastname' => 'nullable|string|max:255',
            'family.father_cin' => 'nullable|string|max:50',
            'family.father_birth_date' => 'nullable|date',
            'family.father_death_date' => 'nullable|date',
            'family.father_profession' => 'nullable|string|max:255',
            'family.mother_firstname' => 'nullable|string|max:255',
            'family.mother_lastname' => 'nullable|string|max:255',
            'family.mother_cin' => 'nullable|string|max:50',
            'family.mother_birth_date' => 'nullable|date',
            'family.mother_death_date' => 'nullable|date',
            'family.mother_profession' => 'nullable|string|max:255',
            'family.spouse_cin' => 'nullable|string|max:50',
            'family.spouse_death_date' => 'nullable|date',
            'family.handicap_code' => 'nullable|string|max:50',
            'family.handicap_type' => 'nullable|string|max:255',
            'family.handicap_card_number' => 'nullable|string|max:100',
        ];
    }
}