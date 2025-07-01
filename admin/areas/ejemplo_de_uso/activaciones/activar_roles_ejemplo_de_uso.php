<?php
/**
 * Creación de roles y asignación de habilidades a roles preexistentes y nuevos
 *  * @param array $post_types
 */
function activar_roles_ejemplo_de_uso($post_types)
{
    // Array con todos los roles como objeto tipo TipoDeRol
    $roles = array();

    // Creación de los roles
    foreach ($roles as $objeto) {
        $objeto->agregar_rol();
    }

    // Asignación de habilidades
    activar_habilidades_ejemplo_de_uso($roles, $post_types);
}
/**
 * Asignación de habilidades a roles preexistentes y nuevos
 * @param array $roles
 * @param array $post_types
 */
function activar_habilidades_ejemplo_de_uso($roles, $post_types)
{
    // Se agrega el rol de administrador al final
    array_push($roles, 'administrator');

    // Asignación según rol de habilidades
    foreach ($roles as $rol) {
        $rol_obtenido = get_role(is_a($rol, 'TipoDeRol') ? $rol->get_id() : $rol);
        switch ($rol_obtenido->name) {
            case 'administrator': { // Al administrador se le asigna la totalidad de habilidades existentes
                foreach ($post_types as $individual) {
                    foreach ($individual->get_habilidades() as $valor) {
                        $rol_obtenido->add_cap($valor);
                    }
                }
                break;
            }
        }
    }
}