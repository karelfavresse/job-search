<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once 'Abstract_model.php';
    require_once 'Ad_entity.php';
    require_once 'Ad_criteria.php';
    
    class Ad_model extends Abstract_model {
        
        protected function tableName() {
            return 'job_ad';
        }
        
        protected function idSequenceName() {
            return 'job_ad_id_seq';
        }
        
        protected function addCriteria($crit) {
            
            $this->addCriteriaAttribute($crit, 'title');
            $this->addCriteriaAttribute($crit, 'url');
            $this->addCriteriaAttribute($crit, 'vdab_reference');
            $this->addCriteriaAttribute($crit, 'company');
            $this->addCriteriaAttribute($crit, 'contact_name');
            
        }
        
        private function addCriteriaAttribute($crit, $attributeName) {
            
            if ( ! empty($crit->{$attributeName}) )
                $this->db->like('upper('.$attributeName.')', strtoupper($crit->{$attributeName}));
        }
        
        protected function entityName() {
            return 'Ad_entity';
        }
        
        public function toData($entity, $incl_id = TRUE) {
            
            $data = parent::toData($entity, $incl_id);
            
            $this->toDataAttribute($entity, 'title', $data);
            $this->toDataAttribute($entity, 'url', $data);
            $this->toDataAttribute($entity, 'vdab_reference', $data);
            $this->toDataAttribute($entity, 'recruiter_id', $data);
            $this->toDataAttribute($entity, 'company', $data);
            $this->toDataAttribute($entity, 'company_address_id', $data);
            $this->toDataAttribute($entity, 'phone_number', $data);
            $this->toDataAttribute($entity, 'email_address', $data);
            $this->toDataAttribute($entity, 'contact_name', $data);
            
            return $data;
        }
        
        public function loadFromData($data, $entity = NULL) {
            
            $entity = parent::loadFromData($data, $entity);
            
            $this->loadAttributeFromData($data, 'title', $entity);
            $this->loadAttributeFromData($data, 'url', $entity);
            $this->loadAttributeFromData($data, 'vdab_reference', $entity);
            $this->loadAttributeFromData($data, 'recruiter_id', $entity);
            $this->loadAttributeFromData($data, 'company', $entity);
            $this->loadAttributeFromData($data, 'company_address_id', $entity);
            $this->loadAttributeFromData($data, 'phone_number', $entity);
            $this->loadAttributeFromData($data, 'email_address', $entity);
            $this->loadAttributeFromData($data, 'contact_name', $entity);
            
            return $entity;
        }
        
    }
    
?>