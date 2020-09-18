<?php
    namespace App\Models;
    use Core\Model;
    use Core\Validators\RequiredValidator;

    class Login extends Model {

        public $username = '', $password = '', $remember_me;

        public function __construct() {
            parent::__construct('pseudo');
        }

        public function validator() : void {
            $this->run_validation(new RequiredValidator($this, ['column' => 'username', 'message' => 'Username is required']));
            
            $this->run_validation(new RequiredValidator($this, ['column' => 'password', 'message' => 'Password is required']));
        }

        public function get_remember_me() : bool {
            return $this->remember_me == true;
        }
    }