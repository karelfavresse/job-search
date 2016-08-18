<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once __DIR__ . '/Abstract_model.php';
    require_once 'Recruiter_entity.php';
    require_once 'Recruiter_criteria.php';
    
    class Recruiter_model extends Abstract_model {
        
        protected function tableName() {
            return 'job_recruiter';
        }
        
        protected function idSequenceName() {
            return 'job_recruiter_id_seq';
        }
        
        protected function addCriteria($crit) {
            
            $this->_add_crit($crit, 'name');
            $this->_add_crit($crit, 'contact_name');
        }
        
        private function _add_crit($crit, $attrName) {
            
            if ( ! empty($crit->{$attrName}) ) {
                if ( $crit->match_any )
                    $this->db->or_like('upper('.$attrName.')', strtoupper($crit->{$attrName}));
                else
                    $this->db->like('upper('.$attrName.')', strtoupper($crit->{$attrName}));
            }
            
        }
        
        protected function entityName() {
            return 'Recruiter_entity';
        }

        public function toData($entity, $incl_id = TRUE) {
            
            $data = parent::toData($entity, $incl_id);
            
            $this->toDataAttribute($entity, 'name', $data);
            $this->toDataAttribute($entity, 'contact_name', $data);
            $this->toDataAttribute($entity, 'email_address', $data);
            $this->toDataAttribute($entity, 'contact_name', $data);
            $this->toDataAttribute($entity, 'address_id', $data);
            $this->toDataAttribute($entity, 'phone_number', $data);
            
            return $data;
        }
        
        public function loadFromData($data, $entity = NULL) {
            
            $entity = parent::loadFromData($data, $entity);
            
            $this->loadAttributeFromData($data, 'name', $entity);
            $this->loadAttributeFromData($data, 'contact_name', $entity);
            $this->loadAttributeFromData($data, 'email_address', $entity);
            $this->loadAttributeFromData($data, 'contact_name', $entity);
            $this->loadAttributeFromData($data, 'address_id', $entity);
            $this->loadAttributeFromData($data, 'phone_number', $entity);

            return $entity;
        }
    
    }
