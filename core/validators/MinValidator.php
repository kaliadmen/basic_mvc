<?php
    class MinValidator extends CustomValidator {

        public function __construct(Model $model, array $params) {
            parent::__construct($model, $params);
        }

        public function run_validation() : bool {
            $value = $this->_model->{$this->column};
            return (strlen($value) >= $this->rule);
        }
    }