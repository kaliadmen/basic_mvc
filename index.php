<?php

    session_start();

    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT', dirname(__FILE__));

    //Create array from url
    $url = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'], '/')) : [];

    require_once(ROOT.DS.'core'.DS.'bootstrap.php');

