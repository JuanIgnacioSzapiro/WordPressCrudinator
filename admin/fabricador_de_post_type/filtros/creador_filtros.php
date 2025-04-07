<?php //creador_filtros.php
require_once dirname(__FILE__) . '/filtro.php';

class CreadorFiltros
{
    protected $post_type_padre;
    private $argumentos;

    public function __construct($post_type_padre, $argumentos)
    {
        $this->set_post_type_padre($post_type_padre);
        $this->set_argumentos($argumentos);

        add_action('restrict_manage_posts', array($this, 'borrar_filtros'));

        add_action('restrict_manage_posts', array($this, 'agregar_filtro'));

        add_action('pre_get_posts', array($this, 'manejar_filtro'));

        // Eliminar buscador nativo del listado
        add_action('admin_head-edit.php', function () {
            global $typenow;

            if ($this->get_post_type_padre() === $typenow) {
                ?>
                <style>
                    .search-box {
                        display: none !important;
                    }
                </style>
                <?php
            }
        });
    }

    public function get_post_type_padre()
    {
        return $this->post_type_padre;
    }
    public function get_argumentos()
    {
        return $this->argumentos;
    }
    public function set_post_type_padre($valor)
    {
        $this->post_type_padre = $valor;
    }
    public function set_argumentos($valor)
    {
        $this->argumentos = $valor;
    }

    public function borrar_filtros($post_type)
    {
        if ($this->get_post_type_padre() !== $post_type) {
            return;
        }

        ?>
        <div class="alignright">
            <button class="button action" onclick="borrar_filtros()">Borrar filtros</button>
        </div>
        <script>
            function borrar_filtros() {
                document.getElementById("filter-by-date").value = "0";
                <?php
                foreach ($this->get_argumentos() as $individual) {
                    ?>
                    document.getElementById("<?php echo $individual->get_id_filtro(); ?>").value = "";
                    <?php
                }
                ?>
            }
        </script>
        <?php
    }

    public function agregar_filtro($post_type)
    {
        if ($this->get_post_type_padre() !== $post_type) {
            return;
        }
        foreach ($this->get_argumentos() as $individual) {
            $current_value = isset($_GET[$individual->get_id_filtro()]) ? sanitize_text_field($_GET[$individual->get_id_filtro()]) : '';
            ?>
            <div class="alignright">
                <label for="<?php echo $individual->get_id_filtro(); ?>">
                    <?php echo esc_html($individual->get_texto()); ?>
                </label>
                <input type="text" id="<?php echo $individual->get_id_filtro(); ?>"
                    name="<?php echo $individual->get_id_filtro(); ?>" value="<?php echo esc_attr($current_value); ?>">
            </div>
            <?php
        }
    }

    public function manejar_filtro($query)
    {
        global $pagenow, $wpdb;

        if (!is_admin() || $pagenow !== 'edit.php' || !$query->is_main_query() || $this->get_post_type_padre() !== $query->get('post_type')) {
            return;
        }

        foreach ($this->get_argumentos() as $individual) {
            if (!empty($_GET[$individual->get_id_filtro()])) {
                $search_term = sanitize_text_field($_GET[$individual->get_id_filtro()]);
                $sql_query = $individual->get_query();

                $num_placeholders = substr_count($sql_query, '%s');
                $like_term = '%' . $wpdb->esc_like($search_term) . '%';
                $args = array_fill(0, $num_placeholders, $like_term);

                // Preparar consulta de forma segura
                $sql = $wpdb->prepare($sql_query, ...$args);

                $ids = $wpdb->get_col($sql);

                if (!empty($ids)) {
                    $query->set($individual->get_ids(), $ids);
                } else {
                    $query->set('post__in', array(0));
                }
            }
        }
    }
}
