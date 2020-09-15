<?php
    namespace Core\Validators;
    use Core\Validators\BaseValidator;
    use Exception;

    class NumericValidator extends BaseValidator {

        public function __construct($model, array $params) {
            try {
                parent::__construct($model, $params);
            } catch(Exception $e) {

            }
        }

        public function run_validation(): bool {
            $value = $this->_model->{$this->column};

            if(!empty($value)) return is_numeric($value);

            return false;
        }
    }