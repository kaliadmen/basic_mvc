<?php
    namespace Core\Validators;
    use Core\Validators\BaseValidator;

    class EmailValidator extends BaseValidator {

        public function __construct($model, array $params) {
            try {
                parent::__construct($model, $params);
            } catch(Exception $e) {
            }
        }

        public function run_validation(): bool {
            $email = $this->_model->{$this->column};

            if(!empty($email)) return filter_var($email, FILTER_VALIDATE_EMAIL);

            return false;
        }
    }