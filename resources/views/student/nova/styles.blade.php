<link rel="preconnect" href="https://fonts.googleapis.com">
<link
    href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;1,9..144,300;1,9..144,400&display=swap"
    rel="stylesheet">

<style>
    /* ════════════════════════════════════════════
   NOVA — CapAvenir System
════════════════════════════════════════════ */
    .db {
        --ink: #0b0c10;
        --paper: #f7f5f0;
        --cream: #ede9e1;
        --warm: #e8e1d4;
        --accent: #d4622a;
        /* terracotta */
        --accent2: #1a4f6e;
        /* marine */
        --accent3: #4a7c59;
        /* sage */
        --gold: #c8973a;
        --ink60: rgba(11, 12, 16, .6);
        --ink30: rgba(11, 12, 16, .3);
        --ink15: rgba(11, 12, 16, .15);
        --ink10: rgba(11, 12, 16, .1);
        --ink06: rgba(11, 12, 16, .06);
        --r: 6px;
        --rl: 16px;
        --rx: 999px;
        --ease: cubic-bezier(.16, 1, .3, 1);

        font-family: 'DM Sans', sans-serif;
        color: var(--ink);
        background: var(--paper);
        padding: 2rem 3rem 5rem;
    }

    [data-theme="dark"] .db {
        --ink: #f0ede6;
        --paper: #10100d;
        --cream: #18170f;
        --warm: #1f1e14;
        --ink60: rgba(240, 237, 230, .6);
        --ink30: rgba(240, 237, 230, .3);
        --ink15: rgba(240, 237, 230, .15);
        --ink10: rgba(240, 237, 230, .08);
        --ink06: rgba(240, 237, 230, .04);
    }

    [data-theme="light"] .db {
        --ink: #0b0c10;
        --paper: #f7f5f0;
        --cream: #ede9e1;
        --warm: #e8e1d4;
        --ink60: rgba(11, 12, 16, .6);
        --ink30: rgba(11, 12, 16, .3);
        --ink15: rgba(11, 12, 16, .15);
        --ink10: rgba(11, 12, 16, .1);
        --ink06: rgba(11, 12, 16, .06);
    }

    .db *,
    .db *::before,
    .db *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    .db a {
        color: inherit;
        text-decoration: none;
    }

    .db .rev {
        opacity: 0;
        transform: translateY(28px);
        transition: opacity .8s var(--ease), transform .8s var(--ease);
    }

    .db .rev.vis {
        opacity: 1;
        transform: none;
    }

    .db .stag {
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--accent);
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: 1rem;
    }

    .db .stag::before {
        content: '';
        width: 18px;
        height: 1px;
        background: var(--accent);
    }

    .db .sh {
        font-family: 'Fraunces', serif;
        font-size: clamp(1.8rem, 3.5vw, 3rem);
        font-weight: 300;
        letter-spacing: -.03em;
        line-height: 1.1;
    }

    .db .sh em {
        font-style: italic;
        color: var(--accent);
    }

    .db .card {
        background: var(--cream);
        border: 1px solid var(--ink10);
        border-radius: var(--rl);
        transition: all .3s var(--ease);
    }

    .db .card:hover {
        border-color: var(--ink30);
    }

    .db .btn-fill {
        display: inline-flex;
        align-items: center;
        gap: .6rem;
        padding: .85rem 1.75rem;
        border-radius: var(--r);
        background: var(--accent);
        color: #fff;
        font-family: 'DM Sans', sans-serif;
        font-size: .9rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        text-decoration: none;
        box-shadow: 0 6px 24px color-mix(in srgb, var(--accent) 38%, transparent);
        transition: all .3s var(--ease);
    }

    .db .btn-fill:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 36px color-mix(in srgb, var(--accent) 45%, transparent);
    }

    .db .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .75rem 1.5rem;
        border-radius: var(--r);
        background: transparent;
        border: 1px solid var(--ink30);
        color: var(--ink);
        font-family: 'DM Sans', sans-serif;
        font-size: .88rem;
        font-weight: 500;
        cursor: pointer;
        transition: all .25s;
        text-decoration: none;
    }

    .db .btn-ghost:hover {
        background: var(--ink10);
        border-color: var(--ink60);
    }

    .db .input-field {
        width: 100%;
        padding: .8rem 1rem;
        background: var(--paper);
        border: 1px solid var(--ink10);
        border-radius: var(--r);
        color: var(--ink);
        font-family: 'DM Sans', sans-serif;
        font-size: .9rem;
        transition: border-color .2s;
    }

    .db .input-field:focus {
        outline: none;
        border-color: var(--accent);
    }

    .db .pill {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .3rem .85rem;
        border-radius: var(--rx);
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .06em;
    }

    .db .pill-accent {
        background: color-mix(in srgb, var(--accent) 10%, transparent);
        color: var(--accent);
        border: 1px solid color-mix(in srgb, var(--accent) 25%, transparent);
    }

    .db .pill-sage {
        background: color-mix(in srgb, var(--accent3) 10%, transparent);
        color: var(--accent3);
        border: 1px solid color-mix(in srgb, var(--accent3) 25%, transparent);
    }

    .db-section {
        margin-bottom: 3rem;
    }

    .db-section-header {
        margin-bottom: 1.75rem;
        text-align: center;
    }

    @media (max-width: 700px) {
        .db {
            padding: 1rem 1rem 3rem;
        }
    }

    /* Nova specific styles */
    .nova-form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 768px) {
        .nova-form-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    .nova-result-card {
        padding: 2.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .nova-score-display {
        font-family: 'Fraunces', serif;
        font-size: 4.5rem;
        font-weight: 600;
        color: var(--accent);
        line-height: 1;
        text-align: center;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const revs = document.querySelectorAll('.rev');
        revs.forEach((rev, index) => {
            setTimeout(() => {
                rev.classList.add('vis');
            }, index * 100);
        });
    });
</script>