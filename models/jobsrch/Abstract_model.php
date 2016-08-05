<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once 'Abstract_criteria.php';
    
    abstract class Abstract_model extends CI_Model {
        
        protected abstract function tableName();
        
        protected abstract function idSequenceName();
        
        protected abstract function addCriteria($crit);
        
        protected abstract function addOrderBy($crit);
        
        protected abstract function entityName();
        
        public function __construct() {
            $this->load->database();
        }
        
        public function search($crit) {
            
            $this->db->from($this->tableName());
            $this->addCriteria($crit);
            $this->addOrderBy($crit);
            $this->db->limit($crit->maxrows);
            
            $query = $this->db->get();
            
            return $query->custom_result_object($this->entityName());
        }
        
        public function insert($detail) {
            if($this->db->insert($this->tableName(), $this->toData($detail, FALSE))) {
                $detail->id = $this->db->insert_id($this->idSequenceName());
                return $detail;
            } else
                return FALSE;
        }
        
        public function update($detail) {
            $this->db->where('id', $detail->id);
            $this->db->where('version', $detail->version);
            $detail->version++;
            $this->db->set($detail);
            if($this->db->update($this->tableName())) {
                if($this->db->affected_rows() > 0)
                    return $detail;
                else {
                    // TODO should give indication of type of error...
                    $detail->version--;
                    return FALSE;
                }
            }
            else {
                $detail->version--;
                return FALSE;
            }
        }
        
        public function get($id) {
            
            $query = $this->db->get_where($this->tableName(), array('id' => $id), 1);
            return $query->custom_row_object(0, $this->entityName());
        }
        
        public function delete($detail) {
            return $this->db->delete($this->tableName(), array('id' => $detail->id, 'version' => $detail->version)) !== FALSE;
        }
        
        /**
         * Returns an array with the data form the specified entity.
         * @param Object $entity The entity whose data to return as an array.
         * @param boolean $incl_id Whether or not to include the id property. Default is TRUE.
         * @return array An array containing the data of the entity.
         */
        public function toData($entity, $incl_id = TRUE) {
            $data = array();
            if($incl_id)
                $data['id'] = $entity->id;
            $data['version'] = $entity->version;
            return $data;
        }
        
        /**
         * Loads entity content from the specified data array. This is the reverse of toData().
         * @param array $data array containing the data for this entity.
         * @param Object $entity reference to the entity to fill with data. If NULL, a new entity is created.
         * @return Object The $entity parameter if not NULL, otherwise a new entity. This entity has been filled with the data from the $data array.
         */
        public function loadFromData($data, $entity = NULL) {
            
            if($entity === NULL)
                $entity = new $this->entityName();

            if(isset($data['id']))
                $entity->id = $data['id'];
            if(isset($data['version']))
                $entity->version = $data['version'];
            
            return $entity;
        }
        
    }
    
?>