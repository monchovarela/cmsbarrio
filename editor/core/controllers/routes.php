<?php defined('BARRIO') or die('Sin accesso a este script.');

// iniciamos el router
$R = new Router();


// Principal
$R->Route(
    '/load/(:any)',
    function ($name = '') {
        echo file_get_contents(ROOT.'/public/demos/'.$name.'.md');
    }
);

// Preview
$R->Route(
    '/preview',
    function () {
        include CONTROLLERS.'/editor.class.php';
        include CONTROLLERS.'/shortcodes.php';
        $e = new Editor();
        $_POST = json_decode(file_get_contents('php://input'), true);
        if(Url::post('code')){
            $content = base64_decode(Url::post('code'));
            echo $e->parseContent($content);
        }
        exit;
    }
);

// Principal
$R->Route(
    '/',
    function () {
        include CONTROLLERS.'/editor.class.php';
        $content = file_get_contents(ROOT.'/public/demos/inicio.md');
        include VIEWS.'/index.html';
    }
);



// Iniciar router
$R->launch();
