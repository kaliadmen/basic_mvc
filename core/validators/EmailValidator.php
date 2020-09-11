<?php
    class EmailValidator extends CustomValidator {

        public function __construct(Model $model, array $params) {
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