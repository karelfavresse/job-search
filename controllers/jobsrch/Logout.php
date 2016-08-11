<?php /* Copyright 2016 Karel Favresse */  ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Logout extends CI_Controller {
        
        public function index() {
            // remove user authentication
            $this->load->library('session');
            unset($_SESSION['auth_user']);
            // redirect to login page
            $this->load->helper('url');
            redirect(site_url('jobsrch/login'));
        }
        
    }
    
?>