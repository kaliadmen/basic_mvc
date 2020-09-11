<?php
class RegisterController extends Controller {

    public function __construct($controller, $action) {
        parent::__construct($controller, $action);
        $this->load_model('Users');
        $this->view->set_layout('default');
    }

    public function loginAction() : void {
        $login_model = new Login();

        if($this->request->is_post()) {
            $this->request->has_valid_csrf_token();
            $login_model->assign($this->request->get());
            $login_model->validator();

            if($login_model->get_validation_status()) {
                $user = $this->Users_Model->find_by_username($_POST['username']);

                if($user && password_verify($this->request->get('password')[0], $user->password)) {
                    $remember = $login_model->get_remember_me();
                    $user->login($remember);
                    Router::redirect('admin');
                }else {
                    $login_model->set_error_message('username', 'There is an error with your username or password');
                }
            }
        }

        $this->view->login = $login_model;
        $this->view->display_errors = $login_model->get_error_messages();

        $this->view->render('register/login');
    }

    public function logoutAction() : void {
        if(Users::get_current_user()) {
            Users::get_current_user()->logout();
        }
        Router::redirect('register/login');
    }

    public function registerAction() : void {
        $new_user = new Users();
        $new_user->set_confirmed('');

        if($this->request->is_post()) {
            $this->request->has_valid_csrf_token();
            $new_user->assign($this->request->get());
            $new_user->set_confirmed($this->request->get('confirm')[0]);

            if($new_user->save()){
                Router::redirect('register/login');
            }
        }

        $this->view->display_errors = $new_user->get_error_messages();
        $this->view->new_user = $new_user;
        $this->view->render('register/register');
    }
}