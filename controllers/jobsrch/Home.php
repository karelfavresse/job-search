<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once 'UIMessage.php';
    require_once 'Language.php';

    class Home extends CI_Controller {
        
        public function __construct()
        {
            parent::__construct();
            
            
            $this->load->helper('url_helper');
            $this->load->library('session');
            $this->load->library('jobsrch/Authentication_library');
            $this->authentication_library->check('jobsrch');
            Language::setup();
        }

        public function index()
        {
            $enableProfiling = $this->config->item('enable_profiling');
            if($enableProfiling === NULL)
                $enableProfiling = FALSE;
            $this->output->enable_profiler($enableProfiling);

            $this->load->helper('form');
            
            $data['title'] = lang('title-home');
            $data['controller_name'] = 'home';
            
            $this->load->view('jobsrch/header', $data);
            $this->load->view('jobsrch/home', $data);
            $this->load->view('jobsrch/footer');
            
            session_write_close();
        }

    }
