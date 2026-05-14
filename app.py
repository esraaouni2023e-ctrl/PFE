# app.py

from flask import Flask, request, jsonify
import pandas as pd
import json

# Importez votre service de recommandation
from recommender_service import (
    recommander_filieres_contextuel,
    load_filiere_data,
    trouver_filiere_actuelle,
    diagnostiquer_filiere_actuelle,
    generer_resume_orientation,
    analyse_gap,
)

from early_stopping import evaluer_arret_precoce

app = Flask(__name__)

# Charger les données des filières une seule fois au démarrage de l'application
df_filieres_global = load_filiere_data()
print(f"[OK] {len(df_filieres_global)} filières chargées au démarrage.")


@app.route('/recommend', methods=['POST'])
def recommend():
    data = request.get_json()

    profil_etudiant = data.get('profil_etudiant')
    filiere_actuelle_nom = data.get('filiere_actuelle', None)
    top_n = data.get('top_n', 12)

    if not profil_etudiant:
        return jsonify({'error': 'profil_etudiant est requis'}), 400

    # Rechercher la filière actuelle dans le DataFrame
    filiere_actuelle_row = None
    if filiere_actuelle_nom:
        filiere_actuelle_row = trouver_filiere_actuelle(df_filieres_global, filiere_actuelle_nom)

    recommandations = recommander_filieres_contextuel(
        df_filieres=df_filieres_global,
        profil_etudiant=profil_etudiant,
        filiere_actuelle_row=filiere_actuelle_row,
        top_n=top_n,
    )

    # Générer le résumé d'orientation
    resume = generer_resume_orientation(recommandations, profil_etudiant, filiere_actuelle_row)

    # Diagnostic de la filière actuelle si fournie
    diagnostic = None
    if filiere_actuelle_row is not None:
        diagnostic = diagnostiquer_filiere_actuelle(filiere_actuelle_row, profil_etudiant)

    return jsonify({
        'recommandations': recommandations.to_dict(orient="records"),
        'resume': resume,
        'diagnostic': diagnostic,
        'total_filieres': len(df_filieres_global),
    })


@app.route('/check_stability', methods=['POST'])
def check_stability():
    data = request.get_json()
    scores_history = data.get('scores_history', [])
    block_index = data.get('block_index', 0)
    
    if not isinstance(scores_history, list):
        return jsonify({'error': 'scores_history doit être une liste'}), 400
        
    result = evaluer_arret_precoce(scores_history, block_index)
    return jsonify(result)

@app.route('/health', methods=['GET'])
def health():
    return jsonify({
        'status': 'ok',
        'filieres_chargees': len(df_filieres_global),
    })


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
