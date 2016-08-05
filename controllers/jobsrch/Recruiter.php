<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once __DIR__ . '/AbstractController.php';
    
    class Recruiter extends AbstractController {
        
        protected function loadModel() {
            $this->load->model('jobsrch/recruiter_model');
        }
        
        protected function createCriteria($input = NULL) {
            if ( ! isset($input) )
                $input = array();
            return new Recruiter_criteria($input);
        }
        
        protected function getModel() {
            return $this->recruiter_model;
        }
        
        protected function setupDetailValidationRules() {
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('contact_name', 'Contact Name', 'trim|required');
            $this->form_validation->set_rules('email_address', 'Email Address', 'trim|valid_email');
            return TRUE;
        }
    
        protected function setTitle(&$data) {
            $data['title'] = 'Recruiters';
        }
        
        protected function createEntity() {
            return new Recruiter_entity();
        }
        
    }
