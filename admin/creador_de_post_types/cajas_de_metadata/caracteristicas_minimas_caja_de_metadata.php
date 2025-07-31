<?php
class CaracteristicasMinimasCajaDeMetadata
{
    protected $id_caja_metadata;
    protected $etiqueta_caja_de_metadata;
    protected $descripcion_caja_de_metadata;
    protected $clonable;
    protected $opcional;
    protected $tipo_de_dato_sql;
    /**
     * Constructor de CaracteristicasMinimasCajaDeMetadata
     * @param string $id_caja_metadata
     * @param string $etiqueta_caja_de_metadata
     * @param string $descripcion_caja_de_metadata
     * @param bool $clonable
     * @param bool $opcional default es false
     */
    public function __construct(
        $id_caja_metadata,
        $etiqueta_caja_de_metadata,
        $descripcion_caja_de_metadata,
        $clonable = null,
        $opcional = true
    ) {
        $this->set_id_caja_metadata($id_caja_metadata);
        $this->set_etiqueta_caja_de_metadata($etiqueta_caja_de_metadata);
        $this->set_descripcion_caja_de_metadata($descripcion_caja_de_metadata);
        $this->set_clonable($clonable);
        $this->set_opcional($opcional);
    }
    public function get_id_caja_metadata()
    {
        return $this->id_caja_metadata;
    }
    public function set_id_caja_metadata($valor)
    {
        $this->id_caja_metadata = $valor;
    }
    public function get_etiqueta_caja_de_metadata()
    {
        return $this->etiqueta_caja_de_metadata;
    }
    public function set_etiqueta_caja_de_metadata($valor)
    {
        $this->etiqueta_caja_de_metadata = $valor;
    }
    public function get_descripcion_caja_de_metadata()
    {
        return $this->descripcion_caja_de_metadata;
    }
    public function set_descripcion_caja_de_metadata($valor)
    {
        $this->descripcion_caja_de_metadata = $valor;
    }
    public function get_clonable()
    {
        return $this->clonable;
    }
    public function set_clonable($valor)
    {
        $this->clonable = $valor;
    }
    public function get_opcional()
    {
        return $this->opcional;
    }
    public function set_opcional($valor)
    {
        $this->opcional = $valor;
    }
    public function get_tipo_de_dato_sql()
    {
        return $this->tipo_de_dato_sql;
    }
    public function set_tipo_de_dato_sql($valor)
    {
        $this->tipo_de_dato_sql = $valor;
    }

    /**
     * Imprime el código html
     * @param mixed $post
     */
    public function generar_fragmento_html($post)
    {
        ?>
        <p>El campo con id '<?php $this->get_id_caja_metadata() ?>' no está disponible</p>
        <?php
    }

    protected function generar_html_label_no_opcional()
    {
        ?>
        <label for="<?php echo esc_attr($this->get_id_caja_metadata()); ?>">
            <?php echo esc_html($this->get_etiqueta_caja_de_metadata()); ?>
        </label>
        <?php
    }
    protected function generar_html_label_opcional()
    {
        ?>
        <label class="no-opcional" for="<?php echo esc_attr($this->get_id_caja_metadata()); ?>">
            <?php echo esc_html($this->get_etiqueta_caja_de_metadata()); ?> *
        </label>
        <div class="no-opcional-comentario">
            Este campo es OBLIGATORIO
        </div>
        <?php
    }
    protected function generar_html_descripcion()
    {
        ?>
        <p class="description">
            <?php echo esc_html($this->get_descripcion_caja_de_metadata()); ?>
        </p>
        <?php
    }
    public function generar_caracteristicas_campo_sql()
    {
        return $this->get_id_caja_metadata() . ' ' . $this->get_tipo_de_dato_sql();
    }
}