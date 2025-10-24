<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pos_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();

	}

	public function getProductNames($term, $limit = 10)
    {
		$this->db->where("(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  (name || ' (' || code ||  ')') LIKE '%" . $term . "%')");
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTodaySales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.date >', $date);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }


    public function getTodayCCSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cc_slips, SUM( COALESCE( total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'CC');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
    
    
     public function clearItensNovosSuspended($id){
         
         if ($this->db->update('suspended_items', array('is_new' => 0), array("suspend_id" => $id) ) ) {
			return true;
        }else{
			return false;
		}
		
	
    }
        

    public function getTodayCashSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'cash');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayRefunds()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned', FALSE)
            ->join('return_sales', 'return_sales.id=payments.return_id', 'left')
            ->where('type', 'returned')->where('payments.date >', $date);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayExpenses()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( amount, 0 ) ) AS total', FALSE)
            ->where('date >', $date);

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayCashRefunds()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned', FALSE)
            ->join('return_sales', 'return_sales.id=payments.return_id', 'left')
            ->where('type', 'returned')->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'cash');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
  
  
    public function UpdateLastNFNumero($numero){
		$numero = $numero + 1;

	if ($this->db->update('settings', array('ultima_nf' => $numero))) {
			return true;
        }else{
			return false;
		}
	
    }
    
    public function UpdateLastNFCNumero($numero){
		$numero = $numero + 1;

	    if ($this->db->update('settings', array('ultima_nfc' => $numero))) {
			return true;
        }else{
			return false;
		}
	
	}
	
	public function UpdateDadosNFC($vendaid, $nf_status, $nf_numero, $nf_chave, $nf_danfe = null, $nf_xml, $nf_modelo){
		
		if ($this->db->update('sales', array('nf_status' => $nf_status,'nf_numero' => $nf_numero,'nf_chave' => $nf_chave,'nf_danfe' => $nf_danfe,'nf_xml' => $nf_xml, 'nf_modelo' => $nf_modelo), array('id' => $vendaid))) {
			return true;
        }else{
			return false;
		}
    }
    
    public function UpdateDadosNF($nfid, $nf_status, $nf_numero, $nf_chave, $nf_danfe = null, $nf_xml, $nf_modelo){
		
		if ($this->db->update('notasfiscais', array('nf_status' => $nf_status,'nf_numero' => $nf_numero,'nf_chave' => $nf_chave,'nf_danfe' => $nf_danfe,'nf_xml' => $nf_xml, 'nf_modelo' => $nf_modelo), array('id' => $nfid))) {
			return true;
        }else{
			return false;
		}
    }

    public function UpdateDadosNFbyNumero($nf_status, $nf_numero, $nf_chave, $nf_danfe = null, $nf_xml, $nf_modelo){
		
		if ($this->db->update('notasfiscais', array('nf_status' => $nf_status,'nf_chave' => $nf_chave,'nf_danfe' => $nf_danfe,'nf_xml' => $nf_xml,  'nf_modelo' => $nf_modelo), array('nf_numero' => $nf_numero))) {
			return true;
        }else{
			return false;
		}
    }
    
    public function InsertDadosNF($nf_status, $nf_numero, $nf_chave, $nf_danfe = null, $nf_xml, $nf_modelo, $userid = 1, $clienteid){
        
        if(!is_numeric($clienteid) || $clienteid=="" || $clienteid==null) $clienteid = null; 
        
        $dataemi = date("Y-m-d H:i:s");
		
		if ($this->db->insert('notasfiscais', array('nf_status' => $nf_status,'nf_numero' => $nf_numero,'nf_chave' => $nf_chave,'nf_danfe' => $nf_danfe,'nf_xml' => $nf_xml,  'nf_modelo' => $nf_modelo, 'clienteid' => $clienteid, 'userid' => $userid, 'data' => $dataemi))) {
			return true;
        }else{
			return false;
		}
	}

    public function getImpostos($id) {
        
        if($id!=""){
            $q = $this->db->get_where('impostos', array('id' => $id), 1);
            if ($q->num_rows() > 0) {
                return $q->row();
            }
        }else{

            $this->db->order_by('nome');
            $q = $this->db->get('impostos');
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }
        }

        return FALSE;
    }


	public function getAllSalesContingencia() 
	{
        $this->db->select('*');
        $this->db->where('nf_status', "contingencia");
        $this->db->or_where('nf_status', "em processamento");
        $this->db->or_where('nf_status', "processamento");
        $q = $this->db->get('notasfiscais');
        
        if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}	
			return $data;
		}
	}

    public function countAllSalesContingencia() 
	{
        $this->db->where('nf_status', "contingencia");
        $this->db->or_where('nf_status', "em processamento");
        $this->db->or_where('nf_status', "processamento");
        $q = $this->db->get('notasfiscais');
    	return $this->db->count_all_results();
    }
	
	public function getTodayPIXSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_pix, SUM( COALESCE( total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'pix');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayChSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'Cheque');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayStripeSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'stripe');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total', FALSE)
            ->where('sales.date >', $date);
        $this->db->where('sales.created_by', $user_id);
        $q = $this->db->get('sales');
        $sales = array();
        if ($q->num_rows() > 0) {
            $sales = $q->row();
        }
        
         $this->db->select('SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->where('payments.date >', $date);
        $this->db->where('payments.created_by', $user_id);
        $q = $this->db->get('payments');
         $pags = array();
        if ($q->num_rows() > 0) {
            $pags = $q->row();
        }
        
        $return = array_merge((array) $sales, (array) $pags);
        if(!empty($return)){
           return (object) $return;  
        }
       
        return false;
    }


    public function getRegisterCCSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cc_slips, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( ' . $this->db->dbprefix('payments') . '.amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'CC');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterCashSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total,  SUM( COALESCE( ' . $this->db->dbprefix('payments') . '.amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'cash');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterPixSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total,  SUM( COALESCE( ' . $this->db->dbprefix('payments') . '.amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'pix');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

	public function getRegisterBoletoSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total,  SUM( COALESCE( ' . $this->db->dbprefix('payments') . '.amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'boleto');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	
	public function getRegisterFiadoSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total,  SUM( COALESCE( ' . $this->db->dbprefix('payments') . '.amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'fiado');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

	public function getRegisterTransfSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total,  SUM( COALESCE( ' . $this->db->dbprefix('payments') . '.amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'transf');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	
	public function getRegisterOtherSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total,  SUM( COALESCE( ' . $this->db->dbprefix('payments') . '.amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'other');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterRefunds($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( ' . $this->db->dbprefix('payments') . '.amount, 0 ) ) AS returned', FALSE)
            ->join('return_sales', 'return_sales.id=payments.return_id', 'left')
            ->where('type', 'returned')->where('payments.date >', $date);
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterCashRefunds($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( ' . $this->db->dbprefix('payments') . '.amount, 0 ) ) AS returned', FALSE)
            ->join('return_sales', 'return_sales.id=payments.return_id', 'left')
            ->where('type', 'returned')->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'cash');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterExpenses($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( amount, 0 ) ) AS total', FALSE)
            ->where('date >', $date);
        $this->db->where('created_by', $user_id);

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterChSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( ' . $this->db->dbprefix('payments') . '.amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'Cheque');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterStripeSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( ' . $this->db->dbprefix('payments') . '.amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('payments.date >', $date)->where("{$this->db->dbprefix('payments')}.paid_by", 'stripe');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function products_count($category_id) {
    	$this->db->where('category_id', $category_id)->from('products');
    	return $this->db->count_all_results();
    }

    public function fetch_products($category_id, $limit, $start) {
    	$this->db->limit($limit, $start);
    	$this->db->where('category_id', $category_id);
    	$this->db->order_by("code", "asc");
    	$query = $this->db->get("products");

    	if ($query->num_rows() > 0) {
    		foreach ($query->result() as $row) {
    			$data[] = $row;
    		}
    		return $data;
    	}
    	return false;
    }

    public function registerData($user_id)
    {
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $q = $this->db->get_where('registers', array('user_id' => $user_id, 'status' => 'open'), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function openRegister($data)
    {
        if ($this->db->insert('registers', $data)) {
            return true;
        }
        return FALSE;
    }

    public function getOpenRegisters()
    {
        $this->db->select("date, user_id, cash_in_hand, (" . $this->db->dbprefix('users') . ".first_name || ' ' ||  " . $this->db->dbprefix('users') . ".last_name || ' - ' || " . $this->db->dbprefix('users') . ".email) as user", FALSE)
            ->join('users', 'users.id=pos_register.user_id', 'left');
        $q = $this->db->get_where('registers', array('status' => 'open'));
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function closeRegister($rid, $user_id, $data)
    {
        if (!$rid) {
            $rid = $this->session->userdata('register_id');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        if ($data['transfer_opened_bills'] == -1) {
            $this->db->delete('suspended_sales', array('created_by' => $user_id));
        } elseif ($data['transfer_opened_bills'] != 0) {
            $this->db->update('suspended_sales', array('created_by' => $data['transfer_opened_bills']), array('created_by' => $user_id));
        }
        if ($this->db->update('registers', $data, array('id' => $rid, 'user_id' => $user_id))) {
            return true;
        }
        return FALSE;
    }

    public function operateRegister($rid, $user_id, $data)
    {
        if (!$rid) {
            $rid = $this->session->userdata('register_id');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }

        $register = $this->registerData($user_id);
        $rid = $register->id;
        $data_update = array();
        
        if($data["tipo"] == "1"){
            $data_update["total_sangrias"] = $register->total_sangrias + $data["valor"];
        }else{
            $data_update["total_reforco"] = $register->total_reforco + $data["valor"];
        }

        if($data["informacao"] != ""){
            
            $arrInfo = array();
            if($register->note_sangrias!=""){
                $arrInfo = json_decode($register->note_sangrias, true);
            }

            $info = ($data["tipo"] == "1")? "Sangría" : "Reforço";
            $arrInfo[] = $data["date"]. " - ($info de R$ ". $this->tec->formatNumber($data["valor"], 2). " - ". $data["informacao"] . ")";

            $data_update["note_sangrias"] = json_encode($arrInfo);
        }
        
        if ($this->db->update('registers', $data_update, array('id' => $rid, 'user_id' => $user_id))) {
            return true;
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

    public function getProductByCode($code)
    {
        $q = $this->db->get_where('products', array('code' => $code), 1);
          if( $q->num_rows() > 0 )
          {
            return $q->row();
          }
          return FALSE;
    }

    public function addSale($data, $items, $payment = array(), $did = NULL)
    {

        if($this->db->insert('sales', $data)) {
            $sale_id = $this->db->insert_id();
            $comissao_total = 0;

            foreach ($items as $item) {
                $item['sale_id'] = $sale_id;
                if($this->db->insert('sale_items', $item)) {
                    $product = $this->site->getProductByID($item['product_id']);
                    if($data["vendedor"]!="" && !empty($product->comissao) && $product->comissao>0){ $comissao_total += (($item['subtotal']/100) * (float)$product->comissao); }
                    
                    if ($product->type == 'standard') {
                        
                        if( $product->composicao_codigo!=""){
                            // descontamos do estoque da composição
                            $prod_composicao = $this->getProductByCode($product->composicao_codigo);
                            if(!empty($prod_composicao->id)){
                                $quantidade_com = (!empty($product->composicao_quantidade))? $product->composicao_quantidade : 0;
                                $this->db->update('products', array('quantity' => ($prod_composicao->quantity - ($item['quantity'] * $quantidade_com))), array('id' => $prod_composicao->id));
                            }
                        }else{
                            $this->db->update('products', array('quantity' => ($product->quantity-$item['quantity'])), array('id' => $product->id));
                    
                        }
                        
                    
                    } elseif ($product->type == 'combo') {
                        // não se usa
                        $combo_items = $this->getComboItemsByPID($product->id);
                        foreach ($combo_items as $combo_item) {
                            $cpr = $this->site->getProductByID($combo_item->id);
                            if($cpr->type == 'standard') {
                                $qty = $combo_item->qty * $item['quantity'];
                                $this->db->update('products', array('quantity' => ($cpr->quantity-$qty)), array('id' => $cpr->id));
                            }
                        }
                    }
                }
            }

            if($did) {
                $this->db->delete('suspended_sales', array('id' => $did));
                $this->db->delete('suspended_items', array('suspend_id' => $did));
                // atualizamos os pagamentos
                $this->db->update('payments', array('sale_id' => $sale_id), array('sale_id' => "open_".$did));
            }

            $msg = array();
            if(!empty($payment)) {
                $pay = $payment;
                foreach($pay as $payment){
                    if ($payment['paid_by'] == 'stripe') {
                            unset($payment['cc_cvv2']);
                            $payment['sale_id'] = $sale_id;
                            $this->db->insert('payments', $payment);
                    } else {
                        if ($payment['paid_by'] == 'gift_card') {
                            $gc = $this->getGiftCardByNO($payment['gc_no']);
                            $this->db->update('gift_cards', array('balance' => ($gc->balance-$payment['amount'])), array('card_no' => $payment['gc_no']));
                        }
                        unset($payment['cc_cvv2']);
                        $payment['sale_id'] = $sale_id;
                        $this->db->insert('payments', $payment);
                    }
                }
                
            }


            

            $this->db->update('sales', array('comissao' => $comissao_total), array('id' => $sale_id));
            
            return array('sale_id' => $sale_id, 'message' => $msg);
            }
           
        return false;
    }

    public function updateSale($id, $data, $items)
    {
        $oitems = $this->getAllSaleItems($id);
         $comissao_total = 0;
        foreach ($oitems as $oitem) {
            $product = $this->site->getProductByID($oitem->product_id);
            if ($product->type == 'standard') {

                if( $product->composicao_codigo!=""){
                    // descontamos do estoque da composição
                    $prod_composicao = $this->getProductByCode($product->composicao_codigo);
                    if(!empty($prod_composicao->id)){
                        $quantidade_com = (!empty($product->composicao_quantidade))? $product->composicao_quantidade : 0;
                        $this->db->update('products', array('quantity' => ($prod_composicao->quantity+ ($oitem->quantity  *  $quantidade_com))), array('id' => $prod_composicao->id));
                    }
                }else{
                    $this->db->update('products', array('quantity' => ($product->quantity+$oitem->quantity)), array('id' => $product->id));
                }
            } elseif ($product->type == 'combo') {
                $combo_items = $this->getComboItemsByPID($product->id);
                foreach ($combo_items as $combo_item) {
                    $cpr = $this->site->getProductByID($combo_item->id);
                    if($cpr->type == 'standard') {
                        $qty = $combo_item->qty * $oitem->quantity;
                        $this->db->update('products', array('quantity' => ($cpr->quantity+$qty)), array('id' => $cpr->id));
                    }
                }
            }
        }

        if($this->db->update('sales', $data, array('id' => $id)) && $this->db->delete('sale_items', array('sale_id' => $id))) {

            foreach ($items as $item) {
                $item['sale_id'] = $id;
                if($this->db->insert('sale_items', $item)) {
                    $product = $this->site->getProductByID($item['product_id']);
                    if($data["vendedor"]!="" && !empty($product->comissao) && $product->comissao>0){ $comissao_total += (($item['subtotal']/100) * (float)$product->comissao); }
                    if ($product->type == 'standard') {
                        if( $product->composicao_codigo!=""){
                            // descontamos do estoque da composição
                            $prod_composicao = $this->getProductByCode($product->composicao_codigo);
                            if(!empty($prod_composicao->id)){
                                $quantidade_com = (!empty($product->composicao_quantidade))? $product->composicao_quantidade : 0;
                                $this->db->update('products', array('quantity' => ($prod_composicao->quantity- ($item['quantity']*$quantidade_com))), array('id' => $prod_composicao->id));
                            }
                        }else{
                            $this->db->update('products', array('quantity' => ($product->quantity-$item['quantity'])), array('id' => $product->id));
                        }
                    } elseif ($product->type == 'combo') {
                        $combo_items = $this->getComboItemsByPID($product->id);
                        foreach ($combo_items as $combo_item) {
                            $cpr = $this->site->getProductByID($combo_item->id);
                            if($cpr->type == 'standard') {
                                $qty = $combo_item->qty * $item['quantity'];
                                $this->db->update('products', array('quantity' => ($cpr->quantity-$qty)), array('id' => $cpr->id));
                            }
                        }
                    }
                }
            }
            
            $this->db->update('sales', array('comissao' => $comissao_total), array('id' => $id));

            return TRUE;
            }

        return false;
    }

    public function suspendSale($data, $items, $did = NULL)
    {

        if($did) {

            if($this->db->update('suspended_sales', $data, array('id' => $did)) && $this->db->delete('suspended_items', array('suspend_id' => $did))) {
                foreach ($items as $item) {
					unset($item['cost']);
                    $item['suspend_id'] = $did;
                    $this->db->insert('suspended_items', $item);
                }
                return $did;
            }

        } else {

            if($this->db->insert('suspended_sales', $data)) {
                $suspend_id = $this->db->insert_id();
                foreach ($items as $item) {
					unset($item['cost']);
                    $item['suspend_id'] = $suspend_id;
                    $this->db->insert('suspended_items', $item);
                }
                return $suspend_id;
            }
        }

        return false;
    }

    public function getNotaFiscalByID($sale_id)
    {
        $q = $this->db->get_where('notasfiscais', array('id' => $sale_id), 1);
          if( $q->num_rows() > 0 ) {
            return $q->row();
          }
          return FALSE;
    }

    public function getSaleByID($sale_id)
    {
        $q = $this->db->get_where('sales', array('id' => $sale_id), 1);
          if( $q->num_rows() > 0 ) {
            return $q->row();
          }
          return FALSE;
    }
    

    public function getAllSaleItems($sale_id)
    {
        $this->db->select('sale_items.*, products.name as product_name, products.code as product_code, products.ncm as product_ncm, products.cest as product_cest, products.imposto as product_imposto, products.unit as product_unit, products.origem as product_origem, products.cfop as product_cfop,products.cfop2 as product_cfop2, products.tax_method as tax_method')
        ->join('products', 'products.id=sale_items.product_id')
        ->order_by('sale_items.id');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        
        if($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllSalePayments($sale_id)
    {
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id));
        if($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSuspendedSaleByID($id)
    {
        $q = $this->db->get_where('suspended_sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSuspendedSaleItems($id)
    {
        $q = $this->db->get_where('suspended_items', array('suspend_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSuspendedSales($user_id = NULL)
    {
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->order_by('date', 'desc');
        $q = $this->db->get_where('suspended_sales'); // , array('created_by' => $user_id)
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getGiftCardByNO($no)
    {
        $q = $this->db->get_where('gift_cards', array('card_no' => $no), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getComboItemsByPID($product_id) {
        $this->db->select($this->db->dbprefix('products') . '.id as id, ' . $this->db->dbprefix('products') . '.code as code, ' . $this->db->dbprefix('combo_items') . '.quantity as qty, ' . $this->db->dbprefix('products') . '.name as name, ' . $this->db->dbprefix('products') . '.quantity as quantity')
        ->join('products', 'products.code=combo_items.item_code', 'left')
        ->group_by('combo_items.id');
        $q = $this->db->get_where('combo_items', array('product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

/*

	public function getProductByName($name)
	{
		$q = $this->db->get_where('products', array('name' => $name), 1);
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  }

		  return FALSE;
	}
	public function getAllCustomers()
	{
		$q = $this->db->get('customers');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}

			return $data;
		}
	}

	public function getCustomerByID($id)
	{

		$q = $this->db->get_where('customers', array('id' => $id), 1);
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  }

		  return FALSE;

	}

	public function getAllProducts()
	{
		$q = $this->db->query('SELECT * FROM products ORDER BY id');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}

			return $data;
		}
	}

	public function getProductByID($id)
	{

		$q = $this->db->get_where('products', array('id' => $id), 1);
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  }

		  return FALSE;

	}

	public function getAllTaxRates()
	{
		$q = $this->db->get('tax_rates');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}

			return $data;
		}
	}

	public function getTaxRateByID($id)
	{

		$q = $this->db->get_where('tax_rates', array('id' => $id), 1);
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  }

		  return FALSE;

	}



	function getSetting()
	{

		$q = $this->db->get_where('settings', array('setting_id' => 1));
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  }

		  return FALSE;

	}




   public function categories_count() {
        return $this->db->count_all("categories");
    }

    public function fetch_categories($limit, $start) {
        $this->db->limit($limit, $start);
		$this->db->order_by("id", "asc");
        $query = $this->db->get("categories");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }

   public function bills_count() {
        return $this->db->count_all("suspended_sales");
    }

    public function fetch_bills($limit, $start) {
        $this->db->limit($limit, $start);
		$this->db->order_by("id", "asc");
        $query = $this->db->get("suspended_sales");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }

	public function getAllCategories()
	{
		$q = $this->db->get('categories');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}

			return $data;
		}
	}

	public function getCustomerBill($id)
	{

		$q = $this->db->get_where('customer_bill', array('customer_id' => $id));
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  }

		  return FALSE;

	}

	public function updateCustomerBill($bID, $saleData, $count, $tax, $total)
	{
		// bill data
		$billData = array(
			'customer_id'		=> $bID,
			'sale_data'			=> $saleData,
			'count'		=> $count,
			'tax' 	=> $tax,
			'total'	=> $total

		);

		if(!$this->getCustomerBill($bID)) {
			if( $this->db->insert('customer_bill', $billData) ) {
				return true;
			}
		} else {
			$this->db->where('customer_id', $bID);
			if($this->db->update('customer_bill', $billData)) {
				return true;
			}
		}

		  return FALSE;

	}

	public function getTodaySales()
	{
		$date = date('Y-m-d');

		$myQuery = "SELECT DATE_FORMAT( date,  '%W, %D %M %Y' ) AS date, SUM( COALESCE( total, 0 ) ) AS total
			FROM sales
			WHERE DATE(date) LIKE '{$date}'";
		$q = $this->db->query($myQuery, false);
		if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  }
	}

	public function getTodayCCSales()
	{
		$date = date('Y-m-d');
		$myQuery = "SELECT SUM( COALESCE( total, 0 ) ) AS total
			FROM sales
			WHERE DATE(date) =  '{$date}' AND paid_by = 'CC'
			GROUP BY date";
		$q = $this->db->query($myQuery, false);
		if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  }
	}

	public function getTodayCashSales()
	{
		$date = date('Y-m-d');
		$myQuery = "SELECT SUM( COALESCE( total, 0 ) ) AS total
			FROM sales
			WHERE DATE(date) =  '{$date}' AND paid_by = 'cash'
			GROUP BY date";
		$q = $this->db->query($myQuery, false);
		if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  }
	}
	public function getTodayChSales()
	{
		$date = date('Y-m-d');
		$myQuery = "SELECT SUM( COALESCE( total, 0 ) ) AS total
			FROM sales
			WHERE DATE(date) =  '{$date}' AND paid_by = 'Cheque'
			GROUP BY date";
		$q = $this->db->query($myQuery, false);
		if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  }
	}

	public function getTodaySale()
	{
		$date = date('Y-m-d');
		$myQuery = "SELECT
					(select sum(total) FROM sales WHERE date LIKE '{$date}%') total,
					(select sum(total) FROM sales WHERE paid_by = 'cash' AND date LIKE '{$date}%') ca,
					(select sum(total) FROM sales WHERE paid_by = 'CC' AND date LIKE '{$date}%') cc,
					(select sum(total) FROM sales WHERE paid_by = 'Cheque' AND date LIKE '{$date}%') ch";
		$q = $this->db->query($myQuery, false);
		if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  }
	}



	public function getInvoiceBySaleID($sale_id)
	{

		$q = $this->db->get_where('sales', array('id' => $sale_id), 1);
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  }

		  return FALSE;

	}

	public function getAllSuspendedItems($suspend_id)
	{
		$this->db->order_by('id');
		$q = $this->db->get_where('suspended_items', array('suspend_id' => $suspend_id));
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}

			return $data;
		}
	}

	public function getSuspendedSaleByID($suspend_id)
	{

		$q = $this->db->get_where('suspended_sales', array('id' => $suspend_id), 1);
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  }

		  return FALSE;

	}

	public function addCustomer($data)
	{

		if($this->db->insert('customers', $data)) {
			return $this->db->insert_id();
		}
		return false;
	}
	*/
}