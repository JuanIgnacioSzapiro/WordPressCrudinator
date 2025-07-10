<?php
require_once dirname(__FILE__) . '/../base_de_datos/manejo_sql.php';

function crear_tablas_relacionales($campos_listables)
{
    foreach ($campos_listables as $nombre_de_tabla) {
        crear_tabla($nombre_de_tabla, '', 'texto VARCHAR(255)');
        crear_tabla('listado_de_' . $nombre_de_tabla, '', 'id_post INT, id_' . $nombre_de_tabla . ' INT');
    }
}

function borrar_tablas_relacionales($campos_listables)
{
    foreach ($campos_listables as $nombre_de_tabla) {
        borrar_tabla($nombre_de_tabla, '');
        borrar_tabla('listado_de_' . $nombre_de_tabla, '');
    }
}