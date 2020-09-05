<?php
    class Helper {

        //Dump and die for debugging
        public static function dd() {
            echo '<pre>';
            array_map(function($x) {var_dump($x);}, func_get_args());
            die;
        }

        //Returns current page being viewed
        public static function current_page() : string {
            $current_page = $_SERVER['REQUEST_URI'];
            if($current_page == PROJECTROOT || $current_page == PROJECTROOT.'/home/index') {
                $current_page = PROJECTROOT.'home';
            }
            return $current_page;
        }

        //Displays menu on pages excluding ones with excluded string passed in
        public static function display_menu(string $page, string $excluding) : bool {
            if((strpos($page, $excluding) == true)) {
                return false;
            }

            return true;
        }

        public static function get_object_properties(object $obj) : array {
            return get_object_vars($obj);
        }

    }