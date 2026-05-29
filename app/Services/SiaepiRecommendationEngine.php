<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * SIAEPI v5.0 — Moteur de recommandation académique PHP-natif
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

    // ── Pondérations globales SIAEPI v4.1 ──────────────────────────────────
    private array $weights = [
        'riasec'      => 0.40,   // Vocation composite (RIASEC + B5 + Int + Val)
        'academique'  => 0.30,   // Capacité académique (Score FG + GATB)
        'marche'      => 0.15,   // Employabilité & marché
        'accessibilite' => 0.15, // Accessibilité (SDO vs score FG)
    ];

    // ── Matrice de Transition (v5.0) ───────────────────────────────────
    // Malus appliqué si l'étudiant change radicalement de domaine par rapport à son Bac
    // Valeur = malus soustrait du score final (0.0 = aucun malus, 0.40 = très fort)
    private array $transitionMatrix = [
        'Mathématiques' => [
            'lettres' => 0.20, 'social' => 0.15, 'arts' => 0.25,
            // NOTE v5.1 : 'sante' retiré — Médecine/Pharmacie/Dentaire sont des débouchés
            // naturels pour les bacheliers Maths excellents en Tunisie (accès par numerus clausus)
        ],
        'Sciences expérimentales' => [
            'lettres' => 0.15, 'social' => 0.10, 'arts' => 0.20,
            'economie'=> 0.10,
        ],
        'Technique' => [
            'lettres' => 0.20, 'social' => 0.15, 'arts' => 0.20,
        ],
        'Informatique' => [
            'lettres' => 0.20, 'social' => 0.15, 'arts' => 0.20,
        ],
        'Économie et gestion' => [
            'sante' => 0.30, 'technique' => 0.25, 'sciences' => 0.15,
        ],
        'Lettres' => [
            'technique' => 0.35, 'sciences' => 0.30, 'sante' => 0.35, 'informatique' => 0.25,
        ],
    ];

    // Exclusions dures : ces domaines sont INTERDITS pour certaines sections BAC
    // Un étudiant Math ne sera JAMAIS recommandé pour des filières paramédicales typiques
    // sauf s'il a un code RIASEC fort en S (Social/Soins)
    private array $hardExclusions = [
        'Mathématiques'   => ['sante_paramedical'], // Kiné, Infirmier, Sage-femme = exclus
        'Lettres'         => ['technique', 'sciences'],
        'Économie et gestion' => ['sante_paramedical'],
    ];

    // Mots-clés identifiant les filières paramédicales bas
    private array $paramedicKeywords = ['infirmier', 'kiné', 'kinesith', 'sage-femme', 'orthoph', 'opticien', 'aide-soignant', 'paramedic'];

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

    // ── Bonus section BAC → filières compatibles (v5.1) ────────────────────
    // NOTE v5.1 : Maths inclut maintenant 'sante', 'medecine', 'pharmacie'
    // Les bacheliers Maths forts accèdent légitimement à Médecine/Pharmacie/Dentaire en Tunisie
    // Le filtre paramedical ($paramedicKeywords) exclut déjà Kiné/Infirmier/Sage-femme
    private array $bacSectionBonus = [
        'Mathématiques'          => ['informatique', 'ingenierie', 'math', 'technique', 'sciences', 'finance', 'sante', 'medecine', 'pharmacie', 'dentaire'],
        'Sciences expérimentales' => ['sante', 'sciences', 'biologie', 'chimie', 'pharmacie', 'medecine'],
        'Économie et gestion'    => ['economie', 'gestion', 'finance', 'commerce', 'droit', 'marketing'],
        'Technique'              => ['technique', 'ingenierie', 'architecture', 'mecanique', 'electrique'],
        'Informatique'           => ['informatique', 'ingenierie', 'sciences', 'logiciel', 'reseau', 'multimedia'],
        'Lettres'                => ['lettres', 'droit', 'social', 'communication', 'journalisme', 'langues'],
        'Sport'                  => ['sport', 'sante', 'education', 'physique'],
    ];

    private array $motsRiasec = [
        "R" => ["technique", "mécanique", "outil", "machine", "terrain", "manuel", "construction", "réparation", "physique", "pratique"],
        "I" => ["analyse", "recherche", "logique", "données", "science", "hypothèse", "investigation", "mathématique", "expérience", "observation"],
        "A" => ["création", "art", "design", "imagination", "musique", "écriture", "esthétique", "expression", "innovation", "culture"],
        "S" => ["aide", "enseignement", "social", "communication", "empathie", "conseil", "soin", "écoute", "coopération", "bénévolat"],
        "E" => ["leadership", "management", "vente", "négociation", "stratégie", "décision", "entrepreneuriat", "persuasion", "ambition", "direction"],
        "C" => ["organisation", "administration", "précision", "procédure", "classement", "comptabilité", "rigueur", "méthode", "planification", "contrôle"],
    ];

    // ── Taux employabilité → score numérique ────────────────────────────────
    private array $employabiliteScore = [
        'Très élevé' => 1.0, 'Elevé' => 0.85, 'Élevé' => 0.85, 'Modéré' => 0.60,
        'Faible' => 0.35, 'Très faible' => 0.15, 'Déclin' => 0.10
    ];

    private array $croissanceScore = [
        'Forte croissance' => 1.0, 'Stable' => 0.65, 'Modéré' => 0.50,
        'Déclin' => 0.20, 'Saturé' => 0.15
    ];

    // SIAEPI v4.0 : Mapping Big Five et Valeurs par domaine
    private array $domainPsychoprofile = [
        'informatique' => ['B5' => ['C' => 0.8, 'O' => 0.7, 'N' => -0.4], 'Val' => ['Ach' => 0.7, 'Aut' => 0.6]],
        'sante'        => ['B5' => ['A' => 0.9, 'C' => 0.8, 'N' => -0.5], 'Val' => ['Ben' => 0.9, 'Sec' => 0.6]],
        'technique'    => ['B5' => ['C' => 0.9, 'O' => 0.6], 'Val' => ['Ach' => 0.8, 'Sec' => 0.5]],
        'sciences'     => ['B5' => ['O' => 0.9, 'C' => 0.7], 'Val' => ['Ach' => 0.7, 'Aut' => 0.8]],
        'economie'     => ['B5' => ['E' => 0.8, 'C' => 0.7], 'Val' => ['Ach' => 0.9, 'Sec' => 0.7]],
        'lettres'      => ['B5' => ['O' => 0.9, 'A' => 0.6], 'Val' => ['Aut' => 0.7, 'Ben' => 0.5]],
        'social'       => ['B5' => ['A' => 0.9, 'E' => 0.7], 'Val' => ['Ben' => 0.9, 'Aut' => 0.5]],
        'arts'         => ['B5' => ['O' => 1.0, 'E' => 0.6], 'Val' => ['Aut' => 0.9, 'Ach' => 0.5]],
    ];

    /** Charge et filtre les filières depuis l'Excel */
    public function loadFilieres(): array
    {
        $basePath = storage_path('app/excels/');
        $files = glob($basePath . '*.xlsx');
        $filieres = [];

        // ── Déduplication : chaque code_filiere est unique ──
        $seenCodes = [];

        // Mapping fichier → type BAC par défaut
        // NOTE : Chaque fichier correspond à une section BAC spécifique.
        $bacMapping = [
            'ECO_FILIERES.XLSX'              => 'Économie et gestion',
            'EXP_FILIERES.XLSX'              => 'Sciences expérimentales',
            'FILIERES_DATA.XLSX'             => 'Mathématiques',
            'INFO_FILIERES.XLSX'             => 'Informatique',
            'TECH_FILIERES.XLSX'             => 'Technique',
            'SPORT_FILIERES.XLSX'            => 'Sport',
            // ⚠️ FIX CRITIQUE : ce fichier contient les filières du BAC Lettres (LET)
            'DONNEES_FILIERE_ENRICHIES.XLSX' => 'Lettres',
        ];

        foreach ($files as $path) {
            try {
                $filename = basename($path);
                $filenameUpper = strtoupper($filename);

                if (!isset($bacMapping[$filenameUpper])) {
                    continue; // Sauter les fichiers non mappés
                }

                $defaultTypeBac = $bacMapping[$filenameUpper];

                $spreadsheet = IOFactory::load($path);
                $ws = $spreadsheet->getActiveSheet();
                $rows = $ws->toArray(null, true, true, false);

                $headers = array_shift($rows);
                if (!$headers) continue;

                // Détecter si le fichier a une colonne Type_Bac propre
                $hasTypeBacColumn = in_array('Type_Bac', $headers);

                foreach ($rows as $row) {
                    if (count($row) < count($headers)) continue;
                    $f = array_combine($headers, array_slice($row, 0, count($headers)));
                    if (empty($f['Code_Filiere']) || empty($f['Nom_Filiere'])) continue;

                    $code = trim($f['Code_Filiere']);

                    if (isset($seenCodes[$code])) {
                        continue; // Doublon détecté sur le code
                    }
                    $seenCodes[$code] = true;

                    // ── FIX CRITIQUE : utiliser la colonne Type_Bac du fichier si elle existe ──
                    // Sinon utiliser le type par défaut du fichier
                    if ($hasTypeBacColumn && !empty(trim($f['Type_Bac'] ?? ''))) {
                        $f['Type_Bac'] = trim($f['Type_Bac']);
                    } else {
                        $f['Type_Bac'] = $defaultTypeBac;
                    }

                    $filieres[] = $f;
                }
            } catch (\Throwable $e) {
                Log::error("SIAEPI: Erreur lecture Excel $path: " . $e->getMessage());
            }
        }

        return $filieres;
    }

    /**
     * Point d'entrée principal — génère le Top-N recommandations
     *
     * @param array $profilEtudiant {
     *   score_fg: float,
     *   section_bac: string,
     *   vecteur_psychometrique: array{R,I,A,S,E,C} (0.0–1.0),
     *   gatb_scores: array{G,V,N,S} (0–100),
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
        // ── Auto-correction : si score_fg reçu sur /20 (moyenne brute), rescaler sur /200 ──
        // Le moteur travaille sur une échelle 0-200 (formule FG officielle).
        // Si quelqu'un passe 18 au lieu de 180, on détecte et corrige.
        if ($scoreFg > 0 && $scoreFg <= 20) {
            Log::warning('SIAEPI: score_fg reçu sur échelle /20 (' . $scoreFg . ') — rescalé x10 vers ' . ($scoreFg * 10));
            $scoreFg = $scoreFg * 10;
        }
        $sectionBac   = $profilEtudiant['section_bac'] ?? $profilEtudiant['filiere_etudiant_actuelle'] ?? '';
        $riasecVec    = $profilEtudiant['vecteur_psychometrique'] ?? ['R'=>0.5,'I'=>0.5,'A'=>0.5,'S'=>0.5,'E'=>0.5,'C'=>0.5];
        $gatbRaw      = $profilEtudiant['gatb_scores'] ?? ['G'=>50,'V'=>50,'N'=>50,'S'=>50];
        $codeHolland  = $profilEtudiant['code_holland'] ?? $this->getHollandFromVec($riasecVec);
        $textePsycho  = $profilEtudiant['texte_psycho'] ?? '';
        
        // Charger les feedbacks utilisateur pour ajustement dynamique
        $feedbacks = [];
        if (isset($profilEtudiant['id'])) {
            try {
                $feedbacks = \App\Models\RecommendationFeedback::where('user_id', $profilEtudiant['id'])
                    ->get()
                    ->keyBy('filiere_code')
                    ->toArray();
            } catch (\Throwable $e) {
                Log::warning("SIAEPI: No feedback table: " . $e->getMessage());
            }
        }
        $profilEtudiant['feedbacks'] = $feedbacks;
        
        // Dimensions v4.0
        $bigFive      = $profilEtudiant['big_five'] ?? ['O'=>0.5,'C'=>0.5,'E'=>0.5,'A'=>0.5,'N'=>0.5];
        $valeurs      = $profilEtudiant['valeurs'] ?? ['Sec'=>0.5,'Ach'=>0.5,'Ben'=>0.5,'Aut'=>0.5];
        $interests    = $profilEtudiant['interests'] ?? [];

        // Normalise GATB sur 0–1 (Échelle 0-100)
        // Détection de test GATB non complété (tous les scores à 0 ou non renseignés)
        $scoreG = $gatbRaw['GATB_G'] ?? $gatbRaw['G'] ?? null;
        $scoreV = $gatbRaw['GATB_V'] ?? $gatbRaw['V'] ?? null;
        $scoreN = $gatbRaw['GATB_N'] ?? $gatbRaw['N'] ?? null;
        $scoreS = $gatbRaw['GATB_S'] ?? $gatbRaw['S'] ?? null;

        $isGatbEmpty = (
            (is_null($scoreG) || (float)$scoreG === 0.0) &&
            (is_null($scoreV) || (float)$scoreV === 0.0) &&
            (is_null($scoreN) || (float)$scoreN === 0.0) &&
            (is_null($scoreS) || (float)$scoreS === 0.0)
        );

        // Si le test GATB n'est pas complété, on utilise une valeur neutre de 0.6 (60%) pour ne pas pénaliser indûment l'étudiant
        $gatbNorm = [
            'G' => $isGatbEmpty ? 0.6 : min(1.0, max(0.0, ((float) ($scoreG ?? 50.0)) / 100.0)),
            'V' => $isGatbEmpty ? 0.6 : min(1.0, max(0.0, ((float) ($scoreV ?? 50.0)) / 100.0)),
            'N' => $isGatbEmpty ? 0.6 : min(1.0, max(0.0, ((float) ($scoreN ?? 50.0)) / 100.0)),
            'S' => $isGatbEmpty ? 0.6 : min(1.0, max(0.0, ((float) ($scoreS ?? 50.0)) / 100.0)),
        ];

        // Bonus section BAC
        $bacDomains = $this->bacSectionBonus[$sectionBac] ?? [];

        // Fonction pour normaliser le nom de la section du Bac (minuscules, sans accents)
        $normalizeBac = function ($str) {
            $str = mb_strtolower(trim($str ?? ''));
            $str = str_replace(['é', 'è', 'ê', 'à', 'â', 'î', 'ô', 'û'], ['e', 'e', 'e', 'a', 'a', 'i', 'o', 'u'], $str);
            return $str;
        };
        // Comparaison sur la chaîne COMPLÈTE normalisée (pas juste le premier mot)
        $normEtuBac = $normalizeBac($sectionBac);

        // Map strict : Section BAC étudiant → Type_Bac attendu dans les fichiers Excel
        $bacTypeMap = [
            'mathematiques'          => 'mathematiques',
            'sciences experimentales'=> 'sciences experimentales',
            'economie et gestion'    => 'economie et gestion',
            'technique'              => 'technique',
            'informatique'           => 'informatique',
            'lettres'                => 'lettres',
            'sport'                  => 'sport',
        ];
        $normEtuBacMapped = $bacTypeMap[$normEtuBac] ?? $normEtuBac;

        $scored = [];
        foreach ($filieres as $f) {
            // Filtrage UNIQUEMENT basé sur les données Excel (source de vérité).
            // Principe : un étudiant voit les filières de SON type de BAC + les filières 'Général'.
            // Aucune exclusion algorithmique par domaine n'est appliquée ici.
            $fBac = $normalizeBac($f['Type_Bac'] ?? 'General');

            if ($fBac !== 'general' && $normEtuBac !== '' && $fBac !== $normEtuBacMapped) {
                continue; // La filière n'est pas dans le fichier Excel de ce BAC
            }

            $score = $this->scoreFiliere($f, $scoreFg, $riasecVec, $gatbNorm, $codeHolland, $bacDomains, $textePsycho, $interests, $bigFive, $valeurs, $sectionBac, $profilEtudiant);
            if ($score === null) continue;

            // Audit Logging (v4.1)
            Log::channel('orientation')->info("Decision for Student: " . ($profilEtudiant['id'] ?? 'unknown'), [
                'filiere' => $f['Nom_Filiere'],
                'scores' => [
                    'final' => $score['total'],
                    'vocation' => $score['riasec'],
                    'academique' => $score['academique'],
                    'confidence' => $score['confidence'],
                    'risk' => $score['risk'] ?? 0
                ]
            ]);

            $scored[] = array_merge($f, [
                'Score_Final'            => round($score['total'], 4),
                'Score_RIASEC'           => round($score['riasec'], 4),
                'Score_Academique'       => round($score['academique'], 4),
                'Score_Marche'           => round($score['marche'], 4),
                'Score_Accessibilite'    => round($score['accessibilite'], 4),
                'Penalite_Cognitive'     => round($score['penalite'], 4),
                'Risk_Factor'            => round($score['risk'] ?? 0, 4),
                'Type_Transition'        => $score['transition'],
                'Explication'           => $score['explication'],
                'Confidence'            => $score['confidence'] ?? 1.0,
                'is_exploratory'        => ($score['confidence'] ?? 1.0) < 0.60,
            ]);
        }

        // Tri par score final décroissant
        usort($scored, fn($a, $b) => $b['Score_Final'] <=> $a['Score_Final']);

        // ── Déduplication finale : même (Nom_Filiere + Etablissement) = doublon ──
        // Garde la version avec le meilleur Score_Final (elle est déjà en premier grâce au tri)
        $uniqueScored = [];
        $seenFinal = [];
        foreach ($scored as $item) {
            $key = mb_strtolower(trim($item['Nom_Filiere'] ?? '')) . '|' . mb_strtolower(trim($item['Etablissement'] ?? ''));
            if (isset($seenFinal[$key])) continue;
            $seenFinal[$key] = true;
            $uniqueScored[] = $item;
        }

        $top = array_slice($uniqueScored, 0, $topN);

        // -- Algorithme du Front de Pareto (Maximisation Vocation vs Employabilité) --
        foreach ($top as &$f1) {
            $isPareto = true;
            foreach ($top as $f2) {
                if ($f1['Code_Filiere'] === $f2['Code_Filiere']) continue;
                
                // Si f2 domine f1 sur les deux axes (Vocation et Marché)
                if ($f2['Score_RIASEC'] >= $f1['Score_RIASEC'] && $f2['Score_Marche'] >= $f1['Score_Marche']) {
                    if ($f2['Score_RIASEC'] > $f1['Score_RIASEC'] || $f2['Score_Marche'] > $f1['Score_Marche']) {
                        $isPareto = false;
                        break;
                    }
                }
            }
            $f1['is_pareto_optimal'] = $isPareto;
        }
        unset($f1); // clean reference

        // ── Normalisation des pourcentages affichés (v5.1) ───────────────
        // Le Score_Final brut est un produit de facteurs multiplicatifs (coherence ×
        // confidence × (1-risk) × penalties) et descend souvent à 0.30–0.50 même pour
        // la meilleure recommandation. On le re-scale linéairement pour que le #1
        // affiche ~96 % et les suivants soient proportionnels, cohérents avec les
        // sous-scores individuels (RIASEC, Académique, Marché, Accès SDO).
        $maxRaw = 0.01;
        foreach ($top as $item) {
            if ($item['Score_Final'] > $maxRaw) $maxRaw = $item['Score_Final'];
        }
        $targetMax  = 0.96;
        $scaleFactor = $targetMax / $maxRaw;

        // Numérotation rang + re-scaling
        foreach ($top as $i => &$item) {
            $item['Rang'] = $i + 1;
            $item['Score_Final_Contextuel'] = min(0.98, max(0.40, $item['Score_Final'] * $scaleFactor));
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

    /** Score global d'une filière pour un profil étudiant - Version 4.1 (Conseiller Scientifique) */
    private function scoreFiliere(
        array $filiere,
        float $scoreFg,
        array $riasecVec,
        array $gatbNorm,
        string $codeHolland,
        array $bacDomains,
        string $textePsycho,
        array $filiereInterests = [],
        array $bigFive = [],
        array $valeurs = [],
        string $sectionBac = '',
        array $rawProfil = []
    ): ?array {
        $codeRiasec  = strtoupper(trim($filiere['Code_RIASEC'] ?? ''));
        $nomFiliere  = strtolower($filiere['Nom_Filiere'] ?? '');
        $sdo         = $this->getSDO($filiere);
        $domain      = $this->detectDomain($nomFiliere, $codeRiasec);

        // ── Seul filtre d'exclusion légitime : SDO trop élevé vs score étudiant ──
        // (l'étudiant n'a pas le niveau académique requis pour cette filière)
        // Tous les autres filtres sont des pénalités DOUCES dans le score final.

        $accessProb = $this->logisticAdmissionProbability($scoreFg, $sdo);
        
        $academicPenalty = 1.0;
        if ($accessProb < 0.25 && $scoreFg < ($sdo - 20)) return null; // Exclusion si trop éloigné
        elseif ($accessProb < 0.35) {
            // Pénalité plus douce (15% au lieu de 35%) pour les excellents profils sur les filières sélectives/médicales
            $isPrestige = ($sdo >= 160) || 
                str_contains($nomFiliere, 'médecine') || str_contains($nomFiliere, 'medecine') || 
                str_contains($nomFiliere, 'pharmacie') || 
                str_contains($nomFiliere, 'dentaire');
            if ($scoreFg >= 160 && $isPrestige) {
                $academicPenalty = 0.85; // Pénalité réduite à 15% pour encourager les vocations d'excellence
            } else {
                $academicPenalty = 0.65;
            }
        }

        // ── Étape 2 : Score Vocation Composite & Confidence (v4.1) ─────────
        $riasecMatch = $this->cosineSimilarityRiasec($riasecVec, $codeRiasec, $codeHolland);
        
        // Facteur de confiance basé sur l'incertitude psychométrique
        $sem = $rawProfil['sem'] ?? 0.30;
        $confidence = max(0.4, 1.0 - $sem);
        
        // Poids dynamiques selon confiance
        $wR = 0.45; $wB = 0.25; $wI = 0.20; $wV = 0.10;
        if ($sem > 0.45) { // Si données bruitées, réduire poids personnalité/valeurs
            $wR = 0.65; $wB = 0.15; $wI = 0.15; $wV = 0.05;
        }

        $b5Profile = $this->domainPsychoprofile[$domain]['B5'] ?? [];
        $b5Match = 0.5;
        if (!empty($b5Profile)) {
            $sum = 0; $count = 0;
            foreach ($b5Profile as $trait => $target) {
                $studentVal = $bigFive[$trait] ?? 0.0;
                $studentValNorm = ($studentVal + 3) / 6.0;
                $sum += 1.0 - abs($studentValNorm - (($target + 3) / 6.0));
                $count++;
            }
            $b5Match = $count > 0 ? $sum / $count : 0.5;
        }

        $filiereDim = $this->getFiliereDimension($nomFiliere, $domain);
        $interestMatch = 0.5;
        if ($filiereDim && isset($filiereInterests[$filiereDim])) {
             $interestMatch = ($filiereInterests[$filiereDim] + 3) / 6.0;
        }

        $valProfile = $this->domainPsychoprofile[$domain]['Val'] ?? [];
        $valMatch = 0.5;
        if (!empty($valProfile)) {
            $sum = 0; $count = 0;
            foreach ($valProfile as $v => $target) {
                $studentVal = (($valeurs[$v] ?? 0.0) + 3) / 6.0;
                $sum += 1.0 - abs($studentVal - (($target + 3) / 6.0));
                $count++;
            }
            $valMatch = $count > 0 ? $sum / $count : 0.5;
        }

        $vocationScore = ($wR * $riasecMatch) + ($wB * $b5Match) + ($wI * $interestMatch) + ($wV * $valMatch);
        
        $vocationPenalty = 1.0;
        if ($vocationScore < 0.25) return null; // Exclusion hard
        elseif ($vocationScore < 0.35) $vocationPenalty = 0.65; // Soft penalty

        // ── Étape 3 : Score Académique et Risque (v4.1) ─────────────────────
        $gatbWeights = $this->gatbDomainWeights[$domain] ?? $this->gatbDomainWeights['default'];
        $gatbScore = 0.0; $totalGatbW = 0.0; $penaliteCognitive = 0.0;

        foreach ($gatbWeights as $dim => $w) {
            $apt = $gatbNorm[$dim] ?? 0.5;
            $gatbScore += $w * $apt;
            $totalGatbW += $w;
            if ($w >= 0.30 && $apt < 0.40) $penaliteCognitive += 0.20;
        }
        $gatbScore = $totalGatbW > 0 ? $gatbScore / $totalGatbW : 0.5;
        
        $academicScore = (0.6 * $accessProb) + (0.4 * $gatbScore);

        $difficulty = $sdo > 0 ? $sdo : 100.0;
        $aptitudeReelle = ($scoreFg + ($gatbScore * 200)) / 2; // Mixte Score FG et Aptitudes
        $risk = min(0.60, max(0, ($difficulty - $aptitudeReelle) / 100));
        
        // ── Étape 4 : Score Marché ─────────────────────────────────────────
        $empStr = $filiere['Taux_Employabilite'] ?? 'Modéré';
        $croStr = $filiere['Croissance_Domaine'] ?? 'Stable';
        $marcheScore = (0.6 * ($this->employabiliteScore[$empStr] ?? 0.6)) + (0.4 * ($this->croissanceScore[$croStr] ?? 0.6));

        // ── Étape 5 : Pondération Finale (v4.1) ────────────────────────────
        $wV = $this->weights['riasec']; 
        $wA = $this->weights['academique']; 
        $wM = $this->weights['marche']; 
        $wAcc = $this->weights['accessibilite'];

        $baseScore = ($wV * $vocationScore) + ($wA * $academicScore) + ($wM * $marcheScore) + ($wAcc * $accessProb);
        $coherence = max(0.4, (0.7 * $riasecMatch) + (0.3 * $b5Match));
        
        $total = ($baseScore * $coherence * $confidence * (1.0 - $risk)) * $vocationPenalty * $academicPenalty;

        // Bonus/Pénalités (v4.1)
        $bacBonus = 0.0;
        foreach ($bacDomains as $bd) {
            if (str_contains($nomFiliere, $bd) || str_contains($domain, $bd)) {
                $bacBonus = 0.10; break;
            }
        }

        // Transition Matrix Malus (pénalité DOUCE uniquement — jamais une exclusion)
        // La source de vérité pour l'accès est le fichier Excel, pas cet algorithme.
        $transitionMalus = 0.0;
        if (isset($this->transitionMatrix[$sectionBac][$domain])) {
            $transitionMalus = $this->transitionMatrix[$sectionBac][$domain];
            // Jamais une exclusion dure : le malus réduit le score mais ne bloque pas
        }

        // Prestige & Pareto Bonus
        // Un étudiant excellent (scoreFg >= 160) qui s'oriente vers des filières d'excellence (médecine, pharmacie, dentaire ou SDO >= 160)
        // reçoit un bonus de prestige car ce sont ses débouchés d'excellence naturels.
        $isPrestigeFiliere = ($sdo >= 160) || 
            str_contains($nomFiliere, 'médecine') || str_contains($nomFiliere, 'medecine') || 
            str_contains($nomFiliere, 'pharmacie') || 
            str_contains($nomFiliere, 'dentaire') || 
            str_contains($nomFiliere, 'ingénieur') || str_contains($nomFiliere, 'ingenieur') || 
            str_contains($nomFiliere, 'préparatoire') || str_contains($nomFiliere, 'preparatoire');
        $prestigeBonus = ($scoreFg >= 160 && $isPrestigeFiliere) ? 0.18 : 0.0;
        
        // Waste Penalty renforcée (v5.0)
        // Si un étudiant fort (FG ≥ 150) postule à une filière bien en dessous de lui (SDO + 30)
        $wastePenalty = 0.0;
        if ($scoreFg >= 150 && $sdo > 0 && ($scoreFg - $sdo) > 30) {
            // Pénalité progressive : plus l'écart est grand, plus la pénalité est forte
            $ecart = $scoreFg - $sdo;
            $wastePenalty = min(0.25, ($ecart - 30) / 200.0 + 0.10);
        }
        // ── Pénalité SDO inconnu (v5.1) : filières sans seuil = risque de sous-orientation ──
        // Pour un étudiant fort (FG ≥ 150), une filière sans SDO défini est pénalisée
        // car elle est généralement ouverte sans sélection (niveau bas attendu)
        if ($scoreFg >= 150 && $sdo === 0.0) {
            $wastePenalty = max($wastePenalty, 0.12); // Pénalité minimale de 12% pour SDO inconnu
        }

        // ── Ajustement Dynamique par Feedback Utilisateur (v5.0) ──
        $codeFiliere = trim($filiere['Code_Filiere'] ?? '');
        $feedbackBonus = 0.0;
        if (isset($rawProfil['feedbacks'][$codeFiliere])) {
            $fb = $rawProfil['feedbacks'][$codeFiliere];
            $feedbackRating = (int) ($fb['rating'] ?? 5);
            $isRelevant = (bool) ($fb['is_relevant'] ?? true);
            
            if ($isRelevant) {
                $feedbackBonus = 0.05 + (($feedbackRating - 3) * 0.05);
            } else {
                $feedbackBonus = -0.10 - ((3 - $feedbackRating) * 0.075);
            }
        }

        $total = $total + $bacBonus + $prestigeBonus - $transitionMalus - $wastePenalty - $penaliteCognitive + $feedbackBonus;
        $total = max(0.05, min(1.0, $total));

        return [
            'total'       => $total,
            'riasec'      => $vocationScore,
            'academique'  => $academicScore,
            'marche'      => $marcheScore,
            'accessibilite'=> $accessProb,
            'penalite'    => $penaliteCognitive + $transitionMalus + $wastePenalty,
            'confidence'  => $confidence,
            'risk'        => $risk,
            'transition'  => $this->classifyTransition($vocationScore, $academicScore, $accessProb),
            'explication' => $this->buildExplication($vocationScore, $academicScore, $marcheScore, $domain, $penaliteCognitive, $scoreFg, $sdo, $risk, $riasecVec, $gatbNorm, $codeHolland, $filiere),
        ];
    }

    /**
     * Similarité hybride (Cosinus + Distance Euclidienne) + Pénalité de Dominance
     * Assure que l'intensité de la passion est prise en compte, pas juste la direction.
     */
    private function cosineSimilarityRiasec(array $studentVec, string $filiereCode, string $studentHolland): float
    {
        if (empty($filiereCode)) return 0.5;

        $dims = ['R', 'I', 'A', 'S', 'E', 'C'];
        $filLetters = str_split(substr($filiereCode, 0, 3));

        // Vecteur filière cible
        $filiereVec = [];
        foreach ($dims as $d) {
            $pos = array_search($d, $filLetters);
            if ($pos === 0)     $filiereVec[$d] = 1.0;
            elseif ($pos === 1) $filiereVec[$d] = 0.8;
            elseif ($pos === 2) $filiereVec[$d] = 0.6;
            else                $filiereVec[$d] = 0.2;
        }

        $dot = 0.0; $normA = 0.0; $normB = 0.0;
        $euclideanDist = 0.0;

        foreach ($dims as $d) {
            $a = $studentVec[$d] ?? 0.0;
            $b = $filiereVec[$d] ?? 0.0;
            $dot   += $a * $b;
            $normA += $a * $a;
            $normB += $b * $b;
            $euclideanDist += pow($a - $b, 2);
        }

        if ($normA < 0.0001 || $normB < 0.0001) return 0.5;
        
        $cosine = $dot / (sqrt($normA) * sqrt($normB));
        $euclidean = max(0, 1.0 - (sqrt($euclideanDist) / sqrt(6))); // Normalisé (max dist = sqrt(6))

        // Modèle Hybride : 60% Cosinus (Direction) + 40% Euclidienne (Magnitude)
        $hybridScore = 0.6 * $cosine + 0.4 * $euclidean;

        // Pénalité de dominance (Top 3)
        $studentTop3 = str_split(substr($studentHolland, 0, 3));
        $filiereTop3 = str_split(substr($filiereCode, 0, 3));
        $overlap = count(array_intersect($studentTop3, $filiereTop3));

        if ($overlap < 2) {
            $hybridScore *= 0.6; // Pénalité très forte pour désalignement profond
        } elseif ($overlap == 3) {
            $hybridScore = min(1.0, $hybridScore * 1.15); // Bonus Perfect Match
        }

        return $hybridScore;
    }

    /**
     * Probabilité d'admission via Courbe Logistique (Inspiré de l'Item Response Theory)
     * Transforme l'écart Score_FG vs SDO en une probabilité d'admission réaliste.
     */
    private function logisticAdmissionProbability(float $scoreFg, float $sdo): float
    {
        if ($sdo <= 0) return 0.8; // Accessible par défaut si SDO inconnu

        // Constante de discrimination (k). Détermine la pente de la courbe (la difficulté à contourner le seuil)
        $k = 0.15; 
        
        // Formule logistique : P = 1 / (1 + e^(-k * (Score - SDO)))
        // Si Score = SDO -> P = 0.50
        // Si Score = SDO + 10 -> P ~ 0.82
        // Si Score = SDO - 10 -> P ~ 0.18
        $prob = 1.0 / (1.0 + exp(-$k * ($scoreFg - $sdo)));
        
        return $prob;
    }

    /** Calcule une similarité textuelle basique entre le profil et la filière */
    private function scoreTexte(string $nomFiliere, string $codeRiasec, string $textePsycho): float
    {
        if (empty($textePsycho)) return 0.5;

        $motsEtudiant = explode(' ', strtolower(str_replace([',', '.'], '', $textePsycho)));
        $motsFiliere = explode(' ', strtolower($nomFiliere));

        foreach (str_split(substr($codeRiasec, 0, 3)) as $lettre) {
            if (isset($this->motsRiasec[$lettre])) {
                $motsFiliere = array_merge($motsFiliere, $this->motsRiasec[$lettre]);
            }
        }

        $matches = 0;
        foreach ($motsEtudiant as $motE) {
            if (strlen($motE) < 4) continue;
            foreach ($motsFiliere as $motF) {
                if (strlen($motF) < 4) continue;
                // Correspondance de sous-chaîne (ex: informatiq -> informatique)
                if (str_contains($motF, $motE) || str_contains($motE, $motF)) {
                    $matches++;
                    break;
                }
            }
        }

        return min(1.0, $matches / max(1, count(array_filter($motsEtudiant, fn($m) => strlen($m) >= 4))));
    }

    /** Détecte le domaine d'une filière à partir de son nom + code RIASEC */
    private function detectDomain(string $nom, string $code): string
    {
        $nom = mb_strtolower($nom);
        // Enlever les accents pour les comparaisons
        $nomNorm = str_replace(
            ['é','è','ê','à','â','î','ô','û','ù'],
            ['e','e','e','a','a','i','o','u','u'],
            $nom
        );

        if (preg_match('/inform|algorithme|reseau|systeme|logiciel|cyber/', $nomNorm)) return 'informatique';
        if (preg_match('/medecin|sante|pharmac|infirmier|kine|dentair/', $nomNorm)) return 'sante';
        if (preg_match('/genie|ingenierie|mecanique|electrique|civil|industri/', $nomNorm)) return 'technique';
        if (preg_match('/biologie|chimie|physique|sciences/', $nomNorm)) return 'sciences';
        if (preg_match('/econom|gestion|commerc|finance|comptab|banque|marketing/', $nomNorm)) return 'economie';
        if (preg_match('/math|statistique/', $nomNorm)) return 'sciences';
        // ── FIX CRITIQUE : psychologie et sociologie classés en 'social', PAS 'lettres' ──
        // Ils étaient capturés par 'socio' dans la regex lettres, ce qui les faisait tomber
        // dans la catégorie lettres et échapper au filtre social.
        if (preg_match('/psycho|sociolog/', $nomNorm)) return 'social';
        if (preg_match('/droit|juridique|notariat/', $nomNorm)) return 'social';
        if (preg_match('/sport|education physique/', $nomNorm)) return 'social';
        // Filières littéraires et arts classés APRÈS les sciences/économie
        if (preg_match('/lettr|arabe|anglais|francais|histoire|philosoph|geograph/', $nomNorm)) return 'lettres';
        if (preg_match('/art|design|music|archit|communication|journalisme/', $nomNorm)) return 'arts';

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

    /** Mappe une filière à une dimension d'intérêt spécifique */
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

    /** Construit une explication industrielle v5.0 enrichie XAI */
    private function buildExplication(
        float $riasec, 
        float $acad, 
        float $marche, 
        string $domain, 
        float $penalite, 
        float $scoreFg, 
        float $sdo, 
        float $risk,
        array $riasecVec = [],
        array $gatbNorm = [],
        string $codeHolland = 'ISA',
        array $filiere = []
    ): array {
        $raisons = [];
        $points_forts = [];
        $points_faibles = [];

        $targetCode = strtoupper(trim($filiere['Code_RIASEC'] ?? ''));
        $nomF = $filiere['Nom_Filiere'] ?? 'cette filière';

        // 1. Alignement RIASEC personnalisé
        if ($riasec >= 0.70) {
            $raisons[] = "Ton code RIASEC dominant ($codeHolland) s'aligne de manière exceptionnelle sur le profil requis ($targetCode) pour $nomF, avec " . round($riasec * 100) . "% d'adéquation psychométrique.";
            $points_forts[] = "Adéquation RIASEC exceptionnelle ($codeHolland vs $targetCode).";
        } elseif ($riasec >= 0.50) {
            $raisons[] = "Ton profil psychométrique montre un bon alignement avec les exigences de cette formation (Adéquation : " . round($riasec * 100) . "%).";
            $points_forts[] = "Bonne compatibilité d'intérêts.";
        } else {
            $raisons[] = "Orientation de découverte : cette filière ($nomF) est un pivot exploratoire par rapport à ton code RIASEC.";
        }

        // 2. Alignement GATB / Capacités
        if ($acad >= 0.70) {
            $points_forts[] = "Maîtrise académique (Score FG : " . round($scoreFg, 1) . ").";
        }

        // Ajout de gap cognitifs spécifiques GATB
        $gScore = isset($gatbNorm['G']) ? round($gatbNorm['G'] * 100) : 50;
        $nScore = isset($gatbNorm['N']) ? round($gatbNorm['N'] * 100) : 50;
        
        if (($domain === 'technique' || $domain === 'sciences' || $domain === 'informatique') && $nScore < 55) {
            $points_faibles[] = "Aptitudes numériques à renforcer.";
            $raisons[] = "Tu as besoin de renforcer tes aptitudes numériques (GATB-N : {$nScore}/100) pour réussir pleinement en ingénierie/sciences.";
        }
        if (($domain === 'lettres' || $domain === 'social') && (isset($gatbNorm['V']) && $gatbNorm['V'] < 0.55)) {
            $vScore = round($gatbNorm['V'] * 100);
            $points_faibles[] = "Aptitudes verbales à consolider.";
            $raisons[] = "Il est recommandé de consolider tes aptitudes verbales (GATB-V : {$vScore}/100) pour cette filière littéraire ou sociale.";
        }

        // 3. Risques et résilience
        $statutRisque = "Faible";
        if ($risk > 0.40) {
            $statutRisque = "Élevé";
            $points_faibles[] = "Exigence de travail très élevée.";
            if ($domain === 'sante') {
                $raisons[] = "Le niveau de stress élevé de la médecine/santé (9/10) peut être particulièrement difficile avec ton score de résilience actuel.";
            } else {
                $raisons[] = "Cette formation exigeante présente un écart significatif (SDO : " . round($sdo, 1) . " vs ton score FG), ce qui nécessite un effort d'adaptation majeur.";
            }
        } elseif ($risk > 0.15) {
            $statutRisque = "Modéré";
        }

        if ($marche >= 0.75) {
            $points_forts[] = "Forte employabilité sur le marché tunisien.";
        }

        return [
            'compatibilite' => round($riasec * 100, 0) . "%",
            'risque'        => $statutRisque,
            'raisons'       => $raisons,
            'points_forts'  => $points_forts,
            'points_faibles' => $points_faibles,
            'alternatives'  => [
                'plus_sure'      => "Recherchez une filière du domaine $domain avec un SDO < " . ($sdo - 10),
                'plus_ambitieuse' => "Visez l'excellence dans le domaine $domain avec un SDO > " . ($sdo + 5)
            ]
        ];
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
        $scoreG = $gatb['GATB_G'] ?? $gatb['G'] ?? null;
        $scoreV = $gatb['GATB_V'] ?? $gatb['V'] ?? null;
        $scoreN = $gatb['GATB_N'] ?? $gatb['N'] ?? null;
        $scoreS = $gatb['GATB_S'] ?? $gatb['S'] ?? null;

        $isGatbEmpty = (
            (is_null($scoreG) || (float)$scoreG === 0.0) &&
            (is_null($scoreV) || (float)$scoreV === 0.0) &&
            (is_null($scoreN) || (float)$scoreN === 0.0) &&
            (is_null($scoreS) || (float)$scoreS === 0.0)
        );

        if ($isGatbEmpty) {
            $result['axes_amelioration'][] = "Compléter le test d'aptitudes cognitives (GATB) pour affiner votre profil";
        } else {
            // Seuil d'amélioration : < 55/100
            if ($scoreG !== null && (float)$scoreG < 55.0) {
                $result['axes_amelioration'][] = "Renforcer le raisonnement logique (exercices de logique, mathématiques)";
            }
            if ($scoreV !== null && (float)$scoreV < 55.0) {
                $result['axes_amelioration'][] = "Développer les compétences verbales (lecture, rédaction)";
            }
            if ($scoreN !== null && (float)$scoreN < 55.0) {
                $result['axes_amelioration'][] = "Améliorer les aptitudes numériques (calcul, statistiques)";
            }
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
