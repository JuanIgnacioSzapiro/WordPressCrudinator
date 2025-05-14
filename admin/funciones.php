<?php
function espiar($texto, $data)
{
    error_log($texto . ': ' . print_r($data, true));
    return $data;
}

function manifestar_errores_por_consola($ubicacion, $valor){
    espiar("Error en {$ubicacion}", $valor);
}

function obtener_resultado_query($query)
{
    global $wpdb;

    // Es buena práctica usar prepare() para evitar inyecciones SQL
    return $wpdb->get_results($query);
}

function controlar_acceso_pagina_con_shortcode(){
    
}