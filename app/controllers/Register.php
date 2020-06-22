<?php
class Register extends Controller {

    public function __construct($controller, $action) {
        parent::__construct($controller, $action);
        $this->load_model('Users');
        $this->view->set_layout('default');
    }

    public function loginAction() : void {
        $validation = new Validate();

        if($_POST) {
          $validation->validation($_POST, [
              'username' => [
                  'display' => "Username",
                  'required' => true
              ],
              'password' => [
                  'display' => "Password",
                  'required' => true,
                  'min' => 6
              ]
          ]);

          if($validation->is_valid()) {
              $user = $this->Users_Model->find_by_username($_POST['username']);

              if($user && password_verify(Input::get('password'), $user->password)) {
                  $remember = (isset($_POST['remember_me']) && Input::get('remember_me')) ? true : false;
                  $user->login($remember);
                  Router::redirect('');
              }else {
                  $validation->add_error("There is and error with your username or password");
              }
          }
        }


        $this->view->display_errors = $validation->display_errors();

        $this->view->render('register/login');
    }

    public function logoutAction() : void {
        if(current_user()) {
            current_user()->logout();
        }
        Router::redirect('register/login');
    }
}