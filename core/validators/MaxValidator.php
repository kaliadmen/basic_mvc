<?php
class MaxValidator extends CustomValidator {

    public function __construct(Model $model, array $params) {
        try {
            parent::__construct($model, $params);
        } catch(Exception $e) {

        }
    }

    public function run_validation() : bool {
        $value = $this->_model->{$this->column};
        return (strlen($value) <= $this->rule);
    }
}