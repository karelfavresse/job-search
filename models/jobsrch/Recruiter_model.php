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
            
            if( ! empty ($crit->name))
                $this->db->like('name', $crit->name);
        }
        
        protected function addOrderBy($crit) {
            $this->db->order_by('name', 'ASC');
        }
        
        protected function entityName() {
            return 'Recruiter_entity';
        }

        public function toData($entity, $incl_id = TRUE) {
            return parent::toData($entity, $incl_id) + array('name' => $entity->name, 'email_address' => $entity->email_address, 'phone_number' => $entity->phone_number, 'contact_name' => $entity->contact_name, 'version' => $entity->version);
        }
        
        public function loadFromData($data, $entity = NULL) {
            
            $entity = parent::loadFromData($data, $entity);
            
            if(isset($data['name']))
                $entity->name=$data['name'];
            if(isset($data['contact_name']))
                $entity->contact_name = $data['contact_name'];
            if(isset($data['email_address']))
                $entity->email_address = $data['email_address'];
            if(isset($data['phone_number']))
                $entity->contact_name = $data['contact_name'];
            
            return $entity;
        }
    
    }
