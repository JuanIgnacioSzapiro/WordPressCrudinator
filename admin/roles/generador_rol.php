<?php

class TipoDeRol
{
    protected $id;
    protected $nombre_para_mostrar;
    protected $habilidades;

    public function __construct()
    {
    }

    public function get_id()
    {
        return $this->id;
    }
    public function get_nombre_para_mostrar()
    {
        return $this->nombre_para_mostrar;
    }
    public function get_habilidades()
    {
        return $this->habilidades;
    }
    public function set_id($valor)
    {
        $this->id = $valor;
    }
    public function set_nombre_para_mostrar($valor)
    {
        $this->nombre_para_mostrar = $valor;
    }
    public function set_habilidades($valor)
    {
        $this->habilidades = $valor;
    }

    public function agregar_rol()
    {
        add_role($this->id, $this->nombre_para_mostrar, $this->habilidades);
    }

    public function borrar_rol()
    {
        remove_role($this->get_id());
    }
}