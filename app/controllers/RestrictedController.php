<?php
    namespace App\Controllers;
    use Core\Controller;

    class RestrictedController extends Controller {

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
        }

        public function indexAction() : void {
            $this->view->render('restricted/index');
        }

        public function csrfAction() : void {
            $this->view->render('restricted/csrf');
        }
    }