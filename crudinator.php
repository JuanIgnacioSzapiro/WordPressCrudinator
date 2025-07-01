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

// Incluir archivos
require_once dirname(__FILE__) . '/activar.php';
require_once dirname(__FILE__) . '/desactivar.php';
require_once dirname(__FILE__) . '/desinstalar.php';
require_once dirname(__FILE__) . '/enque.php';
require_once dirname(__FILE__) . '/admin/areas/ejemplo_de_uso/ejemplo_de_uso.php';
require_once dirname(__FILE__) . '/admin/areas/ejemplo_de_uso/activaciones/activar_tipos_de_post_ejemplo_de_uso.php';
require_once dirname(__FILE__) . '/admin/areas/ejemplo_de_uso/activaciones/activar_roles_ejemplo_de_uso.php';

if (!class_exists('Crudinator')) {
    /**
     * Crudinator es la clase principal de este plugin
     */
    class Crudinator
    {
        protected $ejemplo_de_uso;
        public function __construct()
        {
            $this->set_ejemplo_de_uso(array_merge(obtener_ejemplo_de_uso()));

            // Registrar hooks de estado
            register_activation_hook(__FILE__, 'activar_crudinator');
            register_deactivation_hook(__FILE__, 'desactivar_crudinator');
            register_uninstall_hook(__FILE__, 'desinstalar_crudinator');

            add_action('init', array($this, 'cargar_tipos_de_post')); // Carga de tipos de post
            add_action('init', array($this, 'cargar_roles')); // Carga de roles
        }
        public function get_ejemplo_de_uso()
        {
            return $this->ejemplo_de_uso;
        }
        public function set_ejemplo_de_uso($valor)
        {
            $this->ejemplo_de_uso = $valor;
        }
        /**
         * Creación y registro de los diferentes tipos de post
         * @return void
         */
        function cargar_tipos_de_post()
        {
            activar_tipos_de_post_ejemplo_de_uso($this->get_ejemplo_de_uso());
        }
        /**
         * Creación de roles y asignación de habilidades a roles preexistentes y nuevos
         * @return void
         */
        function cargar_roles()
        {
            activar_roles_ejemplo_de_uso($this->get_ejemplo_de_uso()); // Activación de roles
        }
    }

    new Crudinator();
}