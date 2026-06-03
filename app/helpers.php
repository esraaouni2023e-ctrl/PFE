<?php

if (!function_exists('get_pro_icon')) {
    /**
     * Translates an emoji to a professional Bootstrap Icon tag.
     *
     * @param string $emoji
     * @param string $class
     * @return string
     */
    function get_pro_icon($emoji, $class = '') {
        $mapping = [
            '🎓' => 'bi bi-mortarboard',
            '🏫' => 'bi bi-bank',
            '💻' => 'bi bi-laptop',
            '🖥️' => 'bi bi-pc-display',
            '🤖' => 'bi bi-robot',
            '🔒' => 'bi bi-shield-lock',
            '🛡️' => 'bi bi-shield',
            '🩺' => 'bi bi-heart-pulse',
            '🏥' => 'bi bi-hospital',
            '🏗️' => 'bi bi-building-gear',
            '⚙️' => 'bi bi-gear-fill',
            '📈' => 'bi bi-graph-up-arrow',
            '📊' => 'bi bi-bar-chart-line',
            '💼' => 'bi bi-briefcase',
            '🔬' => 'bi bi-virus',
            '📖' => 'bi bi-book',
            '📚' => 'bi bi-book-half',
            '⚖️' => 'bi bi-scale',
            '🎨' => 'bi bi-palette',
            '✈️' => 'bi bi-airplane',
            '🌾' => 'bi bi-flower1',
            '⚽' => 'bi bi-trophy',
            '⏱️' => 'bi bi-clock',
            '⏱' => 'bi bi-clock',
            '📍' => 'bi bi-geo-alt',
            '💰' => 'bi bi-cash-coin',
            '📝' => 'bi bi-file-earmark-text',
            '📋' => 'bi bi-clipboard-data',
            '🏢' => 'bi bi-building',
            '⭐' => 'bi bi-star-fill',
            '🌟' => 'bi bi-stars',
            '💡' => 'bi bi-lightbulb',
            '🔮' => 'bi bi-magic',
            '🧮' => 'bi bi-calculator',
            '🚀' => 'bi bi-rocket-takeoff',
            '🎯' => 'bi bi-target',
            '➕' => 'bi bi-plus-lg',
            '🌐' => 'bi bi-globe',
            '📱' => 'bi bi-phone',
        ];

        $emoji = trim($emoji);
        if (isset($mapping[$emoji])) {
            return '<i class="' . $mapping[$emoji] . ' ' . $class . '"></i>';
        }
        
        if (str_starts_with($emoji, 'bi ') || str_starts_with($emoji, 'bi-')) {
            return '<i class="' . $emoji . ' ' . $class . '"></i>';
        }

        return $emoji;
    }
}
