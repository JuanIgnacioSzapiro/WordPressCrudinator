<?php //filtro.php

class Filtro
{
    protected $id_filtro;
    protected $query;
    protected $ids;
    protected $texto;
    public function __construct($id_filtro, $query, $ids, $texto)
    {
        $this->set_id_filtro($id_filtro);
        $this->set_query($query);
        $this->set_ids($ids);
        $this->set_texto($texto);
    }
    public function get_id_filtro()
    {
        return $this->id_filtro;
    }
    public function get_query()
    {
        return $this->query;
    }
    public function get_ids()
    {
        return $this->ids;
    }
    public function get_texto()
    {
        return $this->texto;
    }

    public function set_id_filtro($valor)
    {
        $this->id_filtro = $valor;
    }
    public function set_query($valor)
    {
        $this->query = $valor;
    }
    public function set_ids($valor)
    {
        $this->ids = $valor;
    }
    public function set_texto($valor)
    {
        $this->texto = $valor;
    }
}
