<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once __DIR__ . '/Abstract_entity.php';
    
    class AdAction_entity extends Abstract_entity {

        public $job_ad_id;
        public $date;
        public $type;
        public $comment;
    }

?>