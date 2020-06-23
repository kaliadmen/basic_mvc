<?php
    //Dump and die for debugging
    if (!function_exists('dd')) {
        function dd() {
            echo '<pre>';
            array_map(function($x) {var_dump($x);}, func_get_args());
            die;
        }
    }

    //Cleans inputs from client side
    if (!function_exists('sanitize'))  {
        function sanitize(string $dirty) : string {
            return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
        }
    }

    //Returns current user logged in
    if (!function_exists('current_user')) {
        function current_user() : Users {
            return Users::get_current_logged_in_user();
        }
    }

    //Returns array of clean submitted values
    if (!function_exists('post_values')) {
        function post_values(array $values) : array {
            $arr = [];
            foreach($values as $key => $value) {
              $arr[$key] = sanitize($value);
            }

            return $arr;
        }
    }