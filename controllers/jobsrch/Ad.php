<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once __DIR__ . '/AbstractController.php';
    require_once dirname(dirname(__DIR__)) . '/models/jobsrch/Ad_entity.php';
    
    class Ad extends AbstractController {
        
        const LEVEL_ACTIONS = 3;
        
        protected function loadModel() {
            $this->load->model('jobsrch/ad_model');
            $this->load->library('jobsrch/Address_library');
            $this->load->model('jobsrch/address_model');
            $this->load->helper('jobsrch/value');
            $this->load->model('jobsrch/recruiter_model');
            $this->load->model('jobsrch/adaction_model');
        }
        
        protected function createCriteria($input = NULL) {
            if ( ! isset($input) )
                $input = array();
            return new Ad_criteria($input);
        }
        
        protected function getModel() {
            return $this->ad_model;
        }
        
        protected function setupDetailValidationRules() {
            
            $this->form_validation->set_rules('title', lang('label-detail-ad-title'), 'trim|required');
            $this->form_validation->set_rules('company', lang('label-detail-ad-company'), 'trim|required');
            
            $this->address_library->setupValidationRules();
            
            return TRUE;
        }
        
        protected function setTitle(&$data) {
            $data['title'] = lang('title-ad');
        }
        
        protected function singularType() {
            return 'Ad';
        }
        
        protected function createEntity() {
            return new Ad_entity();
        }
        
        protected function urlSegment() {
            return 'ad';
        }
        
        protected function set_detail_data(&$data) {
            
            parent::set_detail_data($data);
            
            // Set address in data
            $data['address'] = $_SESSION[$this->sessionKey('address')];
            
            // Set recruiter in data
            if ( isset ($_SESSION[$this->sessionKey('recruiter')]))
                $data['recruiter'] = $_SESSION[$this->sessionKey('recruiter')];
        }
        
        protected function delete_name($detail) {
            return $detail->title;
        }
        
        protected function startEditInternal($detail) {
            
            parent::startEditInternal($detail);
            
            // Load recruiter if available
            if(isset($detail->recruiter_id)) {
                $_SESSION[$this->sessionKey('recruiter')] = $this->recruiter_model->get($detail->recruiter_id);
            }
            
            // Load address and store in session.
            // If ad does not have an address, create a new one.
            if(isset($detail->company_address_id))
                $address = $this->address_model->get($detail->company_address_id);
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
            
            // Load recruiter from input data
            $recrId = $this->input->post('recruiter');
            if($recrId === NULL)
                unset($_SESSION[$this->sessionKey('recruiter')]);
            else
                $_SESSION[$this->sessionKey('recruiter')] = $this->recruiter_model->get($recrId);
            
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
            
            // Store address ID in ad entity
            $detail->company_address_id = $addr->id;
            
            // Get recruiter ID, store in ad entity if set
            if ( isset($_SESSION[$this->sessionKey('recruiter')]))
                $detail->recruiter_id = $_SESSION[$this->sessionKey('recruiter')]->id;
            else
                $detail->recruiter_id = NULL;
            
            return $detail;
        }
        
        protected function allowAction($action) {
            if ( ! parent::allowAction($action))
                return in_array($action, array('listActions', 'saveActions'));
            return TRUE;
        }
        
        public function saveActions() {
            // TODO
        }
        
        public function listActions() {
            
            
            $crit = new AdAction_criteria(array('job_ad_id' => $this->input->post('detail_id')));
            $actions = $this->adaction_model->search($crit);
            $_SESSION[$this->sessionKey('actions')] = array();
            
            $this->setLevel(self::LEVEL_ACTIONS);
        }
        
        protected function load_view($level) {
            
            switch($level) {
                case self::LEVEL_ACTIONS :
                    $this->load_actions_view();
                    break;
                default :
                    parent::load_view($level);
                    break;
            }
        }
        
        protected function load_actions_view() {
            
            $data = array();
            $data['type'] = $this->singularType();
            $data['actions'] = $_SESSION[$this->sessionKey('actions')];
            
            $this->load->view('jobsrch/ad/actions', $data);
        }
        
        public function back() {
         
            $level = $_SESSION[$this->sessionKey('level')];
            if ( $level == self::LEVEL_ACTIONS )
                $this->setLevel(self::LEVEL_LIST);
            else
                parent::back();
        }
        
        protected function list_data($data) {
            
            // Load address and recruiter oneliners for found ads. Store with the same ID as the ads list.
            $addressIdArray = array();
            $recruiterIdArray = array();
            foreach( $data as $rec ) {
                $addressIdArray[$rec->id] = $rec->company_address_id;
                $recruiterIdArray[$rec->id] = $rec->recruiter_id;
            }
            $addresses = $this->address_model->getList($addressIdArray);
            $addrList = array();
            foreach ( $addresses as $addr ) {
                $addrList[$addr->id] = $this->address_library->oneLineDescription($addr);
            }
            $recruiters = $this->recruiter_model->getList($recruiterIdArray);
            $recrList = array();
            foreach ( $recruiters as $recr ) {
                $recrList[$recr->id] = $recr->contact_name . ' ('.$recr->name .')';
            }
            
            $list_data = array();
            foreach($data as $obj) {
                $list_entry = array('id' => $obj->id, 'title' => default_if_null($obj->title, ''), 'company' => default_if_null($obj->company, ''), 'url' => default_if_null($obj->url, ''), 'vdabreference' => default_if_null($obj->vdab_reference, ''), 'contactname' => default_if_null($obj->contact_name, ''), 'emailaddress' => default_if_null($obj->email_address, ''), 'phonenumber' => default_if_null($obj->phone_number, ''), 'address' => '', 'recruiter' => '', 'action' => '');
                if(isset($addrList[$obj->company_address_id]))
                    $list_entry['address'] = $addrList[$obj->company_address_id];
                if(isset($recrList[$obj->recruiter_id]))
                    $list_entry['recruiter'] = $recrList[$obj->recruiter_id];
                $list_entry['action'] = '<a href="#" onclick="$(\'#detail_id\').val(\'' . $obj->id . '\'); doAction(\'startEdit\');"  title="' . lang('button-tip-edit-ad') . '"><span class="glyphicon glyphicon-pencil"></span></a>';
                $list_entry['action'] .= ' <a href="#" onclick="$(\'#detail_id\').val(\'' . $obj->id . '\'); doAction(\'listActions\');" title="' . lang('button-tip-ad-actions') . '"><span class="glyphicon glyphicon-time"></span></a>';
                $list_data[] = $list_entry;
            }
            
            return $list_data;
        }
        
        public function loadrecruiters() {
            
            // Calling this method is only allowed through Ajax / POST
            if ( ! $this->input->is_ajax_request() || empty ( $this->input->post()) ) {
                $this->redirect();
                exit;
            }
            
            $this->authentication_library->check('jobsrch/'. $this->urlSegment());
            
            // Extract parameters from POST
            $q = $this->input->post('q');
            $page = $this->input->post('page');
            $pagelength = $this->input->post('pagelenght');
            
            // Search recruiters on contact name, returning at most 'pagelength' rows starting from 'page' * 'pagelength'.
            $crit = new Recruiter_criteria();
            $crit->contact_name = $q;
            $crit->name = $q;
            $crit->match_any = TRUE;
            $totalRows = 0;
            $recruiters = $this->recruiter_model->search($crit, $page * $pagelength, $pagelength, array(array('column'=>'contact_name', 'dir' => 'asc')), $totalRows);
            
            // Return found data as JSON.
            $data = array('items' => $recruiters, 'total_count' => $totalRows);
            
            echo json_encode($data);
        }
        
        protected function attribute_for_column($column) {
            
            switch($column) {
                case 'contactname' :
                    return 'contact_name';
                case 'emailaddress' :
                    return 'email_address';
                case 'phonenumber' :
                    return 'phone_number';
                case 'vdabreference' :
                    return 'vdab_reference';
            }
            
            return $column;
        }
        
        protected function createAuthCode() {
            return 'create_ad';
        }
        
        protected function updateAuthCode() {
            return 'update_ad';
        }
        
        protected function deleteAuthCode() {
            return 'delete_ad';
        }
    }
