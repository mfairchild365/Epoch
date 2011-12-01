<?php
if (file_exists(dirname(dirname(__FILE__)) . '/config.inc.php')) {
    require_once dirname(dirname(__FILE__)) . '/config.inc.php';
} else {
    require dirname(dirname(__FILE__)) . '/config.sample.php';
}

session_start();

$router = new \Epoch\Router(array('baseURL' => \App\Controller::$url, 'srcDir' => dirname(dirname(__FILE__)) . "/src/App/"));

if (isset($_GET['model'])) {
    unset($_GET['model']);
}

$app = new \App\Controller($router->route($_SERVER['REQUEST_URI'], $_GET));

$savvy = new \Epoch\OutputController();

if ($app->options['format'] != 'html') {
    switch($app->options['format']) {
        case 'partial':
            Savvy_ClassToTemplateMapper::$output_template['App\Controller'] = 'App/Controller-partial';
            break;
        case 'text':
        case 'json':
            $savvy->addTemplatePath(dirname(__FILE__).'/www/templates/'.$app->options['format']);
            header('Content-type:application/json;charset=UTF-8');
            break;
        default:
            header('Content-type:text/html;charset=UTF-8');
    }
}

// Always escape output, use $context->getRaw('var'); to get the raw data.
$savvy->setEscape('htmlentities');

echo $savvy->render($app);
