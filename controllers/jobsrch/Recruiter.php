<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once __DIR__ . '/AbstractController.php';
    require_once dirname(dirname(__DIR__)) . '/models/jobsrch/Address_entity.php';
    
    class Recruiter extends AbstractController {
        
        protected function loadModel() {
            $this->load->model('jobsrch/recruiter_model');
            $this->load->library('jobsrch/Address_library');
            $this->load->model('jobsrch/address_model');
            $this->load->helper('jobsrch/value');
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
            
            $this->form_validation->set_rules('name', lang('label-detail-recruiter-name'), 'trim|required');
            $this->form_validation->set_rules('contact_name', lang('label-detail-recruiter-contactname'), 'trim|required');
            $this->form_validation->set_rules('email_address', lang('label-detail-recruiter-emailaddress'), 'trim|valid_email');
            
            $this->address_library->setupValidationRules();
            
            return TRUE;
        }
    
        protected function setTitle(&$data) {
            $data['title'] = lang('title-recruiters');
        }
        
        protected function singularType() {
            return 'Recruiter';
        }
        
        protected function createEntity() {
            return new Recruiter_entity();
        }
        
        protected function urlSegment() {
            return 'recruiter';
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
        
        protected function preNew() {
            
            $_SESSION[$this->sessionKey('address')] = new Address_entity();
        }
        
        protected function loadExtraFromData($data) {
            
            // Load address from input data.
            $address = $_SESSION[$this->sessionKey('address')];
            $address = $this->address_model->loadFromData($this->input->post(), $address);
            
            // Store in session so the detail page can be repopulated even on error.
            $_SESSION[$this->sessionKey('address')] = $address;
            
            return $address;
        }
        
        protected function preSave($detail, $extra) {
            
            // Check for duplicate address.
            $address = $this->address_library->checkDuplicate($extra);
            
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
            
            return $detail;
        }
        
        protected function list_data($data) {
            
            // Load address oneliners for found recruiters. Store with the same ID as the recruiters list.
            $idArray = array();
            $idArrayReverse = array();
            foreach( $data as $rec ) {
                $idArray[$rec->id] = $rec->address_id;
            }
            $addresses = $this->address_model->getList($idArray);
            $addrList = array();
            foreach ( $addresses as $addr ) {
                $addrList[$addr->id] = $this->address_library->oneLineDescription($addr);
            }
            
            $list_data = array();
            foreach($data as $obj) {
                $list_entry = array('id' => $obj->id, 'name' => default_if_null($obj->name, ''), 'contactname' => default_if_null($obj->contact_name, ''), 'emailaddress' => default_if_null($obj->email_address, ''), 'phonenumber' => default_if_null($obj->phone_number, ''), 'address' => '', 'action' => '');
                if(isset($addrList[$obj->address_id])) {
                    $list_entry['address'] = $addrList[$obj->address_id];
                }
                $list_entry['action'] = '<a href="#" onclick="$(\'#detail_id\').val(\'' . $obj->id . '\'); doAction(\'startEdit\');"  title="' . lang('button-tip-edit-recruiter') . '"><span class="glyphicon glyphicon-pencil"></span></a>';
                $list_data[] = $list_entry;
            }
            
            return $list_data;
        }
        
        protected function attribute_for_column($column) {
            
            switch($column) {
                case 'contactname' :
                    return 'contact_name';
                case 'emailaddress' :
                    return 'email_address';
                case 'phonenumber' :
                    return 'phone_number';
            }
            
            return $column;
        }
        
        protected function createAuthCode() {
            return 'create_recruiter';
        }
        
        protected function updateAuthCode() {
            return 'update_recruiter';
        }
        
        protected function deleteAuthCode() {
            return 'delete_recruiter';
        }
    }
