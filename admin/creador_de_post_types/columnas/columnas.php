<?php
require_once dirname(__FILE__) . '/../caracteristicas_minimas_post_type.php';
require_once dirname(__FILE__) . '/../../funciones.php';

class ColumnasDeWordpress extends CaracteristicasMinimasPostType
{
    /**
     * Constructor de ColumnasDeWordpress
     * @param array $para_armar_columnas
     * @param string $id_post_type
     */
    public function __construct($para_armar_columnas, $id_post_type)
    {
        $this->set_para_armar_columnas($para_armar_columnas);
        $this->set_id_post_type($id_post_type);

        // Agregar columnas
        add_filter('manage_' . $this->get_id_post_type() . '_posts_columns', array($this, 'mis_columnas'));
        // Mostrat contenido de columnas
        add_action('manage_' . $this->get_id_post_type() . '_posts_custom_column', array($this, 'cargar_mis_columnas'), 10, 2);
        // Agrega columnas ordenables
        add_filter('manage_edit-' . $this->get_id_post_type() . '_sortable_columns', array($this, 'mis_columnas_ordenables'));
        // Ordenamientos en la comuna de autor
        add_action('pre_get_posts', array($this, 'manejar_ordenamiento_columnas'));
    }
    public function mis_columnas_ordenables($columns)
    {
        if (!empty($this->get_para_armar_columnas())) {
            foreach ($this->get_para_armar_columnas() as $columna_para_armar) {
                $columns[$columna_para_armar->get_id_caja_metadata()] = $columna_para_armar->get_id_caja_metadata();
            }
        }
        $columns['creador'] = 'author';
        $columns['fecha_de_carga'] = 'date';
        $columns['modificador'] = 'Último modificador';
        $columns['fecha_de_modificacion'] = 'modified';
        $columns['estado_de_publicacion'] = 'post_status';
        return $columns;
    }
    public function manejar_ordenamiento_columnas($query)
    {
        global $wpdb;

        $orderby = $query->get('orderby');

        if (!empty($this->get_para_armar_columnas())) {
            foreach ($this->get_para_armar_columnas() as $columna_para_armar) {
                if ($orderby == $columna_para_armar->get_id_caja_metadata()) {
                    // Obtener el valor del meta_key para determinar si es numérico o no
                    $meta_key = $columna_para_armar->get_id_caja_metadata();
                    $meta_value = $wpdb->get_var($wpdb->prepare(
                        "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s LIMIT 1",
                        $meta_key
                    ));

                    // Determinar si el valor es numérico
                    if (is_numeric($meta_value)) {
                        $query->set('meta_key', $meta_key);
                        $query->set('orderby', 'meta_value_num'); // Ordenar numéricamente
                    } else {
                        $query->set('meta_key', $meta_key);
                        $query->set('orderby', 'meta_value'); // Ordenar alfabéticamente
                    }
                }
            }
        } elseif ($orderby == 'modificador') {
            $query->get_results($query->prepare("SELECT * FROM wp_postmeta ORDER BY meta_id"));
        } elseif ($orderby == 'estado_de_publicacion') {
            $query->set('orderby', 'post_status');

        }
    }
    public function mis_columnas($columnas)
    {
        $contador = 1;
        $nuevas_columnas = array(
            'cb' => $columnas['cb'],
            'creador' => 'Creador',
            'fecha_de_carga' => 'Fecha de carga',
            'modificador' => 'Último modificador',
            'fecha_de_modificacion' => 'Fecha de modificación',
            'estado_de_publicacion' => 'Estado de publicación',
        );
        if (!empty($this->get_para_armar_columnas())) {
            foreach ($this->get_para_armar_columnas() as $columna_para_armar) {
                $nuevas_columnas = array_merge(
                    array('cb' => $nuevas_columnas['cb']),
                    array_slice($nuevas_columnas, 0, $contador, true),
                    array($columna_para_armar->get_id_caja_metadata() => $columna_para_armar->get_etiqueta_caja_de_metadata()),
                    array_slice($nuevas_columnas, $contador, null, true)
                );
                $contador += 1;
            }
        } else {
            // Agregar la columna 'title' después de 'cb'
            $nuevas_columnas = array_merge(
                array('cb' => $nuevas_columnas['cb'], 'title' => 'Título'),
                array_slice($nuevas_columnas, 1, null, true)
            );
        }
        return $nuevas_columnas;
    }
    public function cargar_mis_columnas($columnas, $post_id)
    {
        $nombres_post_types = get_post_types([], 'names');
        if (!empty($this->get_para_armar_columnas())) {
            foreach ($this->get_para_armar_columnas() as $columna_para_armar) {
                $post_meta = get_post_meta($post_id, $columna_para_armar->get_id_caja_metadata(), true);
                if ($columnas == $columna_para_armar->get_etiqueta_caja_de_metadata()) {
                    if (in_array($columna_para_armar->get_etiqueta_caja_de_metadata(), $nombres_post_types)) {
                        $debug = get_post($post_meta)->post_title;
                    } else {
                        $debug = $post_meta;
                    }
                    echo esc_html($debug);
                }
            }
        }
        if ($columnas == 'creador') {
            echo esc_html(get_the_author());
        } elseif ($columnas == 'fecha_de_carga') {
            echo esc_html(get_the_date("", $post_id));
        } elseif ($columnas == 'modificador') {
            $last_id = get_post_meta($post_id, '_edit_last', true);
            if ($last_id) {
                $user = get_userdata($last_id);
                echo esc_html($user->display_name);
            } else {
                echo esc_html__('N/A', 'textdomain');
            }
        } elseif ($columnas == 'fecha_de_modificacion') {
            echo esc_html(get_the_modified_date("", $post_id));
        } elseif ($columnas == 'estado_de_publicacion') {
            $estado = get_post_status($post_id);
            $estados = array(
                'publish' => __('Publicado', 'text-domain'),
                'draft' => __('Borrador', 'text-domain'),
                'pending' => __('Pendiente de revisión', 'text-domain'),
                'future' => __('Programado', 'text-domain'),
                'private' => __('Privado', 'text-domain')
            );
            echo esc_html($estados[$estado] ?? ucfirst($estado));
        }
    }
}