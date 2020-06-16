<?php
    class Input {

        public static function sanitize(string $dirty) : string {
            return htmlentities($dirty, ENT_QUOTES, "UTF-8");
        }

        public static function get( string $input) : string {
            if(isset($_POST[$input])) {
                return self::sanitize($_POST[$input]);
            } else if(isset($_GET[$input])) {
                return self::sanitize($_GET[$input]);
            }
        }
    }