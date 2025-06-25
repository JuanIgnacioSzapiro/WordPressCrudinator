<?php
class CajaDeArchivos extends CaracteristicasMinimasCajaDeMetadata
{
    protected $filtro_de_archivo;
    /**
     * Constructor de CajaDeFecha
     * @param string $id_caja_metadata
     * @param string $etiqueta_caja_de_metadata
     * @param string $descripcion_caja_de_metadata
     * @param array $filtro_de_archivo
     * @param bool $opcional
     */
    public function __construct(
        $id_caja_metadata,
        $etiqueta_caja_de_metadata,
        $descripcion_caja_de_metadata,
        $filtro_de_archivo,
        $opcional = true
    ) {
        $this->set_id_caja_metadata($id_caja_metadata);
        $this->set_etiqueta_caja_de_metadata($etiqueta_caja_de_metadata);
        $this->set_descripcion_caja_de_metadata($descripcion_caja_de_metadata);
        $this->set_filtro_de_archivo($filtro_de_archivo);
        $this->set_opcional($opcional);
    }
    public function get_filtro_de_archivo()
    {
        return $this->filtro_de_archivo;
    }
    public function set_filtro_de_archivo($valor)
    {
        $this->filtro_de_archivo = $valor;
    }
    public function generar_fragmento_html($post)
    {
        wp_enqueue_media();
        
        $current_file_id = get_post_meta($post->ID, $this->get_id_caja_metadata(), true);

        ?>
        <div class="file-upload-wrapper">
            <?php
            if (!$this->get_opcional()) {
                $this->generar_html_label_no_opcional();
            } else {
                $this->generar_html_label_opcional();
            }
            $this->generar_html_descripcion();
            ?>
            <input type="hidden" id="<?php echo esc_attr($this->get_id_caja_metadata()); ?>"
                name="<?php echo esc_attr($this->get_id_caja_metadata()); ?>"
                value="<?php echo esc_attr($current_file_id); ?>" />
            <br>
            <button type="button" class="button upload-file-btn"
                data-target="<?php echo esc_attr($this->get_id_caja_metadata()); ?>">
                <?php esc_html_e('Subir archivo', 'text-domain'); ?>
            </button>
            <button type="button" class="button deseleccionar-btn"
                data-target="<?php echo esc_attr($this->get_id_caja_metadata()); ?>">
                Deseleccionar el archivo
            </button>

            <div class="file-info-container" id="<?php echo esc_attr($this->get_id_caja_metadata()); ?>-info">
                <?php if ($current_file_id):
                    $this->render_file_info($current_file_id);
                endif; ?>
            </div>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                // Función para formatear el tamaño del archivo
                function formatFileSize(bytes) {
                    if (typeof bytes !== 'number') return '<?php esc_html_e('Tamaño aún no disponible', 'text-domain'); ?>';
                    if (bytes >= 1024 ** 3) return (bytes / 1024 ** 3).toFixed(2) + ' GB';
                    if (bytes >= 1024 ** 2) return (bytes / 1024 ** 2).toFixed(2) + ' MB';
                    if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' KB';
                    return bytes + ' B';
                }

                // Manejador del botón de subida
                $('.upload-file-btn[data-target="<?php echo esc_attr($this->get_id_caja_metadata()); ?>"]').click(function (e) {
                    e.preventDefault();
                    const target = $(this).data('target');

                    // Configurar el media frame
                    const file_frame = wp.media.frames.file_frame = wp.media({
                        title: '<?php esc_html_e('Seleccionar archivo', 'text-domain'); ?>',
                        button: { text: '<?php esc_html_e('Usar este archivo', 'text-domain'); ?>' },
                        library: {
                            type: <?php echo json_encode($this->get_filtro_de_archivo()); ?> // Filtro por tipo
                        },
                        multiple: false
                    });

                    // Al seleccionar archivo
                    file_frame.on('select', function () {
                        const attachment = file_frame.state().get('selection').first().toJSON();

                        // Actualizar campo oculto
                        $(`#${target}`).val(attachment.id);

                        // Renderizar información dinámica
                        const fileInfo = `
                            <p class="file-info">
                                <a href="${attachment.url}" target="_blank">${attachment.filename}</a> 
                                -> ${formatFileSize(attachment.filesize)}
                            </p>
                        `;

                        $(`#${target}-info`).html(fileInfo);
                    });

                    file_frame.open();
                });

                $('.deseleccionar-btn[data-target="<?php echo esc_attr($this->get_id_caja_metadata()); ?>"]').click(function (e) {
                    e.preventDefault();
                    const target = $(this).data('target');
                    $(`#${target}`).val(''); // Limpiar el valor del input
                    $(`#${target}-info`).html(''); // Limpiar la información del archivo
                });
            });
        </script>
        <?php
    }
    private function render_file_info($file_id)
    {
        $attachment = get_post($file_id);
        if (!$attachment || $attachment->post_type !== 'attachment')
            return;

        $metadata = wp_get_attachment_metadata($file_id);
        $file_size = $metadata['filesize'] ?? filesize(get_attached_file($file_id));
        ?>
        <p class="file-info">
            <?php echo the_attachment_link($file_id) . ' -> ' . size_format($file_size, 2); ?>
        </p>
        <?php
    }
}