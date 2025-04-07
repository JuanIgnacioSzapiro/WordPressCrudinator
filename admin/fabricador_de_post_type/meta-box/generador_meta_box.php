<?php // generador_meta_box.php

class TipoMetaBox
{
    protected $post_type_de_origen; //post_type al que pertenece
    protected $titulo_de_editor;
    protected $contenido;
    protected $nombre_meta;
    protected $etiqueta;
    protected $texto_de_ejemplificacion;
    protected $descripcion;
    protected $nombre_meta_asociado2;
    protected $etiqueta_asociado2;
    protected $texto_de_ejemplificacion_asociado2;
    protected $descripcion_asociado2;
    protected $tipo_de_input_asociado2;
    protected $post_type_buscado;
    protected $tipo_de_archivo;
    protected $clonable = false;
    protected $opciones;
    protected $titulo;
    protected $tipo_de_input;
    protected $es_campo_opcional;
    protected $query;

    public function __construct($titulo_de_editor, $contenido, $titulo)
    {
        $this->set_titulo_de_editor($titulo_de_editor);
        $this->set_contenido($contenido);
        $this->set_titulo($titulo);

        add_action('admin_notices', array($this, 'mostrar_errores'));
    }

    public function set_post_type_de_origen($post_type_de_origen)
    {
        $this->post_type_de_origen = $post_type_de_origen;
    }

    public function set_titulo_de_editor($titulo_de_editor)
    {
        $this->titulo_de_editor = $titulo_de_editor;
    }
    public function set_contenido($contenido)
    {
        $this->contenido = $contenido;
    }

    public function set_nombre_meta($nombre_meta)
    {
        $this->nombre_meta = $nombre_meta;
    }

    public function set_etiqueta($etiqueta)
    {
        $this->etiqueta = $etiqueta;
    }
    public function set_texto_de_ejemplificacion($texto_de_ejemplificacion)
    {
        $this->texto_de_ejemplificacion = $texto_de_ejemplificacion;
    }
    public function set_descripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    public function set_post_type_buscado($post_type_buscado)
    {
        $this->post_type_buscado = $post_type_buscado;
    }

    public function set_tipo_de_archivo($tipo_de_archivo)
    {
        $this->tipo_de_archivo = $tipo_de_archivo;
    }

    public function get_contenido()
    {
        return $this->contenido;
    }

    public function get_llave_meta()
    {
        $x = isset($GLOBALS['prefijo_variables_sql']) ? $GLOBALS['prefijo_variables_sql'] : 'INSPT_SISTEMA_DE_INSCRIPCIONES';
        return $x . '_' . $this->get_post_type_de_origen();
    }

    public function get_post_type_de_origen()
    {
        return $this->post_type_de_origen;
    }

    public function get_titulo_de_editor()
    {
        return $this->titulo_de_editor;
    }

    public function get_nombre_meta()
    {
        return (string) $this->nombre_meta;
    }

    public function get_etiqueta()
    {
        return $this->etiqueta;
    }
    public function get_texto_de_ejemplificacion()
    {
        return $this->texto_de_ejemplificacion;
    }
    public function get_descripcion()
    {
        return $this->descripcion;
    }
    public function get_post_type_buscado()
    {
        return $this->post_type_buscado;
    }
    public function get_tipo_de_archivo()
    {
        return $this->tipo_de_archivo;
    }
    public function set_clonable($clonable)
    {
        $this->clonable = $clonable;
    }
    public function get_clonable()
    {
        return $this->clonable;
    }
    public function set_opciones($opciones)
    {
        $this->opciones = $opciones;
    }
    public function get_opciones()
    {
        return $this->opciones;
    }
    public function set_titulo($valor)
    {
        $this->titulo = $valor;
    }
    public function get_titulo()
    {
        return $this->titulo;
    }

    public function set_tipo_de_input($valor)
    {
        $this->tipo_de_input = $valor;
    }

    public function get_tipo_de_input()
    {
        return $this->tipo_de_input;
    }

    // Setters y Getters para asociado2
    public function set_nombre_meta_asociado2($valor = '')
    {
        $this->nombre_meta_asociado2 = $valor;
    }
    public function get_nombre_meta_asociado2()
    {
        return $this->nombre_meta_asociado2;
    }

    public function set_etiqueta_asociado2($valor)
    {
        $this->etiqueta_asociado2 = $valor;
    }
    public function get_etiqueta_asociado2()
    {
        return $this->etiqueta_asociado2;
    }

    public function set_texto_de_ejemplificacion_asociado2($valor)
    {
        $this->texto_de_ejemplificacion_asociado2 = $valor;
    }
    public function get_texto_de_ejemplificacion_asociado2()
    {
        return $this->texto_de_ejemplificacion_asociado2;
    }

    public function set_descripcion_asociado2($valor)
    {
        $this->descripcion_asociado2 = $valor;
    }
    public function get_descripcion_asociado2()
    {
        return $this->descripcion_asociado2;
    }

    public function set_tipo_de_input_asociado2($valor)
    {
        $this->tipo_de_input_asociado2 = $valor;
    }
    public function get_tipo_de_input_asociado2()
    {
        return $this->tipo_de_input_asociado2;
    }
    public function set_es_campo_opcional($valor)
    {
        $this->es_campo_opcional = $valor;
    }
    public function get_es_campo_opcional()
    {
        return $this->es_campo_opcional;
    }

    public function get_nombre_meta_con_llave()
    {
        return $this->get_llave_meta() . '_' . $this->get_nombre_meta();
    }
    public function set_query($valor)
    {
        $this->query = $valor;
    }

    public function get_query()
    {
        return $this->query;
    }


    public function crear_tipo_meta_box()
    {
        add_action('add_meta_boxes', array($this, 'crear_metadata'));
        add_action('save_post', array($this, 'guardar'));
    }

    public function crear_metadata()
    {
        add_meta_box($this->get_llave_meta(), $this->get_titulo_de_editor(), array($this, 'mostrar'), $this->get_post_type_de_origen());
    }

    public function mostrar($post)
    {
        $llave_meta = esc_attr($this->get_llave_meta());

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
            foreach ($this->contenido as $individual) {
                ?>
                <div class="en-meta-box">
                    <?php
                    $individual->generar_fragmento_html($post, $this->get_llave_meta());
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }


    public function guardar($post_id)
    {
        $nonce_name = $this->get_llave_meta();

        // Validaciones iniciales
        if (!isset($_POST[$nonce_name]))
            return;
        if (!wp_verify_nonce($_POST[$nonce_name], $nonce_name))
            return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;
        if (!current_user_can('edit_' . $this->get_post_type_de_origen(), $post_id))
            return;

        foreach ($this->contenido as $individual) {
            if ($individual instanceof CampoTextoAsociado) {
                // Manejar campos de texto asociados
                if ($individual->get_clonable()) {
                    $group_meta_key = $this->get_llave_meta() . '_' . $individual->get_nombre_meta() . '_' . $individual->get_nombre_meta_asociado2();
                    $valores = isset($_POST[$group_meta_key]) ? (array) $_POST[$group_meta_key] : [];

                    $valores_sanitizados = [];
                    foreach ($valores as $valor) {
                        $valor_1 = $this->sanitizar_valor(
                            $valor[$individual->get_nombre_meta()] ?? '',
                            $individual->get_tipo_de_input()
                        );

                        $valor_2 = $this->sanitizar_valor(
                            $valor[$individual->get_nombre_meta_asociado2()] ?? '',
                            $individual->get_tipo_de_input_asociado2()
                        );

                        if ($valor_1 !== false && $valor_2 !== false) {
                            $valores_sanitizados[] = [
                                $individual->get_nombre_meta() => $valor_1,
                                $individual->get_nombre_meta_asociado2() => $valor_2
                            ];
                        }
                    }

                    // Convertir a JSON y guardar
                    update_post_meta(
                        $post_id,
                        $group_meta_key,
                        wp_json_encode($valores_sanitizados) // Serializa a JSON
                    );
                } else {
                    $group_meta_key = $this->get_llave_meta() . '_' . $individual->get_nombre_meta() . '_' . $individual->get_nombre_meta_asociado2();

                    $valor_1 = $this->sanitizar_valor($_POST[$individual->get_nombre_meta()] ?? '', $individual->get_tipo_de_input());
                    $valor_2 = $this->sanitizar_valor($_POST[$individual->get_nombre_meta_asociado2()] ?? '', $individual->get_tipo_de_input_asociado2());

                    if ($valor_1 !== false && $valor_2 !== false) {
                        // Crear un objeto asociativo y guardar como JSON
                        $datos_json = wp_json_encode([
                            $individual->get_nombre_meta() => $valor_1,
                            $individual->get_nombre_meta_asociado2() => $valor_2
                        ]);

                        update_post_meta($post_id, $group_meta_key, $datos_json);
                    }
                }
            } elseif ($individual instanceof CampoArchivo) {
                // Manejar CampoArchivo
                $meta_key = $this->get_llave_meta() . '_' . $individual->get_nombre_meta();

                if ($individual->get_clonable()) {
                    $valores = isset($_POST[$meta_key]) ? (array) $_POST[$meta_key] : [];
                    $sanitized_values = array();

                    foreach ($valores as $valor) {
                        $clean_value = absint($valor);
                        if ($clean_value > 0) {
                            $sanitized_values[] = $clean_value;
                        }
                    }

                    delete_post_meta($post_id, $meta_key);
                    foreach ($sanitized_values as $value) {
                        add_post_meta($post_id, $meta_key, $value);
                    }
                } else {
                    $valor = isset($_POST[$meta_key]) ? absint($_POST[$meta_key]) : '';
                    update_post_meta($post_id, $meta_key, $valor);
                }
            } elseif ($individual instanceof CampoCheckbox || $individual instanceof CampoCheckboxQuery) {
                $meta_key = $this->get_llave_meta() . '_' . $individual->get_nombre_meta();

                // Checkbox simple o múltiple no clonable
                if (!empty($individual->get_opciones()) || $individual instanceof CampoCheckboxQuery) {
                    // Múltiples checkboxes (valores como array)
                    $valores = isset($_POST[$meta_key]) ? (array) $_POST[$meta_key] : [];
                    $sanitized_values = array_map('sanitize_text_field', $valores);
                    update_post_meta($post_id, $meta_key, $sanitized_values);
                } else {
                    // Checkbox único (valor 1/0)
                    $valor = isset($_POST[$meta_key]) ? 'true' : 'false';
                    update_post_meta($post_id, $meta_key, $valor);
                }
            } elseif ($individual instanceof CampoFecha) { // Nuevo caso para CampoFecha
                $meta_key = $this->get_llave_meta() . '_' . $individual->get_nombre_meta();

                if ($individual->get_clonable()) {
                    $valores = isset($_POST[$meta_key]) ? (array) $_POST[$meta_key] : [];
                    $sanitized_values = [];
                    foreach ($valores as $valor) {
                        $clean_value = sanitize_text_field($valor);
                        if (!empty($clean_value)) {
                            $sanitized_values[] = $clean_value;
                        }
                    }
                    delete_post_meta($post_id, $meta_key);
                    foreach ($sanitized_values as $value) {
                        add_post_meta($post_id, $meta_key, $value);
                    }
                } else {
                    $valor = sanitize_text_field($_POST[$meta_key] ?? '');
                    // Convertir a dd/mm/yyyy si es válido
                    if (!empty($valor)) {
                        $fecha = DateTime::createFromFormat('Y-m-d', $valor);
                        if ($fecha) {
                            $valor = $fecha->format('d/m/Y');
                        } else {
                            $valor = ''; // Si la fecha es inválida, se trata como vacío
                        }
                    }
                    update_post_meta($post_id, $meta_key, $valor);
                }
            } else {
                // Lógica original para otros tipos de campos
                $meta_key = $this->get_llave_meta() . '_' . $individual->get_nombre_meta();

                if ($individual->get_clonable()) {
                    $valores = isset($_POST[$meta_key]) ? (array) $_POST[$meta_key] : [];

                    $sanitized_values = [];
                    foreach ($valores as $valor) {
                        if ($individual instanceof CampoDropDownTipoPost) {
                            $clean_value = intval($valor);
                            if ($clean_value > 0)
                                $sanitized_values[] = $clean_value;
                        } else {
                            $clean_value = $this->sanitizar_valor($valor, $individual->get_tipo_de_input());
                            if ($clean_value !== false)
                                $sanitized_values[] = $clean_value;
                        }
                    }

                    delete_post_meta($post_id, $meta_key);
                    foreach ($sanitized_values as $value) {
                        add_post_meta($post_id, $meta_key, $value);
                    }
                } else {
                    $valor = $this->sanitizar_valor($_POST[$meta_key] ?? '', $individual->get_tipo_de_input());
                    update_post_meta($post_id, $meta_key, $valor);
                }
            }
        }

        // Asignación automática del título compuesto
        if ($this->get_titulo() && is_array($this->get_titulo())) {
            $partes_titulo = [];

            foreach ($this->get_titulo() as $campo_titulo) {
                $meta_key = $this->get_llave_meta() . '_' . $campo_titulo;
                $valor = get_post_meta($post_id, $meta_key, true);

                $partes_titulo = [];

                foreach ($this->get_titulo() as $campo_titulo) {
                    $meta_key = $this->get_llave_meta() . '_' . $campo_titulo;

                    // Check if the current campo is a CampoDropDownTipoPost
                    $current_campo = null;
                    foreach ($this->contenido as $campo) {
                        if ($campo->get_nombre_meta() === $campo_titulo) {
                            $current_campo = $campo;
                            break;
                        }
                    }

                    if ($current_campo instanceof CampoDropDownTipoPost) {
                        $partes_titulo = ['Documentacion requerida'];
                        // Handle dropdown post type
                        $clonable = $current_campo->get_clonable();
                        $ids = $clonable
                            ? get_post_meta($post_id, $meta_key, false)
                            : [get_post_meta($post_id, $meta_key, true)];

                        $titles = [];
                        foreach ($ids as $id) {
                            if ($id) {
                                $title = get_the_title($id);
                                if ($title) {
                                    $titles[] = $title;
                                }
                            }
                        }

                        if (!empty($titles)) {
                            $partes_titulo[] = implode(' - ', $titles);
                        }
                    } else {
                        // Original logic for other fields
                        $valor = get_post_meta($post_id, $meta_key, true);
                        if (!empty($valor)) {
                            $valor = is_array($valor) ? current($valor) : $valor;
                            $partes_titulo[] = $valor;
                        }
                    }
                }

            }

            if (!empty($partes_titulo)) {
                $nuevo_titulo = implode(' - ', $partes_titulo);

                remove_action('save_post', [$this, 'guardar']);
                wp_update_post([
                    'ID' => $post_id,
                    'post_title' => $nuevo_titulo,
                    'post_name' => sanitize_title($nuevo_titulo)
                ]);
                add_action('save_post', [$this, 'guardar']);
            }
        }

        // Validación durante la publicación
        $is_publishing = isset($_POST['post_status']) && $_POST['post_status'] === 'publish';
        if (!$is_publishing)
            return;

        $errors = [];
        foreach ($this->contenido as $individual) {
            if ($individual instanceof CampoTextoAsociado && $individual->get_clonable()) {
                // Validación para campos asociados clonables
                $group_meta_key = $this->get_llave_meta() . '_' . $individual->get_nombre_meta() . '_' . $individual->get_nombre_meta_asociado2();
                $valores = isset($_POST[$group_meta_key]) ? (array) $_POST[$group_meta_key] : [];

                foreach ($valores as $index => $valor) {
                    $valor_1 = $valor[$individual->get_nombre_meta()] ?? '';
                    $valor_2 = $valor[$individual->get_nombre_meta_asociado2()] ?? '';

                    $error1 = $this->validar_valor($valor_1, $individual->get_tipo_de_input(), $individual->get_etiqueta());
                    $error2 = $this->validar_valor($valor_2, $individual->get_tipo_de_input_asociado2(), $individual->get_etiqueta_asociado2());

                    if ($error1)
                        $errors[] = $error1;
                    if ($error2)
                        $errors[] = $error2;

                    // Validar requeridos
                    if (empty($valor_1) && !$individual->get_es_campo_opcional()) {
                        $errors[] = sprintf(__('Campo "%s" (posición %d) es obligatorio'), $individual->get_etiqueta(), $index + 1);
                    }
                    if (empty($valor_2) && !$individual->get_es_campo_opcional()) {
                        $errors[] = sprintf(__('Campo "%s" (posición %d) es obligatorio'), $individual->get_etiqueta_asociado2(), $index + 1);
                    }
                }
            } elseif ($individual instanceof CampoArchivo) {
                $meta_key = $this->get_llave_meta() . '_' . $individual->get_nombre_meta();
                $submitted_values = $individual->get_clonable() ?
                    (isset($_POST[$meta_key]) ? (array) $_POST[$meta_key] : []) :
                    (isset($_POST[$meta_key]) ? [$_POST[$meta_key]] : []);

                $allowed_types = $individual->get_tipo_de_archivo() ?: [];
                $filtered = [];
                $invalid_files = [];

                foreach ($submitted_values as $value) {
                    if (empty($value))
                        continue;

                    $attachment_id = absint($value);
                    $mime_type = get_post_mime_type($attachment_id);

                    // Switch para validación de tipos MIME
                    $is_valid = false;
                    switch (true) {
                        case in_array($mime_type, $allowed_types):
                            $is_valid = true;
                            break;
                        case empty($allowed_types): // Si no hay restricciones
                            $is_valid = true;
                            break;
                        default:
                            $invalid_files[] = $mime_type;
                            break;
                    }

                    if ($is_valid) {
                        $filtered[] = $attachment_id;
                    }
                }

                // Manejo de errores
                if (!empty($invalid_files)) {
                    $allowed_extensions = implode(', ', array_map(function ($mime) {
                        return explode('/', $mime)[1]; // Ej: 'pdf' de 'application/pdf'
                    }, $allowed_types));

                    $errors[] = sprintf(
                        __('Error en "%s": Archivos permitidos (%s). Tipos subidos: %s'),
                        $individual->get_etiqueta(),
                        $allowed_extensions,
                        implode(', ', array_unique($invalid_files))
                    );
                }

                if (empty($filtered) && !$individual->get_es_campo_opcional()) {
                    $errors[] = sprintf(__('El campo "%s" es obligatorio'), $individual->get_etiqueta());
                }
            } else {
                // Validación original para otros campos
                $meta_key = $this->get_llave_meta() . '_' . $individual->get_nombre_meta();
                $submitted_values = $individual->get_clonable() ?
                    (isset($_POST[$meta_key]) ? (array) $_POST[$meta_key] : []) :
                    (isset($_POST[$meta_key]) ? [$_POST[$meta_key]] : []);

                if (empty(array_filter($submitted_values)) && !$individual->get_es_campo_opcional()) {
                    $errors[] = sprintf(__('El campo "%s" es obligatorio'), $individual->get_etiqueta());
                }
            }
        }

        if (!empty($errors)) {
            set_transient('inpsc_meta_errors_' . $post_id, $errors, 45);
            remove_action('save_post', [$this, 'guardar']);
            wp_update_post(['ID' => $post_id, 'post_status' => 'draft']);
            add_action('save_post', [$this, 'guardar']);
        } else {
            delete_transient('inpsc_meta_errors_' . $post_id);
        }
    }

    private function sanitizar_valor($valor, $tipo)
    {
        $valor = trim($valor);
        switch ($tipo) {
            case 'int':
                return filter_var($valor, FILTER_VALIDATE_INT);
            case 'float':
                $valor = str_replace(',', '.', $valor);
                return filter_var($valor, FILTER_VALIDATE_FLOAT);
            default: // string
                return sanitize_text_field($valor);
        }
    }

    private function validar_valor($valor, $tipo, $etiqueta)
    {
        $valor = trim($valor);
        if (empty($valor))
            return null;

        switch ($tipo) {
            case 'int':
                if (preg_match('/\s/', $valor)) {
                    return sprintf(__('El campo "%s" no puede contener espacios'), $etiqueta);
                }
                if (!ctype_digit(str_replace('-', '', $valor))) {
                    return sprintf(__('El campo "%s" debe ser un número entero válido'), $etiqueta);
                }
                break;

            case 'float':
                if (preg_match('/\s/', $valor)) {
                    return sprintf(__('El campo "%s" no puede contener espacios'), $etiqueta);
                }
                if (substr_count($valor, ',') > 1) {
                    return sprintf(__('El campo "%s" debe tener máximo una coma decimal'), $etiqueta);
                }
                $float_val = str_replace(',', '.', $valor);
                if (!is_numeric($float_val)) {
                    return sprintf(__('El campo "%s" debe ser un número decimal válido'), $etiqueta);
                }
                break;

            case 'string':
            default:
                // No se aplican restricciones adicionales
                break;
        }

        return null;
    }


    public function mostrar_errores()
    {
        global $post;

        if (!$post || $post->post_type !== $this->get_post_type_de_origen()) {
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