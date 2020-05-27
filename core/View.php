<?php

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
                die('The view \"'.$viewName.'\" does not exist');
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
            /* if($type == 'head') {
                return $this->_head;
            } elseif($type == 'body') {
                return $this->_body;
            }
            return false; */
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
            /* if($this->_outputType == 'head') {
                $this->_head = ob_get_clean();
            } elseif($this->_outputType == 'body') {
                $this->_body = ob_get_clean();
            } else {
                die('You must first run the start method.');
            }*/
        }

        public function getSiteTitle() : string {
            return $this->_siteTitle;
        }
        
        public function setSiteTitle(string $title) : void {
            $this->_siteTitle = $title;
            return;
        }

        public function setLayout(string $path) : void {
            $this->_layout = $path;
            return;
        }
    }