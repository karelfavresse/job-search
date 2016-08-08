<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once __DIR__ . '/Abstract_entity.php';
    
    class Address_entity extends Abstract_entity {
        
        public $street;
        public $house_number;
        public $box_number;
        public $pobox_number;
        public $zip_code;
        public $country;
        public $locality;
        
    }

?>