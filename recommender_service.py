"""
recommender_service.py
Service de recommandation de filières pour CapAvenir.
"""

import os, math, warnings
from pathlib import Path
import difflib
import numpy as np
import pandas as pd
from sklearn.metrics.pairwise import cosine_similarity

warnings.filterwarnings("ignore")
pd.set_option("display.max_columns", 160)
pd.set_option("display.width", 200)
np.random.seed(42)

# ── Constantes globales ──────────────────────────────────────────────
RIASEC_DIMS = ["R", "I", "A", "S", "E", "C"]

GLOBAL_WEIGHTS = {
    "RIASEC":         0.30,
    "Faisabilite":    0.20,
    "GATB":           0.20,
    "Interets":       0.10,
    "Personnalite":   0.10,
    "Employabilite":  0.05,
    "Croissance":     0.05,
}

EMPLOYABILITE_MAP = {"Très élevé": 1.0, "Élevé": 0.8, "Moyen": 0.5, "Faible": 0.25, "Très faible": 0.1}
CROISSANCE_MAP   = {"Forte croissance": 1.0, "Croissance": 0.75, "Stable": 0.5, "Déclin": 0.2}
ALIGNMENT_MAP    = {"Très élevé": 1.0, "Élevé": 0.8, "Modéré": 0.5, "Faible": 0.25}

MOTS_RIASEC = {
    "R": ["technique", "mécanique", "outil", "machine", "terrain", "manuel", "construction", "réparation", "physique", "pratique"],
    "I": ["analyse", "recherche", "logique", "données", "science", "hypothèse", "investigation", "mathématique", "expérience", "observation"],
    "A": ["création", "art", "design", "imagination", "musique", "écriture", "esthétique", "expression", "innovation", "culture"],
    "S": ["aide", "enseignement", "social", "communication", "empathie", "conseil", "soin", "écoute", "coopération", "bénévolat"],
    "E": ["leadership", "management", "vente", "négociation", "stratégie", "décision", "entrepreneuriat", "persuasion", "ambition", "direction"],
    "C": ["organisation", "administration", "précision", "procédure", "classement", "comptabilité", "rigueur", "méthode", "planification", "contrôle"],
}


# ── Fonctions utilitaires ────────────────────────────────────────────
def clamp(val, lo=0.0, hi=1.0):
    """Borne une valeur entre lo et hi."""
    return max(lo, min(hi, float(val)))


def normaliser_minmax(val, val_min, val_max):
    """Normalisation min-max vers [0, 1]."""
    if val_max == val_min:
        return 0.5
    return clamp((val - val_min) / (val_max - val_min))


def normaliser_likert(val, echelle_max=5):
    """Normalise une valeur Likert vers [0, 1]."""
    return clamp(val / echelle_max)


def vector_from_dict(d, keys=None):
    """Convertit un dict en array numpy ordonné."""
    if keys is None:
        keys = RIASEC_DIMS
    return np.array([float(d.get(k, 0.0)) for k in keys])


def weighted_cosine(v1, v2, weights=None):
    """Cosinus pondéré entre deux vecteurs."""
    v1, v2 = np.array(v1, dtype=float), np.array(v2, dtype=float)
    if weights is not None:
        w = np.array(weights, dtype=float)
        v1, v2 = v1 * w, v2 * w
    n1, n2 = np.linalg.norm(v1), np.linalg.norm(v2)
    if n1 == 0 or n2 == 0:
        return 0.0
    return float(np.dot(v1, v2) / (n1 * n2))


# ── RIASEC ────────────────────────────────────────────────────────────
def riasec_from_code(code_str):
    """Convertit un code RIASEC 3 lettres en vecteur 6D normalisé."""
    code_str = str(code_str).upper().strip()
    vec = {d: 0.0 for d in RIASEC_DIMS}
    for i, ch in enumerate(code_str[:3]):
        if ch in vec:
            vec[ch] = (3 - i) / 3.0  # 1.0, 0.67, 0.33
    return vec


def synchroniser_scores(scores_dict):
    """Normalise un dict de scores RIASEC pour que la somme = 1."""
    total = sum(scores_dict.values())
    if total == 0:
        return {k: 1.0 / len(scores_dict) for k in scores_dict}
    return {k: v / total for k, v in scores_dict.items()}


def profil_cible_depuis_riasec(code_riasec):
    """Crée un vecteur cible RIASEC 6D depuis un code 3 lettres."""
    return riasec_from_code(code_riasec)


# ── Scores par blocs ─────────────────────────────────────────────────
def score_blocs(vecteur_etudiant, vecteur_cible):
    """Calcule les scores de compatibilité psychométrique."""
    if isinstance(vecteur_etudiant, dict):
        v_etu = vector_from_dict(vecteur_etudiant)
    else:
        v_etu = np.array(vecteur_etudiant, dtype=float)

    if isinstance(vecteur_cible, dict):
        v_cib = vector_from_dict(vecteur_cible)
    else:
        v_cib = np.array(vecteur_cible, dtype=float)

    # Cosine similarity
    cos_sim = weighted_cosine(v_etu, v_cib)
    compat = clamp((cos_sim + 1) / 2)

    # Distance euclidienne normalisée
    dist = np.linalg.norm(v_etu - v_cib)
    max_dist = np.sqrt(len(v_etu))
    proximite = clamp(1.0 - dist / max_dist)

    # Score de dominance (les 3 premières dims du cible matchent l'étudiant)
    top3_cible = sorted(range(len(v_cib)), key=lambda i: v_cib[i], reverse=True)[:3]
    top3_etu = sorted(range(len(v_etu)), key=lambda i: v_etu[i], reverse=True)[:3]
    overlap = len(set(top3_cible) & set(top3_etu))
    dominance = overlap / 3.0

    score_final = clamp(0.50 * compat + 0.30 * proximite + 0.20 * dominance)

    return {
        "Compatibilite_Psychometrique": score_final,
        "Cosine_Similarity": cos_sim,
        "Proximite_Euclidienne": proximite,
        "Score_Dominance": dominance,
    }


# ── Score académique ──────────────────────────────────────────────────
def _safe_float(val, default=None):
    """Tente de convertir en float, retourne default si impossible."""
    if val is None:
        return default
    try:
        f = float(val)
        return f if not math.isnan(f) else default
    except (ValueError, TypeError):
        return default


def calculer_score_academique(row_filiere, score_fg_etudiant):
    """Score académique basé sur la proximité au SDO de la filière."""
    sdo = _safe_float(row_filiere.get("SDO_2025"))
    if sdo is None:
        sdo = _safe_float(row_filiere.get("SDO_2024"))
    if sdo is None:
        sdo = _safe_float(row_filiere.get("SDO_2023"))
    if sdo is None:
        sdo = 140.0
    score_fg = float(score_fg_etudiant)

    if score_fg >= sdo:
        return clamp(1.0 - 0.002 * max(0, score_fg - sdo - 30))
    else:
        diff = sdo - score_fg
        if diff <= 10:
            return clamp(0.85 - 0.01 * diff)
        elif diff <= 30:
            return clamp(0.70 - 0.015 * (diff - 10))
        else:
            return clamp(0.30 - 0.005 * (diff - 30))


# ── Score marché ──────────────────────────────────────────────────────
def calculer_score_marche(row):
    """Score de marché à partir des indicateurs catégoriels."""
    emp = str(row.get("Taux_Employabilite", "Moyen")).strip()
    cro = str(row.get("Croissance_Domaine", "Stable")).strip()
    ali = str(row.get("Alignment_National", "Modéré")).strip()

    s_emp = EMPLOYABILITE_MAP.get(emp, 0.5)
    s_cro = CROISSANCE_MAP.get(cro, 0.5)
    s_ali = ALIGNMENT_MAP.get(ali, 0.5)

    return clamp(0.45 * s_emp + 0.30 * s_cro + 0.25 * s_ali)


# ── Score textuel ─────────────────────────────────────────────────────
def tokeniser_texte(texte):
    """Tokenise un texte en mots-clés nettoyés."""
    if not texte or not isinstance(texte, str):
        return []
    mots = texte.lower().replace(",", " ").replace(".", " ").split()
    return [m.strip() for m in mots if len(m.strip()) > 2]


def score_texte(row_filiere, texte_etudiant):
    """Score de similarité textuelle entre profil étudiant et filière."""
    if not texte_etudiant:
        return 0.5

    tokens_etu = tokeniser_texte(texte_etudiant)
    if not tokens_etu:
        return 0.5

    code_riasec = str(row_filiere.get("Code_RIASEC", "")).upper()
    mots_filiere = []
    for ch in code_riasec[:3]:
        mots_filiere.extend(MOTS_RIASEC.get(ch, []))

    nom = str(row_filiere.get("Nom_Filiere", ""))
    mots_filiere.extend(tokeniser_texte(nom))

    if not mots_filiere:
        return 0.5

    matches = 0
    for tok in tokens_etu:
        best = difflib.get_close_matches(tok, mots_filiere, n=1, cutoff=0.75)
        if best:
            matches += 1

    return clamp(matches / max(len(tokens_etu), 1))


# ── Overlap RIASEC ────────────────────────────────────────────────────
def score_overlap_riasec(code1, code2):
    """Score d'overlap entre deux codes RIASEC."""
    if not code1 or not code2:
        return 0.0
    s1 = set(str(code1).upper()[:3])
    s2 = set(str(code2).upper()[:3])
    inter = len(s1 & s2)
    return inter / 3.0


# ── GATB Compatibility ──────────────────────────────────────────────────
def score_gatb_compatibility(student_gatb, filiere_row):
    """Calcule le score de compatibilité GATB avec les prérequis de la filière."""
    required = {
        'G': _safe_float(filiere_row.get('g_requis'), 10),
        'V': _safe_float(filiere_row.get('v_requis'), 10),
        'N': _safe_float(filiere_row.get('n_requis'), 10),
        'S': _safe_float(filiere_row.get('s_requis'), 10),
    }
    
    student = {
        'G': _safe_float(student_gatb.get('G'), 10),
        'V': _safe_float(student_gatb.get('V'), 10),
        'N': _safe_float(student_gatb.get('N'), 10),
        'S': _safe_float(student_gatb.get('S'), 10),
    }
    
    total_gap = 0
    details = {}
    
    for aptitude in ['G', 'V', 'N', 'S']:
        gap = max(0, required[aptitude] - student[aptitude])
        total_gap += gap
        details[aptitude] = {
            'student': student[aptitude],
            'required': required[aptitude],
            'gap': gap,
            'status': '✅' if gap == 0 else '⚠️'
        }
        
    # Score normalisé (0 à 1)
    max_gap = 40  # 4 aptitudes × 10 points d'écart max
    score = clamp(1.0 - (total_gap / max_gap))
    
    # Pénalité supplémentaire si écart sévère
    for aptitude, detail in details.items():
        if detail['gap'] >= 8:
            score *= 0.7
            
    return round(score, 3), details

# ── Contexte filière ──────────────────────────────────────────────────
def calculer_score_contexte_filiere(row_candidate, filiere_actuelle_row=None):
    """Calcule scores de passerelle et continuité par rapport à la filière actuelle."""
    if filiere_actuelle_row is None:
        return {"Score_Passerelle": 0.5, "Score_Continuite": 0.5, "Type_Transition": "Nouvelle orientation"}

    code_act = str(filiere_actuelle_row.get("Code_RIASEC", "")).upper()
    code_cand = str(row_candidate.get("Code_RIASEC", "")).upper()

    overlap = score_overlap_riasec(code_act, code_cand)

    nom_act = str(filiere_actuelle_row.get("Nom_Filiere", "")).lower()
    nom_cand = str(row_candidate.get("Nom_Filiere", "")).lower()
    sim_nom = difflib.SequenceMatcher(None, nom_act, nom_cand).ratio()

    etab_act = str(filiere_actuelle_row.get("Etablissement", "")).lower()
    etab_cand = str(row_candidate.get("Etablissement", "")).lower()
    meme_etab = 1.0 if etab_act == etab_cand and etab_act else 0.0

    score_passerelle = clamp(0.50 * overlap + 0.30 * sim_nom + 0.20 * meme_etab)
    score_continuite = clamp(0.60 * overlap + 0.40 * sim_nom)

    if overlap >= 0.67:
        transition = "Continuité directe"
    elif overlap >= 0.33:
        transition = "Passerelle proche"
    elif sim_nom > 0.4:
        transition = "Réorientation apparentée"
    else:
        transition = "Réorientation"

    return {
        "Score_Passerelle": score_passerelle,
        "Score_Continuite": score_continuite,
        "Type_Transition": transition,
    }


# ── Confiance & Maturité ─────────────────────────────────────────────
def calculer_confiance_reponses(reponses=None):
    """Calcule un score de confiance sur les réponses au test."""
    if reponses is None:
        return 0.75
    if isinstance(reponses, (list, np.ndarray)):
        arr = np.array(reponses, dtype=float)
        variance = np.var(arr)
        return clamp(0.5 + 0.5 * min(variance / 2.0, 1.0))
    return 0.75


def calculer_maturite(profil_etudiant):
    """Score de maturité professionnelle."""
    texte = profil_etudiant.get("texte_psycho", "")
    tokens = tokeniser_texte(texte)
    richesse = clamp(len(tokens) / 20.0)

    vec = profil_etudiant.get("vecteur_psychometrique", {})
    if isinstance(vec, dict):
        vals = list(vec.values())
    else:
        vals = list(vec)
    differentiation = np.std(vals) if vals else 0.0
    diff_score = clamp(differentiation / 0.4)

    return clamp(0.5 * richesse + 0.5 * diff_score)


# ── Risque ────────────────────────────────────────────────────────────
def calculer_risque(score_final, score_confiance, score_academique, score_passerelle):
    """Évalue le niveau de risque d'une recommandation."""
    risk_raw = 1.0 - (0.35 * score_final + 0.25 * score_confiance + 0.25 * score_academique + 0.15 * score_passerelle)
    risk = clamp(risk_raw)
    if risk <= 0.25:
        return "Faible"
    elif risk <= 0.50:
        return "Modéré"
    elif risk <= 0.75:
        return "Élevé"
    else:
        return "Très élevé"


# ── Trouver filière actuelle ──────────────────────────────────────────
def trouver_filiere_actuelle(df_filieres, nom_filiere):
    """Recherche la filière actuelle dans le DataFrame."""
    if not nom_filiere or df_filieres.empty:
        return None
    nom = nom_filiere.strip().lower()
    for _, row in df_filieres.iterrows():
        if str(row.get("Nom_Filiere", "")).strip().lower() == nom:
            return row
    # Recherche approximative
    best_score, best_row = 0, None
    for _, row in df_filieres.iterrows():
        r = difflib.SequenceMatcher(None, nom, str(row.get("Nom_Filiere", "")).lower()).ratio()
        if r > best_score:
            best_score, best_row = r, row
    if best_score > 0.6:
        return best_row
    return None


# ── Diagnostiquer filière actuelle ────────────────────────────────────
def diagnostiquer_filiere_actuelle(row_filiere, profil_etudiant):
    """Diagnostic rapide de la filière actuelle de l'étudiant."""
    if row_filiere is None:
        return {"diagnostic": "Aucune filière actuelle identifiée", "score": 0}

    cible = profil_cible_depuis_riasec(row_filiere["Code_RIASEC"])
    blocs = score_blocs(profil_etudiant["vecteur_psychometrique"], cible)
    acad = calculer_score_academique(row_filiere, profil_etudiant["score_fg"])

    score_global = 0.6 * blocs["Compatibilite_Psychometrique"] + 0.4 * acad

    if score_global >= 0.75:
        diag = "Excellent alignement avec la filière actuelle"
    elif score_global >= 0.55:
        diag = "Alignement correct, mais des alternatives pourraient mieux convenir"
    elif score_global >= 0.35:
        diag = "Alignement faible, une réorientation est conseillée"
    else:
        diag = "Désalignement important, réorientation fortement recommandée"

    return {"diagnostic": diag, "score": round(score_global, 3), "details": blocs, "score_academique": acad}


# ── Analyse GAP ───────────────────────────────────────────────────────
def analyse_gap(profil_etudiant, row_filiere_cible):
    """Analyse l'écart entre le profil étudiant et une filière cible."""
    cible = profil_cible_depuis_riasec(row_filiere_cible["Code_RIASEC"])
    vec_etu = profil_etudiant.get("vecteur_psychometrique", {})

    gaps = {}
    for dim in RIASEC_DIMS:
        val_etu = float(vec_etu.get(dim, 0)) if isinstance(vec_etu, dict) else 0
        val_cib = float(cible.get(dim, 0))
        gaps[dim] = {"etudiant": round(val_etu, 2), "cible": round(val_cib, 2), "ecart": round(val_cib - val_etu, 2)}

    return gaps


# ── Niveaux de score ──────────────────────────────────────────────────
def niveau_score(score):
    """Convertit un score numérique en niveau textuel."""
    if score >= 0.80:
        return "Excellent"
    elif score >= 0.65:
        return "Bon"
    elif score >= 0.45:
        return "Moyen"
    elif score >= 0.25:
        return "Faible"
    else:
        return "Très faible"


# ── Résumé d'orientation ──────────────────────────────────────────────
def generer_resume_orientation(resultats_df, profil_etudiant, filiere_actuelle_row=None):
    """Génère un résumé textuel de l'orientation incluant l'analyse GATB."""
    resume = []
    resume.append("=== RÉSUMÉ D'ORIENTATION PERSONNALISÉ ===\n")
    
    # GATB
    student_gatb = profil_etudiant.get("gatb_scores", {})
    if student_gatb:
        gatb_total = _safe_float(student_gatb.get('TOTAL'), 10)
        gatb_niveau = "Très bon" if gatb_total >= 13 else ("Moyen" if gatb_total >= 10 else "Fragile")
        resume.append(f"📊 TES APTITUDES COGNITIVES (GATB) : {gatb_niveau} ({gatb_total}/20)")
        
        # Trouver la dominante
        aptitudes = {k: v for k, v in student_gatb.items() if k in ['G', 'V', 'N', 'S']}
        if aptitudes:
            dominante = max(aptitudes, key=aptitudes.get)
            resume.append(f"   - Point fort : Aptitude {dominante} ({aptitudes[dominante]}/20)")
        resume.append("")

    if filiere_actuelle_row is not None:
        diag = diagnostiquer_filiere_actuelle(filiere_actuelle_row, profil_etudiant)
        resume.append(f"💭 TON RÊVE / FILIÈRE ACTUELLE : {filiere_actuelle_row.get('Nom_Filiere', 'N/A')}")
        resume.append(f"   Diagnostic : {diag['diagnostic']} (Score: {diag['score']})\n")

    if not resultats_df.empty:
        resume.append("🎓 TOP 3 DES FILIÈRES RECOMMANDÉES :")
        for i, row in resultats_df.head(3).iterrows():
            gatb_status = "✅ Aptitudes OK" if row.get("Score_GATB", 0) >= 0.7 else "⚠️ Vérifie tes aptitudes"
            resume.append(f"  {row['Rang']}. {row['Nom_Filiere']} ({row['Universite']})")
            resume.append(f"     Score global: {row['Score_Final_Contextuel']*100:.0f}% | {gatb_status}")
            if row.get("Warning"):
                resume.append(f"     {row['Warning']}")

    resume.append("\n💡 NOS CONSEILS :")
    resume.append("   - Explore les fiches détaillées de nos recommandations.")
    if student_gatb and _safe_float(student_gatb.get('TOTAL'), 10) < 10:
        resume.append("   - 🎯 Priorise un travail sur tes aptitudes cognitives (raisonnement, logique).")

    return "\n".join(resume)


# ══════════════════════════════════════════════════════════════════════
#  FONCTION PRINCIPALE : recommander_filieres_contextuel
# ══════════════════════════════════════════════════════════════════════
def recommander_filieres_contextuel(df_filieres, profil_etudiant, filiere_actuelle_row=None, top_n=12):
    """
    Recommande les meilleures filières pour un étudiant en combinant :
    - Score psychométrique (RIASEC)
    - Score académique (SDO)
    - Score marché (employabilité)
    - Score textuel (mots-clés)
    - Score contexte filière (passerelle / continuité)
    """
    score_confiance = calculer_confiance_reponses(profil_etudiant.get("reponses", None))
    score_maturite = calculer_maturite(profil_etudiant)
    
    # ── EXTRACTION GATB ──
    student_gatb = profil_etudiant.get("gatb_scores", {'G': 10, 'V': 10, 'N': 10, 'S': 10, 'TOTAL': 10})
    
    # ── EXTRACTION DES SCORES BIG FIVE & RIASEC ──
    score_C = float(profil_etudiant.get("conscienciosite", 3.0))
    score_O = float(profil_etudiant.get("ouverture", 3.0))
    score_N = float(profil_etudiant.get("anxiete", 3.0))
    
    vec_etu = profil_etudiant.get("vecteur_psychometrique", {})
    if isinstance(vec_etu, dict) and float(vec_etu.get("A", 0)) >= 0.6:
        # Règle : Intérêt Artistique fort -> Filtrer les codes avec A
        df_filieres_A = df_filieres[df_filieres["Code_RIASEC"].astype(str).str.contains('A', na=False, case=False)]
        if not df_filieres_A.empty:
            df_filieres = df_filieres_A.copy()

    # Filtrage par Type de Bac (Section)
    type_bac_etudiant = profil_etudiant.get("filiere_etudiant_actuelle")
    if type_bac_etudiant and "Type_Bac" in df_filieres.columns:
        def normalize_bac(s):
            if not s or not isinstance(s, str): return ""
            # Prend le premier mot, minuscule, sans accent
            s = s.lower().strip()
            s = s.replace('é', 'e').replace('è', 'e').replace('ê', 'e').replace('à', 'a')
            return s.split()[0]
            
        norm_etu = normalize_bac(type_bac_etudiant)
        df_filieres = df_filieres[
            (df_filieres["Type_Bac"].apply(normalize_bac) == norm_etu) | 
            (df_filieres["Type_Bac"] == "Général")
        ].copy()


    # Pré-calculer Score_Marche si absent
    if "Score_Marche" not in df_filieres.columns:
        df_filieres = df_filieres.copy()
        df_filieres["Score_Marche"] = df_filieres.apply(calculer_score_marche, axis=1)

    lignes = []
    for _, row in df_filieres.iterrows():
        cible = profil_cible_depuis_riasec(row["Code_RIASEC"])
        scores_blocs_res = score_blocs(profil_etudiant["vecteur_psychometrique"], cible)
        
        # Règle de matching RIASEC : Correspondance exacte sur 2 lettres minimum
        overlap_letters = round(scores_blocs_res["Score_Dominance"] * 3.0)
        if overlap_letters < 2:
            scores_blocs_res["Compatibilite_Psychometrique"] *= 0.5  # Pénalité forte
        else:
            scores_blocs_res["Compatibilite_Psychometrique"] = clamp(scores_blocs_res["Compatibilite_Psychometrique"] * 1.2)  # Bonus
            
        score_academique = calculer_score_academique(row, profil_etudiant["score_fg"])
        
        score_marche = float(row["Score_Marche"])
        emp = str(row.get("Taux_Employabilite", "Moyen")).strip()
        cro = str(row.get("Croissance_Domaine", "Stable")).strip()
        
        # ── RÈGLES DE MATCHING INTELLIGENTES BIG FIVE ──
        # Si Conscienciosité > 4 -> favoriser 'Élevé' / 'Très Élevé'
        if score_C >= 4.0 and emp in ["Élevé", "Très élevé"]:
            score_marche *= 1.2
            
        # Si Ouverture > 4 -> favoriser 'Croissance'
        if score_O >= 4.0 and cro in ["Croissance", "Forte croissance"]:
            score_marche *= 1.2
            
        # Anxiété (Score Élevé) -> Proposer filières 'Stable' avec accompagnement
        if score_N >= 4.0:
            if cro == "Stable":
                score_marche *= 1.2
            elif cro in ["Croissance", "Forte croissance"]:
                score_marche *= 0.8
                
        score_marche = clamp(score_marche)
        
        texte = score_texte(row, profil_etudiant.get("texte_psycho", ""))
        
        contexte = calculer_score_contexte_filiere(row, filiere_actuelle_row)
        score_contextuel = clamp(0.60 * contexte["Score_Passerelle"] + 0.40 * contexte["Score_Continuite"])

        # ── EXTRACTION & CALCUL DES 7 CRITÈRES (V2.2) ──
        # 1. RIASEC (30%)
        score_riasec = clamp((scores_blocs_res["Cosine_Similarity"] + 1) / 2)

        # 2. Faisabilité SDO/BAC (20%)
        score_faisabilite = score_academique
        
        # 3. GATB (20%)
        score_gatb, gatb_details = score_gatb_compatibility(student_gatb, row)

        # 4. Intérêts / Discrimination (10%)
        score_interets = texte

        # 5. Personnalité Big Five (10%)
        score_personnalite = 0.5
        if score_C >= 4.0 and emp in ["Élevé", "Très élevé"]: score_personnalite += 0.2
        if score_O >= 4.0 and cro in ["Croissance", "Forte croissance"]: score_personnalite += 0.2
        if score_N >= 4.0 and cro == "Stable": score_personnalite += 0.1
        score_personnalite = clamp(score_personnalite)

        # 6. Employabilité (5%)
        employabilite_score = clamp(score_marche * 1.5) # Approximé depuis l'ancien score marché

        # 7. Croissance (5%)
        croissance_score = clamp(score_marche * 1.2) # Approximé depuis l'ancien score marché

        score_final_contextuel = clamp(
            GLOBAL_WEIGHTS["RIASEC"] * score_riasec
            + GLOBAL_WEIGHTS["Faisabilite"] * score_faisabilite
            + GLOBAL_WEIGHTS["GATB"] * score_gatb
            + GLOBAL_WEIGHTS["Interets"] * score_interets
            + GLOBAL_WEIGHTS["Personnalite"] * score_personnalite
            + GLOBAL_WEIGHTS["Employabilite"] * employabilite_score
            + GLOBAL_WEIGHTS["Croissance"] * croissance_score
        )
        
        # FILTRAGE STRICT : Pénalité si GATB insuffisant
        warning = None
        if score_gatb < 0.4:
            score_final_contextuel *= 0.5
            warning = "⚠️ APTITUDES INSUFFISANTES"

        risque = calculer_risque(score_final_contextuel, score_confiance, score_academique, contexte["Score_Passerelle"])

        ligne = {
            "Code_Filiere": row["Code_Filiere"],
            "Nom_Filiere": row["Nom_Filiere"],
            "Universite": row["Universite"],
            "Etablissement": row["Etablissement"],
            "Code_RIASEC": row["Code_RIASEC"],
            "SDO_2025": row.get("SDO_2025", None),
            "Score_Academique": round(score_academique, 4),
            "Score_Marche": round(score_marche, 4),
            "Score_Texte": round(texte, 4),
            "Score_GATB": round(score_gatb, 4),
            "GATB_Details": gatb_details,
            "Warning": warning,
            "Score_Contexte_Filiere": round(score_contextuel, 4),
            "Score_Continuite": round(contexte["Score_Continuite"], 4),
            "Score_Passerelle": round(contexte["Score_Passerelle"], 4),
            "Type_Transition": contexte["Type_Transition"],
            "Score_Recommandation_Classique": round(score_final_contextuel, 4),
            "Score_Final_Contextuel": round(score_final_contextuel, 4),
            "Risque": risque,
            "Confiance_Test": round(score_confiance, 4),
            "Maturite_Professionnelle": round(score_maturite, 4),
        }
        ligne.update({k: round(v, 4) for k, v in scores_blocs_res.items()})
        lignes.append(ligne)

    resultats = pd.DataFrame(lignes)
    resultats = resultats.sort_values("Score_Final_Contextuel", ascending=False).head(top_n).reset_index(drop=True)
    resultats.insert(0, "Rang", range(1, len(resultats) + 1))
    return resultats


# ══════════════════════════════════════════════════════════════════════
#  Chargement des données filières (7 fichiers Excel)
# ══════════════════════════════════════════════════════════════════════
def load_filiere_data(base_path=None):
    """Charge et concatène tous les fichiers Excel de filières."""
    if base_path is None:
        base_path = Path(__file__).resolve().parent / "storage" / "app" / "excels"
    else:
        base_path = Path(base_path)

    all_df = []
    
    # Mapping des noms de fichiers ou préfixes vers les types de Bac
    bac_mapping = {
        'INFO': 'Informatique', 'INF': 'Informatique',
        'ECO': 'Économie et gestion', 'EGE': 'Économie et gestion',
        'EXP': 'Sciences expérimentales', 'SXP': 'Sciences expérimentales',
        'TECH': 'Technique', 'TEC': 'Technique',
        'SPORT': 'Sport', 'SPO': 'Sport',
        'LET': 'Lettres', 'LTR': 'Lettres',
        'MATH': 'Mathématiques', 'MAT': 'Mathématiques'
    }

    for ext in ("*.xlsx", "*.csv"):
        for fp in base_path.glob(ext):
            try:
                if fp.suffix == ".csv":
                    df = pd.read_csv(fp)
                else:
                    df = pd.read_excel(fp)
                
                # Identifier le type de Bac à partir du nom du fichier
                prefix_file = fp.stem.split('_')[0].upper()
                type_bac = bac_mapping.get(prefix_file)
                
                # Si non trouvé, essayer via le premier Code_Filiere du fichier
                if not type_bac and not df.empty and 'Code_Filiere' in df.columns:
                    first_code = str(df['Code_Filiere'].iloc[0])[:3].upper()
                    type_bac = bac_mapping.get(first_code, 'Général')
                
                df['Type_Bac'] = type_bac or 'Général'
                all_df.append(df)
            except Exception as e:
                print(f"Erreur lecture {fp}: {e}")

    if all_df:
        combined = pd.concat(all_df, ignore_index=True)
        combined = combined.drop_duplicates(subset=["Code_Filiere"], keep="first")
        return combined

    print("Aucun fichier trouvé dans", base_path)
    return pd.DataFrame()


# ══════════════════════════════════════════════════════════════════════
#  Point d'entrée principal (test)
# ══════════════════════════════════════════════════════════════════════
if __name__ == "__main__":
    df_filieres = load_filiere_data()
    print(f"Filières chargées : {len(df_filieres)}")

    if not df_filieres.empty:
        profil = {
            "score_fg": 120,
            "filiere_etudiant_actuelle": "Informatique",
            "texte_psycho": "analyse logique données organisation aide décision autonomie résolution problèmes",
            "vecteur_psychometrique": {"R": 0.5, "I": 0.8, "A": 0.3, "S": 0.6, "E": 0.4, "C": 0.7},
        }

        fil_act = trouver_filiere_actuelle(df_filieres, profil["filiere_etudiant_actuelle"])
        recs = recommander_filieres_contextuel(df_filieres, profil, fil_act, top_n=5)
        print(recs.to_string())
        print("\n")
        print(generer_resume_orientation(recs, profil, fil_act))
