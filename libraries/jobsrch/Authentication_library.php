<?php /* Copyright 2016 Karel Favresse */  ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Authentication_library {
     
        private $CI;

        /**
         * Checks whether the session has a logged in user. If not, the page is redirected to the login page. If this check is run on the login page, the redirect is not done.
         */
        public function check($fromPage = NULL) {
            
            // Logged in ?
            if(self::is_logged_in())
                return;
            
            // Login page ?
            $this->CI =& get_instance();
            $this->CI->load->helper('url');
            if(uri_string() === 'jobsrch/login')
                return;
            
            // Remove any remaining user authentication
            $this->clear();
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
            
            // Using the session to store the url to go to after login will not work any more because we just destroyed the session and its cookie.
            // But if we don't destroy the session as above, then it sticks around and sometimes causes weird problems. E.g., clicking 'New' on a list page when not authenticated any more caused the detail screen to be shown after login, but executing Back resulted in an error.
            // Not logged in. Store the requested page and redirect to the login page.
            if($this->CI->input->is_ajax_request()) {
            /*    if($fromPage === null)
                    // Don't redirect to Ajax URL, won't work properly
                    $_SESSION['auth_redirect_after_login'] = 'jobsrch';
                else
                    $_SESSION['auth_redirect_after_login'] = $fromPage;
             */
                header("HTTP/1.1 401 Unauthorized");
                echo '<script>window.location = ' . site_url('jobsrch/login') . ';</script>';
            }
            else {
                /*
                if($fromPage === NULL)
                    $fromPage = uri_string();
                $_SESSION['auth_redirect_after_login'] = $fromPage;
                 */
                redirect(site_url('jobsrch/login'));
            }
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