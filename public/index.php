<?php

use CodeIgniter\Boot;
use Config\Paths;

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */

$minPhpVersion = '8.2'; // If you update this, don't forget to update `spark`.
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;

    exit(1);
}

/*
 *---------------------------------------------------------------
 * SAMAKAN HURUF SCRIPT_NAME DENGAN URL YANG DIMINTA
 *---------------------------------------------------------------
 * Di Windows nama folder tidak peka huruf besar/kecil: URL "/simpena/login"
 * tetap dijalankan oleh "SIMPENA/public/index.php", tetapi Apache melaporkan
 * SCRIPT_NAME memakai huruf asli folder ("/SIMPENA/public/index.php").
 * CodeIgniter memotong path REQUEST_URI memakai SCRIPT_NAME secara
 * case-sensitive, sehingga "/simpena/login" dibaca sebagai route
 * "simpena/login" dan berakhir 404.
 *
 * Karena itu segmen awal SCRIPT_NAME disamakan dengan huruf yang diketik
 * pengguna, mis. "/simpena/public/index.php". Nama file asli tidak berubah.
 */
$requestPath = str_replace('\\', '/', (string) strtok((string) ($_SERVER['REQUEST_URI'] ?? ''), '?'));
$scriptName  = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));

if ($requestPath !== '' && $scriptName !== '') {
    $segmenScript = explode('/', $scriptName);
    $segmenUri    = explode('/', $requestPath);

    foreach ($segmenScript as $i => $segmen) {
        if (! isset($segmenUri[$i]) || strcasecmp($segmen, $segmenUri[$i]) !== 0) {
            break;
        }

        $segmenScript[$i] = $segmenUri[$i];
    }

    $scriptNameBaru = implode('/', $segmenScript);

    if ($scriptNameBaru !== $scriptName) {
        $_SERVER['SCRIPT_NAME'] = $scriptNameBaru;

        if (isset($_SERVER['PHP_SELF'])) {
            $_SERVER['PHP_SELF'] = $scriptNameBaru;
        }
    }
}

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 */

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// LOAD OUR PATHS CONFIG FILE
// This is the line that might need to be changed, depending on your folder structure.
require FCPATH . '../app/Config/Paths.php';
// ^^^ Change this line if you move your application folder

$paths = new Paths();

// LOAD THE FRAMEWORK BOOTSTRAP FILE
require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
