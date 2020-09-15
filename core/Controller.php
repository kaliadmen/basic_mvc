<?php
    namespace Core;
    use Core\Application;

/*Base controller class*/
    class Controller extends Application {

        protected $_controller, $_action;
        public $view, $request;

        public function __construct($controller, $action) {
            parent::__construct();
            $this->_controller = $controller;
            $this->_action = $action;
            $this->request = new Input();
            $this->view = new View();
        }

        protected function load_model(string $model) : void {
            $model_path = 'App\Models\\'.$model;
            if(class_exists($model_path)) {
                $this->{$model.'_Model'} = new $model_path();
            }
        }
    }