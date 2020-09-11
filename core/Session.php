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

     public static function get_user_agent_without_version() : string {
         $uagent = $_SERVER['HTTP_USER_AGENT'];
         $regx = '/\/[a-zA-Z0-9.]+/';
         $uagent_string = preg_replace($regx, '', $uagent);
         return $uagent_string;
     }

     public static function display_message() : string {
         $alert_types = ['alert-info', 'alert-success', 'alert-warning', 'alert-danger'];

         $html = '';

         foreach($alert_types as $alert_type) {
             if(self::exists($alert_type)) {
                 $html .= '<div class="alert alert-dismissible '.$alert_type.'" role="alert">';
                 $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                 $html .= '<span aria-hidden="true">&times;</span></button>';
                 $html .= self::get($alert_type);
                 $html .= '</div>';
                 self::delete($alert_type);
             }
         }

         return $html;
     }

     /**
      * Sets session alert message
      * @param string $type expects info, success, warning, or danger
      * @param string $message message to display in alert
      */
     public static function set_message(string $type, string $message) : void {
        $session_name = 'alert-'.$type;
        self::set($session_name, $message);
     }
 }