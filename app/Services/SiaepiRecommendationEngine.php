<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * SIAEPI v2.2 — Moteur de recommandation académique PHP-natif
 *
 * Implémente :
 *   - Similarité cosinus pondérée (profil multidimensionnel × filière)
 *   - Pénalité cognitive GATB (protection contre les orientations irréalistes)
 *   - Alignement psychométrique RIASEC (code Holland)
 *   - Score marché (employabilité + croissance domaine)
 *   - Bonus section BAC → filières compatibles
 *   - Gap Analysis entre filière rêvée et profil réel
 */
class SiaepiRecommendationEngine
{
    // ── Fichiers de données ─────────────────────────────────────────────────
    private string $filiereFile = 'filieres_data.xlsx';

    // ── Pondérations globales SIAEPI v2.2 ──────────────────────────────────
    private array $weights = [
        'riasec'      => 0.35,   // Vocation psychométrique (RIASEC)
        'academique'  => 0.30,   // Capacité académique réelle (Score FG + GATB)
        'marche'      => 0.20,   // Employabilité & marché du travail
        'accessibilite' => 0.15, // Accessibilité (SDO vs score FG)
    ];

    // ── Poids GATB par domaine ──────────────────────────────────────────────
    // Chaque filière valorise différemment les aptitudes cognitives
    private array $gatbDomainWeights = [
        'technique'   => ['G' => 0.35, 'V' => 0.15, 'N' => 0.30, 'S' => 0.20],
        'sciences'    => ['G' => 0.40, 'V' => 0.10, 'N' => 0.35, 'S' => 0.15],
        'lettres'     => ['G' => 0.25, 'V' => 0.50, 'N' => 0.10, 'S' => 0.15],
        'economie'    => ['G' => 0.30, 'V' => 0.20, 'N' => 0.40, 'S' => 0.10],
        'arts'        => ['G' => 0.20, 'V' => 0.20, 'N' => 0.10, 'S' => 0.50],
        'social'      => ['G' => 0.25, 'V' => 0.45, 'N' => 0.15, 'S' => 0.15],
        'sante'       => ['G' => 0.35, 'V' => 0.20, 'N' => 0.25, 'S' => 0.20],
        'default'     => ['G' => 0.30, 'V' => 0.25, 'N' => 0.25, 'S' => 0.20],
    ];

    // ── Mapping code RIASEC → compatibilité filières ────────────────────────
    private array $riasecFiliereDomains = [
        'R' => ['technique', 'ingenierie', 'sport', 'sante_paramedical'],
        'I' => ['sciences', 'recherche', 'informatique', 'sante', 'math'],
        'A' => ['arts', 'design', 'lettres', 'communication', 'architecture'],
        'S' => ['social', 'education', 'sante', 'droit', 'tourisme'],
        'E' => ['economie', 'droit', 'gestion', 'management', 'commerce'],
        'C' => ['comptabilite', 'finance', 'economie', 'administration', 'informatique'],
    ];

    // ── Bonus section BAC → filières compatibles ────────────────────────────
    private array $bacSectionBonus = [
        'Mathématiques'          => ['informatique', 'ingenierie', 'math', 'technique', 'sciences'],
        'Sciences expérimentales'=> ['sante', 'sciences', 'biologie', 'chimie', 'pharmacie'],
        'Économie et gestion'    => ['economie', 'gestion', 'finance', 'commerce', 'droit'],
        'Technique'              => ['technique', 'ingenierie', 'architecture'],
        'Informatique'           => ['informatique', 'ingenierie', 'sciences'],
        'Lettres'                => ['lettres', 'droit', 'social', 'communication', 'journalisme'],
        'Sport'                  => ['sport', 'sante', 'education'],
    ];

    // ── Taux employabilité → score numérique ────────────────────────────────
    private array $employabiliteScore = [
        'Très élevé' => 1.0,
        'Elevé'      => 0.85,
        'Élevé'      => 0.85,
        'Modéré'     => 0.60,
        'Faible'     => 0.30,
    ];

    private array $croissanceScore = [
        'Forte croissance' => 1.0,
        'Croissance'       => 0.85,
        'Stable'           => 0.60,
        'Déclin'           => 0.25,
    ];

    /** Charge et filtre les filières depuis l'Excel */
    public function loadFilieres(): array
    {
        $path = storage_path('app/excels/' . $this->filiereFile);
        if (!file_exists($path)) {
            Log::error("SIAEPI: Fichier Excel introuvable: $path");
            return [];
        }

        try {
            $spreadsheet = IOFactory::load($path);
            $ws = $spreadsheet->getActiveSheet();
            $rows = $ws->toArray(null, true, true, false);

            $headers = array_shift($rows);

            $filieres = [];
            foreach ($rows as $row) {
                if (count($row) < count($headers)) continue;
                $f = array_combine($headers, array_slice($row, 0, count($headers)));
                // Skip empty rows
                if (empty($f['Code_Filiere']) || empty($f['Nom_Filiere'])) continue;
                $filieres[] = $f;
            }

            return $filieres;
        } catch (\Throwable $e) {
            Log::error("SIAEPI: Erreur lecture Excel: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Point d'entrée principal — génère le Top-N recommandations
     *
     * @param array $profilEtudiant {
     *   score_fg: float,
     *   section_bac: string,
     *   vecteur_psychometrique: array{R,I,A,S,E,C} (0.0–1.0),
     *   gatb_scores: array{G,V,N,S} (0–20),
     *   code_holland: string (3 lettres),
     * }
     * @param int $topN
     */
    public function recommend(array $profilEtudiant, int $topN = 12): array
    {
        $filieres = $this->loadFilieres();

        if (empty($filieres)) {
            return ['error' => 'Données de filières indisponibles.'];
        }

        $scoreFg      = (float) ($profilEtudiant['score_fg'] ?? 120);
        $sectionBac   = $profilEtudiant['section_bac'] ?? $profilEtudiant['filiere_etudiant_actuelle'] ?? '';
        $riasecVec    = $profilEtudiant['vecteur_psychometrique'] ?? ['R'=>0.5,'I'=>0.5,'A'=>0.5,'S'=>0.5,'E'=>0.5,'C'=>0.5];
        $gatbRaw      = $profilEtudiant['gatb_scores'] ?? ['G'=>10,'V'=>10,'N'=>10,'S'=>10];
        $codeHolland  = $profilEtudiant['code_holland'] ?? $this->getHollandFromVec($riasecVec);

        // Normalise GATB sur 0–1 (max = 20)
        $gatbNorm = [
            'G' => min(1.0, ($gatbRaw['G'] ?? 10) / 20.0),
            'V' => min(1.0, ($gatbRaw['V'] ?? 10) / 20.0),
            'N' => min(1.0, ($gatbRaw['N'] ?? 10) / 20.0),
            'S' => min(1.0, ($gatbRaw['S'] ?? 10) / 20.0),
        ];

        // Bonus section BAC
        $bacDomains = $this->bacSectionBonus[$sectionBac] ?? [];

        $scored = [];
        foreach ($filieres as $f) {
            $score = $this->scoreFiliere($f, $scoreFg, $riasecVec, $gatbNorm, $codeHolland, $bacDomains);
            if ($score === null) continue;

            $scored[] = array_merge($f, [
                'Score_Final'            => round($score['total'], 4),
                'Score_RIASEC'           => round($score['riasec'], 4),
                'Score_Academique'       => round($score['academique'], 4),
                'Score_Marche'           => round($score['marche'], 4),
                'Score_Accessibilite'    => round($score['accessibilite'], 4),
                'Penalite_Cognitive'     => round($score['penalite'], 4),
                'Type_Transition'        => $score['transition'],
                'Explication'           => $score['explication'],
            ]);
        }

        // Tri par score final décroissant
        usort($scored, fn($a, $b) => $b['Score_Final'] <=> $a['Score_Final']);

        $top = array_slice($scored, 0, $topN);

        // Numérotation rang
        foreach ($top as $i => &$item) {
            $item['Rang'] = $i + 1;
            $item['Score_Final_Contextuel'] = $item['Score_Final'];
            $item['Compatibilite_Psychometrique'] = $item['Score_RIASEC'];
        }

        // Gap analysis avec la section BAC comme "filière actuelle"
        $gapAnalysis = $this->gapAnalysis($profilEtudiant, $top[0] ?? null, $scoreFg);

        // Diagnostic global
        $diagnostic = $this->buildDiagnostic($codeHolland, $riasecVec, $gatbNorm, $scoreFg, $sectionBac);

        return [
            'recommandations'      => $top,
            'diagnostic'           => $diagnostic,
            'gap_analysis'         => $gapAnalysis,
            'code_holland'         => $codeHolland,
            'score_fg'             => $scoreFg,
            'total_filieres'       => count($filieres),
            'total_scorees'        => count($scored),
        ];
    }

    /** Score global d'une filière pour un profil étudiant */
    private function scoreFiliere(
        array $filiere,
        float $scoreFg,
        array $riasecVec,
        array $gatbNorm,
        string $codeHolland,
        array $bacDomains
    ): ?array {
        $codeRiasec  = strtoupper(trim($filiere['Code_RIASEC'] ?? ''));
        $nomFiliere  = strtolower($filiere['Nom_Filiere'] ?? '');
        $sdo         = $this->getSDO($filiere);

        // ── 1. Score RIASEC (Similarité cosinus) ──────────────────────────
        $riasecScore = $this->cosineSimilarityRiasec($riasecVec, $codeRiasec);

        // ── 2. Score Académique (SDO + GATB) ──────────────────────────────
        $domain = $this->detectDomain($nomFiliere, $codeRiasec);
        $gatbWeights = $this->gatbDomainWeights[$domain] ?? $this->gatbDomainWeights['default'];

        // Score GATB pondéré pour ce domaine
        $gatbScore = 0.0;
        $totalGatbW = 0.0;
        foreach ($gatbWeights as $dim => $w) {
            $gatbScore += $w * ($gatbNorm[$dim] ?? 0.5);
            $totalGatbW += $w;
        }
        $gatbScore = $totalGatbW > 0 ? $gatbScore / $totalGatbW : 0.5;

        // Score FG normalisé : on compare score étudiant / SDO
        $fgNorm = $sdo > 0 ? min(1.0, $scoreFg / $sdo) : 0.5;
        $academicScore = 0.6 * $fgNorm + 0.4 * $gatbScore;

        // ── 3. Pénalité cognitive (protection contre l'irréalisme) ────────
        // Si l'étudiant a un GATB significativement faible pour le domaine,
        // on pénalise le score final
        $penalite = 0.0;
        if ($gatbScore < 0.3 && in_array($domain, ['sciences', 'technique', 'informatique'])) {
            $penalite = 0.25; // Pénalité forte : 25%
        } elseif ($gatbScore < 0.4) {
            $penalite = 0.10; // Pénalité modérée
        }

        // ── 4. Score Marché (Employabilité + Croissance) ──────────────────
        $empStr = $filiere['Taux_Employabilite'] ?? 'Modéré';
        $croStr = $filiere['Croissance_Domaine'] ?? 'Stable';
        $empScore = $this->employabiliteScore[$empStr] ?? 0.60;
        $croScore = $this->croissanceScore[$croStr] ?? 0.60;
        $marcheScore = 0.6 * $empScore + 0.4 * $croScore;

        // ── 5. Score Accessibilité (Score FG vs SDO) ───────────────────────
        // Favorise les filières accessibles mais pas trop faciles
        $accessScore = 0.5;
        if ($sdo > 0) {
            $ratio = $scoreFg / $sdo;
            if ($ratio >= 1.0) {
                $accessScore = 1.0; // Pleinement accessible
            } elseif ($ratio >= 0.85) {
                $accessScore = 0.8; // Légèrement sous le seuil
            } elseif ($ratio >= 0.70) {
                $accessScore = 0.5; // Effort requis
            } else {
                $accessScore = 0.2; // Très difficile d'accès
            }
        }

        // ── 6. Bonus section BAC ───────────────────────────────────────────
        $bacBonus = 0.0;
        foreach ($bacDomains as $bd) {
            if (str_contains($nomFiliere, $bd) || str_contains($domain, $bd)) {
                $bacBonus = 0.05;
                break;
            }
        }

        // ── 7. Score final pondéré ─────────────────────────────────────────
        $total = (
            $this->weights['riasec']        * $riasecScore +
            $this->weights['academique']    * $academicScore +
            $this->weights['marche']        * $marcheScore +
            $this->weights['accessibilite'] * $accessScore
        );

        // Applique pénalité cognitive
        $total = $total * (1 - $penalite) + $bacBonus;
        $total = max(0, min(1.0, $total));

        // ── 8. Type de transition ──────────────────────────────────────────
        $transition = $this->classifyTransition($riasecScore, $academicScore, $accessScore);

        // ── 9. Explication ─────────────────────────────────────────────────
        $explication = $this->buildExplication($riasecScore, $academicScore, $marcheScore, $domain, $penalite);

        return [
            'total'       => $total,
            'riasec'      => $riasecScore,
            'academique'  => $academicScore,
            'marche'      => $marcheScore,
            'accessibilite'=> $accessScore,
            'penalite'    => $penalite,
            'transition'  => $transition,
            'explication' => $explication,
        ];
    }

    /**
     * Similarité cosinus entre le vecteur RIASEC de l'étudiant
     * et le code RIASEC de la filière (converti en vecteur binaire)
     */
    private function cosineSimilarityRiasec(array $studentVec, string $filiereCode): float
    {
        if (empty($filiereCode)) return 0.5;

        $dims = ['R', 'I', 'A', 'S', 'E', 'C'];
        $filLetters = str_split(substr($filiereCode, 0, 3));

        // Vecteur filière : 1.0 pour les 3 lettres dominantes, décroissant
        $filiereVec = [];
        foreach ($dims as $d) {
            $pos = array_search($d, $filLetters);
            if ($pos === 0)     $filiereVec[$d] = 1.0;
            elseif ($pos === 1) $filiereVec[$d] = 0.8;
            elseif ($pos === 2) $filiereVec[$d] = 0.6;
            else                $filiereVec[$d] = 0.1;
        }

        // Cosinus : dot(a,b) / (norm(a) * norm(b))
        $dot = 0.0; $normA = 0.0; $normB = 0.0;
        foreach ($dims as $d) {
            $a = $studentVec[$d] ?? 0.0;
            $b = $filiereVec[$d] ?? 0.0;
            $dot   += $a * $b;
            $normA += $a * $a;
            $normB += $b * $b;
        }

        if ($normA < 0.0001 || $normB < 0.0001) return 0.5;
        return $dot / (sqrt($normA) * sqrt($normB));
    }

    /** Détecte le domaine d'une filière à partir de son nom + code RIASEC */
    private function detectDomain(string $nom, string $code): string
    {
        $nom = mb_strtolower($nom);

        if (preg_match('/inform|algorithme|réseau|systèm|logiciel|cyber/', $nom)) return 'informatique';
        if (preg_match('/médecin|santé|pharmac|infirmier|kiné|dentair/', $nom)) return 'sante';
        if (preg_match('/génie|ingénierie|mécanique|électrique|civil|industri/', $nom)) return 'technique';
        if (preg_match('/biologie|chimie|physique|sciences/', $nom)) return 'sciences';
        if (preg_match('/économ|gestion|commerc|finance|comptab|banque|marketing/', $nom)) return 'economie';
        if (preg_match('/lettr|arabe|histoire|philosoph|géograph|socio/', $nom)) return 'lettres';
        if (preg_match('/droit|juridique|notariat/', $nom)) return 'social';
        if (preg_match('/art|design|music|archit|communication|journalisme/', $nom)) return 'arts';
        if (preg_match('/sport|éducation physique/', $nom)) return 'social';
        if (preg_match('/math|statistique/', $nom)) return 'sciences';

        // Fallback basé sur le code RIASEC
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

    /** Récupère le SDO (seuil d'admission) de la filière */
    private function getSDO(array $filiere): float
    {
        // Priorité : SDO 2025 > 2024 > 2023
        foreach (['SDO_2025', 'SDO_2024', 'SDO_2023'] as $col) {
            $v = $filiere[$col] ?? null;
            if ($v !== null && $v !== '' && is_numeric($v) && (float)$v > 0) {
                return (float) $v;
            }
        }
        return 0.0; // Pas de SDO = considéré comme accessible
    }

    /** Dérive le code Holland à partir du vecteur psychométrique */
    private function getHollandFromVec(array $vec): string
    {
        arsort($vec);
        return implode('', array_slice(array_keys($vec), 0, 3));
    }

    /** Classifie le type de transition */
    private function classifyTransition(float $riasec, float $academic, float $access): string
    {
        if ($riasec >= 0.75 && $academic >= 0.70) return 'Continuité directe';
        if ($riasec >= 0.60 && $academic >= 0.55) return 'Continuité';
        if ($riasec >= 0.50 || $academic >= 0.60) return 'Pivot stratégique';
        return 'Nouvelle orientation';
    }

    /** Construit une explication courte */
    private function buildExplication(float $riasec, float $acad, float $marche, string $domain, float $penalite): string
    {
        $parts = [];
        if ($riasec >= 0.70) $parts[] = "Excellente compatibilité psychométrique";
        elseif ($riasec >= 0.50) $parts[] = "Bonne adéquation de profil";
        else $parts[] = "Compatibilité partielle";

        if ($acad >= 0.75) $parts[] = "niveau académique solide";
        elseif ($acad >= 0.55) $parts[] = "capacités académiques suffisantes";
        else $parts[] = "effort académique requis";

        if ($marche >= 0.75) $parts[] = "très bonne employabilité";
        elseif ($marche >= 0.55) $parts[] = "employabilité correcte";

        if ($penalite > 0.15) $parts[] = "⚠ aptitudes cognitives à renforcer";

        return ucfirst(implode(', ', $parts)) . '.';
    }

    /** Gap Analysis entre le profil étudiant et la filière Top-1 */
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
            'statut'        => $gap <= 0 ? 'Accessible' : ($gap <= 15 ? 'Effort requis' : 'Difficile'),
            'axes_amelioration' => [],
        ];

        // Axes d'amélioration
        if ($gap > 0) {
            $result['axes_amelioration'][] = "Améliorer la moyenne générale d'environ " . round($gap / 4, 1) . " points";
        }

        $gatb = $profil['gatb_scores'] ?? [];
        if (($gatb['G'] ?? 10) < 12) {
            $result['axes_amelioration'][] = "Renforcer le raisonnement logique (exercices de logique, mathématiques)";
        }
        if (($gatb['V'] ?? 10) < 12) {
            $result['axes_amelioration'][] = "Développer les compétences verbales (lecture, rédaction)";
        }
        if (($gatb['N'] ?? 10) < 12) {
            $result['axes_amelioration'][] = "Améliorer les aptitudes numériques (calcul, statistiques)";
        }

        return $result;
    }

    /** Construit le diagnostic psychométrique global */
    private function buildDiagnostic(string $code, array $riasecVec, array $gatbNorm, float $scoreFg, string $section): array
    {
        arsort($riasecVec);
        $dominant = array_keys($riasecVec)[0] ?? 'I';

        $profiles = [
            'R' => 'profil pragmatique et technique — vous excellez dans les activités concrètes et manuelles',
            'I' => 'profil analytique et scientifique — vous aimez comprendre, chercher et résoudre des problèmes complexes',
            'A' => 'profil créatif et expressif — vous êtes attiré par l\'originalité, l\'art et l\'innovation',
            'S' => 'profil relationnel et altruiste — vous êtes épanoui dans l\'aide, l\'enseignement et le contact humain',
            'E' => 'profil entreprenant et ambitieux — vous aimez diriger, convaincre et relever des défis',
            'C' => 'profil organisé et méthodique — vous appréciez la rigueur, l\'ordre et les procédures claires',
        ];

        $avgGatb = array_sum($gatbNorm) / max(1, count($gatbNorm));

        $niveau = match(true) {
            $scoreFg >= 170 => 'Excellent',
            $scoreFg >= 140 => 'Très bon',
            $scoreFg >= 110 => 'Bon',
            $scoreFg >= 80  => 'Moyen',
            default         => 'Faible',
        };

        return [
            'diagnostic'   => "Votre code RIASEC dominant est **{$code}**, révélant un " . ($profiles[$dominant] ?? 'profil équilibré') . ". Avec un Score FG de {$scoreFg} (niveau {$niveau}), vous avez accès à un large éventail de filières universitaires tunisiennes.",
            'score'        => round($avgGatb, 2),
            'niveau_fg'    => $niveau,
            'code_holland' => $code,
        ];
    }
}
