<?php
 class Session {

     public static function exists(string $name) : bool {
         return (isset($_SESSION[$name])) ? true : false;
     }

     public static function get(string $name) : string {
         return $_SESSION[$name];
     }

     public static function set(string $name, $value) : string {
         return $_SESSION[$name] = $value;
     }

     public static function delete(string $name) : void {
         if(self::exists($name)) {
             unset($_SESSION[$name]);
         }
     }

     public static function uagent_version() : string {
         $uagent = $_SERVER['HTTP_USER_AGENT'];
         $regx = '/\/[a-zA-Z0-9.]+/';
         $uagent_string = preg_replace($regx, '', $uagent);
         return $uagent_string;
     }
 }