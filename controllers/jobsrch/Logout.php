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
            // Destroy the session
            session_unset();
            session_destroy();
            $_SESSION = array();
            session_start();
            // remove session cookie
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                          $params["path"], $params["domain"],
                          $params["secure"], $params["httponly"]
                          );
            }
            // redirect to login page
            $this->load->helper('url');
            redirect(site_url('jobsrch/login'));
        }
        
    }
    
?>