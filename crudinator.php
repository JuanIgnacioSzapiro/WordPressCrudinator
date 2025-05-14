<?php
/*
 * Plugin Name:       Crudinator
 * Plugin URI:        ...
 * Description:       Crea cruds generales
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Juan Ignacio Szapiro
 * License:           GPL v2 or later
 * License URI:       A link to the full text of the license. Example: https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       crudinator
 */

if (!defined('ABSPATH')) { // si la busqueda de la página web no es del path absoluto que le da por default wordpress...
    die('Acceso no permitido');
} else {
    if (!class_exists('Crudinator')) {
        require_once plugin_dir_path(__FILE__) . '/activar.php';
        require_once plugin_dir_path(__FILE__) . '/desactivar.php';
        require_once plugin_dir_path(__FILE__) . '/desinstalar.php';

        register_activation_hook(__FILE__, 'activar_crudinator'); // Lógica de activación
        register_deactivation_hook(__FILE__, 'desactivar_crudinator'); // Lógica de desactivación
        register_uninstall_hook(__FILE__, 'desinstalar_crudinator'); // Lógica de desinstalación

        require_once plugin_dir_path(__FILE__) . '/enque.php';

        class Crudinator
        {
            public function __construct()
            {
                // Lógica del plugin (ej: shortcodes, AJAX, etc.)
                add_action('wp_enqueue_scripts', [$this, 'cargar_recursos']);
            }
            private function cargar_recursos()
            {
                // Lógica movida a enque.php pero accesible desde aquí
                crudinator_cargar_estilos();
                crudinator_cargar_scripts();
            }
        }
        new Crudinator();
    }
}
