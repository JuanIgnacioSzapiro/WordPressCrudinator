<?php
require_once dirname(__FILE__) . '/generador_meta_box.php';
require_once dirname(__FILE__) . '/../../funciones.php';


class CampoCheckboxQuery extends TipoMetaBox
{
    public function __construct(
        $nombre_meta,
        $etiqueta,
        $descripcion,
        $query,
        $es_campo_opcional = false
    ) {
        $this->set_nombre_meta($nombre_meta);
        $this->set_etiqueta($etiqueta);
        $this->set_descripcion($descripcion);
        $this->set_query($query);
        $this->set_es_campo_opcional($es_campo_opcional);
    }

    public function generar_fragmento_html($post, $llave)
    {
        $meta_key = $llave . '_' . $this->get_nombre_meta();
        
        $datos = obtener_resultado_query(str_replace('%s', $post->ID, $this->get_query()));
        
        if (!empty($datos)) {
            $saved_values = get_post_meta($post->ID, $meta_key, true);
            $saved_values = is_array($saved_values) ? $saved_values : [];

            if ($this->get_es_campo_opcional()) {
                ?>
                <label for="<?php echo esc_attr($meta_key); ?>">
                    <?php echo esc_html($this->get_etiqueta()); ?>
                </label>
                <?php
            } else {
                ?>
                <label class="no-opcional" for="<?php echo esc_attr($meta_key); ?>">
                    <?php echo esc_html($this->get_etiqueta()); ?> *
                </label>
                <div class="no-opcional-comentario">Este campo es OBLIGATORIO</div>
                <?php
            }
            ?>
            <p class="description">
                <?php echo esc_html($this->get_descripcion()); ?>
            </p>
            <?php
            foreach ($datos as $id => $titulo) {
                $input_id = $meta_key . '_' . $id;
                $checked = in_array($titulo->post_title, $saved_values) ? 'checked="checked"' : '';
                ?>
                <input type="checkbox" id="<?php echo esc_attr($input_id); ?>" 
                       name="<?php echo esc_attr($meta_key); ?>[]"
                       value="<?php echo esc_html($titulo->post_title); ?>" <?php echo $checked; ?> />
                <label for="<?php echo esc_attr($input_id); ?>">
                    <?php echo esc_html($titulo->post_title); ?>
                </label>
                <br>
                <?php
            }
        }
    }
}