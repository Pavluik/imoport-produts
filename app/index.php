<?php
use App\Core\Application;
require_once "vendor/autoload.php";
$config = require_once __DIR__.'/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $app = new Application($config, $_FILES['file']);
    $response = $app->run();
}

include 'view.php';