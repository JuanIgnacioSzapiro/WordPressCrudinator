<?php //meta_box_tipo_archivo.php
class CampoArchivo extends TipoMetaBox
{
    public function __construct(
        $nombre_meta,
        $etiqueta,
        $tipo_de_archivo,
        $descripcion,
        $clonable = false,
        $es_campo_opcional = false,
    ) {
        $this->set_nombre_meta($nombre_meta);
        $this->set_etiqueta($etiqueta);
        $this->set_tipo_de_archivo($tipo_de_archivo);
        $this->set_descripcion($descripcion);
        $this->set_clonable($clonable);
        $this->set_es_campo_opcional($es_campo_opcional);
    }

    public function generar_fragmento_html($post, $llave)
    {
        $meta_key = $llave . '_' . $this->nombre_meta;
        $current_file_id = get_post_meta($post->ID, $meta_key, true);

        wp_enqueue_media();
        ?>
        <div class="file-upload-wrapper">
            <?php
            if ($this->get_es_campo_opcional()) {
                ?>
                <label for="<?php echo esc_attr($meta_key); ?>">
                    <?php echo esc_html($this->get_etiqueta()); ?>
                </label>
                <?php
            } else {
                ?>
                <label class="no-opcional" for="<?php echo esc_attr($meta_key); ?>">
                    <?php echo esc_html($this->get_etiqueta()); ?> *
                </label>
                <div class="no-opcional-comentario">Este campo es OBLIGATORIO</div>
                <?php
            }
            ?>
            <input type="hidden" id="<?php echo esc_attr($meta_key); ?>" name="<?php echo esc_attr($meta_key); ?>"
                value="<?php echo esc_attr($current_file_id); ?>" />
            <br>
            <button type="button" class="button upload-file-btn" data-target="<?php echo esc_attr($meta_key); ?>">
                <?php esc_html_e('Subir archivo', 'text-domain'); ?>
            </button>
            <button type="button" class="button deseleccionar-btn" data-target="<?php echo esc_attr($meta_key); ?>">
                Deseleccionar el archivo
            </button>

            <div class="file-info-container" id="<?php echo esc_attr($meta_key); ?>-info">
                <?php if ($current_file_id):
                    $this->render_file_info($current_file_id);
                endif; ?>
            </div>

            <p class="description"><?php echo esc_html($this->descripcion); ?></p>
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
                $('.upload-file-btn[data-target="<?php echo esc_attr($meta_key); ?>"]').click(function (e) {
                    e.preventDefault();
                    const target = $(this).data('target');

                    // Configurar el media frame
                    const file_frame = wp.media.frames.file_frame = wp.media({
                        title: '<?php esc_html_e('Seleccionar archivo', 'text-domain'); ?>',
                        button: { text: '<?php esc_html_e('Usar este archivo', 'text-domain'); ?>' },
                        library: {
                            type: <?php echo json_encode($this->tipo_de_archivo); ?> // Filtro por tipo
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

                $('.deseleccionar-btn[data-target="<?php echo esc_attr($meta_key); ?>"]').click(function (e) {
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

    public function generar_fragmento_html_formulario($llave)
    {
        $meta_key = $llave . '_' . $this->nombre_meta;
        wp_enqueue_media();
        ?>
        <div class="file-upload-wrapper">
            <?php
            if ($this->get_es_campo_opcional()) {
                ?>
                <label for="<?php echo esc_attr($meta_key); ?>">
                    <?php echo esc_html($this->get_etiqueta()); ?>
                </label>
                <?php
            } else {
                ?>
                <label class="no-opcional" for="<?php echo esc_attr($meta_key); ?>">
                    <?php echo esc_html($this->get_etiqueta()); ?> *
                </label>
                <div class="no-opcional-comentario">Este campo es OBLIGATORIO</div>
                <?php
            }
            ?>
            <input type="hidden" id="<?php echo esc_attr($meta_key); ?>" name="<?php echo esc_attr($meta_key); ?>" />
            <br>
            <button type="button" class="button upload-file-btn" data-target="<?php echo esc_attr($meta_key); ?>">
                <?php esc_html_e('Subir archivo', 'text-domain'); ?>
            </button>
            <button type="button" class="button deseleccionar-btn" data-target="<?php echo esc_attr($meta_key); ?>">
                Deseleccionar el archivo
            </button>

            <p class="description"><?php echo esc_html($this->descripcion); ?></p>
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
                $('.upload-file-btn[data-target="<?php echo esc_attr($meta_key); ?>"]').click(function (e) {
                    e.preventDefault();
                    const target = $(this).data('target');

                    // Configurar el media frame
                    const file_frame = wp.media.frames.file_frame = wp.media({
                        title: '<?php esc_html_e('Seleccionar archivo', 'text-domain'); ?>',
                        button: { text: '<?php esc_html_e('Usar este archivo', 'text-domain'); ?>' },
                        library: {
                            type: <?php echo json_encode($this->tipo_de_archivo); ?> // Filtro por tipo
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

                $('.deseleccionar-btn[data-target="<?php echo esc_attr($meta_key); ?>"]').click(function (e) {
                    e.preventDefault();
                    const target = $(this).data('target');
                    $(`#${target}`).val(''); // Limpiar el valor del input
                    $(`#${target}-info`).html(''); // Limpiar la información del archivo
                });
            });
        </script>
        <?php
    }
}