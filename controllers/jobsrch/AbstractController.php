<?php /* Copyright 2016 Karel Favresse */  ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once 'UIMessage.php';
    require_once 'Language.php';

    abstract class AbstractController extends CI_Controller {
        
        const LEVEL_SEARCH = 0;
        const LEVEL_LIST = 1;
        const LEVEL_DETAIL = 2;
        
        public function __construct() {
            parent::__construct();
            $this->loadModel();
            $this->load->helper('url_helper');
            $this->load->library('session');
            Language::setup();
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
                        $this->index_page();
                    else
                        $this->process_search();
                    break;
                case self::LEVEL_LIST :
                    $this->process_list();
                    break;
                case self::LEVEL_DETAIL :
                    $this->process_detail();
                    break;
            }
            
            session_write_close();
        }
        
        /**
         * Processes the index page for this controller. Shows the search page. 
         */
        protected function index_page() {
            
            if( ! isset($_SESSION[$this->sessionKey('crit')]))
                $_SESSION[$this->sessionKey('crit')] = $this->createCriteria();
            $this->load_search_view();
        }
        
        /**
         * Processes the search level. Dispatches to other methods according to the 'action' hidden input field.
         */
        protected function process_search() {
            
            if ( ! isset($_SESSION[$this->sessionKey('crit')]))
                $_SESSION[$this->sessionKey('crit')] = $this->createCriteria($this->input->post());
            
            if(NULL !== $this->input->post('action')) {
                if( ! $this->processSearchAction($this->input->post('action')) )
                    $this->load_search_view();
            } else {
                $this->load_search_view();
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
                    $this->reset();
                    break;
                case 'search' :
                    $this->search();
                    break;
                case 'new' :
                    $this->doNew('search');
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
        protected function doNew($fromPage) {
            
            $_SESSION[$this->sessionKey('detail')] = $this->createEntity();
            $_SESSION[$this->sessionKey('detail_frompage')] = $fromPage;
            
            $this->preNew();
            
            $this->load_detail_view();
        }
        
        /**
         * Sets up additional entries in the session if required by the load_detail_view() method.
         */
        protected function preNew() {
            // NOOP
        }
        
        /**
         * Stores the search criteria from the request into session, searches the database using those criteria, and then displays the list screen.
         * Validates search criteria.
         */
        protected function search() {
            
            $this->load->library('form_validation');
            
            $useValidation = $this->setupCriteriaValidationRules();
            
            $crit = $this->createCriteria($this->input->post());
            $_SESSION[$this->sessionKey('crit')] = $crit;
            
            if ( ! $useValidation OR $this->form_validation->run() !== FALSE)
            {
                $this->doSearch();
                $this->load_list_view();
            } else {
                $this->load_search_view();
            }
        }
        
        /**
         * Sets up validation rules for criteria. Default implementation sets a single rule for the 'maxrows' input element.
         * @return TRUE if validation is to be used, FALSE if not.
         */
        protected function setupCriteriaValidationRules() {
            
            $this->form_validation->set_rules('maxrows', lang('label-search-maxrows'), 'trim|required|integer');
            
            return TRUE;
        }
        
        /**
         * Resets the search criteria to their default values, and shows the search screen again.
         */
        protected function reset() {
            
            $_SESSION[$this->sessionKey('crit')] = $this->createCriteria();
            $this->load_search_view();
        }
        
        /**
         * Processes the list level. Dispatches to other methods according to the 'action' hidden input field.
         */
        protected function process_list() {
            
            if(NULL !== $this->input->post('action')) {
                if ( ! $this->processListAction($this->input->post('action')) )
                    $this->load_list_view();
            } else {
                $this->load_list_view();
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
                    $this->refresh();
                    break;
                case 'back' :
                    $this->back('search');
                    break;
                case 'new' :
                    $this->doNew('list');
                    break;
                case 'edit' :
                    $this->startEdit();
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
        protected function back($page) {
            
            switch($page) {
                case 'search' :
                    unset($_SESSION[$this->sessionKey('detail')]);
                    unset($_SESSION[$this->sessionKey('list')]);
                    $this->load_search_view();
                    break;
                case 'list' :
                    unset($_SESSION[$this->sessionKey('detail')]);
                    $this->load_list_view();
                    break;
            }
            
            unset($_SESSION[$this->sessionKey('detail_frompage')]);
        }
        
        /**
         * Reloads data from the database using the search criteria stored in the session. Shows the list screen
         */
        protected function refresh() {
            
            $this->doSearch();
            $this->load_list_view();
        }
        
        /**
         * Perform the actual search and set the list.
         */
        protected function doSearch() {
            
            if(isset($_SESSION[$this->sessionKey('crit')]))
                $rows = $this->recruiter_model->search($_SESSION[$this->sessionKey('crit')]);
            else {
                $_SESSION[$this->sessionKey('crit')] = $this->createCriteria();
                $rows = array();
            }
            $list = array();
            foreach ($rows as $entry) {
                $list[$entry->id] = $entry;
            }
            $_SESSION[$this->sessionKey('list')] = $list;
        }
        
        /**
         * Retrieves a fresh copy for the detail ID, then shows the detail page.
         * If no row can be found for the detail ID, and error is shown instead.
         */
        protected function startEdit() {
            
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
            
            $this->startEditInternal($detail);
            
            $this->load_detail_view();
        }
        
        /**
         * Sets the specified $detail as the edited detail in the session. Override to set additional elements.
         * @param Object $detail The detail to set
         */
        protected function startEditInternal($detail) {
            
            $_SESSION[$this->sessionKey('detail')] = $detail;
        }
        
        /**
         * Saves the current detail and shows the detail screen again.
         */
        protected function save() {
            
            $this->load->library('form_validation');
            
            $useValidation = $this->setupDetailValidationRules();
            
            $detail = $_SESSION[$this->sessionKey('detail')];
            $detail = $this->getModel()->loadFromData($this->input->post(), $detail);
            if ( ! $useValidation OR $this->form_validation->run() !== FALSE)
            {
                
                $d = $this->preSave($detail);
                if($d === FALSE) {
                    UIMessage::addError('Save aborted');
                } else {
                    $detail = $d;
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
            }
            $_SESSION[$this->sessionKey('detail')] = $detail;
            
            $this->load_detail_view();
        }
        
        /**
         * Perform any actions before the actual save takes place.
         * @aparam Object $detail the detail about to be saved
         * @return mixed Either the object to be saved if OK, otherwise FALSE (don't save). If returning FALSE, this method should set any error message indicating the reason (UIMessage).
         */
        protected function preSave($detail) {
            // NOOP
        }
        
        /**
         * Processes the detail level. Dispatches to other methods according to the 'action' hidden input field.
         */
        protected function process_detail() {
            
            if(NULL !== $this->input->post('action')) {
                if ( ! $this->processDetailAction($this->input->post('action')) )
                    $this->load_detail_view();
            } else {
                $this->load_detail_view();
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
                    $this->save();
                    break;
                case 'back' :
                    if(isset($_SESSION[$this->sessionKey('detail_frompage')]))
                        $toPage = $_SESSION[$this->sessionKey('detail_frompage')];
                    else
                        $toPage = 'list';
                    $this->back($toPage);
                    break;
                case 'delete' :
                    $this->delete();
                    break;
                default :
                    return FALSE;
            }
            return TRUE;
        }
        
        /**
         * Deletes the current detail and shows the list screen again.
         */
        protected function delete() {
            
            $detail = $_SESSION[$this->sessionKey('detail')];
            if($this->getModel()->delete($detail)) {
                unset($_SESSION[$this->sessionKey('detail')]);
                UIMessage::addInfo($this->getName() . ' "' . $detail->name . '" deleted.');
                $list =& $_SESSION[$this->sessionKey('list')];
                unset($list[$detail->id]);
                $this->load_list_view();
            } else {
                UIMessage::addError('Failed to delete ' . $this->getName() );
                $this->load_detail_view();
            }
        }
        
        /**
         * Loads the search view
         */
        protected function load_search_view() {
            
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
        protected function load_list_view() {
            
            $data = [];
            $this->setTitle($data);
            
            $_SESSION[$this->sessionKey('level')] = self::LEVEL_LIST;
            
            $data['list'] = $_SESSION[$this->sessionKey('list')];
            
            $this->set_list_data($data);
            
            $this->load->view('jobsrch/header', $data);
            $this->loadListPanel($data);
            $this->load->view('jobsrch/footer');
        }
        
        /**
         * Set the list data for the view. The 'title' and 'list' entries have already been set.
         * @param array &$data data array sent to the view.
         */
        protected function set_list_data(&$data) {
            // NOOP
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
        protected function load_detail_view() {
            
            $data = [];
            $this->setTitle($data);
            
            $_SESSION[$this->sessionKey('level')] = self::LEVEL_DETAIL;
            
            $data['detail'] = $_SESSION[$this->sessionKey('detail')];
            
            $this->set_detail_data($data);
            
            $this->detailButtonState($data, $data['detail']);
            
            $this->load->view('jobsrch/header', $data);
            $this->loadDetailPanel($data);
            $this->load->view('jobsrch/footer');
        }
        
        /**
         * Set the detail data for the view. The 'title' and 'detail' entries have already been set.
         * @param array &$data data array sent to the view.
         */
        protected function set_detail_data(&$data) {
            // NOOP
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