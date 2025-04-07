<?php //meta_box_tipo_archivo.php
class CampoFecha extends TipoMetaBox
{
    public function __construct(
        $nombre_meta,
        $etiqueta,
        $descripcion,
        $es_campo_opcional = false,
    ) {
        $this->set_nombre_meta($nombre_meta);
        $this->set_etiqueta($etiqueta);
        $this->set_descripcion($descripcion);
        $this->set_es_campo_opcional($es_campo_opcional);
    }

    public function generar_fragmento_html($post, $llave)
    {
        $meta_key = $llave . '_' . $this->nombre_meta;
        $current_value = get_post_meta($post->ID, $meta_key, true);


        // Convertir valor guardado (dd/mm/yyyy) a formato input (yyyy-mm-dd)
        $input_value = '';
        if (!empty($current_value)) {
            $fecha = DateTime::createFromFormat('d/m/Y', $current_value);
            if ($fecha) {
                $input_value = $fecha->format('Y-m-d');
            }
        }

        ?>
        <div class="file-upload-wrapper">
            <?php if ($this->es_campo_opcional): ?>
                <label for="<?php echo esc_attr($meta_key); ?>">
                    <?php echo esc_html($this->etiqueta); ?>
                </label>
            <?php else: ?>
                <label class="no-opcional" for="<?php echo esc_attr($meta_key); ?>">
                    <?php echo esc_html($this->etiqueta); ?> *
                </label>
                <div class="no-opcional-comentario">Este campo es OBLIGATORIO</div>
            <?php endif; ?>
            <p class="description"><?php echo esc_html($this->descripcion); ?></p>
            <input type="date" id="<?php echo esc_attr($meta_key); ?>" name="<?php echo esc_attr($meta_key); ?>"
                value="<?php echo esc_attr($input_value); ?>" />
        </div>
        <?php
    }
    public function generar_fragmento_html_formulario($llave){
        $meta_key = $llave . '_' . $this->nombre_meta;
        ?>
        <div class="file-upload-wrapper">
            <?php if ($this->es_campo_opcional): ?>
                <label for="<?php echo esc_attr($meta_key); ?>">
                    <?php echo esc_html($this->etiqueta); ?>
                </label>
            <?php else: ?>
                <label class="no-opcional" for="<?php echo esc_attr($meta_key); ?>">
                    <?php echo esc_html($this->etiqueta); ?> *
                </label>
                <div class="no-opcional-comentario">Este campo es OBLIGATORIO</div>
            <?php endif; ?>
            <p class="description"><?php echo esc_html($this->descripcion); ?></p>
            <input type="date" id="<?php echo esc_attr($meta_key); ?>" name="<?php echo esc_attr($meta_key); ?>" />
        </div>
        <?php
    }
}