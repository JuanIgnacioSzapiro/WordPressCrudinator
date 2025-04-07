<?php //meta_box_tipo_drop_down_post.php
class CampoDropDownTipoPost extends TipoMetaBox
{
    private static $js_added = false;

    public function __construct(
        $nombre_meta,
        $etiqueta,
        $post_type_buscado,
        $descripcion,
        $clonable = false,
        $es_campo_opcional = false,
    ) {
        $this->set_nombre_meta($nombre_meta);
        $this->set_etiqueta($etiqueta);
        $this->set_post_type_buscado($post_type_buscado);
        $this->set_descripcion($descripcion);
        $this->set_clonable($clonable);
        $this->set_es_campo_opcional($es_campo_opcional);
    }

    public function generar_fragmento_html($post, $llave_meta)
    {
        $meta_key = $llave_meta . '_' . $this->get_nombre_meta();

        if (!$this->get_clonable()) {
            // Lógica NO clonable (igual a la versión anterior)
            $selected_value = get_post_meta($post->ID, $meta_key, true);
            $posts = $this->obtener_posts();

            ?>
            <div class="campo-dropdown-container">
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
                <?php $this->generar_buscador_y_select($meta_key, $posts, $selected_value); ?>
            </div>
            <?php
        } else {
            // Lógica CLONABLE (nueva)
            $values = get_post_meta($post->ID, $meta_key);
            ?>
            <div class="clonable-container-drop-down">
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
                <div class="clonable-fields-drop-down">
                    <?php
                    if (empty($values))
                        $values = [''];
                    foreach ($values as $value) {
                        $this->generar_campo_clonable($meta_key, $value);
                    }
                    ?>
                </div>
                <button type="button" class="button add-field-drop-down">Agregar más</button>
            </div>
            <?php
        }

        $this->agregar_scripts();
    }

    private function obtener_posts()
    {
        return get_posts([
            'post_type' => $this->get_post_type_buscado(),
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
        ]);
    }

    private function generar_buscador_y_select($meta_key, $posts, $selected_value = '', $is_clonable = false)
    {
        $unique_id = uniqid();
        ?>
        <input id="buscador_<?php echo esc_attr($unique_id); ?>" type="text" placeholder="Buscador" class="buscador-dropdown">
        <select name="<?php echo $is_clonable ? esc_attr($meta_key) . '[]' : esc_attr($meta_key); ?>"
            id="<?php echo esc_attr($meta_key . '_' . $unique_id); ?>">
            <option value="">Seleccionar...</option>
            <?php foreach ($posts as $post_option): ?>
                <option value="<?php echo esc_attr($post_option->ID); ?>" <?php $selected_value != '' ? selected($selected_value, $post_option->ID) : ''; ?>>
                    <?php echo esc_html($post_option->post_title); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    private function generar_campo_clonable($meta_key, $selected_value)
    {
        $posts = $this->obtener_posts();
        ?>
        <div class="clonable-field-drop-down">
            <div class="campo-dropdown-container">
                <?php $this->generar_buscador_y_select($meta_key, $posts, $selected_value, true); ?>
                <br>
                <button type="button" class="button remove-field-drop-down">Eliminar</button>
            </div>
        </div>
        <?php
    }

    private function agregar_scripts()
    {
        if (!self::$js_added) {
            ?>
            <script>
                (function ($) {
                    // Función única para manejar clonado
                    if (!window.hasOwnProperty('clonableDropdownHandler')) {
                        window.clonableDropdownHandler = {
                            init: function () {
                                // Eventos para clonado
                                $(document)
                                    .on('click', '.clonable-container-drop-down .add-field-drop-down', function (e) {
                                        const container = $(this).closest('.clonable-container-drop-down');
                                        const newField = container.find('.clonable-field-drop-down:last').clone();
                                        newField.find('select, input').val('');
                                        container.find('.clonable-fields-drop-down').append(newField);
                                    })
                                    .on('click', '.clonable-container-drop-down .remove-field-drop-down', function (e) {
                                        if ($(this).closest('.clonable-fields-drop-down').find('.clonable-field-drop-down').length > 1) {
                                            $(this).closest('.clonable-field-drop-down').remove();
                                        }
                                    });

                                // Evento único para búsqueda con delegación
                                $(document).on('input', '.buscador-dropdown', function (e) {
                                    const searchText = $(this).val().toLowerCase();
                                    const container = $(this).closest('.campo-dropdown-container');

                                    container.find('select option').each(function () {
                                        const $option = $(this);
                                        $option.toggle(
                                            $option.text().toLowerCase().includes(searchText) ||
                                            $option.val() === ''
                                        );
                                    });
                                });
                            }
                        };

                        // Inicialización automática al cargar
                        $(document).ready(function () {
                            window.clonableDropdownHandler.init();
                        });
                    }
                })(jQuery);
            </script>
            <?php
            self::$js_added = true;
        }
    }

    public function generar_fragmento_html_formulario($llave_meta)
    {
        $meta_key = $llave_meta . '_' . $this->get_nombre_meta();

        if (!$this->get_clonable()) {
            // Lógica NO clonable (igual a la versión anterior)
            $posts = $this->obtener_posts();
            ?>
            <div class="campo-dropdown-container">
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
                <?php $this->generar_buscador_y_select($meta_key, $posts); ?>
            </div>
            <?php
        } else {
            ?>
            <div class="clonable-container-drop-down">
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
                <div class="clonable-fields-drop-down">
                    <?php
                    if (empty($values))
                        $values = [''];
                    foreach ($values as $value) {
                        $this->generar_campo_clonable($meta_key, $value);
                    }
                    ?>
                </div>
                <button type="button" class="button add-field-drop-down">Agregar más</button>
            </div>
            <?php
        }

        $this->agregar_scripts();
    }
}