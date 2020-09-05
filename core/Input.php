<?php
    class Input {

        public static function get( string $input) : string {
            if(isset($_POST[$input])) {
                return FormHelper::sanitize($_POST[$input]);
            } else if(isset($_GET[$input])) {
                return FormHelper::sanitize($_GET[$input]);
            }
        }
    }