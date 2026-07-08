<?php
/**
 * Helpers generales para el tema Grupo Fadiar
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Procesa una URL para manejar rutas internas y externas.
 *
 * - Si la URL empieza con http:// o https://, se devuelve tal cual (externa).
 * - Si no, se considera ruta interna y se le agrega home_url().
 *
 * @param string $url La URL o ruta a procesar.
 * @return string La URL procesada.
 */
function process_url($url) {
    if (empty($url)) {
        return '';
    }

    // Si empieza con http:// o https://, es externa, devolver tal cual
    if (preg_match('/^https?:\/\//i', $url)) {
        return $url;
    }

    // Es ruta interna, asegurar que empiece con / y agregar home_url()
    $url = '/' . ltrim($url, '/');
    return home_url($url);
}
