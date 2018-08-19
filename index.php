<?php

use Imperium\Databases\Eloquent\Connexion\Connexion;

require_once 'vendor/autoload.php';

$i = table(Connexion::MYSQL,'zen','root','root','dump');

$x = empty(get('table')) ? 'doctors' : get('table');

$pdo = connect(Connexion::MYSQL,'zen','root','root');
$H = server('HTTP_REFERER');


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

                $records = records('mysql','  ',$i,$x,'imperium/edit',"$H/$x/remove?remove-id=",'DESC','Editer','Supprimer','btn btn-outline-primary btn-block','btn btn-outline-danger btn-block',fa('fa-edit'),fa('fa-trash'),20,1,'imperium',$pdo,1,"Sauvegarder",'are you sure','previous','end','','advanced','normal',"index.php?table=$x",'',$x,'?table=',true,true,'',true,true,true,25,1);

                echo html('div', $records, 'container');
            }catch (Exception $e)
            {
                exit($e->getMessage());
            }
        ?>

        <?= bootstrapJs();?>


        <script type="text/javascript">

        </script>

    </body>
</html>




