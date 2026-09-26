<?php
/**
 * AionCMS
 * https://aioncms.com
 */

# Simular variables de entorno para que system.php no falle en consola (CLI)
if (!isset($_SERVER['HTTP_HOST'])) {$_SERVER['HTTP_HOST'] = 'colombianaion.duckdns.org';
}
if (!isset($_SERVER['SERVER_NAME'])) {$_SERVER['SERVER_NAME'] = 'colombianaion.duckdns.org';
}

# Mostrar errores temporalmente para diagnosticar
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

# Access
define('access', 'cron');

# Path
$file_name = basename(__FILE__);$sys_path = str_replace('\\', '/', dirname(dirname(__FILE__))) . '/';

# Load system
try {
    if (!@include_once($sys_path . 'system.php')) {         throw new Exception('Could not load engine.');     }$sdb = Handler::loadDB('siel');
    
    $result =$sdb->queryFetch("SELECT `players`.`id`, `players`.`name`, `players`.`exp`, `players`.`race`, `players`.`player_class`, `players`.`gender`, `abyss_rank`.`ap` FROM `abyss_rank` INNER JOIN `players` ON `abyss_rank`.`player_id` = `players`.`id` ORDER BY `abyss_rank`.`ap` DESC LIMIT 15", array());

    $rowData = [];
    
    if (is_array($result) && !empty($result)) {
        foreach ($result as$row) {
            if (is_array($row)) {
                $rowData[] = implode(",", $row);
            }
        }
    }

    if (empty($rowData)) {
        throw new Exception('La consulta se ejecutó, pero no devolvió jugadores.');
    }

    $cacheData = implode("\vert{}\vert{}", $rowData);

    // Cache File Path
    $filePath = __PATH_CACHE__ . 'rankings.abyss.siel.cache';

    // Save Data
    $fp = fopen($filePath, 'w');
    if ($fp) {
        fwrite($fp,$cacheData);
        fclose($fp);
    } else {
        throw new Exception('No hay permisos (CHMOD) para escribir el archivo de caché en: ' . $filePath);
    }

    echo '1 (Caché generada con éxito)' . PHP_EOL;
    
} catch (Exception $ex) {
    die("Error en Cron: " . $ex->getMessage() . PHP_EOL);
}
