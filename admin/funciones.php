<?php
/**
 * Muestra en la consola de debug el mismo valor que retorna
 * @param string $texto Se recomienda poner la rua de origen del valor
 * @param mixed $data Valor (valores como objetos y arreglos se van a ver con un formato apto para una lectura clara)
 * @return mixed Valor ingresado
 */
function espiar($texto, $data)
{
    error_log($texto . ': ' . print_r($data, true));
    echo $texto . ': ' . print_r($data, true);
    return $data;
}

/**
 * Summary of manifestar_errores_por_consola
 * @param string $ubicacion Ubicación del error o mensaje
 * @param mixed $valor  Valor (valores como objetos y arreglos se van a ver con un formato apto para una lectura clara)
 * @return string Devuelve el error
 */
function manifestar_errores_por_consola($ubicacion, $valor){
    espiar("Error en {$ubicacion}", $valor);
    return "Error en {$ubicacion}";
}

/**
 * Búsqueda SQL
 * @param string $query SQL query que ejecuta una búsqueda
 * @return mixed Valor/es buscado/s
 */
function obtener_resultado_query($query)
{
    global $wpdb;
    // Es buena práctica usar prepare() para evitar inyecciones SQL
    return $wpdb->get_results($query);
}

function controlar_acceso_pagina_con_shortcode(){
    
}