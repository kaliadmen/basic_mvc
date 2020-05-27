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

        //Only one instance of Db class can be used
        public static function getInstance() : Db {
            if(!isset(self::$_instance)) {
                self::$_instance = new Db();
            }

            return self::$_instance;
        }

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

    }