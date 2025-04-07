<?php

function obtener_campos_inicio_sesion()
{
    return array(
        new CampoTexto(
            'nombre_o_correo',
            'Nombre de usuario o correo electrónico',
            '',
            '',
        ),
        new CampoClave(
            'clave',
            'Contraseña',
            '',
            '',
        ),
    );
}

add_shortcode('inicio_sesion_manejo_preinscriptos', 'inicio_sesion_shortcode');

function inicio_sesion_shortcode()
{
    ob_start();
    wp_nonce_field('wp_rest');
    ?>
    <div id="ocultador"></div>
    <div class="formulario">
        <form id="inicio_sesion_shortcode">
            <h2>Iniciar sesión</h2>

            <?php
            recorrer_array(obtener_campos_inicio_sesion(), obtener_prefijo(''));
            ?>
            <button type="submit" class="button">Iniciar sesión</button>
        </form>
        <div id="mensaje"></div>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            $('#inicio_sesion_shortcode').submit(function (e) {
                e.preventDefault();

                var ocultador = document.getElementById('ocultador');

                var form = $(this);
                var nonce = $('#_wpnonce').val(); // Obtener el valor del nonce

                var mensaje_error = document.getElementById('mensaje');

                ocultador.classList.add("cargando");

                if (mensaje_error.innerHTML !== '') {
                    mensaje_error.innerHTML = '';
                }
                mensaje_error.classList.remove("mensaje-de-error");
                mensaje_error.classList.remove("mensaje-de-success");

                $.ajax({
                    type: 'POST',
                    url: '<?php echo esc_url(rest_url('inicio_sesion_shortcode/v1/submit')); ?>',
                    data: form.serialize(),
                    dataType: 'json',
                    xhrFields: {
                        withCredentials: true
                    },
                    headers: {
                        'X-WP-Nonce': nonce // Enviar el nonce en el header
                    },
                    success: function (response) {
                        ocultador.classList.remove("cargando");
                        if (response.success) {
                            mensaje_error.classList.add("mensaje-de-success");
                            response.message.forEach(element => {
                                mensaje_error.innerHTML += '<p>' + element + '</p>';
                            });
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                        } else {
                            mensaje_error.classList.add("mensaje-de-error");
                            response.message.forEach(element => {
                                mensaje_error.innerHTML += '<p>' + element + '</p>';
                            });
                        }
                    },
                    error: function (xhr) {
                        ocultador.classList.remove("cargando");
                        mensaje_error.innerHTML = 'Errores: ' + xhr.responseText;
                    }
                });
            });
        });
    </script>
    <?php
    return ob_get_clean();
}