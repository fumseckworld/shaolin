<?php

use Imperium\Databases\Eloquent\Connexion\Connexion;
use Imperium\Html\Form\Form;

require_once 'vendor/autoload.php';

$i = table(Connexion::MYSQL,'zen','root','root','dump');

$x = empty(get('table')) ? 'doctors' : get('table');

$pdo = connect(Connexion::MYSQL,'zen','root','root');
$H = server('HTTP_REFERER');
$limit = 10;
$current = rand(1,$limit);;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?= cssLoader('/lily/lily-dark.css');  ?>
        <?= fontAwesome();  ?>
        <title>Imperium</title>
    </head>
    <body>
        <?php


        if (get('remove-id'))
        {
            $url = server('REQUEST_URI');
            $p = explode('/',$url);
            $table = $p[1];
            $id = intval(get('remove-id'));
            if($i->setName($table)->deleteById($id))
            {

                header("Location : /");

            }

        }
        if (submit('id'))
        {
            $z = post('table');
            $result = $i->update(intval($_POST['id']),$_POST,[$z],$z);
           if ($result)
            {
                $redirect = server('HTTP_HOST');
               header("Location :$redirect?table=$z");
           }
           else{
               var_dump($result);

           }
        }
            try{

                $records = records(Connexion::MYSQL,'table table-dark table-bordered',$i,$x,fa('fa-table'),'Selected a another table','edit','remove','desc','editer','supprimer','btn btn-outline-primary btn-block','btn btn-outline-danger btn-block',fa('fa-edit'),fa('fa-trash'),$limit,$current,'imperium',$pdo,  Form::BOOTSTRAP,'Sauvegarder','Etes vous sur de vouloir supprimer ?','debut','fin','Advance','simple',"index.php?table=$x",'management','index.php?table=',true,true,'',false,false,true,'',1);

                echo html('div', $records, 'container');
            }catch (Exception $e)
            {
                exit($e->getMessage());
            }
        ?>

        <?= bootstrapJs();?>


    </body>
</html>




