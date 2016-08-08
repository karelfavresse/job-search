<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once 'Abstract_model.php';

    class Address_model extends Abstract_model {
        
        protected function tableName() {
            return 'job_address';
        }
        
        protected function idSequenceName() {
            return 'job_address_id_seq';
        }
        
        protected function addCriteria($crit) {
            
            $this->addCriteriaAttribute($crit, 'street');
            $this->addCriteriaAttribute($crit, 'house_number');
            $this->addCriteriaAttribute($crit, 'box_number');
            $this->addCriteriaAttribute($crit, 'pobox_number');
            $this->addCriteriaAttribute($crit, 'zip_code');
            $this->addCriteriaAttribute($crit, 'country');
            $this->addCriteriaAttribute($crit, 'locality');
        }
        
        private function addCriteriaAttribute($crit, $attributeName) {
            
            if ( empty($crit->{$attributeName}) )
                $this->db->where($attributeName, '');
            else
                $this->db->where($attributeName, $crit->{$attributeName});
        }
        
        protected function addOrderBy($crit) {
        }
        
        protected function entityName() {
            return 'Address_entity';
        }
        
        public function toData($entity, $incl_id = TRUE) {
            
            $data = parent::toData($entity, $incl_id);
            
            $this->toDataAttribute($entity, 'street', $data);
            $this->toDataAttribute($entity, 'house_number', $data);
            $this->toDataAttribute($entity, 'box_number', $data);
            $this->toDataAttribute($entity, 'pobox_number', $data);
            $this->toDataAttribute($entity, 'zip_code', $data);
            $this->toDataAttribute($entity, 'country', $data);
            $this->toDataAttribute($entity, 'locality', $data);

            return $data;
        }
        
        public function loadFromData($data, $entity = NULL) {
            
            $entity = parent::loadFromData($data, $entity);

            $this->loadAttributeFromData($data, 'street', $entity);
            $this->loadAttributeFromData($data, 'house_number', $entity);
            $this->loadAttributeFromData($data, 'box_number', $entity);
            $this->loadAttributeFromData($data, 'pobox_number', $entity);
            $this->loadAttributeFromData($data, 'zip_code', $entity);
            $this->loadAttributeFromData($data, 'country', $entity);
            $this->loadAttributeFromData($data, 'locality', $entity);
            
            return $entity;
        }
        
    }
    
?>