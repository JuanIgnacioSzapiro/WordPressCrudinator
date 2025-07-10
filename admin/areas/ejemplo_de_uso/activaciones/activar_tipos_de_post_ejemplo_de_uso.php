<?php
/**
 * Registro de tipos de post
 * @param array $tipos_de_post
 * @return void
 */
function registrar_tipos_de_post_ejemplo_de_uso($tipos_de_post)
{
    foreach ($tipos_de_post as $individual) {
        $individual->registrar_post_type();
    }
}