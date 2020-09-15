<?php
use Core\Router;
use Core\Session;
use Core\Cookie;
use App\Models\Users;


//use '/' when in Windows or use default DIRECTORY_SEPARATOR on non Windows
(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? define('DS', '/') : define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

    //load configuration
    require_once(ROOT.DS.'config'.DS.'config.php');


    //autoload classes
    function autoload(string $class_name) : void {
        $class_array = explode('\\', $class_name);
        $class = array_pop($class_array);
        $sub_path = strtolower(implode(DS, $class_array));
        $path = ROOT.DS.$sub_path.DS.$class.'.php';

        if(file_exists($path)) {
            require_once($path);
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

