<?php
/**
 * Desactiva el plugin
 * @param array $tipos_de_post // Array asociado [prefijo_de_area => array_de_tipos_de_post_del_area]
 * @return void
 */
function desactivar_crudinator($tipos_de_post)
{
    borrar_totalidad_de_base_de_datos($tipos_de_post);
}
/**
 * Borra las tablas sql según se requiera
 * @param array $tipos_de_post_con_prefijo_de_area // Array asociado [prefijo_de_area => tipos_de_post]
 * @return void
 */
function borrar_totalidad_de_base_de_datos($tipos_de_post_con_prefijo_de_area)
{
    foreach ($tipos_de_post_con_prefijo_de_area as $prefijo_de_area => $tipos_de_post) {
        foreach ($tipos_de_post as $tipo_de_post) {
            // Borrado de tablas
            borrar_tabla($tipo_de_post->get_id_post_type(), $prefijo_de_area);
            // Borrado de sus tablas relacionales
            foreach ($tipo_de_post->get_ids_caja_metadata_campos_clonables() as $nombre_tabla_relacional) {
                // Borrado de tablas intermedias (listas)
                borrar_tabla($nombre_tabla_relacional, $prefijo_de_area . '_' . $tipo_de_post->get_id_post_type() . '_listado_de');
                // Borrado de tablas 'hoja'
                borrar_tabla($nombre_tabla_relacional, $prefijo_de_area . '_' . $tipo_de_post->get_id_post_type());
            }
        }
    }
}
