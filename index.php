<?php

use Imperium\Databases\Eloquent\Connexion\Connexion;


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

        <link rel='shortcut icon' href='https://git-scm.com/favicon.ico'/>
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

                $records = records(Connexion::MYSQL,'table table-dark table-bordered',$i,$x,fa('fa-table'),'Selected a another table','edit','remove','desc','editer','supprimer','btn btn-outline-primary btn-block','btn btn-outline-danger btn-block',fa('fa-edit'),fa('fa-trash'),$limit,$current,'imperium',$pdo,   'Sauvegarder','Etes vous sur de vouloir supprimer ?','debut','fin','Advance','simple',"index.php?table=$x",'management','index.php?table=',true,true,'',false,false,true,'',1 );

                 echo html('div', $records, 'container');
            }catch (Exception $e)
            {
                exit($e->getMessage());
            }
        ?>

          <?= bootstrapJs();?>


            <?php
                $form = form('a','dazzae')->setLargeInput(true)->startRow()->reset('reset','btn btn-block btn-outline-danger')->button(\Imperium\Html\Form\Form::RESET,'empty','btn btn-block btn-outline-primary')->endRowAndNew() ->input('number','a','daz')->select('a',['aadzadz','vdazdazdza'])->endRowAndNew()->textarea('a','adzzad',10,10)->endRowAndNew()->select('a',['a'])->select('a', ['adz'])->select('a',['a'])->select('a',['a'])->endRowAndNew()->redirectSelect('aadz',['a','a'])->redirectSelect('adza' ,['a'])->endRowAndNew()->checkbox('a','azd')->checkbox('adaz','aadzzd','',false)->checkbox('adaz','aadzzd',false)->checkbox('adaz','aadzzd','',false)->endRowAndNew()->radio('adaadzazdzaadazdadzz','a','')->endRowAndNew()->link('https://git.fumseck.eu/cgit','btn btn-outline-primary btn-block','azd')->link('https://git.fumseck.eu/cgit','btn btn-block btn-outline-danger','azdazd')->link('https://git.fumseck.eu/cgit','btn btn-outline-secondary btn-block','dazdazaz')->endRowAndNew()->file('a', 'selection a file','fr')->endRowAndNew()->submit('submit','btn btn-outline-primary btn-block','azd')->endRow()->end();

                $t = html('div',$form,'container mt-5 mb-5');

                 _html(false,$t,$t,$t,$form);

            ?>




    </body>
</html>




