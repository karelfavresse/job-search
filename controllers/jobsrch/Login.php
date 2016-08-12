<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once 'UIMessage.php';
    require_once 'Language.php';
    
    class Login extends CI_Controller {
        
        public function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('url');
            $this->load->library('jobsrch/Authentication_library');
            Language::setup();
        }
        
        /**
         * Main entry point for login page.
         */
        public function index() {
            
            $this->load->helper('form');
            
            if($this->input->post('action') === NULL) {
                // Display login page (no action)
                $this->_load_login_page();
            } else {
                switch($this->input->post('action')) {
                    case 'login' :
                        $this->_login();
                        break;
                    default :
                        // Unknown action, redisplay login page
                        $this->_load_login_page();
                        break;
                }
            }
            
            session_write_close();
            
        }
        
        private function _login() {
            
            // Validate form
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', lang('label-login-name'), 'trim|required');
            $this->form_validation->set_rules('pwd', lang('label-login-pwd'), 'trim|required');
            if( $this->form_validation->run() !== FALSE ) {
                // Form OK, check user/pwd
                if( $this->authentication_library->authenticate($this->input->post('name'), $this->input->post('pwd')) ) {
                    // User/pwd OK, store in session. Then redirect to stored page, or home page if no stored page.
                    $_SESSION['auth_user'] = $this->input->post('name');
                    $url = $this->session->auth_redirect_after_login;
                    if($url === NULL)
                        $url = 'jobsrch';
                    else
                        unset($_SESSION['auth_redirect_after_login']);
                    redirect(site_url($url));
                } else {
                    // User/pwd not OK, issue message and redisplay login page.
                    UIMessage::addError(lang('message-login-failed'));
                    $this->_load_login_page();
                }
            } else {
                // Error validation form, redisplay login page.
                $this->_load_login_page();
            }
            
        }
        
        private function _load_login_page() {
            
            $data = [];
            $data['title'] = lang('title-login');
            
            $ln = $this->input->post('name');
            if($ln === NULL)
                $ln = '';
            $data['login_name'] = $ln;
            $data['login_pwd'] = ''; // Never send back the password...
            
            $this->load->view('jobsrch/header', $data);
            $this->load->view('jobsrch/login_view', $data);
            $this->load->view('jobsrch/footer');
        }
    }

?>