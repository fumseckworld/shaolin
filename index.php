<?php

use Imperium\Databases\Eloquent\Connexion\Connexion;

require_once 'vendor/autoload.php';

$i = table(Connexion::MYSQL,'zen','root','root','dump');

$x = empty(get('table')) ? 'doctors' : get('table');

$pdo = connect(Connexion::MYSQL,'zen','root','root');


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


        if (submit('id'))
        {
            $z = post('table');
            $result = $i->update(intval($_POST['id']),$_POST,[$z],$z);
           if ($result)
            {
                $redirect = server('HTTP_REFERER');
               header("Location :$redirect");
           }
           else{
               var_dump($result);

           }
        }
            try{

                $records = records('mysql','table table-bordered table-hover table-dark',$i,$x,'imperium/edit','imperium/remove','DESC','Editer','remove','btn btn-outline-primary btn-block','btn btn-outline-danger',fa('fa-edit'),fa('fa-trash'),200,1,'imperium',$pdo,1,"search",'are you sure','previous','end','','advanced','normal',"index.php?table=$x",'',$x,'?table=',true,true,'',true,true,true,25,1);

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




