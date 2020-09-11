<?php
    class Users extends Model {

        public $id, $username = '', $email = '', $password = '', $first_name = '', $last_name = '', $acl, $deleted = 0;
        private $_isLoggedIn, $_sessionName, $_cookieName, $_confirmed;
        public static $currentLoggedInUser = null;

        public function __construct($user = '') {
            $table = 'users';
            parent::__construct($table);
            $this->_sessionName = CURRENT_USER_SESSION_NAME;
            $this->_cookieName = REMEMBER_ME_COOKIE_NAME;
            $this->_softDelete = true;
            if($user != '') {
                if(is_int($user)) {
                    $u = $this->_db->find_first('users', ['conditions' => 'id = ?', 'bind' => [$user]], 'Users');
                } else {
                    $u = $this->_db->find_first('users', ['conditions' => 'username = ?', 'bind' => [$user]], 'Users');
                }
                
                if($u) {
                    foreach($u as $key => $val) {
                        $this->$key = $val;
                    }
                }
            }
        }

        public static function get_current_user() : Users {
            if(!isset(self::$currentLoggedInUser) && Session::exists(CURRENT_USER_SESSION_NAME)) {
                $User = new Users((int)Session::get(CURRENT_USER_SESSION_NAME));
                self::$currentLoggedInUser = $User;
            }else if(self::$currentLoggedInUser == null) {
                self::$currentLoggedInUser = new Users();
            }

            return self::$currentLoggedInUser;
        }

        public static function login_user_from_cookie() : Users {
            $user_session = UsersSessions::get_cookie_data();

            if($user_session && $user_session->user_id != '') {
                $user = new self((int)$user_session->user_id);

                if($user) $user->login();

                return $user;
            }

            return new Users();
        }

        public function find_by_username(string $username) : Users {
            return $this->find_first(['conditions' => 'username = ?', 'bind' => [$username]]);
        }

        public function validator(): void {
            $this->run_validation(new RequiredValidator($this, ['column' => 'first_name', 'message' => 'First Name is required']));

            $this->run_validation(new RequiredValidator($this, ['column' => 'last_name', 'message' => 'Last Name is required']));

            $this->run_validation(new RequiredValidator($this, ['column' => 'email', 'message' => 'Email is required']));

            $this->run_validation(new EmailValidator($this, ['column' => 'email', 'message' => 'Email must be a valid email']));

            $this->run_validation(new RequiredValidator($this, ['column' => 'password', 'message' => 'Password is required']));

            $this->run_validation(new MatchValidator($this, ['column' => 'password', 'rule' => $this->_confirmed, 'message' => 'Passwords do not match']));

            $this->run_validation(new MinValidator($this, [
                'column' => 'username',
                'rule' => 6,
                'message' => 'Username must be at least 6 characters.'
                ]));

            $this->run_validation(new MinValidator($this, [
                'column' => 'password',
                'rule' => 8,
                'message' => 'Password must be at least 8 characters.'
            ]));

            $this->run_validation(new MaxValidator($this, [
                'column' => 'email',
                'rule' => 150,
                'message' => 'Email must be less than 151 characters.'
            ]));

            $this->run_validation(new MaxValidator($this, [
                'column' => 'password',
                'rule' => 32,
                'message' => 'Password must be less than 151 characters.'
            ]));

            $this->run_validation(new MaxValidator($this, [
                'column' => 'username',
                'rule' => 150,
                'message' => 'Username must be less than 16 characters.'
            ]));

            $this->run_validation(new UniqueValidator($this, ['column' => 'username', 'message' => 'Username is taken']));

            $this->run_validation(new UniqueValidator($this, ['column' => 'email', 'message' => 'Email is taken']));

            $this->run_validation(new PasswordValidator($this, ['column' => 'password', 'message' => 'Password must contain at least one lowercase, uppercase, special character and number']));
        }

        public function login(bool $remember_me = false) : void {
            Session::set($this->_sessionName, $this->id);

            if($remember_me) {
                $hash = md5(uniqid(rand(0, 100), true));
                $user_agent = Session::get_user_agent_without_version();

                Cookie::set($this->_cookieName, $hash, REMEMBER_ME_COOKIE_EXPIRE);

                $query_data = ['session' => $hash, 'user_agent' => $user_agent, 'user_id' => $this->id];

                //remove old session from database
                $this->_db->query("DELETE FROM users_sessions WHERE user_id = ? AND user_agent = ?", [$this->id, $user_agent]);

                //add new session to database
                $this->_db->insert('users_sessions', $query_data);
            }
        }

        public function logout() : bool {
            $user_agent = Session::get_user_agent_without_version();
            $user_session = UsersSessions::get_cookie_data();

            if($user_session->user_id !== null) {
                $user_session->delete($user_session->user_id);
            }

            $this->_db->query("DELETE FROM users_sessions WHERE user_id = ? AND user_agent = ?",[$this->id, $user_agent]);

            Session::delete(CURRENT_USER_SESSION_NAME);

            if(Cookie::exists(REMEMBER_ME_COOKIE_NAME)) {
                Cookie::delete(REMEMBER_ME_COOKIE_NAME);
            }

            self::$currentLoggedInUser = null;
            return true;
        }

        public function before_save() : void {
            $this->password = password_hash($this->password, PASSWORD_BCRYPT, array(
                'cost' => '12'));
        }

        public function get_acls() {
            if(empty($this->acl)) return [];
            return json_decode($this->acl, true);
        }

        public function get_confirmed() : string {
            return $this->_confirmed;
        }

        public function set_confirmed(string $value): void {
            $this->_confirmed = $value;
        }
    }