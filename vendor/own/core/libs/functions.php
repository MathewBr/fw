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

function debug($arr){
    $steck = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,1);
    echo '<br><pre style="border: solid thin rebeccapurple; display: inline-block; padding: 3px" xmlns="http://www.w3.org/1999/html">'
        . '<span style="color: red; background-color: beige; padding: 0 3px">'
        .  $steck[0]['file']
        . '</span>'
        . '<span style="color: red; background-color: mediumspringgreen; padding: 0 3px">'
        . $steck[0]['function']
        . '</span>'
        . '<span style="color: red; background-color: yellow; padding: 0 3px">'
        . $steck[0]['line']
        . '</span>'
        . '</br>'
        . print_r($arr, true)
        . '</pre></br>';
}

