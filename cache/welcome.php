<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>welcome</title>

        <meta name="description" content="welcome">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>
    <body>
        <main class="container">
            <?= ioc('flash')->call('display'); ?>
            <div class="text-center mt-5">
    <h1>welcome</h1>
</div>
        </main>
    </body>
</html>