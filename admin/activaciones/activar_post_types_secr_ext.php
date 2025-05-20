<?php
require_once dirname(__FILE__) . '/../creador_de_post_types/post_type_personalizado.php';
require_once dirname(__FILE__) . '/../funciones.php';

/**
 * Creación y registro de todos los tipos de post
 * @return void
 */
function activar_post_types_secr_ext()
{
    $cursos = new PostTypePersonalizado(
        $GLOBALS['prefijo_secr_ext'],
        'curso',
        'curso',
        'cursos',
        false,
        'dashicons-welcome-learn-more',
        [],
        []
    );

    $cursos->registrar_post_type();
}