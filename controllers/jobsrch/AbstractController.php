<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once 'UIMessage.php';
    
    abstract class AbstractController extends CI_Controller {
        
        public function __construct() {
            parent::__construct();
            $this->load->helper('url_helper');
            $this->load->library('session');
        }
        
        protected function sessionPrefix() {
            return get_class($this);
        }
        
        /**
         Main entry for the page this controller handles. Dispatches to the various methods based on level.
         This method also loads the form helper.
         */
        public function index() {
            
            $this->load->helper('form');

        }
    }
?>