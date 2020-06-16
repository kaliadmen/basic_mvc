<?php
    class Cookie {

        public static function exists(string $name) : bool {
            return isset($_COOKIE[$name]);
        }

        public static function get(string $name) : string {
            return $_COOKIE[$name];
        }

        public static function set(string $name, $value, int $expire) : bool {
            if(setcookie($name, $value, time() + $expire, '/')) {
                return true;
            }

            return false;
        }

        public static function delete(string $name) : void {
            self::set($name, '', time() - 1);
        }

    }