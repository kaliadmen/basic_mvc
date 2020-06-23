<?php
    class Validate {

        private $_valid = false, $_errors = [], $_db = null;

        public function __construct() {
            $this->_db = DB::get_instance();
        }

        public function validation(array $source, array $items = []) : Validate {
            $this->_errors = [];

            foreach($items as $item => $rules) {
                $item = Input::sanitize($item);
                $display = $rules['display'];

                foreach($rules as $rule => $rule_value) {
                    $value = Input::sanitize(trim($source[$item]));
                    if($rule === 'required' && empty($value)) {
                        $this->add_error(["{$display} is required", $item]);
                    } else if (!empty($value)) {
                        switch($rule) {
                            case 'min':
                                if(strlen($value) < $rule_value) {
                                    $this->add_error(["{$display} must be a minimum of {$rule_value} characters.", $item]);
                                }
                                break;

                            case 'max':
                                if(strlen($value) > $rule_value) {
                                    $this->add_error(["{$display} must be a maximum of {$rule_value} characters.", $item]);
                                }
                                break;

                            case 'matches':
                                if($value != $source[$rule_value]) {
                                    $match_display = $items[$rule_value]['display'];
                                    $this->add_error(["{$match_display} and {$display} must match.", $item]);
                                }
                                break;
                            case 'unique':
                                $query = $this->_db->query("SELECT {$item} FROM {$rule_value} WHERE {$item} = ?", [$value]);
                                if($query->get_count()) {
                                    $this->add_error(["{$display} already exists. Please choose another {$display}.", $item]);
                                }
                                break;

                            case 'unique_update':
                                $extract_values = explode(',', $rule_value);
                                $table = $extract_values[0];
                                $id = $extract_values[1];

                                $query = $this->_db->query("SELECT * FROM {$table} WHERE id != ? AND {$item} = ?", [$id, $value]);
                                if($query->get_count()) {
                                    $this->add_error(["{$display} already exists. Please choose another {$display}.", $item]);
                                }
                                break;
                            case 'is_numeric':
                                if(is_numeric($value)) {
                                    $this->add_error(["{$display} has to be a number. Please use a numeric value.", $item]);
                                }
                                break;
                            case 'valid_email':
                                if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                    $this->add_error(["{$display} must be a valid email address.", $item]);
                                }
                                break;
                            default:
                                //something
                        }
                    }
                }
            }
            if(empty($this->_errors)) {
                $this->_valid = true;
            }
            return $this;
        }

        public function is_valid() : bool {
            return $this->_valid;
        }

        public function get_errors() : array {
            return $this->_errors;
        }

        public function display_errors() : string {
            $html = '<ul class="bg-danger">';
            foreach($this->_errors as $error) {
                if(is_array($error)){
                    $html .= '<li class="text-white">'.$error[0].'</li>';
                    $html .= '<script>$("document").ready(function() {
                  $("#'.$error[1].'").addClass("is-invalid");
                });</script>';
                }else {
                    $html .= '<li class="text-white">'.$error.'</li>';
                }

            }
            $html .= '</ul>';

            return $html;
        }

        public function add_error($error) : void {
            $this->_errors[] = $error;
            if(empty($this->_errors)) {
                $this->_valid = true;
            }

            $this->_valid = false;
        }


    }