<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model
{
	
	public function __construct() {
		parent::__construct();
	}
	
	public function updateSetting($data = array()) {
		if($this->db->update('settings', $data, array('setting_id' => 1))) {
			
			return true;
		}
		
		return false;
	
	}
	
	public function delete_all_products() {
      
		if($this->db->query("DELETE FROM ".$this->db->dbprefix('products')."", false)){
		    return true;
		}else{
		    return false;
		}
		
    }
    
    public function delete_all_customers() {
      
		if($this->db->query("DELETE FROM ".$this->db->dbprefix('customers')."", false)){
		    return true;
		}else{
		    return false;
		}
		
    }
    
    
}