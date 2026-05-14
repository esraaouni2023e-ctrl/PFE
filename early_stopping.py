def get_stable_trigramme(scores_dict):
    """
    Détermine le trigramme dominant à partir d'un dictionnaire de scores.
    Ex: {'R': 80, 'I': 70, 'A': 50, 'S': 40, 'E': 60, 'C': 30}
    """
    # Priorité Holland en cas d'égalité : R > I > A > S > E > C
    holland_priority = {'R': 0, 'I': 1, 'A': 2, 'S': 3, 'E': 4, 'C': 5}
    
    sorted_items = sorted(
        scores_dict.items(),
        key=lambda item: (-item[1], holland_priority.get(item[0], 99))
    )
    
    trigramme = "".join(k for k, v in sorted_items[:3])
    return trigramme

def calculate_confidence(scores_history):
    """
    Calcule un score de confiance (0-100) basé sur la stabilité du profil
    à travers l'historique des blocs.
    """
    if len(scores_history) < 2:
        return 50  # Pas assez de recul

    trigrammes = [get_stable_trigramme(scores) for scores in scores_history]
    
    last_tri = trigrammes[-1]
    prev_tri = trigrammes[-2]
    
    # 1 point par lettre au bon endroit
    matches = sum(1 for i in range(3) if last_tri[i] == prev_tri[i] if i < len(last_tri) and i < len(prev_tri))
    
    # Base: 50 + 15 par lettre stable (max 95)
    confidence = 50 + (matches * 15)
    
    # Bonus si même lettre primaire sur 3 blocs
    if len(trigrammes) >= 3 and last_tri[0] == trigrammes[-3][0]:
        confidence += 5
        
    return min(100, confidence)

def evaluer_arret_precoce(scores_history, block_index):
    """
    Évalue si le test peut s'arrêter selon des règles heuristiques.
    """
    if not scores_history:
        return {"stop": False, "confidence": 0, "trigramme": "", "message": ""}

    trigramme_actuel = get_stable_trigramme(scores_history[-1])
    confidence = calculate_confidence(scores_history)
    
    # Règles d'arrêt obligatoires
    if block_index >= 7:
        return {
            "stop": True, 
            "confidence": confidence, 
            "trigramme": trigramme_actuel, 
            "message": "🎯 Excellente stabilité ! Vous pouvez terminer maintenant."
        }
        
    trigrammes = [get_stable_trigramme(scores) for scores in scores_history]
    
    is_stable_2 = len(trigrammes) >= 2 and trigrammes[-1] == trigrammes[-2]
    is_stable_3 = len(trigrammes) >= 3 and trigrammes[-1] == trigrammes[-2] == trigrammes[-3]
    
    stop = False
    message = "🔍 Continuons pour affiner votre profil unique."
    
    # Règle 1: même trigramme sur 2 blocs consécutifs ET confiance >= 85%
    if is_stable_2 and confidence >= 85:
        stop = True
    # Règle 2: bloc >= 4 ET trigramme stable SUR 3 blocs ET confiance >= 70%
    elif block_index >= 4 and is_stable_3 and confidence >= 70:
        stop = True
        
    if stop:
        message = "🎯 Excellente stabilité ! Vous pouvez terminer maintenant."
    elif 70 <= confidence < 85:
        message = "📊 Profil bien identifié. À vous de choisir."

    return {
        "stop": stop,
        "confidence": confidence,
        "trigramme": trigramme_actuel,
        "message": message
    }
