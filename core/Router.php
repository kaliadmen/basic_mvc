<?php
    class Router {

        public static function route(array $url) : void{
            //controller
            $controller_name = (isset($url[0]) && $url[0] != '') ?  ucwords($url[0]) : DEFAULT_CONTROLLER;
            $controllerLocation = ROOT.DS.'app'.DS.'controllers'.DS.$controller_name.'.php';
            array_shift($url); //remove controller from url array

            //action
            $action_name = (isset($url[0]) && $url[0] != '') ?  ($url[0]).'Action' : 'indexAction';
            array_shift($url); //remove action from url array

            //parameters
            $queryParams = $url;

            if (!file_exists($controllerLocation)) { //check if controller called exists
                die('This controller does not exist "'.$controller_name.'"');
            }else {
                $dispatch = new $controller_name($controller_name, $action_name); //instantiate controller object
            }

            //execute controller method
            if(method_exists($controller_name, $action_name)){
                call_user_func_array([$dispatch, $action_name], $queryParams);
            } else {
                die('That method does not exist in the controller "'.$controller_name.'"');
            }
        }

        public static function redirect(string $location) {
            if(!headers_sent()) {
                header('Location: '.PROJECTROOT.$location);
                exit();
            }else {
                echo('<script type="text/javascript>"');
                echo('window.location.href="'.PROJECTROOT.$location.'";');
                echo('</script>');
                echo('<noscript>');
                echo('<meta http-equiv="refresh" content="0;url='.$location.'" />');
                echo('</noscript>');
                exit();
            }
        }
    }