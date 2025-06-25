<?php
require_once dirname(__FILE__) . '/../caracteristicas_minimas_caja_de_metadata.php';

class CajaDropDown extends CaracteristicasMinimasCajaDeMetadata
{
    protected $opciones;
    /**
     * Constructor de CajaDropDown
     * @param string $id_caja_metadata
     * @param string $etiqueta_caja_de_metadata
     * @param string $descripcion_caja_de_metadata
     * @param array $opciones
     * @param bool $clonable
     * @param bool $opcional
     */
    public function __construct(
        $id_caja_metadata,
        $etiqueta_caja_de_metadata,
        $descripcion_caja_de_metadata,
        $opciones,
        $clonable,
        $opcional = true
    ) {
        $this->set_id_caja_metadata($id_caja_metadata);
        $this->set_etiqueta_caja_de_metadata($etiqueta_caja_de_metadata);
        $this->set_descripcion_caja_de_metadata($descripcion_caja_de_metadata);
        $this->set_opciones($opciones);
        $this->set_clonable($clonable);
        $this->set_opcional($opcional);
    }
    public function get_opciones()
    {
        return $this->opciones;
    }
    public function set_opciones($valor)
    {
        $this->opciones = $valor;
    }
    public function generar_fragmento_html($post)
    {
        $selected_value = get_post_meta($post->ID, $this->get_id_caja_metadata(), true);
        ?>
        <div>
            <?php
            if (!$this->get_opcional()) {
                $this->generar_html_label_no_opcional();
            } else {
                $this->generar_html_label_opcional();
            }
            $this->generar_html_descripcion();
            ?>
            <select name="<?php echo esc_attr($this->get_id_caja_metadata()); ?>"
                id="<?php echo esc_attr($this->get_id_caja_metadata()); ?>">
                <option value="">Seleccionar...</option>
                <?php foreach ($this->get_opciones() as $opcion): ?>
                    <option value="<?php echo esc_attr($opcion); ?>" <?php selected($selected_value, $opcion); ?>>
                        <?php echo esc_html($opcion); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
}