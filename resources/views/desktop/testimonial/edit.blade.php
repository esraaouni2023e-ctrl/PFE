@extends(
    auth()->user()->isCounselor() 
        ? 'layouts.counselor' 
        : (auth()->user()->isSuperAdmin() || auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin'
            ? 'layouts.admin' 
            : 'layouts.student')
)

@section('title', 'Mon Témoignage')

@section('page-heading')
Mon<br><em>Témoignage.</em>
@endsection

@section('page-subtitle')
Partagez votre expérience sur CapAvenir pour inspirer les autres utilisateurs et renforcer la communauté.
@endsection

@section('content')
<div class="db" id="dbRoot">
    {{-- ════════════════════════════════
         § 1 · HEADER (For students layout, since other layouts integrate a header)
    ════════════════════════════════ --}}
    @if(auth()->user()->isStudent())
    <section class="db-hero" style="padding: 3.5rem 4rem 3rem;">
        <div class="db-hero-bgword">Témoin</div>
        <div class="db-hero-orb"></div>

        <div class="db-hero-inner">
            <div class="db-hero-left">
                <div class="db-hero-eyebrow">
                    <span class="eyebrow-dot"></span>
                    Retours d'expérience
                </div>

                <h1 class="db-hero-title">
                    Partage ton<br>
                    <em>aventure</em><br>
                    <strong>CapAvenir.</strong>
                </h1>

                <p class="db-hero-sub">
                    Exprime ce que la plateforme t'a apporté dans ton orientation et aide d'autres élèves à trouver leur voie.
                </p>
            </div>

            <div class="db-hero-right">
                <div class="db-ring-wrap" style="width: 160px; height: 160px;">
                    <div class="db-ring-center">
                        <div class="avatar-nav" style="width: 80px; height: 80px; font-size: 2.5rem; border: 4px solid var(--paper); box-shadow: var(--shadow-card); overflow:hidden; display:flex; align-items:center; justify-content:center;">
                            @php
                                $avatarUrl = $user->getAvatarUrl();
                            @endphp
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" style="width:100%; height:100%; object-fit:cover;">
                                <div style="display:none; width:100%; height:100%; align-items:center; justify-content:center;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @else
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ════════════════════════════════
         § 2 · CONTENT GRID
    ════════════════════════════════ --}}
    <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 2rem; max-width: 1200px; margin: 0 auto; align-items: start;">
        
        {{-- Submission Form --}}
        <section class="db-section rev vis">
            <div class="db-section-header">
                <div>
                    <p class="stag">Votre retour</p>
                    <h2 class="sh" style="font-size: 2rem;">Rédiger un <em>avis</em></h2>
                </div>
            </div>

            @if(session('success'))
                <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.25); color: #10b981; padding: 1rem 1.25rem; border-radius: var(--r); margin-bottom: 1.5rem; font-weight: 500; font-size: 0.9rem;">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <div class="card" style="padding: 2.5rem; background: var(--cream);">
                <form action="{{ route('testimonial.update') }}" method="POST">
                    @csrf
                    
                    {{-- Status Badge display --}}
                    @if($testimonial->id)
                        <div style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-size: 0.85rem; font-weight: 600; color: var(--ink60);">Statut de votre avis :</span>
                            @if($testimonial->status === 'approved')
                                <span class="badge badge-green">Validé & Publié</span>
                            @elseif($testimonial->status === 'pending')
                                <span class="badge badge-amber">En attente de validation</span>
                            @elseif($testimonial->status === 'rejected')
                                <span class="badge badge-red">Rejeté</span>
                            @elseif($testimonial->status === 'archived')
                                <span class="badge badge-violet">Archivé</span>
                            @endif
                        </div>
                    @endif

                    {{-- Star Rating --}}
                    <div style="margin-bottom: 2rem;">
                        <label style="font-weight: 700; margin-bottom: 0.5rem; display: block;">Votre note</label>
                        <div class="star-rating" style="display: flex; gap: 0.5rem; flex-direction: row-reverse; justify-content: flex-end;">
                            <input type="radio" id="star5" name="rating" value="5" {{ old('rating', $testimonial->rating) == 5 ? 'checked' : '' }} style="display:none;" />
                            <label for="star5" title="5 étoiles" class="star-label">★</label>

                            <input type="radio" id="star4" name="rating" value="4" {{ old('rating', $testimonial->rating) == 4 ? 'checked' : '' }} style="display:none;" />
                            <label for="star4" title="4 étoiles" class="star-label">★</label>

                            <input type="radio" id="star3" name="rating" value="3" {{ old('rating', $testimonial->rating) == 3 ? 'checked' : '' }} style="display:none;" />
                            <label for="star3" title="3 étoiles" class="star-label">★</label>

                            <input type="radio" id="star2" name="rating" value="2" {{ old('rating', $testimonial->rating) == 2 ? 'checked' : '' }} style="display:none;" />
                            <label for="star2" title="2 étoiles" class="star-label">★</label>

                            <input type="radio" id="star1" name="rating" value="1" {{ old('rating', $testimonial->rating) == 1 ? 'checked' : '' }} style="display:none;" />
                            <label for="star1" title="1 étoile" class="star-label">★</label>
                        </div>
                        @error('rating')
                            <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.5rem; font-weight: 500;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Comment Textarea --}}
                    <div style="margin-bottom: 2rem;">
                        <label for="comment" style="font-weight: 700; margin-bottom: 0.5rem; display: block;">Votre témoignage détaillé</label>
                        <textarea name="comment" id="comment" rows="6" placeholder="Parlez-nous de votre expérience, ce que vous avez aimé, comment l'IA vous a aidé..." style="line-height: 1.6; resize: vertical;" required>{{ old('comment', $testimonial->comment) }}</textarea>
                        <div style="display: flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.75rem; color: var(--ink30); font-weight: 600;">
                            <span>Min. 10 caractères, Max. 1000</span>
                            <span id="char-counter">0 / 1000</span>
                        </div>
                        @error('comment')
                            <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.5rem; font-weight: 500;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div>
                        <button type="submit" class="primary-button" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 1.1rem; height: 1.1rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                            Soumettre mon témoignage
                        </button>
                    </div>
                </form>
            </div>
        </section>

        {{-- Interactive Live Preview --}}
        <section class="db-section rev vis">
            <div class="db-section-header">
                <div>
                    <p class="stag">Aperçu</p>
                    <h2 class="sh" style="font-size: 2rem;">Rendu en <em>direct</em></h2>
                </div>
            </div>

            <div class="card glass-card" style="padding: 2.5rem; position: sticky; top: 90px; background: var(--paper); display: flex; flex-direction: column; gap: 1.5rem; min-height: 250px; justify-content: space-between;">
                <div>
                    <div style="font-size: 2.5rem; color: var(--accent); opacity: 0.25; font-family: var(--font-serif); line-height: 1; margin-bottom: -0.5rem;">«</div>
                    <p id="preview-text" style="font-style: italic; font-size: 0.95rem; line-height: 1.6; color: var(--ink60); min-height: 80px; word-break: break-word;">
                        Votre avis s'affichera ici au fur et à mesure de votre saisie...
                    </p>
                    <div style="font-size: 2.5rem; color: var(--accent); opacity: 0.25; font-family: var(--font-serif); line-height: 1; text-align: right; margin-top: -0.75rem;">»</div>
                </div>

                <div style="display: flex; align-items: center; gap: 1rem; border-top: 1px solid var(--ink10); padding-top: 1.25rem;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: 700; color: #fff; overflow: hidden; flex-shrink: 0; border: 2px solid var(--paper); box-shadow: var(--shadow-card);">
                        @php
                            $avatarUrl = $user->getAvatarUrl();
                        @endphp
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" style="width:100%; height:100%; object-fit:cover;">
                            <div style="display:none; width:100%; height:100%; align-items:center; justify-content:center;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <h4 style="font-weight: 700; font-size: 0.95rem; color: var(--ink); margin-bottom: 0.15rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $user->name }}</h4>
                        <p style="font-size: 0.75rem; font-weight: 600; color: var(--accent); letter-spacing: 0.05em; text-transform: uppercase;">
                            @if($user->role === 'student')
                                Étudiant · CapAvenir
                            @elseif($user->role === 'counselor')
                                Conseiller · CapAvenir
                            @else
                                {{ ucfirst($user->role) }}
                            @endif
                        </p>
                        <div id="preview-stars" style="color: var(--gold); font-size: 0.85rem; margin-top: 0.25rem; letter-spacing: 1px;">
                            ★★★★★
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
/* ════════════════════════════════════════════
   REUSE / ADAPT STYLES FROM PROFILE
   With beautiful premium colors and stars
   ════════════════════════════════════════════ */
.db {
    --ink:     #0b0c10;
    --paper:   #f7f5f0;
    --cream:   #ede9e1;
    --warm:    #e8e1d4;
    --accent:  #d4622a;
    --accent2: #1a4f6e;
    --accent3: #4a7c59;
    --gold:    #c8973a;
    --ink60:   rgba(11,12,16,.6);
    --ink30:   rgba(11,12,16,.3);
    --ink15:   rgba(11,12,16,.15);
    --ink10:   rgba(11,12,16,.1);
    --ink06:   rgba(11,12,16,.06);
    --r:       6px;
    --rl:      16px;
    --rx:      999px;
    --ease:    cubic-bezier(.16,1,.3,1);

    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    padding: 2rem 3rem 5rem;
}

[data-theme="dark"] .db {
    --ink:     #f0ede6;
    --paper:   #10100d;
    --cream:   #18170f;
    --warm:    #1f1e14;
    --ink60:   rgba(240,237,230,.6);
    --ink30:   rgba(240,237,230,.3);
    --ink15:   rgba(240,237,230,.15);
    --ink10:   rgba(240,237,230,.08);
    --ink06:   rgba(240,237,230,.04);
}

.db .stag {
    font-size: .72rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase;
    color: var(--accent); display: inline-flex; align-items: center; gap: .5rem; margin-bottom: 1rem;
}
.db .stag::before { content: ''; width: 18px; height: 1px; background: var(--accent); }

.db .sh {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.8rem, 3.5vw, 3rem);
    font-weight: 300; letter-spacing: -.03em; line-height: 1.1;
}
.db .sh em { font-style: italic; color: var(--accent); }

.db .card {
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: var(--rl);
    transition: all .3s var(--ease);
}
.db .card:hover { border-color: var(--ink30); }

.db-hero {
    position: relative;
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: 20px;
    padding: 4.5rem 4rem 4rem;
    overflow: hidden;
    margin-bottom: 3rem;
}

.db-hero-bgword {
    position: absolute;
    font-family: 'Fraunces', serif; font-weight: 300; font-style: italic;
    font-size: clamp(9rem, 19vw, 18rem);
    color: transparent;
    -webkit-text-stroke: 1px color-mix(in srgb, var(--ink) 5%, transparent);
    line-height: 1; letter-spacing: -.05em;
    right: -2%; top: 50%; transform: translateY(-50%);
    pointer-events: none; user-select: none;
}

.db-hero-orb {
    position: absolute; border-radius: 50%;
    width: 460px; height: 460px;
    background: radial-gradient(circle at 40% 40%,
        color-mix(in srgb, var(--accent) 14%, transparent),
        color-mix(in srgb, var(--accent2) 9%, transparent) 50%,
        transparent 75%);
    right: 3%; top: 50%; transform: translateY(-50%);
    pointer-events: none;
}

.db-hero-inner {
    position: relative; z-index: 10;
    display: grid; grid-template-columns: 1fr auto;
    align-items: center; gap: 4rem;
}

.db-hero-eyebrow {
    display: inline-flex; align-items: center; gap: .5rem;
    font-size: .75rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
    color: var(--accent); margin-bottom: 2rem;
}
.db-hero-eyebrow::before { content: ''; width: 18px; height: 1px; background: var(--accent); }

.db-hero-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(2.8rem, 5.5vw, 5.2rem);
    font-weight: 300; line-height: 1.04; letter-spacing: -.04em;
    margin-bottom: 1.5rem;
}
.db-hero-title em { font-style: italic; color: var(--accent); }
.db-hero-title strong { font-weight: 600; }
.db-hero-sub { font-size: 1rem; color: var(--ink60); line-height: 1.75; margin-bottom: 2.5rem; max-width: 480px; }

.db-section { margin-bottom: 3rem; }
.db-section-header {
    display: flex; justify-content: space-between; align-items: flex-end;
    margin-bottom: 1.75rem; gap: 1rem; flex-wrap: wrap;
}

.db textarea {
    background: var(--paper) !important;
    border: 1px solid var(--ink10) !important;
    border-radius: var(--r) !important;
    padding: 0.75rem 1rem !important;
    font-family: 'DM Sans', sans-serif !important;
    color: var(--ink) !important;
    width: 100%;
}
.db textarea:focus {
    border-color: var(--accent) !important;
    outline: none !important;
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 15%, transparent) !important;
}

.db .primary-button {
    background: var(--accent);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: var(--r);
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}
.db .primary-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px color-mix(in srgb, var(--accent) 30%, transparent);
}

/* Star Rating Hover Effects and Layout */
.star-label {
    font-size: 2rem;
    color: var(--ink15);
    cursor: pointer;
    transition: color 0.2s ease, transform 0.2s ease;
}
.star-rating input:checked ~ .star-label,
.star-rating .star-label:hover,
.star-rating .star-label:hover ~ .star-label {
    color: var(--gold);
}
.star-label:hover {
    transform: scale(1.15);
}

@media (max-width: 860px) {
    .db-hero-inner { grid-template-columns: 1fr; }
    .db-hero-right { display: none; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const commentInput = document.getElementById('comment');
    const counterDisplay = document.getElementById('char-counter');
    const previewText = document.getElementById('preview-text');
    const previewStars = document.getElementById('preview-stars');
    const starRadios = document.getElementsByName('rating');

    // Real-time character counter and comment preview
    function updateCommentPreview() {
        const length = commentInput.value.length;
        counterDisplay.textContent = `${length} / 1000`;
        
        if (commentInput.value.trim().length > 0) {
            previewText.textContent = commentInput.value;
            previewText.style.fontStyle = 'italic';
            previewText.style.color = 'var(--ink)';
        } else {
            previewText.textContent = "Votre avis s'affichera ici au fur et à mesure de votre saisie...";
            previewText.style.fontStyle = 'italic';
            previewText.style.color = 'var(--ink60)';
        }
    }

    commentInput.addEventListener('input', updateCommentPreview);
    
    // Initial update on page load
    if (commentInput.value.length > 0) {
        updateCommentPreview();
    }

    // Real-time star rating preview
    function updateStarsPreview() {
        let selectedValue = 0;
        for (const radio of starRadios) {
            if (radio.checked) {
                selectedValue = parseInt(radio.value);
                break;
            }
        }
        
        if (selectedValue > 0) {
            previewStars.textContent = '★'.repeat(selectedValue) + '☆'.repeat(5 - selectedValue);
        } else {
            previewStars.textContent = '☆☆☆☆☆';
        }
    }

    // Add event listeners to radio inputs
    for (const radio of starRadios) {
        radio.addEventListener('change', updateStarsPreview);
    }

    // Initial update of stars on page load
    updateStarsPreview();
});
</script>
@endsection
