<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function default_if_null($value, $default) {
    if(isset($value) and $value !== NULL)
        return $value;
    return $default;
}
    

?>