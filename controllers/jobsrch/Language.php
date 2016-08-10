<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    /**
     * The Language class determines which language to load from the request, and loads the necessary language files for that language. Also loads the language helper.
     */
    class Language {
        
        public static function setup() {
            
            $CI =& get_instance();
            $CI->load->helper('language');
            
            // TODO get language from request and load those language files
            // For now always load english
            self::load($CI, 'english');
        }
        
        private static function load($CI, $lang) {
            
            $CI->lang->load('calendar', $lang);
            $CI->lang->load('date', $lang);
            $CI->lang->load('db', $lang);
            $CI->lang->load('email', $lang);
            $CI->lang->load('form_validation', $lang);
            $CI->lang->load('ftp', $lang);
            $CI->lang->load('imglib', $lang);
            $CI->lang->load('jobsrch', $lang);
            $CI->lang->load('migration', $lang);
            $CI->lang->load('number', $lang);
            $CI->lang->load('pagination', $lang);
            $CI->lang->load('profiler', $lang);
            $CI->lang->load('unit_test', $lang);
            $CI->lang->load('upload', $lang);
        }

    }
    
?>