<?php
use fw\App;

require_once dirname(__DIR__) . '/config/init.php';

new App();

App::$appConteiner->writeParameters('test', 'TEST');

throw new Exception('Страница не найдена', 404);
