<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once 'Abstract_model.php';
    require_once 'AdAction_entity.php';
    require_once 'AdAction_criteria.php';
    
    class AdAction_model extends Abstract_model {
        
        protected function tableName() {
            return 'job_action';
        }
        
        protected function idSequenceName() {
            return 'job_action_id_seq';
        }
        
        protected function addCriteria($crit) {
            
            if ( ! empty($crit->job_ad_id))
                $this->db->where('job_ad_id', $crit->job_ad_id);
            
        }
        
        protected function entityName() {
            return 'AdAction_entity';
        }
        
        /**
         * Deletes all actions for the specified ID.
         * @param integer $adId ID of the ad for which to delete actions
         * @return boolean TRUE if the delete succeeded, FALSE if not.
         */
        public function deleteForAdId($adId) {
            
            $crit = new AdAction_criteria(array('job_ad_id' => $adId));
            $actions = $this->search($crit);
            $actionIds = array_keys($actions);
            return $this->deleteList($actionIds, FALSE);
        }
        
        public function toData($entity, $incl_id = TRUE) {
            
            $data = parent::toData($entity, $incl_id);
            
            $this->toDataAttribute($entity, 'job_ad_id', $data);
            $this->toDataAttribute($entity, 'date', $data);
            $this->toDataAttribute($entity, 'type', $data);
            $this->toDataAttribute($entity, 'comment', $data);
            
            return $data;
        }
        
        public function loadFromData($data, $entity = NULL) {
            
            $entity = parent::loadFromData($data, $entity);
            
            $this->loadAttributeFromData($data, 'job_ad_id', $entity);
            $this->loadAttributeFromData($data, 'date', $entity);
            $this->loadAttributeFromData($data, 'type', $entity);
            $this->loadAttributeFromData($data, 'comment', $entity);
            
            return $entity;
        }
        
    }
    
    ?>