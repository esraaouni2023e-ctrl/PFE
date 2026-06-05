@extends(
    auth()->user()->isCounselor() 
        ? 'layouts.counselor' 
        : (auth()->user()->isSuperAdmin() || auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin'
            ? 'layouts.admin' 
            : 'layouts.student')
)

@section('title', 'Témoignage')

@section('content')
<style>
    .testi-mob-container {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    .testi-card-mob {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 1.25rem;
        box-shadow: var(--shadow-card);
    }
    .testi-card-mob.preview-card {
        background: linear-gradient(135deg, var(--paper), var(--cream));
    }
    
    /* Star rating */
    .star-rating-mob {
        display: flex;
        gap: 0.6rem;
        flex-direction: row-reverse;
        justify-content: flex-end;
        margin-top: 0.5rem;
    }
    .star-label-mob {
        font-size: 2.2rem;
        color: var(--ink15);
        cursor: pointer;
        transition: color 0.2s, transform 0.2s;
    }
    .star-rating-mob input:checked ~ .star-label-mob,
    .star-rating-mob .star-label-mob:active,
    .star-rating-mob .star-label-mob:hover,
    .star-rating-mob .star-label-mob:hover ~ .star-label-mob {
        color: var(--gold);
    }

    /* Textarea & Buttons */
    .label-mob {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ink30);
        display: block;
        margin-bottom: 4px;
    }
    .textarea-mob {
        width: 100%;
        min-height: 120px;
        padding: 0.75rem;
        background: var(--cream);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        color: var(--ink);
        font-family: inherit;
        font-size: 16px !important; /* Prevents auto-zoom */
        line-height: 1.5;
        outline: none;
        resize: vertical;
    }
    .textarea-mob:focus {
        border-color: var(--accent);
    }

    .btn-submit-mob {
        width: 100%;
        min-height: 44px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: var(--r);
        font-weight: 700;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px color-mix(in srgb, var(--accent) 30%, transparent);
        cursor: pointer;
        margin-top: 1rem;
    }
    .btn-submit-mob:active {
        transform: translateY(1px);
    }

    /* Badges */
    .badge-mob {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        border-radius: var(--rx);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-mob.green  { background: color-mix(in srgb, var(--success) 10%, transparent); color: var(--success); }
    .badge-mob.amber  { background: color-mix(in srgb, var(--gold) 10%, transparent); color: var(--gold); }
    .badge-mob.red    { background: color-mix(in srgb, var(--red) 10%, transparent); color: var(--red); }
    .badge-mob.violet { background: color-mix(in srgb, var(--accent2) 10%, transparent); color: var(--accent2); }
</style>

<div class="testi-mob-container">
    {{-- Header --}}
    <div>
        <h1 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 700; color: var(--ink); margin-bottom: 2px;">
            Mon Témoignage
        </h1>
        <p style="font-size: 0.8rem; color: var(--ink60);">Partagez votre retour d'expérience sur la plateforme</p>
    </div>

    @if(session('success'))
        <div style="background: color-mix(in srgb, var(--success) 8%, var(--paper)); border: 1px solid color-mix(in srgb, var(--success) 20%, transparent); color: var(--success); padding: 0.75rem; border-radius: var(--r); font-size: 0.8rem; font-weight: 600; text-align: center;">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Submission Card --}}
    <section class="testi-card-mob">
        <form action="{{ route('testimonial.update') }}" method="POST">
            @csrf
            
            @if($testimonial->id)
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                    <span style="font-size: 0.75rem; font-weight: 600; color: var(--ink60);">Statut :</span>
                    @if($testimonial->status === 'approved')
                        <span class="badge-mob green">Validé</span>
                    @elseif($testimonial->status === 'pending')
                        <span class="badge-mob amber">En attente</span>
                    @elseif($testimonial->status === 'rejected')
                        <span class="badge-mob red">Rejeté</span>
                    @elseif($testimonial->status === 'archived')
                        <span class="badge-mob violet">Archivé</span>
                    @endif
                </div>
            @endif

            {{-- Stars --}}
            <div style="margin-bottom: 1.25rem;">
                <label class="label-mob">Votre note</label>
                <div class="star-rating-mob">
                    <input type="radio" id="star5" name="rating" value="5" {{ old('rating', $testimonial->rating) == 5 ? 'checked' : '' }} style="display:none;" />
                    <label for="star5" class="star-label-mob">★</label>

                    <input type="radio" id="star4" name="rating" value="4" {{ old('rating', $testimonial->rating) == 4 ? 'checked' : '' }} style="display:none;" />
                    <label for="star4" class="star-label-mob">★</label>

                    <input type="radio" id="star3" name="rating" value="3" {{ old('rating', $testimonial->rating) == 3 ? 'checked' : '' }} style="display:none;" />
                    <label for="star3" class="star-label-mob">★</label>

                    <input type="radio" id="star2" name="rating" value="2" {{ old('rating', $testimonial->rating) == 2 ? 'checked' : '' }} style="display:none;" />
                    <label for="star2" class="star-label-mob">★</label>

                    <input type="radio" id="star1" name="rating" value="1" {{ old('rating', $testimonial->rating) == 1 ? 'checked' : '' }} style="display:none;" />
                    <label for="star1" class="star-label-mob">★</label>
                </div>
                @error('rating')
                    <p style="color: var(--red); font-size: 0.75rem; margin-top: 0.25rem; font-style: italic;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Comment --}}
            <div style="margin-bottom: 1rem;">
                <label for="comment" class="label-mob">Votre avis détaillé</label>
                <textarea name="comment" id="comment" class="textarea-mob" placeholder="Parlez-nous de votre expérience..." required>{{ old('comment', $testimonial->comment) }}</textarea>
                <div style="display: flex; justify-content: space-between; margin-top: 0.25rem; font-size: 0.7rem; color: var(--ink30); font-weight: 600;">
                    <span>Min. 10 car., Max. 1000</span>
                    <span id="char-counter">0 / 1000</span>
                </div>
                @error('comment')
                    <p style="color: var(--red); font-size: 0.75rem; margin-top: 0.25rem; font-style: italic;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-submit-mob">
                <i class="bi bi-send-fill"></i> Soumettre mon avis
            </button>
        </form>
    </section>

    {{-- Live Preview Card --}}
    <section class="testi-card-mob preview-card">
        <h4 style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink30); margin-bottom: 0.75rem;">Rendu en direct</h4>
        
        <div style="display: flex; flex-direction: column; justify-content: space-between; min-height: 140px;">
            <div>
                <p id="preview-text" style="font-style: italic; font-size: 0.88rem; line-height: 1.5; color: var(--ink60); word-break: break-word;">
                    Votre avis s'affichera ici au fur et à mesure...
                </p>
            </div>
            
            <div style="display: flex; align-items: center; gap: 0.75rem; border-top: 1px solid var(--glass-border); padding-top: 0.75rem; margin-top: 0.75rem;">
                <div style="width: 38px; height: 38px; border-radius: 50%; background: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 700; color: #fff; overflow: hidden; flex-shrink: 0; border: 1.5px solid var(--paper); box-shadow: var(--shadow-card);">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div style="flex: 1; min-width: 0;">
                    <h5 style="font-weight: 700; font-size: 0.85rem; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin: 0;">{{ $user->name }}</h5>
                    <p style="font-size: 0.68rem; font-weight: 600; color: var(--accent); text-transform: uppercase; margin: 0;">
                        @if($user->role === 'student')
                            Étudiant · CapAvenir
                        @elseif($user->role === 'counselor')
                            Conseiller · CapAvenir
                        @else
                            {{ ucfirst($user->role) }}
                        @endif
                    </p>
                    <div id="preview-stars" style="color: var(--gold); font-size: 0.78rem; margin-top: 2px;">
                        ★★★★★
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const commentInput = document.getElementById('comment');
    const counterDisplay = document.getElementById('char-counter');
    const previewText = document.getElementById('preview-text');
    const previewStars = document.getElementById('preview-stars');
    const starRadios = document.getElementsByName('rating');

    function updateCommentPreview() {
        const length = commentInput.value.length;
        counterDisplay.textContent = `${length} / 1000`;
        
        if (commentInput.value.trim().length > 0) {
            previewText.textContent = commentInput.value;
            previewText.style.fontStyle = 'italic';
            previewText.style.color = 'var(--ink)';
        } else {
            previewText.textContent = "Votre avis s'affichera ici au fur et à mesure...";
            previewText.style.fontStyle = 'italic';
            previewText.style.color = 'var(--ink60)';
        }
    }

    commentInput.addEventListener('input', updateCommentPreview);
    if (commentInput.value.length > 0) {
        updateCommentPreview();
    }

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

    for (const radio of starRadios) {
        radio.addEventListener('change', updateStarsPreview);
    }
    updateStarsPreview();
});
</script>
@endsection
