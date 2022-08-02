<?php

function getAppURL(){
    //http or https
    $protocol = isset($_SERVER['HTTP_SCHEME']) ? preg_replace('#[/:]#', '', $_SERVER['HTTP_SCHEME']) :
        (((isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off') || 443 == $_SERVER['SERVER_PORT']) ? 'https' : 'http');
    //fw:8080/public/index.php
    $path = "{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
    //fw:8080/public/
    $path = preg_replace('#[^/]+$#', '', $path);
    //fw:8080
    $path = str_replace('/public/', '', $path);
    return $protocol . '://' . $path;
}