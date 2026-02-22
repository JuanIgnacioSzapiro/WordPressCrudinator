<?php
require_once dirname(__FILE__) . '/conversor_de_datos.php';
class ManejoDeSQL
{
    protected $conversor_de_datos;
    public function __construct()
    {
        $this->set_conversor_de_datos();
    }
    public function get_conversor_de_datos()
    {
        return $this->conversor_de_datos;
    }
    public function set_conversor_de_datos()
    {
        $this->conversor_de_datos = new ConversorDeDatos;
    }
    private function ejecutar_query($query)
    {
        return $GLOBALS['wpdb']->query($query);
    }
    /**
     * Crea tablas SQL
     * @param string $nombre_de_tabla
     * @param string $prefijo_de_area
     * @param string $columnas
     * @return void
     */
    public function crear_tabla($nombre_de_tabla, $prefijo_de_area, $columnas)
    {
        $this->ejecutar_query("CREATE TABLE IF NOT EXISTS " . $this->get_conversor_de_datos()->concatenar_a_nombre_de_tabla_los_prefijos($nombre_de_tabla, $prefijo_de_area) . "(id INT AUTO_INCREMENT PRIMARY KEY" . (($columnas != '') ? ', ' . $columnas : '') . ')');
    }
    /**
     * Vacía tablas SQL
     * @param string $nombre_de_tabla
     * @param string $prefijo_de_area
     * @return void
     */
    public function vaciar_tabla($nombre_de_tabla, $prefijo_de_area)
    {
        $this->ejecutar_query("TRUNCATE TABLE " . $this->get_conversor_de_datos()->concatenar_a_nombre_de_tabla_los_prefijos($nombre_de_tabla, $prefijo_de_area) . "");
    }
    /**
     * Borra tablas SQL
     * @param string $nombre_de_tabla
     * @param string $prefijo_de_area
     * @return void
     */
    public function borrar_tabla($nombre_de_tabla, $prefijo_de_area)
    {
        $this->ejecutar_query("DROP TABLE IF EXISTS " . $this->get_conversor_de_datos()->concatenar_a_nombre_de_tabla_los_prefijos($nombre_de_tabla, $prefijo_de_area) . "");
    }
}
