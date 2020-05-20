<?php
    class Router{
        public static function route($url) {
            //controller
            $controller = (isset($url[0]) && $url[0] != '') ?  ucwords($url[0]) : DEFAULT_CONTROLLER;
            $controller_name = $controller;
            array_shift($url); //remove controller from url array

            //action
            $action = (isset($url[0]) && $url[0] != '') ?  ($url[0]).'Action' : 'indexAction';
            $action_name = $controller;
            array_shift($url); //remove controller from action array

            //parameters
            $queryParams = $url;

            $dispatch = new $controller($controller_name, $action); //instantiate controller object

            //execute controller method
            if(method_exists($controller, $action)){
                call_user_func_array([$dispatch, $action], $queryParams);
            } else {
                die('That method does not exist in the controller "' .$controller_name. '"');
            }
        }
    }