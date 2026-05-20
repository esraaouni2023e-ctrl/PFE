@extends('layouts.admin')

@section('page-title', 'Candidatures Conseillers')

@section('content')
<div class="csl-root">

    {{-- ═══ HEADER ═══ --}}
    <div class="csl-header">
        <div>
            <h1 class="csl-title">
                Candidatures <em>Conseillers</em>
            </h1>
            <p class="csl-subtitle">Validez ou refusez les demandes d'accès des professionnels de l'orientation.</p>
        </div>
    </div>

    {{-- ═══ FLASH NOTIFICATIONS ═══ --}}
    @if(session('success'))
        <div class="csl-flash csl-flash--success" id="flashSuccess">
            <svg class="csl-flash__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
            <button class="csl-flash__close" onclick="this.parentElement.style.display='none'">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1rem;height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="csl-flash csl-flash--error" id="flashError">
            <svg class="csl-flash__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
            <button class="csl-flash__close" onclick="this.parentElement.style.display='none'">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1rem;height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- ═══ STATS WIDGETS ═══ --}}
    <div class="csl-stats">
        {{-- Total --}}
        <div class="csl-stat-card">
            <div class="csl-stat-card__header">
                <span class="csl-stat-card__label">Total Inscrits</span>
                <div class="csl-stat-card__icon csl-stat-card__icon--blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
            </div>
            <div class="csl-stat-card__value">{{ $counselors->count() }}</div>
            <div class="csl-stat-card__bar csl-stat-card__bar--blue"></div>
        </div>

        {{-- Pending --}}
        <div class="csl-stat-card">
            <div class="csl-stat-card__header">
                <span class="csl-stat-card__label">En Attente</span>
                <div class="csl-stat-card__icon csl-stat-card__icon--orange">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="csl-stat-card__value" style="color: var(--accent);">{{ $pending->count() }}</div>
            <div class="csl-stat-card__bar csl-stat-card__bar--orange"></div>
        </div>

        {{-- Approved --}}
        <div class="csl-stat-card">
            <div class="csl-stat-card__header">
                <span class="csl-stat-card__label">Approuvés</span>
                <div class="csl-stat-card__icon csl-stat-card__icon--green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="csl-stat-card__value" style="color: var(--accent3);">{{ $approved->count() }}</div>
            <div class="csl-stat-card__bar csl-stat-card__bar--green"></div>
        </div>

        {{-- Rejected --}}
        <div class="csl-stat-card">
            <div class="csl-stat-card__header">
                <span class="csl-stat-card__label">Refusés</span>
                <div class="csl-stat-card__icon csl-stat-card__icon--red">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                </div>
            </div>
            <div class="csl-stat-card__value" style="color: var(--red, #ef4444);">{{ $rejected->count() }}</div>
            <div class="csl-stat-card__bar csl-stat-card__bar--red"></div>
        </div>
    </div>

    {{-- ═══ SEARCH BAR ═══ --}}
    <div class="csl-search-wrap">
        <div class="csl-search">
            <svg class="csl-search__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" id="counselorSearch" class="csl-search__input" placeholder="Rechercher par nom ou spécialité…" autocomplete="off">
            <kbd class="csl-search__kbd">⌘K</kbd>
        </div>
    </div>

    {{-- ═══ TAB NAVIGATION ═══ --}}
    <div class="csl-tabs">
        <button class="csl-tab csl-tab--active" data-tab="pending" onclick="switchTab('pending')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1rem;height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            En attente
            <span class="csl-tab__count csl-tab__count--orange">{{ $pending->count() }}</span>
        </button>
        <button class="csl-tab" data-tab="approved" onclick="switchTab('approved')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1rem;height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Approuvés
            <span class="csl-tab__count csl-tab__count--green">{{ $approved->count() }}</span>
        </button>
        <button class="csl-tab" data-tab="rejected" onclick="switchTab('rejected')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1rem;height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
            Refusés
            <span class="csl-tab__count csl-tab__count--red">{{ $rejected->count() }}</span>
        </button>
    </div>

    {{-- ═══ TAB: PENDING ═══ --}}
    <div class="csl-panel csl-panel--active" id="panel-pending">
        @if($pending->isEmpty())
            <div class="csl-empty">
                <div class="csl-empty__illustration">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="csl-empty__title">Aucune demande en attente</h3>
                <p class="csl-empty__text">Toutes les candidatures ont été traitées. Revenez plus tard pour de nouvelles demandes.</p>
            </div>
        @else
            <div class="csl-cards-grid">
                @foreach($pending as $user)
                    @php $p = $user->counselorProfile; @endphp
                    <div class="csl-card csl-card--pending" data-name="{{ strtolower($user->name) }}" data-specialty="{{ strtolower($p?->specialty ?? '') }}">
                        {{-- Card Header --}}
                        <div class="csl-card__header">
                            <div class="csl-card__avatar">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                @else
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="csl-card__identity">
                                <h4 class="csl-card__name">{{ $user->name }}</h4>
                                <p class="csl-card__email">{{ $user->email }}</p>
                            </div>
                            <span class="csl-badge csl-badge--time">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:.75rem;height:.75rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $user->created_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- Card Body --}}
                        <div class="csl-card__body">
                            {{-- Meta Row --}}
                            <div class="csl-card__meta">
                                @if($p?->specialty)
                                    <span class="csl-badge csl-badge--specialty">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:.8rem;height:.8rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                                        {{ $p->specialty }}
                                    </span>
                                @endif
                                @if($p?->experience_years)
                                    <span class="csl-badge csl-badge--exp">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:.8rem;height:.8rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.193 23.193 0 0112 15c-3.183 0-6.22-.64-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        {{ $p->experience_years }} an(s)
                                    </span>
                                @endif
                                @if($p?->phone)
                                    <span class="csl-badge csl-badge--phone">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:.8rem;height:.8rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                        {{ $p->phone }}
                                    </span>
                                @endif
                            </div>

                            {{-- Bio --}}
                            @if($p?->bio)
                                <div class="csl-card__bio">
                                    <p>{{ Str::limit($p->bio, 150) }}</p>
                                </div>
                            @endif

                            {{-- CV Link --}}
                            @if($p && $p->cv_path)
                                <a href="{{ asset('storage/' . $p->cv_path) }}" target="_blank" class="csl-card__cv">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1rem;height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    <span>Voir le CV</span>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:.8rem;height:.8rem;opacity:.5;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                </a>
                            @else
                                <div class="csl-card__cv csl-card__cv--none">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1rem;height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    <span>Aucun CV fourni</span>
                                </div>
                            @endif
                        </div>

                        {{-- Card Actions --}}
                        <div class="csl-card__actions">
                            <form method="POST" action="{{ route('admin.counselors.approve', $user->id) }}" style="flex:1;">
                                @csrf
                                <button type="submit" class="csl-btn csl-btn--approve">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    Approuver
                                </button>
                            </form>
                            <button onclick="openRejectModal('{{ $user->id }}', '{{ addslashes($user->name) }}')" class="csl-btn csl-btn--reject" style="flex:1;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                Refuser
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ═══ TAB: APPROVED ═══ --}}
    <div class="csl-panel" id="panel-approved">
        @if($approved->isEmpty())
            <div class="csl-empty">
                <div class="csl-empty__illustration csl-empty__illustration--green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="csl-empty__title">Aucun conseiller approuvé</h3>
                <p class="csl-empty__text">Les conseillers approuvés apparaîtront ici une fois que vous les aurez validés.</p>
            </div>
        @else
            <div class="csl-cards-grid">
                @foreach($approved as $user)
                    @php $p = $user->counselorProfile; @endphp
                    <div class="csl-card csl-card--approved" data-name="{{ strtolower($user->name) }}" data-specialty="{{ strtolower($p?->specialty ?? '') }}">
                        <div class="csl-card__header">
                            <div class="csl-card__avatar csl-card__avatar--approved">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                @else
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                                <div class="csl-card__avatar-badge csl-card__avatar-badge--green">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                </div>
                            </div>
                            <div class="csl-card__identity">
                                <h4 class="csl-card__name">{{ $user->name }}</h4>
                                <p class="csl-card__email">{{ $user->email }}</p>
                            </div>
                            <span class="csl-badge csl-badge--approved">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:.7rem;height:.7rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                Approuvé
                            </span>
                        </div>

                        <div class="csl-card__body">
                            <div class="csl-card__meta">
                                @if($p?->specialty)
                                    <span class="csl-badge csl-badge--specialty">{{ $p->specialty }}</span>
                                @endif
                                @if($p?->experience_years)
                                    <span class="csl-badge csl-badge--exp">{{ $p->experience_years }} an(s)</span>
                                @endif
                                @if($p?->phone)
                                    <span class="csl-badge csl-badge--phone">{{ $p->phone }}</span>
                                @endif
                            </div>

                            <div class="csl-card__detail-rows">
                                <div class="csl-card__detail">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span>Approuvé le {{ $p?->approved_at ? $p->approved_at->format('d/m/Y à H:i') : $user->updated_at->format('d/m/Y à H:i') }}</span>
                                </div>
                                @if($p?->approver)
                                    <div class="csl-card__detail">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        <span>Par <strong>{{ $p->approver->name }}</strong></span>
                                    </div>
                                @elseif($p?->approved_by)
                                    <div class="csl-card__detail">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        <span>Par Admin #{{ $p->approved_by }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ═══ TAB: REJECTED ═══ --}}
    <div class="csl-panel" id="panel-rejected">
        @if($rejected->isEmpty())
            <div class="csl-empty">
                <div class="csl-empty__illustration csl-empty__illustration--red">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
                <h3 class="csl-empty__title">Aucune candidature refusée</h3>
                <p class="csl-empty__text">Les candidatures refusées apparaîtront ici avec leurs motifs de refus.</p>
            </div>
        @else
            <div class="csl-cards-grid">
                @foreach($rejected as $user)
                    @php $p = $user->counselorProfile; @endphp
                    <div class="csl-card csl-card--rejected" data-name="{{ strtolower($user->name) }}" data-specialty="{{ strtolower($p?->specialty ?? '') }}">
                        <div class="csl-card__header">
                            <div class="csl-card__avatar csl-card__avatar--rejected">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                @else
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                                <div class="csl-card__avatar-badge csl-card__avatar-badge--red">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                                </div>
                            </div>
                            <div class="csl-card__identity">
                                <h4 class="csl-card__name">{{ $user->name }}</h4>
                                <p class="csl-card__email">{{ $user->email }}</p>
                            </div>
                            <span class="csl-badge csl-badge--rejected">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:.7rem;height:.7rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                                Refusé
                            </span>
                        </div>

                        <div class="csl-card__body">
                            <div class="csl-card__meta">
                                @if($p?->specialty)
                                    <span class="csl-badge csl-badge--specialty">{{ $p->specialty }}</span>
                                @endif
                            </div>

                            @if($p?->verification_notes)
                                <div class="csl-card__rejection-reason">
                                    <div class="csl-card__rejection-label">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:.85rem;height:.85rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>
                                        Motif du refus
                                    </div>
                                    <p class="csl-card__rejection-text">{{ $p->verification_notes }}</p>
                                </div>
                            @endif

                            <div class="csl-card__detail-rows">
                                <div class="csl-card__detail">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span>Refusé le {{ $user->updated_at->format('d/m/Y à H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- ═══ REJECT MODAL ═══ --}}
<div id="reject-modal" class="csl-modal-overlay">
    <div class="csl-modal" id="rejectModalInner">
        {{-- Close button --}}
        <button class="csl-modal__close" onclick="closeRejectModal()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>

        {{-- Modal Header --}}
        <div class="csl-modal__header">
            <div class="csl-modal__icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
            </div>
            <h3 class="csl-modal__title">Refuser la candidature</h3>
            <p class="csl-modal__subtitle">Veuillez indiquer le motif de refus pour <strong id="modal-counselor-name"></strong>.</p>
        </div>

        {{-- Predefined Reason Chips --}}
        <div class="csl-modal__chips">
            <span class="csl-chip" onclick="fillReason(this)">Diplômes non vérifiables</span>
            <span class="csl-chip" onclick="fillReason(this)">Expérience insuffisante</span>
            <span class="csl-chip" onclick="fillReason(this)">Informations incomplètes</span>
            <span class="csl-chip" onclick="fillReason(this)">CV non conforme</span>
        </div>

        {{-- Form --}}
        <form id="reject-form" method="POST" action="">
            @csrf
            <div class="csl-modal__field">
                <label for="verification_notes" class="csl-modal__label">
                    Motif du refus <span style="color:var(--red,#ef4444);">*</span>
                </label>
                <textarea id="verification_notes" name="verification_notes" required class="csl-modal__textarea" placeholder="Décrivez la raison du refus en détail…"></textarea>
            </div>

            <div class="csl-modal__actions">
                <button type="button" onclick="closeRejectModal()" class="csl-btn csl-btn--cancel">
                    Annuler
                </button>
                <button type="submit" class="csl-btn csl-btn--confirm-reject">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                    Confirmer le refus
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══ STYLES ═══ --}}
<style>
    /* ── Root Container ── */
    .csl-root {
        padding: 0;
        max-width: 1400px;
        margin: 0 auto;
        font-family: 'DM Sans', sans-serif;
    }

    /* ── Header ── */
    .csl-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .csl-title {
        font-family: 'Fraunces', serif;
        font-size: clamp(1.6rem, 3vw, 2.2rem);
        font-weight: 600;
        color: var(--ink);
        margin: 0;
        letter-spacing: -0.02em;
    }
    .csl-title em {
        color: var(--accent);
        font-style: italic;
        font-family: 'Fraunces', serif;
        font-weight: 400;
    }
    .csl-subtitle {
        font-size: 0.88rem;
        color: var(--ink60);
        margin: 0.35rem 0 0 0;
        line-height: 1.5;
    }

    /* ── Flash Notifications ── */
    .csl-flash {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-radius: var(--rl);
        margin-bottom: 1.5rem;
        font-size: 0.88rem;
        font-weight: 500;
        animation: cslSlideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }
    .csl-flash__icon { width: 1.25rem; height: 1.25rem; flex-shrink: 0; }
    .csl-flash__close {
        position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
        background: none; border: none; cursor: pointer; color: inherit; opacity: 0.6;
        transition: opacity 0.2s;
    }
    .csl-flash__close:hover { opacity: 1; }
    .csl-flash--success {
        background: color-mix(in srgb, var(--accent3) 10%, transparent);
        border: 1px solid color-mix(in srgb, var(--accent3) 25%, transparent);
        color: var(--accent3);
    }
    .csl-flash--error {
        background: color-mix(in srgb, var(--red, #ef4444) 10%, transparent);
        border: 1px solid color-mix(in srgb, var(--red, #ef4444) 25%, transparent);
        color: var(--red, #ef4444);
    }

    /* ── Stats Widgets ── */
    .csl-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .csl-stat-card {
        background: var(--cream);
        border: 1px solid var(--ink10);
        padding: 1.35rem 1.5rem 1.1rem;
        border-radius: var(--rl);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease, border-color 0.3s ease;
        position: relative;
        overflow: hidden;
        cursor: default;
    }
    .csl-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.08);
        border-color: var(--ink15);
    }
    .csl-stat-card__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.6rem;
    }
    .csl-stat-card__label {
        font-size: 0.75rem;
        color: var(--ink60);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-weight: 700;
    }
    .csl-stat-card__icon {
        width: 2.2rem; height: 2.2rem;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }
    .csl-stat-card__icon svg { width: 1.15rem; height: 1.15rem; }
    .csl-stat-card__icon--blue { background: color-mix(in srgb, var(--accent2) 10%, transparent); color: var(--accent2); }
    .csl-stat-card__icon--orange { background: color-mix(in srgb, var(--accent) 10%, transparent); color: var(--accent); }
    .csl-stat-card__icon--green { background: color-mix(in srgb, var(--accent3) 10%, transparent); color: var(--accent3); }
    .csl-stat-card__icon--red { background: color-mix(in srgb, var(--red, #ef4444) 10%, transparent); color: var(--red, #ef4444); }
    .csl-stat-card__value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1;
    }
    .csl-stat-card__bar {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 3px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .csl-stat-card:hover .csl-stat-card__bar { opacity: 1; }
    .csl-stat-card__bar--blue { background: var(--accent2); }
    .csl-stat-card__bar--orange { background: var(--accent); }
    .csl-stat-card__bar--green { background: var(--accent3); }
    .csl-stat-card__bar--red { background: var(--red, #ef4444); }

    /* ── Search ── */
    .csl-search-wrap { margin-bottom: 1.5rem; }
    .csl-search {
        position: relative;
        display: flex;
        align-items: center;
        background: var(--cream);
        border: 1px solid var(--ink10);
        border-radius: var(--rl);
        padding: 0 1.25rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .csl-search:focus-within {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 12%, transparent);
    }
    .csl-search__icon {
        width: 1.15rem; height: 1.15rem;
        color: var(--ink30);
        flex-shrink: 0;
        transition: color 0.2s;
    }
    .csl-search:focus-within .csl-search__icon { color: var(--accent); }
    .csl-search__input {
        flex: 1;
        background: none;
        border: none;
        outline: none;
        padding: 0.85rem 0.75rem;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem;
        color: var(--ink);
    }
    .csl-search__input::placeholder { color: var(--ink30); }
    .csl-search__kbd {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--ink30);
        background: var(--ink06);
        border: 1px solid var(--ink10);
        border-radius: 4px;
        padding: 0.15rem 0.45rem;
        line-height: 1.3;
    }

    /* ── Tabs ── */
    .csl-tabs {
        display: flex;
        gap: 0.25rem;
        border-bottom: 2px solid var(--ink06);
        margin-bottom: 2rem;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .csl-tab {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.85rem 1.25rem;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--ink60);
        background: none;
        border: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        cursor: pointer;
        transition: color 0.25s ease, border-color 0.25s ease;
        white-space: nowrap;
    }
    .csl-tab:hover { color: var(--ink); }
    .csl-tab--active {
        color: var(--accent);
        border-bottom-color: var(--accent);
    }
    .csl-tab__count {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.1rem 0.55rem;
        border-radius: 999px;
        line-height: 1.4;
    }
    .csl-tab__count--orange {
        background: color-mix(in srgb, var(--accent) 12%, transparent);
        color: var(--accent);
    }
    .csl-tab__count--green {
        background: color-mix(in srgb, var(--accent3) 12%, transparent);
        color: var(--accent3);
    }
    .csl-tab__count--red {
        background: color-mix(in srgb, var(--red, #ef4444) 12%, transparent);
        color: var(--red, #ef4444);
    }

    /* ── Tab Panels ── */
    .csl-panel { display: none; animation: cslFadeIn 0.35s ease; }
    .csl-panel--active { display: block; }

    /* ── Cards Grid ── */
    .csl-cards-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }
    @media (min-width: 768px) {
        .csl-cards-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (min-width: 1200px) {
        .csl-cards-grid { grid-template-columns: repeat(3, 1fr); }
    }

    /* ── Card Base ── */
    .csl-card {
        background: var(--cream);
        border: 1px solid var(--ink10);
        border-radius: var(--rl);
        overflow: hidden;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease, border-color 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    .csl-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 48px rgba(0,0,0,0.08);
        border-color: var(--ink15);
    }
    .csl-card--pending:hover { border-color: color-mix(in srgb, var(--accent) 30%, transparent); }
    .csl-card--approved:hover { border-color: color-mix(in srgb, var(--accent3) 30%, transparent); }
    .csl-card--rejected:hover { border-color: color-mix(in srgb, var(--red, #ef4444) 30%, transparent); }

    .csl-card__header {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        padding: 1.35rem 1.35rem 0;
    }
    .csl-card__avatar {
        width: 48px; height: 48px;
        border-radius: 50%;
        background: var(--ink10);
        color: var(--ink);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1.2rem;
        overflow: hidden;
        flex-shrink: 0;
        position: relative;
    }
    .csl-card__avatar img { width: 100%; height: 100%; object-fit: cover; }
    .csl-card__avatar--approved { border: 2px solid var(--accent3); }
    .csl-card__avatar--rejected { border: 2px solid var(--red, #ef4444); opacity: 0.8; }

    .csl-card__avatar-badge {
        position: absolute;
        bottom: -2px; right: -2px;
        width: 18px; height: 18px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 2px solid var(--cream);
    }
    .csl-card__avatar-badge svg { width: 10px; height: 10px; }
    .csl-card__avatar-badge--green { background: var(--accent3); color: #fff; }
    .csl-card__avatar-badge--red { background: var(--red, #ef4444); color: #fff; }

    .csl-card__identity { flex: 1; min-width: 0; }
    .csl-card__name {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .csl-card__email {
        font-size: 0.78rem;
        color: var(--ink60);
        margin: 0.15rem 0 0 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── Badges ── */
    .csl-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        font-size: 0.68rem;
        font-weight: 700;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .csl-badge--time {
        background: color-mix(in srgb, var(--accent) 8%, transparent);
        color: var(--accent);
        border: 1px solid color-mix(in srgb, var(--accent) 18%, transparent);
    }
    .csl-badge--specialty {
        background: color-mix(in srgb, var(--accent2) 10%, transparent);
        color: var(--accent2);
        border: 1px solid color-mix(in srgb, var(--accent2) 22%, transparent);
    }
    .csl-badge--exp {
        background: color-mix(in srgb, var(--gold, #c8973a) 10%, transparent);
        color: var(--gold, #c8973a);
        border: 1px solid color-mix(in srgb, var(--gold, #c8973a) 22%, transparent);
    }
    .csl-badge--phone {
        background: var(--ink06);
        color: var(--ink60);
        border: 1px solid var(--ink10);
    }
    .csl-badge--approved {
        background: color-mix(in srgb, var(--accent3) 12%, transparent);
        color: var(--accent3);
        border: 1px solid color-mix(in srgb, var(--accent3) 25%, transparent);
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .csl-badge--rejected {
        background: color-mix(in srgb, var(--red, #ef4444) 12%, transparent);
        color: var(--red, #ef4444);
        border: 1px solid color-mix(in srgb, var(--red, #ef4444) 25%, transparent);
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    /* ── Card Body ── */
    .csl-card__body {
        padding: 1rem 1.35rem 1.35rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }
    .csl-card__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }
    .csl-card__bio {
        font-size: 0.82rem;
        color: var(--ink60);
        line-height: 1.55;
        border-left: 2px solid var(--ink10);
        padding-left: 0.85rem;
    }
    .csl-card__bio p { margin: 0; }

    /* ── CV Link ── */
    .csl-card__cv {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.55rem 0.9rem;
        border-radius: var(--r);
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
        border: 1px dashed var(--ink10);
        color: var(--accent);
        background: color-mix(in srgb, var(--accent) 4%, transparent);
    }
    .csl-card__cv:hover {
        background: color-mix(in srgb, var(--accent) 10%, transparent);
        border-style: solid;
        border-color: color-mix(in srgb, var(--accent) 25%, transparent);
    }
    .csl-card__cv--none {
        color: var(--ink30);
        background: none;
        cursor: default;
        pointer-events: none;
    }

    /* ── Detail rows (approved/rejected) ── */
    .csl-card__detail-rows {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        margin-top: auto;
        padding-top: 0.5rem;
        border-top: 1px solid var(--ink06);
    }
    .csl-card__detail {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.78rem;
        color: var(--ink60);
    }
    .csl-card__detail svg { width: 0.9rem; height: 0.9rem; flex-shrink: 0; opacity: 0.5; }
    .csl-card__detail strong { color: var(--ink); font-weight: 600; }

    /* ── Rejection Reason ── */
    .csl-card__rejection-reason {
        background: color-mix(in srgb, var(--red, #ef4444) 5%, transparent);
        border: 1px solid color-mix(in srgb, var(--red, #ef4444) 12%, transparent);
        border-radius: var(--r);
        padding: 0.75rem 0.9rem;
    }
    .csl-card__rejection-label {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--red, #ef4444);
        margin-bottom: 0.35rem;
    }
    .csl-card__rejection-text {
        font-size: 0.82rem;
        color: var(--ink60);
        line-height: 1.5;
        margin: 0;
        font-style: italic;
    }

    /* ── Card Actions ── */
    .csl-card__actions {
        display: flex;
        gap: 0.5rem;
        padding: 0 1.35rem 1.35rem;
    }

    /* ── Buttons ── */
    .csl-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.65rem 1rem;
        border-radius: var(--r);
        font-family: 'DM Sans', sans-serif;
        font-size: 0.82rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        width: 100%;
    }
    .csl-btn svg { width: 0.9rem; height: 0.9rem; }
    .csl-btn--approve {
        background: var(--accent3);
        color: #fff;
        box-shadow: 0 4px 14px color-mix(in srgb, var(--accent3) 30%, transparent);
    }
    .csl-btn--approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px color-mix(in srgb, var(--accent3) 40%, transparent);
    }
    .csl-btn--reject {
        background: none;
        color: var(--red, #ef4444);
        border: 1px solid color-mix(in srgb, var(--red, #ef4444) 25%, transparent);
    }
    .csl-btn--reject:hover {
        background: color-mix(in srgb, var(--red, #ef4444) 8%, transparent);
        border-color: color-mix(in srgb, var(--red, #ef4444) 40%, transparent);
        transform: translateY(-1px);
    }
    .csl-btn--cancel {
        background: none;
        color: var(--ink60);
        border: 1px solid var(--ink10);
        width: auto;
        padding: 0.7rem 1.4rem;
    }
    .csl-btn--cancel:hover {
        border-color: var(--ink30);
        color: var(--ink);
        background: var(--ink06);
    }
    .csl-btn--confirm-reject {
        background: var(--red, #ef4444);
        color: #fff;
        width: auto;
        padding: 0.7rem 1.4rem;
        box-shadow: 0 4px 14px color-mix(in srgb, var(--red, #ef4444) 30%, transparent);
    }
    .csl-btn--confirm-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px color-mix(in srgb, var(--red, #ef4444) 40%, transparent);
    }

    /* ── Empty States ── */
    .csl-empty {
        text-align: center;
        padding: 4rem 2rem;
    }
    .csl-empty__illustration {
        width: 5rem; height: 5rem;
        margin: 0 auto 1.25rem;
        border-radius: 50%;
        background: color-mix(in srgb, var(--accent) 6%, transparent);
        display: flex; align-items: center; justify-content: center;
    }
    .csl-empty__illustration svg { width: 2.5rem; height: 2.5rem; color: var(--accent); opacity: 0.5; }
    .csl-empty__illustration--green { background: color-mix(in srgb, var(--accent3) 6%, transparent); }
    .csl-empty__illustration--green svg { color: var(--accent3); }
    .csl-empty__illustration--red { background: color-mix(in srgb, var(--red, #ef4444) 6%, transparent); }
    .csl-empty__illustration--red svg { color: var(--red, #ef4444); }
    .csl-empty__title {
        font-family: 'Fraunces', serif;
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--ink);
        margin: 0 0 0.4rem 0;
    }
    .csl-empty__text {
        font-size: 0.85rem;
        color: var(--ink30);
        margin: 0;
        max-width: 380px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.5;
    }

    /* ── Modal ── */
    .csl-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        padding: 1.5rem;
    }
    .csl-modal-overlay--active { display: flex; }

    .csl-modal {
        background: var(--cream);
        border: 1px solid var(--ink10);
        border-radius: var(--rl);
        padding: 2rem;
        width: 100%;
        max-width: 520px;
        box-shadow: 0 24px 80px rgba(0,0,0,0.25);
        position: relative;
        animation: cslModalIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .csl-modal__close {
        position: absolute;
        top: 1rem; right: 1rem;
        width: 2rem; height: 2rem;
        border-radius: var(--r);
        background: var(--ink06);
        border: 1px solid var(--ink10);
        color: var(--ink60);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .csl-modal__close svg { width: 1rem; height: 1rem; }
    .csl-modal__close:hover {
        background: var(--ink10);
        color: var(--ink);
        border-color: var(--ink15);
    }
    .csl-modal__header { text-align: center; margin-bottom: 1.5rem; }
    .csl-modal__icon {
        width: 3.5rem; height: 3.5rem;
        margin: 0 auto 1rem;
        border-radius: 50%;
        background: color-mix(in srgb, var(--red, #ef4444) 10%, transparent);
        display: flex; align-items: center; justify-content: center;
    }
    .csl-modal__icon svg { width: 1.6rem; height: 1.6rem; color: var(--red, #ef4444); }
    .csl-modal__title {
        font-family: 'Fraunces', serif;
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--ink);
        margin: 0 0 0.4rem 0;
    }
    .csl-modal__subtitle {
        font-size: 0.85rem;
        color: var(--ink60);
        margin: 0;
    }

    /* ── Modal Chips ── */
    .csl-modal__chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin-bottom: 1.25rem;
    }
    .csl-chip {
        display: inline-block;
        padding: 0.4rem 0.85rem;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 600;
        color: var(--ink60);
        background: var(--ink06);
        border: 1px solid var(--ink10);
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }
    .csl-chip:hover {
        background: color-mix(in srgb, var(--red, #ef4444) 8%, transparent);
        color: var(--red, #ef4444);
        border-color: color-mix(in srgb, var(--red, #ef4444) 22%, transparent);
    }
    .csl-chip--active {
        background: color-mix(in srgb, var(--red, #ef4444) 12%, transparent) !important;
        color: var(--red, #ef4444) !important;
        border-color: color-mix(in srgb, var(--red, #ef4444) 30%, transparent) !important;
    }

    /* ── Modal Form ── */
    .csl-modal__field { margin-bottom: 1.5rem; }
    .csl-modal__label {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--ink60);
        margin-bottom: 0.5rem;
    }
    .csl-modal__textarea {
        width: 100%;
        min-height: 110px;
        background: var(--ink06);
        border: 1px solid var(--ink10);
        border-radius: var(--r);
        color: var(--ink);
        padding: 0.85rem;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.88rem;
        box-sizing: border-box;
        resize: vertical;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
        line-height: 1.5;
    }
    .csl-modal__textarea:focus {
        outline: none;
        border-color: var(--red, #ef4444);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--red, #ef4444) 10%, transparent);
    }
    .csl-modal__textarea::placeholder { color: var(--ink30); }
    .csl-modal__actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    /* ── Animations ── */
    @keyframes cslSlideDown {
        from { opacity: 0; transform: translateY(-12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes cslFadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes cslModalIn {
        from { opacity: 0; transform: scale(0.95) translateY(12px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* ── Card hide for search ── */
    .csl-card--hidden { display: none !important; }

    /* ── Responsive tweaks ── */
    @media (max-width: 640px) {
        .csl-stats { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
        .csl-stat-card { padding: 1rem 1.1rem 0.85rem; }
        .csl-stat-card__value { font-size: 1.5rem; }
        .csl-card__header { padding: 1rem 1rem 0; }
        .csl-card__body { padding: 0.85rem 1rem 1rem; }
        .csl-card__actions { padding: 0 1rem 1rem; }
        .csl-tab { padding: 0.7rem 0.85rem; font-size: 0.8rem; }
        .csl-modal { padding: 1.5rem; }
        .csl-modal__chips { gap: 0.35rem; }
        .csl-chip { font-size: 0.7rem; padding: 0.35rem 0.7rem; }
    }
</style>

{{-- ═══ SCRIPTS ═══ --}}
<script>
    /* ── Tab Switching ── */
    function switchTab(tab) {
        // Deactivate all tabs
        document.querySelectorAll('.csl-tab').forEach(t => t.classList.remove('csl-tab--active'));
        document.querySelectorAll('.csl-panel').forEach(p => p.classList.remove('csl-panel--active'));

        // Activate selected
        document.querySelector(`[data-tab="${tab}"]`).classList.add('csl-tab--active');
        document.getElementById(`panel-${tab}`).classList.add('csl-panel--active');

        // Re-apply search filter
        filterCounselors();
    }

    /* ── Search / Filter ── */
    const searchInput = document.getElementById('counselorSearch');
    searchInput?.addEventListener('input', filterCounselors);

    function filterCounselors() {
        const query = (searchInput?.value || '').toLowerCase().trim();
        const activePanel = document.querySelector('.csl-panel--active');
        if (!activePanel) return;

        const cards = activePanel.querySelectorAll('.csl-card');
        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const specialty = card.getAttribute('data-specialty') || '';
            if (!query || name.includes(query) || specialty.includes(query)) {
                card.classList.remove('csl-card--hidden');
            } else {
                card.classList.add('csl-card--hidden');
            }
        });

        // Toggle empty state visibility
        const visibleCards = activePanel.querySelectorAll('.csl-card:not(.csl-card--hidden)');
        const grid = activePanel.querySelector('.csl-cards-grid');
        const emptyState = activePanel.querySelector('.csl-empty');

        // Show "no results" when searching produces 0 visible cards but there are cards in DOM
        if (grid && visibleCards.length === 0 && query) {
            if (!activePanel.querySelector('.csl-search-empty')) {
                const noResult = document.createElement('div');
                noResult.className = 'csl-empty csl-search-empty';
                noResult.innerHTML = `
                    <div class="csl-empty__illustration">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <h3 class="csl-empty__title">Aucun résultat</h3>
                    <p class="csl-empty__text">Aucun conseiller ne correspond à « ${query} »</p>
                `;
                activePanel.appendChild(noResult);
            }
        } else {
            const searchEmpty = activePanel.querySelector('.csl-search-empty');
            if (searchEmpty) searchEmpty.remove();
        }
    }

    /* ── Keyboard shortcut for search ── */
    document.addEventListener('keydown', function(e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            searchInput?.focus();
        }
    });

    /* ── Reject Modal ── */
    function openRejectModal(userId, name) {
        const modal = document.getElementById('reject-modal');
        const form = document.getElementById('reject-form');
        const nameSpan = document.getElementById('modal-counselor-name');
        const textarea = document.getElementById('verification_notes');

        form.action = `/admin/counselors/reject/${userId}`;
        nameSpan.innerText = name;
        textarea.value = '';

        // Reset chips
        document.querySelectorAll('.csl-chip').forEach(c => c.classList.remove('csl-chip--active'));

        modal.classList.add('csl-modal-overlay--active');
        modal.style.display = 'flex';
        setTimeout(() => textarea.focus(), 100);
    }

    function closeRejectModal() {
        const modal = document.getElementById('reject-modal');
        modal.classList.remove('csl-modal-overlay--active');
        modal.style.display = 'none';
    }

    /* ── Reason Chips ── */
    function fillReason(chip) {
        const textarea = document.getElementById('verification_notes');
        const text = chip.innerText.trim();

        // Toggle chip active state
        document.querySelectorAll('.csl-chip').forEach(c => c.classList.remove('csl-chip--active'));
        chip.classList.add('csl-chip--active');

        // Fill textarea
        textarea.value = text;
        textarea.focus();
    }

    /* ── Close modal on overlay click ── */
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('reject-modal');
        if (event.target === modal) {
            closeRejectModal();
        }
    });

    /* ── Close modal on Escape key ── */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRejectModal();
        }
    });

    /* ── Auto-dismiss flash notifications after 6s ── */
    setTimeout(() => {
        const flash = document.querySelector('.csl-flash');
        if (flash) {
            flash.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-8px)';
            setTimeout(() => flash.style.display = 'none', 400);
        }
    }, 6000);
</script>
@endsection
