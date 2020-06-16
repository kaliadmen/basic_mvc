<?php
class Register extends Controller {

    public function __construct($controller, $action) {
        parent::__construct($controller, $action);
        $this->load_model('Users');
        $this->view->set_layout('default');
    }

    public function loginAction() : void {
        if($_POST) {
          $validation = true;
          if($validation === true) {
              $user = $this->UsersModel->find_bY_username($_POST['username']);
              if($user && password_verify(Input::get('password'), $user->password)) {
                  $remember = (isset($_POST['remember_me']) && Input::get('remember_me')) ? true : false;
                  $user->login($remember);
                  Router::redirect('');
              }
          }
        }
        $this->view->render('register/login');
    }
}