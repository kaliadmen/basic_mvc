<?php
    namespace App\Controllers;
    use Core\Controller;


    class AdminController extends Controller {

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
        }

        public function indexAction() : void {
            $this->view->render('admin/index');
        }
    }
