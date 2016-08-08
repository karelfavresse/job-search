<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once __DIR__ . '/Abstract_entity.php';
    require_once __DIR__ . '/Address_entity.php';
    
    class Recruiter_entity extends Abstract_entity {
    
        public $name;
        public $address_id;
        public $email_address;
        public $phone_number;
        public $contact_name;
     
    }
    
?>