<?php
use app\core\Application;
require_once "vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $app = new Application();
    $message = $app->run();
}
?>

<html lang="en">
<head>
<title>Product import</title>
</head>
<body>
<h1><?=$message ?? 'Import products';?></h1>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="file-csv" required>
    <button type="submit">Import</button>
</form>
</body>
</html>
