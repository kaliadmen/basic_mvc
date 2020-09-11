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

        public static function build_checkbox_block(string $label, string $name, bool $checked = false, array $container_attributes = [], array $input_attributes = []) : string {
            $container_attributes = (!empty($container_attributes)) ? self::attributes_to_string($container_attributes) : '';
            $input_attributes = (!empty($input_attributes)) ? self::attributes_to_string($input_attributes) : '';
            $check_string = ($checked) ? ' checked="checked"' : '';

            $html = '<div'.$container_attributes.'>';
            $html .= '<label for="'.$name.'">'.$label.' <input type="checkbox" id="'.$name.'" name="'.$name.'" value="true"'.$check_string.$input_attributes.'></label>';
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

        //Cleans inputs from client side
        public static function sanitize(string $dirty) : string {
            return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
        }

        //Returns array of clean submitted values
        public static function post_values(array $values) : array {
            $arr = [];
            foreach($values as $key => $value) {
                $arr[$key] = self::sanitize($value);
            }

            return $arr;
        }

        public static function display_errors(array $errors) : string {
            $html = '<div class="form-errors"><ul class="bg-danger">';
            foreach($errors as $column => $error) {

                $html .= '<li class="text-white">'.$error.'</li>';

                $html .= '<script>$("document").ready(function() {
                    $("#'.$column.'").addClass("is-invalid");
                });</script>';


            }
            $html .= '</ul></div>';

            return $html;
        }
    }
