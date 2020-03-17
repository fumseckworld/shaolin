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
        @js(app)
    </body>
</html>