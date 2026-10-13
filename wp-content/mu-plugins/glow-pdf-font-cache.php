<?php
/**
 * Plugin Name: Glow PDF Font Cache Repair
 * Description: Resolve migrated PDF font paths against the current site's font directory.
 */
defined('ABSPATH') || exit;

add_filter('wpo_wcpdf_dompdf_options', function($options) {
    $directory = isset($options['fontDir']) ? realpath($options['fontDir']) : false;
    $content = realpath(WP_CONTENT_DIR);
    if (!$directory || !$content || strpos(wp_normalize_path($directory) . '/', trailingslashit(wp_normalize_path($content))) !== 0) {
        return $options;
    }
    $cache_file = $directory . '/installed-fonts.json';
    if (!is_file($cache_file) || !is_writable($cache_file)) {
        return $options;
    }
    $fonts = json_decode(file_get_contents($cache_file), true);
    if (!is_array($fonts)) {
        return $options;
    }
    $changed = false;
    foreach ($fonts as &$variants) {
        if (!is_array($variants)) {
            continue;
        }
        foreach ($variants as &$path) {
            if (!is_string($path) || is_file($path . '.ttf')) {
                continue;
            }
            $local = wp_normalize_path($directory . '/' . basename(str_replace('\\', '/', $path)));
            if (is_file($local . '.ttf') && is_file($local . '.ufm')) {
                $path = $local;
                $changed = true;
            }
        }
        unset($path);
    }
    unset($variants);
    if ($changed) {
        file_put_contents($cache_file, wp_json_encode($fonts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
    }
    return $options;
}, 20);
