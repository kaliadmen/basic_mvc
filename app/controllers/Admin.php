<?php
class Admin extends Controller {

    public function __construct($controller, $action) {
        parent::__construct($controller, $action);
    }

    public function indexAction() : void {
        $this->view->render('admin/index');
    }
}
