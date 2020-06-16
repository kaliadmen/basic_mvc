<?php
class Register extends Controller {

    public function __construct($controller, $action) {
        parent::__construct($controller, $action);
        $this->view->set_layout('default');
    }

    public function loginAction() : void {
        $this->view->render('register/login');
    }
}