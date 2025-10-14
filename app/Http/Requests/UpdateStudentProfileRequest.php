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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $studentId = $this->user('student')->id ?? null;

        return [
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'prenom_ar' => 'nullable|string|max:255',
            'nom_ar' => 'nullable|string|max:255',
            // 'email' => 'required|email|max:255|unique:students,email,' . $studentId,
            'cne' => 'required|string|max:255|unique:students,cne,' . $studentId,
            // 'apogee' => 'required|string|max:255',
            'cin' => 'required|string|max:255',
            'sexe' => 'nullable|in:m,f',
            'tel' => 'nullable|string|max:20',
            'tel_urgence' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date_format:Y-m-d',
            'nationalite' => 'nullable|string',
            'situation_familiale' => 'nullable|in:célibataire,marié,divorcé,veuf',
            'situation_professionnelle' => 'nullable|in:étudiant,salarié,chômeur,autre',
            'organisme' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'adresse_ar' => 'nullable|string',
            'ville_naissance' => 'nullable|string|max:255',
            'ville_naissance_ar' => 'nullable|string|max:255',
            'province_naissance' => 'nullable|string|max:255',
            'province_adresse' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:20',
            'pays' => 'nullable|string|size:2',
            'boursier' => 'nullable|boolean',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
