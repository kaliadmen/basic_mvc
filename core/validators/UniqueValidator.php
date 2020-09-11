<?php
    class UniqueValidator extends CustomValidator {

        public function __construct(Model $model, array $params) {
            try {
                parent::__construct($model, $params);
            } catch(Exception $e) {

            }
        }

        public function run_validation(): bool {
            $column = (is_array($this->column)) ? $this->column[0] : $this->column;
            $value = $this->_model->{$column};

            $conditions = ["{$column} = ?"];
            $bind = [$value];

            //check if updating a record
            if(!empty($this->_model->id)) {
                $conditions[] = "id = ?";
                $bind[] = $this->_model->id;
            }

            //check multiple columns for uniqueness
            if(is_array($this->column)) {
                array_unshift($this->column);

                foreach($this->column as $item) {
                    $conditions[] = "{$item} = ?";
                    $bind[] = $this->_model->{$item};
                }
            }

            $query_params = ['conditions' => $conditions, 'bind' => $bind];
            $previous_record = $this->_model->find_first($query_params);

            return (!property_exists($previous_record, 'id'));
        }
    }