<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
<!--    <title>Document</title>-->
    <?=$insertedMeta?>
</head>
<body>
    <h1>Это шаблон по-умолчанию</h1>

    <?=$content?>

    <?php
        $logs = \R::getDatabaseAdapter()->getDatabase()->getLogger();
        debug($logs->grep('SELECT'), true, "redBeanPHP");
    ?>

</body>
</html>