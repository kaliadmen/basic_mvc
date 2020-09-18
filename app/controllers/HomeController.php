<?php
    namespace App\Controllers;
    use Core\Controller;

    class HomeController extends Controller {

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
        }

        public function indexAction() : void {
            $this->view->render('home/index');
        }
    }
