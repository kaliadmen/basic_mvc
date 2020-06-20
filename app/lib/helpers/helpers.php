<?php
    //Dump and die for debugging
    if (!function_exists('dd')) {
        function dd() {
            echo '<pre>';
            array_map(function($x) {var_dump($x);}, func_get_args());
            die;
        }
    }

    if (!function_exists('sanitize'))  {
        function sanitize(string $dirty) : string {
            return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
    }

        if (!function_exists('get_current_user')) {
            function get_current_user() : Users {
                return Users::current_logged_in_user();
            }
        }
}