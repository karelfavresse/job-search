<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once __DIR__.'/Abstract_criteria.php';
    
    class Recruiter_criteria extends Abstract_criteria {
    
        public $name;
        public $contact_name;
        
        public function __construct($data = array()) {
            parent::__construct($data);
            
            $this->load($data, 'name', '');
            $this->load($data, 'contact_name', '');
        }
        
    }

?>
