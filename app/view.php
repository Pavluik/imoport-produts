<?php
/** @var string|array $response */
if (isset($response) && is_array($response)) {
    $response = ucwords(str_replace('&', ' | ', http_build_query($response)));
}
?>

<html lang="en">
<head>
    <title>Product import</title>
</head>
<body>
<h1><?=$response ?? 'Import products';?></h1>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="file" required>
    <button type="submit">Import</button>
</form>
</body>
</html>

