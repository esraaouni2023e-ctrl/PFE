<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paramètres Généraux du Test
    |--------------------------------------------------------------------------
    */
    'max_questions' => 20,
    'cache_ttl' => 7200, // 2 heures

    /*
    |--------------------------------------------------------------------------
    | Règles d'Arrêt (Early Stopping)
    |--------------------------------------------------------------------------
    */
    'stopping_rules' => [
        'min_certainty_threshold' => 80.0,
        'min_dimensions_reached' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Paramètres du Modèle Bayésien (Phase 1)
    |--------------------------------------------------------------------------
    */
    'learning_rate' => [
        'base' => 0.5,
        'decay_floor' => 0.2, // Valeur minimale du decay pour éviter un blocage total
    ],

    /*
    |--------------------------------------------------------------------------
    | Seuils d'Analyse Comportementale (Détection de Fraude - Phase 2)
    |--------------------------------------------------------------------------
    */
    'behavioral' => [
        'speed_threshold_ms' => 3000,
        'flat_variance_threshold' => 0.5,
        'inconsistency_theta_jump' => 1.5,
        'max_alerts' => 3,
    ],
];
