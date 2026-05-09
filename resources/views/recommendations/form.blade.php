<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommandations de filières — CapAvenir</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Fraunces:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --accent: #d4622a;
            --accent2: #1a4f6e;
            --ink: #0b0c10;
            --paper: #f7f5f0;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--paper);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .form-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(11,12,16,.10);
            padding: 3rem 3.5rem;
            max-width: 520px;
            width: 100%;
        }
        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(212,98,42,.10);
            color: var(--accent);
            border-radius: 50px;
            padding: .3rem .9rem;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
        }
        .form-title {
            font-family: 'Fraunces', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--ink);
            line-height: 1.2;
            margin-bottom: .5rem;
        }
        .form-subtitle {
            color: rgba(11,12,16,.55);
            font-size: .92rem;
            margin-bottom: 2rem;
        }
        .form-label {
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(11,12,16,.6);
            margin-bottom: .4rem;
        }
        .form-control, .form-select {
            border: 1.5px solid rgba(11,12,16,.15);
            border-radius: 10px;
            padding: .75rem 1rem;
            font-size: .95rem;
            transition: border-color .2s, box-shadow .2s;
            background: #fafaf8;
        }
        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(212,98,42,.12);
            background: #fff;
        }
        .riasec-hint {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
            margin-top: .5rem;
        }
        .riasec-chip {
            background: rgba(26,79,110,.08);
            color: var(--accent2);
            border-radius: 50px;
            padding: .2rem .65rem;
            font-size: .73rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
            border: none;
            font-family: inherit;
        }
        .riasec-chip:hover { background: rgba(26,79,110,.18); }
        .btn-submit {
            background: linear-gradient(135deg, var(--accent) 0%, #c04a15 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: .85rem 1.5rem;
            font-size: .95rem;
            font-weight: 600;
            width: 100%;
            margin-top: 1.5rem;
            transition: transform .15s, box-shadow .2s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212,98,42,.35);
            color: #fff;
        }
        .alert-custom {
            border-radius: 10px;
            font-size: .9rem;
        }
    </style>
</head>
<body>
<div class="form-card">

    <div class="brand-badge">
        <i class="bi bi-stars"></i> CapAvenir IA
    </div>

    <h1 class="form-title">Trouver ma filière</h1>
    <p class="form-subtitle">
        Renseignez votre score et votre profil RIASEC pour obtenir des recommandations personnalisées.
    </p>

    {{-- Alertes session --}}
    @if (session('error'))
        <div class="alert alert-danger alert-custom d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-custom d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-info-circle-fill flex-shrink-0"></i>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('recommendations.get') }}" novalidate>
        @csrf

        {{-- Score FG --}}
        <div class="mb-4">
            <label for="score_fg" class="form-label">
                Score du baccalauréat <span class="text-muted fw-normal">(0 – 200)</span>
            </label>
            <div class="input-group">
                <span class="input-group-text" style="border-radius:10px 0 0 10px;border:1.5px solid rgba(11,12,16,.15);background:#f0ede8;">
                    <i class="bi bi-mortarboard-fill text-secondary"></i>
                </span>
                <input
                    type="number"
                    id="score_fg"
                    name="score_fg"
                    class="form-control @error('score_fg') is-invalid @enderror"
                    style="border-radius:0 10px 10px 0;"
                    value="{{ old('score_fg') }}"
                    min="0" max="200" step="0.01"
                    placeholder="ex: 145.5"
                    required
                >
                @error('score_fg')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Code RIASEC --}}
        <div class="mb-3">
            <label for="riasec_input" class="form-label">
                Code RIASEC <span class="text-muted fw-normal">(3 lettres)</span>
            </label>
            <div class="input-group">
                <span class="input-group-text" style="border-radius:10px 0 0 10px;border:1.5px solid rgba(11,12,16,.15);background:#f0ede8;">
                    <i class="bi bi-person-circle text-secondary"></i>
                </span>
                <input
                    type="text"
                    id="riasec_input"
                    name="riasec_input"
                    class="form-control text-uppercase @error('riasec_input') is-invalid @enderror"
                    style="border-radius:0 10px 10px 0;letter-spacing:.15em;font-weight:700;"
                    value="{{ old('riasec_input') }}"
                    maxlength="3"
                    placeholder="ex: ISA"
                    required
                >
                @error('riasec_input')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Suggestions rapides --}}
            <div class="riasec-hint mt-2">
                <small class="text-muted me-1" style="font-size:.72rem;align-self:center;">Suggestions :</small>
                @foreach (['RIA', 'ISA', 'ARS', 'SCE', 'ECS', 'CRE', 'RIC', 'AIE', 'SIE'] as $code)
                    <button type="button" class="riasec-chip" onclick="document.getElementById('riasec_input').value='{{ $code }}'">
                        {{ $code }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- RIASEC legend --}}
        <div class="p-3 mt-2 mb-1 rounded-3" style="background:rgba(26,79,110,.05);font-size:.78rem;color:rgba(11,12,16,.6);">
            <strong style="color:var(--accent2);">Légende RIASEC :</strong>
            <span class="ms-1"><b>R</b>éaliste · <b>I</b>nvestigateur · <b>A</b>rtiste · <b>S</b>ocial · <b>E</b>ntreprenant · <b>C</b>onventionnel</span>
        </div>

        <button type="submit" class="btn btn-submit">
            <i class="bi bi-search me-2"></i> Obtenir mes recommandations
        </button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ url()->previous() }}" class="text-muted" style="font-size:.83rem;">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>
</div>

<script>
    // Force uppercase on riasec input
    document.getElementById('riasec_input').addEventListener('input', function () {
        this.value = this.value.toUpperCase().replace(/[^A-Z]/g, '');
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
