<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\Filiere;
use App\Models\ProfileRiasec;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * ComparateurController — Comparateur interactif de filières.
 *
 * Permet de comparer 2 à 4 filières côte-à-côte (formations, filières ou saisie libre).
 */
class ComparateurController extends Controller
{
    /**
     * Affiche la page du comparateur.
     */
    public function index(): \Illuminate\View\View
    {
        $formations = Formation::with('specialite')
                               ->orderBy('nom')
                               ->take(10)
                               ->get(['id', 'nom', 'etablissement', 'niveau', 'specialite_id', 'icon']);

        return view('student.comparateur.index', compact('formations'));
    }

    /**
     * Autocomplete search endpoint.
     */
    public function search(Request $request): JsonResponse
    {
        $q = $request->query('q', '');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        // 1. Recherche dans formations
        $formations = Formation::where('nom', 'like', "%$q%")
            ->orWhere('etablissement', 'like', "%$q%")
            ->take(5)
            ->get();

        // 2. Recherche dans filieres
        $filieres = Filiere::where('nom_filiere', 'like', "%$q%")
            ->orWhere('etablissement', 'like', "%$q%")
            ->orWhere('universite', 'like', "%$q%")
            ->take(15)
            ->get();

        $results = [];

        foreach ($formations as $f) {
            $results[] = [
                'value' => 'formation:' . $f->id,
                'label' => '🎓 ' . $f->nom . ' (' . $f->etablissement . ') [Formation]',
                'icon' => $f->icon,
                'etab' => $f->etablissement,
                'niveau' => $f->niveau,
                'ville' => $f->ville,
                'duree' => $f->duree,
            ];
        }

        foreach ($filieres as $f) {
            $niveau = $this->detectLevel($f->nom_filiere);
            $duree = $this->detectDuration($niveau);
            $icon = $this->detectIcon($f->nom_filiere, $f->domaine ?? '');
            $ville = $this->getCityFromEtab($f->etablissement, $f->universite);

            $results[] = [
                'value' => 'filiere:' . $f->id,
                'label' => $icon . ' ' . $f->nom_filiere . ' (' . $f->etablissement . ') [Filière Nationale]',
                'icon' => $icon,
                'etab' => $f->etablissement,
                'niveau' => $niveau,
                'ville' => $ville . ' (Univ. ' . $f->universite . ')',
                'duree' => $duree,
            ];
        }

        return response()->json($results);
    }

    /**
     * Retourne les données de comparaison pour un ensemble de composite IDs (AJAX).
     */
    public function comparer(Request $request): JsonResponse
    {
        $ids = $request->validate([
            'ids'   => ['required', 'array', 'min:2', 'max:4'],
            'ids.*' => ['string'],
        ])['ids'];

        $userId = auth()->id();
        $academicProfile = \App\Models\Profile::where('user_id', $userId)->first();
        $scoreFg = $academicProfile ? (float)$academicProfile->score_fg : 120.0;

        $riasec = ProfileRiasec::pourUser($userId)->complets()->recents()->first();

        $data = [];

        foreach ($ids as $compositeId) {
            $parts = explode(':', $compositeId, 2);
            $type = $parts[0] ?? '';
            $val = $parts[1] ?? '';

            if ($type === 'formation') {
                $f = Formation::with('specialite')->find((int)$val);
                if ($f) {
                    $salaireMax     = 8000; 
                    $dureeMax       = 60;   
                    $dureeMois = $this->parseDureeMois($f->duree);
                    $sdo = $this->getEstimatedSdo($f->etablissement, $f->niveau);
                    
                    if ($scoreFg >= $sdo) {
                        $accessibilite = (int) min(100, 75 + (($scoreFg - $sdo) * 1.5));
                    } else {
                        $accessibilite = (int) max(15, 75 - (($sdo - $scoreFg) * 2.5));
                    }

                    $data[] = [
                        'id'             => 'formation:' . $f->id,
                        'nom'            => $f->nom,
                        'icon'           => $f->icon,
                        'etablissement'  => $f->etablissement,
                        'ville'          => $f->ville,
                        'niveau'         => $f->niveau,
                        'duree'          => $f->duree,
                        'domaine'        => $f->specialite?->domaine ?? 'N/A',
                        'icon_spec'      => $f->specialite?->icon ?? '🎓',
                        'salaire_min'    => $f->salaire_min,
                        'salaire_max'    => $f->salaire_max,
                        'description'    => $f->description,
                        'debouches'      => $f->debouches,
                        'sdo_estime'     => $sdo,
                        'secteur'        => $f->specialite?->domaine ?? 'N/A',
                        'radar' => [
                            'matching'    => (int) $f->score_matching,
                            'salaire'     => (int) min(100, ($f->salaire_max / $salaireMax) * 100),
                            'rapidite'    => (int) max(0, 100 - (($dureeMois / $dureeMax) * 100)),
                            'insertion'   => $this->getInsertionScore($f->niveau),
                            'accessibilite'=> $accessibilite,
                        ],
                    ];
                }
            } elseif ($type === 'filiere') {
                $f = Filiere::find((int)$val);
                if ($f) {
                    $niveau = $this->detectLevel($f->nom_filiere);
                    $dureeText = $this->detectDuration($niveau);
                    $dureeMois = $this->parseDureeMois($dureeText);
                    $icon = $this->detectIcon($f->nom_filiere, $f->domaine ?? '');
                    $ville = $this->getCityFromEtab($f->etablissement, $f->universite);
                    $salaries = $this->getDomainSalaries($f->domaine ?? '');
                    
                    $sdo = (float)($f->sdo_2025 ?: ($f->sdo_2024 ?: ($f->sdo_2023 ?: 110.0)));
                    if ($scoreFg >= $sdo) {
                        $accessibilite = (int) min(100, 75 + (($scoreFg - $sdo) * 1.5));
                    } else {
                        $accessibilite = (int) max(15, 75 - (($sdo - $scoreFg) * 2.5));
                    }

                    $matching = $this->calculateMatchingScore($riasec, $f->domaine ?? '');

                    $data[] = [
                        'id'             => 'filiere:' . $f->id,
                        'nom'            => $f->nom_filiere,
                        'icon'           => $icon,
                        'etablissement'  => $f->etablissement,
                        'ville'          => $ville . ' (Univ. ' . $f->universite . ')',
                        'niveau'         => $niveau,
                        'duree'          => $dureeText,
                        'domaine'        => $f->domaine ?? 'N/A',
                        'icon_spec'      => '🎓',
                        'salaire_min'    => $salaries['min'],
                        'salaire_max'    => $salaries['max'],
                        'description'    => "Filière universitaire nationale régie par l'orientation universitaire tunisienne.",
                        'debouches'      => "Débouchés professionnels dans le secteur de : " . ($f->domaine ?? 'N/A'),
                        'sdo_estime'     => $sdo,
                        'secteur'        => $f->domaine ?? 'N/A',
                        'radar' => [
                            'matching'    => $matching,
                            'salaire'     => (int) min(100, ($salaries['max'] / 8000) * 100),
                            'rapidite'    => (int) max(0, 100 - (($dureeMois / 60) * 100)),
                            'insertion'   => $this->getInsertionScore($niveau),
                            'accessibilite'=> $accessibilite,
                        ],
                    ];
                }
            } elseif ($type === 'manual') {
                $rawName = trim($val);
                if ($rawName) {
                    $niveau = $this->detectLevel($rawName);
                    $dureeText = $this->detectDuration($niveau);
                    $dureeMois = $this->parseDureeMois($dureeText);
                    $icon = $this->detectIcon($rawName, '');
                    
                    $sdo = 110.0;
                    if ($scoreFg >= $sdo) {
                        $accessibilite = (int) min(100, 75 + (($scoreFg - $sdo) * 1.5));
                    } else {
                        $accessibilite = (int) max(15, 75 - (($sdo - $scoreFg) * 2.5));
                    }

                    $data[] = [
                        'id'             => 'manual:' . $rawName,
                        'nom'            => $rawName,
                        'icon'           => $icon,
                        'etablissement'  => 'Saisie manuelle',
                        'ville'          => 'Tunisie',
                        'niveau'         => $niveau,
                        'duree'          => $dureeText,
                        'domaine'        => 'Général',
                        'icon_spec'      => '🎓',
                        'salaire_min'    => '900',
                        'salaire_max'    => '2000',
                        'description'    => "Filière saisie manuellement par l'étudiant à des fins de simulation comparative.",
                        'debouches'      => "Débouchés variés selon le parcours exact choisi.",
                        'sdo_estime'     => $sdo,
                        'secteur'        => 'N/A',
                        'radar' => [
                            'matching'    => 70, 
                            'salaire'     => (int) min(100, (2000 / 8000) * 100),
                            'rapidite'    => (int) max(0, 100 - (($dureeMois / 60) * 100)),
                            'insertion'   => $this->getInsertionScore($niveau),
                            'accessibilite'=> $accessibilite,
                        ],
                    ];
                }
            }
        }

        return response()->json(['success' => true, 'formations' => $data]);
    }

    private function getCityFromEtab(string $etab, string $univ): string
    {
        $text = strtolower($etab . ' ' . $univ);
        if (str_contains($text, 'sfax')) return 'Sfax';
        if (str_contains($text, 'sousse')) return 'Sousse';
        if (str_contains($text, 'monastir')) return 'Monastir';
        if (str_contains($text, 'gabès') || str_contains($text, 'gabes')) return 'Gabès';
        if (str_contains($text, 'gafsa')) return 'Gafsa';
        if (str_contains($text, 'jendouba')) return 'Jendouba';
        if (str_contains($text, 'kairouan')) return 'Kairouan';
        if (str_contains($text, 'nabeul')) return 'Nabeul';
        if (str_contains($text, 'bizerte')) return 'Bizerte';
        if (str_contains($text, 'manouba')) return 'La Manouba';
        if (str_contains($text, 'ariana')) return 'Ariana';
        if (str_contains($text, 'carthage')) return 'Carthage';
        
        return 'Tunis';
    }

    private function detectLevel(string $nom): string
    {
        $n = strtolower($nom);
        if (str_contains($n, 'ingénieur') || str_contains($n, 'ingénierie')) return 'Ingénierie';
        if (str_contains($n, 'master') || str_contains($n, 'mastère')) return 'Master';
        if (str_contains($n, 'doctorat')) return 'Doctorat';
        if (str_contains($n, 'preparatoire') || str_contains($n, 'préparatoire')) return 'Préparatoire';
        
        return 'Licence';
    }

    private function detectDuration(string $niveau): string
    {
        return match ($niveau) {
            'Master' => '2 ans',
            'Doctorat' => '3 ans',
            'Préparatoire' => '2 ans',
            default => '3 ans',
        };
    }

    private function detectIcon(string $nom, string $domain): string
    {
        $text = strtolower($nom . ' ' . $domain);
        if (str_contains($text, 'informatique') || str_contains($text, 'info') || str_contains($text, 'logiciel') || str_contains($text, 'data') || str_contains($text, 'web') || str_contains($text, 'mobile') || str_contains($text, 'réseau')) return '💻';
        if (str_contains($text, 'santé') || str_contains($text, 'médecine') || str_contains($text, 'médical') || str_contains($text, 'pharmacie') || str_contains($text, 'dentaire')) return '🩺';
        if (str_contains($text, 'finance') || str_contains($text, 'comptabilité') || str_contains($text, 'gestion') || str_contains($text, 'marketing') || str_contains($text, 'management') || str_contains($text, 'économie')) return '📈';
        if (str_contains($text, 'civil') || str_contains($text, 'méc') || str_contains($text, 'élec') || str_contains($text, 'techno') || str_contains($text, 'ingénieur') || str_contains($text, 'industriel')) return '🏗️';
        if (str_contains($text, 'art') || str_contains($text, 'design') || str_contains($text, 'musique') || str_contains($text, 'architecture')) return '🎨';
        if (str_contains($text, 'sport') || str_contains($text, 'physique')) return '⚽';
        
        return '🎓';
    }

    private function getDomainSalaries(string $domain): array
    {
        $d = strtolower($domain);
        if (str_contains($d, 'informatique') || str_contains($d, 'technologie')) {
            return ['min' => '1500', 'max' => '3800'];
        }
        if (str_contains($d, 'santé') || str_contains($d, 'médic')) {
            return ['min' => '1300', 'max' => '3200'];
        }
        if (str_contains($d, 'gestion') || str_contains($d, 'éco') || str_contains($d, 'finance')) {
            return ['min' => '1100', 'max' => '2500'];
        }
        
        return ['min' => '900', 'max' => '2000'];
    }

    private function calculateMatchingScore(?ProfileRiasec $riasec, string $domain): int
    {
        if (!$riasec) {
            return 50; // Pas de profil RIASEC → score neutre
        }

        $domainMap = [
            'informatique' => ['I', 'R', 'C'],
            'technologie' => ['R', 'I', 'E'],
            'sciences expérimentales' => ['I', 'S', 'R'],
            'mathématiques et appliquées' => ['I', 'C'],
            'économie et gestion' => ['E', 'C', 'I'],
            'lettres et sciences humaines' => ['S', 'A', 'I'],
            'sport' => ['R', 'E', 'S'],
            'santé' => ['I', 'S', 'C'],
            'droit' => ['E', 'S', 'C'],
            'arts' => ['A', 'S', 'E'],
        ];

        $d = strtolower($domain);
        $targetDims = [];
        foreach ($domainMap as $key => $dims) {
            if (str_contains($d, $key) || str_contains($key, $d)) {
                $targetDims = $dims;
                break;
            }
        }

        if (empty($targetDims)) {
            $targetDims = ['I', 'C']; // Fallback générique
        }

        // Calcul pondéré : première dimension pèse plus (40%), les suivantes répartissent le reste
        $weights = [];
        $remaining = 1.0;
        foreach ($targetDims as $i => $dim) {
            $w = ($i === 0) ? 0.4 : (0.6 / (count($targetDims) - 1));
            $weights[] = $w;
        }

        $weightedSum = 0;
        foreach ($targetDims as $i => $dim) {
            $prop = 'score_' . strtolower($dim);
            $score = $riasec->$prop ?? 0;
            $weightedSum += $score * $weights[$i];
        }

        return (int) min(98, max(20, round($weightedSum)));
    }

    /**
     * SDO estimé d'admission selon l'établissement et le niveau.
     */
    private function getEstimatedSdo(string $etablissement, string $niveau): float
    {
        $etab = strtolower($etablissement);
        if (str_contains($etab, 'médecine')) return 175.0;
        if (str_contains($etab, 'insat')) return 165.0;
        if (str_contains($etab, 'ensi')) return 160.0;
        if (str_contains($etab, 'enit')) return 155.0;
        if (str_contains($etab, 'esprit')) return 130.0;
        if (str_contains($etab, 'ihec')) return 135.0;
        if (str_contains($etab, 'isi')) return 130.0;
        if (str_contains($etab, 'isg')) return 120.0;
        if (str_contains($etab, 'sciences de tunis') || str_contains($etab, 'fst')) return 125.0;
        
        return match ($niveau) {
            'Ingénierie' => 145.0,
            'Doctorat'   => 150.0,
            'Master'     => 120.0,
            'Licence'    => 115.0,
            default      => 100.0,
        };
    }

    /**
     * Parse la durée textuelle en mois (ex: "3 ans" → 36).
     */
    private function parseDureeMois(string $duree): int
    {
        if (preg_match('/(\d+)\s*ans?/i', $duree, $m)) return (int)$m[1] * 12;
        if (preg_match('/(\d+)\s*mois/i', $duree, $m)) return (int)$m[1];
        return 36; // défaut
    }

    /**
     * Score d'insertion professionnelle estimé par niveau.
     */
    private function getInsertionScore(string $niveau): int
    {
        return match ($niveau) {
            'Doctorat'   => 95,
            'Ingénierie' => 90,
            'Master'     => 82,
            'Licence'    => 72,
            'BTS'        => 78,
            default      => 70,
        };
    }
}
