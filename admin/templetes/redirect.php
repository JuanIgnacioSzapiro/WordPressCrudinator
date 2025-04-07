<?php
// Redirección temprana para usuarios no autenticados
add_action('template_redirect', 'controlar_acceso_pagina_con_shortcode');

function controlar_acceso_pagina_con_shortcode()
{
    global $post;

    echo 'Espacio en desarrollo';
}