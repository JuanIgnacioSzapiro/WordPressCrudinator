<?php

use function PHPSTORM_META\map;

class CajasDeMetadata
{
    protected $id_post_type_perteneciente;  // Se obtiene a través de la función seder_id_post_type_perteneciente() perteneciente a la clase PostTypePersonalizado
    protected $contenido;
    protected $titulo_del_editor; // Se obtiene a través de la función seder_nombre_de_editor() perteneciente a la clase PostTypePersonalizado
    /**
     * Constructor de CajasDeMetadata
     * @param string $id_post_type_perteneciente // ID del tipo de post al que pertenece, es lo que permite relacionar las vistas
     * @param array $contenido // Array que contiene los diferentes tipos de cajas de metadata
     */
    public function __construct($contenido)
    {
        $this->contenido = $contenido;
    }
    public function get_id_post_type_perteneciente()
    {
        return $this->id_post_type_perteneciente;
    }
    public function set_id_post_type_perteneciente($valor)
    {
        if (empty($valor)) {
            manifestar_errores_por_consola('cajas_de_metadata.php->set_id_post_type_perteneciente()->$this->id_post_type_perteneciente', $this->id_post_type_perteneciente);
        }
        $this->id_post_type_perteneciente = $valor;
    }
    public function get_contenido()
    {
        return $this->contenido;
    }
    public function set_contenido($valor)
    {
        $this->contenido = $valor;
    }
    public function get_titulo_del_editor()
    {
        if (empty($this->titulo_del_editor)) {
            return "Editor de contenido";
        }

        return "Editor de " . $this->titulo_del_editor;
    }
    public function set_titulo_del_editor($valor)
    {
        $this->titulo_del_editor = $valor;
    }
    public function get_id_creador_cajas_de_metadata()
    {
        return "cajasDeMetadataDe" . '_' . $this->get_id_post_type_perteneciente();
    }
    /**
     * Se agregan cajas de metadata y se reemplaza la función de guardar
     */
    public function crear_cajas_de_metadata()
    {
        add_action('add_meta_boxes', array($this, 'crear_editor_cajas_de_metadata'));
        add_action('save_post', array($this, 'guardar_contenido_de_cajas_de_metadata'));
    }
    /**
     * Se muestran las cajas de metadata previamente agregadas
     */
    public function crear_editor_cajas_de_metadata()
    {
        add_meta_box($this->get_id_creador_cajas_de_metadata(), $this->get_titulo_del_editor(), array($this, 'mostrar_cajas_de_metadata'), $this->get_id_post_type_perteneciente());
    }
    /**
     * Se utiliza para mostrar las cajas de metadata tanto en wordpress como en front end independiente
     * @param mixed $post
     * @return void
     */
    public function mostrar_cajas_de_metadata($post)
    {
        $llave_meta = esc_attr($this->get_id_creador_cajas_de_metadata());
        wp_nonce_field($llave_meta, $llave_meta);
        ?>
        <style>
            .no-opcional-comentario {
                display: none;
                position: absolute;
                /* Posición absoluta respecto al padre */
                left: 0;
                top: 100%;
                /* Se coloca justo debajo del elemento .no-opcional */
                z-index: 1000;
                width: max-content;
                color: crimson;
            }

            .no-opcional:hover+.no-opcional-comentario {
                position: relative;
                display: contents;
            }

            .en-meta-box {
                margin-top: 1.5%;
            }
        </style>
        <div class="meta-box">
            <?php
            if (!empty($this->get_contenido())) {
                $repetido = $this->checkear_id_cajas_metadate_repetidas();
                if ($repetido == "") {
                    foreach ($this->get_contenido() as $caja_metadata_individual) {
                        ?>
                        <div class="en-meta-box">
                            <?php
                            $this->inicializar_caja($post, $caja_metadata_individual)
                                ?>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <p>El id "<b><?php echo $repetido ?></b>" está repetido</p>
                    <?php
                }
            } else {
                ?>
                <p>Las cajas de metadata <b>no tienen contenido</b></p>
                <?php
            }
            ?>
        </div>
        <?php
    }
    protected function checkear_id_cajas_metadate_repetidas()
    {
        $repetido = "";
        $ids_de_contenido = array_map(function ($caja_metadata_individual) {
            return $caja_metadata_individual->get_id_caja_metadata();
        }, $this->get_contenido());
        foreach (($ids_de_contenido) as $caja_metadata_individual) {
            if (array_count_values($ids_de_contenido)[$caja_metadata_individual] > 1) {
                $repetido = $caja_metadata_individual;
            }
        }
        return $repetido;
    }
    public function inicializar_caja($post, $caja_metadata_individual)
    {
        $caja_metadata_individual->set_metakey(
            $this->get_id_creador_cajas_de_metadata()
        );
        $caja_metadata_individual->generar_fragmento_html($post);
    }
    public function guardar_contenido_de_cajas_de_metadata()
    {
    }
    public function mostrar_errores()
    {
        global $post;

        if (!$post || $post->post_type !== $this->get_id_creador_cajas_de_metadata()) {
            return;
        }

        $transient_key = 'inpsc_meta_errors_' . $post->ID;
        $errors = get_transient($transient_key);

        if ($errors) {
            delete_transient($transient_key);
            ?>
            <div class="notice notice-error is-dismissible">
                <p><strong><?php _e('Error:', 'text-domain'); ?></strong></p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo esc_html($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php
        }
    }
}