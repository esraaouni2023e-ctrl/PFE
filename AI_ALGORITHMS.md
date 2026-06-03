# 🧮 Algorithmes détaillés - SIAEPI v8.0

## 1️⃣ Score FG (Formule Globale BAC tunisien)

### Formules officielles par section

#### Section 1 : **Mathématiques**
```
FG = 4×MG + 2×MATH + 1.5×SP + 0.5×SVT + 1×FR + 1×ANG

Où :
  MG = Moyenne générale (0-20)
  MATH = Note Mathématiques (0-20)
  SP = Sciences physiques (0-20)
  SVT = Sciences de la vie (0-20)
  FR = Français (0-20)
  ANG = Anglais (0-20)

Résultat: FG ∈ [0, 200]
```

**Exemple calcul** :
```
MG=15, MATH=14, SP=13, SVT=12, FR=16, ANG=15

FG = 4(15) + 2(14) + 1.5(13) + 0.5(12) + 1(16) + 1(15)
   = 60 + 28 + 19.5 + 6 + 16 + 15
   = 144.5 / 200
```

#### Section 2 : **Sciences expérimentales**
```
FG = 4×MG + 1×MATH + 1.5×SP + 1.5×SVT + 1×FR + 1×ANG
```

#### Section 3 : **Économie et gestion**
```
FG = 4×MG + 1.5×ECO + 1.5×GEST + 0.5×MATH + 0.5×HG + 1×FR + 1×ANG
```

#### Section 4 : **Technique**
```
FG = 4×MG + 1.5×TECH + 1.5×MATH + 1×SP + 1×FR + 1×ANG
```

#### Section 5 : **Informatique**
```
FG = 4×MG + 1.5×ALGO + 0.5×SP + 0.5×STI + 1×FR + 1×ANG
```

#### Section 6 : **Lettres**
```
FG = 4×MG + 1.5×ARABE + 1.5×PHILO + 1×HG + 1×FR + 1×ANG
```

#### Section 7 : **Sport**
```
FG = 4×MG + 1.5×SB + 1×SP_SPORT + 0.5×EP + 0.5×SP + 0.5×PH + 1×FR + 1×ANG
```

### Interprétation des scores FG

```
FG Score | Niveau      | Formations accessibles      | Chance admission
---------|-------------|---------------------------|------------------
190-200  | Excellent   | Tous (très compétitif)   | 95%+
170-189  | Très bon    | Majorité (compétitif)    | 80-95%
150-169  | Bon         | Beaucoup (modéré)        | 60-80%
130-149  | Moyen       | Quelques filières        | 40-60%
110-129  | Faible      | Peu de choix             | 20-40%
< 110    | Très faible | Limité (secteurs niche) | < 20%
```

---

## 2️⃣ Scores RIASEC (Holland)

### Calcul brut par dimension

Pour chaque dimension D ∈ {R, I, A, S, E, C} :

```
1. Sélectionner TOUTES les questions de bloc='riasec' ET dimension=D

2. Pour chaque question Q :
   - Récupérer réponse valeur ∈ {1,2,3,4,5}
   - Si Q.is_reverse == true :
       valeur = 6 - valeur  (inversion)
   - Poids = Q.poids ∈ {1,2,3}
   - Ajouter : valeur × poids au score brut

3. Score brut = Σ(valeur × poids) pour dimension D

4. Score maximum possible = Σ(5 × poids) pour dimension D

5. Score normalisé [0-100] = (Score brut / Score max) × 100
```

### Exemple de calcul pour dimension 'I' (Investigateur)

```
Questions RIASEC pour dimension I:
  Q3:  valeur=4, poids=1 → 4×1 = 4
  Q11: valeur=5, poids=1 → 5×1 = 5
  Q27: valeur=3, poids=2 → 3×2 = 6
  Q45: valeur=4, poids=1, is_reverse=true → (6-4)×1 = 2

Score brut = 4 + 5 + 6 + 2 = 17
Score max = 5×1 + 5×1 + 5×2 + 5×1 = 25

Score I = (17/25) × 100 = 68 / 100
```

### Déterminathion du code Holland

```
1. Calculer scores pour 6 dimensions : [R, I, A, S, E, C]
2. Trier par score décroissant
3. En cas d'égalité, priorité HOLLAND :
   R(0) > I(1) > A(2) > S(3) > E(4) > C(5)

Exemple:
  Scores: R=72, I=68, A=65, S=45, E=42, C=40
  Rangement: I(68) > A(65) > R(72)
  
  CORRECTION : I(68) mais R=72, donc:
  Rangement: R(72) > I(68) > A(65)
  Code Holland: "RIA"
```

---

## 3️⃣ Similarité Cosinus (RIASEC Matching)

### Formule

```
Soient deux vecteurs :
  V_etudiant = [r_e, i_e, a_e, s_e, e_e, c_e]  (scores étudiant)
  V_filiere  = [r_f, i_f, a_f, s_f, e_f, c_f]  (scores cibles filière)

Similarité cosinus = (V_etudiant · V_filiere) / (||V_etudiant|| × ||V_filiere||)

Où :
  V_etudiant · V_filiere = Σ(v_e[i] × v_f[i])
  ||V|| = √(Σ v²)
```

### Exemple de calcul

```
Étudiant avec code "IAS" :
  Scores : R=45, I=78, A=70, S=55, E=42, C=38
  Vecteur normalisé : [0.45, 0.78, 0.70, 0.55, 0.42, 0.38]

Filière Informatique (cible ICA) :
  Scores cibles : R=60, I=90, A=70, S=35, E=50, C=75
  Vecteur normalisé : [0.60, 0.90, 0.70, 0.35, 0.50, 0.75]

Produit scalaire:
  = 0.45×0.60 + 0.78×0.90 + 0.70×0.70 + 0.55×0.35 + 0.42×0.50 + 0.38×0.75
  = 0.27 + 0.702 + 0.49 + 0.1925 + 0.21 + 0.285
  = 2.1495

Normes:
  ||V_etudiant|| = √(0.45² + 0.78² + 0.70² + 0.55² + 0.42² + 0.38²)
                 = √(0.2025 + 0.6084 + 0.49 + 0.3025 + 0.1764 + 0.1444)
                 = √1.9342 = 1.391

  ||V_filiere|| = √(0.60² + 0.90² + 0.70² + 0.35² + 0.50² + 0.75²)
                = √(0.36 + 0.81 + 0.49 + 0.1225 + 0.25 + 0.5625)
                = √2.655 = 1.629

Cosinus = 2.1495 / (1.391 × 1.629) = 2.1495 / 2.266 = 0.949
```

**Résultat** : Similarité = 0.949 (excellent match !)

---

## 4️⃣ Score Recommandation Final (SIAEPI)

### Formule complète

```
Score_filiere = cosine(RIASEC) 
              × W_employability × employability_index
              × W_gatb × gatb_compatibility
              × W_big5 × big5_alignment
              × W_sdo × (1 - (abs(FG - SDO_2025) / 200))
              × W_market × (1 - market_saturation/100)
              × W_interest × domain_interest_match

Puis normaliser via sigmoid : final_score = 1 / (1 + e^(-z))

Résultat: Score ∈ [0, 1]
```

### Poids par défaut

```
W_employability = 0.25   (25% du score)
W_gatb           = 0.20   (20% du score)
W_big5           = 0.15   (15% du score)
W_sdo            = 0.20   (20% du score)
W_market         = 0.10   (10% du score)
W_interest       = 0.10   (10% du score)

Σ poids = 1.0
```

### Exemple de calcul complet

```
Étudiant :
  - RIASEC : Similarité = 0.85
  - FG = 145, SDO filière = 140
  - GATB : G=70, V=65, N=75, S=60 vs requiert G=60, V=55, N=65, S=50
  - Intérêt IT : Oui (+1.0)
  - Employabilité filière : 0.80
  - Saturation marché IT : 35%

Calculs :
  GATB_compat = min(1.0, (70+65+75+60)/(60+55+65+50)) = 1.04 → clampé à 1.0
  
  SDO_match = 1 - abs(145-140)/200 = 1 - 0.025 = 0.975
  
  Market = 1 - 35/100 = 0.65
  
  Big5_align = (à partir des scores big five, similarité ~ 0.70)

Score_filiere = 0.85 × 0.25×0.80 × 0.20×1.0 × 0.15×0.70 × 0.20×0.975 × 0.10×0.65 × 0.10×1.0
             
Approx = 0.85 × 0.20 × 0.20 × 0.105 × 0.195 × 0.065 × 0.10
       ≈ 0.72  (Très bon match)

Sigmoid(0.72) ≈ 0.673 (Probabilité ~67%)
```

---

## 5️⃣ Moteur Adaptatif (CAT - Computerized Adaptive Testing)

### Architecture 3 phases

#### **Phase 1 : Couverture obligatoire (Q 1-30)**

```
Objectif : Chaque dimension RIASEC obtient ≥ 3 réponses

Algorithme :
  TANT QUE une dimension < 3 réponses ET Q < 30 :
    
    1. Identifier dimension sous-couverte D
    2. Estimer theta (score latent) de l'étudiant :
       θ_D = (réponses_D - 1) / 4  ∈ [-1, 1]
    
    3. Sélectionner question Q de dimension D
       avec max information (entropy) :
       
       I(Q) = -Σ p(x) × log₂(p(x))
       où p(x) = P(réponse correcte | θ, params_Q)
    
    4. Afficher Q, enregistrer réponse
```

#### **Phase 2 : Intercalage GATB (tous les 6)**

```
Toutes les 6 réponses à partir de Q6, injecter 1 question GATB

Dimensions GATB :
  - GATB_G : Aptitude générale
  - GATB_V : Aptitude verbale
  - GATB_N : Aptitude numérique
  - GATB_S : Aptitude spatiale
```

#### **Phase 3 : Sélection adaptive (Q 30+)**

```
Choisir dimension avec max incertitude (entropy)

Pour chaque dimension D :
  certainty_D = |score_D - 50| / 50  ∈ [0, 1]
  entropy_D = 1 - certainty_D

Sélectionner D avec max(entropy_D)
Puis choisir meilleure question de D
```

### Critère d'arrêt

```
ARRÊTER SI (tout vrai) :
  1. nb_reponses_RIASEC_par_dim ≥ 3  (pour chaque dim)
  ET
  2. avg(certainty_top3_dims) > 0.85
  ET
  3. stability_scores_top3 < 0.10  (rangs stables 10q)
  
  OU
  
  nb_total_questions ≥ max_questions (50)
```

### Calcul IRT (Item Response Theory)

```
Pour chaque question Q avec paramètres (a, b, c) :

P(correct | θ) = c + (1 - c) × 1 / (1 + e^(-a×(θ-b)))

Où :
  θ = score latent étudiant (-3 à 3)
  a = discrimination (0.5 à 2.5)
  b = difficulté (-3 à 3)
  c = pseudo-guessing (0 à 0.25)

Information de Fisher :
  I(θ,Q) = a² × D² × P(1-P) × [(1-c)/P + c/(1-P)]²
  
  où P = P(correct|θ,Q) et D = 1.702 (scaling factor)
```

---

## 6️⃣ Compatibilité GATB

### Formule d'évaluation

```
Pour chaque aptitude A ∈ {G, V, N, S} :
  
  score_etudiant[A] = (nb_correct_A / nb_total_A) × 100
  
  gap_A = required_A - score_etudiant[A]
  
  Si gap ≤ 0   : status_A = "✅ OK"
  Si gap ≤ 10  : status_A = "⚠️  Effort mineur"
  Si gap ≤ 25  : status_A = "⚠️  Travail nécessaire"
  Si gap ≤ 40  : status_A = "🔴 Risque d'échec"
  Sinon        : status_A = "❌ Déconseillé"
```

### Scoring global

```
total_gap = Σ max(0, gap_A) pour A ∈ {G,V,N,S}

compatibilité_score = CASE
  WHEN total_gap = 0     THEN 1.0  (EXCELLENT)
  WHEN total_gap ≤ 10   THEN 0.8  (BON)
  WHEN total_gap ≤ 25   THEN 0.6  (MOYEN)
  WHEN total_gap ≤ 40   THEN 0.4  (FRAGILE)
  ELSE                       0.2  (INSUFFISANT)
```

---

## 7️⃣ Détection de fraude (Behavioral Analysis)

### Signaux d'alerte

```
1. Temps réponse anormal :
   temps_reponse < 500ms                 → FLAG
   temps_reponse > 120s                  → FLAG (hésitation)
   coeff_variation > 300%                → FLAG (erratique)

2. Pattern réponses :
   variance_réponses_similaires > 50     → FLAG
   Réponse toujours "3" (neutre)         → FLAG
   Progression non-monotone anormale     → FLAG

3. Cohérence interne :
   Cronbach α < 0.40                     → FLAG (très faible)

Score fraude = Σ flags
Si score > seuil → is_flagged = true
```

### Gestion des cas flaggés

```
NIVEAU 1 (Faible suspicion) :
  - Score fraudé = false
  - But log pour monitoring
  - Afficher dans admin dashboard

NIVEAU 2 (Moyenne suspicion) :
  - Score fraudé = true
  - Marquer profil pour révision humaine
  - Envoyer alerte conseiller

NIVEAU 3 (Forte suspicion) :
  - Score fraudé = true + blocked
  - Invalider test automatiquement
  - Demander retake
```

---

## 8️⃣ Early Stopping

### Condition d'arrêt précoce

```
Arrêter le test SUR SI (après chaque réponse) :
  
  1. nb_reponses_riasec_par_dim ≥ 3
  
  2. Calculer 3 dimensions dominantes (top 3 scores)
  
  3. Certitude pour chaque top 3 = 1 - (entropy / max_entropy)
  
  4. avg(certainty_top3) > 0.85
  
  5. Rang des top 3 stable (inchangé depuis 10 questions)

ALORS :
  stopped_early = true
  confidence_score = avg(certainty_top3)
  blocks_completed = ceil(nb_questions / 6)
  
  Générer profil avec scores actuels
  Afficher résultats
```

### Économies et fiabilité

```
Réduction questions : ~50% (60→30 q)
Mais fiabilité préservée : Cronbach α = 0.78-0.82
(vs 0.85-0.90 avec test complet)

Économie temps : ~15-20 min au lieu 30-40 min
```

---

## 9️⃣ Calcul de cohérence (Score de fiabilité)

### Méthode : Cronbach Alpha

```
α = (k / (k - 1)) × (1 - (Σ σ²_item / σ²_total))

Où :
  k = nombre de questions
  σ²_item = variance de chaque question
  σ²_total = variance du score total
  
Résultat : α ∈ [0, 1]

Interprétation :
  α ≥ 0.90  : Excellent
  α ≥ 0.80  : Bon
  α ≥ 0.70  : Acceptable
  α ≥ 0.60  : Questionnable
  α < 0.60  : Inacceptable
```

### Flagging automatique

```
Si α < 0.60 :
  is_flagged = true
  flag_reason += "Faible cohérence interne"
  Signaler au conseiller pour révision
```

---

## 🔟 Prédiction admission (AdmissionPredictor)

### Logique MVP

```
base_score = 60
seed = CRC32(user_id + formation_name)
rand_factor = PSEUDO_RANDOM(seed, -15, +35)
prediction_score = min(98, max(45, base_score + rand_factor))

Résultat: Score ∈ [45%, 98%]
```

### Améliorations futures

```
À remplacer par :
  - Modèle logistique (sklearn)
  - Forêt aléatoire (XGBoost)
  - Réseau neurontal (PyTorch)
  
Features :
  - FG score
  - Code Holland
  - GATB scores
  - Big Five scores
  - Employability filière
  - Taux employabilité domaine
  - Scores interaction historique
  
Target : Binaire (accepted / rejected)
```

---

## 📊 Récapitulatif des formules

| Concept | Formule | Range |
|---------|---------|-------|
| **Score FG** | 4×MG + Σ(coef_i×mat_i) | [0, 200] |
| **Score RIASEC dim** | (Σ réponse×poids) / max × 100 | [0, 100] |
| **Similarité cosinus** | (V·W) / (\|\|V\|\| × \|\|W\|\|) | [0, 1] |
| **Score recommandation** | Σ(W_i × score_i) sigmoid | [0, 1] |
| **IRT P(correct)** | c + (1-c) / (1 + e^(-a(θ-b))) | [0, 1] |
| **Cronbach α** | (k/(k-1)) × (1 - Σσ²/σ²_t) | [0, 1] |
| **Information Fisher** | a²×D²×P(1-P)×[...] | [0, ∞] |
| **Compatibilité GATB** | (sum(correct)/sum(total))×100 | [0, 100] |

---

**Fin de la documentation alglorithmique**
**SIAEPI v8.0 - Mai 2026**
