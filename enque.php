<?php
// Registrar estilos y scripts
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