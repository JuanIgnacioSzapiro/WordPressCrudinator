<?php
require_once dirname(__FILE__) . '/../../../creador_de_post_types/post_type_personalizado.php';
require_once dirname(__FILE__) . '/../../../creador_de_post_types/cajas_de_metadata/cajas_de_metadata.php';
require_once dirname(__FILE__) . '/../../../creador_de_post_types/cajas_de_metadata/cajas/caja_de_texto.php';
require_once dirname(__FILE__) . '/../../../creador_de_post_types/cajas_de_metadata/cajas/caja_de_area_de_texto.php';
require_once dirname(__FILE__) . '/../../../creador_de_post_types/cajas_de_metadata/cajas/checkbox.php';


/**
 * Creación y registro de todos los tipos de post
 * @return void
 */
function activar_post_types_ejemplo_de_uso()
{
    $cursos = new PostTypePersonalizado(
        $GLOBALS['prefijo_ejemplo_de_uso'],
        'MiTest',
        'Mi Test',
        'Mis Test',
        false,
        'dashicons-welcome-learn-more',
        new CajasDeMetadata(
            [
                new CajaDeTexto(
                    'textoBasico',
                    'Etiqueta en texto básico',
                    'Texto de ejemplificación en texto básico',
                    'Descripción de texto básico',
                    false,
                    false
                ),
                $textoConExpresionRegular = new CajaDeTexto(
                    'textoConExpresionRegular',
                    'Etiqueta en texto con expresión regular',
                    'Texto de ejemplificación en texto con expresión regular',
                    'Descripción de texto con expresión regular',
                    false,
                ),
                new CajaDeTexto(
                    'textoClonable',
                    'Etiqueta en texto clonable',
                    'Texto de ejemplificación en texto clonable',
                    'Descripción de texto clonable',
                    true,
                ),
                new CajaDeAreaDeTexto(
                    'areaDeTextoBasico',
                    'Etiqueta de área de texto',
                    'Texto ejemplificación de área de texto',
                    'Descripción de área de texto',
                    false
                ),
                new CajaDeAreaDeTexto(
                    'areaDeTextoClonable',
                    'Etiqueta de área de texto',
                    'Texto ejemplificación de área de texto',
                    'Descripción de área de texto',
                    true
                ),
                new Checkbox(
                    'checkboxBasico',
                    'Etiqueta de checkbox',
                    'Descripción de checkbox',
                    ['Opción 1', 'Opción 2', 'Opción 3'],
                ),
            ]
        ),
        []
    );

    $textoConExpresionRegular->set_formato_de_texto(
        '/[A-z]/',
        'Se aceptan únicamente letras'
    );

    $cursos->registrar_post_type();
}