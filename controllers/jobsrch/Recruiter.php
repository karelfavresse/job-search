<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once __DIR__ . '/AbstractController.php';
    
    class Recruiter extends AbstractController {
        
        public function __construct()
        {
            parent::__construct();
            $this->load->model('jobsrch/recruiter_model');
        }
    
        /**
         * Main entry for Recruiter page. Dispatches to correct method based on current level.
         */
        public function index()
        {
            $this->load->helper('form');
            
            if(isset($_SESSION['Recruiter_level']))
                $level = $_SESSION['Recruiter_level'];
            else
                $level = 0;

            switch($level) {
                case 0 :
                    if($this->input->post('action')===NULL)
                        $this->_index_page();
                    else
                        $this->_process_search();
                    break;
                case 1 :
                    $this->_process_list();
                    break;
                case 2 :
                    $this->_process_detail();
                    break;
            }
            
            session_write_close();
        }
        
        private function _index_page() {
            
            if( ! isset($_SESSION['Recruiter_criteria']))
                $_SESSION['Recruiter_criteria'] = new Recruiter_criteria(array());
            $this->_load_search_view();
        }
        
        private function _process_search() {
            
            if(NULL !== $this->input->post('action')) {
                switch($this->input->post('action')) {
                    case 'reset' :
                        $this->_reset();
                        break;
                    case 'search' :
                        $this->_search();
                        break;
                    case 'new' :
                        $this->_doNew('search');
                        break;
                }
            } else {
                $this->_load_search_view();
            }
        }
        
        private function _process_list() {
            
            if(NULL !== $this->input->post('action')) {
                switch($this->input->post('action')) {
                    case 'refresh' :
                        $this->_refresh();
                        break;
                    case 'back' :
                        $this->_back('search');
                        break;
                    case 'new' :
                        $this->_doNew('list');
                        break;
                    case 'edit' :
                        $this->_startEdit();
                        break;
                }
            } else {
                $this->_load_list_view();
            }

        }
        
        private function _process_detail() {

            if(NULL !== $this->input->post('action')) {
                switch($this->input->post('action')) {
                    case 'save' :
                        $this->_save();
                        break;
                    case 'back' :
                        if(isset($_SESSION['Recruiter_detail_frompage']))
                            $toPage = $_SESSION['Recruiter_detail_frompage'];
                        else
                            $toPage = 'list';
                        $this->_back($toPage);
                        break;
                    case 'delete' :
                        $this->_delete();
                        break;
                }
            } else {
                $this->_load_detail_view();
            }

        }
        
        /**
         * Retrieves a fresh copy for the detail ID, then shows the detail page.
         * If no row can be found for the detail ID, and error is shown instead.
         * @param array &$data Data array sent to the loader's view() method.
         */
        private function _startEdit() {
            
            $id = $this->input->post('detail_id');
            if($id === NULL) {
                UIMessage::addError('No ID specified');
                return;
            }
            
            $detail = $this->recruiter_model->get($id);
            if ( ! isset($detail)) {
                UIMessage::addError('No recruiter found with id '. $id);
                return;
            }
            
            $_SESSION['Recruiter_detail'] = $detail;
            $this->_load_detail_view();
        }
        
        /**
         * Saves the current detail Recruiter and shows the detail screen again.
         * @param array &$data Data array sent to the loader's view() method.
         */
        private function _save() {
         
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('contact_name', 'Contact Name', 'trim|required');
            $this->form_validation->set_rules('email_address', 'Email Address', 'trim|valid_email');
            
            $detail = $_SESSION['Recruiter_detail'];
            $detail = $this->recruiter_model->loadFromData($this->input->post(), $detail);
            if ($this->form_validation->run() !== FALSE)
            {
                
                $isNew = ! isset($detail->id);
                if( $isNew )
                    $d = $this->recruiter_model->insert($detail);
                else
                    $d = $this->recruiter_model->update($detail);
                if($d === FALSE) {
                    UIMessage::addError('Failed to save Recruiter');
                } else {
                    $detail = $d;
                    $_SESSION['Recruiter_list'][$detail->id] = $detail;
                    if($isNew) {
                        $list =& $_SESSION['Recruiter_list'];
                        $list[] = $detail;
                    }
                    UIMessage::addInfo('Recruiter saved.');
                }
            }
            $_SESSION['Recruiter_detail'] = $detail;

            $this->_load_detail_view();
        }
        
        /**
         * Deletes the current detail Recruiter and shows the list screen again.
         * @param array &$data Data array sent to the loader's view() method.
         */
        private function _delete() {
            
            $detail = $_SESSION['Recruiter_detail'];
            if($this->recruiter_model->delete($detail)) {
                unset($_SESSION['Recruiter_detail']);
                UIMessage::addInfo('Recruiter "' . $detail->name . '" deleted.');
                $list =& $_SESSION['Recruiter_list'];
                unset($list[$detail->id]);
                $this->_load_list_view();
            } else {
                UIMessage::addError('Failed to delete recruiter');
                $this->_load_detail_view();
            }
        }
        
        /**
         * Reloads data from the database using the search criteria stored in the session. Shows the list screen
         * @param array &$data Data array sent to the loader's view() method.
         */
        private function _refresh() {
            
            $this->_doSearch();
            $this->_load_list_view();
        }
        
        private function _doSearch() {
            
            $list = array();
            foreach ($this->recruiter_model->search($_SESSION['Recruiter_crit']) as $entry) {
                $list[$entry->id] = $entry;
            }
            $_SESSION['Recruiter_list'] = $list;
        }
        
        /**
         * Processes the Back button, setting the 'crit' entry in the $data array from session. It then shows the specified page (search or list).
         * @param array &$data Data array sent to the loader's view() method.
         * @param string $page Page to actually show (search or list).
         */
        private function _back($page) {
            
            switch($page) {
                case 'search' :
                    unset($_SESSION['Recruiter_detail']);
                    unset($_SESSION['Recruiter_list']);
                    $this->_load_search_view();
                    break;
                case 'list' :
                    unset($_SESSION['Recruiter_detail']);
                    $this->_load_list_view();
                    break;
            }
            
            unset($_SESSION['Recruiter_detail_frompage']);
        }
        
        /**
         * Adds the common page title to the $data parameter.
         * @param array &$data Data array sent to the loader's view() method during actual view building.
         */
        private function _setTitle(&$data) {
            $data['title'] = 'Recruiters';
        }
        
        /**
         * Resets the search criteria to their default values, and shows the search screen again.
         *
         * @param array &$data Data array sent to the loader's view() method. This method adds the 'crit' entry with the new search criteria object.
         */
        private function _reset() {
            
            $_SESSION['Recruiter_level'] = new Recruiter_criteria(array());
            $this->_load_search_view();
        }
        
        private function _detailButtonState(&$data, Recruiter_entity $detail) {
            
            $btn_state = array();
            
            if( ! isset($detail->id))
                $btn_state['delete'] = 'disabled';
            else
                $btn_state['delete'] = '';
            
            $data['button_state'] = $btn_state;
        }
        
        /**
         * Creates a blank Recruiter object, then shows the detail screen.
         * @param array &$data Data array sent to the loader's view() method.
         */
        private function _doNew($fromPage) {

            $_SESSION['Recruiter_detail'] = new Recruiter_entity();
            $_SESSION['Recruiter_detail_frompage'] = $fromPage;

            $this->_load_detail_view();
        }
        
        /**
         * Stores the search criteria from the request into session, searches the database using those criteria, and then displays the list screen.
         * Validates search criteria.
         * @param array &$data Data array sent to the loader's view() method.
         */
        private function _search() {
            
            $this->load->library('form_validation');

            $this->form_validation->set_rules('maxrows', 'Max. Rows', 'trim|required|integer');

            $crit = new Recruiter_criteria($this->input->post());
            $_SESSION['Recruiter_crit'] = $crit;
            
            if ($this->form_validation->run() !== FALSE)
            {
                $this->_doSearch();
                $this->_load_list_view();
            } else {
                $this->_load_search_view();
            }
        }
        
        private function _load_search_view() {
            $data = [];
            $this->_setTitle($data);
            $_SESSION['Recruiter_level'] = 0;
            $data['crit'] = $_SESSION['Recruiter_crit'];
            $this->load->view('jobsrch/header', $data);
            $this->load->view('jobsrch/recruiter/search', $data);
            $this->load->view('jobsrch/footer');
        }
        
        private function _load_list_view() {
            $data = [];
            $this->_setTitle($data);
            $_SESSION['Recruiter_level'] = 1;
            $data['list'] = $_SESSION['Recruiter_list'];
            $this->load->view('jobsrch/header', $data);
            $this->load->view('jobsrch/recruiter/list', $data);
            $this->load->view('jobsrch/footer');
        }
        
        private function _load_detail_view() {
            $data = [];
            $this->_setTitle($data);
            $_SESSION['Recruiter_level'] = 2;
            $data['detail'] = $_SESSION['Recruiter_detail'];
            $this->_detailButtonState($data,$data['detail']);
            $this->load->view('jobsrch/header', $data);
            $this->load->view('jobsrch/recruiter/detail', $data);
            $this->load->view('jobsrch/footer');
        }

    }
