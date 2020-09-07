<?php
//use '/' when in Windows or use default DIRECTORY_SEPARATOR on non Windows
(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? define('DS', '/') : define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

    //load configuration
    require_once(ROOT.DS.'config'.DS.'config.php');


    //autoload classes
    function autoload($className) {
        if(file_exists(ROOT.DS.'core'.DS.$className.'.php')){
            require_once(ROOT.DS.'core'.DS.$className.'.php');
        }elseif(file_exists(ROOT.DS.'app'.DS.'controllers'.DS.$className.'.php')){
            require_once(ROOT.DS.'app'.DS.'controllers'.DS.$className.'.php');
        }elseif(file_exists(ROOT.DS.'app'.DS.'models'.DS.$className.'.php')){
            require_once(ROOT.DS.'app'.DS.'models'.DS.$className.'.php');
        }elseif(file_exists(ROOT.DS.'app'.DS.'custom_validators'.DS.$className.'.php')){
            require_once(ROOT.DS.'app'.DS.'custom_validators'.DS.$className.'.php');
        }elseif(file_exists(ROOT.DS.'core'.DS.'validators'.DS.$className.'.php')){
            require_once(ROOT.DS.'core'.DS.'validators'.DS.$className.'.php');
        }
    }

    spl_autoload_register('autoload');
    session_start();

    //Create array from url
    $url = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'], '/')) : [];

    if(!Session::exists(CURRENT_USER_SESSION_NAME) && Cookie::exists(REMEMBER_ME_COOKIE_NAME)) {
        Users::login_user_from_cookie();
    }

    //route requests
    Router::route($url);

