<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Abstract_criteria {
    
        public $maxrows;
        
        public function __construct($data = array()) {
        
            $this->load($data, 'maxrows', 100);
        }
        
        protected function load(&$data, $name, $default) {
            if(isset($data[$name]))
                $this->{$name} = $data[$name];
            else
                $this->{$name} = $default;
        }
    
    }

?>
