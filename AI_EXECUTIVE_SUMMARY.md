# 🎯 SIAEPI v8.0 - Résumé Exécutif

## Vue générale en une page

**SIAEPI** = Système Intelligent d'Assistance à l'Évaluation et à l'Orientation Professionnelle

### Architecture 3 étapes

```
┌──────────────────┐     ┌──────────────────┐     ┌──────────────────┐
│   PROFIL ACAD.   │────→│   TEST RIASEC    │────→│ RECOMMANDATIONS  │
│   Score FG       │     │   25-50 Q        │     │   Top 10 Filières│
│   Calcul 30s     │     │   Adaptatif      │     │   Métiers        │
└──────────────────┘     └──────────────────┘     └──────────────────┘
```

---

## Composants principaux

### 1️⃣ **Services IA**

| Service | Rôle | Technologie |
|---------|------|-------------|
| **SiaepiRecommendationEngine** | Appariement filière-étudiant | Cosinus + Sigmoid |
| **TestManager** | Gestion tests RIASEC | Sommes pondérées |
| **AdaptiveTestEngine** | CAT bayésien | IRT + Item Selection |
| **ScoreFGService** | Calcul FG BAC | Formules officielles |
| **NovaOrientationService** | Conseil IA | Gemini API |
| **FutureSimulatorService** | Scénarios What-If | Simulations |

### 2️⃣ **Modèles de données**

| Modèle | Rôle | Enregistrements |
|--------|------|-----------------|
| **Filiere** | Catalogue universités | 2241 |
| **FiliereProfile** | Profils psycho cibles | 2241 |
| **QuestionRiasec** | Banque questions | 74 |
| **ProfileRiasec** | Résultat test étudiant | N utilisateurs |
| **AnswerRiasec** | Réponses individuelles | N×50 avg |
| **Recommendation** | Suggestions personnalisées | N |

### 3️⃣ **Contrôleurs**

| Route | Étape | Action |
|-------|-------|--------|
| `/student/pipeline` | Unified entry | Redirige selon progression |
| `/student/orientation/nova` | FG calc | Formule officielle via Gemini |
| `/riasec/question/{step}` | Test | Affiche question adaptée |
| `/student/orientation` | Browse | Liste filières (2241) |
| `/student/whatif` | Simulation | 6 modules scénarios |

---

## 🧠 Algorithmes clés

### **Recommandation (SIAEPI)**

```
score_filiere = cosine_sim(RIASEC_etudiant, RIASEC_filiere)
              × employability_index
              × gatb_compatibility
              × big_five_alignment
              × (1 - market_saturation)
              
TOP 10 : Classement score décroissant
```

### **Score FG**

```
Mathématiques  : FG = 4*MG + 2*M + 1.5*SP + 0.5*SVT + 1*FR + 1*ANG
Sciences exp.  : FG = 4*MG + 1*M + 1.5*SP + 1.5*SVT + 1*FR + 1*ANG
Économie       : FG = 4*MG + 1.5*EC + 1.5*GE + 0.5*M + 0.5*HG + 1*FR + 1*ANG
(... 7 sections totales)

Résultat : 0-200
```

### **Test RIASEC adaptatif**

```
Phase 1 (Q1-30)  : Couverture obligatoire (3 Q par dim RIASEC)
Phase 2 (Q6, 12) : Intercalage GATB tous les 6
Phase 3 (Q30+)   : Sélection adaptive (max incertitude)

Arrêt : min 3 Q/dim ET certainty > 0.85 OU max 50 Q
Résultat : Code Holland (3 lettres) + scores 0-100
```

---

## 📊 Données tunisiennes intégrées

- **2241 filières** (Universités, Établissements)
- **8 domaines** (Informatique, Santé, Technique, Sciences, Économie, Lettres, Social, Arts)
- **7 sections BAC** (Maths, Sci exp, Éco, Tech, Info, Lettres, Sport)
- **SDO 2023-2025** (Scores admission)
- **Salaires 2026** (TND/mois par domaine)
- **Taux employabilité** (ANETI / INS)

---

## 🔄 Flux utilisateur complet

```
1. Inscription
   └─ Section BAC, MG (0-20), notes matières

2. Score FG
   └─ Calcul automatic (ScoreFGService)
   └─ Display: "Votre FG = X.XX / 200"

3. Test RIASEC
   └─ Start session (UUID)
   └─ Adaptive: 25-50 questions (CAT)
   └─ Result: Code Holland + scores

4. Recommandations
   └─ SiaepiRecommendationEngine
   └─ Top 10 filières
   └─ Fiche carrière par domaine

5. Exploration (optionnelle)
   └─ Comparateur côte à côte
   └─ What-If (6 modules)
   └─ Chatbot (Ollama/Nova)
```

---

## 🚀 Capacités IA

### **1. Psychométrie adaptée**
- Test Holland RIASEC (6 dimensions)
- GATB (4 aptitudes cognitives)
- Big Five (5 traits comportement)
- Résilience & Attention

### **2. Recommandation contextuelle**
- Appariement 9+ variables
- Pondération domaine-spécifique
- Scoring employabilité marché

### **3. Génération contenu (LLM)**
- Calcul FG (Gemini API)
- Analyse portfolio (Ollama)
- Roadmap carrière (Ollama)
- Conseils personnalisés

### **4. Simulation What-If**
- Variation notes
- Changement section BAC
- Alternativité filières
- Secteur & employabilité
- Salaires & ROI
- Compatibilité carrière

---

## 📈 Performance

| Métrique | Valeur | Note |
|----------|--------|------|
| Test duration | 15-40 min | Adaptatif |
| Questions | 25-50 | CAT optimization |
| Fiabilité | ~0.80 | Cronbach α |
| Early stop rate | ~30% | Haute confiance |
| Processing time | <1s | Recommandations |
| Filières chargées | 2241 | Temps réel |

---

## 🔐 Qualité assurance

✅ Validation psychométrique (IRT calibration)
✅ Détection fraude (BehavioralAnalyzer)
✅ Cohérence interne (score_coherence)
✅ Tests unitaires RIASEC
✅ Logging exhaustif (Sentry/Firebase)
✅ Error handling complet

---

## 📚 Documentation technique

- **AI_ARCHITECTURE_COMPLETE.md** → Détail complet (25+ KB)
- **AI_DATA_MODEL.json** → Schéma données
- **API_ENDPOINTS.md** → Tous endpoints
- **ALGORITHMS.md** → Détail math

---

## 🎓 Contexte éducatif tunisien

**SIAEPI est spécifiquement calibré pour** :
- Systèmes éducatifs tunisiens
- Marchés emploi tunisiens
- Critères admission (SDO tunisien)
- Salaires & secteurs tunisiens (2026)
- Conseils culturel-contextuels

**Institutions partenaires** : ANETI, INS, Universités tunisiennes

---

## ⚡ Points forts

1. **Entièrement adaptatif** : Test s'ajuste au profil en temps réel
2. **Mathématiquement rigoureux** : Formules BAC officielles + IRT
3. **Données richement contextualisées** : 2241 filières + mercato
4. **Recommandations nuancées** : 9+ critères pondérés
5. **Totalement transparent** : Étudiant voit tous calculs
6. **Extensible** : Architecture modulaire services

---

## 🚧 Points d'amélioration possibles

1. Mobile app (actuellement web only)
2. API client-side (calculs côté navigateur)
3. ML admission prediction (classification réelle)
4. Real-time ANETI API
5. International expansion
6. Gamification (badges, rankings)
7. Peer comparison (anonyme)
8. Video coaching (métiers)
9. Job matching intégré (LinkedIn/Indeed)
10. Alumni success tracking

---

**SIAEPI v8.0** — Production-ready, contexte Tunisie, 2026
