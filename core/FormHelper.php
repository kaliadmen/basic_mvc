<?php

    class FormHelper {
        public static function build_input(string $type, string $name, string $label, string $default_value = '',
                             array $container_attributes = [], array $input_attributes = []) : string {

            $container_attributes = (!empty($container_attributes)) ? self::attributes_to_string($container_attributes) : '';
            $input_attributes = (!empty($input_attributes)) ? self::attributes_to_string($input_attributes) : '';

            $html = '<div'.$container_attributes.'>';
            $html .= '<label for="'.$name.'">'.$label.'</label>';
            $html .= '<input id="'.$name.'" type ="'.$type.'" name="'.$name.'" value="'.$default_value.'" '.$input_attributes.' />';
            $html .= '</div>';

            return $html;
        }

        public static function build_submit(string $value, array $input_attributes = []) :string {
            $input_attributes = (!empty($input_attributes)) ? self::attributes_to_string($input_attributes) : '';

            $html = '<input type="submit" value="'.$value.'" '.$input_attributes.' />';

            return $html;
        }

        public static function build_submit_block(string $value, array $container_attributes = [], array $input_attributes = []) :string {
            $container_attributes = (!empty($container_attributes)) ? self::attributes_to_string($container_attributes) : '';
            $input_attributes = (!empty($input_attributes)) ? self::attributes_to_string($input_attributes) : '';

            $html = '<div'.$container_attributes.'>';
            $html .= '<input type="submit" value="'.$value.'" '.$input_attributes.' />';
            $html .= '</div>';

            return $html;
        }

        public static function attributes_to_string(array $attributes) : string {
            $string = '';

            foreach($attributes as $key => $value) {
                $string .= ' '.$key.'= "'.$value.'"';
            }

            return $string;
        }

        public static function generate_token() : string {
            $token = base64_encode(openssl_random_pseudo_bytes(32));
            Session::set('csrf_token', $token);

            return $token;
        }

        public static function validate_token(string $token) : bool {
            return (Session::exists('csrf_token') && Session::get('csrf_token') == $token);
        }

        public static function generate_csrf_input() : string {
            return '<input type="hidden" name="csrf_token" id="csrf_token" value="'.self::generate_token().'"/>';
        }
    }
