# 📚 SIAEPI v8.0 - Index Documentation Complète

**Document généré** : May 30, 2026  
**Système** : SIAEPI v8.0 - Production-Grade Stable  
**Contexte** : Orientation universitaire intelligente en Tunisie

---

## 📖 Documents disponibles

### 1. **AI_EXECUTIVE_SUMMARY.md** ⭐ START HERE
**2-3 minutes de lecture**

Vue générale complète du système :
- Architecture 3 étapes (Pipeline unifié)
- Composants principaux (Services, Modèles, Contrôleurs)
- Algorithmes clés (résumés)
- Données tunisiennes intégrées
- Flux utilisateur complet
- Capacités IA
- KPIs et points forts

**Meilleur pour** : Vue d'ensemble rapide, présentation client, décisions architecturales

---

### 2. **AI_ARCHITECTURE_COMPLETE.md** 🏗️ COMPREHENSIVE
**30-40 minutes de lecture**

Documentation technique exhaustive :
- Vue d'ensemble (Contexte SIAEPI)
- Architecture générale (Diagrammes, couches)
- **Modèles de données** :
  - User & Profile
  - RIASEC Questions, Answers, Profiles
  - Filière & FiliereProfile (2241 records)
  - StudentInteraction, Recommendation
  - SimulationHistory
  - Taxonomie (Domaine, SousDomaine, Specialisation, Metier)
  
- **Services et moteurs IA** (8 services détaillés) :
  1. SiaepiRecommendationEngine (Appariement psycho)
  2. CareerPathEngine (8 domaines + métiers)
  3. ScoreFGService (Formules BAC tunisien)
  4. AdmissionPredictorService (Chances admission MVP)
  5. FutureSimulatorService (6 modules What-If)
  6. NovaOrientationService (Gemini API)
  7. OllamaService (LLM local)
  8. RIASEC Services (TestManager, AdaptiveTestEngine, GatbCalculator, etc.)
  
- **Contrôleurs et points d'entrée** (6 contrôleurs clés)
- **Base de données** (Migrations, Seeders)
- **Flux de données** (Pipeline étudiant complet)
- **Algorithmes clés** (Résumés avec exemples)
- **Intégrations externes** (Gemini, Ollama, Firebase, ANETI)

**Meilleur pour** : Compréhension architecturale complète, développement, troubleshooting

---

### 3. **AI_DATA_MODEL.json** 📊 SCHEMA REFERENCE
**15 minutes pour parcourir**

Schéma JSON complet des 20+ tables :
- `users` → Authentification
- `profiles` → Profil académique étudiant
- `riasec_questions` → Banque 74 questions
- `riasec_answers` → Réponses individuelles
- `riasec_profiles` → Résultats tests
- `filieres` → 2241 formations
- `filiere_profiles` → Profils cibles
- `student_interactions` → Tracking
- `recommendations` → Suggestions
- `recommendation_feedbacks` → Feedback utilisateur
- `simulation_history` → Historique What-If
- `domaines` → Taxonomie niveau 1
- `sous_domaines` → Niveau 2
- `specialisations` → Niveau 3
- `metiers` → Débouchés professionnels

Pour chaque table :
- ✅ Colonnes (type, range, constraints)
- ✅ Indexes
- ✅ Relationships
- ✅ Cache structures
- ✅ Statistics

**Meilleur pour** : Requêtes DB, migrations, ORM modeling, schéma queries

---

### 4. **AI_ALGORITHMS.md** 🧮 MATHEMATICAL DETAIL
**20-30 minutes de lecture**

Détail complet des 10 algorithmes principaux :

1. **Score FG** (7 formules BAC)
2. **Scores RIASEC** (Calcul + Code Holland)
3. **Similarité Cosinus** (Matching psycho)
4. **Score Recommandation** (9+ critères pondérés)
5. **Moteur Adaptatif** (CAT 3 phases + IRT)
6. **Compatibilité GATB** (Évaluation aptitudes)
7. **Détection fraude** (Behavioral analysis)
8. **Early Stopping** (Conditions arrêt précoce)
9. **Cohérence interne** (Cronbach Alpha)
10. **Prédiction admission** (MVP + futures améliorations)

Pour chaque algorithme :
- ✅ Formule mathématique
- ✅ Exemple de calcul numérique
- ✅ Implémentation en pseudo-code
- ✅ Résultats et interprétation

Tableau récapitulatif des formules clés.

**Meilleur pour** : Validation algorithmes, optimisation, debugging numérique, publications

---

### 5. **AI_ROUTES_FLOWS.md** 🌍 ENDPOINTS & WORKFLOWS
**15-20 minutes de lecture**

Routes, endpoints, et flux utilisateur détaillés :

**Routes principales** :
- `/student/pipeline` → Unified entry point
- `/student/orientation/*` → Filières & Nova
- `/riasec/*` → Test complet
- `/student/whatif/*` → 6 modules simulation
- `/student/comparateur/*` → Comparaison côte-à-côte

**Flux détaillés** :
1. **Pipeline complet** (8 étapes) → Orientation de A à Z
2. **Calcul FG via Nova** (Gemini) → Analyse IA
3. **Simulation What-If** (6 modules) → Scénarios
4. **API Endpoints** → Pour intégrations mobiles/tierces
5. **State Management** → Session & Cache
6. **Middleware & Guards** → Authentification & autorisation
7. **Dashboard** → Vue étudiant
8. **Analytics Events** → Logging usage
9. **Exemple complet d'utilisation** → User journey

**Meilleur pour** : Intégrations frontend, API design, user flows, mobile app

---

## 🎯 Guides par cas d'usage

### Je veux **comprendre** l'architecture globale
```
1. Lire : AI_EXECUTIVE_SUMMARY.md (5 min)
2. Regarder : Diagramme architecture (AI_ARCHITECTURE_COMPLETE.md)
3. Consulter : Schéma données (AI_DATA_MODEL.json)
```

### Je veux **développer** une nouvelle fonctionnalité
```
1. Lire : AI_ARCHITECTURE_COMPLETE.md (section pertinente)
2. Vérifier : Modèles existants (AI_DATA_MODEL.json)
3. Examiner : Service correspondant
4. Tester : Avec les données seeders existantes
```

### Je veux **déboguer** un algorithme
```
1. Lire : AI_ALGORITHMS.md (algorithme concerné)
2. Vérifier : Formule mathématique
3. Tracer : Avec exemples numériques
4. Comparer : Avec logs applicatifs (Firebase)
```

### Je veux **intégrer** l'API dans une app mobile
```
1. Consulter : AI_ROUTES_FLOWS.md (section API)
2. Vérifier : Endpoints détaillés
3. Implémenter : État management local
4. Tester : Avec données mock
```

### Je veux **optimiser** les performances
```
1. Consulter : Cache structures (AI_DATA_MODEL.json)
2. Vérifier : Indexes DB
3. Mesurer : Temps exécution (AI_ARCHITECTURE_COMPLETE.md)
4. Profiler : Avec Redis Profiler
```

### Je veux **améliorer** les recommandations
```
1. Lire : SiaepiRecommendationEngine (AI_ARCHITECTURE_COMPLETE.md)
2. Étudier : Formule scoring (AI_ALGORITHMS.md)
3. Tester : Module 6 What-If (AI_ROUTES_FLOWS.md)
4. Valider : Contre feedback utilisateur
```

---

## 🔍 Recherche rapide

### Par thème

| Thème | Document | Sections |
|-------|----------|----------|
| **Modèles données** | AI_DATA_MODEL.json | Tables, relationships |
| **Services** | AI_ARCHITECTURE_COMPLETE.md | Services & moteurs IA |
| **Algorithmes** | AI_ALGORITHMS.md | 10 algorithmes |
| **Contrôleurs** | AI_ARCHITECTURE_COMPLETE.md | Contrôleurs |
| **Routes** | AI_ROUTES_FLOWS.md | Routes principales |
| **Flux utilisateur** | AI_ROUTES_FLOWS.md | Workflows |
| **Psychométrie** | AI_ARCHITECTURE_COMPLETE.md | RIASEC Services |
| **Filières tunisiennes** | AI_ARCHITECTURE_COMPLETE.md | CareerPathEngine |
| **Intégrations IA** | AI_ARCHITECTURE_COMPLETE.md | Nova, Ollama |
| **Cache** | AI_DATA_MODEL.json | Cache structures |

### Par technologie

| Tech | Document | Sections |
|------|----------|----------|
| **Laravel** | AI_ARCHITECTURE_COMPLETE.md | Contrôleurs, Routes |
| **PHP** | AI_ALGORITHMS.md | Pseudo-code |
| **PostgreSQL** | AI_DATA_MODEL.json | Schéma complète |
| **Redis** | AI_DATA_MODEL.json | Cache structures |
| **Gemini API** | AI_ARCHITECTURE_COMPLETE.md | NovaOrientationService |
| **Ollama** | AI_ARCHITECTURE_COMPLETE.md | OllamaService |
| **IRT/CAT** | AI_ALGORITHMS.md | Moteur adaptatif |
| **JSON** | AI_DATA_MODEL.json | Schema |

---

## 📊 Statistiques clés

```
Filières universitaires tunisiennes    : 2241
Questions psychométriques (banque)     : 74
  - RIASEC pures                       : 18
  - Big Five                           : 10
  - GATB (aptitudes)                   : 8
  - Résilience                         : 6
  - Autres domaines                    : ~32

Questions par test (adaptatif)         : 25-50
Durée test moyen                       : 25 minutes
Early stop rate                        : ~30%
Fiabilité (Cronbach α)                 : 0.78-0.90

Domaines couverts                      : 8
  (Informatique, Santé, Technique, Sciences, 
   Économie, Lettres, Social, Arts)

Services IA principaux                 : 8
Contrôleurs clés                       : 6
Modèles majeurs                        : 20+
```

---

## 🚀 Quick Navigation

**Besoin rapide ?**

- Pour UML/Diagramme → AI_ARCHITECTURE_COMPLETE.md (début)
- Pour Modèles DB → AI_DATA_MODEL.json
- Pour Formules math → AI_ALGORITHMS.md
- Pour Routes API → AI_ROUTES_FLOWS.md (section API)
- Pour vue 30 sec → AI_EXECUTIVE_SUMMARY.md

**En doute sur la structure ?**

→ Consulter : **AI_DATA_MODEL.json** (source de vérité des données)

**Besoin de détails techniques ?**

→ Consulter : **AI_ARCHITECTURE_COMPLETE.md** (documentation exhaustive)

---

## 📝 Historique

| Version | Date | Changements |
|---------|------|------------|
| v8.0 | 2026-05-30 | Production-grade stable, documentation complète |

---

## 🔗 Fichiers associés (code source)

```
app/Services/
  ├─ SiaepiRecommendationEngine.php
  ├─ CareerPathEngine.php
  ├─ ScoreFGService.php
  ├─ AdmissionPredictorService.php
  ├─ FutureSimulatorService.php
  ├─ NovaOrientationService.php
  ├─ OllamaService.php
  └─ RIASEC/
     ├─ TestManager.php
     ├─ AdaptiveTestEngine.php
     ├─ GatbCalculator.php
     ├─ BehavioralAnalyzer.php
     ├─ IrtCalibrator.php
     ├─ PostTestValidator.php
     └─ EarlyStoppingService.php

app/Models/
  ├─ Filiere.php
  ├─ FiliereProfile.php
  ├─ ProfileRiasec.php
  ├─ QuestionRiasec.php
  ├─ AnswerRiasec.php
  ├─ StudentInteraction.php
  ├─ Recommendation.php
  ├─ Domaine.php
  ├─ SousDomaine.php
  ├─ Specialisation.php
  └─ Metier.php

app/Http/Controllers/
  ├─ RiasecTestController.php
  ├─ OrientationController.php
  ├─ NovaOrientationController.php
  ├─ OrientationPipelineController.php
  ├─ WhatIfController.php
  └─ Student/...

database/
  ├─ migrations/
  ├─ seeders/
  │  ├─ FilieresSeeder.php
  │  ├─ FiliereProfilesSeeder.php
  │  └─ ...
  └─ ...

routes/
  └─ web.php (routes définies)
```

---

## ✅ Checklist de lecture

- [ ] AI_EXECUTIVE_SUMMARY.md (5 min) - Vue générale
- [ ] Diagramme architecture (AI_ARCHITECTURE_COMPLETE.md) (2 min)
- [ ] AI_DATA_MODEL.json (10 min) - Modèles clés
- [ ] Services IA (AI_ARCHITECTURE_COMPLETE.md) (15 min)
- [ ] Algorithmes (AI_ALGORITHMS.md) (20 min) - Détails math
- [ ] Routes & Flows (AI_ROUTES_FLOWS.md) (15 min) - Endpoints
- [ ] Vérifier code source (services + controllers) (20 min)

**Total** : ~90 minutes pour maîtrise complète

---

**Documentation SIAEPI v8.0**  
**Contexte** : Orientation universitaire intelligente tunisienne  
**Dernière mise à jour** : 2026-05-30

---

## 📞 Support

Pour questions/clarifications, consulter :
1. Le service/modèle correspondant (code source)
2. Logs applicatifs (Firebase/Sentry)
3. Tests unitaires (tests/Unit/SiaepiRecommendationEngineTest.php)

