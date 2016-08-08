<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once __DIR__ . '/AbstractController.php';
    require_once dirname(dirname(__DIR__)) . '/models/jobsrch/Address_entity.php';
    
    class Recruiter extends AbstractController {
        
        protected function loadModel() {
            $this->load->model('jobsrch/recruiter_model');
            $this->load->library('jobsrch/Address_library');
            $this->load->model('jobsrch/address_model');
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
            
            $this->address_library->setupValidationRules();
            
            return TRUE;
        }
    
        protected function setTitle(&$data) {
            $data['title'] = 'Recruiters';
        }
        
        protected function createEntity() {
            return new Recruiter_entity();
        }
        
        protected function set_detail_data(&$data) {
            
            parent::set_detail_data($data);
            
            // Set address in data
            $data['address'] = $_SESSION[$this->sessionKey('address')];
            
        }
        
        protected function startEditInternal($detail) {
            
            parent::startEditInternal($detail);
            
            // Load address and store in session.
            // If recruiter does not have an address, create a new one.
            if(isset($detail->address_id))
                $address = $this->address_model->get($detail->address_id);
            else
                $address = new Address_entity();
            
            $_SESSION[$this->sessionKey('address')] = $address;
        }
        
        protected function preSave($detail) {
            
            // Load address from input data.
            $address = $_SESSION[$this->sessionKey('address')];
            $address = $this->address_model->loadFromData($this->input->post(), $address);
            
            // Check for duplicate address.
            $address = $this->address_library->checkDuplicate($address);
            
            // Save the address first, set new ID in $detail.
            if( ! isset($address->id) )
                $addr = $this->address_model->insert($address);
            else
                $addr = $this->address_model->update($address);
            if ( $addr === FALSE ) {
                UIMessage::addError('Failed to save address');
                return FALSE;
            }
            
            // Save address in session
            $_SESSION[$this->sessionKey('address')] = $addr;
            
            // Store address ID in recruiter entity
            $detail->address_id = $addr->id;
            
            // Update address in list screen
            $addrList =& $_SESSION[$this->sessionKey('addrList')];
            $addrList[$detail->id] = $this->address_library->oneLineDescription($addr);
            
            return $detail;
        }
        
        protected function doSearch() {
            
            // Let parent search for recruiters.
            parent::doSearch();
            
            // Load address oneliners for found recruiters. Store with the same ID as the recruiters list.
            $idArray = array();
            $idArrayReverse = array();
            foreach( $_SESSION[$this->sessionKey('list')] as $rec ) {
                $idArray[$rec->id] = $rec->address_id;
                $idArrayReverse[$rec->address_id] = $rec->id;
            }
            $addresses = $this->address_model->getList($idArray);
            $addrList = array();
            foreach ( $addresses as $addr ) {
                $addrList[$idArrayReverse[$addr->id]] = $this->address_library->oneLineDescription($addr);
            }
            
            // Store address list in session
            $_SESSION[$this->sessionKey('addrList')] = $addrList;
        }
        
        protected function set_list_data(&$data) {
            
            parent::set_list_data($data);
            
            $data['addrList'] = $_SESSION[$this->sessionKey('addrList')];
            
        }
    }
