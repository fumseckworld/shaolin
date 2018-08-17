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

            try{

                $records = records('mysql','table table-bordered table-hover table-dark',$i,$x,'imperium/edit','imperium/remove','DESC','edit','remove','btn btn-outline-primary','btn btn-outline-danger',fa('fa-edit'),fa('fa-trash'),10,rand(1,10),'imperium',$pdo,1,"search",'are you sure','previous','end','','advanced','normal','index.php','',$x,'?table=',true,true,'',true,true,true,25,1);

                echo html('div', $records, 'container');
            }catch (Exception $e)
            {
                exit($e->getMessage());
            }
        ?>

        <?= bootstrapJs();?>

        <style>
            .table
            {
                display:table;
                border-collapse:separate;
                border-spacing:  1rem;
            }
            .thead
            {
                display:table-header-group;
                color:white;
                text-align: center;
                text-transform: uppercase;

                font-weight:bold;
                background-color:#000;
            }

            .tbody
            {
                display:table-row-group;
                background-color: #000;
            }
            .tr
            {
                display:table-row;

            }
            .td
            {
                display:table-cell;
                padding: 1rem;
                border: 1px solid #2e353c;
            }
            .tr:hover{
                background-color: rgba(19, 26, 35, 0.82);
            }
            .tr.editing .td INPUT
            {
                width:100%;
            }
        </style>
        <div class="container">
            <div class="table-responsive">
                <div class="table">
                    <div class="thead">
                        <div class="tr">
                            <div class="td">One</div>
                            <div class="td">Two</div>
                            <div class="td">Three</div>
                            <div class="td">Four</div>
                            <div class="td">five</div>
                            <div class="td">action</div>
                        </div>
                    </div>
                    <div class="tbody">
                        <form class="tr">
                            <div class="td">1</div>
                            <div class="td">2</div>
                            <div class="td">3</div>
                            <div class="td">4567890123456</div>
                            <div class="td">4567890123456</div>
                            <div class="td action btn-group"><button type="button" onclick="edit(this);" class="btn-outline-primary btn-block  btn">Edit</button> </div>
                        </form>
                        <form class="tr">
                            <div class="td">1</div>
                            <div class="td">2</div>
                            <div class="td">3</div>
                            <div class="td">4</div>
                            <div class="td">4</div>
                            <div class="td action btn-group"><button type="button" onclick="edit(this);" class="btn-outline-primary btn-block  btn">Edit</button> </div>
                        </form>
                        <form class="tr">
                            <div class="td">1</div>
                            <div class="td">234567890123456</div>
                            <div class="td">3</div>
                            <div class="td">3</div>
                            <div class="td">4</div>
                            <div class="td action btn-group"><button type="button" onclick="edit(this);" class="btn-outline-primary btn-block  btn">Edit</button> </div>
                        </form>

                        <form class="tr">
                            <div class="td">1</div>
                            <div class="td">2</div>
                            <div class="td">34567</div>
                            <div class="td">34567</div>
                            <div class="td">4</div>
                            <div class="td action btn-group"><button type="button" onclick="edit(this);" class="btn-outline-primary btn-block  btn">Edit</button> </div>
                        </form>
                        <form class="tr">
                            <div class="td">1234</div>
                            <div class="td">2</div>
                            <div class="td">3</div>
                            <div class="td">4</div>
                            <div class="td">4</div>
                            <div class="td action btn-group"><button type="button" onclick="edit(this);" class="btn-outline-primary btn-block  btn">Edit</button> </div>
                        </form>
                    </div>
                </div>
            </div>

            <em>The width of this jumps around a bit because of the INPUTs but you could fix this by giving a width to each column or the table DIV.</em>
        </div>
        <script>

                function edit(element)
                {
                    var tr = $(element).parent().parent();




                    if(!tr.hasClass("editing"))
                    {
                        tr.addClass("editing");
                        tr.find("DIV.td").each(function()
                        {
                            if(!$(this).hasClass("action"))
                            {
                                var value = $(this).text();
                                $(this).text("");
                                $(this).append('<input type="text" class="form-control form-control-lg" value="'+value+'" />');
                            } else
                            {
                                $(this).find("BUTTON").text("Save");
                            }
                        });
                    } else {
                        tr.removeClass("editing");
                        tr.find("DIV.td").each(function()
                        {
                            if(!$(this).hasClass("action"))
                            {
                                var value = $(this).find("INPUT").val();
                                $(this).text(value);
                                $(this).find("INPUT").remove();
                            } else
                            {
                                $(this).find("BUTTON").text("Edit");
                            }
                        });
                    }
                }


        </script>
    </body>
</html>




