<?php
require_once dirname(__FILE__) . '/caracteristicas_minimas_post_type.php';
require_once dirname(__FILE__) . '/columnas/columnas.php';
/**
 * Creador de tipos de post con todos sus componentes
 */
class PostTypePersonalizado extends CaracteristicasMinimasPostType
{
    private $singular;
    private $plural;
    private $femenino;
    private $icono;
    private $cajas_de_metadata;
    private $incrementador = 0;
    private $columnas_de_wordpress;
    /**
     * Constructor de PostTypePersonalizado
     * @param string $prefijo Se va a utilizar para tener mejor trazabilidad de tablas y valores SQL por lo que no puede repetirse entre sectores
     * @param string $id_post_type DEBE SER ÚNICO Y NO CONTENER MÁS DE 20 CARACTERES
     * @param string $singular Valor singular para mostrar en el front end
     * @param string $plural Valor en plural para mostrar en el front end
     * @param boolean $femenino Valor booleano (true == femenino / false == masculino) que permite al sistema cambiar el género de los artículos
     * @param string $icono  WordPress dash-icon importado o nativo 
     * @param CajasDeMetadata $cajas_de_metadata cajas_de_metadata (campos y tipos de campos)
     * @param array $para_armar_columnas Array de los id de los campos que deberían mostrarse.
     * Si el id pertenece a un campo que es una lista se muestra el primero.
     * Si está vacío se muestra el título del post.
     */
    public function __construct(
        $id_post_type,
        $singular,
        $plural,
        $femenino,
        $icono,
        $cajas_de_metadata,
        $para_armar_columnas,
    ) {
        $this->set_id_post_type(strtolower($id_post_type));
        $this->set_singular($singular);
        $this->set_plural($plural);
        $this->set_femenino($femenino);
        $this->set_icono($icono);
        $this->set_cajas_de_metadata($cajas_de_metadata);
        $this->set_para_armar_columnas($para_armar_columnas);

        $this->inicializar_cajas_de_metadata();
    }
    // Getters y Setters
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
        return ucfirst($this->get_singular());
    }
    public function get_plural_mayuscula()
    {
        return ucfirst($this->get_plural());
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
    public function set_cajas_de_metadata($valor)
    {
        $this->cajas_de_metadata = $valor;
    }
    public function get_cajas_de_metadata()
    {
        return $this->cajas_de_metadata;
    }
    /**
     * Retorna un valor entero que determina la posición anterior existente y lo retorna incrementado en uno para asignarle al post actual
     * @return int
     */
    public function get_posicion()
    {
        $this->incrementador += 1;
        return 1000 + $this->incrementador;
    }
    public function get_columnas_de_wordpress()
    {
        return $this->columnas_de_wordpress;
    }
    public function set_columnas_de_wordpress()
    {
        $this->columnas_de_wordpress = new ColumnasDeWordpress($this->get_para_armar_columnas(), $this->get_id_post_type());
    }
    public function get_caracteristicas()
    {
        return array(
            'public' => true, // Debe ser true para que sea visible
            'show_ui' => true, // Muestra la interfaz en el admin
            'labels' => array(
                'name' => __($this->get_plural_mayuscula()),
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
            'has_archive' => true, // Habilita el archivo (ej: /cursos/)
            'show_in_menu' => true, // Aparece en el menú admin
            'supports' => false, // Si no hay soporte, no mostrará campos
            'exclude_from_search' => false,
            'capability_type' => $this->get_id_post_type(),
            'map_meta_cap' => true,
            'menu_position' => $this->get_posicion(),
            'capabilities' => $this->get_habilidades(),
            'show_in_nav_menus' => true // Para aparecer en menús
        );
    }

    /**
     * Registra el tipo de post actual
     * @return void
     */
    public function registrar_post_type()
    {
        register_post_type($this->get_id_post_type(), $this->get_caracteristicas());

        $this->set_columnas_de_wordpress();
    }

    /**
     * Devuelve un array con todos los post del tipo actual
     * @return array<int|WP_Post>
     */
    public function obtener_todos_los_post()
    {
        return get_posts(array(
            'post_type' => $this->get_id_post_type(),
            'numberposts' => -1,
            'post_status' => 'any',
        ));
    }

    /**
     * Dregistra tipo de post actual, pero no borra la base de datos
     * @return void
     */
    public function deregistrar_post_type()
    {
        unregister_post_type($this->get_id_post_type());
    }

    /**
     * Borra la base de datos del tipo de post actual
     * @return void
     */
    public function borrar_todos_los_post()
    {
        foreach ($this->obtener_todos_los_post() as $objeto) {
            wp_delete_post($objeto->ID, true);
        }
    }
    /**
     * Inicializa en orden todos los datos de las cajas de metadata necesarios y genera las mismas al final
     */
    public function inicializar_cajas_de_metadata()
    {
        $this->seder_id_post_type_perteneciente();
        $this->seder_nombre_de_editor();
        $this->generar_cajas_de_metadata();
    }
    /**
     *  Permite a la vincular la página de edición al post type
     */
    public function seder_id_post_type_perteneciente()
    {
        $this->cajas_de_metadata->set_id_post_type_perteneciente($this->get_id_post_type());
    }
    /**
     * Permite a la página de edición incorporar el singular en el título
     */
    protected function seder_nombre_de_editor()
    {
        $this->cajas_de_metadata->set_titulo_del_editor($this->get_singular());
    }
    /**
     * Una vez inicializados todos los datos, las cajas de metadata pueden ser creadas
     */
    public function generar_cajas_de_metadata()
    {
        $this->cajas_de_metadata->crear_cajas_de_metadata();
    }
    /**
     * Devuelve el id_caja_metadata de los campos que sean clonables de un tipo de post
     * @param int $clonables el valor debe ser 0 o 1 según sea verdadero o falso
     * @return array => [string => string]
     */
    public function get_ids_caja_metadata_campos($clonables)
    {
        $completo = [];
        if ($clonables == 0) {
            $para_fraccionar = array_filter(
                $this->get_cajas_de_metadata()->get_contenido(),
                function ($objeto_completo) {
                    return $objeto_completo->get_clonable() == 0;
                }
            );
        } else {
            $para_fraccionar = array_filter(
                $this->get_cajas_de_metadata()->get_contenido(),
                function ($objeto_completo) {
                    return $objeto_completo->get_clonable() == 1;
                }
            );
        }
        foreach ($para_fraccionar as $individual) {
            $completo = array_merge($completo, [$individual->get_id_caja_metadata() => get_class($individual)]);
        }
        return $completo;
    }
}