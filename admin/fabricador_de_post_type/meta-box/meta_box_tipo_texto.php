<?php // meta_box_tipo_texto.php
require_once dirname(__FILE__) . '/generador_meta_box.php';

class CampoTexto extends TipoMetaBox
{
    public function __construct(
        $nombre_meta,
        $etiqueta,
        $texto_de_ejemplificacion,
        $descripcion,
        $clonable = false,
        $tipo_de_input = 'string',
        $es_campo_opcional = false,
    ) {
        $this->set_nombre_meta($nombre_meta);
        $this->set_etiqueta($etiqueta);
        $this->set_texto_de_ejemplificacion($texto_de_ejemplificacion);
        $this->set_descripcion($descripcion);
        $this->set_clonable($clonable);
        $this->set_tipo_de_input($tipo_de_input);
        $this->set_es_campo_opcional($es_campo_opcional);
    }
    public function generar_fragmento_html($post, $llave)
    {
        if (!$this->get_clonable()) {
            $meta_key = $llave . '_' . $this->get_nombre_meta();
            $custom_field_value = get_post_meta($post->ID, $meta_key, true);
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
            <p class="description"><?php echo esc_html($this->get_descripcion()); ?></p>
            <input type="text" id="<?php echo esc_attr($meta_key); ?>" name="<?php echo esc_attr($meta_key); ?>"
                value="<?php echo esc_attr($custom_field_value); ?>"
                placeholder="<?php echo esc_attr($this->get_texto_de_ejemplificacion()); ?>" style="width: 100%;" />
            <?php
        } else {
            $meta_key = $llave . '_' . $this->get_nombre_meta();
            $values = get_post_meta($post->ID, $meta_key);
            ?>
            <div class="clonable-container-texto">
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
                <p class="description"><?php echo esc_html($this->get_descripcion()); ?></p>
                <div class="clonable-fields-texto">
                    <?php
                    // Always render at least ONE field (even if empty)
                    if (empty($values)) {
                        $values = [''];
                    }
                    foreach ($values as $value) { ?>
                        <div class="clonable-field-texto">
                            <input type="text" name="<?php echo esc_attr($meta_key); ?>[]" value="<?php echo esc_attr($value); ?>"
                                placeholder="<?php echo esc_attr($this->get_texto_de_ejemplificacion()); ?>"
                                style="width: 100%; margin-bottom: 5px;" />
                            <button type="button" class="button remove-field-texto">Eliminar</button>
                        </div>
                    <?php } ?>
                </div>
                <button type="button" class="button add-field-texto">Agregar más</button>
            </div>
            <script>
                (function ($) {
                    // Only define once in global scope
                    if (typeof window.initClonableFields_texto !== 'function') {
                        window.initClonableFields_texto = function () {
                            $(document)
                                .off('click', '.clonable-container-texto .add-field-texto') // Prevent duplicate bindings
                                .on('click', '.clonable-container-texto .add-field-texto', function (e) {
                                    e.preventDefault();
                                    const container = $(this).closest('.clonable-container-texto');
                                    const newField = container.find('.clonable-field-texto:last').clone();
                                    newField.find('input').val('');
                                    container.find('.clonable-fields-texto').append(newField);
                                })
                                .off('click', '.clonable-container-texto .remove-field-texto')
                                .on('click', '.clonable-container-texto .remove-field-texto', function (e) {
                                    e.preventDefault();
                                    const container = $(this).closest('.clonable-container-texto');
                                    if (container.find('.clonable-field-texto').length > 1) {
                                        $(this).closest('.clonable-field-texto').remove();
                                    }
                                });
                        };
                    }

                    // Initialize when DOM is ready
                    $(document).ready(function () {
                        if (!window.clonableFieldsInitialized_texto) {
                            window.initClonableFields_texto();
                            window.clonableFieldsInitialized_texto = true;
                        }
                    });
                })(jQuery);
            </script>
            <?php
        }
    }

    public function generar_fragmento_html_formulario($llave)
    {
        if (!$this->get_clonable()) {
            $meta_key = $llave . '_' . $this->get_nombre_meta();
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
            <p class="description"><?php echo esc_html($this->get_descripcion()); ?></p>
            <input type="text" id="<?php echo esc_attr($meta_key); ?>" name="<?php echo esc_attr($meta_key); ?>"
                placeholder="<?php echo esc_attr($this->get_texto_de_ejemplificacion()); ?>" style="width: 100%;" />
            <?php
        } else {
            $meta_key = $llave . '_' . $this->get_nombre_meta();
            ?>
            <div class="clonable-container-texto">
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
                <p class="description"><?php echo esc_html($this->get_descripcion()); ?></p>
                <div class="clonable-fields-texto">
                    <?php
                    // Always render at least ONE field (even if empty)
                    if (empty($values)) {
                        $values = [''];
                    }
                    foreach ($values as $value) { ?>
                        <div class="clonable-field-texto">
                            <input type="text" name="<?php echo esc_attr($meta_key); ?>[]" value="<?php echo esc_attr($value); ?>"
                                placeholder="<?php echo esc_attr($this->get_texto_de_ejemplificacion()); ?>"
                                style="width: 100%; margin-bottom: 5px;" />
                            <button type="button" class="button remove-field-texto">Eliminar</button>
                        </div>
                    <?php } ?>
                </div>
                <button type="button" class="button add-field-texto">Agregar más</button>
            </div>
            <script>
                (function ($) {
                    // Only define once in global scope
                    if (typeof window.initClonableFields_texto !== 'function') {
                        window.initClonableFields_texto = function () {
                            $(document)
                                .off('click', '.clonable-container-texto .add-field-texto') // Prevent duplicate bindings
                                .on('click', '.clonable-container-texto .add-field-texto', function (e) {
                                    e.preventDefault();
                                    const container = $(this).closest('.clonable-container-texto');
                                    const newField = container.find('.clonable-field-texto:last').clone();
                                    newField.find('input').val('');
                                    container.find('.clonable-fields-texto').append(newField);
                                })
                                .off('click', '.clonable-container-texto .remove-field-texto')
                                .on('click', '.clonable-container-texto .remove-field-texto', function (e) {
                                    e.preventDefault();
                                    const container = $(this).closest('.clonable-container-texto');
                                    if (container.find('.clonable-field-texto').length > 1) {
                                        $(this).closest('.clonable-field-texto').remove();
                                    }
                                });
                        };
                    }

                    // Initialize when DOM is ready
                    $(document).ready(function () {
                        if (!window.clonableFieldsInitialized_texto) {
                            window.initClonableFields_texto();
                            window.clonableFieldsInitialized_texto = true;
                        }
                    });
                })(jQuery);
            </script>
            <?php
        }
    }
}