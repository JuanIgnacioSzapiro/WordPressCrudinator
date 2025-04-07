<?php
require_once dirname(__FILE__) . '/../templetes/navbar.php';
require_once dirname(__FILE__) . '/../funciones.php';
require_once dirname(__FILE__) . '/../templetes/redirect.php';

// Verificar si las constantes de WordPress están definidas
if (!defined('ABSPATH')) {
    exit; // Salir si se accede directamente
}

// 1. Crear el shortcode
add_shortcode('menu_de_inicio', 'menu_de_inicio');
function menu_de_inicio()
{
    ob_start();
    if (is_user_logged_in()) {
        obtener_navbar();
    } else {
        controlar_acceso_pagina_con_shortcode();
    }
    return ob_get_clean();
}
