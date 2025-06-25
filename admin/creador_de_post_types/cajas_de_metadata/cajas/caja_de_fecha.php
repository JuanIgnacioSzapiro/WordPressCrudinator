<?php
require_once dirname(__FILE__) . '/../caracteristicas_minimas_caja_de_metadata.php';

class CajaDeFecha extends CaracteristicasMinimasCajaDeMetadata
{
    /**
     * Constructor de CajaDeFecha
     * @param string $id_caja_metadata
     * @param string $etiqueta_caja_de_metadata
     * @param string $descripcion_caja_de_metadata
     * @param bool $opcional
     */
    public function __construct(
        $id_caja_metadata,
        $etiqueta_caja_de_metadata,
        $descripcion_caja_de_metadata,
        $opcional = true
    ) {
        $this->set_id_caja_metadata($id_caja_metadata);
        $this->set_etiqueta_caja_de_metadata($etiqueta_caja_de_metadata);
        $this->set_descripcion_caja_de_metadata($descripcion_caja_de_metadata);
        $this->set_opcional($opcional);
    }
    public function generar_fragmento_html($post)
    {
        // Convertir valor guardado (dd/mm/yyyy) a formato input (yyyy-mm-dd)
        $input_value = '';
        if (!empty($current_value)) {
            $fecha = DateTime::createFromFormat('d/m/Y', $current_value);
            if ($fecha) {
                $input_value = $fecha->format('Y-m-d');
            }
        }
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
            <input type="date" id="<?php echo esc_attr($this->get_id_caja_metadata()); ?>" name="<?php echo esc_attr($this->get_id_caja_metadata()); ?>"
                value="<?php echo esc_attr($input_value); ?>" />
        </div>
        <?php
    }
}