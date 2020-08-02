<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta name="content-type" content="text/html">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="charset" content="UTF-8">
        <title><?= $title ?? ''?></title>
        <meta name="description" content="<?= $description  ?? ''?>">
        <meta name="author" content="<?= $author ?? ''?>">
        <meta name="keywords" content="<?= $keywords ?? ''?>">
        <meta name="creator" content="<?= $creator  ?? ''?>">
        <meta name="robots" content="<?= $robots ?? ''?>">
    </head>
    <body>
        <main class="container">
            <?= $content ?? '' ?>
        </main>
    </body>
</html>