<?php
require_once dirname(__FILE__) . '/generador_meta_box.php';

class CampoTextoAsociado extends TipoMetaBox
{
    public function __construct(
        $nombre_meta_1,
        $etiqueta_1,
        $texto_de_ejemplificacion_1,
        $descripcion_1,
        $nombre_meta_2,
        $etiqueta_2,
        $texto_de_ejemplificacion_2,
        $descripcion_2,
        $tipo_de_input_1 = 'string',
        $tipo_de_input_2 = 'string',
        $clonable = false,
        $es_campo_opcional = false,
    ) {
        $this->set_nombre_meta($nombre_meta_1);
        $this->set_etiqueta($etiqueta_1);
        $this->set_texto_de_ejemplificacion($texto_de_ejemplificacion_1);
        $this->set_descripcion($descripcion_1);
        $this->set_tipo_de_input($tipo_de_input_1);
        $this->set_nombre_meta_asociado2($nombre_meta_2);
        $this->set_etiqueta_asociado2($etiqueta_2);
        $this->set_texto_de_ejemplificacion_asociado2($texto_de_ejemplificacion_2);
        $this->set_descripcion_asociado2($descripcion_2);
        $this->set_tipo_de_input_asociado2($tipo_de_input_2);
        $this->set_clonable($clonable);
        $this->set_es_campo_opcional($es_campo_opcional);
    }

    public function generar_fragmento_html($post, $llave)
    {
        if (!$this->get_clonable()) {
            // Campos no clonables
            $meta_llave1 = $llave . '_' . $this->get_nombre_meta();
            $meta_llave2 = $llave . '_' . $this->get_nombre_meta_asociado2();
            $value1 = get_post_meta($post->ID, $meta_llave1, true);
            $value2 = get_post_meta($post->ID, $meta_llave2, true);
            ?>
            <div style="margin-bottom: 20px;">
                <label for="<?php echo esc_attr($meta_llave1); ?>">
                    <?php echo esc_html($this->get_etiqueta()); ?>
                </label>
                <input type="text" id="<?php echo esc_attr($meta_llave1); ?>" name="<?php echo esc_attr($meta_llave1); ?>"
                    value="<?php echo esc_attr($value1); ?>"
                    placeholder="<?php echo esc_attr($this->get_texto_de_ejemplificacion()); ?>" style="width: 100%;" />
                <p class="description">
                    <?php echo esc_html($this->get_descripcion()); ?>
                </p>

                <label for="<?php echo esc_attr($meta_llave2); ?>">
                    <?php echo esc_html($this->get_etiqueta_asociado2()); ?>
                </label>
                <input type="text" id="<?php echo esc_attr($meta_llave2); ?>" name="<?php echo esc_attr($meta_llave2); ?>"
                    value="<?php echo esc_attr($value2); ?>"
                    placeholder="<?php echo esc_attr($this->get_texto_de_ejemplificacion_asociado2()); ?>" style="width: 100%;" />
                <p class="description">
                    <?php echo esc_html($this->get_descripcion_asociado2()); ?>
                </p>
            </div>
            <?php
        } else {
            $group_meta_key = $llave . '_' . $this->get_nombre_meta() . '_' . $this->get_nombre_meta_asociado2();
            $group_values = get_post_meta($post->ID, $group_meta_key, false);
            $posible_json = array();

            if (!is_array($group_values)) {
                $group_values = array();
            }

            if (empty($group_values)) {
                // Inicializar con un par vacío
                $posible_json = array(
                    array(array())
                );
            } else {
                // Decodificar cada valor como array asociativo
                foreach ($group_values as $item) {
                    $decoded = json_decode($item, true);
                    if (is_array($decoded)) {
                        $posible_json[] = $decoded;
                    }
                }
            }

            ?>
            <div class="clonable-container-texto-asociado">
                <div class="clonable-fields-texto-asociado">
                    <?php foreach ($posible_json[0] as $i => $pair): ?>
                        <div class="clonable-field-texto-asociado"
                            style="margin-bottom:15px; border-bottom:1px solid #ddd; padding-bottom:15px;">
                            <?php
                            if ($this->get_es_campo_opcional()) {
                                ?>
                                <label for="<?php echo esc_attr($group_meta_key); ?>">
                                    <?php echo esc_html($this->get_etiqueta()); ?>
                                </label>
                                <?php
                            } else {
                                ?>
                                <label class="no-opcional" for="<?php echo esc_attr($group_meta_key); ?>">
                                    <?php echo esc_html($this->get_etiqueta()); ?> *
                                </label>
                                <div class="no-opcional-comentario">Este campo es OBLIGATORIO
                                </div>
                                <?php
                            }
                            ?>
                            <input type="text"
                                name="<?php echo esc_attr($group_meta_key); ?>[<?php echo $i; ?>][<?php echo esc_attr($this->get_nombre_meta()); ?>]"
                                value="<?php echo esc_attr(isset($pair[$this->get_nombre_meta()]) ? $pair[$this->get_nombre_meta()] : ''); ?>"
                                placeholder="<?php echo esc_attr($this->get_texto_de_ejemplificacion()); ?>"
                                style="width:100%; margin-bottom:5px;" />
                            <p class="description">
                                <?php echo esc_html($this->get_descripcion()); ?>
                            </p>
                            <?php
                            if ($this->get_es_campo_opcional()) {
                                ?>
                                <label for="<?php echo esc_attr($group_meta_key); ?>">
                                    <?php echo esc_html($this->get_etiqueta_asociado2()); ?>
                                </label>
                                <?php
                            } else {
                                ?>
                                <label class="no-opcional" for="<?php echo esc_attr($group_meta_key); ?>">
                                    <?php echo esc_html($this->get_etiqueta_asociado2()); ?> *
                                </label>
                                <div class="no-opcional-comentario">Este campo es OBLIGATORIO
                                </div>
                                <?php
                            }
                            ?>
                            <input type="text"
                                name="<?php echo esc_attr($group_meta_key); ?>[<?php echo $i; ?>][<?php echo esc_attr($this->get_nombre_meta_asociado2()); ?>]"
                                value="<?php echo esc_attr(isset($pair[$this->get_nombre_meta_asociado2()]) ? $pair[$this->get_nombre_meta_asociado2()] : ''); ?>"
                                placeholder="<?php echo esc_attr($this->get_texto_de_ejemplificacion_asociado2()); ?>"
                                style="width:100%; margin-bottom:5px;" />
                            <p class="description">
                                <?php echo esc_html($this->get_descripcion_asociado2()); ?>
                            </p>
                            <button type="button" class="button remove-field-texto-asociado">Eliminar</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-field-texto-asociado" style="margin-top:10px;">Agregar más</button>
            </div>
            <script>
                (function ($) {
                    $(document).ready(function () {
                        $('.clonable-container-texto-asociado').each(function () {
                            const container = $(this);
                            const groupMetaKey = '<?php echo esc_js($group_meta_key); ?>';

                            container.on('click', '.add-field-texto-asociado', function (e) {
                                e.preventDefault();
                                const lastField = container.find('.clonable-field-texto-asociado:last');
                                const newField = lastField.clone();
                                const index = container.find('.clonable-field-texto-asociado').length;

                                newField.find('input').each(function () {
                                    const name = $(this).attr('name')
                                        .replace(/\[\d+\]\[/, '[' + index + '][');
                                    $(this).attr('name', name).val('');
                                });

                                container.find('.clonable-fields-texto-asociado').append(newField);
                            });

                            container.on('click', '.remove-field-texto-asociado', function (e) {
                                e.preventDefault();
                                if (container.find('.clonable-field-texto-asociado').length > 1) {
                                    $(this).closest('.clonable-field-texto-asociado').remove();
                                    container.find('.clonable-field-texto-asociado').each(function (i) {
                                        $(this).find('input').each(function () {
                                            const name = $(this).attr('name')
                                                .replace(/\[\d+\]\[/, '[' + i + '][');
                                            $(this).attr('name', name);
                                        });
                                    });
                                }
                            });
                        });
                    });
                })(jQuery);
            </script>
            <?php
        }
    }
    public function generar_fragmento_html_formulario($llave)
    {
        if (!$this->get_clonable()) {
            // Campos no clonables
            $meta_llave1 = $llave . '_' . $this->get_nombre_meta();
            $meta_llave2 = $llave . '_' . $this->get_nombre_meta_asociado2();
            ?>
            <div style="margin-bottom: 20px;">
                <label for="<?php echo esc_attr($meta_llave1); ?>">
                    <?php echo esc_html($this->get_etiqueta()); ?>
                </label>
                <input type="text" id="<?php echo esc_attr($meta_llave1); ?>" name="<?php echo esc_attr($meta_llave1); ?>"
                    placeholder="<?php echo esc_attr($this->get_texto_de_ejemplificacion()); ?>" style="width: 100%;" />
                <p class="description">
                    <?php echo esc_html($this->get_descripcion()); ?>
                </p>

                <label for="<?php echo esc_attr($meta_llave2); ?>">
                    <?php echo esc_html($this->get_etiqueta_asociado2()); ?>
                </label>
                <input type="text" id="<?php echo esc_attr($meta_llave2); ?>" name="<?php echo esc_attr($meta_llave2); ?>"
                    placeholder="<?php echo esc_attr($this->get_texto_de_ejemplificacion_asociado2()); ?>" style="width: 100%;" />
                <p class="description">
                    <?php echo esc_html($this->get_descripcion_asociado2()); ?>
                </p>
            </div>
            <?php
        } else {
            $group_meta_key = $llave . '_' . $this->get_nombre_meta() . '_' . $this->get_nombre_meta_asociado2();
            // Inicializar con un par vacío
            $posible_json = array(
                array(array())
            );

            ?>
            <div class="clonable-container-texto-asociado">
                <div class="clonable-fields-texto-asociado">
                    <?php foreach ($posible_json[0] as $i => $pair): ?>
                        <div class="clonable-field-texto-asociado"
                            style="margin-bottom:15px; border-bottom:1px solid #ddd; padding-bottom:15px;">
                            <?php
                            if ($this->get_es_campo_opcional()) {
                                ?>
                                <label for="<?php echo esc_attr($group_meta_key); ?>">
                                    <?php echo esc_html($this->get_etiqueta()); ?>
                                </label>
                                <?php
                            } else {
                                ?>
                                <label class="no-opcional" for="<?php echo esc_attr($group_meta_key); ?>">
                                    <?php echo esc_html($this->get_etiqueta()); ?> *
                                </label>
                                <div class="no-opcional-comentario">Este campo es OBLIGATORIO
                                </div>
                                <?php
                            }
                            ?>
                            <input type="text"
                                name="<?php echo esc_attr($group_meta_key); ?>[<?php echo $i; ?>][<?php echo esc_attr($this->get_nombre_meta()); ?>]"
                                placeholder="<?php echo esc_attr($this->get_texto_de_ejemplificacion()); ?>"
                                style="width:100%; margin-bottom:5px;" />
                            <p class="description">
                                <?php echo esc_html($this->get_descripcion()); ?>
                            </p>
                            <?php
                            if ($this->get_es_campo_opcional()) {
                                ?>
                                <label for="<?php echo esc_attr($group_meta_key); ?>">
                                    <?php echo esc_html($this->get_etiqueta_asociado2()); ?>
                                </label>
                                <?php
                            } else {
                                ?>
                                <label class="no-opcional" for="<?php echo esc_attr($group_meta_key); ?>">
                                    <?php echo esc_html($this->get_etiqueta_asociado2()); ?> *
                                </label>
                                <div class="no-opcional-comentario">Este campo es OBLIGATORIO
                                </div>
                                <?php
                            }
                            ?>
                            <input type="text"
                                name="<?php echo esc_attr($group_meta_key); ?>[<?php echo $i; ?>][<?php echo esc_attr($this->get_nombre_meta_asociado2()); ?>]"
                                placeholder="<?php echo esc_attr($this->get_texto_de_ejemplificacion_asociado2()); ?>"
                                style="width:100%; margin-bottom:5px;" />
                            <p class="description">
                                <?php echo esc_html($this->get_descripcion_asociado2()); ?>
                            </p>
                            <button type="button" class="button remove-field-texto-asociado">Eliminar</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-field-texto-asociado" style="margin-top:10px;">Agregar más</button>
            </div>
            <script>
                (function ($) {
                    $(document).ready(function () {
                        $('.clonable-container-texto-asociado').each(function () {
                            const container = $(this);
                            const groupMetaKey = '<?php echo esc_js($group_meta_key); ?>';

                            container.on('click', '.add-field-texto-asociado', function (e) {
                                e.preventDefault();
                                const lastField = container.find('.clonable-field-texto-asociado:last');
                                const newField = lastField.clone();
                                const index = container.find('.clonable-field-texto-asociado').length;

                                newField.find('input').each(function () {
                                    const name = $(this).attr('name')
                                        .replace(/\[\d+\]\[/, '[' + index + '][');
                                    $(this).attr('name', name).val('');
                                });

                                container.find('.clonable-fields-texto-asociado').append(newField);
                            });

                            container.on('click', '.remove-field-texto-asociado', function (e) {
                                e.preventDefault();
                                if (container.find('.clonable-field-texto-asociado').length > 1) {
                                    $(this).closest('.clonable-field-texto-asociado').remove();
                                    container.find('.clonable-field-texto-asociado').each(function (i) {
                                        $(this).find('input').each(function () {
                                            const name = $(this).attr('name')
                                                .replace(/\[\d+\]\[/, '[' + i + '][');
                                            $(this).attr('name', name);
                                        });
                                    });
                                }
                            });
                        });
                    });
                })(jQuery);
            </script>
            <?php
        }
    }
}