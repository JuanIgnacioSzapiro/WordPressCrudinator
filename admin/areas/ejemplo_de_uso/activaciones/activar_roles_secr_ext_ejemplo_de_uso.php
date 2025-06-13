<?php
/**
 * Creación de roles y asignación de habilidades a roles preexistentes y nuevos
 */
function activar_roles_ejemplo_de_uso()
{
    // Array con todos los roles como objeto tipo TipoDeRol
    $roles = array();

    // Creación de los roles
    foreach ($roles as $objeto) {
        $objeto->agregar_rol();
    }

    // Asignación de habilidades
    activar_habilidades_ejemplo_de_uso($roles);
}

/**
 * Asignación de habilidades a roles preexistentes y nuevos
 * @param array $roles
 */
function activar_habilidades_ejemplo_de_uso($roles)
{
    // Se agrega el rol de administrador al final
    array_push($roles, 'administrator');

    // Totalida de habilidades, principalmente usada para administradores
    $total = array(
        new CaracteristicasMinimasPostType('MiTest'),
    );

    // Asignación según rol de habilidades
    foreach ($roles as $rol) {
        $rol_obtenido = get_role(is_a($rol, 'TipoDeRol') ? $rol->get_id() : $rol);
        switch ($rol_obtenido->name) {
            case 'administrator': { // Al administrador se le asigna la totalidad de habilidades existentes
                foreach ($total as $individual) {
                    foreach ($individual->get_habilidades() as $valor) {
                        $rol_obtenido->add_cap($valor);
                    }
                }
                break;
            }
        }
    }
}