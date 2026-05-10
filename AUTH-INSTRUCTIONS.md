✅ Intégration thème & logo — CapAvenir

Résumé rapide
- J'ai ajouté des vues d'authentification stylées : `resources/views/auth/login.blade.php` et `resources/views/auth/register.blade.php`.
- Layout principal : `resources/views/layouts/auth.blade.php`.
- Feuille de style dédiée : `public/css/auth.css`.
- Routes de démo (UI seulement) dans `routes/web.php`: `login`, `register`, `password.request`.

Étapes pour finir l'intégration (recommandé)
1. Ajouter ton logo original dans : `public/images/capavenir.png` (remplace le fichier existant si tu en as un).
2. Installer Laravel Breeze (pour la logique d'auth complète) :
   - `composer require laravel/breeze --dev`
   - `php artisan breeze:install blade`
   - `npm install && npm run dev`
   - `php artisan migrate`
3. Si tu veux compiler Tailwind et utiliser les couleurs du thème :
   - Ajoute dans `tailwind.config.js` un objet `theme.extend.colors` avec
     - `primary-600: '#0f6b8f'`
     - `primary-500: '#1eaec0'`
     - `primary-400: '#2bb7c8'`
     - `accent: '#ffd166'`
4. Personnalisation UI :
   - Ajuste `public/css/auth.css` selon ta charte (j'ai mis des variables CSS de base).
   - Remplace `{{ asset('images/capavenir.png') }}` par l'image fournie (format .png ou .svg conseillé).

Notes
- Les pages sont pour l'instant UI-only (les formulaires postent vers les routes `login`/`register` qui existent en GET pour la démo). Pour le fonctionnement complet (validation, envoi email reset, etc.), installe Breeze ou Fortify.

Si tu veux, je peux :
- Installer et configurer Breeze + Tailwind, compiler les assets et remplacer les vues Breeze par cette charte graphique ✅
- Ou bien convertir le design en composants Blade réutilisables (boutons, inputs, cartes) ✅

Que préfères-tu que je fasse ensuite ?