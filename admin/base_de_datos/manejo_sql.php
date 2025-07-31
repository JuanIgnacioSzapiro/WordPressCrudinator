<?php
function ejecutar_query($query)
{
    return $GLOBALS['wpdb']->query($query);
}
/**
 * Concatenador de nombres de tablas, en caso de superar los 64 caracteres es acortado
 * @param string $nombre_de_tabla
 * @param string $prefijo_de_area
 * @return string
 */
function concatenar_a_nombre_de_tabla_los_prefijos($nombre_de_tabla, $prefijo_de_area)
{
    $nombre = ("wp_" . $GLOBALS['prefijo_universal'] . "_" . (($prefijo_de_area != '') ? ($prefijo_de_area . "_") : '') . $nombre_de_tabla);
    return strlen($nombre) > 64 ? implode('_', array_map(function ($sub) {
        return str_split($sub, 3)[0];
    }, explode('_', $nombre))) : $nombre;
}
/**
 * Crea tablas SQL
 * @param string $nombre_de_tabla
 * @param string $prefijo_de_area
 * @param string $columnas
 * @return void
 */
function crear_tabla($nombre_de_tabla, $prefijo_de_area, $columnas)
{
    ejecutar_query("CREATE TABLE IF NOT EXISTS " . concatenar_a_nombre_de_tabla_los_prefijos($nombre_de_tabla, $prefijo_de_area) . "(id INT AUTO_INCREMENT PRIMARY KEY" . (($columnas != '') ? ', ' . $columnas : '') . ')');
}
/**
 * Vacía tablas SQL
 * @param string $nombre_de_tabla
 * @param string $prefijo_de_area
 * @return void
 */
function vaciar_tabla($nombre_de_tabla, $prefijo_de_area)
{
    ejecutar_query("TRUNCATE TABLE " . concatenar_a_nombre_de_tabla_los_prefijos($nombre_de_tabla, $prefijo_de_area) . "");
}
/**
 * Borra tablas SQL
 * @param string $nombre_de_tabla
 * @param string $prefijo_de_area
 * @return void
 */
function borrar_tabla($nombre_de_tabla, $prefijo_de_area)
{
    ejecutar_query("DROP TABLE IF EXISTS " . concatenar_a_nombre_de_tabla_los_prefijos($nombre_de_tabla, $prefijo_de_area) . "");
}
function clase_a_tipo_de_dato_sql($caso)
{
    if ($caso == 'Checkbox') {
        return 'BOOL';
    } else if ($caso == 'CajaDeFecha') {
        return 'DATE';
    } else if ($caso == 'CajaDropDown' || $caso == 'CajaDeArchivos') {
        return 'INT';
    } else {
        return 'TEXT';
    }
}