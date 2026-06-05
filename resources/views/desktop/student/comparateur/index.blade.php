@extends('layouts.student')
@section('title', 'Comparateur de Filières')

@section('content')
<style>
.cp-container {
    font-family: var(--font-main), sans-serif;
    color: var(--ink);
    padding: 2rem 2rem 5rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* Header */
.cp-header {
    margin-bottom: 2.5rem;
    border-bottom: 1px solid var(--warm);
    padding-bottom: 1.5rem;
}
.cp-eyebrow {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--accent);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}
.cp-title {
    font-family: var(--font-serif), serif;
    font-size: 2.5rem;
    font-weight: 300;
    letter-spacing: -0.03em;
    line-height: 1.2;
    margin-bottom: 0.5rem;
}
.cp-title em {
    font-style: italic;
    color: var(--accent);
}
.cp-sub {
    font-size: 0.95rem;
    color: var(--ink60);
    line-height: 1.6;
}

/* Slots for selection */
.cp-selector-section {
    margin-bottom: 2.5rem;
}
.cp-slots-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}
@media(max-width: 1100px) {
    .cp-slots-grid { grid-template-columns: repeat(2, 1fr); }
}
@media(max-width: 600px) {
    .cp-slots-grid { grid-template-columns: 1fr; }
}

.cp-slot-card {
    background: var(--paper);
    border: 1px dashed var(--ink15);
    border-radius: var(--rl);
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    transition: all var(--transition);
    position: relative;
}
.cp-slot-card.active {
    border-style: solid;
    border-color: var(--warm);
    box-shadow: var(--shadow-card);
}
.cp-slot-label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--ink30);
}

/* Search autocomplete wrappers */
.cp-search-wrapper {
    position: relative;
    width: 100%;
}
.cp-slot-search-input {
    width: 100%;
    padding: 0.625rem 0.875rem;
    background: var(--cream);
    border: 1px solid var(--warm);
    border-radius: var(--r);
    color: var(--ink);
    font-size: 0.85rem;
    transition: all var(--transition);
}
.cp-slot-search-input:focus {
    outline: none;
    border-color: var(--accent);
    background: var(--paper);
}

.cp-suggestions-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 50;
    background: var(--paper);
    border: 1px solid var(--warm);
    border-radius: var(--r);
    max-height: 280px;
    overflow-y: auto;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    display: none;
    margin-top: 4px;
}
.cp-suggestion-item {
    padding: 0.625rem 0.875rem;
    font-size: 0.82rem;
    cursor: pointer;
    border-bottom: 1px solid rgba(0,0,0,0.03);
    line-height: 1.3;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--ink);
    transition: background var(--transition);
}
.cp-suggestion-item:hover {
    background: var(--cream);
    color: var(--accent);
}
.cp-suggestion-item.manual-item {
    font-weight: 600;
    color: var(--accent);
    background: rgba(234, 88, 12, 0.03);
    border-top: 1px dashed var(--warm);
}
.cp-suggestion-item.manual-item:hover {
    background: rgba(234, 88, 12, 0.08);
}

/* Selected preview info inside card */
.cp-preview-box {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 0.25rem;
    animation: fadeIn 0.3s ease;
}
.cp-preview-header {
    display: flex;
    align-items: center;
    gap: 0.625rem;
}
.cp-preview-icon {
    font-size: 1.5rem;
    width: 38px;
    height: 38px;
    background: var(--cream);
    border: 1px solid var(--warm);
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
}
.cp-preview-title {
    font-size: 0.875rem;
    font-weight: 600;
    line-height: 1.3;
    color: var(--ink);
}
.cp-preview-meta {
    font-size: 0.78rem;
    color: var(--ink60);
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}
.cp-preview-btn-clear {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    background: transparent;
    border: none;
    color: var(--ink30);
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all var(--transition);
}
.cp-preview-btn-clear:hover {
    color: var(--accent);
    background: rgba(234, 88, 12, 0.08);
}

/* Empty placeholder inside slot */
.cp-empty-placeholder {
    height: 90px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ink30);
    font-size: 0.8rem;
    font-style: italic;
    border-radius: var(--r);
    background: rgba(30, 41, 59, 0.02);
}

/* Compare action box */
.cp-action-bar {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1.5rem;
}
.cp-btn-compare {
    padding: 0.875rem 2.5rem;
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: var(--r);
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(234, 88, 12, 0.3);
    transition: all var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.625rem;
}
.cp-btn-compare:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(234, 88, 12, 0.4);
}
.cp-btn-compare:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Results Section Layout */
.cp-results-section {
    display: none;
    animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.cp-grid-primary {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}
@media(max-width: 950px) {
    .cp-grid-primary { grid-template-columns: 1fr; }
}

/* Panel cards */
.cp-card-panel {
    background: var(--paper);
    border: 1px solid var(--warm);
    border-radius: var(--rl);
    box-shadow: var(--shadow-card);
    overflow: hidden;
}
.cp-card-panel-head {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--warm);
    background: var(--cream);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.cp-card-panel-title {
    font-family: var(--font-serif), serif;
    font-size: 1.15rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.cp-card-panel-body {
    padding: 1.5rem;
}

/* Chart Canvas Wrapper */
.cp-chart-container {
    position: relative;
    height: 380px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cp-custom-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    justify-content: center;
    margin-top: 1.25rem;
    padding-top: 1rem;
    border-top: 1px solid var(--warm);
}
.cp-legend-item {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
}
.cp-legend-color {
    width: 12px;
    height: 12px;
    border-radius: 3px;
}

/* Summary Cards right side */
.cp-summary-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.cp-summary-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    background: var(--cream);
    border: 1px solid var(--warm);
    border-radius: var(--r);
    transition: all var(--transition);
}
.cp-summary-item:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}
.cp-summary-icon {
    font-size: 1.75rem;
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    background: var(--paper);
    border: 1px solid var(--warm);
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
}
.cp-summary-content {
    flex: 1;
    min-width: 0;
}
.cp-summary-name {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--ink);
    line-height: 1.3;
    margin-bottom: 0.25rem;
}
.cp-summary-meta {
    font-size: 0.75rem;
    color: var(--ink60);
}
.cp-summary-score-badge {
    text-align: right;
    flex-shrink: 0;
}
.cp-summary-score-value {
    font-family: var(--font-serif), serif;
    font-size: 1.5rem;
    font-weight: 600;
}
.cp-summary-score-label {
    font-size: 0.625rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--ink30);
}

/* Analysis: Forces & Vigilance */
.cp-analysis-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.25rem;
    margin-bottom: 2rem;
}
.cp-analysis-card {
    background: var(--paper);
    border: 1px solid var(--warm);
    border-radius: var(--rl);
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    box-shadow: var(--shadow-card);
}
.cp-analysis-title-wrap {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border-bottom: 1px solid var(--warm);
    padding-bottom: 0.75rem;
}
.cp-analysis-icon {
    font-size: 1.25rem;
}
.cp-analysis-name {
    font-weight: 600;
    font-size: 0.9rem;
    line-height: 1.3;
}
.cp-analysis-lists {
    display: flex;
    flex-direction: column;
    gap: 0.875rem;
    flex: 1;
}
.cp-analysis-list-title {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}
.cp-analysis-list-title.green { color: #10B981; }
.cp-analysis-list-title.amber { color: #F59E0B; }

.cp-analysis-ul {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
    padding-left: 0.25rem;
}
.cp-analysis-li {
    font-size: 0.78rem;
    line-height: 1.4;
    color: var(--ink60);
    display: flex;
    align-items: flex-start;
    gap: 0.35rem;
}
.cp-analysis-li::before {
    content: '•';
    font-weight: bold;
    flex-shrink: 0;
}
.cp-analysis-li.green::before { color: #10B981; }
.cp-analysis-li.amber::before { color: #F59E0B; }

/* Table styling */
.cp-table-wrap {
    overflow-x: auto;
    margin-bottom: 2rem;
}
.cp-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
    text-align: left;
}
.cp-table th {
    padding: 1rem 1.25rem;
    background: var(--cream);
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--ink30);
    border-bottom: 2px solid var(--warm);
}
.cp-table td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--warm);
    vertical-align: middle;
}
.cp-table tr:hover td {
    background: rgba(30, 41, 59, 0.01);
}
.cp-table-col-criterion {
    font-weight: 600;
    color: var(--ink60);
    width: 220px;
    background: var(--cream);
}
.cp-table-header-cell {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    min-width: 180px;
}
.cp-table-header-icon {
    width: 32px;
    height: 32px;
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.cp-table-header-text {
    font-size: 0.8rem;
    font-weight: 600;
    line-height: 1.3;
}

/* Custom progress bars inside table */
.cp-bar-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.cp-bar-track {
    flex: 1;
    height: 6px;
    background: var(--warm);
    border-radius: var(--rx);
    overflow: hidden;
}
.cp-bar-fill {
    height: 100%;
    border-radius: var(--rx);
    transition: width 0.8s ease;
}
.cp-bar-value {
    font-family: var(--font-serif), serif;
    font-size: 0.9rem;
    font-weight: 600;
    width: 40px;
    text-align: right;
}

/* Placeholder card */
.cp-placeholder-card {
    text-align: center;
    padding: 5rem 2rem;
    background: var(--paper);
    border: 1px dashed var(--ink15);
    border-radius: var(--rl);
    box-shadow: var(--shadow-card);
    transition: all var(--transition);
}
.cp-placeholder-icon-wrap {
    font-size: 4rem;
    color: var(--ink30);
    margin-bottom: 1rem;
    animation: float 4s ease-in-out infinite;
}
.cp-placeholder-title {
    font-family: var(--font-serif), serif;
    font-size: 1.75rem;
    font-weight: 300;
    letter-spacing: -0.02em;
    margin-bottom: 0.5rem;
}
.cp-placeholder-sub {
    font-size: 0.9rem;
    color: var(--ink60);
    line-height: 1.6;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes slideUp {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
    100% { transform: translateY(0px); }
}
</style>

<div class="cp-container">

    <!-- Header Section -->
    <div class="cp-header">
        <div class="cp-eyebrow">
            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z' /></svg>
            Aide à la décision
        </div>
        <h1 class="cp-title">Comparer des <em>filières</em></h1>
        <p class="cp-sub">Saisissez manuellement ou recherchez 2 à 4 filières dans notre base nationale pour comparer l'adéquation, l'accessibilité et les débouchés.</p>
    </div>

    <!-- Selection Grid -->
    <div class="cp-selector-section">
        <div class="cp-slots-grid" id="slotsGrid">
            @for($i = 1; $i <= 4; $i++)
            <div class="cp-slot-card" id="slot-card-{{ $i }}">
                <span class="cp-slot-label">Choix {{ $i }} {!! $i <= 2 ? '<span style="color:var(--accent);">*</span>' : '(Optionnel)' !!}</span>
                
                <div class="cp-search-wrapper">
                    <input type="text" 
                           id="search-input-{{ $i }}" 
                           class="cp-slot-search-input" 
                           placeholder="Écrire ou rechercher une filière..." 
                           oninput="triggerSearch({{ $i }})"
                           onfocus="triggerSearch({{ $i }})"
                           autocomplete="off">
                           
                    <!-- Suggestions Box -->
                    <div class="cp-suggestions-list" id="suggestions-{{ $i }}"></div>
                    
                    <!-- Hidden input storing composite ID -->
                    <input type="hidden" id="select-f{{ $i }}" value="">
                </div>

                <!-- Preview container -->
                <div id="preview-container-{{ $i }}">
                    <div class="cp-empty-placeholder">Emplacement vide</div>
                </div>
            </div>
            @endfor
        </div>

        <div class="cp-action-bar">
            <button class="cp-btn-compare" id="comparerBtn" onclick="lancerComparaison()">
                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='currentColor' style='width:1.1rem;height:1.1rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5' /></svg>
                Lancer la comparaison
            </button>
        </div>
    </div>

    <!-- Results Section -->
    <div class="cp-results-section" id="cpResults">
        <div class="cp-grid-primary">
            <!-- Radar Chart -->
            <div class="cp-card-panel">
                <div class="cp-card-panel-head">
                    <h2 class="cp-card-panel-title">
                        <span style="color:var(--accent)">{!! get_pro_icon('📊') !!}</span>
                        Dimensions comparatives
                    </h2>
                </div>
                <div class="cp-card-panel-body">
                    <div class="cp-chart-container">
                        <canvas id="radarChart"></canvas>
                    </div>
                    <div id="radarLegend" class="cp-custom-legend"></div>
                </div>
            </div>

            <!-- Summary list -->
            <div class="cp-card-panel">
                <div class="cp-card-panel-head">
                    <h2 class="cp-card-panel-title">
                        <span>{!! get_pro_icon('🎯') !!}</span>
                        Résumé des filières
                    </h2>
                </div>
                <div class="cp-card-panel-body">
                    <div class="cp-summary-list" id="summaryCards"></div>
                </div>
            </div>
        </div>

        <!-- Forces & Vigilance analysis -->
        <div class="cp-card-panel" style="margin-bottom: 2rem;">
            <div class="cp-card-panel-head">
                <h2 class="cp-card-panel-title">
                    <span>{!! get_pro_icon('🌟') !!}</span>
                    Analyse des Forces & Points de Vigilance
                </h2>
            </div>
            <div class="cp-card-panel-body">
                <div class="cp-analysis-grid" id="analysisGrid"></div>
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="cp-card-panel">
            <div class="cp-card-panel-head">
                <h2 class="cp-card-panel-title">
                    <span>{!! get_pro_icon('📋') !!}</span>
                    Tableau comparatif détaillé
                </h2>
            </div>
            <div class="cp-card-panel-body" style="padding:0;">
                <div class="cp-table-wrap">
                    <table class="cp-table" id="cpTable">
                        <thead id="cpTableHead"></thead>
                        <tbody id="cpTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Initial Placeholder -->
    <div class="cp-placeholder-card" id="cpPlaceholder">
        <div class="cp-placeholder-icon-wrap">
            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' style='width:4rem;height:4rem;display:inline-block;'><path stroke-linecap='round' stroke-linejoin='round' d='M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z' /><path stroke-linecap='round' stroke-linejoin='round' d='M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z' /></svg>
        </div>
        <h2 class="cp-placeholder-title">Prêt à comparer les filières</h2>
        <p class="cp-placeholder-sub">Saisissez au moins deux filières obligatoires ci-dessus,<br>puis lancez le comparateur pour obtenir l'analyse visuelle et textuelle.</p>
    </div>

</div>

<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    
    function getProIconJs(emoji, extraClass = '') {
        const mapping = {
            '🎓': 'bi bi-mortarboard',
            '🏫': 'bi bi-bank',
            '💻': 'bi bi-laptop',
            '🖥️': 'bi bi-pc-display',
            '🤖': 'bi bi-robot',
            '🔒': 'bi bi-shield-lock',
            '🛡️': 'bi bi-shield',
            '🩺': 'bi bi-heart-pulse',
            '🏥': 'bi bi-hospital',
            '🏗️': 'bi bi-building-gear',
            '⚙️': 'bi bi-gear-fill',
            '📈': 'bi bi-graph-up-arrow',
            '📊': 'bi bi-bar-chart-line',
            '💼': 'bi bi-briefcase',
            '🔬': 'bi bi-virus',
            '📖': 'bi bi-book',
            '📚': 'bi bi-book-half',
            '⚖️': 'bi bi-scale',
            '🎨': 'bi bi-palette',
            '✈️': 'bi bi-airplane',
            '🌾': 'bi bi-flower1',
            '⚽': 'bi bi-trophy',
            '⏱️': 'bi bi-clock',
            '⏱': 'bi bi-clock',
            '📍': 'bi bi-geo-alt',
            '💰': 'bi bi-cash-coin',
            '📝': 'bi bi-file-earmark-text',
            '📋': 'bi bi-clipboard-data',
            '🏢': 'bi bi-building',
            '⭐': 'bi bi-star-fill',
            '🌟': 'bi bi-stars',
            '💡': 'bi bi-lightbulb',
            '🔮': 'bi bi-magic',
            '🧮': 'bi bi-calculator',
            '🚀': 'bi bi-rocket-takeoff',
            '🎯': 'bi bi-target',
            '➕': 'bi bi-plus-lg',
            '🏛️': 'bi bi-building',
            '🏷️': 'bi bi-tags',
            '📂': 'bi bi-folder2-open',
        };

        const trimmed = (emoji || '').trim();
        if (mapping[trimmed]) {
            return `<i class="${mapping[trimmed]} ${extraClass}"></i>`;
        }
        if (trimmed.startsWith('bi ') || trimmed.startsWith('bi-')) {
            return `<i class="${trimmed} ${extraClass}"></i>`;
        }
        return trimmed || '<i class="bi bi-mortarboard"></i>';
    }
    
    // Theme-friendly curated palette: Orange, Sky Blue, Emerald Green, Purple
    const COLORS = ['#EA580C', '#0EA5E9', '#10B981', '#8B5CF6'];
    const DEFAULT_FORMATIONS = @json($formations);
    let radarInstance = null;
    let searchTimeouts = {};

    // Close suggestions list when clicking away
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.cp-search-wrapper')) {
            document.querySelectorAll('.cp-suggestions-list').forEach(list => list.style.display = 'none');
        }
    });

    // Triggers search autocompletion
    window.triggerSearch = function(index) {
        const input = document.getElementById(`search-input-${index}`);
        const suggestions = document.getElementById(`suggestions-${index}`);
        const query = input.value.trim();

        clearTimeout(searchTimeouts[index]);

        const selectedValues = [1, 2, 3, 4].map(i => document.getElementById(`select-f${i}`).value);

        if (query.length === 0) {
            // Show default popular formations!
            let html = '<div style="padding:0.5rem 0.875rem;font-size:0.7rem;font-weight:700;text-transform:uppercase;color:var(--ink30);border-bottom:1px solid var(--warm);">Filières suggérées</div>';
            let count = 0;

            DEFAULT_FORMATIONS.forEach((item) => {
                const val = 'formation:' + item.id;
                if (selectedValues.includes(val)) return;
                count++;
                
                html += `
                    <div class="cp-suggestion-item" onclick="selectItem('${val}', '${item.icon}', '${item.nom.replace(/'/g, "\\'")}', '${item.etablissement.replace(/'/g, "\\'")}', '${item.niveau}', 'Tunis', '3 ans', ${index})">
                        <span>${getProIconJs(item.icon)}</span>
                        <div style="flex:1;">
                            <strong>${item.nom}</strong>
                            <div style="font-size:0.72rem;color:var(--ink60);">${item.etablissement} · ${item.niveau}</div>
                        </div>
                    </div>
                `;
            });

            suggestions.innerHTML = html;
            suggestions.style.display = 'block';
            return;
        }

        if (query.length < 2) {
            suggestions.innerHTML = `
                <div class="cp-suggestion-item manual-item" onclick="selectManual('${query.replace(/'/g, "\\'")}', ${index})">
                    <span>${getProIconJs('➕')}</span>
                    <div>
                        <strong>Comparer la saisie libre :</strong>
                        <div style="font-size:0.75rem;color:var(--ink60);">${query}</div>
                    </div>
                </div>
            `;
            suggestions.style.display = 'block';
            return;
        }

        searchTimeouts[index] = setTimeout(async () => {
            try {
                const res = await fetch(`{{ route('student.comparateur.search') }}?q=${encodeURIComponent(query)}`);
                const data = await res.json();

                let html = '';
                
                data.forEach((item) => {
                    if (selectedValues.includes(item.value)) return;
                    
                    html += `
                        <div class="cp-suggestion-item" onclick="selectItem('${item.value}', '${item.icon}', '${item.label.replace(/'/g, "\\'")}', '${item.etab.replace(/'/g, "\\'")}', '${item.niveau}', '${item.ville}', '${item.duree}', ${index})">
                            <span>${getProIconJs(item.icon)}</span>
                            <div style="flex:1;">
                                <strong>${item.label.split(' (')[0]}</strong>
                                <div style="font-size:0.72rem;color:var(--ink60);">${item.etab} · ${item.niveau} · ${item.ville}</div>
                            </div>
                        </div>
                    `;
                });

                html += `
                    <div class="cp-suggestion-item manual-item" onclick="selectManual('${query.replace(/'/g, "\\'")}', ${index})">
                        <span>${getProIconJs('➕')}</span>
                        <div>
                            <strong>Comparer la saisie libre :</strong>
                            <div style="font-size:0.75rem;color:var(--ink60);">${query}</div>
                        </div>
                    </div>
                `;

                suggestions.innerHTML = html;
                suggestions.style.display = 'block';
            } catch (e) {
                console.error('Error fetching suggestions:', e);
            }
        }, 300);
    };

    window.selectItem = function(value, icon, label, etab, niveau, ville, duree, index) {
        const hiddenInput = document.getElementById(`select-f${index}`);
        const searchInput = document.getElementById(`search-input-${index}`);
        const suggestions = document.getElementById(`suggestions-${index}`);
        
        hiddenInput.value = value;
        // Clean label if it has trailing tags
        searchInput.value = label.replace(/\[.*\]$/, '').replace(/^[^\s]+\s/, '').trim(); 
        suggestions.style.display = 'none';
        
        updatePreviewForIndex(index, icon, searchInput.value, etab, niveau, ville, duree);
        applyOptionExclusion();
    };

    window.selectManual = function(query, index) {
        const hiddenInput = document.getElementById(`select-f${index}`);
        const searchInput = document.getElementById(`search-input-${index}`);
        const suggestions = document.getElementById(`suggestions-${index}`);
        
        hiddenInput.value = `manual:${query}`;
        searchInput.value = query;
        suggestions.style.display = 'none';
        
        updatePreviewForIndex(index, 'bi bi-plus-circle', query, 'Saisie manuelle', 'Simulation', 'Tunisie', 'N/A');
        applyOptionExclusion();
    };

    function updatePreviewForIndex(index, icon, name, etab, niveau, ville, duree) {
        const card = document.getElementById(`slot-card-${index}`);
        const container = document.getElementById(`preview-container-${index}`);
        
        card.classList.add('active');
        container.innerHTML = `
            <div class="cp-preview-box">
                <div class="cp-preview-header">
                    <div class="cp-preview-icon">${getProIconJs(icon)}</div>
                    <div class="cp-preview-title">${name}</div>
                </div>
                <div class="cp-preview-meta">
                    <span>${getProIconJs('🏛️')} ${etab}</span>
                    <span>${getProIconJs('📍')} ${ville} (${duree})</span>
                </div>
                <button class="cp-preview-btn-clear" onclick="clearSlot(${index})" title="Désélectionner">
                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1.1rem;height:1.1rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M6 18L18 6M6 6l12 12' /></svg>
                </button>
            </div>
        `;
    }

    window.clearSlot = function(index) {
        const hiddenInput = document.getElementById(`select-f${index}`);
        const searchInput = document.getElementById(`search-input-${index}`);
        const card = document.getElementById(`slot-card-${index}`);
        const container = document.getElementById(`preview-container-${index}`);
        
        hiddenInput.value = "";
        searchInput.value = "";
        card.classList.remove('active');
        container.innerHTML = `<div class="cp-empty-placeholder">Emplacement vide</div>`;
        
        applyOptionExclusion();
    };

    function applyOptionExclusion() {
        // Option exclusion is naturally handled in Autocomplete render loop by filtering values,
        // but we trigger updates to keep lists clean.
    }

    // Submit selected IDs and generate comparison
    window.lancerComparaison = async function() {
        const ids = [1, 2, 3, 4]
            .map(i => document.getElementById(`select-f${i}`).value)
            .filter(v => v !== '');

        if (ids.length < 2) {
            alert('Veuillez sélectionner au moins 2 formations à comparer (Choix 1 et Choix 2 sont obligatoires).');
            return;
        }

        const btn = document.getElementById('comparerBtn');
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="animate-spin" xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='currentColor' style='width:1.1rem;height:1.1rem;display:inline-block;vertical-align:middle;margin-right:4px;'><path stroke-linecap='round' stroke-linejoin='round' d='M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99' /></svg>
            Génération du comparatif...
        `;

        try {
            const res = await fetch('{{ route('student.comparateur.data') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ ids }),
            });
            const data = await res.json();

            if (!data.success) throw new Error(data.message);

            renderRadar(data.formations);
            renderSummary(data.formations);
            renderAnalysis(data.formations);
            renderTable(data.formations);

            document.getElementById('cpPlaceholder').style.display = 'none';
            document.getElementById('cpResults').style.display = 'block';
            
            document.getElementById('cpResults').scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch(e) {
            alert('Erreur lors du chargement : ' + e.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = `
                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='currentColor' style='width:1.1rem;height:1.1rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5' /></svg>
                Lancer la comparaison
            `;
        }
    };

    function renderRadar(formations) {
        const labels = ['Compatibilité RIASEC', 'Niveau Salaire', 'Rapidité d\'études', 'Insertion pro', 'Accessibilité BAC'];
        const datasets = formations.map((f, i) => ({
            label: f.nom.length > 25 ? f.nom.substring(0, 25) + '...' : f.nom,
            data: [f.radar.matching, f.radar.salaire, f.radar.rapidite, f.radar.insertion, f.radar.accessibilite],
            backgroundColor: COLORS[i] + '18',
            borderColor: COLORS[i],
            pointBackgroundColor: COLORS[i],
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: COLORS[i],
            borderWidth: 2.5,
            pointRadius: 4.5,
            pointHoverRadius: 6,
        }));

        if (radarInstance) radarInstance.destroy();

        const ctx = document.getElementById('radarChart');
        
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(30, 41, 59, 0.08)';
        const angleLineColor = isDark ? 'rgba(255, 255, 255, 0.12)' : 'rgba(30, 41, 59, 0.12)';
        const textColor = isDark ? 'rgba(241, 245, 249, 0.8)' : 'rgba(30, 41, 59, 0.8)';

        radarInstance = new Chart(ctx, {
            type: 'radar',
            data: { labels, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: gridColor },
                        angleLines: { color: angleLineColor },
                        pointLabels: { 
                            font: { size: 10.5, family: "'DM Sans', sans-serif", weight: '600' }, 
                            color: textColor 
                        },
                        ticks: { display: false },
                    }
                },
                plugins: { 
                    legend: { display: false } 
                },
            },
        });

        const legend = document.getElementById('radarLegend');
        legend.innerHTML = formations.map((f, i) => `
            <div class="cp-legend-item" style="color:${COLORS[i]}">
                <div class="cp-legend-color" style="background:${COLORS[i]}"></div>
                <span>${getProIconJs(f.icon)} ${f.nom.length > 20 ? f.nom.substring(0, 20) + '...' : f.nom}</span>
            </div>
        `).join('');
    }

    function renderSummary(formations) {
        const container = document.getElementById('summaryCards');
        container.innerHTML = formations.map((f, i) => `
            <div class="cp-summary-item" style="border-left: 4px solid ${COLORS[i]}">
                <div class="cp-summary-icon">${getProIconJs(f.icon)}</div>
                <div class="cp-summary-content">
                    <div class="cp-summary-name">${f.nom}</div>
                    <div class="cp-summary-meta">
                        <span>${getProIconJs('🏛️')} ${f.etablissement} · ${f.niveau}</span>
                        <span style="display:block;margin-top:2px;">${getProIconJs('💰')} Rémunération : ${f.salaire_min} - ${f.salaire_max} DT / mois</span>
                    </div>
                </div>
                <div class="cp-summary-score-badge" style="color:${COLORS[i]}">
                    <div class="cp-summary-score-value">${f.radar.matching}%</div>
                    <div class="cp-summary-score-label">Holland Match</div>
                </div>
            </div>
        `).join('');
    }

    function renderAnalysis(formations) {
        const container = document.getElementById('analysisGrid');
        
        container.innerHTML = formations.map((f, i) => {
            const forces = [];
            const vigilance = [];

            if (f.radar.matching >= 85) {
                forces.push("Excellente adéquation psychométrique avec vos intérêts professionnels.");
            } else if (f.radar.matching < 70) {
                vigilance.push("Adéquation psychométrique modérée (éventuels écarts avec vos traits RIASEC forts).");
            }

            if (f.radar.salaire >= 65) {
                forces.push(`Excellent niveau de rémunération estimé (${f.salaire_max} DT max).`);
            } else if (f.radar.salaire < 40) {
                vigilance.push("Rémunérations de début de carrière modérées.");
            }

            if (f.radar.rapidite >= 80) {
                forces.push("Formation courte (2 ou 3 ans), permettant une insertion rapide sur le marché.");
            } else if (f.radar.rapidite <= 40) {
                vigilance.push("Études longues (5 ans ou plus), exigeant un investissement de temps conséquent.");
            }

            if (f.radar.insertion >= 85) {
                forces.push("Taux d'employabilité et d'insertion professionnelle post-diplôme exceptionnel.");
            }

            if (f.radar.accessibilite >= 75) {
                forces.push(`Très accessible avec votre score académique actuel (Seuil estimé à ${f.sdo_estime}).`);
            } else if (f.radar.accessibilite < 50) {
                vigilance.push(`Sélection académique exigeante (Seuil requis estimé à ${f.sdo_estime} ; risque d'orientation).`);
            }

            if (forces.length === 0) forces.push("Profil de formation équilibré.");
            if (vigilance.length === 0) vigilance.push("Aucun point de vigilance majeur identifié pour votre profil.");

            return `
                <div class="cp-analysis-card" style="border-top: 4px solid ${COLORS[i]}">
                    <div class="cp-analysis-title-wrap">
                        <span class="cp-analysis-icon">${getProIconJs(f.icon)}</span>
                        <div class="cp-analysis-name" style="color:${COLORS[i]}">${f.nom}</div>
                    </div>
                    
                    <div class="cp-analysis-lists">
                        <div>
                            <div class="cp-analysis-list-title green">
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='currentColor' style='width:0.95rem;height:0.95rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z' /></svg>
                                Points Forts
                            </div>
                            <ul class="cp-analysis-ul">
                                ${forces.map(item => `<li class="cp-analysis-li green">${item}</li>`).join('')}
                            </ul>
                        </div>

                        <div>
                            <div class="cp-analysis-list-title amber">
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='currentColor' style='width:0.95rem;height:0.95rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z' /></svg>
                                Vigilances
                            </div>
                            <ul class="cp-analysis-ul">
                                ${vigilance.map(item => `<li class="cp-analysis-li amber">${item}</li>`).join('')}
                            </ul>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function renderTable(formations) {
        const metrics = [
            { key: 'matching',     label: `${getProIconJs('🎯')} Compatibilité RIASEC` },
            { key: 'salaire',      label: `${getProIconJs('💰')} Potentiel Salarial` },
            { key: 'rapidite',     label: `${getProIconJs('⏱️')} Vitesse d'Études` },
            { key: 'insertion',    label: `${getProIconJs('🚀')} Insertion Professionnelle` },
            { key: 'accessibilite',label: `${getProIconJs('🔒')} Accessibilité d'Admission` },
        ];

        document.getElementById('cpTableHead').innerHTML = `<tr>
            <th class="cp-table-col-criterion">Critères Généraux</th>
            ${formations.map((f, i) => `
                <th>
                    <div class="cp-table-header-cell">
                        <div class="cp-table-header-icon" style="background:${COLORS[i]}15;border:1px solid ${COLORS[i]}30;color:${COLORS[i]}">${getProIconJs(f.icon)}</div>
                        <div class="cp-table-header-text" style="color:${COLORS[i]}">${f.nom}</div>
                    </div>
                </th>
            `).join('')}
        </tr>`;

        const infoRows = [
            { label: `${getProIconJs('🏛️')} Établissement`,  fn: f => f.etablissement },
            { label: `${getProIconJs('📍')} Localisation`,    fn: f => f.ville },
            { label: `${getProIconJs('🎓')} Diplôme Visé`,    fn: f => f.niveau },
            { label: `${getProIconJs('⏱️')} Durée théorique`, fn: f => f.duree },
            { label: `${getProIconJs('🏷️')} Domaine d'Études`, fn: f => f.domaine },
            { label: `${getProIconJs('💼')} Débouchés clés`,    fn: f => `<span style="font-size:0.78rem;line-height:1.3;display:block;">${f.debouches}</span>` },
            { label: `${getProIconJs('💰')} Rémunération min`,  fn: f => `${f.salaire_min} DT / mois` },
            { label: `${getProIconJs('💰')} Rémunération max`,  fn: f => `${f.salaire_max} DT / mois` },
            { label: `${getProIconJs('📂')} Secteur principal`, fn: f => f.secteur },
            { label: `${getProIconJs('🔒')} Seuil SDO estimé`,  fn: f => `<span class="font-semibold text-orange-600">${f.sdo_estime} DT</span>` }
        ];

        let tbody = infoRows.map(row => `
            <tr>
                <td class="cp-table-col-criterion">${row.label}</td>
                ${formations.map(f => `<td>${row.fn(f)}</td>`).join('')}
            </tr>
        `).join('');

        tbody += `<tr><td colspan="${formations.length+1}" style="height:12px;background:var(--cream);border-bottom: 1px solid var(--warm);"></td></tr>`;
        
        tbody += metrics.map(m => `
            <tr>
                <td class="cp-table-col-criterion">${m.label}</td>
                ${formations.map((f, i) => `
                    <td>
                        <div class="cp-bar-container">
                            <div class="cp-bar-track">
                                <div class="cp-bar-fill" style="width:${f.radar[m.key]}%;background:${COLORS[i]}"></div>
                            </div>
                            <div class="cp-bar-value" style="color:${COLORS[i]}">${f.radar[m.key]}%</div>
                        </div>
                    </td>
                `).join('')}
            </tr>
        `).join('');

        document.getElementById('cpTableBody').innerHTML = tbody;
    }
});
</script>
@endsection
