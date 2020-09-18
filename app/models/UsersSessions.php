<?php
    namespace App\Models;
    use Core\Model;
    use Core\Cookie;
    use Core\Session;

    class UsersSessions extends Model {

        public $id, $user_id, $session, $user_agent;

        public function __construct() {
            $table = 'users_sessions';
            parent::__construct($table);
        }

        public static function get_cookie_data() {
            $user_session = new self();

            if(Cookie::exists(REMEMBER_ME_COOKIE_NAME)) {
                $user_session = $user_session->find_first([
                    'conditions' => "user_agent = ? AND session = ?",
                    'bind' => [Session::get_user_agent_without_version(), Cookie::get(REMEMBER_ME_COOKIE_NAME)]
                ]);
            }

            if(!$user_session) {
                return false;
            }

            return $user_session;
        }
    }