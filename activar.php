<?php
require_once dirname(__FILE__) . '/admin/activaciones/constantes.php';

function activar_crudinator()
{
    // Ejecutar al inicializar el plugin
    add_action('init', 'cargar_configuracion_desde_csv');
}