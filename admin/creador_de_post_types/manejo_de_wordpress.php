<?php
require_once dirname(__FILE__) . '/caracteristicas_minimas_post_type.php';

class ManejoDeWordpress extends CaracteristicasMinimasPostType
{
    private $para_armar_columnas;
    public function __construct()
    {
    }
    public function set_para_armar_columnas($valor)
    {
        $this->para_armar_columnas = $valor;
    }
    public function get_para_armar_columnas()
    {
        return $this->para_armar_columnas;
    }
}