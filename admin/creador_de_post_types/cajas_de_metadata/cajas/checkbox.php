<?php
class Checkbox extends CaracteristicasMinimasCajaDeMetadata
{
    protected $opciones;
    /**
     * Constructor de Checkbox
     * @param string $id_caja_metadata
     * @param string $etiqueta_caja_de_metadata
     * @param string $descripcion_caja_de_metadata
     * @param array $opciones
     * @param bool $opcional
     */
    public function __construct($id_caja_metadata, $etiqueta_caja_de_metadata, $descripcion_caja_de_metadata, $opciones, $opcional = true)
    {
        $this->set_id_caja_metadata($id_caja_metadata);
        $this->set_etiqueta_caja_de_metadata($etiqueta_caja_de_metadata);
        $this->set_descripcion_caja_de_metadata($descripcion_caja_de_metadata);
        $this->set_opciones($opciones);
        $this->set_opcional($opcional);
    }
    public function set_opciones($valor)
    {
        $this->opciones = $valor;
    }
    public function get_opciones()
    {
        return $this->opciones;
    }
    public function generar_fragmento_html($post)
    {
        ?>
        <div>
            <?php
            if (!$this->get_opcional()) {
                $this->generar_html_label_no_opcional();
            } else {
                $this->generar_html_label_opcional();
            }
            $this->generar_html_descripcion();
            $this->generar_fragmento_html_no_clonable();
            ?>
        </div>
        <?php
    }
    public function generar_fragmento_html_no_clonable()
    {
        // Generar checkboxes para cada opción
        foreach ($this->get_opciones() as $key => $opcion) {
            $cadena = $this->get_id_caja_metadata() . '_' . $key;
            // $input_id = $this->get_id_caja_metadata() . '_' . $key;
            // $checked = in_array($opcion, $saved_values) ? 'checked="checked"' : '';
            ?>
            <input type="checkbox" id="<?php echo esc_attr($cadena); ?>" name="<?php echo esc_attr($cadena); ?>[]"
                value="<?php echo esc_attr($opcion); ?>" <?php
                   //echo $checked;
                   ?> />
            <label for="<?php echo esc_attr($cadena); ?>">
                <?php echo esc_html($opcion); ?>
            </label>
            <br>
            <?php
        }
    }
}