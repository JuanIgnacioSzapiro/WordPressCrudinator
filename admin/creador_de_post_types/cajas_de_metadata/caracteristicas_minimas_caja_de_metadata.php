<?php
class CaracteristicasMinimasCajaDeMetadata
{
    protected $id_caja_metadata;
    protected $metakey;
    protected $etiqueta_caja_de_metadata;
    protected $descripcion_caja_de_metadata;
    protected $clonable;
    protected $opcional;
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
        $clonable,
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
    public function get_metakey()
    {
        return $this->metakey;
    }
    public function set_metakey($valor)
    {
        $this->metakey = $valor;
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
}