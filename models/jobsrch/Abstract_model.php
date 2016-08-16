<?php /* Copyright 2016 Karel Favresse */ ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once 'Abstract_criteria.php';
    
    abstract class Abstract_model extends CI_Model {
        
        protected abstract function tableName();
        
        protected abstract function idSequenceName();
        
        protected abstract function addCriteria($crit);
        
        protected abstract function entityName();
        
        public function __construct() {
            $this->load->database();
        }
        
        public function search($crit, $start = -1, $length = 0, $sort = NULL, &$totalRows = NULL) {
            
            $this->db->from($this->tableName());
            $this->addCriteria($crit);
            if($start >= 0 and $length > 0 )
                $this->db->limit($start * $length);
            if($sort !== NULL) {
                foreach($sort as $sorte) {
                    $this->db->order_by($sorte['column'], $sorte['dir']);
                }
            }
            
            $query = $this->db->get();
            
            if($totalRows !== NULL)
                $totalRows = $query->num_rows();
            
            if($length < 1) {
                $length = $query->num_rows();
                if($start > 0)
                    $length -= $start;
            }
            
            if($start > 0) {
                if( ! $query->data_seek($start) ) {
                    // Not supported by database driver.
                    // Read all rows until $start is reached. Wastes resources, but currently no other way to stay DB independent...
                    for($i = 0; $i < $start ; $i++)
                        $query->unbuffered_row();
                }
            }
            
            $data = array();
            for($i = 0 ; $i < $length ; $i++) {
                $obj = $query->unbuffered_row($this->entityName());
                if ( $obj === FALSE )
                    break;
                $data[] = $obj;
            }
            
            return $data;
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
            $this->db->set($this->toData($detail));
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
        
        /**
         * Returns all entities for the IDs specified in the parameter array.
         * @param array $idArray array with IDs
         * @return array array with addresses for the specified IDs
         */
        public function getList($idArray) {
            
            if ( empty ( $idArray ) )
                return array();
            
            $this->db->from($this->tableName());
            $this->db->where_in('id', $idArray);
            
            $query = $this->db->get();
            
            return $query->custom_result_object($this->entityName());
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
                $this->toDataAttribute($entity, 'id', $data);
            $this->toDataAttribute($entity, 'version', $data);
            return $data;
        }
        
        /**
         * Stores the specified attribute of the entity into the data array.
         * @param Object $entity Entity containing the attribute
         * @param string $attributeName name of the attribute to store.
         * @param array &$data reference to the array to store the data in
         */
        protected function toDataAttribute($entity, $attributeName, &$data) {
            $data[$attributeName] = $entity->{$attributeName};
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

            $this->loadAttributeFromData($data, 'id', $entity);
            $this->loadAttributeFromData($data, 'version', $entity);
            
            return $entity;
        }
        
        /**
         * Loads the element from $data array specified by $attributeName into the attribute of the entity named $attributeName.
         * @param array $data array containing the data for this entity
         * @param string $attributeName name of attribute to load
         * @param Object $entity Entity to contain the data
         */
        protected function loadAttributeFromData($data, $attributeName, $entity) {
            if(isset($data[$attributeName]))
                $entity->{$attributeName} = $data[$attributeName];
        }
        
    }
    
?>