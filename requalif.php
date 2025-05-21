<?php
use vendor\easyFrameWork\Core\Master\Cryptographer;
require_once ("vendor/easyFrameWork/Core/Master/EasyFrameWork.php");
use vendor\easyFrameWork\Core\Master\EasyFrameWork;

use vendor\easyFrameWork\Core\Master\Router;

use vendor\easyFrameWork\Core\Master\Autoloader;

EasyFrameWork::INIT("./vendor/easyFrameWork/Core/config/config.json");
Autoloader::register();
$router = new Router();
$router->addRoute('requalif.php', 'requalifController');
$router->route($_SERVER["REQUEST_URI"],["year"=>date("Y")]);