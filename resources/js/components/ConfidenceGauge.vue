<template>
    <div class="confidence-gauge-container">
        <div class="gauge-wrapper">
            <svg viewBox="0 0 100 50" class="gauge-svg">
                <!-- Arrière-plan de la jauge (gris) -->
                <path d="M 10 50 A 40 40 0 0 1 90 50" fill="none" stroke="#e0e0e0" stroke-width="10" stroke-linecap="round" />
                
                <!-- Remplissage dynamique -->
                <path d="M 10 50 A 40 40 0 0 1 90 50" fill="none" :stroke="color" stroke-width="10" stroke-linecap="round" 
                      :stroke-dasharray="circumference" :stroke-dashoffset="dashOffset" class="gauge-fill" />
            </svg>
            <div class="gauge-text">
                <span class="gauge-value" :style="{ color: color }">{{ score }}%</span>
            </div>
        </div>
        <p class="gauge-message">{{ message }}</p>
    </div>
</template>

<script>
export default {
    name: 'ConfidenceGauge',
    props: {
        score: {
            type: Number,
            required: true,
            default: 0
        },
        message: {
            type: String,
            default: 'Analyse en cours...'
        }
    },
    computed: {
        circumference() {
            return Math.PI * 40; // Rayon = 40
        },
        dashOffset() {
            // Un offset de 0 = 100%, un offset de circumference = 0%
            return this.circumference - (this.score / 100) * this.circumference;
        },
        color() {
            if (this.score >= 85) return '#22c55e'; // Vert
            if (this.score >= 70) return '#eab308'; // Jaune/Orange
            return '#3b82f6'; // Bleu (en cours)
        }
    }
}
</script>

<style scoped>
.confidence-gauge-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    margin-top: 1.5rem;
    border: 1px solid #f1f5f9;
}

.gauge-wrapper {
    position: relative;
    width: 150px;
    height: 75px;
}

.gauge-svg {
    width: 100%;
    height: 100%;
    overflow: visible;
}

.gauge-fill {
    transition: stroke-dashoffset 1s ease-in-out, stroke 0.5s ease;
}

.gauge-text {
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 100%;
    text-align: center;
}

.gauge-value {
    font-size: 1.5rem;
    font-weight: 700;
}

.gauge-message {
    margin-top: 1rem;
    font-size: 0.95rem;
    color: #475569;
    text-align: center;
    font-weight: 500;
}
</style>
