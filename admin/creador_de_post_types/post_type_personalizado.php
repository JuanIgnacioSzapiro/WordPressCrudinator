<?php
class PostTypePersonalizado extends CaracteristicasMinimasPostType
{
    private $singular;
    private $plural;
    private $femenino;
    private $nombre_para_mostrar;
    private $icono;
    private $meta;
    private $prefijo;
    private $para_armar_columnas;
    private $incrementador = 0;
    public function __construct(
        $prefijo,
        $id_post_type,
        $singular,
        $nombre_para_mostrar,
        $plural,
        $femenino,
        $icono,
        $meta,
        $para_armar_columnas,
    ) {
        $this->set_prefijo($prefijo);
        $this->set_id_post_type($id_post_type);
        $this->set_singular($singular);
        $this->set_nombre_para_mostrar($nombre_para_mostrar);
        $this->set_plural($plural);
        $this->set_femenino($femenino);
        $this->set_icono($icono);
        $this->set_meta($meta);
        $this->set_para_armar_columnas($para_armar_columnas);
    }
    public function set_prefijo($valor)
    {
        $this->prefijo = $valor;
    }
    public function get_prefijo()
    {
        return $this->prefijo;
    }
    public function set_singular($valor)
    {
        $this->singular = strtolower($valor);
    }
    public function get_singular()
    {
        return $this->singular;
    }
    public function set_plural($valor)
    {
        $this->plural = strtolower($valor);
    }
    public function get_plural()
    {
        return $this->plural;
    }
    public function get_singular_mayuscula()
    {
        return str_replace('_', ' ', ucfirst($this->get_singular()));
    }
    public function get_plural_mayuscula()
    {
        return str_replace('_', ' ', ucfirst($this->get_plural()));
    }
    public function set_femenino($femenino)
    {
        $this->femenino = $femenino;
    }
    public function get_femenino()
    {
        return $this->femenino;
    }
    public function set_icono($valor)
    {
        $this->icono = $valor;
    }
    public function get_icono()
    {
        return $this->icono;
    }
    public function set_meta($valor)
    {
        $this->meta = $valor;
    }
    public function get_meta()
    {
        return $this->meta;
    }
    public function get_posicion()
    {
        $this->incrementador += 1;
        return 1000 + $this->incrementador;
    }
    public function set_nombre_para_mostrar($valor)
    {
        $this->nombre_para_mostrar = $valor;
    }
    public function get_nombre_para_mostrar()
    {
        return $this->nombre_para_mostrar;
    }
    public function set_para_armar_columnas($valor)
    {
        $this->para_armar_columnas = $valor;
    }
    public function get_para_armar_columnas()
    {
        return $this->para_armar_columnas;
    }
    public function get_caracteristicas()
    {
        return array(
            'public' => true,
            'show_ui' => true,
            'labels' => array(
                'name' => __($this->get_nombre_para_mostrar()),
                'singular_name' => __(str_replace("_", ' ', $this->get_singular_mayuscula())),
                'add_new' => __('Agregar nuev' . ($this->get_femenino() ? 'a' : 'o')),
                'add_new_item' => __('Agregar nuev' . ($this->get_femenino() ? 'a' : 'o') . ' ' . $this->get_singular()),
                'edit' => __('Editar'),
                'edit_item' => __('Editar ' . $this->get_singular()),
                'new_item' => __('Nuev' . ($this->get_femenino() ? 'a' : 'o') . ' ' . $this->get_singular()),
                'view' => __('Ver ' . $this->get_singular()),
                'view_item' => __('Ver ' . $this->get_singular()),
                'search_items' => __('Buscar'),
                'not_found' => __('No se encontraron ' . $this->get_plural()),
                'not_found_in_trash' => __('No se encontraron ' . $this->get_plural() . ' en la basura'),
                'parent' => __($this->get_id_post_type()),
            ),
            'menu_icon' => $this->get_icono(),
            'show_in_rest' => true,
            'rest_base' => $this->get_id_post_type(),
            'has_archive' => true,
            'show_in_menu' => true,
            'supports' => false,
            'exclude_from_search' => false,
            'capability_type' => $this->get_id_post_type(),
            'map_meta_cap' => true,
            'menu_position' => $this->get_posicion(),
            'capabilities' => $this->get_habilidades(),
        );
    }

    public function registrar_post_type()
    {
        register_post_type($this->get_id_post_type(), $this->get_caracteristicas());
    }

    public function deregistrar_post_type()
    {
        unregister_post_type($this->get_id_post_type());
    }

    public function obtener_todos_los_post()
    {
        return get_posts(array(
            'post_type' => $this->get_id_post_type(),
            'numberposts' => -1,
            'post_status' => 'any',
        ));
    }

    public function borrar_todos_los_post()
    {
        foreach ($this->obtener_todos_los_post() as $objeto) {
            wp_delete_post($objeto->ID, true);
        }
    }
}