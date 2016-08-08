<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once __DIR__.'/Abstract_criteria.php';
    
    class Address_criteria extends Abstract_criteria {

        public $street;
        public $house_number;
        public $box_number;
        public $pobox_number;
        public $zip_code;
        public $country;
        public $locality;
        
        public function __construct($data) {
            parent::__construct($data);
            
            $this->load($data, 'street', '');
            $this->load($data, 'house_number', '');
            $this->load($data, 'box_number', '');
            $this->load($data, 'pobox_number', '');
            $this->load($data, 'zip_code', '');
            $this->load($data, 'country', '');
            $this->load($data, 'locality', '');
            
        }
    }

?>