<?php /* Copyright 2016 Karel Favresse */  ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Authorization_library {
        
        private $CI;
        
        public function __construct() {
            $this->CI =& get_instance();
            $this->CI->load->database();
        }
        
        /**
         * Clear any stored authorization.
         */
        public function clear() {
            
            if(isset($_SESSION['auth_codes']))
                unset($_SESSION['auth_codes']);
            
        }
        
        /**
         * Authorizes the specified user.
         * @param string $user
         * @return boolean TRUE if authorized, FALSE if not.
         */
        public function authorize($user) {
            
            // Load authorization codes from store into session
            $this->CI->db->select('authorization');
            $query = $this->CI->db->get_where('job_authorization', array('user_name' => $user));
            if ( empty ( $query ))
                return FALSE;
            
            // Store authorization codes in session.
            $ac = array();
            foreach($query->result_array() as $r)
                $ac[$r['authorization']] = TRUE;
            $_SESSION['auth_codes'] = $ac;
            
            return TRUE;
        }
        
        /**
         * Returns whether the current session is authorized for the specified code. 
         */
        public static function is_authorized($code) {
            if(isset($_SESSION['auth_codes']))
                return isset($_SESSION['auth_codes'][$code]);
            return FALSE;
        }
    }

?>