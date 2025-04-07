<?php
class CampoDropDownPredeterminado extends TipoMetaBox
{
    public function __construct(
        $nombre_meta,
        $etiqueta,
        $opciones,
        $descripcion,
        $clonable = false,
        $es_campo_opcional = false,
    ) {
        $this->set_nombre_meta($nombre_meta);
        $this->set_etiqueta($etiqueta);
        $this->set_opciones($opciones);
        $this->set_descripcion($descripcion);
        $this->set_clonable($clonable);
        $this->set_es_campo_opcional($es_campo_opcional);
    }

    public function generar_fragmento_html($post, $llave_meta)
    {
        $meta_key = $llave_meta . '_' . $this->get_nombre_meta();
        $selected_value = get_post_meta($post->ID, $meta_key, true);

        $argumentos = $this->get_opciones();
        ?>
        <div>
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
            <select name="<?php echo esc_attr($meta_key); ?>" id="<?php echo esc_attr($meta_key); ?>">
                <option value="">Seleccionar...</option>
                <?php foreach ($argumentos as $opcion): ?>
                    <option value="<?php echo esc_attr($opcion); ?>" <?php selected($selected_value, $opcion); ?>>
                        <?php echo esc_html($opcion); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
    public function generar_fragmento_html_formulario($llave_meta)
    {
        $meta_key = $llave_meta . '_' . $this->get_nombre_meta();
        $argumentos = $this->get_opciones();
        ?>
        <div>
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
            <select name="<?php echo esc_attr($meta_key); ?>" id="<?php echo esc_attr($meta_key); ?>">
                <option value="">Seleccionar...</option>
                <?php foreach ($argumentos as $opcion): ?>
                    <option value="<?php echo esc_attr($opcion); ?>">
                        <?php echo esc_html($opcion); ?>
                    </option>
                <?php endforeach; ?>
            </select>

        </div>
        <?php
    }
}