<?php
    class Router {

        public static function route(array $url) : void{
            //controller
            $controller = (isset($url[0]) && $url[0] != '') ?  ucwords($url[0]).'Controller' : DEFAULT_CONTROLLER.'Controller';
            $controller_name = str_replace('Controller','',$controller);
            $controller_location = ROOT.DS.'app'.DS.'controllers'.DS.$controller.'.php';
            array_shift($url); //remove controller from url array

            //action
            $action = (isset($url[0]) && $url[0] != '') ? ($url[0]).'Action' : 'indexAction';
            $action_name = (isset($url[0]) && $url[0] != '') ? $url[0] : 'index';
            array_shift($url); //remove action from url array

            //check acl
            $grant_access = self::has_access($controller_name, $action_name);

            if(!$grant_access) {
                $controller = ACCESS_RESTRICTED.'Controller';
                $called_controller = $controller_name;
                $controller_name = ACCESS_RESTRICTED;
                $action = 'indexAction';
            }

            //parameters
            $queryParams = $url;

            if (!file_exists($controller_location)) { //check if controller called exists
                die('This controller does not exist "'.$called_controller.'"');
            }else {
                $dispatch = new $controller($controller_name, $action); //instantiate controller object
            }
            //execute controller method
            if(method_exists($controller, $action)){
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

        public static function has_access(string $controller_name, string $action_name = 'index') : bool {
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

        public static function get_menu(string $menu) : array{
            $menu_array = [];
            $menu_file = file_get_contents(ROOT.DS.'app'.DS.$menu.'.json');
            $acl = json_decode($menu_file, true);

            foreach($acl as $key => $value) {
                if(is_array($value)) {
                    $sub_menu = [];
                    foreach($value as $k => $val) {
                        if($k == 'separator' && !empty($sub_menu)) {
                            $sub_menu[$k] = '';
                            continue;
                        } else if($final_val = self::get_link($val)) {
                            $sub_menu[$k] = $final_val;
                        }
                    }
                    if(!empty($sub_menu)) {
                        $menu_array[$key] = $sub_menu;
                    }
                }else {
                    if($final_val = self::get_link($value)) {
                        $menu_array[$key] = $final_val;
                    }
                }
            }

            return $menu_array;
        }

        private static function get_link(string $value) : string {
            //check for external link
            if(preg_match('/https?:\/\//', $value) == 1) {
                return $value;
            } else {
                //build link
                $url_array = explode(DS, $value);
                $controller_name = ucwords($url_array[0]);
                $action_name = (isset($url_array[1])) ? $url_array[1] : '';
                //check for access
                if(self::has_access($controller_name, $action_name)) {
                    return PROJECTROOT.$value;
                }
                return '';
            }
        }
    }