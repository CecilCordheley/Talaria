<?php
use vendor\easyFrameWork\Core\Master\Cryptographer;
require_once ("vendor/easyFrameWork/Core/Master/EasyFrameWork.php");
use vendor\easyFrameWork\Core\Master\EasyFrameWork;

use vendor\easyFrameWork\Core\Master\Router;

use vendor\easyFrameWork\Core\Master\Autoloader;

EasyFrameWork::INIT("./vendor/easyFrameWork/Core/config/config.json");
Autoloader::register();
$router = new Router();
//Ici Insérez les routes
$router->addRoute('Licence', 'licenceController');
$router->addRoute('useLicence', 'licenceController');
$router->route($_SERVER["REQUEST_URI"],["year"=>date("Y")]);