<?php

/**
 * Net_RouterOS_PHP Autoloader.
 * 
 * Provides PSR-4 autoloading for the PEAR2\Net\RouterOS namespace when
 * installed without Composer. If Composer's vendor/autoload.php is available,
 * it will be loaded automatically.
 * 
 * PHP version 7.4+ / 8.x
 * 
 * @category Net
 * @package  PEAR2_Net_RouterOS
 * @license  http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @link     https://github.com/srikanta159/Net_RouterOS_PHP
 */

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

spl_autoload_register(function ($class) {
    $prefix = 'PEAR2\\Net\\RouterOS\\';
    $baseDir = __DIR__ . '/src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

