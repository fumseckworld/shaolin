<?php


use Imperium\Databases\Eloquent\Connexion\Connexion;


require_once 'vendor/autoload.php';
whoops();

$pdo = connect(Connexion::MYSQL,'zen','root','root');
$bases = base(Connexion::MYSQL,'zen','root','root','');
$users =  user(Connexion::MYSQL,'root','root');
$tables = table(Connexion::MYSQL,'zen','root','root','');
$query = sql( 'doctors');
$model = model($pdo,$tables,'doctors');
$app = imperium($bases,$tables,$users,$query,$model);



$x = empty(get('table')) ? 'doctors' : get('table');

?>
<!DOCTYPE html>
<html lang="en">
    <head>

        <?= fontAwesome();  ?>
        <?= cssLoader('lily/lily-dark.css');  ?>
        <title>Imperium</title>

        <link rel='shortcut icon' href='https://git-scm.com/favicon.ico'/>
    </head>
    <body>

    <div class="container">
        <?php $app->print( html('div',registerForm('a', '1','1','username','email','password','confirm','create account','register',true,[ ''=> 'Choose a language','en' => 'English','fr' => 'Francais'],'choose a time zone'),'mt-5')) ?>
    </div>
    </body>
</html>




