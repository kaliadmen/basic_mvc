<?php
    class Users extends Model {

        private $_isLoggedIn, $_sessionName, $_cookieName;
        public static $currentLoggedInUser = null;

        public function __construct($user = '') {
            $table = 'users';
            parent::__construct($table);
            $this->_sessionName = CURRENT_USER_SESSION_NAME;
            $this->_cookieName = REMEMBER_ME_COOKIE_NAME;
            $this->_softDelete = true;
            if($user != '') {
                if(is_int($user)) {
                    $u = $this->_db->find_first('users', ['conditions' => 'id = ?', 'bind' => [$user]]);
                } else {
                    $u = $this->_db->find_first('users', ['conditions' => 'username = ?', 'bind' => [$user]]);
                }
                
                if($u) {
                    foreach($u as $key => $val) {
                        $this->$key = $val;
                    }
                }
            }
        }

        public static function current_logged_in_user() : Users {
            if(!isset(self::$currentLoggedInUser) && Session::exists(CURRENT_USER_SESSION_NAME)) {
                $User = new Users((int)Session::get(CURRENT_USER_SESSION_NAME));
                self::$currentLoggedInUser = $User;
            }

            return self::$currentLoggedInUser;
        }

        public static function login_user_from_cookie() : Users {
            $user_session = UsersSessions::get_cookie_data();

            if($user_session->user_id != '') {
                $user = new self((int)$user_session->user_id);
            }

            if($user) {
                $user->login();
            }
            return $user;
        }

        public function find_by_username(string $username) : Users {
            return $this->find_first(['conditions' => 'username = ?', 'bind' => [$username]]);
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

            if($user_session) {
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
    }