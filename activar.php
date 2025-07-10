<?php
/**
 * Ejecuciones al momento de activación del plugin
 */
require_once dirname(__FILE__) . '/admin/base_de_datos/manejo_sql.php';

require_once dirname(__FILE__) . '/admin/activaciones/tablas_relacionales_sql.php';


/**
 * Activa el plugin
 * Carga configuración de variables globales constantes a través de un csv
 * Agrega css y js
 * Crea base de datos
 * @param array $tipos_de_post // Array asociado [prefijo_de_area => array_de_tipos_de_post_del_area]
 * @return void
 */
function activar_crudinator($tipos_de_post, $campos_listables)
{

    // Métodos públicos para hooks
    add_action('wp_enqueue_scripts', 'cargar_recursos'); // Carga de js y css

    crear_totalidad_de_base_de_datos($tipos_de_post, $campos_listables);
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
/**
 * Crea las tablas sql según se requiera
 * @param array $tipos_de_post_con_prefijo_de_area // Array asociado [prefijo_de_area => tipos_de_post]
 * @return void
 */
function crear_totalidad_de_base_de_datos($tipos_de_post_con_prefijo_de_area, $campos_listables)
{
    foreach ($tipos_de_post_con_prefijo_de_area as $prefijo_de_area => $tipos_de_post) {
        foreach ($tipos_de_post as $tipo_de_post) {
            crear_tabla($tipo_de_post->get_id_post_type(), $prefijo_de_area, '');
        }
    }

    crear_tablas_relacionales($campos_listables);
}
