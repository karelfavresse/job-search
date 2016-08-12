<?php /* Copyright 2016 Karel Favresse */  ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Authentication_library {
     
        private $CI;

        /**
         * Checks whether the session has a logged in user. If not, the page is redirected to the login page. If this check is run on the login page, the redirect is not done.
         */
        public static function check() {
            
            // Logged in ?
            if(self::is_logged_in())
                return;
            
            // Login page ?
            $CI =& get_instance();
            $CI->load->helper('url');
            if(uri_string() === 'jobsrch/login')
                return;
            
            // Not logged in. Store the requested page and redirect to the login page.
            $_SESSION['auth_redirect_after_login'] = uri_string();
            redirect(site_url('jobsrch/login'));
        }
        
        public static function current_user() {
            if(isset($_SESSION['auth_user']))
                return $_SESSION['auth_user'];
            else
                return '';
        }
        
        public static function is_logged_in() {
            return ! empty (self::current_user());
        }
        
        public function __construct() {
            $this->CI =& get_instance();
            $this->CI->load->library('session');
            $this->CI->load->library('jobsrch/Authorization_library');
            $this->CI->load->database();
        }
        
        /**
         * Authenticates the user and specified password, and if OK, sets it as the authenticated user. In that case, the user's authorization is also loaded.
         * @param string $user
         * @param string $pwd
         * @return boolean TRUE if authenticated and authorized, FALSE if not.
         */
        public function authenticate($user, $pwd) {
            
            if($this->check_user_pwd($user, $pwd)) {
                $_SESSION['auth_user'] = $user;
                return $this->CI->authorization_library->authorize($user);
            } else
                return FALSE;
        }
        
        /**
         * Checks whether the user and password match.
         * @return boolean TRUE if OK, FALSE if not.
         */
        protected function check_user_pwd($user, $pwd) {
            
            $query = $this->CI->db->get_where('job_user', array('name' => $user), 1);
            $r = $query->result_array();
            if(empty($r))
                return FALSE;
            return password_verify($pwd, $r[0]['pwd']);
        }
        
        /**
         * Clear the currently authenticated user. 
         */
        public function clear() {
            unset($_SESSION['auth_user']);
            $this->CI->authorization_library->clear();
        }
        
    }
    
?>