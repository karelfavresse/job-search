<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once __DIR__ . '/Abstract_entity.php';
    
    class Ad_entity extends Abstract_entity {
        
        public $title;
        public $url;
        public $vdab_reference;
        public $recruiter_id;
        public $company;
        public $company_address_id;
        public $phone_number;
        public $email_address;
        public $contact_name;

    }
    
?>