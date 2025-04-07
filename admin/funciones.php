<?php
function espiar($texto, $data)
{
    error_log($texto . ': ' . print_r($data, true));
    return $data;
}

function manifestar_errores_por_consola($ubicacion, $valor){
    espiar("Error en {$ubicacion}", $valor);
}