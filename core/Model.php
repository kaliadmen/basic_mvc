<?php
class Model {

    protected $_db, $_table, $_modelName, $_softDelete = false, $_columnNames = [];
    public $id;

    public function __construct(string $table) {
        $this->_db = DB::get_instance();
        $this->_table = $table;
        $this->_set_table_columns();
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
        $results = [];
        $resultsQuery = $this->_db->find($this->_table, $params);

        if(!$resultsQuery) return [];

        //loop through results
        foreach($resultsQuery as $result) {
            //create a new object
            $obj = new $this->_modelName($this->_table);
            $obj->_populate_object_data($result);
            $results[] = $obj;
        }
        //return array of objects instantiated from model class
        return  $results;

    }

    public function find_first(array $params = []) : Model {
        $params = $this->_soft_delete_params($params);
        $result_query = $this->_db->find_first($this->_table, $params);
        $result = new $this->_modelName($this->_table);
        if($result_query) {
            $result->_populate_object_data($result_query);
        }
        return $result;


    }

    public function find_by_id(int $id) : stdClass {
        return $this->find_first(['conditions' => 'id = ?', 'bind' => [$id]]);
    }

    public function assign(array $params) : void {
        if(!empty($params)) {
            foreach($params as $key => $val) {
                if(in_array($key, $this->_columnNames)) {
                    $this->$key = sanitize($val);
                }
            }
        }
    }

    public function data() : stdClass {
       $data = new stdClass();
       foreach($this->_columnNames as $columnName) {
           $data->column = $this->column;
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
        $columns = [];

        foreach($this->_columnNames as $columnName) {
            $columns[$columnName] = $this->$columnName;
        }
        //determines if to update or insert
        if(property_exists($this, 'id') && $this->id != '') {
            return $this->update($this->id, $columns);
        }else {
            return $this->insert($columns);
        }
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

    protected function _set_table_columns() : void {
        $columns = $this->get_columns();

        foreach($columns as $column) {
            $columnName = $column->Field;
            $this->_columnNames[] = $column->Field;
            $this->{$columnName} = null;
        }
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