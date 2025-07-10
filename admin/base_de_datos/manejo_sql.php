<?php
/**
 * Summary of ejecutar_query
 * @param string $query
 * @return mixed
 */
function ejecutar_query($query)
{
    return $GLOBALS['wpdb']->query($query);
}
function concatenar_a_nombre_de_tabla_los_prefijos($nombre_de_tabla, $prefijo_de_area)
{
    return "wp_" . $GLOBALS['prefijo_universal'] . "_" . (($prefijo_de_area != '') ? ($prefijo_de_area . "_") : '') . $nombre_de_tabla;
}
function crear_tabla($nombre_de_tabla, $prefijo_de_area, $columnas)
{
    ejecutar_query("CREATE TABLE IF NOT EXISTS " . concatenar_a_nombre_de_tabla_los_prefijos($nombre_de_tabla, $prefijo_de_area) . "(id INT AUTO_INCREMENT PRIMARY KEY" . (($columnas != '') ? ', ' . $columnas : '') . ')');
}
function vaciar_tabla($nombre_de_tabla, $prefijo_de_area)
{
    ejecutar_query("TRUNCATE TABLE " . concatenar_a_nombre_de_tabla_los_prefijos($nombre_de_tabla, $prefijo_de_area) . "");
}
function borrar_tabla($nombre_de_tabla, $prefijo_de_area)
{
    ejecutar_query("DROP TABLE IF EXISTS " . concatenar_a_nombre_de_tabla_los_prefijos($nombre_de_tabla, $prefijo_de_area) . "");
}
