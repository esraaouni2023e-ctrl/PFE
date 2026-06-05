@extends('layouts.counselor')

@section('page-heading')
Mon<br><em>Agenda.</em>
@endsection

@section('page-subtitle')
Gérez vos rendez-vous d'orientation, planifiez des séances individuelles de coaching et suivez l'historique de vos entretiens d'accompagnement.
@endsection

@section('content')
<style>
    .ca-container {
        font-family: var(--font-main);
        display: flex;
        flex-direction: column;
        gap: 2rem;
        padding-bottom: 3rem;
    }

    /* Stats Row */
    .ca-stats-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
    }

    .ca-stat-card {
        background: var(--paper);
        border: 1px solid var(--ink10);
        border-radius: var(--rl);
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: transform .3s var(--ease), border-color .3s var(--ease);
    }

    .ca-stat-card:hover {
        transform: translateY(-2px);
        border-color: var(--accent);
    }

    .ca-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--r);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ca-stat-info {
        display: flex;
        flex-direction: column;
    }

    .ca-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.2;
    }

    .ca-stat-label {
        font-size: .78rem;
        color: var(--ink60);
        font-weight: 500;
    }

    /* Next Appointment Highlight */
    .ca-next-apt-card {
        background: var(--paper);
        border: 1px solid var(--ink10);
        border-left: 4px solid var(--accent);
        border-radius: var(--rl);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        position: relative;
        overflow: hidden;
    }

    .ca-next-apt-badge {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        background: rgba(255, 94, 0, 0.08);
        color: var(--accent);
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: .3rem .75rem;
        border-radius: var(--rx);
        border: 1px solid rgba(255, 94, 0, 0.2);
    }

    .ca-next-apt-title {
        font-size: .8rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ink60);
        letter-spacing: .05em;
        margin: 0;
    }

    .ca-next-apt-student {
        font-family: var(--font-serif);
        font-size: 1.5rem;
        font-weight: 300;
        color: var(--ink);
        margin: .25rem 0;
    }

    .ca-next-apt-student b {
        font-weight: 700;
    }

    .ca-next-apt-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        font-size: .88rem;
        color: var(--ink80);
    }

    .ca-meta-item {
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .ca-next-apt-notes {
        font-size: .88rem;
        color: var(--ink60);
        background: var(--cream);
        padding: .75rem 1rem;
        border-radius: var(--r);
        border: 1px solid var(--ink10);
        margin: .25rem 0;
    }

    .ca-next-apt-action {
        display: flex;
        justify-content: flex-end;
    }

    /* Main Workspace Layout */
    .ca-workspace {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 2rem;
    }

    @media (max-width: 900px) {
        .ca-workspace {
            grid-template-columns: 1fr;
        }
    }

    /* Timeline and Agenda List */
    .ca-panel {
        background: var(--paper);
        border: 1px solid var(--ink10);
        border-radius: var(--rl);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .ca-panel-title {
        font-family: var(--font-serif);
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--ink);
        margin: 0;
        display: flex;
        align-items: center;
        gap: .75rem;
        border-bottom: 1px solid var(--ink10);
        padding-bottom: 1rem;
    }

    .ca-timeline {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        position: relative;
        padding-left: 1.5rem;
    }

    .ca-timeline::before {
        content: '';
        position: absolute;
        left: 3px;
        top: .5rem;
        bottom: .5rem;
        width: 2px;
        background: var(--ink10);
    }

    .ca-timeline-group-title {
        font-size: .8rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ink60);
        letter-spacing: .05em;
        margin-left: -1.5rem;
        padding-left: .5rem;
        background: var(--paper);
        align-self: flex-start;
        z-index: 1;
    }

    .ca-timeline-item {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }

    .ca-timeline-dot {
        position: absolute;
        left: -1.5rem;
        top: .3rem;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--ink30);
        border: 2px solid var(--paper);
        box-shadow: 0 0 0 2px var(--ink10);
        transform: translateX(-3px);
        z-index: 2;
    }

    .ca-timeline-item.active .ca-timeline-dot {
        background: var(--accent);
        box-shadow: 0 0 0 2px rgba(255, 94, 0, 0.2);
    }

    .ca-timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }

    .ca-timeline-time {
        font-size: .88rem;
        font-weight: 700;
        color: var(--ink);
    }

    .ca-timeline-student {
        font-size: 1rem;
        font-weight: 600;
        color: var(--ink);
        text-decoration: none;
    }

    .ca-timeline-student:hover {
        color: var(--accent);
    }

    .ca-timeline-body {
        font-size: .82rem;
        color: var(--ink60);
        margin: 0;
    }

    .ca-status-pill {
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: .2rem .5rem;
        border-radius: var(--rx);
        display: inline-flex;
        align-items: center;
        gap: .25rem;
    }

    .ca-status-scheduled {
        background: rgba(0, 45, 107, 0.08);
        color: var(--accent2);
        border: 1px solid rgba(0, 45, 107, 0.2);
    }

    .ca-status-completed {
        background: rgba(16, 185, 129, 0.08);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .ca-status-cancelled {
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    /* Scheduling Form */
    .ca-form-group {
        display: flex;
        flex-direction: column;
        gap: .4rem;
        margin-bottom: 1.25rem;
    }

    .ca-label {
        font-size: .78rem;
        font-weight: 700;
        color: var(--ink);
        text-transform: uppercase;
        letter-spacing: .02em;
    }

    .ca-input, .ca-select, .ca-textarea {
        padding: .75rem 1rem;
        border: 1px solid var(--ink10);
        border-radius: var(--r);
        background: var(--cream);
        font-size: .88rem;
        font-family: var(--font-main);
        color: var(--ink);
        transition: var(--transition);
        width: 100%;
    }

    .ca-input:focus, .ca-select:focus, .ca-textarea:focus {
        outline: none;
        border-color: var(--accent);
        background: var(--paper);
    }

    .ca-textarea {
        resize: vertical;
        min-height: 100px;
    }

    /* Collapsible Past Appointments */
    .ca-details-past {
        background: var(--paper);
        border: 1px solid var(--ink10);
        border-radius: var(--rl);
        overflow: hidden;
    }

    .ca-details-past summary {
        padding: 1.25rem 1.5rem;
        font-family: var(--font-serif);
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--ink);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        user-select: none;
        list-style: none;
    }

    .ca-details-past summary::-webkit-details-marker {
        display: none;
    }

    .ca-details-past summary svg {
        transition: transform .3s ease;
    }

    .ca-details-past[open] summary svg {
        transform: rotate(180deg);
    }

    .ca-past-content {
        padding: 0 1.5rem 1.5rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        border-top: 1px solid var(--ink10);
        margin-top: 0;
        padding-top: 1.25rem;
    }

    .ca-past-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding-bottom: 1rem;
        border-bottom: 1px dashed var(--ink10);
        gap: 1rem;
    }

    .ca-past-item:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }

    .ca-past-info {
        display: flex;
        flex-direction: column;
        gap: .25rem;
    }

    .ca-past-student {
        font-size: .95rem;
        font-weight: 600;
        color: var(--ink);
        text-decoration: none;
    }

    .ca-past-student:hover {
        color: var(--accent);
    }

    .ca-past-date {
        font-size: .8rem;
        color: var(--ink60);
        font-weight: 500;
    }

    .ca-past-notes {
        font-size: .8rem;
        color: var(--ink60);
        margin: 0;
    }

    /* Dark Mode Overrides */
    [data-theme="dark"] .ca-stat-card,
    [data-theme="dark"] .ca-next-apt-card,
    [data-theme="dark"] .ca-panel,
    [data-theme="dark"] .ca-details-past {
        background: var(--ink06);
        border-color: var(--ink10);
    }

    [data-theme="dark"] .ca-timeline-group-title {
        background: var(--ink06);
    }

    [data-theme="dark"] .ca-next-apt-notes {
        background: var(--paper);
        border-color: var(--ink10);
    }

    [data-theme="dark"] .ca-input,
    [data-theme="dark"] .ca-select,
    [data-theme="dark"] .ca-textarea {
        background-color: var(--paper);
        border-color: var(--ink10);
    }
</style>

<div class="ca-container">

    <!-- A. Stats Row -->
    <div class="ca-stats-bar">
        <div class="ca-stat-card">
            <div class="ca-stat-icon" style="background: rgba(0, 45, 107, 0.08); color: var(--accent2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
            </div>
            <div class="ca-stat-info">
                <span class="ca-stat-value">{{ $stats['total'] }}</span>
                <span class="ca-stat-label">Total Sessions</span>
            </div>
        </div>

        <div class="ca-stat-card">
            <div class="ca-stat-icon" style="background: rgba(255, 94, 0, 0.08); color: var(--accent);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
            </div>
            <div class="ca-stat-info">
                <span class="ca-stat-value">{{ $stats['thisMonth'] }}</span>
                <span class="ca-stat-label">Ce Mois-ci</span>
            </div>
        </div>

        <div class="ca-stat-card">
            <div class="ca-stat-icon" style="background: rgba(0, 45, 107, 0.08); color: var(--accent2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="ca-stat-info">
                <span class="ca-stat-value">{{ $stats['upcoming'] }}</span>
                <span class="ca-stat-label">À Venir</span>
            </div>
        </div>

        <div class="ca-stat-card">
            <div class="ca-stat-icon" style="background: rgba(16, 185, 129, 0.08); color: #10b981;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="ca-stat-info">
                <span class="ca-stat-value">{{ $stats['completed'] }}</span>
                <span class="ca-stat-label">Effectués</span>
            </div>
        </div>
    </div>

    <!-- B. Next Appointment Highlight -->
    @if($stats['nextAppointment'])
        <div class="ca-next-apt-card">
            <span class="ca-next-apt-badge">Prochain rendez-vous</span>
            <h4 class="ca-next-apt-title">Session individuelle d'orientation</h4>
            <h2 class="ca-next-apt-student">
                Accompagnement de : <b>{{ $stats['nextAppointment']->student->name }}</b>
            </h2>

            <div class="ca-next-apt-meta">
                <div class="ca-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    <span>{{ $stats['nextAppointment']->scheduled_at->format('d F Y à H:i') }}</span>
                </div>
                <div class="ca-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>Format : Visioconférence</span>
                </div>
            </div>

            @if($stats['nextAppointment']->notes)
                <div class="ca-next-apt-notes">
                    <strong>Motif / Notes :</strong> {{ $stats['nextAppointment']->notes }}
                </div>
            @endif

            <div class="ca-next-apt-action">
                <a href="{{ route('counselor.student.show', $stats['nextAppointment']->student->id) }}" class="csl-btn-crm" style="text-decoration:none;">
                    Ouvrir le dossier étudiant
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    @endif

    <!-- C. Two-column Layout -->
    <div class="ca-workspace">
        <!-- Left Column: Upcoming Agenda -->
        <div class="ca-panel">
            <h3 class="ca-panel-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                Séances à venir
            </h3>

            @if($upcomingByWeek->count() > 0)
                <div class="ca-timeline">
                    @foreach($upcomingByWeek as $week => $apts)
                        <span class="ca-timeline-group-title">Semaine du {{ $week }}</span>
                        @foreach($apts as $apt)
                            <div class="ca-timeline-item {{ $stats['nextAppointment'] && $stats['nextAppointment']->id === $apt->id ? 'active' : '' }}">
                                <div class="ca-timeline-dot"></div>
                                <div class="ca-timeline-header">
                                    <div>
                                        <span class="ca-timeline-time">{{ $apt->scheduled_at->format('H:i') }}</span> ·
                                        <a href="{{ route('counselor.student.show', $apt->student->id) }}" class="ca-timeline-student">
                                            {{ $apt->student->name }}
                                        </a>
                                    </div>
                                    <span class="ca-status-pill ca-status-{{ $apt->status }}">
                                        {{ $apt->status === 'scheduled' ? 'Planifié' : ($apt->status === 'completed' ? 'Effectué' : 'Annulé') }}
                                    </span>
                                </div>
                                <p class="ca-timeline-body">
                                    {{ $apt->notes ?: 'Aucune note spécifique saisie.' }}
                                </p>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            @else
                <div style="text-align:center; padding: 3rem 1.5rem; color: var(--ink60);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:1rem; opacity:.5;"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    <p style="margin:0; font-weight:600;">Aucune séance planifiée</p>
                    <p style="margin:.25rem 0 0 0; font-size:.82rem;">Utilisez le formulaire ci-contre pour programmer un entretien.</p>
                </div>
            @endif
        </div>

        <!-- Right Column: Scheduling Form -->
        <div class="ca-panel">
            <h3 class="ca-panel-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M10 16h4"/><path d="M12 14v4"/></svg>
                Planifier un entretien
            </h3>

            @if(session('success'))
                <div style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; padding: .75rem 1rem; border-radius: var(--r); font-size: .82rem; font-weight: 600;">
                    {{ session('success') }}
                </div>
            @endif

            <form id="scheduleForm" method="POST" action="#" class="ca-form">
                @csrf
                <div class="ca-form-group">
                    <label class="ca-label" for="student_select">Choisir l'étudiant</label>
                    <select id="student_select" name="student_id" class="ca-select" required onchange="updateFormAction(this.value)">
                        <option value="">-- Sélectionner un étudiant --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="ca-form-group">
                    <label class="ca-label" for="scheduled_at">Date et Heure</label>
                    <input type="datetime-local" id="scheduled_at" name="scheduled_at" class="ca-input" required min="{{ date('Y-m-d\TH:i') }}">
                </div>

                <div class="ca-form-group">
                    <label class="ca-label" for="format">Format de la rencontre</label>
                    <select id="format" name="format" class="ca-select">
                        <option value="visio">Visioconférence intégrée</option>
                        <option value="physical">Entretien physique au bureau</option>
                        <option value="hybrid">Format hybride / Autre</option>
                    </select>
                </div>

                <div class="ca-form-group">
                    <label class="ca-label" for="notes">Notes / Objectif</label>
                    <textarea id="notes" name="notes" class="ca-textarea" placeholder="Détaillez le motif du rendez-vous (ex: Restitution de test RIASEC, Analyse des aptitudes GATB...)"></textarea>
                </div>

                <button type="submit" class="csl-btn-submit" style="width:100%; text-align:center; justify-content:center;">Planifier le rendez-vous</button>
            </form>
        </div>
    </div>

    <!-- D. Past Appointments -->
    <details class="ca-details-past">
        <summary>
            <span>Historique des entretiens passés ({{ $past->count() }})</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </summary>
        <div class="ca-past-content">
            @if($past->count() > 0)
                @foreach($past as $apt)
                    <div class="ca-past-item">
                        <div class="ca-past-info">
                            <a href="{{ route('counselor.student.show', $apt->student->id) }}" class="ca-past-student">
                                {{ $apt->student->name }}
                            </a>
                            <span class="ca-past-date">Rencontre effectuée le {{ $apt->scheduled_at->format('d/m/Y à H:i') }}</span>
                            @if($apt->notes)
                                <p class="ca-past-notes"><b>Observations :</b> {{ $apt->notes }}</p>
                            @endif
                        </div>
                        <span class="ca-status-pill ca-status-completed">Complété</span>
                    </div>
                @endforeach
            @else
                <p style="margin:0; text-align:center; padding: 1.5rem; color: var(--ink60); font-size: .88rem;">Aucun entretien dans l'historique.</p>
            @endif
        </div>
    </details>

</div>

<script>
    function updateFormAction(studentId) {
        const form = document.getElementById('scheduleForm');
        if (studentId) {
            form.action = `/counselor/student/${studentId}/appointments`;
        } else {
            form.action = '#';
        }
    }
</script>
@endsection
