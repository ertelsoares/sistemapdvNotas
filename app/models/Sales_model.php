<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_model extends CI_Model
{
	
	public function __construct() {
		parent::__construct();

	}

	public function getSaleByID($id)
    {
        
        if(strpos($id, "open_") !== false){
            $db = "suspended_sales";
            $ex = explode("_", $id);
            $id = $ex[1];
        }else{
            $db = "sales";
        }

        $q = $this->db->get_where($db, array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function deleteInvoice($id) {

        $sale = $this->getSaleByID($id);
        
        // Atualizamos o estoque (volta o itens para o estoque)
        $oitems = $this->pos_model->getAllSaleItems($id);
        if($oitems!=false){ 
            foreach ($oitems as $oitem) {
                $product = $this->site->getProductByID($oitem->product_id);
                if ($product->type == 'standard') {
                    $this->db->update('products', array('quantity' => ($product->quantity+$oitem->quantity)), array('id' => $product->id));
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
        }
        
		if($this->db->delete('sale_items', array('sale_id' => $id)) && $this->db->delete('sales', array('id' => $id)) && $this->db->delete('payments', array('sale_id' => $id))) {
			return true;
		}
		
		return FALSE;
	}

	public function deleteOpenedSale($id) {
		if($this->db->delete('suspended_items', array('suspend_id' => $id)) && $this->db->delete('payments', array('id' => "open_".$id)) &&  $this->db->delete('suspended_sales', array('id' => $id))) {
			return true;
		}
		return FALSE;
	}
	
	public function getSalePayments($sale_id)
    {
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getPaymentByID($id)
    {
        $q = $this->db->get_where('payments', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getTotalComissao($where = array())
    {
        $q = $this->db->get_where('sales', $where);
        if($q->num_rows() > 0) {
            $total = 0;
			foreach (($q->result()) as $row) {
				 $total += (!empty($row->comissao))? $row->comissao: 0;
			}
			return $total;
		}
		
        return FALSE;
    }
    
    public function addPayment($data = array())
    {
        if ($this->db->insert('payments', $data)) {
            if ($data['paid_by'] == 'gift_card') {
                $gc = $this->site->getGiftCardByNO($data['gc_no']);
                $this->db->update('gift_cards', array('balance' => ($gc->balance - $data['amount'])), array('card_no' => $data['gc_no']));
            }

            $this->syncSalePayments($data['sale_id']);
            return true;
        }
        return false;
    }

    public function updatePayment($id, $data = array())
    {
        if ($this->db->update('payments', $data, array('id' => $id))) {
            $this->syncSalePayments($data['sale_id']);
            return true;
        }
        return false;
    }

    public function deletePayment($id)
    {
        $opay = $this->getPaymentByID($id);
        if ($this->db->delete('payments', array('id' => $id))) {
            $this->syncSalePayments($opay->sale_id);
            return true;
        }
        return FALSE;
    }

    public function syncSalePayments($id)
    {
        $sale = $this->getSaleByID($id);
        $payments = $this->getSalePayments($id);
        $paid = 0;
        if($payments) {
        	foreach ($payments as $payment) {
        		$paid += $payment->amount;
        	}
        }
        $status = $paid <= 0 ? 'Não Pago' : $sale->status;
	    if ($this->tec->formatDecimal($sale->grand_total) > $this->tec->formatDecimal($paid) && $paid > 0) {
            $status = 'Parcial';
        } elseif ($this->tec->formatDecimal($sale->grand_total) <= $this->tec->formatDecimal($paid)) {
            $status = 'Pago';
        }

        $troco = ($this->tec->formatDecimal($paid) > $this->tec->formatDecimal($sale->grand_total))? 
        ($this->tec->formatDecimal($paid) - $this->tec->formatDecimal($sale->grand_total)) : 0;

        if(strpos($id, "open_") !== false){
            $ex = explode("_", $id);
            $id = $ex[1];
            if ($this->db->update('suspended_sales', array('paid' => $paid), array('id' => $id))) {
               return true;
            }
        }else{
            if ($this->db->update('sales', array('paid' => $paid, 'troco' => $troco,'status' => $status), array('id' => $id))) {
               return true;
            }
        }

        return FALSE;
    }
}