<?php
$file = 'resources/views/student/comparateur/index.blade.php';
$content = file_get_contents($file);

function svg($path, $color = 'currentColor', $size = '1rem') {
    return "<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='$color' style='width:$size;height:$size;display:inline-block;vertical-align:middle;'>$path</svg>";
}

$chart_path = "<path stroke-linecap='round' stroke-linejoin='round' d='M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z' />";
$radar_path = "<path stroke-linecap='round' stroke-linejoin='round' d='M12 3v18M3 12h18M5.25 5.25l13.5 13.5M18.75 5.25l-13.5 13.5' />"; 
$list_path  = "<path stroke-linecap='round' stroke-linejoin='round' d='M8.25 6.75h12M8.25 12h12M8.25 17.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z' />";
$scale_path = "<path stroke-linecap='round' stroke-linejoin='round' d='M12 3v17.25m0 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM7.95 19.382c-.285.067-.56.19-.815.362L4.935 21.23a.75.75 0 11-.888-1.208l2.197-1.485c.21-.142.438-.255.677-.336a13.918 13.918 0 0110.158 0c.239.081.467.194.677.336l2.197 1.485a.75.75 0 11-.888 1.208l-2.197-1.485a2.25 2.25 0 00-.815-.362' />";

$replacements = [
    '/📊 Outil de comparaison/u' => svg($chart_path, 'var(--accent)') . ' Outil de comparaison',
    '/>📊 Comparer/u' => '>' . svg($chart_path) . ' Comparer',
    '/🕸️/u' => svg($radar_path, 'var(--accent2)', '1.2rem'),
    '/📋/u' => svg($list_path, 'var(--accent)', '1.2rem'),
    '/>📊/u' => '>' . svg($chart_path, 'var(--accent)', '1.2rem'),
    '/⚖️/u' => svg($scale_path, 'var(--ink30)', '4rem'),
    '/⏳ Chargement/u' => 'Chargement...',
    '/💰/u' => svg("<path stroke-linecap='round' stroke-linejoin='round' d='M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z' />", 'var(--accent)'),
    '/🎯/u' => svg("<path stroke-linecap='round' stroke-linejoin='round' d='M15.59 14.37a6 6 0 01-5.84 7.38 4.75 4.75 0 01-4.51-3.46 8.97 8.97 0 005.54-3.92zM9 15.165V15.303a3 3 0 01-3 3V15.303a3 3 0 013-3z' />", 'var(--accent)'),
    '/⚡/u' => svg("<path stroke-linecap='round' stroke-linejoin='round' d='M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z' />", 'var(--gold)'),
    '/🚀/u' => svg("<path stroke-linecap='round' stroke-linejoin='round' d='M15.59 14.37a6 6 0 01-5.84 7.38 4.75 4.75 0 01-4.51-3.46 8.97 8.97 0 005.54-3.92zM9 15.165V15.303a3 3 0 01-3 3V15.303a3 3 0 013-3z' />", 'var(--accent3)'),
    '/🔓/u' => svg("<path stroke-linecap='round' stroke-linejoin='round' d='M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z' />", 'var(--accent)'),
    '/🏛️/u' => svg("<path stroke-linecap='round' stroke-linejoin='round' d='M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z' />"),
    '/📍/u' => svg("<path stroke-linecap='round' stroke-linejoin='round' d='M15 10.5a3 3 0 11-6 0 3 3 0 016 0z' /><path stroke-linecap='round' stroke-linejoin='round' d='M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z' />"),
    '/📐/u' => svg("<path stroke-linecap='round' stroke-linejoin='round' d='M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25' />"),
    '/⏱/u' => svg("<path stroke-linecap='round' stroke-linejoin='round' d='M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z' />"),
    '/🏷️/u' => svg("<path stroke-linecap='round' stroke-linejoin='round' d='M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.659A2.25 2.25 0 009.568 3z' /><path stroke-linecap='round' stroke-linejoin='round' d='M6 6h.008v.008H6V6z' />"),
];

foreach ($replacements as $regex => $rep) {
    $content = preg_replace($regex, $rep, $content);
}

file_put_contents($file, $content);
echo \"Comparator replacement done\n\";
