<?php
    abstract class CustomValidator {

        public $valid = true, $message = '', $column, $rule;

        protected $_model;

        public function __construct(Model $model, array $params)  {
            $this->_model = $model;

            if(!array_key_exists('column', $params)) {
                throw new Exception('You must add a "column" to the params array');
            }else {
                $this->column = (is_array($params['column'])) ? $params['column'][0] : $params['column'];
            }

            if(!property_exists($model, $this->column)) {
                throw new Exception('The column must exist in the model');
            }

            if(!array_key_exists('message', $params)) {
                throw new Exception('You must add a "message" to params array');
            } else {
                $this->message = $params['message'];
            }

            if(array_key_exists('rule', $params)) {
                $this->rule = $params['rule'];
            }

            try {
                $this->valid = $this->run_validation();
            } catch(Exception $e) {
                echo('Validation Exception on '.get_class().': '.$e->getMessage().'<br/>');
            }
        }
        
        public function is_valid() : bool {
            return $this->valid;
        }

        abstract public function run_validation() : bool;
    }