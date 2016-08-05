<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Abstract_criteria {
    
        public $maxrows;
        
        public function __construct($data) {
        
            if(isset($data['maxrows']))
                $this->maxrows = $data['maxrows'];
            else
                $this->maxrows = 100;
        }
    
    }

?>
