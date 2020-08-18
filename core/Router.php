<?php
    class Router {

        public static function route(array $url) : void{
            //controller
            $controller = (isset($url[0]) && $url[0] != '') ?  ucwords($url[0]) : DEFAULT_CONTROLLER;
            $controller_name = $controller;
            $controllerLocation = ROOT.DS.'app'.DS.'controllers'.DS.$controller_name.'.php';
            array_shift($url); //remove controller from url array

            //action
            $action = (isset($url[0]) && $url[0] != '') ? ($url[0]).'Action' : 'indexAction';
            $action_name = (isset($url[0]) && $url[0] != '') ? $url[0] : 'index';
            array_shift($url); //remove action from url array

            //check acl
            $grant_access = self::hasAccess($controller, $action_name);

            if(!$grant_access) {
                $called_controller = $controller_name;
                $controller_name = $controller = ACCESS_RESTRICTED;
                $action = 'indexAction';
            }

            //parameters
            $queryParams = $url;

            if (!file_exists($controllerLocation)) { //check if controller called exists
                die('This controller does not exist "'.$called_controller.'"');
            }else {
                $dispatch = new $controller_name($controller_name, $action); //instantiate controller object
            }

            //execute controller method
            if(method_exists($controller_name, $action)){
                call_user_func_array([$dispatch, $action], $queryParams);
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

        public static function hasAccess(string $controller_name, string $action_name = 'index') : bool {
            $acl_file = file_get_contents(ROOT.DS.'app'.DS.'acl.json');
            $acl = json_decode($acl_file, true);
            $current_user_acls = ['Guest'];
            $access_granted = false;

            if(Session::exists(CURRENT_USER_SESSION_NAME)) {
                $current_user_acls[] = 'LoggedIn';

                foreach(current_user()->get_acls() as $user_acl) {
                    $current_user_acls[] = $user_acl;
                }
            }

            //check controller access
            foreach($current_user_acls as $access_level) {
                if(array_key_exists($access_level, $acl) &&
                    array_key_exists($controller_name, $acl[$access_level])) {

                    if(in_array($action_name, $acl[$access_level][$controller_name]) ||
                        in_array('*', $acl[$access_level][$controller_name])) {

                        $access_granted = true;
                        break;
                    }
                }
            }

            //check if denied
            foreach($current_user_acls as $access_level) {
                $denied = $acl[$access_level]['denied'];

                if(!empty($denied) &&
                    array_key_exists($controller_name, $denied) &&
                    in_array($action_name, $denied[$controller_name])) {

                    $access_granted = false;
                    break;
                }
            }

            return $access_granted;
        }
    }