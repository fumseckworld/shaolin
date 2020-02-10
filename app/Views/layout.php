<!DOCTYPE html>
<html lang="<?= $lang ?? 'en' ?>">
    <head>
        <title><?= $title ?? '' ?></title>

        <meta name="description" content="<?= $description ?? '' ?>">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='shortcut icon' href='/favicon.ico'/>
        @css(app)
    </head>
    <body>
        <main class="container">
            @flash
            <?= $content ?? ''?>
        </main>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        @js(app)
    </body>
</html>