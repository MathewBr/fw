<?php

define("DEBUG", 1);
define("ROOT", dirname(__DIR__));
define("WWW", ROOT . '/public');
define("APP", ROOT . '/app');
define("VIEWS", ROOT . '/app/views');
define("WIDGETS", ROOT . '/app/widgets');
define("CORE", ROOT . '/vendor/own/core');
define("LIBS", ROOT . '/vendor/own/core/libs');
define("CACHE", ROOT . '/tmp/cache');
define("CONF", ROOT . '/config');
define("DEF_LAY", 'watches');

require_once LIBS . '/functions.php';

define("PATH", getAppURL());
define("ADMIN", PATH . '/admin');

require_once ROOT . '/vendor/autoload.php';
