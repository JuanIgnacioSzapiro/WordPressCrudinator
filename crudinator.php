<?php
/*
 * Plugin Name:       Crudinator
 * Plugin URI:        ...
 * Description:       Crea CRUDs generales
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Juan Ignacio Szapiro
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       crudinator
 */

defined('ABSPATH') || exit; // Debe ir AL INICIO para seguridad

// Registrar hooks de estado
register_activation_hook(__FILE__, 'activar_crudinator');
register_deactivation_hook(__FILE__, 'desactivar_crudinator');
register_uninstall_hook(__FILE__, 'desinstalar_crudinator');

// Incluir archivos
require_once plugin_dir_path(__FILE__) . 'activar.php';
require_once plugin_dir_path(__FILE__) . 'desactivar.php';
require_once plugin_dir_path(__FILE__) . 'desinstalar.php';
require_once plugin_dir_path(__FILE__) . 'enque.php';

require_once dirname(__FILE__) . '/admin/activaciones/constantes.php';

require_once plugin_dir_path(__FILE__) . 'admin/funciones.php';

require_once plugin_dir_path(__FILE__) . 'admin/areas/secretaria_de_extension/activaciones/activar_post_types_secr_ext.php';
require_once plugin_dir_path(__FILE__) . 'admin/areas/secretaria_de_extension/activaciones/activar_roles_secr_ext.php';

if (!class_exists('Crudinator')) {
    /**
     * Crudinator es la clase principal de este plugin
     */
    class Crudinator
    {
        public function __construct()
        {
            cargar_configuracion_desde_csv();

            // Métodos públicos para hooks
            add_action('wp_enqueue_scripts', [$this, 'cargar_recursos']); // Carga de js y css
            add_action('init', [$this, 'cargar_tipos_de_post']); // Carga de tipos de post
            add_action('init', [$this, 'cargar_roles']); // Carga de roles
        }
        /**
         * Carga de recursos de estilos (css) y scripts (js) PROPIOS DE ESTE PLUGIN
         * @return void
         */
        public function cargar_recursos()
        {
            crudinator_cargar_estilos(); // Carga de archivos css
            crudinator_cargar_scripts(); // Carga de archivos js
        }

        /**
         * Creación y registro de los diferentes tipos de post
         * @return void
         */
        public function cargar_tipos_de_post()
        {
            activar_post_types_secr_ext(); // Activación de tipos de post
        }

        /**
         * Creación de roles y asignación de habilidades a roles preexistentes y nuevos
         * @return void
         */
        public function cargar_roles()
        {
            activar_roles_secr_ext(); // Activación de roles
        }
    }

    new Crudinator();
}