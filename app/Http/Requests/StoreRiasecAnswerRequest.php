<?php

namespace App\Http\Requests;

use App\Models\QuestionRiasec;
use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreRiasecAnswerRequest — Validation stricte d'une réponse RIASEC.
 *
 * Utilisée lors de l'appel AJAX POST /riasec/repondre.
 * Vérifie que la question existe, est active, et que la valeur est dans la plage.
 */
class StoreRiasecAnswerRequest extends FormRequest
{
    /**
     * Seuls les étudiants authentifiés OU les invités avec une session active.
     */
    public function authorize(): bool
    {
        return true; // La vérification de session est gérée par EnsureTestInProgress
    }

    public function rules(): array
    {
        return [
            'question_id' => [
                'required',
                'integer',
                'exists:riasec_questions,id',
                // La question doit être active
                function ($attr, $value, $fail) {
                    $q = QuestionRiasec::where('id', $value)->where('actif', true)->first();
                    if (! $q) {
                        $fail('Cette question n\'est pas disponible.');
                    }
                },
            ],
            'valeur' => [
                'required',
                'integer',
                'min:1',
                'max:5',
                // Validation contextuelle selon le type de la question
                function ($attr, $value, $fail) {
                    $question = QuestionRiasec::find($this->input('question_id'));
                    if ($question && ! $question->valeurEstValide((int) $value)) {
                        $fail(
                            "La valeur {$value} est invalide pour ce type de question ({$question->type_reponse})."
                        );
                    }
                },
            ],
            'temps_ms' => ['nullable', 'integer', 'min:0', 'max:300000'],
        ];
    }

    public function messages(): array
    {
        return [
            'question_id.required' => 'La question est obligatoire.',
            'question_id.exists'   => 'Cette question n\'existe pas.',
            'valeur.required'      => 'Vous devez sélectionner une réponse.',
            'valeur.integer'       => 'La réponse doit être un nombre entier.',
            'valeur.min'           => 'La réponse doit être au minimum 1.',
            'valeur.max'           => 'La réponse doit être au maximum 5.',
        ];
    }

    /** Expose l'ID de session RIASEC injecté par le middleware. */
    public function riasecSessionId(): string
    {
        return $this->input('_riasec_session_id') ?? session('riasec_session_id');
    }
}
