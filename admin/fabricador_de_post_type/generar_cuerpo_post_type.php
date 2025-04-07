<?php
require_once dirname(__FILE__) . '/meta-box/generador_meta_box.php';
require_once dirname(__FILE__) . '/meta-box/meta_box_tipo_archivo.php';
require_once dirname(__FILE__) . '/meta-box/meta_box_tipo_drop_down_post.php';
require_once dirname(__FILE__) . '/meta-box/meta_box_tipo_texto.php';
require_once dirname(__FILE__) . '/filtros/creador_filtros.php';
require_once dirname(__FILE__) . '/filtros/filtro.php';
require_once dirname(__FILE__) . '/generador_post_type.php';
require_once dirname(__FILE__) . '/meta-box/meta_box_clave.php';


class CuerpoPostType extends CaracteristicasBasicasPostType
{
    private $prefijo;
    private $para_armar_columnas;

    public function __construct(
        $singular,
        $nombre_para_mostrar,
        $plural,
        $femenino,
        $prefijo,
        $icono,
        $meta,
        $para_armar_columnas,
    ) {
        $this->set_singular($singular);
        $this->set_nombre_para_mostrar($nombre_para_mostrar);
        $this->set_plural($plural);
        $this->set_femenino($femenino);
        $this->set_prefijo($prefijo);
        $this->set_icono($icono);
        $this->set_meta($meta);
        $this->set_para_armar_columnas($para_armar_columnas);

        $meta = $this->get_meta();
        $meta->set_post_type_de_origen($this->get_plural());

        $meta->crear_tipo_meta_box();

        // Registrar columnas
        add_filter('manage_edit-' . $this->get_plural() . '_sortable_columns', array($this, 'mis_columnas_ordenables'));
        add_filter('manage_' . $this->get_plural() . '_posts_columns', array($this, 'mis_columnas'));
        add_action('manage_' . $this->get_plural() . '_posts_custom_column', array($this, 'cargar_mis_columnas'), 10, 2);

        // Ordenamientos
        add_action('pre_get_posts', array($this, 'manejar_ordenamiento_columnas'));

        // Filtros
        $this->mis_filtros();

        // Templates
        add_action('template_redirect', array($this, 'add_template_support'));

        $this->registrar_post_type();

        add_action('admin_init', array($this, 'generar_csv'));
        add_filter('views_edit-' . $this->get_plural(), array($this, 'agregar_boton_csv'));

        add_shortcode('shortcode_listado_post_type_' . $this->get_plural(), array($this, 'mostrar_pantalla_de_listado'));

        $this->generador_pagina_post_type_listado();

        add_action('wp_ajax_generar_csv_frontend', array($this, 'generar_csv_frontend'));
    }
    public function set_prefijo($valor)
    {
        $this->prefijo = $valor;
    }

    public function get_prefijo()
    {
        return $this->prefijo;
    }

    public function set_para_armar_columnas($valor)
    {
        $this->para_armar_columnas = $valor;
    }

    public function get_para_armar_columnas()
    {
        return $this->para_armar_columnas;
    }
    public function mis_columnas_ordenables($columns)
    {
        if (!empty($this->get_para_armar_columnas())) {
            foreach ($this->get_para_armar_columnas() as $columna_para_armar) {
                $columns[$columna_para_armar] = $this->get_prefijo() . '_' . $this->get_plural() . '_' . $columna_para_armar;
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
                if ($orderby == $this->get_prefijo() . '_' . $this->get_plural() . '_' . $columna_para_armar) {
                    // Obtener el valor del meta_key para determinar si es numérico o no
                    $meta_key = $this->get_prefijo() . '_' . $this->get_plural() . '_' . $columna_para_armar;
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
            foreach ($this->get_para_armar_columnas() as $columnas_para_armar) {
                $nuevas_columnas = array_merge(
                    array('cb' => $nuevas_columnas['cb']),
                    array_slice($nuevas_columnas, 0, $contador, true),
                    array($columnas_para_armar => str_replace('_', ' ', ucfirst($columnas_para_armar))),
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
                $post_meta = get_post_meta($post_id, $this->get_prefijo() . '_' . $this->get_plural() . '_' . $columna_para_armar, true);
                if ($columnas == $columna_para_armar) {
                    if (in_array($columna_para_armar, $nombres_post_types)) {
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

    public function mis_filtros()
    {
        $id_filtro = '';
        $la_query = '';
        $post_type = $this->get_plural();

        if (!empty($this->get_para_armar_columnas())) {
            foreach ($this->get_para_armar_columnas() as $key => $columna_para_armar) {
                $id_filtro .= '_' . $columna_para_armar;
                if ($key < count($this->get_para_armar_columnas()) - 1) {
                    $id_filtro .= '_o_';
                }

                $meta_key = $this->get_prefijo() . '_' . $this->get_plural() . '_' . $columna_para_armar;
                $la_query .= "(wp_postmeta.meta_key = '$meta_key' AND wp_postmeta.meta_value LIKE %s AND wp_posts.post_type = '$post_type')";
                if ($key < count($this->get_para_armar_columnas()) - 1) {
                    $la_query .= ' OR ';
                }
            }

            $filtrosXcreador = new CreadorFiltros($post_type, array(
                new Filtro(
                    'filtroXcreador',
                    "SELECT ID FROM wp_posts WHERE post_author IN (SELECT ID FROM wp_users WHERE user_login LIKE %s) AND post_type = '$post_type'",
                    'post__in',
                    'Filtrar por creador'
                ),
                new Filtro(
                    'filtrar_x' . $id_filtro,
                    "SELECT DISTINCT wp_postmeta.post_id FROM wp_postmeta INNER JOIN wp_posts ON wp_postmeta.post_id = wp_posts.ID WHERE ($la_query)",
                    'post__in',
                    'Filtrar por ' . implode(' o ', str_replace("_", " ", $this->get_para_armar_columnas()))
                )
            ));
        } else {
            $filtrosXcreador = new CreadorFiltros($post_type, array(
                new Filtro(
                    'filtroXcreador',
                    "SELECT ID FROM wp_posts WHERE post_author IN (SELECT ID FROM wp_users WHERE user_login LIKE %s) AND post_type = '$post_type'",
                    'post__in',
                    'Filtrar por creador'
                ),
                new Filtro(
                    'buscar_x_titulo',
                    "SELECT ID FROM wp_posts WHERE post_title LIKE %s AND post_type = '$post_type'",
                    'post__in',
                    'Buscar por título'
                )
            ));
        }
    }

    public function add_template_support()
    {
        $post_type = $this->get_plural();

        add_filter("single_template", function ($template) use ($post_type) {
            global $post;
            return $post->post_type === $post_type && !locate_template("single-{$post_type}.php")
                ? dirname(__FILE__) . '/../templetes/muestra_individual.php'
                : $template;
        });
    }

    public function generar_csv()
    {
        $post_types = get_post_types([], 'names');

        if (!isset($_GET['download_csv']) || $_GET['download_csv'] != 1)
            return;
        if (!isset($_GET['post_type']) || $_GET['post_type'] != $this->get_plural())
            return;

        $post_status = isset($_GET['post_status']) ? $_GET['post_status'] : 'all';
        $args = [
            'post_type' => $this->get_plural(),
            'post_status' => ($post_status === 'all') ? 'any' : $post_status,
            'posts_per_page' => -1,
        ];

        if ($post_status === 'trash')
            $args['post_status'] = 'trash';

        $posts = get_posts($args);
        $meta_box = $this->get_meta();
        $meta_fields = [];

        foreach ($meta_box->get_contenido() as $campo) {
            $meta_key = $this->get_prefijo() . '_' . $this->get_plural() . '_' . $campo->get_nombre_meta();
            $meta_fields[$meta_key] = $campo->get_etiqueta();
        }

        $meta_fields['creador'] = 'Creador';
        $meta_fields['fecha_de_carga'] = 'Fecha de carga';
        $meta_fields['modificador'] = 'Último modificador';
        $meta_fields['fecha_de_modificacion'] = 'Fecha de modificación';
        $meta_fields['estado_de_publicacion'] = 'Estado de publicación';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $this->get_plural() . '_' . $post_status . '.csv');
        $output = fopen('php://output', 'w');

        fputcsv($output, array_values($meta_fields));

        foreach ($posts as $post) {
            $row = [];
            foreach ($meta_fields as $key => $label) {
                $valor = get_post_meta($post->ID, $key, true);
                if (in_array($key, ['creador', 'fecha_de_carga', 'modificador', 'fecha_de_modificacion', 'estado_de_publicacion'])) {
                    switch ($key) {
                        case 'creador':
                            $row[] = get_the_author_meta('display_name', $post->post_author);
                            break;
                        case 'fecha_de_carga':
                            $row[] = get_the_date('', $post->ID);
                            break;
                        case 'modificador':
                            $last_id = get_post_meta($post->ID, '_edit_last', true);
                            $user = $last_id ? get_userdata($last_id)->display_name : 'N/A';
                            $row[] = $user;
                            break;
                        case 'fecha_de_modificacion':
                            $row[] = get_the_modified_date('', $post->ID);
                            break;
                        case 'estado_de_publicacion':
                            $estado = get_post_status($post->ID);
                            $row[] = $estado === 'publish' ? 'Publicado' : ucfirst($estado);
                            break;
                    }
                } elseif (is_numeric($valor) && get_post($valor) && get_post($valor)->post_type === 'attachment') {
                    $row[] = get_post(($valor))->guid;
                } elseif (in_array(str_replace($this->get_prefijo() . '_' . $this->get_plural() . '_', '', $key), $post_types)) {
                    $row[] = get_post(($valor))->post_title;
                } else {
                    $row[] = is_array($valor) ? implode('; ', $valor) : $valor;
                }
            }
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    public function agregar_boton_csv($views)
    {
        $url = add_query_arg([
            'post_type' => $this->get_plural(),
            'download_csv' => 1,
            'nonce' => wp_create_nonce('descargar_csv_' . $this->get_plural())
        ], admin_url('edit.php'));

        $views['download_csv'] = '<a href="' . esc_url($url) . '" class="button">Descargar CSV</a>';
        return $views;
    }
    public function mostrar_pantalla_de_listado()
    {
        ob_start();
        if (is_user_logged_in()) {
            obtener_navbar();
            $this->armar_tabla();
        } else {
            controlar_acceso_pagina_con_shortcode();
        }
        return ob_get_clean();
    }

    public function generador_pagina_post_type_listado()
    {
        $titulo = 'Listado de ' . $this->get_nombre_para_mostrar();
        $paginador = new Paginador($titulo, '[shortcode_listado_post_type_' . $this->get_plural() . ']');
        $objPage = $paginador->new_get_page_by_title($titulo);
        if (empty($objPage)) {
            $paginador->create_page($titulo, '[shortcode_listado_post_type_' . $this->get_plural() . ']');
        }
    }

    public function armar_tabla()
    {
        $paged = max(1, get_query_var('paged'));
        $post_type = $this->get_plural();
        $columnas_a_mostrar = $this->get_para_armar_columnas();
        $prefijo = $this->get_prefijo();

        $meta_box = $this->get_meta();
        $post_types = get_post_types([], 'names');

        $columnas = [];
        foreach ($columnas_a_mostrar as $columna) {
            foreach ($meta_box->get_contenido() as $campo) {
                if ($campo->get_nombre_meta() === $columna) {
                    $meta_key = $prefijo . '_' . $post_type . '_' . $columna;
                    $columnas[$meta_key] = $campo->get_etiqueta();
                    break;
                }
            }
        }

        $columnas['creador'] = 'Creador';
        $columnas['fecha_de_carga'] = 'Fecha de carga';
        $columnas['modificador'] = 'Modificador';
        $columnas['fecha_de_modificacion'] = 'Última modificación';

        $args = [
            'post_type' => $post_type,
            'post_status' => 'any',
            'posts_per_page' => 10,
            'paged' => $paged,
        ];

        $query = new WP_Query($args);

        $columnas_js = [];
        foreach ($columnas as $key => $label) {
            $columnas_js[] = [
                'key' => $key,
                'label' => $label,
            ];
        }

        $filas_js = [];
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $fila = [];

                foreach ($columnas as $columna_key => $label) {
                    if (in_array($columna_key, ['creador', 'fecha_de_carga', 'modificador', 'fecha_de_modificacion'])) {
                        switch ($columna_key) {
                            case 'creador':
                                $fila[$columna_key] = get_the_author();
                                break;
                            case 'fecha_de_carga':
                                $fila[$columna_key] = get_the_date();
                                break;
                            case 'modificador':
                                $last_id = get_post_meta($post_id, '_edit_last', true);
                                $user = $last_id ? get_userdata($last_id) : null;
                                $fila[$columna_key] = $user ? $user->display_name : 'N/A';
                                break;
                            case 'fecha_de_modificacion':
                                $fila[$columna_key] = get_the_modified_date();
                                break;
                        }
                    } else {
                        $valor = get_post_meta($post_id, $columna_key, true);
                        $campo_base = str_replace($prefijo . '_' . $post_type . '_', '', $columna_key);

                        if (is_numeric($valor) && $post_relacionado = get_post($valor)) {
                            if ($post_relacionado->post_type === 'attachment') {
                                $fila[$columna_key] = [
                                    'tipo' => 'archivo',
                                    'url' => wp_get_attachment_url($valor),
                                    'texto' => 'Archivo',
                                ];
                            } elseif (in_array($campo_base, $post_types)) {
                                $fila[$columna_key] = [
                                    'tipo' => 'post',
                                    'texto' => get_the_title($valor),
                                ];
                            }
                        } else {
                            $fila[$columna_key] = is_array($valor) ? implode(', ', $valor) : $valor;
                        }
                    }
                }
                $fila['acciones'] = get_permalink($post_id);
                $filas_js[] = $fila;
            }
            wp_reset_postdata();
        }

        $nonce = wp_create_nonce('descargar_csv_frontend_' . $this->get_plural());
        $url_csv = admin_url('admin-ajax.php') . '?action=generar_csv_frontend&download_csv=1&post_type=' . $this->get_plural() . '&nonce=' . $nonce;

        ?>
        <!-- Después de la paginación -->
        <div class="tablenav bottom" style="margin-top: 10px;">
            <a href="<?php echo esc_url($url_csv); ?>" class="button">Descargar CSV</a>
        </div>
        <div class="table-container"></div>

        <script>

            window.tablaDatos = {
                columnas: <?php echo wp_json_encode($columnas_js); ?>,
                filas: <?php echo wp_json_encode($filas_js); ?>,
                sinRegistros: 'No se encontraron registros'
            };

            document.addEventListener('DOMContentLoaded', function () {
                const container = document.querySelector('.table-container');
                if (!window.tablaDatos) return;

                const { columnas, filas, sinRegistros } = window.tablaDatos;
                let currentSort = { key: null, direction: 'none' };
                let filteredFilas = [...filas];

                // Agregar elementos de búsqueda
                const searchContainer = document.createElement('div');
                searchContainer.style.marginBottom = '1em';
                searchContainer.style.display = 'flex';
                searchContainer.style.alignItems = 'center';

                const searchInput = document.createElement('input');
                searchInput.type = 'text';
                searchInput.placeholder = 'Buscar en todos los campos...';
                searchInput.style.width = '300px';
                searchInput.style.padding = '8px';
                searchInput.style.borderRadius = '4px';
                searchInput.style.border = '1px solid #ddd';

                const searchButton = document.createElement('button');
                searchButton.textContent = 'Buscar';
                searchButton.style.padding = '8px 20px';
                searchButton.style.marginLeft = '10px';
                searchButton.style.background = '#0073aa';
                searchButton.style.color = 'white';
                searchButton.style.border = 'none';
                searchButton.style.borderRadius = '4px';
                searchButton.style.cursor = 'pointer';

                searchContainer.appendChild(searchInput);
                searchContainer.appendChild(searchButton);
                container.parentNode.insertBefore(searchContainer, container);

                // Función de búsqueda
                const handleSearch = () => {
                    const searchTerm = searchInput.value.toLowerCase().trim();

                    filteredFilas = filas.filter(fila => {
                        return Object.entries(fila).some(([key, value]) => {
                            if (key === 'acciones') return false;

                            let searchValue;
                            if (typeof value === 'object' && value !== null) {
                                searchValue = value.texto?.toLowerCase() || '';
                            } else {
                                searchValue = String(value).toLowerCase();
                            }

                            return searchValue.includes(searchTerm);
                        });
                    });

                    if (currentSort.key) {
                        filteredFilas.sort((a, b) => {
                            const result = compararValores(a, b, currentSort.key);
                            return currentSort.direction === 'asc' ? result : -result;
                        });
                    }

                    actualizarTabla(filteredFilas);
                    actualizarPaginacion();
                };

                // Función para comparar valores
                const compararValores = (a, b, key) => {
                    const valorA = obtenerValorComparable(a[key]);
                    const valorB = obtenerValorComparable(b[key]);

                    if (typeof valorA === 'string' && typeof valorB === 'string') {
                        return valorA.localeCompare(valorB);
                    }
                    return valorA > valorB ? 1 : -1;
                };

                // Obtener valor comparable
                const obtenerValorComparable = (valor) => {
                    if (typeof valor === 'object' && valor !== null) {
                        return valor.texto.toLowerCase();
                    }
                    if (typeof valor === 'string' && !isNaN(valor)) {
                        return parseFloat(valor);
                    }
                    if (!isNaN(Date.parse(valor))) {
                        return new Date(valor);
                    }
                    return valor.toString().toLowerCase();
                };

                // Actualizar tabla
                const actualizarTabla = (data) => {
                    const tbody = document.querySelector('tbody');
                    tbody.innerHTML = '';

                    data.forEach(fila => {
                        const tr = document.createElement('tr');
                        columnas.forEach(columna => {
                            const td = document.createElement('td');
                            const valor = fila[columna.key];

                            if (typeof valor === 'object' && valor !== null) {
                                if (valor.tipo === 'archivo') {
                                    const enlace = document.createElement('a');
                                    enlace.href = valor.url;
                                    enlace.textContent = valor.texto;
                                    td.appendChild(enlace);
                                } else if (valor.tipo === 'post') {
                                    td.textContent = valor.texto;
                                }
                            } else {
                                td.textContent = valor;
                            }
                            tr.appendChild(td);
                        });

                        const tdAcciones = document.createElement('td');
                        const botonVer = document.createElement('a');
                        botonVer.className = 'button button-primary';
                        botonVer.href = fila.acciones;
                        botonVer.textContent = 'Ver';
                        tdAcciones.appendChild(botonVer);
                        tr.appendChild(tdAcciones);

                        tbody.appendChild(tr);
                    });

                    if (data.length === 0) {
                        const tr = document.createElement('tr');
                        const td = document.createElement('td');
                        td.colSpan = columnas.length + 1;
                        td.textContent = sinRegistros;
                        tr.appendChild(td);
                        tbody.appendChild(tr);
                    }
                };

                // Ordenamiento
                const ordenarTabla = (key) => {
                    let direction = 'asc';

                    if (currentSort.key === key) {
                        direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
                    }

                    currentSort = { key, direction };

                    filteredFilas.sort((a, b) => {
                        const result = compararValores(a, b, key);
                        return direction === 'asc' ? result : -result;
                    });

                    actualizarTabla(filteredFilas);
                    actualizarIndicadoresSort(key, direction);
                };

                // Actualizar indicadores de orden
                const actualizarIndicadoresSort = (key, direction) => {
                    document.querySelectorAll('th').forEach(th => {
                        th.innerHTML = th.innerHTML.replace(/ ↑| ↓/, '');
                        if (th.id === key) {
                            th.innerHTML += direction === 'asc' ? ' ↑' : ' ↓';
                        }
                    });
                };

                // Eventos
                searchButton.addEventListener('click', handleSearch);
                searchInput.addEventListener('keyup', (e) => {
                    if (e.key === 'Enter') handleSearch();
                });

                // Crear tabla
                const tabla = document.createElement('table');
                tabla.className = 'wp-list-table widefat fixed striped';

                // Crear thead
                const thead = document.createElement('thead');
                const trHead = document.createElement('tr');
                columnas.forEach(columna => {
                    const th = document.createElement('th');
                    th.id = columna.key;
                    th.textContent = columna.label;
                    th.style.cursor = 'pointer';
                    th.addEventListener('click', () => ordenarTabla(columna.key));
                    trHead.appendChild(th);
                });

                const thAcciones = document.createElement('th');
                thAcciones.textContent = 'Acciones';
                trHead.appendChild(thAcciones);
                thead.appendChild(trHead);
                tabla.appendChild(thead);

                // Crear tbody
                const tbody = document.createElement('tbody');
                tabla.appendChild(tbody);
                container.appendChild(tabla);

                // Render inicial
                actualizarTabla(filteredFilas);
            });
        </script>

        <div class="tablenav bottom">
            <?php echo paginate_links([
                'total' => $query->max_num_pages,
                'current' => $paged,
                'prev_text' => __('&laquo; Anterior'),
                'next_text' => __('Siguiente &raquo;'),
            ]); ?>
        </div>
        <?php
    }

    public function generar_csv_frontend()
    {
        // Verificar parámetros básicos
        if (!isset($_GET['download_csv']) || $_GET['download_csv'] != 1)
            return;
        if (!isset($_GET['post_type']) || $_GET['post_type'] != $this->get_plural())
            return;

        // Configurar argumentos de consulta
        $args = [
            'post_type' => $this->get_plural(),
            'post_status' => 'any', // Incluir todos los estados excepto trash
            'posts_per_page' => -1,
            'fields' => 'ids' // Optimizar memoria
        ];

        // Obtener posts
        $posts = get_posts($args);

        // Obtener estructura de metadatos
        $meta_box = $this->get_meta();
        $meta_fields = [];
        $post_types = get_post_types([], 'names');

        // Construir encabezados
        foreach ($meta_box->get_contenido() as $campo) {
            $meta_key = $this->get_prefijo() . '_' . $this->get_plural() . '_' . $campo->get_nombre_meta();
            $meta_fields[$meta_key] = $campo->get_etiqueta();
        }

        // Campos adicionales
        $meta_fields['creador'] = 'Creador';
        $meta_fields['fecha_de_carga'] = 'Fecha de carga';
        $meta_fields['modificador'] = 'Último modificador';
        $meta_fields['fecha_de_modificacion'] = 'Fecha de modificación';
        $meta_fields['estado_de_publicacion'] = 'Estado de publicación';

        // Configurar headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $this->get_plural() . '_frontend_export_' . date('Y-m-d') . '.csv');
        $output = fopen('php://output', 'w');

        // Escribir encabezados
        fputcsv($output, array_values($meta_fields));

        // Procesar cada post
        foreach ($posts as $post_id) {
            $row = [];
            foreach ($meta_fields as $key => $label) {
                if (in_array($key, ['creador', 'fecha_de_carga', 'modificador', 'fecha_de_modificacion', 'estado_de_publicacion'])) {
                    switch ($key) {
                        case 'creador':
                            $row[] = get_the_author_meta('display_name', get_post_field('post_author', $post_id));
                            break;
                        case 'fecha_de_carga':
                            $row[] = get_the_date('', $post_id);
                            break;
                        case 'modificador':
                            $last_id = get_post_meta($post_id, '_edit_last', true);
                            $user = $last_id ? get_userdata($last_id)->display_name : 'N/A';
                            $row[] = $user;
                            break;
                        case 'fecha_de_modificacion':
                            $row[] = get_the_modified_date('', $post_id);
                            break;
                        case 'estado_de_publicacion':
                            $estado = get_post_status($post_id);
                            $row[] = ($estado === 'publish') ? 'Publicado' : ucfirst($estado);
                            break;
                    }
                } else {
                    $valor = get_post_meta($post_id, $key, true);
                    $campo_base = str_replace($this->get_prefijo() . '_' . $this->get_plural() . '_', '', $key);

                    if (is_numeric($valor)) {
                        $post_relacionado = get_post($valor);
                        if ($post_relacionado && $post_relacionado->post_type === 'attachment') {
                            $row[] = wp_get_attachment_url($valor);
                        } elseif (in_array($campo_base, $post_types)) {
                            $row[] = get_the_title($valor);
                        } else {
                            $row[] = $valor;
                        }
                    } else {
                        $row[] = is_array($valor) ? implode('; ', $valor) : $valor;
                    }
                }
            }
            fputcsv($output, $row);
        }

        fclose($output);
        exit; // Importante: detener ejecución
    }
}