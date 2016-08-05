<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class UIMessage {
        
        protected static function addMessage($type, $text) {
            
            if( ! isset($_SESSION['UIMessages_'.$type])) {
                $_SESSION['UIMessages_'.$type] = array();
            }
            $msg =& $_SESSION['UIMessages_'.$type];
            $msg[] = $text;
        }
        
        public static function clearAll() {
            unset($_SESSION['UIMessages_info']);
            unset($_SESSION['UIMessages_error']);
            unset($_SESSION['UIMessages_success']);
            unset($_SESSION['UIMessages_warn']);
        }
        
        public static function addSuccess($text) {
            self::addMessage('success', $text);
        }
        
        public static function addInfo($text) {
            self::addMessage('info', $text);
        }
        
        public static function addWarning($text) {
            self::addMessage('warn', $text);
        }
        
        public static function addError($text) {
            self::addMessage('error', $text);
        }
        
        protected static function getMessages($type) {
            if( ! isset($_SESSION['UIMessages_'.$type]))
                return NULL;
            return $_SESSION['UIMessages_'.$type];
        }
        
        public static function getSuccessMessages() {
            return self::getMessages('success');
        }
        
        public static function getInfoMessages() {
            return self::getMessages('info');
        }
        
        public static function getWarningMessages() {
            return self::getMessages('warn');
        }
        
        public static function getErrorMessages() {
            return self::getMessages('error');
        }
        
    }
?>