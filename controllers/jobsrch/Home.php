<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once __DIR__ . '/UIMessage.php';

    class Home extends CI_Controller {
        
        public function __construct()
        {
            parent::__construct();
            $this->load->helper('url_helper');
            $this->load->library('session');
        }

        public function index()
        {
            $data['title'] = 'Job Search Home';
            
            $this->load->view('jobsrch/header', $data);
            $this->load->view('jobsrch/home', $data);
            $this->load->view('jobsrch/footer');
            
            session_write_close();
        }

    }
