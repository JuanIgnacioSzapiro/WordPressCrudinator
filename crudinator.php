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

// Carga de constantes super globales
require_once dirname(__FILE__) . '/admin/activaciones/globales.php';
cargar_superglobales();

if (!class_exists('Crudinator')) {
    /**
     * Crudinator es la clase principal de este plugin
     */
    class Crudinator
    {
        protected $ejemplo_de_uso;
        public function __construct()
        {
            $this->set_ejemplo_de_uso(obtener_ejemplo_de_uso());

            // Registrar hooks de estado
            register_activation_hook(__FILE__, array($this, 'llamar_activacion'));
            register_deactivation_hook(__FILE__, array($this, 'llamar_desactivacion'));
            register_uninstall_hook(__FILE__, 'Crudinator::llamar_desinstalacion'); // La desactivación siempre debe ser una función estática

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
        public function cargar_tipos_de_post()
        {
            registrar_tipos_de_post_ejemplo_de_uso($this->get_ejemplo_de_uso());
        }
        /**
         * Creación de roles y asignación de habilidades a roles preexistentes y nuevos
         * @return void
         */
        public function cargar_roles()
        {
            activar_roles_ejemplo_de_uso($this->get_ejemplo_de_uso()); // Activación de roles
        }
        /**
         * Activación del plugin
         * @return void
         */
        public function llamar_activacion()
        {
            activar_crudinator(array_merge(array($GLOBALS['ejemplo_de_uso'] => $this->get_ejemplo_de_uso())));
        }
        /**
         * Desactivación del plugin
         * @return void
         */
        public function llamar_desactivacion()
        {
            desactivar_crudinator(array_merge(array($GLOBALS['ejemplo_de_uso'] => $this->get_ejemplo_de_uso())));
        }
        /**
         * Desinstalación del plugin
         * @return void
         */
        public static function llamar_desinstalacion()
        {
            desinstalar_crudinator();
        }
    }

    new Crudinator();
}