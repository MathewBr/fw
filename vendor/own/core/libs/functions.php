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

function redirect($http = false){
    if($http){
        $redirect = $http;
    }else{
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
    }
    header("Location: $redirect");
    exit();
}

function debug($arr, $die = false, $htmlspecialchars = true, $massage = ''){
    $steck = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,1);
    $out = print_r($arr, true);
    if (!$arr){
        ob_start();
        var_dump($arr);
        $out = ob_get_clean();
    }
    if ($htmlspecialchars) $out = htmlspecialchars($out, ENT_QUOTES);
    echo '<br><pre style="border: solid thin rebeccapurple; display: inline-block; padding: 3px" xmlns="http://www.w3.org/1999/html">'
        . '<span style="color: red; background-color: beige; padding: 0 3px">'
        .  $steck[0]['file']
        . '</span>'
        . '<span style="color: black; background-color: mediumspringgreen; padding: 0 3px">'
        . $steck[0]['function']
        . '</span>'
        . '<span style="color: black; font-weight: bold; background-color: yellow; padding: 0 3px">'
        . $steck[0]['line']
        . '</span>'
        . '<span style="color: black; font-weight: bold; background-color: powderblue; padding: 0 3px">'
        . $massage
        . '</span>'
        . '</br>'
        . $out
        . '</pre></br>';
    if ($die) die("Завершено");
}

function h($str){
    return htmlspecialchars($str, ENT_QUOTES);
}

