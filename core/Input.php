<?php
    class Input {

        public function is_post() : bool {
            return $this->get_request_method() === 'POST';
        }

        public function is_get() : bool {
            return $this->get_request_method() === 'GET';
        }

        public function is_put() : bool {
            return $this->get_request_method() === 'PUT';
        }

        public function is_delete() : bool {
            return $this->get_request_method() === 'DELETE';
        }

        public function get_request_method() : string {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }

        public function get( string $input = '') : array {
            if(!(bool)$input) {
                // return sanitized request array
                $data = [];
                foreach($_REQUEST as $column => $value) {
                    $data[$column] = FormHelper::sanitize($value);
                }
                return $data;
            }

            return (array)FormHelper::sanitize($_REQUEST[$input]);
        }

        public function has_valid_csrf_token() : bool {
            if(!FormHelper::validate_token($this->get('csrf_token')[0])) {
                Router::redirect('restricted/csrf');
                return false;
            }
            return true;
        }
    }