<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once __DIR__.'/Abstract_criteria.php';
    
    class Ad_criteria extends Abstract_criteria {
        
        public $title;
        public $url;
        public $vdab_reference;
        public $company;
        public $contact_name;
        
        public function __construct($data = array()) {
            parent::__construct($data);
            
            $this->load($data, 'title', '');
            $this->load($data, 'url', '');
            $this->load($data, 'vdab_reference', '');
            $this->load($data, 'company', '');
            $this->load($data, 'contact_name', '');
            
        }
    }
    
?>