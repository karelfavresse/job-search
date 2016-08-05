<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once 'Recruiter_entity.php';
    require_once 'Recruiter_criteria.php';
    
    class Recruiter_model extends CI_Model {
        
        public function __construct()
        {
            $this->load->database();
        }
        
        public function search(Recruiter_criteria $crit) {
            
            $this->db->from('job_recruiter');
            
            if( ! empty ($crit->name))
                $this->db->like('name', $crit->name);
            
            $this->db->order_by('name', 'ASC');
            
            $this->db->limit($crit->maxrows);
            
            $query = $this->db->get();
            
            return $query->custom_result_object('Recruiter_entity');
        }
        
        public function insert(Recruiter_entity $detail) {
            if($this->db->insert('job_recruiter', $this->toData($detail, FALSE))) {
                $detail->id = $this->db->insert_id('job_recruiter_id_seq');
                return $detail;
            } else
                return FALSE;
        }
        
        public function update(Recruiter_entity $detail) {
            $this->db->where('id',$detail->id);
            if($this->db->update('job_recruiter', $detail))
                return $detail;
            else
                return FALSE;
        }
        
        public function get($id) {
            
            $query = $this->db->get_where('job_recruiter', array('id' => $id), 1);
            return $query->custom_row_object(0, 'Recruiter_entity');
        }
        
        public function delete(Recruiter_entity $detail) {
            return $this->db->delete('job_recruiter', array('id'=>$detail->id)) !== FALSE;
        }
        
        /**
         Returns an array with the data form the specified entity.
         @param Recruiter_entity $entity The entity whose data to return as an array.
         @param boolean $incl_id Whether or not to include the id property. Default is TRUE.
         @return array An array containing the data of the entity.
         */
        public function toData(Recruiter_entity $entity, $incl_id = TRUE) {
            $data = array('name' => $entity->name, 'email_address' => $entity->email_address, 'phone_number' => $entity->phone_number, 'contact_name' => $entity->contact_name);
            if($incl_id)
                $data['id'] = $entity->id;
            return $data;
        }
        
        /**
         Loads entity content from the specified data array. This is the reverse of toData().
         @param array $data array containing the data for this entity.
         @param Recruiter_entity $entity reference to the entity to fill with data. If NULL, a new entity is created.
         @return Recruiter_entity The $entity parameter if not NULL, otherwise a new entity. This entity has been filled with the data from the $data array.
         */
        public function loadFromData($data, Recruiter_entity $entity = NULL) {
            
            if($entity === NULL)
                $entity = new Recruiter_entity();
            
            if(isset($data['id']))
                $this->id = $data['id'];
            if(isset($data['name']))
                $this->name=$data['name'];
            if(isset($data['contact_name']))
                $this->contact_name = $data['contact_name'];
            if(isset($data['email_address']))
                $this->email_address = $data['email_address'];
            if(isset($data['phone_number']))
                $this->contact_name = $data['contact_name'];
            
            return $entity;
        }
    
    }
