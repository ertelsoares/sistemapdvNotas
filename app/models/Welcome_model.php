<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }

    public function topProducts($user_id = NULL)
    {
        $m = date('Y-m');
        if(!$this->Admin) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select($this->db->dbprefix('products').".code as product_code, ".$this->db->dbprefix('products').".name as product_name, sum(".$this->db->dbprefix('sale_items').".quantity) as quantity")
        ->join('products', 'products.id=sale_items.product_id', 'left')
        ->join('sales', 'sales.id=sale_items.sale_id', 'left')
        ->order_by("sum(".$this->db->dbprefix('sale_items').".quantity)", 'desc')
        ->group_by('sale_items.product_id')
        ->limit(10)
        ->like('sales.date', $m, 'both');
        if($user_id) {
            $this->db->where('created_by', $user_id);
        }
        $q = $this->db->get('sale_items');
        if($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getChartData($user_id = NULL) {
        if(!$this->Admin) {
            $user_id = $this->session->userdata('user_id');
        }
        if ($this->db->dbdriver == 'sqlite3') {
            $this->db->select("strftime('%Y-%m', date) as month, SUM(total) as total, SUM(total_tax) as tax, SUM(total_discount) as discount")
            ->where("date >= datetime('now','-6 month')", NULL, FALSE)
            ->group_by("strftime('%Y-%m', date)");
        } else {
            $this->db->select("strftime('%Y-%m', date) as month, SUM(total) as total, SUM(total_tax) as tax, SUM(total_discount) as discount")
            ->where("date >= date_sub( now() , INTERVAL 6 MONTH)", NULL, FALSE)
            ->group_by("strftime('%Y-%m', date)");
        }
        if($user_id) {
            $this->db->where('created_by', $user_id);
        }
        if ($store_id = $this->session->userdata('store_id')) {
            $this->db->where('store_id', $store_id);
        }
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getUserGroups() {
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("users_groups");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function userGroups() {
        $ugs = $this->getUserGroups();
        if ($ugs) {
            foreach ($ugs as $ug) {
                $this->db->update('users', array('group_id' => $ug->group_id), array('id' => $ug->user_id));
            }
            return true;
        }
        return false;
    }


    // total de despesas
    public function getTotalExpenses($filter, $user = "")
    {
        if($filter=="hoje"){
            $dateIni = date('Y-m-d 00:00:00');
            $dateFim = date('Y-m-d 23:59:59');
        }

        if($filter=="semana"){
            $dateIni = date('Y-m-d 00:00:00', strtotime("this week - 1 day"));
            $dateFim = date('Y-m-d 23:59:59', strtotime("+7 days ".$dateIni));
        }

        if($filter=="mes"){
            $dateIni = date('Y-m-01 00:00:00');
            $dateFim = date('Y-m-t 23:59:59');
        }
        
        $this->db->select('SUM( COALESCE( amount, 0 ) ) AS total', FALSE)
            ->where('date >=', $dateIni)
            ->where('date <=', $dateFim);

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    // total de vendas
    public function getTotalSales($filter, $user = "")
    {
        if($filter=="hoje"){
            $dateIni = date('Y-m-d 00:00:01');
            $dateFim = date('Y-m-d 23:59:59');
        }

        if($filter=="semana"){
            $dateIni = date('Y-m-d 00:00:01', strtotime("this week - 1 day")); // 1 day - domingo
            $dateFim = date('Y-m-d 23:59:59', strtotime("+7 days ".$dateIni));
        }

        if($filter=="mes"){
            $dateIni = date('Y-m-01 00:00:01');
            $dateFim = date('Y-m-t 23:59:59');
        }
        
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS totalvendas, SUM( COALESCE( (paid), 0 ) ) - SUM( COALESCE( (grand_total), 0 ) ) as balance', FALSE)
            ->where('date >', $dateIni)
            ->where('date <', $dateFim);

        if($user!=""){ 
            $this->db->where('created_by', $user); 
            $this->db->or_where('vendedor', $user);
        }
            
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }


}
