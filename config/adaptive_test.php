<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paramètres Généraux du Test v5.0
    |--------------------------------------------------------------------------
    | max_questions : Limite haute absolue (tous blocs confondus).
    |   RIASEC   30 questions
    |   Big Five 14 questions
    |   GATB     12 questions
    |   Résilience 6 questions
    |   Attention  2 questions
    |   Intra-domaine 10 questions
    |   TOTAL ≈ 74 — on garde 80 pour marge.
    */
    'max_questions' => 80,
    'cache_ttl'     => 7200, // 2 heures en secondes

    /*
    |--------------------------------------------------------------------------
    | Règles d'Arrêt (Early Stopping)
    |--------------------------------------------------------------------------
    | L'arrêt précoce se déclenche quand la certitude des dimensions RIASEC
    | dominantes est suffisamment haute ET que les theta sont stables.
    */
    'stopping_rules' => [
        'min_certainty_threshold' => 80.0,  // % de certitude requis
        'min_dimensions_reached'  => 3,     // Dimensions RIASEC à ≥ seuil
    ],

    /*
    |--------------------------------------------------------------------------
    | Paramètres du Modèle Bayésien (IRT/CAT)
    |--------------------------------------------------------------------------
    */
    'learning_rate' => [
        'base'        => 0.5,
        'decay_floor' => 0.2, // Valeur plancher du learning rate
    ],

    /*
    |--------------------------------------------------------------------------
    | Seuils d'Analyse Comportementale (Détection de Fraude)
    |--------------------------------------------------------------------------
    | speed_threshold_ms      : Temps moyen < valeur → alerte speedrunning
    | flat_variance_threshold : Variance < valeur → alerte réponses plates
    | inconsistency_theta_jump: Saut de valeur ≥ valeur sur même dim → alerte
    | max_alerts              : Nb d'alertes avant flag du profil
    | gatb_min_response_ms    : Temps minimum acceptable pour réponse GATB
    */
    'behavioral' => [
        'speed_threshold_ms'       => 3000,
        'flat_variance_threshold'  => 0.5,
        'inconsistency_theta_jump' => 1.5,
        'max_alerts'               => 3,
        'gatb_min_response_ms'     => 4000,
    ],
];
