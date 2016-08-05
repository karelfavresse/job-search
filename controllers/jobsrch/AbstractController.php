<?php /* Copyright 2016 Karel Favresse */  ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once 'UIMessage.php';
    
    abstract class AbstractController extends CI_Controller {
        
        const LEVEL_SEARCH = 0;
        const LEVEL_LIST = 1;
        const LEVEL_DETAIL = 2;
        
        public function __construct() {
            parent::__construct();
            $this->loadModel();
            $this->load->helper('url_helper');
            $this->load->library('session');
        }
        
        /* ===== Abstract methods ======= */
        
        /**
         * Loads the model
         */
        protected abstract function loadModel();
        
        /**
         * Returns a new criteria object suitable for this controller.
         * @return Object a criteria object
         */
        protected abstract function createCriteria();
        
        /**
         * Adds the common page title to the $data parameter.
         * @param array &$data Data array sent to the loader's view() method during actual view building.
         */
        protected abstract function setTitle(&$data);
        
        /**
         * Returns the model used for data.
         * @return Object the model instance.
         */
        protected abstract function getModel();
        
        /**
         * Returns a new entity.
         * @return Object the new entity.
         */
        protected abstract function createEntity();
        
        /* ====== End of abstract methods ====== */
        
        /**
         * Returns the name of the entity being worked with. Defaults to the class name.
         */
        protected function getName() {
            return get_class($this);
        }
        
        /**
         * Sets up validation rules for detail input. Default implementation does not set any rules.
         * @return TRUE if validation is required, FALSE if not.
         */
        protected function setupDetailValidationRules() {
            return FALSE;
        }
        
        /**
         * Returns the key to use to get or set a session variable for this object's actual class. This is the class name, followed by '_' and the specified name.
         * @param string $name Name of the key
         * @return string Key to use for the specified name.
         */
        protected function sessionKey($name) {
            return get_class($this) . '_' . $name;
        }
        
        /**
         * Main entry for the page this controller handles. Dispatches to the various methods based on level.
         * This method also loads the form helper.
         */
        public function index() {
            
            $this->load->helper('form');

            if(isset($_SESSION[$this->sessionKey('level')]))
                $level = $_SESSION[$this->sessionKey('level')];
            else
                $level = self::LEVEL_SEARCH;
            
            switch($level) {
                case self::LEVEL_SEARCH :
                    if($this->input->post('action') === NULL)
                        $this->_index_page();
                    else
                        $this->_process_search();
                    break;
                case self::LEVEL_LIST :
                    $this->_process_list();
                    break;
                case self::LEVEL_DETAIL :
                    $this->_process_detail();
                    break;
            }
            
            session_write_close();
        }
        
        /**
         * Processes the index page for this controller. Shows the search page. 
         */
        private function _index_page() {
            
            if( ! isset($_SESSION[$this->sessionKey('criteria')]))
                $_SESSION[$this->sessionKey('criteria')] = $this->createCriteria();
            $this->_load_search_view();
        }
        
        /**
         * Processes the search level. Dispatches to other methods according to the 'action' hidden input field.
         */
        private function _process_search() {
            
            if(NULL !== $this->input->post('action')) {
                if( ! $this->processSearchAction($this->input->post('action')) )
                    $this->_load_search_view();
            } else {
                $this->_load_search_view();
            }
        }
        
        /**
         * Process the specified action from the search level. Default implementation handles 'search', 'reset' and 'new'.
         * Override to handle extra actions.
         * @param string $action The action to process.
         * @return boolean TRUE if the action was handled, FALSE if not.
         */
        protected function processSearchAction($action) {
            
            switch($action) {
                case 'reset' :
                    $this->_reset();
                    break;
                case 'search' :
                    $this->_search();
                    break;
                case 'new' :
                    $this->_doNew('search');
                    break;
                default :
                    return FALSE;
            }
            
            return TRUE;
        }
        
        /**
         * Creates a blank Recruiter object, then shows the detail screen.
         * @param string $fromPage The level 'New' is called from. The 'Back' button uses this to return to that level.
         */
        private function _doNew($fromPage) {
            
            $_SESSION[$this->sessionKey('detail')] = $this->createEntity();
            $_SESSION[$this->sessionKey('detail_frompage')] = $fromPage;
            
            $this->_load_detail_view();
        }
        
        /**
         * Stores the search criteria from the request into session, searches the database using those criteria, and then displays the list screen.
         * Validates search criteria.
         */
        private function _search() {
            
            $this->load->library('form_validation');
            
            $useValidation = $this->setupCriteriaValidationRules();
            
            $crit = $this->createCriteria($this->input->post());
            $_SESSION[$this->sessionKey('crit')] = $crit;
            
            if ( ! $useValidation OR $this->form_validation->run() !== FALSE)
            {
                $this->_doSearch();
                $this->_load_list_view();
            } else {
                $this->_load_search_view();
            }
        }
        
        /**
         * Sets up validation rules for criteria. Default implementation sets a single rule for the 'maxrows' input element.
         * @return TRUE if validation is to be used, FALSE if not.
         */
        protected function setupCriteriaValidationRules() {
            
            $this->form_validation->set_rules('maxrows', 'Max. Rows', 'trim|required|integer');
            
            return TRUE;
        }
        
        /**
         * Resets the search criteria to their default values, and shows the search screen again.
         */
        private function _reset() {
            
            $_SESSION[$this->sessionKey('crit')] = $this->createCriteria();
            $this->_load_search_view();
        }
        
        /**
         * Processes the list level. Dispatches to other methods according to the 'action' hidden input field.
         */
        private function _process_list() {
            
            if(NULL !== $this->input->post('action')) {
                if ( ! $this->processListAction($this->input->post('action')) )
                    $this->_load_list_view();
            } else {
                $this->_load_list_view();
            }
            
        }
        
        /**
         * Process the specified action from the search level. Default implementation handles 'refresh', 'back', 'edit' and 'new'.
         * Override to handle extra actions.
         * @param string $action The action to process.
         * @return boolean TRUE if the action was handled, FALSE if not.
         */
        protected function processListAction($action) {
            
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
                default :
                    return FALSE;
            }
            return TRUE;
        }
        
        /**
         * Processes the Back button, setting the 'crit' entry in the $data array from session. It then shows the specified page (search or list).
         * @param string $page Page to actually show (search or list).
         */
        private function _back($page) {
            
            switch($page) {
                case 'search' :
                    unset($_SESSION[$this->sessionKey('detail')]);
                    unset($_SESSION[$this->sessionKey('list')]);
                    $this->_load_search_view();
                    break;
                case 'list' :
                    unset($_SESSION[$this->sessionKey('detail')]);
                    $this->_load_list_view();
                    break;
            }
            
            unset($_SESSION[$this->sessionKey('detail_frompage')]);
        }
        
        /**
         * Reloads data from the database using the search criteria stored in the session. Shows the list screen
         */
        private function _refresh() {
            
            $this->_doSearch();
            $this->_load_list_view();
        }
        
        /**
         * Perform the actual search and set the list.
         */
        private function _doSearch() {
            
            $list = array();
            foreach ($this->recruiter_model->search($_SESSION[$this->sessionKey('crit')]) as $entry) {
                $list[$entry->id] = $entry;
            }
            $_SESSION[$this->sessionKey('list')] = $list;
        }
        
        /**
         * Retrieves a fresh copy for the detail ID, then shows the detail page.
         * If no row can be found for the detail ID, and error is shown instead.
         */
        private function _startEdit() {
            
            $id = $this->input->post('detail_id');
            if($id === NULL) {
                UIMessage::addError('No ID specified');
                return;
            }
            
            $detail = $this->getModel()->get($id);
            if ( ! isset($detail)) {
                UIMessage::addError('No ' . $this->getName() . ' found with id '. $id);
                return;
            }
            
            $_SESSION[$this->sessionKey('detail')] = $detail;
            $this->_load_detail_view();
        }
        
        /**
         * Saves the current detail and shows the detail screen again.
         */
        private function _save() {
            
            $this->load->library('form_validation');
            
            $useValidation = $this->setupDetailValidationRules();
            
            $detail = $_SESSION[$this->sessionKey('detail')];
            $detail = $this->getModel()->loadFromData($this->input->post(), $detail);
            if ( ! $useValidation OR $this->form_validation->run() !== FALSE)
            {
                
                $isNew = ! isset($detail->id);
                if( $isNew )
                    $d = $this->getModel()->insert($detail);
                else
                    $d = $this->getModel()->update($detail);
                if($d === FALSE) {
                    UIMessage::addError('Failed to save ' . $this->getName() );
                } else {
                    $detail = $d;
                    $_SESSION[$this->sessionKey('list')][$detail->id] = $detail;
                    UIMessage::addInfo($this->getName() . ' saved.');
                }
            }
            $_SESSION[$this->sessionKey('detail')] = $detail;
            
            $this->_load_detail_view();
        }
        
        /**
         * Processes the detail level. Dispatches to other methods according to the 'action' hidden input field.
         */
        private function _process_detail() {
            
            if(NULL !== $this->input->post('action')) {
                if ( ! $this->processDetailAction($this->input->post('action')) )
                    $this->_load_detail_view();
            } else {
                $this->_load_detail_view();
            }
            
        }
        
        /**
         * Process the specified action from the search level. Default implementation handles 'refresh', 'back', 'edit' and 'new'.
         * Override to handle extra actions.
         * @param string $action The action to process.
         * @return boolean TRUE if the action was handled, FALSE if not.
         */
        protected function processDetailAction($action) {
            
            switch($action) {
                case 'save' :
                    $this->_save();
                    break;
                case 'back' :
                    if(isset($_SESSION[$this->sessionKey('detail_frompage')]))
                        $toPage = $_SESSION[$this->sessionKey('detail_frompage')];
                    else
                        $toPage = 'list';
                    $this->_back($toPage);
                    break;
                case 'delete' :
                    $this->_delete();
                    break;
                default :
                    return FALSE;
            }
            return TRUE;
        }
        
        /**
         * Deletes the current detail and shows the list screen again.
         */
        private function _delete() {
            
            $detail = $_SESSION[$this->sessionKey('detail')];
            if($this->getModel()->delete($detail)) {
                unset($_SESSION[$this->sessionKey('detail')]);
                UIMessage::addInfo($this->getName() . ' "' . $detail->name . '" deleted.');
                $list =& $_SESSION[$this->sessionKey('list')];
                unset($list[$detail->id]);
                $this->_load_list_view();
            } else {
                UIMessage::addError('Failed to delete ' . $this->getName() );
                $this->_load_detail_view();
            }
        }
        
        /**
         * Loads the search view
         */
        private function _load_search_view() {
            
            $data = [];
            $this->setTitle($data);
            
            $_SESSION[$this->sessionKey('level')] = self::LEVEL_SEARCH;
            
            $data['crit'] = $_SESSION[$this->sessionKey('crit')];
            
            $this->load->view('jobsrch/header', $data);
            $this->loadSearchPanel($data);
            $this->load->view('jobsrch/footer');
        }
        
        /**
         * Loads the search panel.
         * @param array $data Data for the panel.
         */
        protected function loadSearchPanel($data) {
            $this->load->view('jobsrch/' . strtolower(get_class($this)) . '/search', $data);
        }
        
        /**
         * Loads the list view 
         */
        private function _load_list_view() {
            
            $data = [];
            $this->setTitle($data);
            
            $_SESSION[$this->sessionKey('level')] = self::LEVEL_LIST;
            
            $data['list'] = $_SESSION[$this->sessionKey('list')];
            
            $this->load->view('jobsrch/header', $data);
            $this->loadListPanel($data);
            $this->load->view('jobsrch/footer');
        }
        
        /**
         * Loads the list panel.
         * @param array $data Data for the panel.
         */
        protected function loadListPanel($data) {
            $this->load->view('jobsrch/' . strtolower(get_class($this)) . '/list', $data);
        }
        
        /**
         * Loads the detail view.
         */
        private function _load_detail_view() {
            
            $data = [];
            $this->setTitle($data);
            
            $_SESSION[$this->sessionKey('level')] = self::LEVEL_DETAIL;
            
            $data['detail'] = $_SESSION[$this->sessionKey('detail')];
            $this->detailButtonState($data, $data['detail']);
            
            $this->load->view('jobsrch/header', $data);
            $this->loadDetailPanel($data);
            $this->load->view('jobsrch/footer');
        }
        
        /**
         * Loads the detail panel.
         * @param array $data Data for the panel.
         */
        protected function loadDetailPanel($data) {
            $this->load->view('jobsrch/' . strtolower(get_class($this)) . '/detail', $data);
        }
        
        /**
         * Sets the button state for any buttons using it. By default it sets the 'delete' button state to 'disabled' if the detail entity does not have its 'id' set (assumes it's a new one).
         * @param array $data Data array for the view.
         * @param Object $detail Object with the detail.
         * @return array array with button states.
         */
        protected function detailButtonState(&$data, $detail) {
            
            $btn_state = array();
            
            if( ! isset($detail->id))
                $btn_state['delete'] = 'disabled';
            else
                $btn_state['delete'] = '';
            
            $data['button_state'] = $btn_state;
        }
        
    }
?>