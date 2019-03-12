<?php
require_once  '../vendor/autoload.php';

$git =     $git = new \Imperium\Versioning\Git\Git(dirname(core_path('app')) .DIRECTORY_SEPARATOR  .'data/symfony');
d($git->contributors()->collection());
?>
<!DOCTYPE HTML>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <input type="search" id="git-search" class="form-control form-control-lg mt-5" placeholder="search">
    <div id="line" class="mt-5"></div>


</div>
<?= app()->model()->from('git')->display(DISPLAY_CONTRIBUTORS,'/',1,'','','') ;?>
<?= $git->contributors_view() ?>
<script
  src="http://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="js/app.js"></script>
</body>
</html>