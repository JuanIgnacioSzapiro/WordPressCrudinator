<?php
/**
 * Ejecuciones al momento de activación del plugin
 */
require_once dirname(__FILE__) . '/admin/base_de_datos/manejo_sql.php';
/**
 * Activa el plugin
 * Carga configuración de variables globales constantes a través de un csv
 * Agrega css y js
 * Crea base de datos
 * @param array $tipos_de_post // Array asociado [prefijo_de_area => array_de_tipos_de_post_del_area]
 * @return void
 */
function activar_crudinator($tipos_de_post)
{
    // Creacion de bases de datos
    crear_totalidad_de_base_de_datos($tipos_de_post);
}
/**
 * Crea las tablas sql según se requiera
 * @param array $tipos_de_post_con_prefijo_de_area // Array asociado [prefijo_de_area => tipos_de_post]
 * @return void
 */
function crear_totalidad_de_base_de_datos($tipos_de_post_con_prefijo_de_area)
{
    // Se usa más adelante para almacenar las características de campos no clonables
    $contenido_no_clonable = [];
    $fragmento_de_query_para_columnas = '';
    $contador_de_comas = 0;
    $manejo_de_sql = new ManejoDeSQL;
    $conversor_de_datos = new ConversorDeDatos;
    foreach ($tipos_de_post_con_prefijo_de_area as $prefijo_de_area => $tipos_de_post) {
        foreach ($tipos_de_post as $tipo_de_post) {
            $fragmento_de_query_para_columnas = '';
            $contador_de_comas = 0;
            // Se filtran los ids y las clases de los diferentes tipos de contenido
            $contenido_no_clonable = $tipo_de_post->get_ids_caja_metadata_campos(0);
            foreach ($contenido_no_clonable as $id_contenido => $caso) {
                // Se arma un único fragmento de query para crear las columnas
                $fragmento_de_query_para_columnas .= $id_contenido . ' ' . $conversor_de_datos->clase_a_tipo_de_dato_sql($caso) . ($contador_de_comas < count($contenido_no_clonable) - 1 ? ', ' : '');
                $contador_de_comas += 1;
            }
            // Creación de la tabla principal
            $manejo_de_sql->crear_tabla($tipo_de_post->get_id_post_type(), $prefijo_de_area, $fragmento_de_query_para_columnas);
            // Creación de sus tablas relacionales
            foreach ($tipo_de_post->get_ids_caja_metadata_campos(1) as $id_contenido => $caso) {
                // Creación de tablas intermedias (listas)
                $manejo_de_sql->crear_tabla($id_contenido, $prefijo_de_area . '_' . $tipo_de_post->get_id_post_type() . '_listado_de', 'id_' . $tipo_de_post->get_id_post_type() . ' INT, id_' . $id_contenido . ' INT');
                // Creación de tablas 'hoja'
                $manejo_de_sql->crear_tabla($id_contenido, $prefijo_de_area . '_' . $tipo_de_post->get_id_post_type(), 'valor ' . $conversor_de_datos->clase_a_tipo_de_dato_sql($caso));
            }
        }
    }
}
