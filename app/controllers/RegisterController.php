<?php
class RegisterController extends Controller {

    public function __construct($controller, $action) {
        parent::__construct($controller, $action);
        $this->load_model('Users');
        $this->view->set_layout('default');
    }

    public function loginAction() : void {
        $validation = new Validate();

        if($_SERVER['REQUEST_METHOD'] == "POST") {
          $validation->validation(FormHelper::post_values($_POST), [
              'username' => [
                  'display' => "Username",
                  'required' => true
              ],
              'password' => [
                  'display' => "Password",
                  'required' => true,
                  'min' => 6
              ]
          ], true);

          if($validation->is_valid()) {
              $user = $this->Users_Model->find_by_username($_POST['username']);

              if($user && password_verify(Input::get('password'), $user->password)) {
                  $remember = (isset($_POST['remember_me']) && Input::get('remember_me')) ? true : false;
                  $user->login($remember);
                  Router::redirect('admin');
              }else {
                  $validation->add_error("There is an error with your username or password");
              }
          }
        }


        $this->view->display_errors = $validation->display_errors();

        $this->view->render('register/login');
    }

    public function logoutAction() : void {
        if(Users::get_current_user()) {
            Users::get_current_user()->logout();
        }
        Router::redirect('register/login');
    }

    public function registerAction() : void {
        $validation = new Validate();
        $submitted_values = [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'username' => '',
            'password' => '',
            'confirm' => ''
        ];

        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $submitted_values = FormHelper::post_values($_POST);
            $validation->validation($submitted_values, [
                'first_name' => [
                    'display' => "First name",
                    'required' => true
                ],
                'last_name' => [
                    'display' => "Last name",
                    'required' => true,
                ],
                'email' => [
                    'display' => "Email",
                    'required' => true,
                    'unique' => 'users',
                    'max' => 150,
                    'valid_email' => true
                ],
                'username' => [
                    'display' => "Username",
                    'required' => true,
                    'unique' => 'users',
                    'min' => 5,
                    'max' => 150
                ],
                'password' => [
                    'display' => "Password",
                    'required' => true,
                    'min' => 6
                ],
                'confirm' => [
                    'display' => "Confirm password",
                    'required' => true,
                    'matches' => 'password'
                ],
            ]);

            if($validation->is_valid()) {
                $new_user = new Users();
                $new_user->register_new_user($submitted_values);
                Router::redirect('register/login');
            }

        }

        $this->view->display_errors = $validation->display_errors();
        $this->view->post = $submitted_values;
        $this->view->render('register/register');
    }
}