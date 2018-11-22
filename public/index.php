<?php

use DebugBar\DebugBar;

require '../vendor/autoload.php';


$debugbar = new DebugBar();
$debugbarRenderer = $debugbar->getJavascriptRenderer();

?>
<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>

<?= bootstrap_js(). $debugbarRenderer->renderHead()?>
</head>
<body>

<h1>This is a Heading</h1>
<p>This is a paragraph.</p>
    <?= $debugbarRenderer->render() ?>
</body>
</html>
