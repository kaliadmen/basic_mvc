<?php
    class PasswordValidator extends CustomValidator {

        public function __construct(Model $model, array $params) {
            try {
                parent::__construct($model, $params);
            } catch(Exception $e) {

            }
        }

        public function run_validation(): bool {
            $value = $this->_model->{$this->column};
            $regex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[*.!@$%^&._]).{8,32}$/';

            return (bool)preg_match($regex, $value );
        }
    }