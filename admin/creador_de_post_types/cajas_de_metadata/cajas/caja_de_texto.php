<?php
require_once dirname(__FILE__) . '/../caracteristicas_minimas_caja_de_metadata.php';

class CajaDeTexto extends CaracteristicasMinimasCajaDeMetadata
{
    protected $texto_ejemplificatorio;
    protected $expresion_regular;
    protected $mensaje_aclaratorio_de_expresion_regular;
    protected $es_clave;

    /**
     * Constructor de CajaDeTexto
     * @param string $id_caja_metadata
     * @param string $etiqueta_caja_de_metadata
     * @param string $texto_ejemplificatorio
     * @param string $descripcion_caja_de_metadata
     * @param bool $clonable
     * @param bool $es_clave
     * @param bool $opcional
     */
    public function __construct(
        $id_caja_metadata,
        $etiqueta_caja_de_metadata,
        $texto_ejemplificatorio,
        $descripcion_caja_de_metadata,
        $clonable,
        $es_clave = false,
        $opcional = true
    ) {
        $this->set_id_caja_metadata($id_caja_metadata);
        $this->set_etiqueta_caja_de_metadata($etiqueta_caja_de_metadata);
        $this->set_texto_ejemplificatorio($texto_ejemplificatorio);
        $this->set_descripcion_caja_de_metadata($descripcion_caja_de_metadata);
        $this->set_clonable($clonable);
        $this->set_es_clave($es_clave);
        $this->set_opcional($opcional);
    }
    public function set_texto_ejemplificatorio($valor)
    {
        $this->texto_ejemplificatorio = $valor;
    }
    public function get_texto_ejemplificatorio()
    {
        return $this->texto_ejemplificatorio;
    }
    public function set_es_clave($valor)
    {
        $this->es_clave = $valor;
    }
    public function get_es_clave()
    {
        return $this->es_clave;
    }
    /**
     * Se utiliza cuando la casilla de texto va a tener un formato específico
     * @param string $expresion_regular
     * @param string $mensaje_aclaratorio_de_expresion_regular
     */
    public function set_formato_de_texto($expresion_regular, $mensaje_aclaratorio_de_expresion_regular)
    {
        $this->expresion_regular = $expresion_regular;
        $this->mensaje_aclaratorio_de_expresion_regular = $mensaje_aclaratorio_de_expresion_regular;
    }
    public function get_expresion_regular()
    {
        return $this->expresion_regular;
    }
    public function get_mensaje_aclaratorio_de_expresion_regular()
    {
        return $this->mensaje_aclaratorio_de_expresion_regular;
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
            if ($this->get_mensaje_aclaratorio_de_expresion_regular() != "") {
                ?>
                <p class="description">
                    <?php echo esc_html($this->get_mensaje_aclaratorio_de_expresion_regular()); ?>
                </p>
                <?php
            }
            if (!$this->get_clonable()) {
                $this->generar_fragmento_html_no_clonable();
            } else {
                $this->generar_fragmento_html_clonable();
            }
            ?>
        </div>
        <?php
    }
    protected function generar_fragmento_html_no_clonable()
    {
        ?>
        <p>
            <input type="<?php echo !$this->get_es_clave() ? 'text' : 'password' ?>"
                id="<?php echo esc_attr($this->get_id_caja_metadata()); ?>"
                name="<?php echo esc_attr($this->get_id_caja_metadata()); ?>" value="<?php ?>"
                placeholder="<?php echo esc_attr($this->get_texto_ejemplificatorio()); ?>" style="width: 100%; margin: 0%;" />
        </p>
        <?php
    }
    protected function generar_fragmento_html_clonable()
    {
        ?>
        <div class="clonable-container-texto">
            <div class="clonable-fields-texto">
                <?php
                // Always render at least ONE field (even if empty)
                if (empty($values)) {
                    $values = [''];
                }
                foreach ($values as $contador => $value) { ?>
                    <div class="clonable-field-texto">
                        <input type="text" id="<?php echo esc_attr($this->get_id_caja_metadata()); ?>[]"
                            name="<?php echo esc_attr($this->get_id_caja_metadata()); ?>[]" value="<?php ?>"
                            placeholder="<?php echo esc_attr($this->get_texto_ejemplificatorio()); ?>"
                            style="width: 100%; margin-bottom: 5px;" />
                        <button type="button" class="button remove-field-texto">Eliminar</button>
                    </div>
                <?php } ?>
            </div>
            <button type="button" class="button add-field-texto">Agregar más</button>
        </div>
        <script>
            (function ($) {
                // Only define once in global scope
                if (typeof window.initClonableFields_texto !== 'function') {
                    window.initClonableFields_texto = function () {
                        $(document)
                            .off('click', '.clonable-container-texto .add-field-texto') // Prevent duplicate bindings
                            .on('click', '.clonable-container-texto .add-field-texto', function (e) {
                                e.preventDefault();
                                const container = $(this).closest('.clonable-container-texto');
                                const newField = container.find('.clonable-field-texto:last').clone();
                                newField.find('input').val('');
                                container.find('.clonable-fields-texto').append(newField);
                            })
                            .off('click', '.clonable-container-texto .remove-field-texto')
                            .on('click', '.clonable-container-texto .remove-field-texto', function (e) {
                                e.preventDefault();
                                const container = $(this).closest('.clonable-container-texto');
                                if (container.find('.clonable-field-texto').length > 1) {
                                    $(this).closest('.clonable-field-texto').remove();
                                }
                            });
                    };
                }
                // Initialize when DOM is ready
                $(document).ready(function () {
                    if (!window.clonableFieldsInitialized_texto) {
                        window.initClonableFields_texto();
                        window.clonableFieldsInitialized_texto = true;
                    }
                });
            })(jQuery);
        </script>
        <?php
    }
}