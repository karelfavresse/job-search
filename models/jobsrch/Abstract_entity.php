<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    abstract class Abstract_entity {
        
        public $id;
        public $version = 1;
        
        // Note : don't use a constructor to set the initial values. The constructor is called AFTER the properties have been set by CI, thus the "initial" values will overwrite the fetched values.
        // https://github.com/bcit-ci/CodeIgniter/issues/4199
    }
    
?>