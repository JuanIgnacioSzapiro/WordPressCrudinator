<?php
/**
 * Carga de recursos de estilos (css) y scripts (js) PROPIOS DE ESTE PLUGIN
 */
add_action('wp_enqueue_scripts', 'cargar_recursos');

function cargar_recursos()
{
    crudinator_cargar_estilos();
    crudinator_cargar_scripts();
}

/**
 * Registro y encolación de estilos (css)
 */
function crudinator_cargar_estilos()
{
    wp_register_style(
        'crudinator-css',
        plugins_url('public/css/general.css', __FILE__),
        array(),
        '1.0'
    );
    wp_enqueue_style('crudinator-css');
}
/**
 * Registro y encolación de scripts (js)
 */
function crudinator_cargar_scripts()
{
    wp_register_script(
        'crudinator-js',
        plugins_url('public/js/general.js', __FILE__),
        array('jquery'),
        '1.0',
        true // Cargar en footer
    );
    wp_enqueue_script('crudinator-js');
}