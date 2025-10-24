<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customers_model extends CI_Model
{
	
	public function __construct() {
		parent::__construct();
	}
	
	public function getCustomerNames($term, $limit = 10)
    {
		$this->db->where("(name LIKE '%" . $term . "%' OR cf1 LIKE '%" . $term . "%' OR  (name ||  ' (' ||  cf1 || ')') LIKE '%" . $term . "%')");
        $this->db->limit($limit);
        $q = $this->db->get('customers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	

	public function getCustomerByID($id) 
	{
		$q = $this->db->get_where('customers', array('id' => $id), 1); 
		if( $q->num_rows() > 0 ) {
			return $q->row();
		} 
		return FALSE;
	}
	
	public function addCustomer($data = array())
	{
		if($this->db->insert('customers', $data)) {
			return $this->db->insert_id();
		}
		return false;
	}
	
	public function updateCustomer($id, $data = array())
	{
		if($this->db->update('customers', $data, array('id' => $id))) {
			return true;
		}
		return false;
	}
	
	public function deleteCustomer($id) 
	{
		if($this->db->delete('customers', array('id' => $id))) {
			return true;
		}
		return FALSE;
	}

}
