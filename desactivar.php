<?php
require_once dirname(__FILE__) . '/admin/activaciones/tablas_relacionales_sql.php';

/**
 * Desactiva el plugin
 * @param array $tipos_de_post // Array asociado [prefijo_de_area => array_de_tipos_de_post_del_area]
 * @return void
 */
function desactivar_crudinator($tipos_de_post, $campos_listables)
{
    borrar_totalidad_de_base_de_datos($tipos_de_post);

    borrar_tablas_relacionales($campos_listables);
}

/**
 * Crea las tablas sql según se requiera
 * @param array $tipos_de_post_con_prefijo_de_area // Array asociado [prefijo_de_area => tipos_de_post]
 * @return void
 */
function borrar_totalidad_de_base_de_datos($tipos_de_post_con_prefijo_de_area)
{
    foreach ($tipos_de_post_con_prefijo_de_area as $prefijo_de_area => $tipos_de_post) {
        foreach ($tipos_de_post as $tipo_de_post) {
            borrar_tabla($tipo_de_post->get_id_post_type(), $prefijo_de_area);
        }
    }
}
