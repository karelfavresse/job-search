<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once dirname(dirname(__DIR__)) . '/models/jobsrch/Address_entity.php';
    require_once dirname(dirname(__DIR__)) . '/models/jobsrch/Address_criteria.php';
    
    class Address_library {
        
        protected $CI;
        
        public function __construct() {
            $this->CI =& get_instance();
            $this->CI->load->model('jobsrch/Address_model');
        }
        
        /**
         * Checks if the address specified in $address already exists in the database, and returns that. In this way the address table only contains unique addresses.
         * Comparison is done on the actual data columns, not the ID.
         * If the address does not yet exist, the $address parameter is returned.
         * @param Address_entity $address The address to check
         * @return Address_entity The existing address, or the $address parameter if the address does not yet exist.
         */
        public function checkDuplicate(Address_entity $address) {
            
            // Check if the address exists by searching for it.
            $crit = new Address_criteria($this->CI->address_model->toData($address, FALSE));
            $crit->maxrows = 1;
            $result = $this->CI->address_model->search($crit);
            
            // If no address found, return the address parameter itself. Unset the ID (if set) so a new address will be created.
            if(empty($result)) {
                unset($address->id);
                return $address;
            }
            
            // Otherwise, return the found address.
            return $result[0];
        }
        
        /**
         * Setup validation rules for address.
         * @return boolean TRUE if validation rules have been set, FALSE if not.
         */
        public function setupValidationRules() {
            
            $this->CI->load->library('form_validation');
            
            $this->CI->form_validation->set_rules('zip_code', lang('label-detail-address-zipcode'), 'trim|required');
            $this->CI->form_validation->set_rules('locality', lang('label-detail-address-locality'), 'trim|required');
            
            $this->CI->form_validation->set_rules('street', lang('label-detail-address-street'), array(
                                            'trim',
                                            array('check_street', array($this, 'check_street'))
                                            ));
            
            return TRUE;
        }
        
        /**
         * Rule to check if either street/house OR PO box are filled. 
         */
        public function check_street() {
            
            // Either enter street and house number (and optionally box number), or PO Box number, but not both.
            
            if ( empty ($this->CI->input->post('pobox_number'))) {
                // PO box not specified, must specify at least street and house number.
                if ( empty($this->CI->input->post('street')) || empty($this->CI->input->post('house_number'))  ) {
                    $this->CI->form_validation->set_message('check_street', lang('message-address-empty'));
                    return FALSE;
                }
            } else {
                // PO box specified, cannot specify street/house/box number.
                if ( ! empty($this->CI->input->post('street')) || ! empty($this->CI->input->post('house_number')) || ! empty($this->CI->input->post('box_number')) ) {
                    $this->CI->form_validation->set_message('check_street', lang('messsage-address-street-and-pobox'));
                    return FALSE;
                }
            }
            
            return TRUE;
        }
        
        /**
         * Returns a one line description of the address, suitable for display in lists or tables.
         * @return string The address on 1 line.
         */
        public function oneLineDescription(Address_entity $address) {
            
            if ( empty($address->pobox_number) ) {
                $str = $address->street . ' ' . $address->house_number;
                if ( ! empty($address->box_number) )
                    $str .= ' b. ' . $address->box_number;
            } else
                $str = $address->pobox_number;
            
            $str .= ', ' . $address->zip_code . ' ' . $address->locality;
            
            if ( ! empty ( $address->country ) )
                $str .= ', ' . $address_country;
            
            return $str;
        }
    }
    
?>