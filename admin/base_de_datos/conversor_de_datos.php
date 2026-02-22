<?php
class ConversorDeDatos
{
    public function __construct()
    {
    }
    /**
     * Devuelve el tipo de dato sql que se adecúa a la clase
     * @param string $caso
     * @return string
     */
    public function clase_a_tipo_de_dato_sql($caso)
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
    /**
     * Concatenador de nombres de tablas, en caso de superar los 64 caracteres es acortado
     * @param string $nombre_de_tabla
     * @param string $prefijo_de_area
     * @return string
     */
    public function concatenar_a_nombre_de_tabla_los_prefijos($nombre_de_tabla, $prefijo_de_area)
    {
        $nombre = ("wp_" . $GLOBALS['prefijo_universal'] . "_" . (($prefijo_de_area != '') ? ($prefijo_de_area . "_") : '') . $nombre_de_tabla);
        return strlen($nombre) > 64 ? implode('_', array_map(function ($sub) {
            return str_split($sub, 3)[0];
        }, explode('_', $nombre))) : $nombre;
    }
    /**
     * Acumula en un solo array todos los datos pasados por $_POST 
     * @param CajasDeMetadata $contenido
     * @return array
     */
    public function acumulador_de_datos($contenido)
    {
        $valores = []; // Próximo lugar de almacenamiento exclusivo para los valores obtenidos a través de $_POST
        if (!empty($_POST)) {
            foreach ($contenido as $individual) {
                if (get_class($individual) == 'Checkbox') {
                    $valores_de_checkbox = []; // Próximo lugar de almacenamiento exclusivo para los valores de checkboxes
                    $cantidad_opciones = count($individual->get_opciones()); // Se cuenta (desde 0 inclusive) la cantidad de opciones que puede haber como máximo
                    for ($contador_de_opciones = 0; $contador_de_opciones < $cantidad_opciones; $contador_de_opciones++) { // Se recorre el array de la check list
                        if (array_key_exists($individual->get_id_caja_metadata() . '_' . $contador_de_opciones, $_POST)) { // Para evitar un error de que no exista la key, se filtra
                            array_push(
                                $valores_de_checkbox,
                                $individual->get_id_caja_metadata() . '_' . $contador_de_opciones
                            ); // Se concatenan los diferentes id de la lista de checkboxes
                        }
                    }
                    $valores = array_merge($valores, [$individual->get_id_caja_metadata() => $valores_de_checkbox]); // Array de opciones checked
                } else {
                    if (array_key_exists($individual->get_id_caja_metadata(), $_POST)) {
                        $valores = array_merge($valores, [$individual->get_id_caja_metadata() => $_POST[$individual->get_id_caja_metadata()]]); // Se almacena 1:1
                    }
                }
            }
        }
        return $valores;
    }
}
