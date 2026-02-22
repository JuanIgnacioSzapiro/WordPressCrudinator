<?php
class CarrilesDeDatos
{
    protected $manejo_de_sql;
    public function __construct()
    {
        $this->set_manejo_de_sql();
    }
    public function get_manejo_de_sql()
    {
        return $this->manejo_de_sql;
    }
    public function set_manejo_de_sql()
    {
        $this->manejo_de_sql = new ManejoDeSQL();
    }
    /**
     * 
     * @param mixed $post_id
     * @param mixed $id_post_type_perteneciente
     * @param mixed $contenido
     * @return void
     */
    public function entrada_a_base_de_datos($post_id, $id_post_type_perteneciente, $contenido)
    {
        espiar('carriles_de_datos.php->entrada_a_base_de_datos', $this->get_manejo_de_sql()->get_conversor_de_datos()->acumulador_de_datos($contenido));
    }
}