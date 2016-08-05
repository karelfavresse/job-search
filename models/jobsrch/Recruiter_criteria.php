<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once __DIR__.'/Abstract_criteria.php';
    
    class Recruiter_criteria extends Abstract_criteria {
    
        public $name;
        
        public function __construct($data) {
            parent::__construct($data);
            
            if(isset($data['name']))
                $this->name = $data['name'];
            else
                $this->name = '';
        }
        
    }

?>
