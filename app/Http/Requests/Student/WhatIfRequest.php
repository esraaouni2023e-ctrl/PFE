<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valide les données du simulateur What-If (calcul Score FG).
 */
class WhatIfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isStudent();
    }

    public function rules(): array
    {
        return [
            'section_bac'      => ['required', 'string', 'in:' . implode(',', self::SECTIONS)],
            'moyenne_generale'  => ['required', 'numeric', 'min:0', 'max:20'],
            'label'             => ['nullable', 'string', 'max:100'],
            // Notes dynamiques selon la section
            'notes'             => ['required', 'array', 'min:2'],
            'notes.*'           => ['required', 'numeric', 'min:0', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'section_bac.required'     => 'Veuillez sélectionner votre section du BAC.',
            'section_bac.in'           => 'Section BAC invalide.',
            'moyenne_generale.required' => 'La moyenne générale est obligatoire.',
            'moyenne_generale.min'      => 'La moyenne doit être entre 0 et 20.',
            'notes.required'           => 'Veuillez saisir vos notes.',
            'notes.*.min'              => 'Chaque note doit être entre 0 et 20.',
        ];
    }

    public const SECTIONS = [
        'Mathématiques',
        'Sciences expérimentales',
        'Économie et gestion',
        'Technique',
        'Informatique',
        'Lettres',
        'Sport',
    ];
}
