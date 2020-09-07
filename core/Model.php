<?php
class Model {

    protected $_db, $_table, $_modelName, $_softDelete = false, $_validate = true, $_validation_errors = [];
    public $id;

    public function __construct(string $table) {
        $this->_db = DB::get_instance();
        $this->_table = $table;

        $this->_modelName = str_replace(
            ' ', '', 
            ucwords(
                str_replace(
                    '_', ' ', $this->_table
                )
            )
        );
    }

    public function find(array $params = []) : array {
        $params = $this->_soft_delete_params($params);
        $result_query = $this->_db->find($this->_table, $params, get_class($this));

        if(!$result_query) return [];
        return  $result_query;

    }

    public function find_first(array $params = []) : Model {
        $params = $this->_soft_delete_params($params);
        $result_query = $this->_db->find_first($this->_table, $params, get_class($this));

        return $result_query;


    }

    public function find_by_id(int $id) : stdClass {
        return $this->find_first(['conditions' => 'id = ?', 'bind' => [$id]]);
    }

    public function assign(array $params) : void {
        if(!empty($params)) {
            foreach($params as $key => $val) {
                if(property_exists($this, $key)) {
                    $this->$key = FormHelper::sanitize($val);
                }
            }
        }
    }

    public function data() : stdClass {
       $data = new stdClass();
       foreach(Helper::get_object_properties($this) as $property => $value) {
           $data->property = $value;
       }

       return $data;
    }

    public function insert(array $columns) : bool {
        if(empty($columns)) {
            return false;
        }
        return $this->_db->insert($this->_table, $columns);
    }

    public function query(string $sql, array $bind = []) : Db {
        return $this->_db->query($sql, $bind);
    }

    public function update(int $id, array $columns) : bool {
        if(empty($columns) || $id == '') {
            return false;
        }
        return $this->_db->update($this->_table, $id, $columns);
    }

    public function save() : bool {
        $this->validator();

        if($this->_validate) {
            $columns = Helper::get_object_properties($this);

            //determines if to update or insert
            if(property_exists($this, 'id') && $this->id != '') {
                return $this->update($this->id, $columns);
            }else {
                return $this->insert($columns);
            }
        }

        return false;
    }

    public function delete(int $id) : bool {
        if($id == '' && $this->id == '') {
            return false;
        }

        $id = ($id == '') ? $this->id : $id;

        if($this->_softDelete) {
            return $this->update($id, ['deleted' => 1]);
        }
        return $this->_db->delete($this->_table, $id);
    }

    public function get_columns() : array {
        return $this->_db->get_columns($this->_table);
    }

    public function get_error_messages() : array{
        return $this->_validation_errors;
    }

    public function get_validation_status() : bool{
        return $this->_validate;
    }

    public function set_error_message(string $error, string $message) : void {
        $this->_validate = false;
        $this->_validation_errors[$error] = $message;
    }

    public function validator() : void {}

    public function run_validation(CustomValidator $validator) : bool {
        $key = $validator->column;

        if(!$validator->is_valid()) {
            $this->_validate = false;
            $this->_validation_errors[$key] = $validator->message;
            return false;
        }

        return true;
    }

    protected function _populate_object_data(Object $result) : void {
        foreach($result as $key => $val) {
            $this->$key = $val;
        }
    }

    protected function _soft_delete_params(array $params) : array {
        if($this->_softDelete){
            if(array_key_exists('conditions', $params)) {
                if(is_array($params['conditions'])) {
                    $params['conditions'][] = "deleted BETWEEN -1 AND 0";
                }
                else {
                    $params['conditions'] .= " AND deleted BETWEEN -1 AND 0";
                }
            }
            else {
                $params['conditions'] = "deleted BETWEEN -1 AND 0";
            }
        }
        return $params;
    }
}