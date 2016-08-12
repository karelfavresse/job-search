<?php /* Copyright 2016 Karel Favresse */  ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Logout extends CI_Controller {
        
        public function __construct() {
            parent::__construct();
            $this->load->library('jobsrch/Authentication_library');
            $this->load->library('session');
        }
        
        public function index() {
            // remove user authentication
            $this->authentication_library->clear();
            $this->session->sess_destroy();
            // redirect to login page
            $this->load->helper('url');
            redirect(site_url('jobsrch/login'));
        }
        
    }
    
?>