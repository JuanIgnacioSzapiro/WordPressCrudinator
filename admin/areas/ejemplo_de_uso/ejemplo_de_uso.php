<?php
require_once dirname(__FILE__) . '/../../creador_de_post_types/post_type_personalizado.php';
require_once dirname(__FILE__) . '/../../creador_de_post_types/cajas_de_metadata/cajas_de_metadata.php';
require_once dirname(__FILE__) . '/../../creador_de_post_types/cajas_de_metadata/cajas/caja_de_texto.php';
require_once dirname(__FILE__) . '/../../creador_de_post_types/cajas_de_metadata/cajas/caja_de_area_de_texto.php';
require_once dirname(__FILE__) . '/../../creador_de_post_types/cajas_de_metadata/cajas/checkbox.php';
require_once dirname(__FILE__) . '/../../creador_de_post_types/cajas_de_metadata/cajas/caja_drop_down_predefinido.php';
require_once dirname(__FILE__) . '/../../creador_de_post_types/cajas_de_metadata/cajas/caja_de_fecha.php';
require_once dirname(__FILE__) . '/../../creador_de_post_types/cajas_de_metadata/cajas/cajas_de_archivos.php';

/**
 * Creación y registro de todos los tipos de post
 * @return array
 */
function obtener_ejemplo_de_uso()
{
    $cursos = new PostTypePersonalizado(
        'mi_test',
        'Mi Test',
        'Mis Test',
        false,
        'dashicons-welcome-learn-more',
        new CajasDeMetadata(
            [
                $texto_basico = new CajaDeTexto(
                    'texto_basico',
                    'Etiqueta en texto básico',
                    'Texto de ejemplificación en texto básico',
                    'Descripción de texto básico',
                    false,
                    false,
                    false
                ),
                new CajaDeTexto(
                    'clave_basica',
                    'Etiqueta en clave básica',
                    'Texto de ejemplificación en clave básica',
                    'Descripción de clave básica',
                    false,
                    true,
                    true
                ),
                $textoConExpresionRegular = new CajaDeTexto(
                    'texto_con_expresion_regular',
                    'Etiqueta en texto con expresión regular',
                    'Texto de ejemplificación en texto con expresión regular',
                    'Descripción de texto con expresión regular',
                    false,
                ),
                new CajaDeTexto(
                    'texto_clonable',
                    'Etiqueta en texto clonable',
                    'Texto de ejemplificación en texto clonable',
                    'Descripción de texto clonable',
                    true,
                ),
                new CajaDeAreaDeTexto(
                    'area_de_texto_basico',
                    'Etiqueta de área de texto',
                    'Texto ejemplificación de área de texto',
                    'Descripción de área de texto',
                    false
                ),
                new CajaDeAreaDeTexto(
                    'area_de_texto_clonable',
                    'Etiqueta de área de texto',
                    'Texto ejemplificación de área de texto',
                    'Descripción de área de texto',
                    true
                ),
                new Checkbox(
                    'checkbox_basico',
                    'Etiqueta de checkbox',
                    'Descripción de checkbox',
                    ['Opción 1', 'Opción 2', 'Opción 3'],
                ),
                new CajaDropDown(
                    'dropdown_predeterminado_basico',
                    'Etiqueta de dropdown predeterminado',
                    'Descripción de dropdown predeterminado',
                    ['Opción 1', 'Opción 2', 'Opción 3'],
                    false,
                    false,
                ),
                new CajaDeFecha(
                    'caja_de_fecha_basica',
                    'Etiqueta de caja de fecha',
                    'Descripción de caja de fecha',
                    false
                ),
                new CajaDeArchivos(
                    'caja_de_archivos_basica',
                    'Etiqueta de caja de archivos',
                    'Descripción de caja de archivos',
                    ["application/pdf", 'image/jpeg', 'image/png']
                )
            ]
        ),
        array()
    );
    $textoConExpresionRegular->set_formato_de_texto(
        '/[A-z]/',
        'Se aceptan únicamente letras'
    );
    
    return array($cursos);
}