<?php

function cargar_superglobales()
{
    cargar_configuracion_desde_csv();
}

/**
 * Abre en modo lectura el archivo csv
 * @return void
 */
function cargar_configuracion_desde_csv()
{
    // Rutas de los archivos CSV en el directorio del plugin
    $csv_files = array(
        dirname(__FILE__) . '/../constantes/prefijos.csv',
    );

    // Apertura y lectura de los diferentes archivos y creación de las variables globales
    foreach ($csv_files as $csv_file) {
        abridor($csv_file);
    }
}

function abridor($csv_file)
{
    // Verificar si el archivo existe
    if (!file_exists($csv_file)) {
        error_log('El archivo de configuración CSV no existe');
        return;
    }

    // Verificar que sea un archivo CSV válido
    $file_info = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($file_info, $csv_file);
    finfo_close($file_info);

    if (!in_array($mime_type, ['text/plain', 'text/csv'])) {
        error_log('El archivo no es un CSV válido');
        return;
    }

    // Abrir y leer el archivo CSV
    if (($handle = fopen($csv_file, 'r')) !== FALSE) {
        // Leer encabezados
        $headers = fgetcsv($handle, 1000, ',');

        // Leer primera línea de datos
        $data = fgetcsv($handle, 1000, ',');

        fclose($handle);

        if ($data !== FALSE) {
            // Combinar encabezados con datos
            $config = array_combine($headers, $data);

            // Sanitizar y asignar a variables globales
            foreach ($config as $key => $value) {
                $global_var_name = sanitize_key($key);
                $global_value = sanitize_text_field($value);

                // Crear variables globales dinámicamente
                $GLOBALS[$global_var_name] = $global_value;
            }
        }
    }
}