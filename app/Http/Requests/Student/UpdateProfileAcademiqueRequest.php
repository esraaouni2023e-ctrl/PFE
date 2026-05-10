<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valide la mise à jour du profil académique étudiant.
 */
class UpdateProfileAcademiqueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isStudent();
    }

    public function rules(): array
    {
        return [
            'section_bac'       => ['required', 'string', 'in:' . implode(',', WhatIfRequest::SECTIONS)],
            'moyenne_generale'   => ['required', 'numeric', 'min:0', 'max:20'],
            'annee_bac'         => ['required', 'integer', 'min:2010', 'max:' . (date('Y') + 1)],
            'gouvernorat'       => ['required', 'string', 'max:100'],
            'notes_matieres'    => ['required', 'array', 'min:2'],
            'notes_matieres.*'  => ['required', 'numeric', 'min:0', 'max:20'],
            'interests'         => ['nullable', 'string', 'max:1000'],
            'skills'            => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'section_bac.required'      => 'La section BAC est obligatoire.',
            'moyenne_generale.required'  => 'La moyenne générale est obligatoire.',
            'annee_bac.required'        => 'L\'année du BAC est obligatoire.',
            'gouvernorat.required'      => 'Le gouvernorat est obligatoire.',
            'notes_matieres.required'   => 'Les notes par matière sont obligatoires.',
        ];
    }
}
