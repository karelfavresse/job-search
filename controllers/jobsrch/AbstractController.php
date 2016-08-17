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
            $this->load->library('jobsrch/Authorization_library');
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
         * Returns the type in singular form. This type is used to load the correct translation.
         * @return string Type in singular form.
         */
        protected abstract function singularType();
        
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
        
        /**
         * Returns the URL segment for the controller.
         */
        protected abstract function urlSegment();
        
        /**
         * Return an array containing the data for the list.
         */
        protected abstract function list_data($data);
        
        /**
         * Returns the attribute name for the table column in the list.
         */
        protected abstract function attribute_for_column($column);
        
        /* ====== End of abstract methods ====== */
        
        protected function setLevel($level) {
            $_SESSION[$this->sessionKey('level')] = $level;
        }
        
        /**
         * Returns the authority code to create an entity.
         * @return mixed the authority code, or FALSE if no authority is required.
         */
        protected function createAuthCode() {
            return FALSE;
        }
        
        /**
         * Returns the authority code to update an entity.
         * @return mixed the authority code, or FALSE if no authority is required.
         */
        protected function updateAuthCode() {
            return FALSE;
        }
        
        /**
         * Returns the authority code to delete an entity.
         * @return mixed the authority code, or FALSE if no authority is required.
         */
        protected function deleteAuthCode() {
            return FALSE;
        }
        
        public function can_create() {
            $ac = $this->createAuthCode();
            if( ! $ac )
                return TRUE;
            return $this->authorization_library->is_authorized($ac);
        }
        
        public function can_update() {
            $ac = $this->updateAuthCode();
            if( ! $ac )
                return TRUE;
            return $this->authorization_library->is_authorized($ac);
        }
        
        public function can_delete() {
            $ac = $this->deleteAuthCode();
            if( ! $ac )
                return TRUE;
            return $this->authorization_library->is_authorized($ac);
        }
        
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
         * Main entry for the controller. Dispatches based on action field.
         */
        public function index() {
            
            $this->load->helper('form');
            
            // Perform action if specified
            $action = $this->input->post('action');
            if ( $action != NULL )
                $this->$action();
            
            if(isset($_SESSION[$this->sessionKey('level')]))
                $level = $_SESSION[$this->sessionKey('level')];
            else
                $level = self::LEVEL_SEARCH;
            
            if( ! isset($_SESSION[$this->sessionKey('crit')]))
                $_SESSION[$this->sessionKey('crit')] = $this->createCriteria();
            
            // If no action was specified, then display the whole page again. Probably a browser refresh, or user returning from a bookmark.
            if ( $action === NULL ) {
                $data = array();
                $this->setTitle($data);
                $data['controller_name'] = $this->urlSegment();
                $this->load->view('jobsrch/header', $data);
            }
            
            switch($level) {
                case self::LEVEL_SEARCH :
                    $this->load_search_view();
                    break;
                case self::LEVEL_LIST :
                    $this->load_list_view();
                    break;
                case self::LEVEL_DETAIL :
                    $this->load_detail_view();
                    break;
            }
            
            if ( $action === NULL )
                $this->load->view('jobsrch/footer');

            session_write_close();
        }
        
        /**
         * Creates a blank Recruiter object, then renders the detail screen.
         */
        public function create() {
            
            $_SESSION[$this->sessionKey('detail')] = $this->createEntity();
            
            $this->preNew();
            
            $this->setLevel(self::LEVEL_DETAIL);
        }
        
        /**
         * Sets up additional entries in the session if required by the load_detail_view() method.
         */
        protected function preNew() {
            // NOOP
        }
        
        /**
         * Validates search criteria, then performs search. 
         */
        public function search() {
            
            $this->load->library('form_validation');
            
            $useValidation = $this->setupCriteriaValidationRules();
            
            $crit = $this->createCriteria($this->input->post());
            $_SESSION[$this->sessionKey('crit')] = $crit;
            
            if ( ! $useValidation OR $this->form_validation->run() !== FALSE) {
                $_SESSION[$this->sessionKey('list_display_start')] = 0;
                if ( ! isset($_SESSION[$this->sessionKey('list_page_length')]) )
                    $_SESSION[$this->sessionKey('list_page_length')] = 10;
                if ( ! isset($_SESSION[$this->sessionKey('list_order')]) )
                    $_SESSION[$this->sessionKey('list_order')] = array(array(0, 'asc'));
                $this->setLevel(self::LEVEL_LIST);
            }
        }
        
        /**
         * Sets up validation rules for criteria. 
         * @return TRUE if validation is to be used, FALSE if not.
         */
        protected function setupCriteriaValidationRules() {
            
            return FALSE;
        }
        
        /**
         * Resets the search criteria to their default values, and shows the search screen again.
         */
        public function reset() {
            
            $_SESSION[$this->sessionKey('crit')] = $this->createCriteria();
        }
        
        /**
         * Processes the Back button, setting the 'crit' entry in the $data array from session. It then shows the specified page (search or list).
         * @param string $page Page to actually show (search or list).
         */
        public function back() {
            
            $level = $_SESSION[$this->sessionKey('level')] - 1;
            switch($level) {
                case self::LEVEL_SEARCH :
                    unset($_SESSION[$this->sessionKey('detail')]);
                    break;
                case self::LEVEL_LIST :
                    unset($_SESSION[$this->sessionKey('detail')]);
                    break;
            }
            $this->setLevel($level);
        }
        
        /**
         * Reloads data from the database using the search criteria stored in the session. Shows the list screen
         */
        public function refresh() {
            // Done automatically by server side datatables
        }
        
        /**
         * Used when paging the DataTable. Start and row count are included in the POST parameters.
         */
        public function listdata() {

            // Extract parameters from POST (start, limit, draw ID).
            $draw = (integer)$this->input->post('draw');
            $start = (integer)$this->input->post('start');
            $length = (integer)$this->input->post('length');
            $columns = $this->input->post('columns');
            $order = $this->input->post('order');
            
            $_SESSION[$this->sessionKey('list_display_start')] = $start;
            $_SESSION[$this->sessionKey('list_page_length')] = $length;

            $crit = $_SESSION[$this->sessionKey('crit')];
            
            // Determine on which columns to sort
            $sort = array();
            $list_order = array();
            foreach($order as $oe) {
                // Column in order array is the index to the column, but the model needs the attribute name.
                $sort[] = array('column' => $this->attribute_for_column($columns[$oe['column']]['data']), 'dir' => $oe['dir']);
                if ( $oe['dir'] !== 'asc' && $oe['dir'] !== 'desc' )
                    $oe['dir'] = 'asc';
                $list_order[] = array($oe['column'], $oe['dir']);
            }
            $_SESSION[$this->sessionKey('list_order')] = $list_order;
            
            // Load the data as required
            $totalRows = 0;
            $data = $this->getModel()->search($crit, $start, $length, $sort, $totalRows);
            
            // Turn found data into JSON and return
            $json = array('draw' => $draw, 'recordsTotal' => $totalRows, 'recordsFiltered' => $totalRows, 'data' => $this->list_data($data));
            
            echo json_encode($json);
        }
        
        /**
         * Retrieves a fresh copy for the detail ID, then shows the detail page.
         * If no row can be found for the detail ID, and error is shown instead.
         */
        public function startEdit() {
            
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
            $this->setLevel(self::LEVEL_DETAIL);
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
        public function save() {
            
            $this->load->library('form_validation');
            
            $useValidation = $this->setupDetailValidationRules();
            
            $detail = $_SESSION[$this->sessionKey('detail')];
            $detail = $this->getModel()->loadFromData($this->input->post(), $detail);
            $extra = $this->loadExtraFromData($this->input->post());
            if ( ! $useValidation OR $this->form_validation->run() !== FALSE)
            {
                $this->db->trans_start();
                
                $d = $this->preSave($detail, $extra);
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
                        UIMessage::addInfo($this->getName() . ' saved.');
                        $this->postSave($detail, $extra);
                    }
                }
                
                $this->db->trans_complete();
            }
            $_SESSION[$this->sessionKey('detail')] = $detail;
        }
        
        /**
         * Load additional data not contained in the main entity. Return whatever was loaded. This will later be passed to the preSave() and postSave() methods.
         * @param array $data input from request
         * @return mixed the extra data loaded from the $data parameter, or NULL if no extra data applicable.
         */
        protected function loadExtraFromData($data) {
            return NULL;
        }
        
        /**
         * Perform any actions before the actual save takes place.
         * @param Object $detail the detail about to be saved
         * @param mixed $extra extra data loaded by the loadExtraFromData() method, or NULL if no such data was loaded.
         * @return mixed Either the object to be saved if OK, otherwise FALSE (don't save). If returning FALSE, this method should set any error message indicating the reason (UIMessage).
         */
        protected function preSave($detail, $extra) {
            // NOOP
        }
        
        /**
         * Perform any actions after the actual save takes place.
         * @param Object $detail the detail that was saved
         * @param mixed $extra extra data loaded by the loadExtraFromData() method.
         */
        protected function postSave($detail, $extra) {
            // NOOP
        }
        
        /**
         * Deletes the current detail and shows the list screen again.
         */
        public function delete() {
            
            $this->db->trans_start();
            
            $detail = $_SESSION[$this->sessionKey('detail')];
            if($this->getModel()->delete($detail)) {
                unset($_SESSION[$this->sessionKey('detail')]);
                UIMessage::addInfo($this->getName() . ' "' . $detail->name . '" deleted.');
                $this->setLevel(self::LEVEL_LIST);
            } else {
                UIMessage::addError('Failed to delete ' . $this->getName() );
            }
            
            $this->db->trans_complete();
        }
        
        /**
         * Loads the search view
         */
        protected function load_search_view() {
            
            $data = [];
            
            $data['crit'] = $_SESSION[$this->sessionKey('crit')];
            
            $data['can_create'] = $this->can_create();
            
            $this->loadSearchPanel($data);
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
            
            $data['can_create'] = $this->can_create();
            
            $data['list_display_start'] = $_SESSION[$this->sessionKey('list_display_start')];
            $data['list_page_length'] = $_SESSION[$this->sessionKey('list_page_length')];
            $data['list_order'] = $_SESSION[$this->sessionKey('list_order')];
            
            $this->set_list_data($data);
            
            $this->loadListPanel($data);
        }
        
        /**
         * Set the list data for the view. The 'title' entry has already been set.
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
            $data['type'] = $this->singularType();
            
            $data['detail'] = $_SESSION[$this->sessionKey('detail')];
            
            $data['can_update'] = $this->can_update();
            $data['can_delete'] = $this->can_delete();
            
            $this->set_detail_data($data);
            
            $this->detailButtonState($data, $data['detail']);
            
            $this->loadDetailPanel($data);
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