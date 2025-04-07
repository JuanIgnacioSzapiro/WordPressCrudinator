<?php
function obtener_navbar()
{
    $contenido_nav_bar = array(
        'fechas' => 'Fechas y calendario',
        'links' => 'Link de inscripciones',
        'materias' => 'Materias',
        'planes_y_programas' => 'Planes y programas',
        'tipos_de_carrera' => 'Tipo de carreras',
        'carreras' => 'Carreras',
        'doc' => 'Documentaciones',
        'doc_total' => 'Documentación requerida',
        'pre_form_ingreso' => 'Formulario previo al formulario pre ingreso',
        'form_ingreso' => 'Formulario pre ingreso',
    );
    $nonce = wp_create_nonce('wp_rest'); // Generar nonce para seguridad

    ?>
    <div class="mi_navbar">
        <a
            href="<?php echo obtener_resultado_query('SELECT guid FROM wp_posts where wp_posts.post_title like "Menu de inicio" LIMIT 1;')[0]->guid ?>">
            <div>
                Menu de inicio
            </div>
        </a>
        <?php
        if ($contenido_nav_bar) {
            foreach ($contenido_nav_bar as $key => $individual) {
                if (in_array('read_' . $key, array_keys(wp_get_current_user()->allcaps))) {
                    ?>
                    <a
                        href="<?php echo obtener_resultado_query('SELECT guid FROM wp_posts where wp_posts.post_title like "Listado de ' . $individual . '" LIMIT 1;')[0]->guid ?>">
                        <div id="<?php echo $key ?>">
                            <?php echo $individual ?>
                        </div>
                    </a>
                    <?php
                }
            }
        }
        ?>
        <div id="cerrar_sesion">Cerrar sesión</div>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            $('#cerrar_sesion').click(function (e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '<?php echo esc_url(rest_url('cerrar_sesion/v1/submit')); ?>',
                    headers: {
                        'X-WP-Nonce': '<?php echo $nonce; ?>' // Incluir nonce en headers
                    },
                    success: function (response) {
                        window.location.href = '<?php echo obtener_resultado_query('SELECT guid FROM wp_posts where wp_posts.post_title like "Inicio de sesión" LIMIT 1;')[0]->guid ?>';
                    },
                    error: function (xhr, status, error) {
                        console.error('Error al cerrar sesión:', error);
                    }
                });
            });
        });
    </script>
    <?php
}

