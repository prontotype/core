<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = urldecode($uri);

$requested = getcwd().$uri;

if ($uri !== '/' && file_exists($requested) && ! is_dir($requested) )
{
    return false;
}

require_once getcwd() . '/index.php';