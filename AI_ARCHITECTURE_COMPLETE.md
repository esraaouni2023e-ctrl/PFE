# 📚 SIAEPI v8.0 - Architecture Complète du Système IA

## 📋 Table des matières
1. [Vue d'ensemble](#vue-densemble)
2. [Architecture générale](#architecture-générale)
3. [Modèles de données](#modèles-de-données)
4. [Services et moteurs IA](#services-et-moteurs-ia)
5. [Contrôleurs et points d'entrée](#contrôleurs-et-points-dentrée)
6. [Base de données](#base-de-données)
7. [Flux de données](#flux-de-données)
8. [Algorithmes clés](#algorithmes-clés)
9. [Intégrations externes](#intégrations-externes)

---

## 🎯 Vue d'ensemble

**SIAEPI** (Système Intelligent d'Assistance à l'Évaluation et à l'Orientation Professionnelle Innovant) est un système d'orientation universitaire basé sur l'IA qui combine :

- **Tests psychométriques adaptatifs** (RIASEC - Holland)
- **Calcul de Score FG** (Formule Globale du BAC tunisien)
- **Analyse psychologique complète** (Big Five, GATB, Résilience)
- **Recommandation de filières** basée sur l'appariement IA
- **Simulation de carrière** (What-If engine)
- **Chatbot intelligent** (Ollama + Nova Gemini)

**Contexte tunisien** : Adaptée aux filières universitaires tunisiennes, critères d'admission (SDO 2023-2025), et marchés de l'emploi tunisiens.

---

## 🏗️ Architecture générale

```
┌─────────────────────────────────────────────────────────────────┐
│                    INTERFACE UTILISATEUR                         │
│  (Vues Blade + Vue.js interactif)                              │
└──────────┬──────────────────────────────────────────────────────┘
           │
    ┌──────┴───────────────────────────────────────┐
    │                                               │
    v                                               v
┌─────────────────────┐                  ┌────────────────────┐
│  Student Pipeline   │                  │  Admin Dashboard   │
│  - Orientation      │                  │  - Gestion Données │
│  - Tests RIASEC     │                  │  - Analytics       │
│  - Comparateur      │                  │  - Exports         │
└──────────┬──────────┘                  └────────────────────┘
           │
    ┌──────┴────────────────────────────────────────────┐
    │                                                     │
    v                                                     v
┌──────────────────────────────────┐    ┌─────────────────────────┐
│     CONTROLLERS (HTTP Layer)      │    │    API ENDPOINTS        │
├──────────────────────────────────┤    ├─────────────────────────┤
│ • RiasecTestController           │    │ • /student/orientation  │
│ • OrientationController          │    │ • /student/riasec       │
│ • NovaOrientationController      │    │ • /student/whatif       │
│ • OrientationPipelineController  │    │ • /student/comparateur  │
│ • StudentController              │    └─────────────────────────┘
│ • WhatIfController               │
└──────────┬──────────────────────┘
           │
    ┌──────┴──────────────────────────────────────────────────┐
    │                                                           │
    v                                                           v
┌──────────────────────────────────────┐   ┌─────────────────────────┐
│       SERVICES LAYER (Business Logic) │   │   EXTERNAL SERVICES     │
├──────────────────────────────────────┤   ├─────────────────────────┤
│ • SiaepiRecommendationEngine         │   │ • Gemini API (Nova)     │
│ • CareerPathEngine                   │   │ • Ollama (Local LLM)    │
│ • ScoreFGService                     │   │ • Firebase/Cloud Logs   │
│ • AdmissionPredictorService          │   │ • ANETI Data            │
│ • FutureSimulatorService             │   │ • INS Tunisia Stats     │
│ • NovaOrientationService             │   └─────────────────────────┘
│ • OllamaService                      │
│ • RIASEC Services (TestManager, ...) │
└──────────┬──────────────────────────┘
           │
    ┌──────┴──────────────────────────────────────────┐
    │                                                  │
    v                                                  v
┌───────────────────────────────┐    ┌──────────────────────────┐
│    MODELS (Data Access)       │    │   CACHE & SESSION        │
├───────────────────────────────┤    ├──────────────────────────┤
│ • Filiere                     │    │ • Redis (CAT state)      │
│ • FiliereProfile              │    │ • Session (RIASEC)       │
│ • ProfileRiasec               │    │ • Cache (Questions)      │
│ • AnswerRiasec                │    │ • Memory (Test progress) │
│ • QuestionRiasec              │    └──────────────────────────┘
│ • StudentInteraction          │
│ • Recommendation              │
│ • User & Profile              │
│ • Domaine/SousDomaine/Metier  │
└──────────┬────────────────────┘
           │
    ┌──────┴──────────────────────┐
    │                              │
    v                              v
┌──────────────────────────────┐ ┌──────────────────────┐
│   POSTGRESQL DATABASE        │ │   EXCEL IMPORTS      │
│   (Primary Data Store)       │ │   (Filières Tunisie) │
└──────────────────────────────┘ └──────────────────────┘
```

---

## 📊 Modèles de données

### 1. **Modèle Utilisateur et Profil**

#### `User`
- `id` (PK)
- `name`, `email`, `password`
- `role` (student, counselor, admin)
- `avatar`, `is_blocked`
- Timestamps

#### `Profile`
- `id` (PK)
- `user_id` (FK)
- `section_bac` (Mathématiques, Sciences expérimentales, etc.)
- `moyenne_generale` (0-20)
- `score_fg` (0-200) - **Formule Globale calculée**
- `gouvernorat` (Région de Tunisie)
- **Propriétés académiques enrichies** :
  - Notes par matière (stockées en JSON)
  - Préférences d'études
  - Historique des tests
- Timestamps

---

### 2. **Modèle RIASEC (Test Psychométrique)**

#### `QuestionRiasec`
```php
- id (PK)
- dimension (CHAR 1: R|I|A|S|E|C ou GATB_G/V/N/S ou B5_O/C/E/A/N ou RESILIENCE)
- bloc (riasec | big_five | gatb | resilience | attention)
- categorie (Intérêts professionnels, Aptitudes, Traits, etc.)
- texte_fr, texte_ar (Localisations)
- type_reponse (likert | boolean | choice)
- options (JSON array pour type='choice')
- poids (1-3) - Importance de la question
- ordre, actif, version
- difficulty, discrimination (Calibrage psychométrique)
- is_reverse (Questions inversées)
- is_seed (Questions de démarrage du test adaptatif)
- bacs_cibles (JSON: sections BAC ciblées)
- Timestamps
```

**Banque totale** : ~74 questions réparties en :
- **18 questions RIASEC pures** (3 par dimension R/I/A/S/E/C)
- **10 questions Big Five** (2 par trait O/C/E/A/N)
- **8 questions GATB** (2 par aptitude G/V/N/S)
- **6 questions Résilience**
- **Autres questions** de domaines spécifiques (MED, ENG, INFO, DROIT, ECO, EDU, ART, LTR, SOC, SPO, ARCHI)

#### `AnswerRiasec`
```php
- id (PK)
- test_session_id (UUID: lien avec session de test)
- user_id (FK: nullable pour invités)
- session_guest_id (ID session PHP pour invités)
- question_id (FK → QuestionRiasec)
- valeur (1-5 pour Likert, 0-1 pour Boolean, 1-5 pour choice)
- temps_reponse_ms (Temps pour répondre)
- Timestamps
```

#### `ProfileRiasec` (Résultat du test)
```php
- id (PK)
- test_session_id (UUID: lien unique avec la session)
- user_id (FK: nullable)
- session_guest_id (ID session PHP)
- score_r, score_i, score_a, score_s, score_e, score_c (0-100 chacun)
- code_holland (EX: "IAS" - 3 lettres dominantes)
- statut (en_cours | complet | expire)
- nb_questions_repondues, nb_questions_total
- score_coherence (Fiabilité 0-100 : cohérence interne)
- interpretation (JSON: profil enrichi, recommandations)
- duree_minutes (Durée totale du test)
- complete_at (Timestamp fin)
- score_gatb_g, score_gatb_v, score_gatb_n, score_gatb_s (0-100)
- score_resilience (0-100)
- is_flagged, flag_reason (Détection fraude)
- validation_status (Statut de validation)
- stopped_early, confidence_score, blocks_completed (Early stopping)
- Timestamps
```

---

### 3. **Modèle Filières (Université)**

#### `Filiere`
```php
- id (PK)
- code_filiere (UNIQUE: identifiant tunisien)
- nom_filiere
- universite, etablissement
- sdo_2023, sdo_2024, sdo_2025 (Score minimum d'admission par année)
- domaine (Économie et Gestion, Sciences Expérimentales, etc.)
- domaine_id (FK → Domaine)
- sous_domaine_id (FK → SousDomaine)
- specialisation_id (FK → Specialisation)
- code_riasec (EX: "ICA" - code Holland cible)
- taux_employabilite (Très élevé, Élevé, Modéré, Faible)
- croissance_domaine (Croissance, Stable, Déclin)
- type_bac (BAC tunisien ciblé)
- g_requis, v_requis, n_requis, s_requis (Seuils GATB 0-20)
- Timestamps
```

#### `FiliereProfile` (Profil psychométrique cible)
```php
- id (PK)
- code_filiere (UNIQUE)
- nom_filiere
- domaine
- riasec_r, riasec_i, riasec_a, riasec_s, riasec_e, riasec_c (0.0-1.0 cibles)
- gatb_g_required, gatb_v_required, gatb_n_required, gatb_s_required (0-100)
- employability_index (0.0-1.0)
- employability_rate, growth_rate, annual_openings
- difficulty_level (1-10)
- stress_tolerance (1-10)
- job_demand, salary, internships (0.0-1.0)
- market_source (ANETI / INS Tunisie)
- market_date, market_region
- big5_openness, big5_conscientiousness, big5_extraversion, big5_agreeableness, big5_neuroticism (-3 à +3 z-scores)
- description
- Timestamps
```

#### `StudentInteraction` (Tracking utilisateur)
```php
- id (PK)
- user_id (FK)
- filiere_code (Filière consultée)
- action (view | save | ignore)
- weight (Poids pour recommandation)
- Timestamps
```

---

### 4. **Modèle Taxonomie**

#### `Domaine` (Niveau 1)
```php
- id (PK)
- code (UNIQUE)
- nom (EX: "Informatique & TIC")
- description
- icon (emoji)
- Timestamps
```

#### `SousDomaine` (Niveau 2)
```php
- id (PK)
- domaine_id (FK)
- code, nom, description
- Timestamps
```

#### `Specialisation` (Niveau 3)
```php
- id (PK)
- sous_domaine_id (FK)
- code, nom, description
- Timestamps
```

#### `Metier` (Débouchés professionnels)
```php
- id (PK)
- specialisation_id (FK)
- title, description
- salary_range (EX: "1200-2000 TND")
- employability (Très élevé, Élevé, etc.)
- secteurs (JSON array)
- skills_hard (JSON array: compétences techniques)
- skills_soft (JSON array: compétences transversales)
- perspectives (Texte sur avenir professionnel)
- Timestamps
```

---

### 5. **Modèles secondaires**

#### `Recommendation`
```php
- id (PK)
- user_id (FK)
- title, description
- data (JSON: détails recommandation)
- source (siaepi | nova | chatbot)
- relevance (0-100: pertinence)
- created_by (user_id: conseiller ayant créé)
- Timestamps
```

#### `SimulationHistory`
```php
- id (PK)
- user_id (FK)
- section_bac, moyenne_generale
- notes_matieres (JSON)
- score_fg
- label (Nom de la simulation)
- formations_accessibles (JSON)
- Timestamps
```

#### `RecommendationFeedback`
```php
- id (PK)
- user_id (FK)
- recommendation_id (FK)
- rating (1-5)
- comment
- is_helpful (Boolean)
- Timestamps
```

---

## ⚙️ Services et moteurs IA

### 1. **SiaepiRecommendationEngine**

**Responsabilité** : Moteur central de recommandation basé sur appariement psychométrique.

**Algorithme principal** :

```
Pour chaque filière :
  1. Charger RIASEC cible (code_filiere → FiliereProfile)
  2. Charger RIASEC étudiant (ProfileRiasec)
  3. Calculer similarité cosinus : cos(vecteur_etudiant, vecteur_filiere)
  4. Poids multiplicatifs :
     - RIASEC match (0-1)
     - GATB compatibility (0-1)
     - Big Five alignment (0-1)
     - Score FG vs SDO (0-1)
     - Employability index (0-1)
     - Domaine interesse (0-1)
  5. Score final = produit pondéré + normalisation sigmoid
  6. Ranking par score décroissant
```

**Données chargées** (loadFilieres) :
```php
- Tous codes RIASEC par filière (avec reconstruction dynamique)
- GATB requis par domaine
- Profils Big Five cibles
- Données employabilité (ANETI 2026)
- Scores SDO (Scores de démarcation 2023-2025)
```

**Profils psychométriques cibles par domaine** :
```php
'informatique' => [
    'B5' => ['O' => 0.7, 'C' => 0.8, 'E' => 0.0, 'A' => 0.0, 'N' => -0.4],
    'Val' => ['Sec' => 0.0, 'Ach' => 0.7, 'Ben' => 0.0, 'Aut' => 0.6]
],
'sante' => [
    'B5' => ['O' => 0.0, 'C' => 0.8, 'E' => 0.0, 'A' => 0.9, 'N' => -0.5],
    'Val' => ['Sec' => 0.6, 'Ach' => 0.0, 'Ben' => 0.9, 'Aut' => 0.0]
],
// ... technologie, sciences, économie, lettres, social, arts
```

---

### 2. **CareerPathEngine**

**Responsabilité** : Fournit des fiches métier complètes par domaine.

**Domaines couverts** (8) :
1. **Informatique & TIC** 
   - Métiers : Ingénieur Logiciel, Cloud/DevOps, Data Scientist/BI
   - Salaires : 1200-2800 TND/mois
   - Employabilité : Très élevée (95%)

2. **Santé & Sciences Médicales**
   - Métiers : Médecin, Pharmacien, Kinésithérapeute
   - Salaires : 900-3500 TND/mois
   - Employabilité : Très élevée (92%)

3. **Technique & Génie Industriel**
   - Métiers : Ingénieur Électromécanique, Génie Civil
   - Salaires : 1100-2000 TND/mois
   - Employabilité : Élevée (85%)

4. **Sciences Fondamentales**
   - Métiers : Technicien Labo, Responsable QC
   - Salaires : 800-1600 TND/mois
   - Employabilité : Modérée (70%)

5. **Économie & Gestion**
   - Métiers : Auditeur Financier, Conseiller Bancaire
   - Salaires : 900-1800 TND/mois
   - Employabilité : Élevée (80%)

6. **Lettres & Traduction**
   - Métiers : Traducteur, Enseignant
   - Salaires : 800-1500 TND/mois
   - Employabilité : Modérée (55%)

7. **Sciences Sociales & Juridique**
   - Métiers : Avocat, Conseiller RH
   - Salaires : 800-1500 TND/mois
   - Employabilité : Stable (60%)

8. **Arts & Design**
   - Métiers : Graphiste, Designer UX, Architecte
   - Salaires : 800-1800 TND/mois
   - Employabilité : Modérée (55%)

**Structure métier** :
```php
[
    'title' => '...',
    'description' => '...',
    'secteurs' => ['secteur1', ...],
    'skills_hard' => ['tech1', ...],
    'skills_soft' => ['soft1', ...],
    'salary_range' => '...',
    'employability' => '...'
]
```

---

### 3. **ScoreFGService**

**Responsabilité** : Calcul du Score FG (Formule Globale) du BAC tunisien.

**Formules officielles par section BAC** :

```
1. Mathématiques : FG = 4*MG + 2*M + 1.5*SP + 0.5*SVT + 1*F + 1*Ang

2. Sciences expérimentales : FG = 4*MG + 1*M + 1.5*SP + 1.5*SVT + 1*F + 1*Ang

3. Économie et gestion : FG = 4*MG + 1.5*Ec + 1.5*Ge + 0.5*M + 0.5*HG + 1*F + 1*Ang

4. Technique : FG = 4*MG + 1.5*TE + 1.5*M + 1*SP + 1*F + 1*Ang

5. Informatique : FG = 4*MG + 1.5*Algo + 0.5*SP + 0.5*STI + 1*F + 1*Ang

6. Lettres : FG = 4*MG + 1.5*A + 1.5*PH + 1*HG + 1*F + 1*Ang

7. Sport : FG = 4*MG + 1.5*SB + 1*Sp-sport + 0.5*EP + 0.5*SP + 0.5*PH + 1*F + 1*Ang
```

**Résultat** : Score 0-200 représentant la performance académique.

**Cas de test (getFormationsAccessibles)** :
- Convertit FG (0-200) en % (0-100)
- Récupère formations avec `score_matching <= %`
- Classement par score_matching décroissant

---

### 4. **AdmissionPredictorService**

**Responsabilité** : Prédiction probabiliste des chances d'admission.

**Logique MVP** :
```php
baseScore = 60
dynamicScore = randomSeed(userID, formationName)
chances = min(98, max(45, baseScore + rand(-15, 35)))
// Résultat : 45-98%
```

**À améliorer** : Intégration modèle ML réel (classification logistique, forêts aléatoires).

---

### 5. **FutureSimulatorService**

**Responsabilité** : Engine de simulation "What-If" pour scénarios académiques/professionnels.

**6 modules de simulation** :

#### Module 1 : Variation de notes
- **Input** : Section BAC, MG, notes matieres
- **Output** : Score FG simulé, formations accessibles, chances admission
- **Cas d'usage** : "Que se passe-t-il si j'améliore mes maths de 1 point ?"

#### Module 2 : Changement de spécialité
- **Input** : Section actuelle, score actuel, nouvelle section
- **Output** : Comparaison delta score, formations accessibles avant/après, verdict
- **Cas d'usage** : "Dois-je changer de section du BAC ?"

#### Module 3 : Filière alternative
- **Input** : Filières actuelles sélectionnées
- **Output** : Filières alternatives similaires, scores match
- **Cas d'usage** : "Quelles sont les alternatives si je ne suis pas accepté(e) ?"

#### Module 4 : Secteur & Employabilité
- **Data** : 10 secteurs tunisiens (Informatique, Santé, Ingénierie, etc.)
- **Indices** : Taux insertion, saturation, croissance, salaire moyen
- **Analyse** : Quels secteurs correspondent à votre profil ?

#### Module 5 : Salaires & ROI
- **Grille salariale** par niveau (Licence, Ingénieur, Master, Doctorat)
- **Évolution** : Salaires après 5 et 10 ans d'expérience
- **ROI** : Coût études vs gains salariaux

#### Module 6 : Compatibilité Carrière
- **Mapping RIASEC → Domaines professionnels** :
  - R → Ingénierie, Agriculture, Informatique
  - I → Santé, Ingénierie, Informatique
  - A → Arts, Éducation, Tourisme
  - S → Éducation, Santé, Droit
  - E → Commerce, Finance, Tourisme
  - C → Finance, Commerce, Droit

---

### 6. **NovaOrientationService**

**Responsabilité** : Conseiller IA basé sur Google Gemini (API officielle).

**Moteur** : `gemini-2.0-flash` / `gemini-2.5-flash` (avec fallback `gemini-2.5-pro`).

**Prompt système** :
```
Tu es Nova, conseiller d'orientation expert.
Ton rôle : Analyser le profil étudiant (notes, MG, section BAC).

Étape 1 - Calcul FG : Appliquer formule officielle tunisienne strictement
Étape 2 - Recommandations : Suggérer 3-5 filières optimales

Réponse JSON obligatoire :
{
  "section_bac": "...",
  "score_fg": 158.45,
  "top_filieres": [...],
  "conseil_personnalise": "..."
}
```

**Cas d'usage** : Alternative interactive au calcul IA pure.

---

### 7. **OllamaService**

**Responsabilité** : Intégration LLM locale (Ollama) pour analyses texte.

**Modèle** : `llama3` (ou personnalisable).

**Capacités** :
1. **Analyse Portfolio** : Extraction automatique compétences depuis projets
   ```
   Input : Texte projet/certificat
   Output : {summary, skills[]}
   ```

2. **Génération Roadmap** : Parcours académique vers métier cible
   ```
   Input : Métier cible, profil étudiant
   Output : Étapes (Licence 3 ans → Master 2 ans → ...)
   ```

---

### 8. **RIASEC Services** (Sous-dossier `app/Services/RIASEC/`)

#### **TestManager** (v5.0)

**Responsabilités** :
1. Chargement questions (cache 1h)
2. Enregistrement réponses
3. Calcul scores RIASEC (Holland 6 dimensions)
4. Calcul cohérence interne
5. Détermination trigramme dominant
6. Génération interprétation enrichie

**Calcul scores RIASEC** :
```
Pour chaque dimension (R/I/A/S/E/C) :
  1. Filtre questions RIASEC pures (bloc='riasec', dimension=D)
  2. Somme : score = Σ(réponse * poids) pour chaque question
  3. Normalisation : score_0_100 = (score_brut / score_max) * 100
  4. Inversion : si is_reverse=true, inverte (5 - valeur)
  5. Résultat final : 0-100 par dimension
```

**Trigramme Holland** :
```
1. Trier 3 dimensions avec scores plus élevés
2. En cas d'égalité, utiliser HOLLAND_PRIORITY = R(0) > I(1) > A(2) > S(3) > E(4) > C(5)
Résultat : Code à 3 lettres (EX: "IAS")
```

**Cohérence interne** :
```
Mesure fiabilité réponses (0-100) :
- Comparer réponses similaires
- Détecter incohérences
- Flag profils douteux
```

---

#### **AdaptiveTestEngine** (v5.2 - IRT Bayésien)

**Algorithme adaptatif en 3 phases** :

**Phase 1 : Couverture RIASEC (questions 1-30)**
```
Objectif : Chaque dimension RIASEC ≥ 3 réponses
Sélection : Dimension la plus sous-couverte
Calcul : Score Bayésien (theta) pour cette dimension
Question : Celle maximisant l'information (max entropy)
```

**Phase 2 : Intercalage GATB (questions 6, 12, 18, ...)**
```
Tous les 6 réponses : Injecter 1 question GATB
(Aptitudes générales : G, V, N, S)
```

**Phase 3 : Sélection adaptive (questions 30+)**
```
Dimension la plus incertaine (max variance)
Sélectionner question maximisant information mutuelle
IRT (Item Response Theory) calibration
```

**Critère d'arrêt** :
```
Arrêt précoce si :
- min(3) réponses RIASEC par dimension
- ET certitude moyenne > 0.85 (trois meilleures dimensions)
OU max_questions (50) atteint
```

**État de session** (Cache Redis) :
```php
[
  'dimensions' => [
    'R' => ['score' => 0.75, 'certainty' => 0.82, 'count' => 3],
    'I' => [...],
    ...
    'GATB_G' => ['score' => 0.68, 'certainty' => 0.60, 'count' => 2],
    ...
  ],
  'answered_ids' => [1, 3, 5, ...],
  'phase' => 1,
  'is_completed' => false,
  'alerts' => []
]
```

---

#### **GatbCalculator** (v5.0)

**GATB = Aptitudes cognitives** (4 dimensions) :

```
- G : Aptitude Générale (raisonnement global)
- V : Aptitude Verbale (compréhension vocabulaire, texte)
- N : Aptitude Numérique (calcul, logique mathématique)
- S : Aptitude Spatiale (visualisation 3D, géométrie)
```

**Mode d'évaluation** : Exercices objectifs (QCM)
```
Réponse correcte (valeur=5) → +1 point
Réponse incorrecte (valeur≠5) → 0 point
Score = (correct / total) * 100 %
```

**Compatibilité avec filière** :
```
Pour chaque aptitude :
  gap = required - student_score
  if gap ≤ 0   : ✅ OK
  if gap ≤ 10  : ⚠️ Effort mineur
  if gap ≤ 25  : ⚠️ Travail nécessaire
  if gap ≤ 40  : 🔴 Risque d'échec
  else         : ❌ Déconseillé

Scoring global :
  total_gap = 0     → EXCELLENT (1.0)
  total_gap ≤ 10    → BON (0.8)
  total_gap ≤ 25    → MOYEN (0.6)
  total_gap ≤ 40    → FRAGILE (0.4)
  else              → INSUFFISANT (0.2)
```

---

#### **BehavioralAnalyzer**

**Analyse comportementale du test** :
- Temps par question (détecte rush/hésitation)
- Patterns réponses (détecte incohérences)
- Variation dimensionnelle (détecte profil aléatoire)
- Flags fraude si :
  - Temps < 500ms pour réponses complexes
  - Variation > 50 points entre questions similaires
  - Pattern récurrent suspect

---

#### **IrtCalibrator**

**Item Response Theory** (Calibrage psychométrique) :

```
Θ (theta) = score latent de l'étudiant
p(correct) = 1 / (1 + e^(-a*(Θ-b)))

a = discrimination (0.5-2.5)
b = difficulté (-3 à +3)
```

**Sert à** :
- Sélectionner questions les plus informatiques
- Calculer certitude (entropy)
- Évaluer fiabilité score

---

#### **PostTestValidator**

**Validation post-test** :
1. Vérifier cohérence interne (Cronbach α > 0.6)
2. Détecter profils invalides
3. Vérifier progression temporelle
4. Calculer reliability score
5. Flag éventuels problèmes

---

#### **EarlyStoppingService**

**Arrêt précoce (early stopping)** :
```
Si après N questions :
  - Certitude RIASEC > seuil (0.85)
  - Dimensions classement stable (top 3 inchangés 10q)
  - Confidence score > 0.80
  
ALORS : Arrêter test, générer profil

Économie : Réduit 60+ questions → 25-30 questions
Fiabilité : Conserve validité psychométrique
```

---

## 🎮 Contrôleurs et points d'entrée

### 1. **RiasecTestController**

**Routes** :
- `GET /riasec/entry` → Afficher page démarrage
- `POST /riasec/initialize` → Créer session, reset cache
- `GET /riasec/question/{step}` → Afficher question N
- `POST /riasec/answer` → Enregistrer réponse (JSON)
- `GET /riasec/complete` → Afficher calcul en cours
- `GET /riasec/results` → Afficher résultats profil

**Logique clé** :
```php
1. Démarrage : Générer UUID session, invalider anciens tests
2. Chaque réponse : Procédé adaptatif, mise à jour état CAT
3. Early stopping : Si condition arrêt, rediriger résultats
4. Résultats : Charger ProfileRiasec + recommandations filières
```

---

### 2. **OrientationController**

**Routes** :
- `GET /student/orientation` → Listing filières (paginated 15)
- `GET /student/orientation/formation/{id}` → Détail formation

**Filtrage** :
- `?domaine=Informatique`
- `?etablissement=ENSI`
- `?recherche=Ingénieur`
- `?niveau=Master`

**Source données** : `Filiere::with('profile')` (2241 filières tunisiennes)

---

### 3. **NovaOrientationController**

**Routes** :
- `GET /student/orientation/nova` → Formulaire input
- `POST /student/orientation/nova/analyser` → Appel Gemini
- `GET /student/orientation/nova/resultat` → Afficher FG + conseil

**Flux** :
```
1. Utilisateur entre : section BAC, MG, notes matieres
2. POST → NovaOrientationService.analyzeProfile()
3. Gemini calcule FG (formule officielle)
4. Réponse JSON validée, stockée en session
5. Affichage résultat + bouton "Voir recommandations"
```

---

### 4. **OrientationPipelineController** (Unified entry point)

**Route** : `GET /student/pipeline` (Single button)

**Logique 3 étapes** :
```
Étape 1 : Vérifier score_fg dans Profile
  Si manquant → Redirect profile.edit
  Si présent → Étape 2

Étape 2 : Vérifier ProfileRiasec complété
  Si manquant → Redirect riasec.initialize
  Si < 10 questions → Invalider, restart
  Si présent → Étape 3

Étape 3 : Afficher résultats
  Redirect riasec.results avec succès
```

**Vue** : Tab bidirectionnelle (Score FG + Psycho RIASEC)

---

### 5. **StudentController**

**Dashboard étudiant** :
- Résumé profil (FG, code Holland, etc.)
- Boutons accès rapide (Pipeline, Comparateur, WhatIf)
- Historique simulations

---

### 6. **WhatIfController**

**Routes** :
- `GET /student/whatif` → Formulaire simulateur
- `POST /student/whatif/calculer` → AJAX calcul FG
- `POST /student/whatif/simuler-avance` → AJAX autres modules
- `GET /student/whatif/matieres` → AJAX matières par section
- `GET /student/whatif/historique` → Historique sauvegardes
- `DELETE /student/whatif/historique/{id}` → Supprimer

**Retour JSON** :
```php
{
  'success' => true,
  'score_fg' => 158.45,
  'niveau' => 'Bon',
  'formations' => [/* array */],
  'chancesAdmission' => 78,
  'advices' => ['Améliorer maths', ...]
}
```

---

## 💾 Base de données

### Migrations pertinentes

```
✓ 2026_05_07_213953_create_filieres_table
✓ 2026_05_07_220001_create_riasec_questions_table
✓ 2026_05_07_220003_create_riasec_profiles_table
✓ 2026_05_29_010100_create_filiere_profiles_table
✓ 2026_05_29_191800_create_student_interactions_table
✓ 2026_05_29_200000_create_taxonomy_tables (Domaine/SousDomaine/Spé/Metier)
```

### Seeders

```
FilieresSeeder → 2241 filières depuis 7 fichiers Excel
               → Domaines, GATB defaults, codes RIASEC

FiliereProfilesSeeder → Crée profils psycho cibles depuis FilieresSeeder
                      → Big Five, GATB, employabilité

PsychometricQuestionsSeeder → 74 questions RIASEC + GATB + Big Five

SiaePiTaxonomySeeder → Domaine/SousDomaine/Specialisation/Metiers
```

---

## 🔄 Flux de données

### **Pipeline complet étudiant** :

```
1. AUTHENTIFICATION
   └─→ User crée compte, identifiée

2. ÉTAPE 1 : PROFIL ACADÉMIQUE
   └─→ Profile.section_bac, .moyenne_generale, .notes_matieres
   └─→ ScoreFGService.calculer() → Profile.score_fg
   └─→ Visualisation : "Votre FG = 158.45"

3. ÉTAPE 2 : TEST RIASEC
   └─→ Session créée (UUID)
   └─→ AdaptiveTestEngine.getNextQuestion()
   └─→ Affiche 25-50 questions selon CAT
   └─→ RiasecTestController.storeAnswer()
   └─→ AnswerRiasec enregistrées
   └─→ État session mis à jour (Redis)
   └─→ Early stopping vérifié
   └─→ TestManager.calculateScores()
   └─→ ProfileRiasec créé
   └─→ Code Holland déterminé (EX: "IAS")

4. ÉTAPE 3 : RECOMMANDATIONS
   └─→ SiaepiRecommendationEngine.loadFilieres()
   └─→ Pour chaque filière :
       - Appariement RIASEC (cos similarity)
       - Check GATB compatibilité
       - Check Big Five alignment
       - Calcul score final (sigmoid)
   └─→ Top 10-20 filières recommandées
   └─→ CareerPathEngine.getCareersForDomain()
   └─→ Fiche métier détaillée par domaine
   └─→ Affichage résultats enrichis

5. EXPLORATION & SIMULATION (optionnel)
   └─→ Comparateur filières (côte à côte)
   └─→ WhatIfController (6 modules simulation)
   └─→ NovaOrientationController (conseil IA)
   └─→ Portfolio/Roadmap (projects, steps)
```

---

## 🧮 Algorithmes clés

### **1. Similarité Cosinus (RIASEC Matching)**

```python
def cosine_similarity(vec_a, vec_b):
    """
    Mesure similarité entre profil étudiant et filière.
    Range: [0, 1] où 1 = match parfait
    """
    dot_product = sum(a*b for a,b in zip(vec_a, vec_b))
    norm_a = sqrt(sum(a**2 for a in vec_a))
    norm_b = sqrt(sum(b**2 for b in vec_b))
    
    if norm_a < 1e-6 or norm_b < 1e-6:
        return 0.5  # Par défaut si vecteur nul
    
    return dot_product / (norm_a * norm_b)
```

---

### **2. Score FG (Formule Globale)**

```php
Score FG = 4*MG + coef1*M1 + coef2*M2 + ... + coef_n*Mn
Résultat: 0-200 (normalisé)

Exemple Mathématiques :
FG = 4*MG + 2*Maths + 1.5*SP + 0.5*SVT + 1*FR + 1*ANG
Avec MG=15, Maths=14, SP=13, SVT=12, FR=16, ANG=15
FG = 4*15 + 2*14 + 1.5*13 + 0.5*12 + 1*16 + 1*15
   = 60 + 28 + 19.5 + 6 + 16 + 15
   = 144.5
```

---

### **3. Score RIASEC (Somme pondérée)**

```php
For dimension D in [R, I, A, S, E, C]:
    raw_score = 0
    max_score = 0
    
    For each question Q with dimension=D:
        valeur = answer_value
        if Q.is_reverse:
            valeur = 5 - valeur  // Inversion
        
        raw_score += valeur * Q.poids
        max_score += 5 * Q.poids  // Max Likert = 5
    
    score_0_100 = (raw_score / max_score) * 100

Result: Code Holland = 3 lettres (scores décroissants)
```

---

### **4. Détection fraude (BehavioralAnalyzer)**

```php
Flags :
- temps < 500ms par question complexe
- Variance réponses > 50 points
- Pattern répétitif (EX: toujours "3")
- Coefficient variation temps > 300%
- Progression non-monotone étrange

Score fraude = weighted_flags
If score > seuil → is_flagged = true
```

---

### **5. Coefficient d'ajustement employabilité**

```
Base: score RIASEC match = 0.75
Multiplicateurs:
  × employability_index (0.5-0.95)   [Indice marché]
  × (1 - saturation_secteur/100)     [Saturation]
  × (1 + croissance_domaine/100)     [Croissance]
  × gatb_compat_score                [GATB match]

Résultat: Score 0-1 final
```

---

## 🌐 Intégrations externes

### **1. Gemini API (Nova)**

**Provider** : Google Cloud (Generative AI)

**Endpoint** : `https://generativelanguage.googleapis.com/v1/models/{model}:generateContent`

**Modèles** (avec fallback) :
```
1. gemini-2.0-flash (recommandé, rapide)
2. gemini-2.5-flash
3. gemini-1.5-flash
4. gemini-2.5-pro (backup si quota)
```

**Config** : `.env` → `GEMINI_API_KEY`

**Prompt** : Calcul FG + recommandations (JSON strict)

**Rate limiting** : 429 → Essayer modèle suivant

---

### **2. Ollama (LLM Local)**

**Modèle** : `llama3` (ou personnalisable)

**Endpoint** : `http://localhost:11434/api/chat`

**Capabilities** :
- Portfolio analysis (extraction skills)
- Roadmap generation (étapes carrière)

**Avantage** : Pas de limite API, traitement local

---

### **3. Firebase Logging**

**Service** : Logs en temps réel

**Events** :
```
- test.started
- test.completed
- recommendation.generated
- admission.predicted
- error.api
```

---

### **4. ANETI & INS Tunisia Data**

**Source** : Données publiques marché emploi tunisien (2026)

**Intégration** : Seeders chargent statiquement

**Données** :
```
- Taux employabilité par domaine
- Salaires moyens (2026 TND)
- Croissance secteurs
- Saturation marché
```

---

## 📈 Métriques et monitoring

### **KPIs principaux**

```
1. Taux complétude test (%)
2. Temps moyen test (minutes)
3. Cohérence moyenne (0-100)
4. Taux early stopping (%)
5. Taux utilisateurs satisfaits (feedback)
6. Accuracy prédictions vs inscriptions réelles (post-hoc)
```

---

## 🔐 Sécurité & Validation

```php
1. Authentification : Middleware 'auth', 'two-factor'
2. Validation input : Request classes (StoreRiasecAnswerRequest, etc.)
3. Throttling : 'throttle:10,1' sur endpoints sensibles
4. Rate limiting : Globalement appliqué
5. CORS : Configuration stricte pour API
6. Logging : Tous erreurs loggées (Log::error)
7. Fraude detection : BehavioralAnalyzer + flags
```

---

## 📚 Fichiers clés par fonctionnalité

| Fonctionnalité | Fichiers |
|---|---|
| **Test RIASEC** | `RiasecTestController`, `TestManager`, `AdaptiveTestEngine`, `ProfileRiasec`, `QuestionRiasec`, `AnswerRiasec` |
| **Score FG** | `ScoreFGService`, `NovaOrientationController` |
| **Recommandations** | `SiaepiRecommendationEngine`, `FiliereProfile`, `Filiere` |
| **Carrière** | `CareerPathEngine`, `Metier`, `Specialisation` |
| **Simulation** | `FutureSimulatorService`, `WhatIfController` |
| **IA Générative** | `NovaOrientationService`, `OllamaService` |
| **Taxonomie** | `Domaine`, `SousDomaine`, `Specialisation`, `Metier` |

---

## 🚀 Points d'extensibilité

1. **Modèles ML** : Remplacer `AdmissionPredictorService.predictAdmissionChances()` par vrai ML (sklearn, TensorFlow)
2. **More LLMs** : Ajouter Claude, GPT-4 aux fallbacks
3. **Intégrations marché** : API ANETI temps réel, données LinkedIn
4. **Mobilité internationale** : Étendre à filières pays voisins (France, Canada, etc.)
5. **Gamification** : Badges, achievements, rankings
6. **Peer comparison** : Comparer son profil à autres utilisateurs
7. **Tutoring** : Recommendation tuteurs, ressources apprentissage

---

**Document généré** : May 30, 2026
**Version SIAEPI** : v8.0 Production-Grade Stable
