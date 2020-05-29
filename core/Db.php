<?php
//database class using singleton pattern.
    class Db {

        private static $_instance = null;
        private $_pdo, $_query, $_error = false, $_result, $_count = 0, $_lastInsertID = null;

        private function __construct() {
            try {
                $this->_pdo = new PDO(DB_DRIVER.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
            } catch(PDOException $e) {
                die($e->getMessage());
            }
        }

        //only one instance of Db class can be used
        public static function getInstance() : Db {
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

        public function error() : bool {
            return $this->_error;
        }

    }