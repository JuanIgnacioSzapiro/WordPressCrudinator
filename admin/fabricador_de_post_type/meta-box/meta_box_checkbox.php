<?php // meta_box_tipo_texto.php
require_once dirname(__FILE__) . '/generador_meta_box.php';

class CampoCheckbox extends TipoMetaBox
{
    public function __construct(
        $nombre_meta,
        $etiqueta,
        $descripcion,
        $opciones = [],
        $es_campo_opcional = false,
    ) {
        $this->set_nombre_meta($nombre_meta);
        $this->set_etiqueta($etiqueta);
        $this->set_descripcion($descripcion);
        $this->set_opciones($opciones);
        $this->set_es_campo_opcional($es_campo_opcional);
    }
    public function generar_fragmento_html($post, $llave)
    {
        $meta_key = $llave . '_' . $this->get_nombre_meta();
        if (!empty($this->get_opciones())) {
            // Obtener valores guardados como array
            $saved_values = get_post_meta($post->ID, $meta_key, true);
            $saved_values = is_array($saved_values) ? $saved_values : [];

            // Mostrar etiqueta y descripción
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
            // Generar checkboxes para cada opción
            foreach ($this->get_opciones() as $key => $opcion) {
                $input_id = $meta_key . '_' . $key;
                $checked = in_array($opcion, $saved_values) ? 'checked="checked"' : '';
                ?>
                <input type="checkbox" id="<?php echo esc_attr($input_id); ?>" name="<?php echo esc_attr($meta_key); ?>[]"
                    value="<?php echo esc_attr($opcion); ?>" <?php echo $checked; ?> />
                <label for="<?php echo esc_attr($input_id); ?>">
                    <?php echo esc_html($opcion); ?>
                </label>
                <br>
                <?php
            }
        } else {
            $custom_field_value = get_post_meta($post->ID, $meta_key, true);
            ?>
            <input type="checkbox" id="<?php echo esc_attr($meta_key); ?>" name="<?php echo esc_attr($meta_key); ?>" value="1" <?php checked($custom_field_value, 'true'); ?> />
            <?php
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
        }
    }
    public function generar_fragmento_html_formulario($llave){
        $meta_key = $llave . '_' . $this->get_nombre_meta();
        if (!empty($this->get_opciones())) {
            // Mostrar etiqueta y descripción
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
            // Generar checkboxes para cada opción
            foreach ($this->get_opciones() as $key => $opcion) {
                $input_id = $meta_key . '_' . $key;
                ?>
                <input type="checkbox" id="<?php echo esc_attr($input_id); ?>" name="<?php echo esc_attr($meta_key); ?>[]"
                    value="<?php echo esc_attr($opcion); ?>"/>
                <label for="<?php echo esc_attr($input_id); ?>">
                    <?php echo esc_html($opcion); ?>
                </label>
                <br>
                <?php
            }
        } else {
            ?>
            <input type="checkbox" id="<?php echo esc_attr($meta_key); ?>" name="<?php echo esc_attr($meta_key); ?>" value="1" />
            <?php
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
        }
    }
}