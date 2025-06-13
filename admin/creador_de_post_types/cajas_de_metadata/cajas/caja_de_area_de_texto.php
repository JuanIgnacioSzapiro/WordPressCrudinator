<?php
require_once dirname(__FILE__) . '/../caracteristicas_minimas_caja_de_metadata.php';

class CajaDeAreaDeTexto extends CaracteristicasMinimasCajaDeMetadata
{
    protected $texto_ejemplificatorio;
    protected $expresion_regular;
    protected $mensaje_aclaratorio_de_expresion_regular;
    /**
     * Constructor de CajaDeAreaDeTexto
     * @param string $id_caja_metadata
     * @param string $etiqueta_caja_de_metadata
     * @param string $texto_ejemplificatorio
     * @param string $descripcion_caja_de_metadata
     * @param bool $clonable
     * @param bool $opcional
     */
    public function __construct(
        $id_caja_metadata,
        $etiqueta_caja_de_metadata,
        $texto_ejemplificatorio,
        $descripcion_caja_de_metadata,
        $clonable,
        $opcional = true
    ) {
        $this->set_id_caja_metadata($id_caja_metadata);
        $this->set_etiqueta_caja_de_metadata($etiqueta_caja_de_metadata);
        $this->set_texto_ejemplificatorio($texto_ejemplificatorio);
        $this->set_descripcion_caja_de_metadata($descripcion_caja_de_metadata);
        $this->set_clonable($clonable);
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
        <textarea onfocus="this.style.height='auto';this.style.height=this.scrollHeight+'px'"
            oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px'"
            id="<?php echo esc_attr($this->get_id_caja_metadata()); ?>[]"
            name="<?php echo esc_attr($this->get_id_caja_metadata()); ?>[]"
            placeholder="<?php echo esc_attr($this->get_texto_ejemplificatorio()); ?>" style="width: 100%;"><?php ?></textarea>
        <?php
    }
    protected function generar_fragmento_html_clonable()
    {
        ?>
        <div class="clonable-container-area-de-texto">
            <div class="clonable-fields-area-de-texto">
                <?php
                // Always render at least ONE field (even if empty)
                if (empty($values)) {
                    $values = [''];
                }
                foreach ($values as $contador => $value) { ?>
                    <div class="clonable-field-area-de-texto">
                        <textarea onfocus="this.style.height='auto';this.style.height=this.scrollHeight+'px'"
                            oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px'"
                            id="<?php echo esc_attr($this->get_id_caja_metadata()); ?>[]"
                            name="<?php echo esc_attr($this->get_id_caja_metadata()); ?>[]"
                            placeholder="<?php echo esc_attr($this->get_texto_ejemplificatorio()); ?>"
                            style="width: 100%;"><?php ?></textarea>
                        <button type="button" class="button remove-field-area-de-texto">Eliminar</button>
                    </div>
                <?php } ?>
            </div>
            <button type="button" class="button add-field-area-de-texto">Agregar más</button>
        </div>
        <script>
            (function ($) {
                // Only define once in global scope
                if (typeof window.initClonableFields_area !== 'function') {
                    window.initClonableFields_area = function () {
                        $(document)
                            .off('click', '.clonable-container-area-de-texto .add-field-area-de-texto') // Prevent duplicate bindings
                            .on('click', '.clonable-container-area-de-texto .add-field-area-de-texto', function (e) {
                                e.preventDefault();
                                const container = $(this).closest('.clonable-container-area-de-texto');
                                const newField = container.find('.clonable-field-area-de-texto:last').clone();
                                newField.find('textarea').val('');
                                container.find('.clonable-fields-area-de-texto').append(newField);
                            })
                            .off('click', '.clonable-container-area-de-texto .remove-field-area-de-texto')
                            .on('click', '.clonable-container-area-de-texto .remove-field-area-de-texto', function (e) {
                                e.preventDefault();
                                const container = $(this).closest('.clonable-container-area-de-texto');
                                if (container.find('.clonable-field-area-de-texto').length > 1) {
                                    $(this).closest('.clonable-field-area-de-texto').remove();
                                }
                            });
                    };
                }

                // Initialize when DOM is ready
                $(document).ready(function () {
                    if (!window.clonableFieldsInitialized_area_de_texto) {
                        window.initClonableFields_area();
                        window.clonableFieldsInitialized_area_de_texto = true;
                    }
                });
            })(jQuery);
        </script>
        <?php
    }
}