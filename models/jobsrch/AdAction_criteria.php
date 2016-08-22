<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once __DIR__.'/Abstract_criteria.php';
    
    class AdAction_criteria extends Abstract_criteria {
        
        public $job_ad_id;
        
        public function __construct($data = array()) {
            parent::__construct($data);
            
            $this->load($data, 'job_ad_id', 0);
            
        }
    }
    
    ?>