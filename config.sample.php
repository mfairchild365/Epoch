<?php
function autoload($class)
{
    $file = str_replace(array('_', '\\'), '/', $class).'.php';
    if ($fullpath = stream_resolve_include_path($file)) {
        include $fullpath;
        return true;
    }
    return false;
}

spl_autoload_register("autoload");

set_include_path(
    implode(PATH_SEPARATOR, array(get_include_path())).PATH_SEPARATOR
    .dirname(__FILE__) . '/src'.PATH_SEPARATOR
    .dirname(__FILE__).'/lib/php'.PATH_SEPARATOR
    .dirname(__FILE__).'/lib/php/RegExpRouter/src'
);

//Session life in seconds.
ini_set("session.gc_maxlifetime", 7200); 

ini_set('display_errors', true);

error_reporting(E_ALL);

\Epoch\Router::$cacheRoutes = false;

\App\Controller::$url = 'http://localhost/Epoch/www/';

\App\Controller::setDbSettings(array(
    'host'     => 'localhost',
    'user'     => 'app',
    'password' => 'app',
    'dbname'   => 'app'
));