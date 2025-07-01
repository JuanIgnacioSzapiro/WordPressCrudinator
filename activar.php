<?php
/**
 * Ejecuciones al momento de activación del plugin
 */
require_once dirname(__FILE__) . '/admin/activaciones/constantes.php';
function activar_crudinator()
{
    cargar_configuracion_desde_csv();

    // Métodos públicos para hooks
    add_action('wp_enqueue_scripts', 'cargar_recursos'); // Carga de js y css
}
/**
 * Carga de recursos de estilos (css) y scripts (js) PROPIOS DE ESTE PLUGIN
 * @return void
 */
function cargar_recursos()
{
    crudinator_cargar_estilos(); // Carga de archivos css
    crudinator_cargar_scripts(); // Carga de archivos js
}