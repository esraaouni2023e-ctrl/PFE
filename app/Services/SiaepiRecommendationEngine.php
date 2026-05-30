<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\Filiere;

/**
 * SIAEPI v8.0 — Moteur Décisionnel de Recommandation (Version Production-Grade Stable)
 */
class SiaepiRecommendationEngine
{
    // ── Profils psychométriques cibles par domaine ─────────────────────────
    private array $domainPsychoprofile = [
        'informatique' => ['B5' => ['O' => 0.7, 'C' => 0.8, 'E' => 0.0, 'A' => 0.0, 'N' => -0.4], 'Val' => ['Sec' => 0.0, 'Ach' => 0.7, 'Ben' => 0.0, 'Aut' => 0.6]],
        'sante'        => ['B5' => ['O' => 0.0, 'C' => 0.8, 'E' => 0.0, 'A' => 0.9, 'N' => -0.5], 'Val' => ['Sec' => 0.6, 'Ach' => 0.0, 'Ben' => 0.9, 'Aut' => 0.0]],
        'technique'    => ['B5' => ['O' => 0.6, 'C' => 0.9, 'E' => 0.0, 'A' => 0.0, 'N' => 0.0],  'Val' => ['Sec' => 0.5, 'Ach' => 0.8, 'Ben' => 0.0, 'Aut' => 0.0]],
        'sciences'     => ['B5' => ['O' => 0.9, 'C' => 0.7, 'E' => 0.0, 'A' => 0.0, 'N' => 0.0],  'Val' => ['Sec' => 0.0, 'Ach' => 0.7, 'Ben' => 0.0, 'Aut' => 0.8]],
        'economie'     => ['B5' => ['O' => 0.0, 'C' => 0.7, 'E' => 0.8, 'A' => 0.0, 'N' => 0.0],  'Val' => ['Sec' => 0.7, 'Ach' => 0.9, 'Ben' => 0.0, 'Aut' => 0.0]],
        'lettres'      => ['B5' => ['O' => 0.9, 'C' => 0.0, 'E' => 0.0, 'A' => 0.6, 'N' => 0.0],  'Val' => ['Sec' => 0.0, 'Ach' => 0.0, 'Ben' => 0.5, 'Aut' => 0.7]],
        'social'       => ['B5' => ['O' => 0.0, 'C' => 0.0, 'E' => 0.7, 'A' => 0.9, 'N' => 0.0],  'Val' => ['Sec' => 0.0, 'Ach' => 0.0, 'Ben' => 0.9, 'Aut' => 0.5]],
        'arts'         => ['B5' => ['O' => 1.0, 'C' => 0.0, 'E' => 0.6, 'A' => 0.0, 'N' => 0.0],  'Val' => ['Sec' => 0.0, 'Ach' => 0.5, 'Ben' => 0.0, 'Aut' => 0.9]],
    ];

    /**
     * Charge les filières depuis la base de données relationnelle.
     */
    public function loadFilieres(): array
    {
        try {
            $dbFilieres = Filiere::with('profile')->get();
            $filieres = [];
            foreach ($dbFilieres as $f) {
                $profile = $f->profile;
                $codeRiasec = strtoupper(trim($f->code_riasec ?? ''));
                $nomFiliere = strtolower($f->nom_filiere ?? '');
                $domain = $this->detectDomain($nomFiliere, $codeRiasec);

                // Reconstruct RIASEC vector dynamically if missing
                $riasecVec = ['R'=>0.5, 'I'=>0.5, 'A'=>0.5, 'S'=>0.5, 'E'=>0.5, 'C'=>0.5];
                if (strlen($codeRiasec) >= 3) {
                    $letters = str_split(substr($codeRiasec, 0, 3));
                    foreach (['R', 'I', 'A', 'S', 'E', 'C'] as $d) {
                        $pos = array_search($d, $letters);
                        if ($pos === 0)      $riasecVec[$d] = 1.0;
                        elseif ($pos === 1)  $riasecVec[$d] = 0.8;
                        elseif ($pos === 2)  $riasecVec[$d] = 0.6;
                        else                 $riasecVec[$d] = 0.2;
                    }
                }

                $riasec_r = $profile ? $profile->riasec_r : $riasecVec['R'];
                $riasec_i = $profile ? $profile->riasec_i : $riasecVec['I'];
                $riasec_a = $profile ? $profile->riasec_a : $riasecVec['A'];
                $riasec_s = $profile ? $profile->riasec_s : $riasecVec['S'];
                $riasec_e = $profile ? $profile->riasec_e : $riasecVec['E'];
                $riasec_c = $profile ? $profile->riasec_c : $riasecVec['C'];

                $riasecSum = $riasec_r + $riasec_i + $riasec_a + $riasec_s + $riasec_e + $riasec_c;
                if ($riasecSum < 0.1) {
                    throw new \Exception("Filiere RIASEC profile is null or invalid for " . ($f->nom_filiere ?? 'Unknown'));
                }

                // Domain-specific target Big Five defaults
                $b5Defaults = $this->domainPsychoprofile[$domain]['B5'] ?? ['O'=>0.0,'C'=>0.0,'E'=>0.0,'A'=>0.0,'N'=>0.0];

                // Domain-specific GATB defaults
                $gatbDefaults = [
                    'technique'   => ['G' => 60, 'V' => 50, 'N' => 60, 'S' => 60],
                    'sciences'    => ['G' => 65, 'V' => 50, 'N' => 60, 'S' => 55],
                    'lettres'     => ['G' => 50, 'V' => 65, 'N' => 45, 'S' => 45],
                    'economie'    => ['G' => 55, 'V' => 55, 'N' => 60, 'S' => 50],
                    'arts'        => ['G' => 50, 'V' => 50, 'N' => 45, 'S' => 60],
                    'social'      => ['G' => 55, 'V' => 60, 'N' => 50, 'S' => 50],
                    'sante'       => ['G' => 65, 'V' => 60, 'N' => 55, 'S' => 55],
                    'default'     => ['G' => 50, 'V' => 50, 'N' => 50, 'S' => 50],
                ];
                $gDefs = $gatbDefaults[$domain] ?? $gatbDefaults['default'];

                $filieres[] = [
                    'Code_Filiere'       => $f->code_filiere,
                    'Nom_Filiere'        => $f->nom_filiere,
                    'Universite'         => $f->universite,
                    'Etablissement'      => $f->etablissement,
                    'SDO_2023'           => $f->sdo_2023 !== null ? (float)$f->sdo_2023 : 0.0,
                    'SDO_2024'           => $f->sdo_2024 !== null ? (float)$f->sdo_2024 : 0.0,
                    'SDO_2025'           => $f->sdo_2025 !== null ? (float)$f->sdo_2025 : 0.0,
                    'Domaine'            => $f->domaine,
                    'Code_RIASEC'        => $f->code_riasec,
                    'Taux_Employabilite' => $f->taux_employabilite ?: 'Modéré',
                    'Croissance_Domaine' => $f->croissance_domaine ?: 'Stable',
                    'Type_Bac'           => $f->type_bac,

                    'filiere_id'         => $f->id,
                    'domaine_id'         => $f->domaine_id,
                    'specialisation_id'  => $f->specialisation_id,
                    'Careers'            => $f->careers,

                    'riasec_r'           => $riasec_r,
                    'riasec_i'           => $riasec_i,
                    'riasec_a'           => $riasec_a,
                    'riasec_s'           => $riasec_s,
                    'riasec_e'           => $riasec_e,
                    'riasec_c'           => $riasec_c,

                    'gatb_g_required'    => $profile ? $profile->gatb_g_required : $gDefs['G'],
                    'gatb_v_required'    => $profile ? $profile->gatb_v_required : $gDefs['V'],
                    'gatb_n_required'    => $profile ? $profile->gatb_n_required : $gDefs['N'],
                    'gatb_s_required'    => $profile ? $profile->gatb_s_required : $gDefs['S'],

                    'employability_index'=> $profile ? $profile->employability_index : 0.60,
                    'employability_rate' => $profile ? $profile->employability_rate : null,
                    'growth_rate'        => $profile ? $profile->growth_rate : null,
                    'annual_openings'    => $profile ? $profile->annual_openings : null,

                    'difficulty_level'   => $profile ? $profile->difficulty_level : 5,
                    'stress_tolerance'   => $profile ? $profile->stress_tolerance : 5,

                    'job_demand'         => $profile ? (float)$profile->job_demand : 0.60,
                    'salary'             => $profile ? (float)$profile->salary : 0.60,
                    'internships'        => $profile ? (float)$profile->internships : 0.60,
                    'market_source'      => $profile ? $profile->market_source : 'ANETI / INS Tunisie',
                    'market_date'        => $profile ? $profile->market_date : '2026-05',
                    'market_region'      => $profile ? $profile->market_region : 'Tunisie',

                    'big5_openness'            => $profile ? $profile->big5_openness : ($b5Defaults['O'] ?? 0.0),
                    'big5_conscientiousness'   => $profile ? $profile->big5_conscientiousness : ($b5Defaults['C'] ?? 0.0),
                    'big5_extraversion'        => $profile ? $profile->big5_extraversion : ($b5Defaults['E'] ?? 0.0),
                    'big5_agreeableness'       => $profile ? $profile->big5_agreeableness : ($b5Defaults['A'] ?? 0.0),
                    'big5_neuroticism'         => $profile ? $profile->big5_neuroticism : ($b5Defaults['N'] ?? 0.0),
                ];
            }
            return $filieres;
        } catch (\Throwable $e) {
            Log::error("SIAEPI: Erreur de chargement BDD filières: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Z-Score Normalization helper.
     */
    private function sigmoidNormalize(float $x, float $mu, float $sigma): float
    {
        if ($sigma <= 0.0) return 0.5;
        $z = ($x - $mu) / $sigma;
        return $this->clamp01(1.0 / (1.0 + exp(-$z)));
    }

    private function clamp01(float $value): float
    {
        return max(0.0, min(1.0, $value));
    }

    /**
     * Cosine similarity of two numeric vectors.
     */
    private function cosineSimilarity(array $vecA, array $vecB): float
    {
        $dot = 0.0; $normA = 0.0; $normB = 0.0;
        foreach ($vecA as $k => $a) {
            $b = $vecB[$k] ?? 0.0;
            $dot += $a * $b;
            $normA += $a * $a;
            $normB += $b * $b;
        }
        if ($normA < 1e-6 || $normB < 1e-6) return 0.5;
        $cosine = $dot / (sqrt($normA) * sqrt($normB));
        return $this->clamp01($cosine);
    }

    private function domainInterestMatch(array $studentInterests, ?string $filiereDimension, string $domain): float
    {
        if ($filiereDimension === null && $domain === '') {
            return 0.0;
        }

        $studentLabels = $this->buildInterestLabelSet($studentInterests);
        $targetLabels = [];

        if ($filiereDimension !== null) {
            $targetLabels[] = strtoupper(trim($filiereDimension));
        }
        if ($domain !== '') {
            $targetLabels[] = strtoupper(trim($domain));
        }

        $targetLabels = array_unique(array_filter($targetLabels));
        if (empty($studentLabels) || empty($targetLabels)) {
            return 0.0;
        }

        foreach ($targetLabels as $target) {
            if (isset($studentLabels[$target])) {
                return 1.0;
            }
        }

        return 0.0;
    }

    private function buildInterestLabelSet(array $interests): array
    {
        $labels = [];
        foreach ($interests as $key => $value) {
            if (is_string($key) && trim($key) !== '') {
                if (is_numeric($value)) {
                    if ((float) $value > 0.0) {
                        $labels[strtoupper(trim($key))] = true;
                    }
                } elseif (is_string($value) && trim($value) !== '') {
                    $labels[strtoupper(trim($value))] = true;
                }
            } elseif (is_string($value) && trim($value) !== '') {
                $labels[strtoupper(trim($value))] = true;
            }
        }
        return $labels;
    }

    private function recommendationSimilarity(array $a, array $b): float
    {
        $vecA = [
            'R' => $a['riasec_r'] ?? 0.5,
            'I' => $a['riasec_i'] ?? 0.5,
            'A' => $a['riasec_a'] ?? 0.5,
            'S' => $a['riasec_s'] ?? 0.5,
            'E' => $a['riasec_e'] ?? 0.5,
            'C' => $a['riasec_c'] ?? 0.5,
            'domain' => ($a['Domaine'] ?? '') === ($b['Domaine'] ?? '') ? 1.0 : 0.0,
        ];

        $vecB = [
            'R' => $b['riasec_r'] ?? 0.5,
            'I' => $b['riasec_i'] ?? 0.5,
            'A' => $b['riasec_a'] ?? 0.5,
            'S' => $b['riasec_s'] ?? 0.5,
            'E' => $b['riasec_e'] ?? 0.5,
            'C' => $b['riasec_c'] ?? 0.5,
            'domain' => ($a['Domaine'] ?? '') === ($b['Domaine'] ?? '') ? 1.0 : 0.0,
        ];

        return $this->cosineSimilarity($vecA, $vecB);
    }

    private function resolveHealthInterests(array $interests): array
    {
        $hasDoctor = false;
        $hasParamedical = false;

        foreach ($interests as $key => $value) {
            // 1. Numeric check (keys)
            if (is_string($key) && is_numeric($value) && (float)$value >= 0.35) {
                $keyUpper = strtoupper(trim($key));
                if (preg_match('/\b(MED|DENT|PHARM)\b/', $keyUpper)) {
                    $hasDoctor = true;
                }
                if (preg_match('/\b(SANT|HEALTH|KINE|INFIR|SAGE|NURS|SPO)\b/', $keyUpper)) {
                    $hasParamedical = true;
                    if (preg_match('/\b(SANT|HEALTH)\b/', $keyUpper)) {
                        $hasDoctor = true;
                    }
                }
            }
            // 2. String check (declared)
            if (is_string($value) && trim($value) !== '') {
                $normVal = mb_strtolower(trim($value));
                $normVal = str_replace(['é', 'è', 'ê', 'à', 'â', 'î', 'ô', 'û', 'ë', 'ï'], ['e', 'e', 'e', 'a', 'a', 'i', 'o', 'u', 'e', 'i'], $normVal);
                
                if (preg_match('/\b(medecin|dent|pharm)/i', $normVal)) {
                    $hasDoctor = true;
                }
                if (preg_match('/\b(kine|infirm|sage|nurs|soin|paramed|sport|rehab|ergo|ortho)/i', $normVal)) {
                    $hasParamedical = true;
                }
                if (preg_match('/\b(sante|health)/i', $normVal)) {
                    $hasDoctor = true;
                    $hasParamedical = true;
                }
            }
        }

        return ['doctor' => $hasDoctor, 'paramedical' => $hasParamedical];
    }

    private function hasMedicalInterest(array $interests): bool
    {
        $res = $this->resolveHealthInterests($interests);
        return $res['doctor'] || $res['paramedical'];
    }

    private function isHealthAcademicPath(array $profil): bool
    {
        $section = mb_strtolower(trim($profil['section_bac'] ?? $profil['filiere_etudiant_actuelle'] ?? ''));
        if ($section === '') {
            return false;
        }
        // Exclude sciences experimentales, biologie and SVT from automatically indicating medical path
        return preg_match('/sante|medical|paramedi|santé/i', $section) === 1;
    }

    private function applyMmrDiversity(array $sortedCandidates, int $limit): array
    {
        $candidatePool = array_slice($sortedCandidates, 0, $limit);
        $selected = [];
        $lambda = 0.12;

        while (!empty($candidatePool)) {
            $bestIndex = null;
            $bestScore = -INF;

            foreach ($candidatePool as $index => $candidate) {
                $maxSimilarity = 0.0;
                foreach ($selected as $selectedItem) {
                    $maxSimilarity = max($maxSimilarity, $this->recommendationSimilarity($candidate, $selectedItem));
                }

                $adjustedScore = $candidate['FinalScoreBase'] - ($lambda * $maxSimilarity);

                if (
                    $adjustedScore > $bestScore ||
                    (
                        abs($adjustedScore - $bestScore) < 1e-9 &&
                        $bestIndex !== null &&
                        ($candidate['FinalScoreBase'] <=> $candidatePool[$bestIndex]['FinalScoreBase']) > 0
                    )
                ) {
                    $bestScore = $adjustedScore;
                    $bestIndex = $index;
                }
            }

            $bestItem = $candidatePool[$bestIndex];
            $bestItem['FinalScore'] = $this->clamp01($bestScore);
            $bestItem['Score_Final'] = round($bestItem['FinalScore'], 4);
            $bestItem['RankScore'] = round($bestItem['FinalScore'], 4);
            $selected[] = $bestItem;

            array_splice($candidatePool, $bestIndex, 1);
        }

        return $selected;
    }

    /**
     * Génère les recommandations d'orientation pour un étudiant (v8.0 stable).
     */
    public function recommend(array $profilEtudiant, int $topN = 8): array
    {
        $filieres = $this->loadFilieres();

        if (empty($filieres)) {
            return ['error' => 'Données de filières indisponibles en base de données.'];
        }

        $scoreFg = (float) ($profilEtudiant['score_fg'] ?? 120);
        if ($scoreFg > 0 && $scoreFg <= 20) {
            $scoreFg = $scoreFg * 10;
        }

        $sectionBac   = $profilEtudiant['section_bac'] ?? $profilEtudiant['filiere_etudiant_actuelle'] ?? '';
        $riasecVec    = $profilEtudiant['vecteur_psychometrique'] ?? ['R'=>0.5,'I'=>0.5,'A'=>0.5,'S'=>0.5,'E'=>0.5,'C'=>0.5];
        $gatbRaw      = $profilEtudiant['gatb_scores'] ?? [];
        $codeHolland  = $profilEtudiant['code_holland'] ?? $this->getHollandFromVec($riasecVec);

        // Normalize section bac
        $normalizeBac = function ($str) {
            $str = mb_strtolower(trim($str ?? ''));
            $str = str_replace(['é', 'è', 'ê', 'à', 'â', 'î', 'ô', 'û'], ['e', 'e', 'e', 'a', 'a', 'i', 'o', 'u'], $str);
            return $str;
        };
        $normEtuBac = $normalizeBac($sectionBac);
        $bacTypeMap = [
            'mathematiques'           => 'mathematiques',
            'sciences experimentales' => 'sciences experimentales',
            'sciences'                => 'sciences experimentales',
            'economie et gestion'     => 'economie et gestion',
            'economie'                => 'economie et gestion',
            'technique'               => 'technique',
            'informatique'            => 'informatique',
            'lettres'                 => 'lettres',
            'sport'                   => 'sport',
        ];
        $normEtuBacMapped = $bacTypeMap[$normEtuBac] ?? $normEtuBac;

        // Détection du type de profil étudiant
        arsort($riasecVec);
        $dominantRiasec = array_key_first($riasecVec);

        $studentProfileType = 'default';
        if ($dominantRiasec === 'R' || $dominantRiasec === 'I' || in_array($normEtuBacMapped, ['mathematiques', 'sciences experimentales', 'technique', 'informatique'])) {
            $studentProfileType = 'scientific';
        } elseif ($dominantRiasec === 'A') {
            $studentProfileType = 'artistic';
        } elseif ($dominantRiasec === 'S' || $dominantRiasec === 'E' || $normEtuBacMapped === 'lettres') {
            $studentProfileType = 'social';
        }

        // Identify if GATB is empty/missing
        $isGatbEmpty = true;
        if (!empty($gatbRaw)) {
            foreach (['GATB_G', 'GATB_V', 'GATB_N', 'GATB_S', 'G', 'V', 'N', 'S'] as $key) {
                if (isset($gatbRaw[$key]) && (float)$gatbRaw[$key] > 0.0) {
                    $isGatbEmpty = false;
                    break;
                }
            }
        }

        // Deterministic Fallback if missing (population mean = 60)
        if ($isGatbEmpty) {
            $gatbRaw = ['G' => 60.0, 'V' => 60.0, 'N' => 60.0, 'S' => 60.0];
        }

        // ── Deterministic Scoring Run ───────────────────
        $scored = $this->runScoringPipeline($profilEtudiant, $filieres, $studentProfileType, $scoreFg, $gatbRaw, $isGatbEmpty);

        // Clamping ALL displayed values to [0.0, 1.0] for the UI
        $uiScored = [];
        foreach ($scored as $item) {
            $sdoValue = $this->getSDO($item);
            $uiScored[] = array_merge($item, [
                'confidence_flag'     => $isGatbEmpty ? 'LOW' : 'OK',
                'FinalScoreBase'      => $this->clamp01($item['FinalScore']),
                'Score_Final'         => round($item['FinalScore'], 4),
                'RankScore'           => round($item['FinalScore'], 4),
                'Score_Fit'           => round($item['FitScore'], 4),
                'Score_RIASEC'        => round($item['VocationScore'], 4),
                'Score_Academique'    => round($item['CognitiveScore'], 4),
                'Score_Marche'        => round($item['MarketScore'], 4),
                'Score_Accessibilite' => round($item['AccessScore'], 4),
                'Score_Motivation'    => round($item['MotivationScore'], 4),
                'SDO_Gap'             => round($item['FinalScore'] >= 0 ? ($this->getSDO($item) > 0 ? $scoreFg - $sdoValue : 0.0) : 0.0, 2),
                'is_exploratory'      => false,
                'is_pareto_optimal'   => true,
            ]);
        }

        // ── DEDUPLICATION LAYER ───────────────────────
        $uniqueScored = [];
        $seenNames = [];
        $seenCodes = [];
        $seenIds   = [];
        foreach ($uiScored as $item) {
            $nameKey = mb_strtolower(trim($item['Nom_Filiere'] ?? ''));
            $codeKey = mb_strtolower(trim($item['Code_Filiere'] ?? ''));
            $idKey   = $item['filiere_id'] ?? null;

            if (isset($seenNames[$nameKey]) || isset($seenCodes[$codeKey]) || ($idKey !== null && isset($seenIds[$idKey]))) {
                continue;
            }
            $seenNames[$nameKey] = true;
            $seenCodes[$codeKey] = true;
            if ($idKey !== null) {
                $seenIds[$idKey] = true;
            }
            $uniqueScored[] = $item;
        }

        // ── STRICT 3D PARETO DOMINANCE ─────────────────
        foreach ($uniqueScored as &$f1) {
            $isPareto = true;
            foreach ($uniqueScored as $f2) {
                if ($f1['Code_Filiere'] === $f2['Code_Filiere']) continue;

                $fit1 = $f1['FitScore'];
                $fit2 = $f2['FitScore'];

                $market1 = $f1['MarketScore'];
                $market2 = $f2['MarketScore'];

                $access1 = $f1['AccessScore'];
                $access2 = $f2['AccessScore'];

                // Strict 3D Pareto dominance check (Fit, Feasibility, Market)
                $dominated = (
                    $fit2 >= $fit1 &&
                    $market2 >= $market1 &&
                    $access2 >= $access1 &&
                    ($fit2 > $fit1 || $market2 > $market1 || $access2 > $access1)
                );

                if ($dominated) {
                    $isPareto = false;
                    break;
                }
            }
            $f1['is_pareto_optimal'] = $isPareto;
        }
        unset($f1);

        // ── MMR DIVERSITY LAYER (λ = 0.12) ─────────────
        $candidates = $uniqueScored;
        usort($candidates, function ($a, $b) {
            if ($b['FinalScore'] === $a['FinalScore']) {
                return ($a['filiere_id'] ?? 0) <=> ($b['filiere_id'] ?? 0);
            }
            return $b['FinalScore'] <=> $a['FinalScore'];
        });
        $uniqueScored = $this->applyMmrDiversity($candidates, min(100, count($candidates)));

        // ── BOUNDED SERENDIPITY SLOT ───────────────────
        $serendipityItem = null;
        $bestSerendipityScore = -1.0;
        $serendipityIndex = null;

        $candidateCount = min(15, count($uniqueScored));
        for ($i = 3; $i < $candidateCount; $i++) {
            $c = $uniqueScored[$i];
            $sdoVal = $this->getSDO($c);
            $gapVal = $scoreFg - $sdoVal;
            if ($sdoVal > 0 && $gapVal > 55.0) {
                continue;
            }
            if (($c['AccessScore'] ?? 0.0) > 0.40) {
                $serenScore = 0.7 * ($c['VocationScore'] ?? 0.0) + 0.3 * ($c['MarketScore'] ?? 0.0);
                if ($serenScore > $bestSerendipityScore) {
                    $bestSerendipityScore = $serenScore;
                    $serendipityIndex = $i;
                }
            }
        }

        if ($serendipityIndex !== null) {
            $serendipityItem = $uniqueScored[$serendipityIndex];
            $serendipityItem['is_exploratory'] = true;
            $serendipityItem['is_serendipity'] = true;

            // Remove from original ranking
            array_splice($uniqueScored, $serendipityIndex, 1);
        }

        // UI Classification
        $ambitieusesScored = [];
        $optimalScored = [];
        $accessibleScored = [];
        $securiteScored = [];

        foreach ($uniqueScored as $item) {
            $sdo = $this->getSDO($item);
            $gap = $scoreFg - $sdo;

            if ($sdo <= 0) {
                $securiteScored[] = $item;
            } elseif ($gap < 0) {
                if ($gap >= -15.0) {
                    $ambitieusesScored[] = $item;
                }
            } else {
                if ($gap > 55.0) {
                    $securiteScored[] = $item;
                } else {
                    if ($item['FinalScore'] >= 0.60) {
                        $optimalScored[] = $item;
                    } else {
                        $accessibleScored[] = $item;
                    }
                }
            }
        }

        // Sorting
        $sortFn = function($a, $b) {
            if ($b['Score_Final'] === $a['Score_Final']) {
                return $b['filiere_id'] <=> $a['filiere_id'];
            }
            return $b['Score_Final'] <=> $a['Score_Final'];
        };

        usort($ambitieusesScored, $sortFn);
        usort($optimalScored, $sortFn);
        usort($accessibleScored, $sortFn);
        usort($securiteScored, $sortFn);

        // Fallback transfers: ensure we have at least min(3, $topN) top recommendations
        $minTop = min(3, $topN);
        if (count($optimalScored) < $minTop) {
            $need = $minTop - count($optimalScored);

            // Pull from accessible first
            if ($need > 0 && count($accessibleScored) > 0) {
                $take = min($need, count($accessibleScored));
                $optimalScored = array_merge($optimalScored, array_slice($accessibleScored, 0, $take));
                $accessibleScored = array_slice($accessibleScored, $take);
                $need -= $take;
            }

            // Then from ambitious list
            if ($need > 0 && count($ambitieusesScored) > 0) {
                $take = min($need, count($ambitieusesScored));
                $optimalScored = array_merge($optimalScored, array_slice($ambitieusesScored, 0, $take));
                $ambitieusesScored = array_slice($ambitieusesScored, $take);
                $need -= $take;
            }

            // Finally from securite (only promote those with gap <= 55)
            if ($need > 0 && count($securiteScored) > 0) {
                $promotable = [];
                $nonPromotable = [];
                foreach ($securiteScored as $secItem) {
                    $secSdo = $this->getSDO($secItem);
                    $secGap = $scoreFg - $secSdo;
                    if ($secSdo > 0 && $secGap > 55.0) {
                        $nonPromotable[] = $secItem;
                    } else {
                        $promotable[] = $secItem;
                    }
                }

                $take = min($need, count($promotable));
                $optimalScored = array_merge($optimalScored, array_slice($promotable, 0, $take));
                $securiteScored = array_merge(array_slice($promotable, $take), $nonPromotable);
                $need -= $take;
            }
        }

        $topOptimal = array_slice($optimalScored, 0, $topN);

        // Inject serendipity item strictly at the final position N of topOptimal if available
        if ($serendipityItem !== null) {
            if (count($topOptimal) >= $topN) {
                $topOptimal[$topN - 1] = $serendipityItem;
            } else {
                $topOptimal[] = $serendipityItem;
            }
        }

        $topAmbitieuses = array_slice($ambitieusesScored, 0, $topN);
        $topAccessibles = array_slice($accessibleScored, 0, $topN);
        $topSecurite = array_slice($securiteScored, 0, $topN);

        $processList = function(&$list) {
            foreach ($list as $i => &$item) {
                $item['Rang'] = $i + 1;
                $item['Score_Final_Contextuel'] = $item['Score_Final'];
                $item['Compatibilite_Psychometrique'] = $item['Score_RIASEC'];
                $item['Career_Path'] = [
                    'domain_label' => $item['Domaine'] ?? 'Domaine Professionnel',
                    'careers' => $item['Careers'] ?? []
                ];
            }
            unset($item);
        };

        $processList($topAmbitieuses);
        $processList($topOptimal);
        $processList($topAccessibles);
        $processList($topSecurite);

        $gapAnalysis = $this->gapAnalysis($profilEtudiant, $topOptimal[0] ?? $topAmbitieuses[0] ?? null, $scoreFg);
        $diagnostic = $this->buildDiagnostic($codeHolland, $riasecVec, $gatbRaw, $scoreFg, $sectionBac);

        return [
            'recommandations'      => $topOptimal,
            'accessibles'          => $topAccessibles,
            'securite'             => $topSecurite,
            'ambitieuses'          => $topAmbitieuses,
            'diagnostic'           => $diagnostic,
            'gap_analysis'         => $gapAnalysis,
            'code_holland'         => $codeHolland,
            'confidence_flag'      => $isGatbEmpty ? 'LOW' : 'OK',
            'score_fg'             => $scoreFg,
            'total_filieres'       => count($filieres),
            'total_scorees'        => count($scored),
        ];
    }

    /**
     * Executes the scoring and hard-filtering logic.
     */
    private function runScoringPipeline(
        array $profil,
        array $filieres,
        string $studentProfileType,
        float $scoreFg,
        array $gatbRaw,
        bool $isGatbEmpty
    ): array {
        $riasecVec = $profil['vecteur_psychometrique'] ?? ['R'=>0.5,'I'=>0.5,'A'=>0.5,'S'=>0.5,'E'=>0.5,'C'=>0.5];

        $bigFive   = $profil['big_five'] ?? ['O'=>0.0,'C'=>0.0,'E'=>0.0,'A'=>0.0,'N'=>0.0];
        $valeurs   = $profil['valeurs'] ?? ['Sec'=>0.0,'Ach'=>0.0,'Ben'=>0.0,'Aut'=>0.0];
        $interests = $profil['interests'] ?? [];

        // Normalise student RIASEC using Sigmoid Z-Score
        $riasecStudentNorm = [];
        foreach ($riasecVec as $k => $v) {
            $rawV = $v <= 1.0 ? $v * 100.0 : $v;
            $riasecStudentNorm[$k] = $this->sigmoidNormalize($rawV, 50.0, 15.0);
        }

        // Normalize student GATB using Sigmoid Z-Score (with population mean = 60)
        $gatbStudentNorm = [
            'G' => $this->sigmoidNormalize($gatbRaw['GATB_G'] ?? $gatbRaw['G'] ?? 60.0, 60.0, 15.0),
            'V' => $this->sigmoidNormalize($gatbRaw['GATB_V'] ?? $gatbRaw['V'] ?? 60.0, 60.0, 15.0),
            'N' => $this->sigmoidNormalize($gatbRaw['GATB_N'] ?? $gatbRaw['N'] ?? 60.0, 60.0, 15.0),
            'S' => $this->sigmoidNormalize($gatbRaw['GATB_S'] ?? $gatbRaw['S'] ?? 60.0, 60.0, 15.0),
        ];

        // Normalize Student Personality on the production axes: Stability, Social, Innovation.
        $cNorm   = $this->sigmoidNormalize($bigFive['C'] ?? 0.0, 0.0, 1.0);
        $nNorm   = $this->sigmoidNormalize($bigFive['N'] ?? 0.0, 0.0, 1.0);
        $eNorm   = $this->sigmoidNormalize($bigFive['E'] ?? 0.0, 0.0, 1.0);
        $aNorm   = $this->sigmoidNormalize($bigFive['A'] ?? 0.0, 0.0, 1.0);
        $oNorm   = $this->sigmoidNormalize($bigFive['O'] ?? 0.0, 0.0, 1.0);
        $autNorm = $this->sigmoidNormalize($valeurs['Aut'] ?? 0.0, 0.0, 1.0);

        $studentAxis = [
            'Stability'  => ($cNorm + (1.0 - $nNorm)) / 2.0,
            'Social'     => ($eNorm + $aNorm) / 2.0,
            'Innovation' => ($oNorm + $autNorm) / 2.0,
        ];

        $codeHolland = $profil['code_holland'] ?? $this->getHollandFromVec($riasecVec);
        $healthInterests = $this->resolveHealthInterests($interests);
        $isHealthAcademic = $this->isHealthAcademicPath($profil);
        $studentDomainVector = $this->getStudentDomainVector($profil, $riasecStudentNorm, $gatbRaw);

        $results = [];
        foreach ($filieres as $f) {
            $nomFiliere = strtolower($f['Nom_Filiere'] ?? '');
            $codeRiasec = strtoupper(trim($f['Code_RIASEC'] ?? ''));
            $domain     = $this->detectDomain($nomFiliere, $codeRiasec);

            // Hard Filter 1: Bac Compatibility
            $normalizeBac = function ($str) {
                $str = mb_strtolower(trim($str ?? ''));
                $str = str_replace(['é', 'è', 'ê', 'à', 'â', 'î', 'ô', 'û'], ['e', 'e', 'e', 'a', 'a', 'i', 'o', 'u'], $str);
                return $str;
            };
            $normEtuBac = $normalizeBac($profil['section_bac'] ?? $profil['filiere_etudiant_actuelle'] ?? '');
            $fBac = $normalizeBac($f['Type_Bac'] ?? 'General');

            if ($fBac !== 'general' && $normEtuBac !== '') {
                $bacMap = [
                    'mathematiques'           => 'mathematiques',
                    'sciences experimentales' => 'sciences experimentales',
                    'economie et gestion'     => 'economie et gestion',
                    'technique'               => 'technique',
                    'informatique'            => 'informatique',
                    'lettres'                 => 'lettres',
                    'sport'                   => 'sport',
                ];
                $normEtuBacMapped = $bacMap[$normEtuBac] ?? $normEtuBac;
                if ($fBac !== $normEtuBacMapped) {
                    continue; // Reject
                }
            }

            // Hard Filter 2: Probabilistic Health Filter
            $notesMatieres = $profil['notes_matieres'] ?? [];
            $svtGrade = (float) ($notesMatieres['svt'] ?? $notesMatieres['bio'] ?? 12.0);

            $nomFilNorm = str_replace(
                ['é','è','ê','à','â','î','ô','û','ù','ë','ï'],
                ['e','e','e','a','a','i','o','u','u','e','i'],
                $nomFiliere
            );

            $isSanteFiliere = (
                $domain === 'sante' ||
                preg_match('/medecin|pharmac|dentair|kine|infirmier|sage.femm|soins|paramedi|veterin|nutrition|ergother|reanimat|pediatr|puericult|audiolog|orthophon/', $nomFilNorm)
            );

            if ($isSanteFiliere) {
                $isDoctorFiliere = preg_match('/medecin|pharmac|dentair/i', $nomFilNorm) === 1;
                $isParamedicalFiliere = preg_match('/kine|infirmier|sage.femm|soins|paramedi|ergother|reanimat|pediatr|puericult|audiolog|orthophon|nutrition|veterin/i', $nomFilNorm) === 1;

                $allowed = false;
                if ($isDoctorFiliere && ($healthInterests['doctor'] || $isHealthAcademic)) {
                    $allowed = true;
                }
                if ($isParamedicalFiliere && ($healthInterests['paramedical'] || $isHealthAcademic)) {
                    $allowed = true;
                }
                // If it's a general health track or unknown health type
                if (!$isDoctorFiliere && !$isParamedicalFiliere && ($healthInterests['doctor'] || $healthInterests['paramedical'] || $isHealthAcademic)) {
                    $allowed = true;
                }

                if (!$allowed) {
                    continue; // Exclude health tracks for students sans intérêt médical explicite
                }
            }

            $healthPenalty = 1.0;
            if ($isSanteFiliere) {
                $svtSigmoid  = 1.0 / (1.0 + exp(-(12 - $svtGrade)));
                $gatbGVal = (float) ($gatbRaw['GATB_G'] ?? $gatbRaw['G'] ?? 50.0);
                $gatbSigmoid = 1.0 / (1.0 + exp(-(55 - $gatbGVal)));
                $hr = $svtSigmoid + $gatbSigmoid;

                if ($hr > 0.85) {
                    continue; // Reject for health risk
                } elseif ($hr >= 0.60) {
                    $healthPenalty = 0.5; // Strong Penalty
                }
            }

            // 1. Vocation Score
            $filiereRiasecVec = [
                'R' => $f['riasec_r'] ?? 0.5,
                'I' => $f['riasec_i'] ?? 0.5,
                'A' => $f['riasec_a'] ?? 0.5,
                'S' => $f['riasec_s'] ?? 0.5,
                'E' => $f['riasec_e'] ?? 0.5,
                'C' => $f['riasec_c'] ?? 0.5,
            ];
            $riasecFiliereNorm = [];
            foreach ($filiereRiasecVec as $k => $v) {
                $riasecFiliereNorm[$k] = $this->sigmoidNormalize($v * 100.0, 50.0, 15.0);
            }
            $riasecCosine = $this->cosineSimilarity($riasecStudentNorm, $riasecFiliereNorm);

            $filiereDim = $this->getFiliereDimension($nomFiliere, $domain);
            $domainInterestMatch = $this->domainInterestMatch($interests, $filiereDim, $domain);
            $vocationScore = $this->clamp01(0.8 * $riasecCosine + 0.2 * $domainInterestMatch);

            // Minimal Vocation/RIASEC Threshold skip (v8.0: 0.28)
            if ($vocationScore < 0.28) {
                continue;
            }

            // Compute DomainScore
            $fDomains = $this->getFiliereDomains($nomFiliere, $codeRiasec);
            $fDomainPrincipal = $fDomains['principal'];
            $fDomainSecondaire = $fDomains['secondaire'];
            if ($fDomainSecondaire !== null) {
                $domainScore = 0.7 * ($studentDomainVector[$fDomainPrincipal] ?? 0.0) + 0.3 * ($studentDomainVector[$fDomainSecondaire] ?? 0.0);
            } else {
                $domainScore = $studentDomainVector[$fDomainPrincipal] ?? 0.0;
            }
            $domainScore = $this->clamp01($domainScore);

            // Minimal Domain Compatibility Threshold skip (v8.0: 0.22)
            if ($domainScore < 0.22) {
                continue;
            }

            // 2. Cognitive Score calculation
            $gatbFiliereNorm = [
                'G' => $this->sigmoidNormalize((float) ($f['gatb_g_required'] ?? 60.0), 60.0, 15.0),
                'V' => $this->sigmoidNormalize((float) ($f['gatb_v_required'] ?? 60.0), 60.0, 15.0),
                'N' => $this->sigmoidNormalize((float) ($f['gatb_n_required'] ?? 60.0), 60.0, 15.0),
                'S' => $this->sigmoidNormalize((float) ($f['gatb_s_required'] ?? 60.0), 60.0, 15.0),
            ];
            $cognitiveScore = $this->cosineSimilarity($gatbStudentNorm, $gatbFiliereNorm);

            // 3. Personality Motivation Score
            $filiereB5 = [
                'O' => $f['big5_openness'] ?? 0.0,
                'C' => $f['big5_conscientiousness'] ?? 0.0,
                'E' => $f['big5_extraversion'] ?? 0.0,
                'A' => $f['big5_agreeableness'] ?? 0.0,
                'N' => $f['big5_neuroticism'] ?? 0.0,
            ];
            $valProfile = $this->domainPsychoprofile[$domain]['Val'] ?? [];

            $fC = $this->sigmoidNormalize($filiereB5['C'], 0.0, 1.0);
            $fN = $this->sigmoidNormalize($filiereB5['N'], 0.0, 1.0);
            $fE = $this->sigmoidNormalize($filiereB5['E'], 0.0, 1.0);
            $fA = $this->sigmoidNormalize($filiereB5['A'], 0.0, 1.0);
            $fO = $this->sigmoidNormalize($filiereB5['O'], 0.0, 1.0);
            $fAut = $this->sigmoidNormalize($valProfile['Aut'] ?? 0.0, 0.0, 1.0);

            $filiereAxis = [
                'Stability'  => ($fC + (1.0 - $fN)) / 2.0,
                'Social'     => ($fE + $fA) / 2.0,
                'Innovation' => ($fO + $fAut) / 2.0,
            ];
            $motivationScore = $this->cosineSimilarity($studentAxis, $filiereAxis);

            // 4. Access Score (Academic Proximity Matching)
            $sdo = $this->getSDO($f);
            if ($sdo <= 0) {
                $accessScore = 0.50;
            } else {
                $gap = $scoreFg - $sdo;
                if ($gap < 0) {
                    $accessScore = $this->clamp01(0.5 - 0.5 * (abs($gap) / 15.0));
                } else {
                    $accessScore = $this->clamp01(1.0 - 0.5 * ($gap / 55.0));
                }
            }

            // 5. Market Score
            $marketScore = $this->clamp01(
                0.5 * $this->clamp01((float) $f['job_demand'])
              + 0.3 * $this->clamp01((float) $f['salary'])
              + 0.2 * $this->clamp01((float) $f['internships'])
            );

            $fitScore = $this->clamp01(0.5 * $vocationScore + 0.5 * $cognitiveScore);

            // 6. Unified Scoring Model (v8.0 weights: RIASEC 20%, Domain 25%, GATB 25%, Access 15%, Market 10%, Motivation 5%)
            $finalScore = 0.20 * $vocationScore
                        + 0.25 * $domainScore
                        + 0.25 * $cognitiveScore
                        + 0.15 * $accessScore
                        + 0.10 * $marketScore
                        + 0.05 * $motivationScore;

            $finalScore = $this->clamp01($finalScore * $healthPenalty);

            // Coherence Penalty (vocation < 0.45 && cognitive > 0.85) -> -15%
            if ($vocationScore < 0.45 && $cognitiveScore > 0.85) {
                $finalScore *= 0.85;
            }

            // Holland / RIASEC consistency boost or penalty
            $studentLetters = array_filter(array_map('strtoupper', str_split(substr($codeHolland ?? '', 0, 3))));
            $filLetters = array_filter(array_map('strtoupper', str_split(substr($f['Code_RIASEC'] ?? '', 0, 3))));
            $matches = count(array_intersect($studentLetters, $filLetters));
            if ($matches > 0) {
                // small deterministic boost per matching letter (max 10%)
                $hollandBoost = min(0.10, 0.05 * $matches);
            } else {
                // deterministic penalty for clear mismatch (-8%)
                $hollandBoost = -0.08;
            }

            $finalScore = $this->clamp01($finalScore + $hollandBoost);

            // Cognitive Penalty computation
            $penaliteCognitive = 0.0;
            if ($isGatbEmpty) {
                $penaliteCognitive = 0.0;
            } else {
                $gReq = (float) ($f['gatb_g_required'] ?? 50);
                $vReq = (float) ($f['gatb_v_required'] ?? 50);
                $nReq = (float) ($f['gatb_n_required'] ?? 50);
                $sReq = (float) ($f['gatb_s_required'] ?? 50);

                $gVal = (float) ($gatbRaw['GATB_G'] ?? $gatbRaw['G'] ?? 0);
                $vVal = (float) ($gatbRaw['GATB_V'] ?? $gatbRaw['V'] ?? 0);
                $nVal = (float) ($gatbRaw['GATB_N'] ?? $gatbRaw['N'] ?? 0);
                $sVal = (float) ($gatbRaw['GATB_S'] ?? $gatbRaw['S'] ?? 0);

                $penalties = [];
                if ($gVal < $gReq) $penalties[] = ($gReq - $gVal) / 100.0;
                if ($vVal < $vReq) $penalties[] = ($vReq - $vVal) / 100.0;
                if ($nVal < $nReq) $penalties[] = ($nReq - $nVal) / 100.0;
                if ($sVal < $sReq) $penalties[] = ($sReq - $sVal) / 100.0;

                if (!empty($penalties)) {
                    $penaliteCognitive = max($penalties);
                }
            }

            $results[] = array_merge($f, [
                'VocationScore'      => $vocationScore,
                'CognitiveScore'     => $cognitiveScore,
                'MotivationScore'    => $motivationScore,
                'AccessScore'        => $accessScore,
                'MarketScore'        => $marketScore,
                'DomainScore'        => $domainScore,
                'FitScore'           => $fitScore,
                'FinalScore'         => $finalScore,
                'Explication'        => $this->buildExplication(
                    $vocationScore, $cognitiveScore, $marketScore, $domain,
                    $scoreFg, $sdo, $accessScore, $riasecStudentNorm, $riasecFiliereNorm, $codeHolland, $f, $domainScore
                )
            ]);
        }

        return $results;
    }

    /**
     * Helper to retrieve SDO.
     */
    private function getSDO(array $filiere): float
    {
        foreach (['SDO_2025', 'SDO_2024', 'SDO_2023'] as $col) {
            $v = $filiere[$col] ?? null;
            if ($v !== null && $v !== '' && is_numeric($v) && (float)$v > 0) {
                return (float) $v;
            }
        }
        return 0.0;
    }

    /**
     * Detect domain based on filename and code.
     */
    private function detectDomain(string $nom, string $code): string
    {
        $nom = mb_strtolower($nom);
        $nomNorm = str_replace(
            ['é','è','ê','à','â','î','ô','û','ù'],
            ['e','e','e','a','a','i','o','u','u'],
            $nom
        );

        if (preg_match('/inform|algorithme|reseau|systeme|logiciel|cyber/', $nomNorm)) return 'informatique';
        if (preg_match('/medecin|sante|pharmac|infirmier|kine|dentair|sage.femme|obstetr|soins|ergother|reanimat|pediatr|puericult|audiolog|orthophon|nutrition|paramedi|veterin/', $nomNorm)) return 'sante';
        if (preg_match('/genie|ingenierie|mecanique|electrique|civil|industri/', $nomNorm)) return 'technique';
        if (preg_match('/biologie|chimie|physique|sciences/', $nomNorm)) return 'sciences';
        if (preg_match('/econom|gestion|commerc|finance|comptab|banque|marketing/', $nomNorm)) return 'economie';
        if (preg_match('/math|statistique/', $nomNorm)) return 'sciences';
        if (preg_match('/psycho|sociolog/', $nomNorm)) return 'social';
        if (preg_match('/droit|juridique|notariat/', $nomNorm)) return 'social';
        if (preg_match('/sport|education physique/', $nomNorm)) return 'social';
        if (preg_match('/lettr|arabe|anglais|francais|histoire|philosoph|geograph/', $nomNorm)) return 'lettres';
        if (preg_match('/art|design|music|archit|communication|journalisme/', $nomNorm)) return 'arts';

        $first = substr($code, 0, 1);
        return match($first) {
            'R' => 'technique',
            'I' => 'sciences',
            'A' => 'arts',
            'S' => 'social',
            'E' => 'economie',
            'C' => 'economie',
            default => 'default',
        };
    }

    /**
     * Get target dimension for the filiere.
     */
    private function getFiliereDimension(string $nom, string $domain): ?string
    {
        $nom = mb_strtolower($nom);

        if (str_contains($nom, 'médecin') || str_contains($nom, 'dentaire')) return 'MED';
        if (str_contains($nom, 'pharmacie')) return 'MED';
        if (str_contains($nom, 'infirmier') || str_contains($nom, 'sage-femme')) return 'MED';

        if ($domain === 'informatique') return 'INFO';
        if ($domain === 'technique' || str_contains($nom, 'ingénieur')) return 'ENG';
        if ($domain === 'economie' || $domain === 'gestion') return 'ECO';
        if ($domain === 'lettres') return 'LTR';
        if ($domain === 'arts') return 'ART';
        if ($domain === 'social') {
            if (str_contains($nom, 'droit')) return 'DROIT';
            if (str_contains($nom, 'éducation') || str_contains($nom, 'enseign')) return 'EDU';
            if (str_contains($nom, 'psyc')) return 'SOC';
            if (str_contains($nom, 'sport')) return 'SPO';
            return 'SOC';
        }
        if (str_contains($nom, 'architecte')) return 'ARCHI';

        return null;
    }

    private function getHollandFromVec(array $vec): string
    {
        arsort($vec);
        return implode('', array_slice(array_keys($vec), 0, 3));
    }

    /**
     * Build explanations for recommendations (using Dominant RIASEC).
     */
    private function buildExplication(
        float $vocation,
        float $acad,
        float $marche,
        string $domain,
        float $scoreFg,
        float $sdo,
        float $accessScore,
        array $riasecStudentNorm = [],
        array $riasecFiliereNorm = [],
        string $codeHolland = 'ISA',
        array $filiere = [],
        float $domainScore = 0.5
    ): array {
        $raisons = [];
        $points_forts = [];
        $points_faibles = [];
        $axes_amelioration = [];

        $nomF = $filiere['Nom_Filiere'] ?? 'cette filière';
        $targetCode = strtoupper(trim($filiere['Code_RIASEC'] ?? ''));

        arsort($riasecStudentNorm);
        arsort($riasecFiliereNorm);

        $riasecStudentLetters = array_keys($riasecStudentNorm);
        $riasecFiliereLetters = array_keys($riasecFiliereNorm);

        $dominantStudent = $riasecStudentLetters[0] ?? 'I';
        $dominantFiliere = $riasecFiliereLetters[0] ?? 'I';

        // 1. RIASEC Match Bullet
        $riasecLabels = [
            'R' => 'Réaliste',
            'I' => 'Investigateur',
            'A' => 'Artistique',
            'S' => 'Social',
            'E' => 'Entreprenant',
            'C' => 'Conventionnel',
        ];
        $riasecFrench = $riasecLabels[$dominantStudent] ?? 'Investigateur';
        $vocationLevel = $vocation >= 0.70 ? 'fortement compatible' : ($vocation >= 0.50 ? 'compatible' : 'modérément compatible');
        $raisons[] = "votre profil dominant $riasecFrench est $vocationLevel avec les exigences de la filière (adéquation vocationnelle de " . round($vocation * 100) . "%).";

        // 2. Cognitive Match Bullet
        $acadLevel = $acad >= 0.80 ? 'très élevées' : ($acad >= 0.60 ? 'adaptées' : 'modérées');
        $aptitudeDesc = 'vos aptitudes cognitives globales';
        if (in_array($domain, ['informatique', 'sciences', 'ingenieur'])) {
            $aptitudeDesc = 'vos aptitudes numériques et analytiques';
        } elseif (in_array($domain, ['lettres', 'social', 'juridique'])) {
            $aptitudeDesc = 'vos aptitudes verbales et relationnelles';
        }
        $raisons[] = "$aptitudeDesc sont $acadLevel pour ce cursus (adéquation cognitive de " . round($acad * 100) . "%).";

        // 3. Domain Coherence Bullet
        $domainFrench = match($domain) {
            'informatique' => "l'informatique et les technologies",
            'sante' => 'la santé et la médecine',
            'ingenieur' => "l'ingénierie et la technique",
            'sciences' => 'les sciences exactes',
            'gestion' => 'la gestion et le commerce',
            'juridique' => 'le droit et le juridique',
            'lettres' => 'les lettres et langues',
            'arts' => 'les arts et le design',
            'social' => 'les sciences sociales',
            default => 'votre profil',
        };
        $raisons[] = "votre domaine d'intérêt principal est orienté vers $domainFrench (cohérence domaine de " . round($domainScore * 100) . "%).";

        // 4. Academic Access Bullet
        if ($sdo > 0) {
            $gap = $scoreFg - $sdo;
            if ($gap < 0) {
                $ecart = round(abs($gap), 1);
                $raisons[] = "votre score académique (" . round($scoreFg, 1) . ") est inférieur au seuil historique d'admission (" . round($sdo, 1) . ").";
                $points_faibles[] = "Admission ambitieuse (écart de -$ecart points).";
            } elseif ($gap > 55.0) {
                $raisons[] = "votre score académique (" . round($scoreFg, 1) . ") dépasse très largement le seuil historique d'admission (" . round($sdo, 1) . ").";
                $points_forts[] = "Accès académique garanti (Option de sécurité).";
            } else {
                $raisons[] = "votre score académique (" . round($scoreFg, 1) . ") dépasse le seuil historique d'admission (" . round($sdo, 1) . ").";
                $points_forts[] = "Accès académique sécurisé.";
            }
        } else {
            $raisons[] = "l'accès à cette filière est ouvert sur le plan académique.";
        }

        // 5. Job / Market Bullet
        $jobDemand = $filiere['job_demand'] ?? 0.60;
        if ($jobDemand >= 0.70) {
            $raisons[] = "les perspectives d'emploi et d'insertion professionnelle sont très favorables en Tunisie.";
            $points_forts[] = "Secteur d'avenir avec un marché de l'emploi dynamique en Tunisie.";
        } else {
            $raisons[] = "les perspectives de débouchés et d'emploi sur le marché tunisien sont stables.";
        }

        $gatbMatchPct = round($acad * 100);
        if ($gatbMatchPct >= 75) {
            $points_forts[] = "Forte adéquation cognitive globale ($gatbMatchPct%).";
        }

        return [
            'vocation_match'      => round($vocation * 100, 0) . "%",
            'cognitive_match'     => round($acad * 100, 0) . "%",
            'academic_access'     => $sdo > 0 ? (round($scoreFg - $sdo, 1) >= 0 ? "Favorable" : "Ambitieux") : "Non défini",
            'motivation_fit'      => "Favorable",
            'constraints_applied' => $sdo > 0 ? "SDO appliqué" : "Aucun",
            'market_signal'       => "Indice marché: " . round($marche * 100, 0) . "% (Source: " . ($filiere['market_source'] ?? 'ANETI') . ")",
            'raisons'             => $raisons,
            'points_forts'        => $points_forts,
            'points_faibles'      => $points_faibles,
            'axes_amelioration'   => $axes_amelioration,
            'alternatives'        => [
                'plus_sure'       => "Filière connexe dans le domaine $domain avec un SDO inférieur.",
                'plus_ambitieuse' => "Filière de pointe connexe dans le domaine $domain avec un SDO supérieur."
            ]
        ];
    }

    /**
     * Compute Gap analysis.
     */
    private function gapAnalysis(array $profil, ?array $topFiliere, float $scoreFg): array
    {
        if (!$topFiliere) return [];

        $sdo = $this->getSDO($topFiliere);
        $gap = $sdo > 0 ? round($sdo - $scoreFg, 2) : 0;

        $result = [
            'filiere_cible' => $topFiliere['Nom_Filiere'] ?? '',
            'etablissement' => $topFiliere['Etablissement'] ?? '',
            'score_fg_etudiant' => $scoreFg,
            'sdo_filiere'   => $sdo,
            'ecart_fg'      => $gap,
            'statut'        => $gap <= -15.0 ? 'Accès Sécurisé' : ($gap <= 0 ? 'Accessible' : ($gap <= 15 ? 'Effort requis' : 'Difficile')),
            'axes_amelioration' => [],
        ];

        if ($gap > 0) {
            $result['axes_amelioration'][] = "Améliorer la moyenne générale d'environ " . round($gap / 4, 1) . " points";
        }

        return $result;
    }

    /**
     * Diagnostic global statement.
     */
    private function buildDiagnostic(string $code, array $riasecVec, array $gatbRaw, float $scoreFg, string $section): array
    {
        arsort($riasecVec);
        $dominant = array_keys($riasecVec)[0] ?? 'I';

        $profiles = [
            'R' => 'profil pragmatique et technique',
            'I' => 'profil analytique et scientifique',
            'A' => 'profil créatif et expressif',
            'S' => 'profil relationnel et altruiste',
            'E' => 'profil entreprenant et ambitieux',
            'C' => 'profil organisé et méthodique',
        ];

        $avgGatb = count($gatbRaw) > 0 ? array_sum($gatbRaw) / count($gatbRaw) : 60;

        $niveau = match(true) {
            $scoreFg >= 170 => 'Excellent',
            $scoreFg >= 140 => 'Très bon',
            $scoreFg >= 110 => 'Bon',
            $scoreFg >= 80  => 'Moyen',
            default         => 'Faible',
        };

        return [
            'diagnostic'   => "Votre code RIASEC dominant est **{$code}**, révélant un " . ($profiles[$dominant] ?? 'profil équilibré') . ". Avec un Score FG de {$scoreFg} (niveau {$niveau}), vous disposez d'un profil solide d'accès aux recommandations d'orientation ci-dessous.",
            'score'        => round($avgGatb, 2),
            'niveau_fg'    => $niveau,
            'code_holland' => $code,
        ];
    }

    /**
     * Detect principal and secondary domains for a filiere.
     */
    private function getFiliereDomains(string $nomFiliere, string $codeRiasec): array
    {
        $nom = mb_strtolower($nomFiliere);
        $nomNorm = str_replace(
            ['é','è','ê','à','â','î','ô','û','ù','ë','ï'],
            ['e','e','e','a','a','i','o','u','u','e','i'],
            $nom
        );

        $principal = 'default';
        $secondaire = null;

        if (preg_match('/inform|algorithme|reseau|systeme|logiciel|cyber/', $nomNorm)) {
            $principal = 'informatique';
            if (preg_match('/gestion|econom|commerc|finance|comptab|banque|marketing/', $nomNorm)) {
                $secondaire = 'gestion';
            } elseif (preg_match('/genie|ingenierie|mecanique|electrique|civil|industri/', $nomNorm)) {
                $secondaire = 'ingenieur';
            }
        } elseif (preg_match('/medecin|sante|pharmac|infirmier|kine|dentair|sage.femme|obstetr|soins|ergother|reanimat|pediatr|puericult|audiolog|orthophon|nutrition.humain|paramedi/', $nomNorm)) {
            $principal = 'sante';
        } elseif (preg_match('/genie|ingenierie|mecanique|electrique|civil|industri|technologique/', $nomNorm)) {
            $principal = 'ingenieur';
        } elseif (preg_match('/biologie|chimie|physique|sciences|math|statistique/', $nomNorm)) {
            $principal = 'sciences';
        } elseif (preg_match('/econom|gestion|commerc|finance|comptab|banque|marketing|administration/', $nomNorm)) {
            $principal = 'gestion';
        } elseif (preg_match('/droit|juridique|notariat/', $nomNorm)) {
            $principal = 'juridique';
        } elseif (preg_match('/lettr|arabe|anglais|francais|histoire|philosoph|geograph/', $nomNorm)) {
            $principal = 'lettres';
        } elseif (preg_match('/art|design|music|archit|communication|journalisme/', $nomNorm)) {
            $principal = 'arts';
        } elseif (preg_match('/psycho|sociolog|sport|education physique|social/', $nomNorm)) {
            $principal = 'social';
        } else {
            $first = substr($codeRiasec, 0, 1);
            $principal = match($first) {
                'R' => 'ingenieur',
                'I' => 'sciences',
                'A' => 'arts',
                'S' => 'social',
                'E' => 'gestion',
                'C' => 'gestion',
                default => 'default',
            };
        }

        return ['principal' => $principal, 'secondaire' => $secondaire];
    }

    /**
     * Compute student domain compatibility weights for each domain.
     */
    private function getStudentDomainVector(array $profil, array $riasecStudentNorm, array $gatbRaw): array
    {
        $domains = ['informatique', 'sante', 'ingenieur', 'sciences', 'gestion', 'juridique', 'lettres', 'arts', 'social', 'default'];
        $vector = [];

        // 1. Bac Type Compatibility
        $normalizeBac = function ($str) {
            $str = mb_strtolower(trim($str ?? ''));
            $str = str_replace(['é', 'è', 'ê', 'à', 'â', 'î', 'ô', 'û'], ['e', 'e', 'e', 'a', 'a', 'i', 'o', 'u'], $str);
            return $str;
        };
        $sectionBac = $normalizeBac($profil['section_bac'] ?? $profil['filiere_etudiant_actuelle'] ?? '');
        $bacMap = [
            'mathematiques'           => 'mathematiques',
            'sciences experimentales' => 'sciences experimentales',
            'sciences'                => 'sciences experimentales',
            'economie et gestion'     => 'economie et gestion',
            'economie'                => 'economie et gestion',
            'technique'               => 'technique',
            'informatique'            => 'informatique',
            'lettres'                 => 'lettres',
            'sport'                   => 'sport',
        ];
        $bac = $bacMap[$sectionBac] ?? $sectionBac;

        $bacFits = array_fill_keys($domains, 0.0);
        if ($bac === 'informatique') {
            $bacFits['informatique'] = 1.0;
            $bacFits['ingenieur'] = 0.7;
            $bacFits['sciences'] = 0.6;
            $bacFits['gestion'] = 0.5;
        } elseif ($bac === 'mathematiques' || $bac === 'sciences experimentales') {
            $bacFits['sciences'] = 1.0;
            $bacFits['sante'] = 1.0;
            $bacFits['ingenieur'] = 0.9;
            $bacFits['informatique'] = 0.8;
            $bacFits['gestion'] = 0.4;
        } elseif ($bac === 'technique') {
            $bacFits['ingenieur'] = 1.0;
            $bacFits['informatique'] = 0.8;
            $bacFits['sciences'] = 0.7;
        } elseif ($bac === 'economie et gestion') {
            $bacFits['gestion'] = 1.0;
            $bacFits['juridique'] = 0.6;
            $bacFits['social'] = 0.4;
        } elseif ($bac === 'lettres') {
            $bacFits['lettres'] = 1.0;
            $bacFits['juridique'] = 0.8;
            $bacFits['social'] = 0.8;
            $bacFits['arts'] = 0.7;
        } elseif ($bac === 'sport') {
            $bacFits['social'] = 1.0;
            $bacFits['sante'] = 0.5;
        }

        // 2. Declared Interests Compatibility
        $interests = $profil['interests'] ?? [];
        $interestFits = array_fill_keys($domains, 0.0);
        
        $interestMap = [
            'INFO' => 'informatique',
            'MED'  => 'sante',
            'ENG'  => 'ingenieur',
            'ECO'  => 'gestion',
            'LAW'  => 'juridique',
            'LTR'  => 'lettres',
            'ART'  => 'arts',
            'SOC'  => 'social',
        ];
        foreach ($interests as $tag => $val) {
            $mappedDomain = $interestMap[strtoupper($tag)] ?? null;
            if ($mappedDomain !== null) {
                $interestFits[$mappedDomain] = max($interestFits[$mappedDomain], (float)$val);
            }
        }

        // 3. Academic Grades Compatibility
        $grades = $profil['notes_matieres'] ?? [];
        $gradeFits = array_fill_keys($domains, 0.0);
        
        $math = (float)($grades['math'] ?? $grades['mathematiques'] ?? 10.0);
        $phys = (float)($grades['physique'] ?? $grades['phys'] ?? 10.0);
        $svt = (float)($grades['svt'] ?? $grades['bio'] ?? 10.0);
        $eco = (float)($grades['economie'] ?? $grades['eco'] ?? 10.0);
        $gest = (float)($grades['gestion'] ?? 10.0);
        $algo = (float)($grades['algo'] ?? $grades['informatique'] ?? 10.0);
        $fr = (float)($grades['francais'] ?? 10.0);
        $ang = (float)($grades['anglais'] ?? 10.0);

        if ($math >= 14.0 || $phys >= 14.0) {
            $gradeFits['ingenieur'] = 1.0;
            $gradeFits['sciences'] = 1.0;
            $gradeFits['informatique'] = 0.8;
        }
        if ($svt >= 14.0) {
            $gradeFits['sante'] = 1.0;
            $gradeFits['sciences'] = 0.8;
        }
        if ($eco >= 14.0 || $gest >= 14.0) {
            $gradeFits['gestion'] = 1.0;
        }
        if ($algo >= 14.0) {
            $gradeFits['informatique'] = 1.0;
            $gradeFits['ingenieur'] = 0.7;
        }
        if ($fr >= 14.0 || $ang >= 14.0) {
            $gradeFits['lettres'] = 1.0;
            $gradeFits['juridique'] = 0.8;
            $gradeFits['social'] = 0.8;
        }

        // 4. RIASEC Compatibility (psychometric fits for each domain)
        $r = (float)($riasecStudentNorm['R'] ?? 0.5);
        $i = (float)($riasecStudentNorm['I'] ?? 0.5);
        $a = (float)($riasecStudentNorm['A'] ?? 0.5);
        $s = (float)($riasecStudentNorm['S'] ?? 0.5);
        $e = (float)($riasecStudentNorm['E'] ?? 0.5);
        $c = (float)($riasecStudentNorm['C'] ?? 0.5);

        $riasecFits = [
            'informatique' => $this->clamp01(0.4 * $i + 0.4 * $c + 0.2 * $r),
            'sante'        => $this->clamp01(0.5 * $i + 0.5 * $s),
            'ingenieur'    => $this->clamp01(0.6 * $r + 0.4 * $i),
            'sciences'     => $this->clamp01(0.8 * $i + 0.2 * $c),
            'gestion'      => $this->clamp01(0.5 * $e + 0.5 * $c),
            'juridique'    => $this->clamp01(0.2 * $a + 0.3 * $s + 0.5 * $e),
            'lettres'      => $this->clamp01(0.5 * $a + 0.5 * $s),
            'arts'         => $this->clamp01(0.9 * $a + 0.1 * $i),
            'social'       => $this->clamp01(0.8 * $s + 0.2 * $e),
            'default'      => 0.5,
        ];

        // Combine all sub-scores to compute the final Student Domain Vector
        foreach ($domains as $d) {
            $vector[$d] = $this->clamp01(
                0.3 * ($bacFits[$d] ?? 0.0) +
                0.3 * ($interestFits[$d] ?? 0.0) +
                0.2 * ($gradeFits[$d] ?? 0.0) +
                0.2 * ($riasecFits[$d] ?? 0.5)
            );
        }

        return $vector;
    }
}
