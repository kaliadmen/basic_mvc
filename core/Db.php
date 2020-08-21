<?php

    class Db {

        private static $_instance = null;
        private $_pdo, $_query, $_error = false, $_result, $_count = 0, $_lastInsertID = null;

        private function __construct() {
            try {
                $this->_pdo = new PDO(DB_DRIVER.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
                //set error mode
                if(DEBUG) {
                    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                }
            } catch(PDOException $e) {
                die($e->getMessage());
            }
        }

        //only one instance of Db class can be used
        public static function get_instance() : Db {
            if(!isset(self::$_instance)) {
                self::$_instance = new Db();
            }

            return self::$_instance;
        }

        //gets data from database
        public function query(string $sql, array $params = []) : Db {
            $this->_error = false;
            if($this->_query = $this->_pdo->prepare($sql)) {
                //counter for bind value
                $x = 1;
                if(count($params)) {
                    foreach($params as $param) {
                        $this->_query->bindValue($x, $param);
                        $x++;
                    }
                }
            }

            if($this->_query->execute()) {
                $this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
                $this->_lastInsertID = $this->_pdo->lastInsertId();
            } else {
                $this->_error = true;
            }

            return $this;
        }

        //puts data into database
        public function insert(string $table, array $columns = []) : bool {
            $columnString = '';
            $valueString = '';
            $values = [];

            //build column and value string for insert statement
            foreach($columns as $column => $value) {
                $columnString .= '`'.$column.'`,';
                //values will be bound in query call
                $valueString .='?,';
                $values[] = $value;
            }

            //remove extra commas added from foreach loop
            $columnString = rtrim($columnString, ',');
            $valueString = rtrim($valueString, ',');

            $sql = "INSERT INTO {$table} ({$columnString}) VALUES ({$valueString})";
            if(!$this->query($sql, $values)->error()) {
                return true;
            }
                return false;
        }

        //updates data in database
        public function update(string $table, int $id, array $columns) : bool {
            $columnString = '';
            $values = [];

            //build column and value string for insert statement
            foreach($columns as $column => $value) {
                $columnString .= ' '.$column.'= ?,';
                $values[] = $value;
            }


            $columnString = trim($columnString);
            $columnString = rtrim($columnString, ',');

            $sql = "UPDATE {$table}  SET {$columnString} WHERE id = {$id}";
            if(!$this->query($sql, $values)->error()) {
                return true;
            }
            return false;
        }

        //delete data from database
        public function delete(string $table, int $id) : bool {
            $sql = "DELETE FROM {$table} WHERE id = {$id}";
            if(!$this->query($sql)->error()) {
                return true;
            }
            return false;
        }

        //builds up a condition string for condition query method
        protected function _build_condition_string($conditions) : string {
            $condition_string = '';

            if(is_array($conditions)){
                foreach($conditions as $condition) {
                    $condition_string .= ' '.$condition.' AND';
                }
                $condition_string = trim($condition_string);
                $condition_string = rtrim($condition_string, ' AND');
            }else {
                $condition_string = $conditions;
            }
            if($condition_string != ''){
                $condition_string = ' WHERE '.$condition_string;
                return $condition_string;
            }else{
                return $condition_string;
            }
        }

        //makes a query to passed in table with conditions passed in as parameters
        protected function _condition_query(string $table, array $params = []) : bool {
            $condition_string = '';
            $bind = [];
            $order = '';
            $limit = '';

            //conditions
            if(isset($params['conditions'])) {
                $condition_string = $this->_build_condition_string($params['conditions']);
            }
            //bind
            if(array_key_exists('bind', $params)) {
                $bind =$params['bind'];
            }
            //order
            if(array_key_exists('order', $params)) {
                $order = ' ORDER BY '.$params['order'];
            }
            //limit
            if(array_key_exists('limit', $params)) {
                $limit = ' LIMIT '.$params['limit'];
            }

            $sql = "SELECT * FROM {$table}{$condition_string}{$order}{$limit}";

            if($this->query($sql, $bind)) {
                if(!count($this->_result)) {
                    return false;
                }
                return true;
            }

            return false;
        }

        //finds all records in database table based on passed in parameters
        public function find(string $table, array $params = []) : array {
            if($this->_condition_query($table, $params)) {
                return $this->get_result();
            }
            return ['error' => 'No results found!'];
        }

        //finds first records in database table based on passed in parameters
        public function find_first(string $table, array $params = []) : stdClass {
            if($this->_condition_query($table, $params)) {
                return $this->get_first_result();
            }
            return (object) ['DB-Error' => 'No results found!'];
        }

        //gets results from query
        public function get_result() : array {
            return $this->_result;
        }

        //gets first result from query
        public function get_first_result() : stdClass {
            return (!empty($this->_result)) ? $this->_result[0] : (object) ['error' => 'No results found!'];
        }

        //gets row count from query
        public function get_count() : int {
            return  $this->_count;
        }

        //gets last inserted id
        public function get_last_ld()  : int {
            return (int) $this->_lastInsertID;
        }

        //get columns names from table passed in
        public function get_columns(string $table) : array {
            return $this->query("SHOW COLUMNS FROM {$table}")->get_result();
        }

        //returns if there is an error in query
        public function error() : bool {
            return $this->_error;
        }

    }