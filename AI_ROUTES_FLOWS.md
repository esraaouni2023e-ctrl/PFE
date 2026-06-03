# 🌍 Points d'entrée & Flux utilisation - SIAEPI v8.0

## 📍 Routes principales

### Vue étudiant - Pipeline unifié

```
GET  /student/pipeline
     │
     ├─→ Étape 1 : Profil académique
     │   └─ POST /student/pipeline/step1 → Enregistre section/MG
     │   └─ ScoreFGService.calculer() → FG calculé
     │
     ├─→ Étape 2 : Test RIASEC
     │   └─ GET  /riasec/question/{step} → Affiche Q adaptée
     │   └─ POST /riasec/answer → Enregistre réponse
     │   └─ AdaptiveTestEngine.processAnswer() → MAJ état CAT
     │
     └─→ Étape 3 : Recommandations & résultats
         └─ GET  /riasec/results → Affiche profil + filières
         └─ SiaepiRecommendationEngine → Top 10 filières
         └─ CareerPathEngine → Fiches métier
```

### Routes détaillées par fonctionnalité

#### **1. Orientation & Filières**

```
GET  /student/orientation
     Affiche liste 2241 filières (paginated 15)
     Filtres : ?domaine=, ?etablissement=, ?recherche=, ?niveau=
     
GET  /student/orientation/formation/{id}
     Détail 1 formation (JSON)
     
GET  /student/orientation/nova
     Formulaire d'analyse nova (Gemini)
     
POST /student/orientation/nova/analyser
     Appel NovaOrientationService
     Calcul FG via IA
     
GET  /student/orientation/nova/resultat
     Affiche score FG + conseil
```

#### **2. Test RIASEC**

```
GET  /riasec/entry
     Page démarrage test
     
POST /riasec/initialize
     Crée session UUID
     Reset cache ancien test
     
GET  /riasec/question/{step}
     Affiche question N (adaptatif)
     Progress bar
     
POST /riasec/answer
     Enregistre réponse (JSON)
     {
       question_id: int,
       valeur: int (1-5),
       temps_ms: int,
       riasecSessionId: uuid
     }
     Retour : { success, completed, progress, redirect }
     
GET  /riasec/complete
     Page attente (calcul résultats)
     
GET  /riasec/results
     Affiche ProfileRiasec complet
     Code Holland
     Interprétation enrichie
     Top filières recommandées
```

#### **3. Simulateur What-If**

```
GET  /student/whatif
     Page formulaire (6 modules)
     
POST /student/whatif/calculer
     Module 1 : Variation notes
     Input  : section, mg, notes
     Output : score_fg, formations, chances
     
POST /student/whatif/simuler-avance
     Modules 2-6 : Spécialité, filière alt, secteur, salaires, compat
     
GET  /student/whatif/matieres
     Retour matières par section (AJAX)
     
GET  /student/whatif/historique
     Liste simulations sauvegardées
     
DELETE /student/whatif/historique/{id}
     Supprimer 1 simulation
```

#### **4. Comparateur**

```
GET  /student/comparateur
     Page comparateur côte à côte
     
POST /student/comparateur/data
     Récupère données 2+ filières
     Affiche tableau comparatif
     
GET  /student/comparateur/search
     Recherche filières (AJAX)
```

---

## 🔄 Flux détaillés

### **Flux 1 : Orientation complète (Pipeline)**

```
1. AUTHENTIFICATION
   └─ User (student) authentifié
   └─ Profile créé

2. ENTRÉE PIPELINE
   └─ GET /student/pipeline
   └─ Détecte étape courante
   
3. ÉTAPE 1 : PROFIL ACADÉMIQUE
   └─ Affiche formulaire (section, MG, notes)
   └─ POST /student/pipeline/step1
   └─ Profile.section_bac = X
   └─ Profile.moyenne_generale = Y
   └─ Profile.notes_matieres = JSON
   └─ ScoreFGService.calculer()
   └─ Profile.score_fg = Z
   └─ Session : ['profile_score_fg' => Z]
   
4. ÉTAPE 2 : TEST RIASEC
   └─ POST /riasec/initialize
   └─ Créer session UUID
   └─ AdaptiveTestEngine.getSessionState()
   └─ Session : ['riasec_session_id' => UUID]
   
5. QUESTIONS ADAPTATIVES
   Boucle :
     └─ GET /riasec/question/{step}
     └─ AdaptiveTestEngine.getNextQuestion()
     └─ Afficher Q (CAT phase 1/2/3)
     └─ POST /riasec/answer
     └─ AnswerRiasec enregistrée
     └─ TestManager.saveAnswer()
     └─ AdaptiveTestEngine.processAnswer()
     └─ State CAT mis à jour (Redis)
     └─ Vérifier early stopping
     └─ Si completed : break
   
6. CALCUL RÉSULTATS
   └─ GET /riasec/complete (page attente)
   └─ TestManager.calculateScores()
   └─ ProfileRiasec créé
   └─ Code Holland déterminé
   └─ Score cohérence calculé
   └─ Interprétation générée
   
7. AFFICHAGE RÉSULTATS
   └─ GET /riasec/results
   └─ ProfileRiasec + Code Holland affiché
   └─ SiaepiRecommendationEngine.loadFilieres()
   └─ Pour chaque filière :
       - Appariement RIASEC
       - Check GATB
       - Scoring final
   └─ Top 10 filières affichées
   
8. FICHES MÉTIER
   └─ CareerPathEngine.getCareersForDomain()
   └─ Affiche 2-3 métiers par domaine
   └─ Secteurs, compétences, salaires
```

### **Flux 2 : Calcul FG via Nova (Gemini)**

```
1. GET /student/orientation/nova
   └─ Afficher formulaire

2. POST /student/orientation/nova/analyser
   Données :
   {
     section_bac: "Mathématiques",
     mg: 15,
     notes: {
       math: 14,
       sp: 13,
       svt: 12,
       fr: 16,
       ang: 15
     }
   }

3. NovaOrientationService.analyzeProfile()
   └─ Construire prompt avec données
   └─ Appel Gemini API
   └─ Retry si quota (429)
   └─ Fallback autres modèles si erreur
   └─ Parser réponse JSON
   
4. Validation réponse
   └─ Vérifie score_fg ∈ [0, 200]
   └─ Stocke en session
   
5. GET /student/orientation/nova/resultat
   └─ Affiche FG calculé
   └─ Bouton "Voir recommandations"
   
6. Peut rediriger vers /student/orientation
   └─ Voir filières correspondant au FG
```

### **Flux 3 : Simulation What-If (6 modules)**

```
MODULE 1 : VARIATION NOTES
│
├─ Input  : Section, MG, notes_matieres
├─ Appel  : ScoreFGService.calculer()
├─ Output : {
│    score_fg: X,
│    niveau: "Bon",
│    formations: [...],
│    chances_admission: 78
│  }
├─ Display : Tableau filières accessibles
└─ Save   : SimulationHistory enregistrée

MODULE 2 : CHANGEMENT SPÉCIALITÉ
│
├─ Input  : Section actuelle, score, nouvelle section
├─ Appel  : FutureSimulatorService.simulerChangementSpecialite()
├─ Calcul : Score nouvel BAC, delta
├─ Compare: Nb formations accessibles avant/après
├─ Output : { delta: +5.2, verdict: "favorable" }
└─ Display : Comparaison avant/après

MODULE 3 : FILIÈRE ALTERNATIVE
│
├─ Input  : Filière(s) préférée(s)
├─ Appel  : FutureSimulatorService.simulerFiliereAlternative()
├─ Calcul : Similarité avec autres filières
├─ Output : Top alternatives (scoring)
└─ Display : Similarité score avec chaque alternative

MODULE 4 : SECTEUR & EMPLOYABILITÉ
│
├─ Data   : 10 secteurs (IT, Santé, Ingénierie, etc.)
├─ Pour chaque secteur :
│   - Taux insertion
│   - Saturation marché
│   - Croissance
│   - Salaire moyen
├─ Appel  : FutureSimulatorService.getSecteursEmployabilite()
└─ Display : Heatmap secteurs vs profil

MODULE 5 : SALAIRES & ROI
│
├─ Input  : Filière sélectionnée, niveau
├─ Data   : Grille salariale tunisienne 2026
├─ Calcul : Salaire entrée, évolution 5/10 ans
├─ ROI    : Coûts études vs gains
├─ Output : Courbe salaire/temps
└─ Display : Prévisions financières

MODULE 6 : COMPATIBILITÉ CARRIÈRE
│
├─ Input  : Code Holland étudiant, domaine
├─ Map    : RIASEC → domaines professionnels
├─ Calcul : Taux compatibilité (0-100)
├─ Output : Domaines triés par compat
└─ Display : Radar de compatibilité
```

---

## 📱 Endpoints API

### **Authentication**

```
POST /login
  Authenticate user
  
POST /logout
  Logout

POST /register
  Create new student account
  
POST /password/forgot
  Request password reset
```

### **Student Resources**

```
GET  /api/student/profile
     Récupère profil étudiant + score FG

PUT  /api/student/profile
     Met à jour profil

GET  /api/student/riasec-results
     Récupère dernier ProfileRiasec

GET  /api/student/recommendations
     Liste recommandations personnalisées

GET  /api/filieres
     Filières (querystring filtering)
     ?domaine=IT&page=2

GET  /api/filieres/{code}
     Détail 1 filière

GET  /api/metiers/{domaine}
     Métiers d'un domaine
```

### **Test RIASEC**

```
GET  /api/riasec/session
     Créer/récupérer session test
     
POST /api/riasec/answer
     Enregistrer réponse
     
GET  /api/riasec/progress
     Progression test
     
GET  /api/riasec/results
     Résultats finaux
```

### **Simulator**

```
POST /api/simulator/calculate-fg
     Calcule FG avec variations notes
     
POST /api/simulator/compare-specialties
     Compare 2 spécialités BAC
     
POST /api/simulator/alternative-filieres
     Filières alternatives
```

---

## 🗺️ State Management

### **Session (PHP)**

```php
session([
    'riasec_session_id'    => UUID,
    'riasec_started_at'    => ISO8601,
    'riasec_current_step'  => int,
    'riasec_profile_id'    => int,
    'riasec_stopped_early' => bool,
    'riasec_confidence'    => float,
    'nova_result'          => array,
]);
```

### **Cache (Redis)**

```
cat_state_{sessionId}
├─ dimensions
│  ├─ R: { score, certainty, count }
│  ├─ I: { ... }
│  ├─ A, S, E, C, GATB_*, B5_*, ...
├─ answered_ids : []
├─ phase : int
├─ is_completed : bool
└─ alerts : []

riasec.questions.grouped
└─ Grouped by categorie

riasec.scores.{sessionId}
└─ DTO avec tous scores
```

---

## 🔐 Middleware & Guards

```
Route::middleware(['auth', 'two-factor'])->group(function() {
    // Routes étudiant autentifiés
    
    Route::middleware('role:student')->group(function() {
        // Accès étudiant seulement
        // /student/orientation
        // /student/whatif
        // /riasec/*
    });
    
    Route::middleware('role:counselor')->group(function() {
        // Accès conseillers
    });
    
    Route::middleware('role:admin')->group(function() {
        // Accès admin
    });
});
```

---

## 📊 Dashboard

### **Student Dashboard**

```
GET /student
│
├─ Profile card
│  ├─ Section BAC
│  ├─ MG
│  └─ Score FG (si calculé)
│
├─ Quick actions
│  ├─ Button "Start/Continue Pipeline"
│  ├─ Button "Nova Orientation"
│  ├─ Button "What-If Simulator"
│  └─ Button "Comparateur"
│
├─ Recent results
│  ├─ Code Holland (si test terminé)
│  ├─ Top 5 recommandations
│  └─ Historique simulations
│
└─ Recommended next step
   └─ Dynamique selon progression
```

---

## 🚀 Performance & Caching

```
Opération                  | Durée    | Cache
---------------------------|----------|----------
Charger 2241 filières      | 200ms    | 1h (Redis)
Calc 1 FG                  | 30ms     | N/A
Similarité 1 filière       | 5ms      | N/A
Recommandations top 10     | 500ms    | 30min
Test RIASEC (50Q)          | 25 min   | N/A
Charger questions RIASEC   | 50ms     | 1h

Optimisations :
├─ Filières chargées une seule fois
├─ Profiles en cache Redis
├─ Questions groupées par catégorie
├─ Lazy loading filieres (pagination)
└─ Index DB sur (user_id, statut, test_session_id)
```

---

## 📈 Analytics Events

```
Event               | Trigger         | Data
--------------------|-----------------|------------
test.started        | POST /initialize | session_id
test.answered       | POST /answer     | q_id, time_ms
test.completed      | GET /results     | code, scores
test.flagged        | Validation       | flags
recommendation.gen  | CAT complete     | top_10
user.simulated      | POST /simuler    | module, params
```

---

## 🔄 Exemple complet d'utilisation

```
1. User accède app
   └─ Authentifié → /dashboard

2. Clique "Commencer orientation"
   └─ GET /student/pipeline
   └─ Détecte : pas de FG
   └─ Affiche formulaire étape 1

3. Entre section + notes
   └─ POST /student/pipeline/step1
   └─ FG calculé (2 sec)
   └─ Stocké en DB

4. Continuación du pipeline
   └─ GET /student/pipeline
   └─ Détecte : FG OK, pas de test RIASEC
   └─ Redirige vers test

5. Teste RIASEC
   └─ POST /riasec/initialize (crée session)
   └─ Boucle 25-50 questions adaptatif
   └─ POST /riasec/answer × N
   └─ Early stopping détecté
   └─ Test complété (15 min)

6. Résultats
   └─ GET /riasec/results
   └─ Code Holland: "IAS"
   └─ Scores: I=78, A=70, S=55, R=45, E=42, C=38
   └─ Top 10 filières affichées
   └─ Fiches carrière disponibles

7. Exploration optionnelle
   └─ Comparateur : 2 filières côte à côte
   └─ What-If : teste variation notes
   └─ Nova : conseil IA supplémentaire
```

---

**Documentation routes et flux**
**SIAEPI v8.0 - 2026**
