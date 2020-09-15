<?php
    namespace Core;

    class View {

        protected $_head, $_body, $_siteTitle = SITE_TITLE, $_outputType, $_layout = DEFAULT_LAYOUT;

        public function __construct() {

        }

        public function render(string $viewName) : void {
            $viewArr = explode('/', $viewName);
            $viewString = implode(DS, $viewArr);

            if(file_exists(ROOT.DS.'app'.DS.'views'.DS.$viewString.'.php')) {
                include(ROOT.DS.'app'.DS.'views'.DS.$viewString.'.php');
                include(ROOT.DS.'app'.DS.'views'.DS.'layouts'.DS.$this->_layout.'.php');
            } else {
                die('The view "'.$viewName.'" does not exist');
            }
        }

        public function content(string $type) {
            switch($type) {
                case 'head':
                    return $this->_head;
                    break;
                case 'body':
                    return $this->_body;
                    break;
                default:
                    return false;
            }
        }

        public function start(string $type) : void {
            $this->_outputType = $type;
            ob_start();
        }

        public function end() : void {
            switch($this->_outputType){
                case 'head':
                    $this->_head = ob_get_clean();
                    break;
                case 'body':
                    $this->_body = ob_get_clean();
                    break;
                default:
                    die('You must first run the start method.');
                    break;
            }
        }

        public function get_site_title() : string {
            return $this->_siteTitle;
        }
        
        public function set_site_title(string $title) : void {
            $this->_siteTitle = $title;
            return;
        }

        public function set_layout(string $path) : void {
            $this->_layout = $path;
            return;
        }

        public function insert(string $path) : void {
            include(ROOT.DS.'app'.DS.'views'.DS.$path.'.php');
        }

        public function add_partial(string $group, string $partial) : void {
            include(ROOT.DS.'app'.DS.'views'.DS.$group.DS.'partials'.DS.$partial.'.php');
        }
    }