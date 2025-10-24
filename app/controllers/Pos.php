<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pos extends MY_Controller {

	function __construct()
	{
		parent::__construct();

		if (!$this->loggedIn) {
			redirect('login');
		}
		$this->load->library('form_validation');
		$this->load->model('pos_model');
		$this->load->model('sales_model');

	}

	function index($sid = NULL, $eid = NULL)
	{

		
		if( $this->input->get('hold') ) { $sid = $this->input->get('hold'); }
		if( $this->input->get('edit') ) { $eid = $this->input->get('edit'); }
		if( $this->input->post('eid') ) { $eid = $this->input->post('eid'); }
		if( $this->input->post('did') ) { $did = $this->input->post('did'); } else { $did = NULL; }
		if($eid && !$this->Admin){
			//$this->session->set_flashdata('error', lang('access_denied'));
			//redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'pos');
		}
		if (!$this->Settings->default_customer || !$this->Settings->default_category) {
			$this->session->set_flashdata('warning', lang('please_update_settings'));
			redirect('settings');
		}
		if ($register = $this->pos_model->registerData($this->session->userdata('user_id'))) {
			$register_data = array('register_id' => $register->id, 'cash_in_hand' => $register->cash_in_hand, 'register_open_time' => $register->date);
			$this->session->set_userdata($register_data);
		} else {

			if($this->session->userdata('acesso_nfc') != 1) {
				redirect("upgrade");
			}

			$this->session->set_flashdata('error', lang('register_not_open'));
			redirect('pos/open_register');
		}

		$suspend = $this->input->post('suspend') ? TRUE : FALSE;

		$this->form_validation->set_rules('customer', lang("customer"), 'trim|required');

		if ($this->form_validation->run() == true) {

			$quantity = "quantity";
			$product = "product";
			$unit_cost = "unit_cost";
			$tax_rate = "tax_rate";

			$date = date('Y-m-d H:i:s');
			$customer_id = $this->input->post('customer_id');
			$customer_details = $this->pos_model->getCustomerByID($customer_id);
			$customer = $customer_details->name;
			$note = $this->tec->clear_tags($this->input->post('spos_note'));
			$entrega_endereco = $this->tec->clear_tags($this->input->post('spos_entrega_endereco'));

			$total = 0;
			$product_tax = 0;
			$order_tax = 0;
			$product_discount = 0;
			$order_discount = 0;
			$percentage = '%';
			$i = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;
			for ($r = 0; $r < $i; $r++) {
				$item_id = $_POST['product_id'][$r];
				$real_unit_price = $this->tec->formatDecimal($_POST['real_unit_price'][$r]);
				$item_quantity = $this->tec->formatDolar($_POST['quantity'][$r], 3);
				$item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : '0';
				$nnote = isset($_POST['product_comment'][$r]) ? $this->tec->clear_tags($_POST['product_comment'][$r]) : '';
				if (isset($item_id) && isset($real_unit_price) && isset($item_quantity)) {
					$product_details = $this->site->getProductByID($item_id);
					$unit_price = $real_unit_price;

					$pr_discount = 0;
					if (isset($item_discount)) {
					    $discount = $item_discount;
					    $dpos = strpos($discount, $percentage);
					    if ($dpos !== false) {
					        $pds = explode("%", $discount);
					        $pr_discount = (($this->tec->formatDecimal($unit_price)) * (Float)($pds[0])) / 100;
					    } else {
					        $pr_discount = $this->tec->formatDecimal($discount);
					    }
					}
					$unit_price = $this->tec->formatDecimal($unit_price - $pr_discount);
					$item_net_price = $unit_price;
					$pr_item_discount = $this->tec->formatDecimal($pr_discount * $item_quantity);
					$product_discount += $pr_item_discount;

					$pr_item_tax = 0; $item_tax = 0; $tax = "";
						if (isset($product_details->tax) && $product_details->tax != 0) {

					        if ($product_details && $product_details->tax_method == 1) {
					            $item_tax = $this->tec->formatDecimal((($unit_price) * $product_details->tax) / 100);
					            $tax = $product_details->tax . "%";
					        } else {
					            $item_tax = $this->tec->formatDecimal((($unit_price) * $product_details->tax) / (100 + $product_details->tax));
					            $tax = $product_details->tax . "%";
					            $item_net_price -= $item_tax;
					        }

						    $pr_item_tax = $this->tec->formatDecimal($item_tax * $item_quantity);

						}

					$product_tax += $pr_item_tax;
					$subtotal = (($item_net_price * $item_quantity) + $pr_item_tax);

					$products[$r] = array(
						'product_id' => $item_id,
						'quantity' => $item_quantity,
						'unit_price' => $unit_price,
						'net_unit_price' => $item_net_price,
						'discount' => $item_discount,
						'item_discount' => $pr_item_discount,
						'tax' => $tax,
						'item_tax' => $pr_item_tax,
						'subtotal' => $this->tec->formatDecimal($subtotal),
						'real_unit_price' => $real_unit_price,
						'cost' => $product_details->cost,
						'note' => ($nnote!="undefined")? $nnote : "",
				     	'is_new' => ($_POST['is_new'][$r]=="1")? 1: ""
						);
						
					
						if(!$suspend) {
						    unset($products[$r]["is_new"]);
						}

					$total += $this->tec->formatDecimal($item_net_price * $item_quantity);

				}
			}
			if (empty($products)) {
				$this->form_validation->set_rules('product', lang("order_items"), 'required');
			} else {
				krsort($products);
			}

			if ($this->input->post('order_discount')!="") {
				$order_discount_id = $this->input->post('order_discount');
				$opos = strpos($order_discount_id, $percentage);
				if ($opos !== false) {
					$ods = explode("%", $order_discount_id);
					$order_discount = $this->tec->formatDecimal((($total + $product_tax) * (Float)($ods[0])) / 100);
				} else {
					$order_discount = $this->tec->formatDecimal($this->tec->formatDolar($order_discount_id));
				}
			} else {
				$order_discount_id = NULL;
			}
			$total_discount = $this->tec->formatDecimal($order_discount + $product_discount);

			if($this->input->post('order_tax')) {
				$order_tax_id = $this->input->post('order_tax');
				$opos = strpos($order_tax_id, $percentage);
				if ($opos !== false) {
					$ots = explode("%", $order_tax_id);
					$order_tax = $this->tec->formatDecimal((($total + $product_tax - $order_discount) * (Float)($ots[0])) / 100);
				} else {
					$order_tax = $this->tec->formatDecimal($order_tax_id);
				}

			} else {
				$order_tax_id = NULL;
				$order_tax = 0;
			}

			$total_tax = $this->tec->formatDecimal($product_tax + $order_tax);
			$grand_total = $this->tec->formatDecimal($this->tec->formatDecimal($total) + $total_tax - $order_discount);
			$paid = 0;


			foreach($_POST["amount"] as $k => $v){
				$paid = $paid + (($_POST["amount"][$k] > 0 && $_POST["paid_by"][$k]!="" && $_POST["paid_by"][$k]!="fiado")? $v : 0);
			}

			// adicionamos os pagamentos que já foram pagos
			if(!empty($did)){
				$payments = $this->sales_model->getSalePayments("open_".$did);
				if($payments) {
					foreach ($payments as $payment) {
						$paid = $paid + $payment->amount;
					}
				}
			}

			if(!$eid) {
				$status = 'Não pago';
				if ($grand_total > $paid && $paid > 0) {
					$status = 'Parcial';
				} elseif ($grand_total <= $paid) {
					$status = 'Pago';
				}
			}
	
			$round_total = $this->tec->roundNumber($grand_total, $this->Settings->rounding);
			$rounding = $this->tec->formatDecimal($round_total - $grand_total);
			$data = array('date' => $date,
				'customer_id' => $customer_id,
				'customer_name' => $customer,
				'total' => $this->tec->formatDecimal($total),
				'product_discount' => $this->tec->formatDecimal($product_discount),
				'order_discount_id' => $order_discount_id,
				'order_discount' => $order_discount,
				'total_discount' => $total_discount,
				'product_tax' => $this->tec->formatDecimal($product_tax),
				'order_tax_id' => $order_tax_id,
				'order_tax' => $order_tax,
				'total_tax' => $total_tax,
				'grand_total' => $grand_total,
				'total_items' => $this->input->post('total_items'),
				'total_quantity' => $this->input->post('total_quantity'),
				'rounding' => $rounding,
				'paid' => $paid,
				'status' => $status,
				'created_by' => $this->session->userdata('user_id'),
				'vendedor' => $this->input->post('vendedor'),
				'note' => ($note!="undefined")? $note : "",
				'entrega_endereco' => ($entrega_endereco!="undefined")? $entrega_endereco : "",
				'chamada_numero' => $this->Settings->ultima_chamada
			);

			$data['troco'] = ($this->input->post('balance_amount')>0)? $this->input->post('balance_amount') : 0;

			
			if($suspend) {
				$data['hold_ref'] = $this->input->post('hold_ref');
			}

			
			if (!$suspend && $paid) {
			   $payment = array();

				$amount = $this->tec->formatDecimal($paid > $grand_total ? ($paid - $this->input->post('balance_amount')) : $paid);
				$amountTotalPagos = 0;
				foreach($_POST["amount"] as $k => $v ){

					if($_POST["amount"][$k]>0 && $_POST["paid_by"][$k]!=""){ //  && $_POST["paid_by"][$k]!="fiado"

						if($_POST["paid_by"][$k]=="cash"){
							// se for pagamento em dinheiro "para ser frendly, mostramos o quantidade paga"
							$v = $v - $data['troco'];
						}
						
						$amountTotalPagos += $v;
						$payment[$k] = array(
							'date' => $date,
							'amount' => $v,
							'customer_id' => $customer_id,
							'paid_by' => $_POST["paid_by"][$k],
							'created_by' => $this->session->userdata('user_id'),
							'note' => null,
							//'pos_paid' => ($_POST["paid_by"][$k]=="cash") ? $amount : 0 , //$this->tec->formatDecimal($inv),
							//'pos_balance' => ($_POST["paid_by"][$k]=="cash") ? $troco : 0.00,
						);

					}
					
				}

				//$data['paid'] = $amountTotalPagos;

			} else {
				$payment = array();
			}
			
			//$this->tec->print_arrays($data, $products, $payment);
			
		}


		if ( $this->form_validation->run() == true && !empty($products) )
		{
		    
		    $proxima_chamada = (int) $this->Settings->ultima_chamada + 1;
		    
			if($suspend) {
			    
				unset($data['status'], $data['rounding'], $data['troco']);
				if($suspid = $this->pos_model->suspendSale($data, $products, $did)) {
					$this->session->set_userdata('rmspos', 1);
					$this->session->set_flashdata('message', lang("sale_saved_to_opened_bill"));
					//
					
					// adicionar chamada de senhas
					$this->db->update("settings", array('ultima_chamada' => $proxima_chamada));
	
					if($did){
					    redirect("pos/view_bill?hold=".$suspid."&noremove=1");
					}else{
					   redirect("pos/view_bill?hold=".$suspid."&noremove=1");
					}
				
				} else {

					$this->session->set_flashdata('error', lang("action_failed"));
					redirect("pos/".$did);
				}

			} elseif($eid) {

				unset($data['date'], $data['paid'], $data['troco'], $data['status']);
				$data['updated_at'] = date('Y-m-d H:i:s');
				$data['updated_by'] = $this->session->userdata('user_id');
				if($this->pos_model->updateSale($eid, $data, $products)) {
				    
				    	// adicionar chamada de senhas
					$this->db->update("settings", array('ultima_chamada' => $proxima_chamada));
					
					$this->session->set_userdata('rmspos', 1);
					$this->session->set_flashdata('message', lang("sale_updated"));
					redirect("sales");
				}
				else {
					$this->session->set_flashdata('error', lang("action_failed"));
					redirect("pos/?edit=".$eid);
				}

			} else {

				if($sale = $this->pos_model->addSale($data, $products, $payment, $did)) {
					$this->session->set_userdata('rmspos', 1);
					$msg = lang("sale_added");
					if (!empty($sale['message'])) {
						foreach ($sale['message'] as $m) {
							$msg .= '<br>' . $m;
						}
					}
					$this->session->set_flashdata('message', $msg);
					
				    // adicionar chamada de senhas
					$this->db->update("settings", array('ultima_chamada' => $proxima_chamada));
					
					if($this->Settings->pdvdiretonfc=="1"){
						redirect("pos/view/" . $sale['sale_id']. "?fim=1&emitirnfc=1");
					}else{
						redirect("pos/view/" . $sale['sale_id']. "?fim=1");
					}
				}
				else {
					$this->session->set_flashdata('error', lang("action_failed"));
					redirect("pos");
				}

			}
		}
		else
		{

			if(isset($sid) && !empty($sid)) {
				$suspended_sale = $this->pos_model->getSuspendedSaleByID($sid);
				$inv_items = $this->pos_model->getSuspendedSaleItems($sid);
				krsort($inv_items);
				$c = rand(100000, 9999999);
				foreach ($inv_items as $item) {
					$row = $this->site->getProductByID($item->product_id);
					if (!$row) {
						$row = json_decode('{}');
					}
					$row->price = $item->net_unit_price+($item->item_discount/$item->quantity);
					$row->unit_price = $item->unit_price+($item->item_discount/$item->quantity)+($item->item_tax/$item->quantity);
					$row->real_unit_price = $item->real_unit_price;
					$row->discount = $item->discount;
					$row->qty = $item->quantity;
					$row->comment = $item->note;
					$combo_items = FALSE;
					$ri = $this->Settings->item_addition ? $row->id : $c;
					$pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items);
					$c++;
				}

				$payments = $this->sales_model->getSalePayments("open_".$sid);
				$countcash = 0;
				$amount_total = 0;
				$tipopags_open = array();
				if($payments) {
					foreach ($payments as $payment) {
						$amount_total += $payment->amount;
					}
				}

				$this->data['totalpago_open'] = $amount_total;
				$this->data['items'] = json_encode($pr);
				$this->data['sid'] = $sid;
				$this->data['suspend_sale'] = $suspended_sale;
				$this->data['message'] = lang('suspended_sale_loaded');
			}

			if(isset($eid) && !empty($eid)) {
				$sale = $this->pos_model->getSaleByID($eid);
				$inv_items = $this->pos_model->getAllSaleItems($eid);
				krsort($inv_items);
				$c = rand(100000, 9999999);
				foreach ($inv_items as $item) {
					$row = $this->site->getProductByID($item->product_id);
					if (!$row) {
						$row = json_decode('{}');
					}
					$row->price = $item->net_unit_price;
					$row->unit_price = $item->unit_price;
					$row->real_unit_price = $item->real_unit_price;
					$row->discount = $item->discount;
					$row->qty = $item->quantity;
					$combo_items = FALSE;
					$row->quantity += $item->quantity;
					if ($row->type == 'combo') {
						$combo_items = $this->pos_model->getComboItemsByPID($row->id);
						foreach ($combo_items as $combo_item) {
							$combo_item->quantity += ($combo_item->qty*$item->quantity);
						}
					}
					$ri = $this->Settings->item_addition ? $row->id : $c;
					$pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items);
					$c++;
				}
				$this->data['items'] = json_encode($pr);
				$this->data['eid'] = $eid;
				$this->data['sale'] = $sale;
				$this->data['message'] = lang('sale_loaded');
			}

			if($this->session->userdata('acesso_nfc') != 1) {
				redirect("upgrade");
			}

		
			$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
			$this->data['reference_note'] = isset($sid) ? $suspended_sale->hold_ref : NULL;
			$this->data['sid'] = isset($sid) ? $sid : 0;
			$this->data['eid'] = isset($eid) ? $eid : 0;
			$this->data['vendedores'] = $this->site->getAllUsers();
			$this->data['meiopagamento'] = $this->site->getAllmeiopagamento();
			$this->data['customers'] = $this->site->getAllCustomers();
			$this->data["tcp"] = $this->pos_model->products_count($this->Settings->default_category);
			$this->data['products'] = $this->ajaxproducts($this->Settings->default_category, 1);
			$this->data['categories'] = $this->site->getAllCategories();
			$this->data['message'] = $this->session->flashdata('message');
			$this->data['suspended_sales'] = $this->site->getUserSuspenedSales();
			$mesas = array();
			foreach($this->data['suspended_sales'] as $m){
				$mesas[$m->hold_ref] = (array) $m;
			}
			$this->data["mesas"] = $mesas;
			$this->data['page_title'] = lang('pos');
			$this->data['user_info'] = $this->site->getUser();
			$bc = array(array('link' => '#', 'page' => lang('pos')));
			$meta = array('page_title' => lang('pos'), 'bc' => $bc);
			$this->load->view($this->theme.'pos/index', $this->data, $meta);

		}
}

	function nfe($sale_id = NULL, $tipo = 1, $escolha = "", $origem = "pos")
  {		

		if(DEMO) {
			$this->session->set_flashdata('error', "Esta é um versão de DEMONSTRAÇÃO, por segurança não será emitida um nota real.");
			redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'pos');
		}

		function limparString($str){
			if($str){
				return str_replace(array(".", "/", "-", " ", "(", ")"), "", $str);
			}else{
				return "";
			}
		}

		$endpoint = APINFE_URL_BASE;

		if($this->input->get('id')){ $sale_id = $this->input->get('id'); }

		//1 nfc  2 nf
		if($tipo==1){	
			$modelo = 65; $presenca = 1; $frete = 9; $impressao = 4;
			$seriepadrao = $this->Settings->serie_nfc;
		}elseif($tipo==2){
			$modelo = 55;  $presenca = 9; $frete = 0; $impressao = 1;
			$seriepadrao = $this->Settings->serie_nf;
		}
		if($seriepadrao==""){ $seriepadrao = 0; }

		//1 nfc  2 nf
		if($tipo==1 && $escolha=="emitir"){
			
			if($this->session->userdata('acesso_nfc') != 1 ) {
				redirect('upgrade');
			}

			if($this->session->userdata('limite_nfc') <= $this->site->getTotalNFC()) {
				redirect('upgrade');
			}

		}elseif($tipo==2  && $escolha=="emitir"){

			if($this->session->userdata('acesso_nf') != 1 ){
				redirect('upgrade');
			}

			if($this->session->userdata('limite_nf') <= $this->site->getTotalNF()) {
				redirect('upgrade');
			}

		}

		
		if($sale_id!=null && $sale_id!="0" && $origem == "pos"){ 

			if($tipo==1){
				$ultimoNF = $this->Settings->ultima_nfc;
			}else{
				$ultimoNF = $this->Settings->ultima_nf;
			}
			$inv = $this->pos_model->getSaleByID($sale_id);
			$customer_id = $inv->customer_id;
			
			$data['customer'] = $this->pos_model->getCustomerByID($customer_id);

			$origempos = true;

		}elseif($origem=="nf"){

			$inv = $this->pos_model->getNotaFiscalByID($sale_id);
			$origempos = false;

		}

		foreach($this->site->getAllmeiopagamento() as $pagamento){ $pag[$pagamento->cod] = $pagamento->nome; }

		if($inv->nf_status=="" && $inv->nf_chave=="" && $escolha=="emitir"){

			// PAGAMENTO
			$cnpj_credenciadora = array();
			$bandeira = array();
			$autorizacao = array();
			$pgto = array();
			$v_pgto = array();

			$pgtomess = "";

			if($origempos){
			    
			    if($tipo==2) $presenca = 1;
				
				$pp = 0;
				$totalpagamentos = 0;
				foreach($this->pos_model->getAllSalePayments($sale_id) as $pag){

					if($pag->paid_by=='cash'){
						$pg = '01';
						$pag->amount = $pag->amount + $inv->troco;
					} elseif($pag->paid_by=='stripe'){
						$pg = '04';
					} elseif($pag->paid_by=='CC'){
						$pg = '03';
					} elseif($pag->paid_by=='Cheque'){
						$pg = '02';
					} elseif($pag->paid_by=='valerefei'){
						$pg = '11';
					} elseif($pag->paid_by=='gift_card'){
						$pg = '12';
					} elseif($pag->paid_by=='valecombus'){
						$pg = '13';
					} elseif($pag->paid_by=='boleto'){
						$pg = '15';
					} elseif($pag->paid_by=='depbanc'){
						$pg = '16';
					} elseif($pag->paid_by=='pix'){
						$pg = '17';
					} elseif($pag->paid_by=='transf'){
						$pg = '18';
					} elseif($pag->paid_by=='progrfideli'){
						$pg = '19';
					}else{
						$pg = '99';
					}

					$pgto[$pp] = $pg;
					$v_pgto[$pp] = $pag->amount;
					$totalpagamentos += $pag->amount;
					$pp++;
				}


				if($pp == 0){
					echo "<h3>Nenhum pagamento foi adicionado na venda. Adicione o pagamento e tente emitir novamente</h3>";
					//echo "<a class='btn' href='".site_url('pos')."/view/".$sale_id."/'>Voltar</a>";
					die;
				}	

				if ($inv->paid < $round_total) { } // falta pagar (fazer algo)
				if ($inv->paid > $round_total) { 
					$troco = $inv->troco; //$inv->paid - $inv->grand_total;
				}

				$troco = ($troco <= 0)? "0" : $troco;
				
			}else{
				// painel emissor de NF

				$pp = 0;
				foreach($_REQUEST["idpag"] as $idp){

					$pag = $_REQUEST["tpag"][$idp];
					$pag = strval(str_pad($pag, 2, '0', STR_PAD_LEFT));
					$pgto[$pp] = $pag;
					$varorigi = $_REQUEST["vpag"][$idp];
					$v_pgto[$pp] = $this->tec->formatDolar($varorigi);

					if($pag=="99" && $this->input->post('tipooperacao')=="0"){ $v_pgto[$pp] = ""; } // outro só para notas de entradas??

					if($pag=="03" || $pag == "04"){
						if($_REQUEST["cnpjCredenciadora"][$idp]!="") $cnpj_credenciadora[$pp] = $_REQUEST["cnpjCredenciadora"][$idp];
						if($_REQUEST["tband"][$idp]!="") $bandeira[$pp] = $_REQUEST["tband"][$idp];
						if($_REQUEST["cAut"][$idp]!="") $autorizacao[$pp] = $_REQUEST["cAut"][$idp];
					}
					$pp++;

					if($pag=="01"){ $pgtomess .= " (Dinheiro: R$ ".$varorigi.") "; }
					if($pag=="02"){ $pgtomess .= " (Cheque: R$ ".$varorigi.") "; }
					if($pag=="03"){ $pgtomess .= " (Cartão de Crédito: R$ ".$varorigi.") "; }
					if($pag=="04"){ $pgtomess .= " (Cartão de Débito: R$ ".$varorigi.") "; }
					if($pag=="05"){ $pgtomess .= " (Crédito Loja: R$ ".$varorigi.") "; }
					if($pag=="10"){ $pgtomess .= " (Vale Alimentação: R$ ".$varorigi.") "; }
					if($pag=="11"){ $pgtomess .= " (Vale Refeição: R$ ".$varorigi.") "; }
					if($pag=="12"){ $pgtomess .= " (Vale Presente: R$ ".$varorigi.") "; }
					if($pag=="13"){ $pgtomess .= " (Vale Combustível: R$ ".$varorigi.") "; }
					if($pag=="15"){ $pgtomess .= " (Boleto Bancário: R$ ".$varorigi.") "; }
					if($pag=="16"){ $pgtomess .= " (Depósito Bancário: R$ ".$varorigi.") "; }
					if($pag=="17"){ $pgtomess .= " (Pagamento Instantâneo (PIX): R$ ".$varorigi.") "; }
					if($pag=="18"){ $pgtomess .= " (Transferência bancária, Carteira Digital: R$ ".$varorigi.") "; }
					if($pag=="19"){ $pgtomess .= " (Programa de fidelidade, Cashback, Crédito Virtual: R$ ".$varorigi.") "; }
					if($pag=="90"){ $pgtomess .= " (Sem pagamento: R$ ".$varorigi.") "; }
					if($pag=="99"){ $pgtomess .= " (Outros: R$ ".$varorigi.") "; }

				}
				
				if($pp == 0){
					echo "<h3>Nenhum pagamento foi adicionado na venda. Adicione o pagamento e tente emitir novamente</h3>";
					die;
				}

				$troco = ($this->input->post('troco')=="" || $this->tec->formatDolar($this->input->post('troco')) < 0)? "0" : $this->tec->formatDolar($this->input->post('troco'));

				if($pag=="90"){ $troco = null; }

			}

			// PEDIDO
			if($this->input->post('data_entrada_saida_auto')=="on"){
				$data_entrada_saida = "auto";
			}else{
				if($this->input->post('data_entrada_saida')!="" && $this->input->post('hora_entrada_saida')!=""){
					$data_entrada_saida = $this->input->post('data_entrada_saida')." ".$this->input->post('hora_entrada_saida').":00";
				}else{
					$data_entrada_saida = "";
				}
			}

			if($this->input->post('data_emissao')!=""){
				$dataemissao = $this->input->post('data_emissao');
			}else{
				$dataemissao = date("Y-m-d");
			}

			$ultimoNF = ($this->input->post('numero')=="")? $ultimoNF : $this->input->post('numero');

			$vat_no = limparString($this->Settings->vat_no);
			$ie = limparString( $this->Settings->ie);
			$im = limparString( $this->Settings->im);
			$tel = limparString( $this->Settings->phone_number);
			$cep = limparString( $this->Settings->postal_code);

			if($this->input->post('destinooperacao')!=""){
				$destinooperacao = 	$this->input->post('destinooperacao');
			}else{
				$destinooperacao = 	"1";
			}
			
			/**
			 * NATUREZA DA OPERACAO
			 */
			$obligaCFOP = "";
			if($this->input->post('natureza_operacao')!=""){
				
				$natopr = $this->input->post('natureza_operacao');
				$xnat = explode("/", $natopr);
				if(is_numeric($xnat[0]) === true){
					$natopr = $xnat[1];
					if($xnat[0]>0) $obligaCFOP = $xnat[0];
				}else{
					$natopr = $xnat[0];
				}
				
			} else{ 
				$natopr = 'Venda';
			}

			$presenca = ($this->input->post('presenca')=="")? $presenca : $this->input->post('presenca');

			$intermediario = "0";
			if($presenca==2 || $presenca==3 || $presenca==4 || $presenca==9){
				if($this->input->post('intermediador_cnpj')!="" || $this->input->post('intermediador_ident')!=""){
					$intermediario = "1";
				}
			}

			$data_nfe = array(
				'ID' => $sale_id, 
				'NF' => $ultimoNF,
				'operacao' => 1, 
				'destinooperacao' => $destinooperacao,
				'serie' => ($this->input->post('serie')=="")? $seriepadrao : $this->input->post('serie'), 
				'natureza_operacao' => $natopr, 
				'modelo' => $modelo, 
				'emissao' => 1, // normal ou contingencia
				'data_emissao' => $dataemissao,
				'data_entrada_saida' => ($data_entrada_saida)? $data_entrada_saida: "",
				'finalidade' => ($this->input->post('finalidade')=="")? 1 : $this->input->post('finalidade'), 
				'impressao' => $impressao,  
				'tipooperacao' => ($this->input->post('tipooperacao')=="")? 1 : $this->input->post('tipooperacao'),
				'nfe_referenciada' => ($this->input->post('nfe_referenciada')=="")? "" : $this->input->post('nfe_referenciada'),
				'consumidorfinal' => ($this->input->post('indFinal')=="")? "1" : $this->input->post('indFinal'),
				"tpAmb" => strval($this->Settings->tpAmb),  // TIPO DE AMBIENTE: 1 - PRODUCAO / 2 - HOMOLOGACAO
				"razaosocial" => strval($this->Settings->razaosocial),
				"cnpj" => strval($vat_no),
				"fantasia" => strval($this->Settings->fantasia), 
				"ie" => strval($ie), 
				"im" => strval($im),
				//"cnae" => strval($this->Settings->cnae),
				"crt" => strval($this->Settings->crt),
				"rua" => strval($this->Settings->address),
				"numero" => strval($this->Settings->numero),
				"bairro" => strval($this->Settings->bairro),
				"cidade" => strval($this->Settings->city),
				"ccidade" => strval($this->Settings->ccidade), 
				"cep" => strval($cep), 
				"siglaUF" => strval($this->Settings->estado), 
				"codigoUF" => strval($this->Settings->codigoUF), 
				"fone" => strval($tel),
				"tokenIBPT" => strval($this->Settings->tokenIBPT), 
				"CSC" => strval($this->Settings->CSC), 
				"CSCid" => strval($this->Settings->CSCid),
				"logo" => strval($this->Settings->logo), 
				"certificado" => strval($this->Settings->certificado), 
				"certificadosenha" => strval($this->Settings->certificadosenha),
				"timezone" => strval($this->timezone),
				'intermediario' => $intermediario, 
				'intermediador' => array(
					"cnpj" => $this->input->post('intermediador_cnpj'),
					"idcadastro" => $this->input->post('intermediador_ident')
				),
				'pedido' => array(
					'numero_interno' => $sale_id, 
					'pagamento' => 0, 
					'presenca' => $presenca,  
					'modalidade_frete' => ($this->input->post('modalidade_frete')=="")? $frete : $this->input->post('modalidade_frete'),  
					'frete' => ($this->input->post('frete')=="")? '0.00' : $this->tec->formatDolar($this->input->post('frete')), 
					'desconto' => ($this->input->post('desconto')=="")? $inv->total_discount : $this->tec->formatDolar($this->input->post('desconto')), 
					'total' => ($this->input->post('valorNota')=="")? $inv->total : $this->tec->formatDolar($this->input->post('valorNota')), 
					'troco' => $troco,
					'outras_despesas' => ($this->input->post('outrasDespesas')=="")? $inv->total_tax : $this->tec->formatDolar($this->input->post('outrasDespesas')),
					'forma_pagamento' => $pgto,
					'valor_pagamento' => $v_pgto,
					'cnpj_credenciadora' => $cnpj_credenciadora,
					'bandeira' => $bandeira,
					'autorizacao' => $autorizacao, 
					'tipo_integracao' => ($this->input->post('tpIntegra')=="")? "2" : $this->input->post('tpIntegra'),
				),
			);

			$errValidar = "";
			// VALIDADAR DADOS DO EMISSOR:
			if($data_nfe["razaosocial"]==""){ $errValidar .= "<br>Configure a Razão Social do emissor da nota fiscal"; }
			if($data_nfe["cnpj"]==""){ $errValidar .= "<br>Configure o CNPJ do emissor da nota fiscal"; }
			if($data_nfe["fantasia"]==""){ $errValidar .= "<br>Configure o Nome Fantasia do emissor da nota fiscal"; }
			if($data_nfe["ie"]==""){ $errValidar .= "<br>Configure a Inscrição Estadual do emissor da nota fiscal"; }
			if($data_nfe["crt"]==""){ $errValidar .= "<br>Configure o CRT do emissor da nota fiscal"; }
			if($data_nfe["rua"]==""){ $errValidar .= "<br>Configure o Rua do endereço do emissor da nota fiscal"; }
			if($data_nfe["numero"]==""){ $errValidar .= "<br>Configure o Número do endereço do emissor da nota fiscal"; }
			if($data_nfe["bairro"]==""){ $errValidar .= "<br>Configure o Bairro do endereço do emissor da nota fiscal"; }
			if($data_nfe["cidade"]==""){ $errValidar .= "<br>Configure a Cidade do endereço do emissor da nota fiscal"; }
			if($data_nfe["cep"]==""){ $errValidar .= "<br>Configure o CEP do endereço do emissor da nota fiscal"; }
			if($data_nfe["ccidade"]==""){ $errValidar .= "<br>Configure o Código da Cidade do endereço do emissor da nota fiscal"; }
			if($data_nfe["siglaUF"]==""){ $errValidar .= "<br>Configure o Estado do endereço do emissor da nota fiscal"; }
			if($data_nfe["codigoUF"]==""){ $errValidar .= "<br>Configure o Código do Estado do endereço do emissor da nota fiscal"; }
			if($data_nfe["fone"]==""){ $errValidar .= "<br>Configure o Telefone do emissor da nota fiscal"; }
			if($data_nfe["certificado"]==""){ $errValidar .= "<br>Deve fazer upload do certificado digital"; }
			if($data_nfe["certificadosenha"]==""){ $errValidar .= "<br>Configure a senha do certificado digital"; }
			if($modelo == "65"){
				if($data_nfe["CSC"]==""){ $errValidar .= "<br>Configure o CSC do emissor da nota (O Contador poderá te informar este dado)"; }
				if($data_nfe["CSCid"]==""){ $errValidar .= "<br>Configure o ID do CSC do emissor da nota (O Contador poderá te informar este dado)"; }
			}
		
			// TECNICO RESPONSAVEL

			if(tecCNPJ!=""){
				$data_nfe["tecnico"] = array(
					'cnpj' => tecCNPJ,
					'contato'=> tecxContato,
					'email'=> tecemail,
					'fone'=> tecfone,
					'csrt'=> tecCSRT,
					'idcsrt'=> tecidCSRT
				);
			}else{
				$nomeTecnico = explode(" ", $data_nfe["razaosocial"]);
				$nomeTecnico = trim($nomeTecnico[0]. " ".$nomeTecnico[1]. " ". $nomeTecnico[2]);

				$data_nfe["tecnico"] = array(
					'cnpj' => $data_nfe["cnpj"],
					'contato'=> $nomeTecnico,
					'email'=> "tdnclientes+nfe_".$data_nfe["cnpj"]."@gmail.com",
					'fone'=> $data_nfe["fone"],
					'csrt'=> null,
					'idcsrt'=> null
				);
			}

			// TRANSPORTE
			$transdoc = limparString($this->input->post('cnpjTransportador'));
			if(strlen($transdoc)==11){
				$t1 = 'cpf';
				$t2 = 'nome_completo';
			}else{
				$t1 = 'cnpj';
				$t2 = 'razao_social';
			}
			$data_nfe["transporte"] = array(
				$t2 => $this->input->post('transportador'),
				'placa' => $this->input->post('placa'),
				'uf_veiculo' => $this->input->post('ufVeiculo'),
				'rntc' => $this->input->post('rntc'),
				$t1 => limparString($this->input->post('cnpjTransportador')),
				'ie' => limparString($this->input->post('ieTransportador')), //  0 = isento
				'endereco' => $this->input->post('enderecoTransportador'),
				'cidade' => $this->input->post('municipioTransportador'),
				'uf' => $this->input->post('ufTransportador'),
				'seguro' => $this->tec->formatDolar($this->input->post('seguro')),
				'volume' => $this->input->post('qtdVolumes'),
				'especie' => $this->input->post('especie'),
				'marca' => $this->input->post('marca'),
				'numeracao' => $this->input->post('nroDosVolumes'),
				'peso_bruto' => $this->input->post('pesoBruto'),
				'peso_liquido' => $this->input->post('pesoLiquido')
			);
			
			$costumer = "";
			if($this->input->post('nomeVendedor')!=""){ $costumer .= " | Vendedor: ".$this->input->post('nomeVendedor')." ";}
			if($pgtomess) $costumer .= $pgtomess;

			// CLIENTE
			if($data['customer']->tipo_cad==1 || $this->input->post('tipoPessoa')=="F"){
				$d1 = 'cpf';
				$d2 = 'contato';
				$d3 = 'ie';
				$tipoPessoa = "F";
				$d3string = "";
				$iddocpersona = $this->input->post('cnpj'); 
			}elseif($data['customer']->tipo_cad==2 || $this->input->post('tipoPessoa')=="J"){
				$d1 = 'cnpj';
				$d2 = 'contato';
				$d3 = 'ie';
				$tipoPessoa = "J";
				$iddocpersona = $this->input->post('cnpj');
			}elseif($data['customer']->tipo_cad==3 || $this->input->post('tipoPessoa')=="E"){
				$d1 = 'id_estrangeiro';
				$d2 = 'contato';
				$d3 = 'ie_extran';
				$tipoPessoa = "E";
				$iddocpersona = $this->input->post('idext');
			}

			if($tipo==2){	// modelo NF
			
			
			    // emissão de NF-e, no pdv ou na pagina de vends
    			if($origempos && ($data['customer']->cep=="" || $data['customer']->numero=="" || $data['customer']->cf1=="" || $data['customer']->endereco=="" || $data['customer']->cidade=="" || $data['customer']->estado=="" || $data['customer']->codigocidade=="")){
    			    
    				echo "<h3>Preencha todos os dados do Cliente para emitir a NF-e, edite os dados do cliente e emita novamente.</h3>";
    			
    			    if($data['customer']->cep==""){ echo "- CEP<br>";}
    			    if($data['customer']->numero==""){ echo "- Número do endereço<br>";}
    			    if($data['customer']->cf1==""){ echo "- CNPJ/CPF<br>";}
    			    if($data['customer']->endereco==""){ echo "- Endereço<br>";}
    			    if($data['customer']->cidade==""){ echo "- Cidade<br>";}
    			    if($data['customer']->codigocidade==""){ echo "- Código da cidade<br>";}
    			    if($data['customer']->estado==""){ echo "- Estado<br>";}
    
    				die;
    				
    			}
    			
				if($tipoPessoa=="J"){
					if($data['customer']->cf2==""){
						 echo "- Falta informação da Inscrição Estadual do cliente ou se usar ISENTO se for o caso.<br>";
						 die;
					}
				}


				$cep = ($data['customer']->cep=="")? $this->input->post('cep') : $data['customer']->cep;
				$cep = limparString($cep);

				$data_nfe['cliente'] = array(
					$d1 => ($data['customer']->cf1=="")? limparString($iddocpersona) : limparString($data['customer']->cf1), 
					$d2 => ($data['customer']->name=="")? $this->input->post('contato') : $data['customer']->name, 
					$d3 => ($data['customer']->cf2=="")? limparString($this->input->post('ie')) : limparString($data['customer']->cf2), 
					'endereco' => ($data['customer']->endereco=="")? $this->input->post('endereco') : $data['customer']->endereco,
					'complemento' => ($data['customer']->complemento=="")? $this->input->post('complemento') : $data['customer']->complemento, 
					'numero' => ($data['customer']->numero=="")? $this->input->post('enderecoNro') : $data['customer']->numero, 
					'bairro' => ($data['customer']->bairro=="")? $this->input->post('bairro') : $data['customer']->bairro, 
					'cidade' => ($data['customer']->cidade=="")? $this->input->post('cidade') : $data['customer']->cidade, 
					'cidade_cod' => ($data['customer']->codigocidade=="")? $this->input->post('cidade_cod') : $data['customer']->codigocidade, 
					'uf' => ($data['customer']->estado=="")? $this->input->post('uf') : $data['customer']->estado, 
					'cep' => $cep,
					'telefone' => ($data['customer']->phone=="")? $this->input->post('fone') : $data['customer']->phone, 
					'email' => ($data['customer']->email=="")? $this->input->post('email') : $data['customer']->email,
					'indIEDest' => $this->input->post('indIEDest'),
					'cod_pais' => ($this->input->post('cod_pais')=="")? "1058" : $this->input->post('cod_pais'),
					'nome_pais' => ($this->input->post('nome_pais')=="")? "BRASIL" : $this->input->post('nome_pais'),
					'tipoPessoa' => $tipoPessoa
				);
				
			}else{ // MODELO NFC
			
				// CPF na nota
				$cpfnanota = limparString($data['customer']->cf1);
				if($cpfnanota !="" && strlen($cpfnanota)==11){
					// CPF na nota
					$data_nfe['cliente'] = array(
						'cpf' => $cpfnanota, 
						'indIEDest' => "9",
						'tipoPessoa' => "F",
						$d2 => ($data['customer']->name=="")? $this->input->post('contato') : $data['customer']->name, 
					);
				}elseif($cpfnanota !="" && strlen($cpfnanota)==14){
					// CNPJ NA NOTA
					$data_nfe['cliente'] = array(
						'cnpj' => $cpfnanota, 
						'indIEDest' => "9",
						'tipoPessoa' => "J",
						$d2 => ($data['customer']->name=="")? $this->input->post('contato') : $data['customer']->name, 
					);
				}
				
				if($data['customer']->id!="1"){ // não é o cliente padrão
					//if($data['customer']->name!="") $costumer .= ' | Cliente: '.$data['customer']->name;
					if($data['customer']->endereco!="") $costumer .= ' | Endereço:'.$data['customer']->endereco;
					if($data['customer']->cidade!="") $costumer .= ', '.$data['customer']->cidade;
					if($data['customer']->estado!="") $costumer .= ', '.$data['customer']->estado;
					//if($data['customer']->pais!="") $costumer .= ', '.$data['customer']->pais; 
					if($data['customer']->cep!="") $costumer .= ', '.$data['customer']->cep; 
					if($data['customer']->phone!="") $costumer .= ' | Telefone: '.$data['customer']->phone;
					if($data['customer']->email!="") $costumer .= ' | Email: '.$data['customer']->email;
				}

			

			}
			
				$complementosNfe = $this->Settings->footer;
				
				// PRODUTOS
				if($origempos){

					$x = 0;
					foreach($this->pos_model->getAllSaleItems($sale_id) as $prod){	

						if($prod->product_imposto!=""){
							$impostos = $this->pos_model->getImpostos($prod->product_imposto);
						}
						
						$data_nfe['produtos'][$x] = array(
							'item' => $prod->product_code, 
							'nome' => $prod->product_name, 
							'ean' => '',
							'ncm' => $prod->product_ncm, 
							'cest' => $prod->product_cest,
							'unidade' => $prod->product_unit, 
							'quantidade' => $prod->quantity, 
							'peso' => '0.200',
							'origem' => $prod->product_origem, 
							'desconto' => $prod->discount,
							'subtotal' => number_format((float)$this->tec->formatDecimal($prod->unit_price), 2, '.', ''), 
							'total' => number_format((float)$prod->subtotal, 2, '.', ''),
						);

						$data_nfe['produtos'][$x]['tipo_item'] = $impostos->tipo;
						$data_nfe['produtos'][$x]['impostos']['icms']['origem'] = $prod->product_origem;
						
						$codigo_cfop = "";
						if($obligaCFOP!="" && $impostos->tipo == "1"){ // CFOP OBRIGATORIO E É UM PRODUTO
							if($destinooperacao=="1"){
								$codigo_cfop = $obligaCFOP;
							}elseif($destinooperacao=="2"){
								$codigo_cfop = (int)($obligaCFOP) + 1000;
							}elseif($destinooperacao=="3"){
								$codigo_cfop = (int)($obligaCFOP) + 2000;
							} 

						}else{
							
							if($destinooperacao=="1"){
								$codigo_cfop = $prod->product_cfop;
							}elseif($destinooperacao=="2"){
								$codigo_cfop = (int)($prod->product_cfop) + 1000;
							}elseif($destinooperacao=="3"){
								$codigo_cfop = (int)($prod->product_cfop) + 2000;
							}
							
						}


						$data_nfe['produtos'][$x]['impostos']['icms']['codigo_cfop'] = $codigo_cfop;

						if($impostos->regras!="" && $prod->product_imposto){

							$impostos->regras = preg_replace('/\\\\/m', "", $impostos->regras);
							$js = json_decode($impostos->regras, true);
	
							foreach($js as $a => $b){
								if(is_array($b)){
									foreach($b as $c => $d){
										if(is_array($d)){
											foreach($d as $e => $f){
												$data_nfe['produtos'][$x][$a][$c][$e] = $f;
											}
										}else{
											$data_nfe['produtos'][$x][$a][$c] = $d;
										}
									}
								}else{
									$data_nfe['produtos'][$x][$a] = $b;
								}
							}
						}
						
						$x++;

					}

				} else { // EMISSOR DE NOTAS

					$x = 0;
					foreach($_REQUEST["produtoId"] as $prod){	

						$product = $this->site->getProductByID($_REQUEST["produtoId"][$x]);

						if($product->imposto!=""){
							$impostos = $this->pos_model->getImpostos($product->imposto);
							
						}
						
						$produnit_price = $this->tec->formatDolar($_REQUEST["precounitario"][$x]);

						$data_nfe['produtos'][$x] = array(
							'item' => $_REQUEST["codigo"][$x], // ITEM do produto
							'nome' => $_REQUEST["produto"][$x], 
							'codigo' => $_REQUEST["codigo"][$x],
							'ean' => '',
							'ncm' => $_REQUEST["ncm"][$x], 
							'cest' => $product->cest,
							'unidade' => $_REQUEST["un"][$x], 
							'quantidade' => $this->tec->formatDolar($_REQUEST["quantidade"][$x]), 
							'peso' => '0.200',
							'desconto' => "",
							'subtotal' => number_format((float)$produnit_price, 2, '.', ''), 
							'total' => number_format((float)($produnit_price * $this->tec->formatDolar($_REQUEST["quantidade"][$x])), 2, '.', ''),
							'informacoes_adicionais' => $_REQUEST["informacoes_adicionais"][$x]
						);

						$data_nfe['produtos'][$x]['impostos']['icms']['origem'] = $product->origem;
						$codigo_cfop = "";
						if($obligaCFOP!="" && $impostos->tipo == "1"){ // CFOP OBRIGATORIO E É UM PRODUTO
							if($destinooperacao=="1"){
								$codigo_cfop = $obligaCFOP;
							}elseif($destinooperacao=="2"){
								$codigo_cfop = (int)($obligaCFOP) + 1000;
							}elseif($destinooperacao=="3"){
								$codigo_cfop = (int)($obligaCFOP) + 2000;
							} 

						}else{
							
							if($destinooperacao=="1"){
								$codigo_cfop = $product->cfop;
							}elseif($destinooperacao=="2"){
								$codigo_cfop = (int)($product->cfop) + 1000;
							}elseif($destinooperacao=="3"){
								$codigo_cfop = (int)($product->cfop) + 2000;
							}
							
						}

						$data_nfe['produtos'][$x]['impostos']['icms']['codigo_cfop'] = $codigo_cfop;

						if($impostos->regras!="" && $product->imposto){
						    
						    $impostos->regras = preg_replace('/\\\\/m', "", $impostos->regras);
							$js = json_decode($impostos->regras, true);

							$data_nfe['produtos'][$x]['tipo_item'] = $impostos->tipo;

							foreach($js as $a => $b){
								if(is_array($b)){
									foreach($b as $c => $d){
										if(is_array($d)){
											foreach($d as $e => $f){
												$data_nfe['produtos'][$x][$a][$c][$e] = $f;
											}
										}else{
											$data_nfe['produtos'][$x][$a][$c] = $d;
										}
									}
								}else{
									$data_nfe['produtos'][$x][$a] = $b;
								}
							}
						}
						$x++;
					}

				}


				// VENCIMENTOS
				$x = 0;
				$totalvenc = 0;
				foreach($_REQUEST["idvenci"] as $idven){	
					$data_nfe['duplicata']['numero'][$x] = strval(str_pad(($x+1), 3, '0', STR_PAD_LEFT));
					$data_nfe['duplicata']['vencimento'][$x] = $_REQUEST["datavenci"][$idven];
					$data_nfe['duplicata']['valor'][$x] = $this->tec->formatDolar($_REQUEST["vvenci"][$idven]);
					$totalvenc += $this->tec->formatDolar($_REQUEST["vvenci"][$idven]);
					$x++;
				}

				if($x>0){
					$desconto = 0.00;
					$data_nfe['fatura']['numero'] = "001";
					$data_nfe['fatura']['valor'] = $totalvenc;
					$data_nfe['fatura']['desconto'] = $desconto;
					$data_nfe['fatura']['valor_liquido'] = ($totalvenc - $desconto);
				}
				
				// INFORMACOES DO CLIENTE
				$data_nfe['pedido']['informacoes_complementares'] = $complementosNfe." | ".$_REQUEST["observacoes"]." | ". $costumer ." | v.1";
				
				// check Contingencia
				if(!$this->site->is_connected()){ 

					if($modelo=="55"){
						echo '<h2>Não será possível emitir a nota nesse momento: Sem conexão de internet. Tente novamente com conexão de internet.</h2>';
						die;
					}

					$data_nfe['conti'] = 1;
				}

				if($this->input->post('teste')=="ok"){
					$data_nfe['teste'] = "ok";
				}

				// erro de valicações
				if($errValidar!=""){
					echo '<h2>Erro na emissão:</h2>';
					echo '<p>'.$errValidar.'</p>';
					die;
				}

				$data_nfe["endpoint"] = $endpoint;

				$fields_string = http_build_query($data_nfe);
				if(ENVIRONMENT == "development"){
				
					print_r($data_nfe);
					echo "<br><br><br>:: URL ::<br>";
					echo $endpoint."gerador/Emissor.php?".$fields_string;
					echo "<br><br><br>::: DADOS :::<br>";
					foreach(explode("&", $fields_string) as $k => $v) {
						$x2 = explode("=", $v);
						echo urldecode($x2[0])." = ". urldecode($x2[1]) . "<br>";
					}
					die;
				}

				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL, $endpoint."gerador/Emissor.php");
				curl_setopt($ch,CURLOPT_POST, count($data_nfe, COUNT_RECURSIVE));
				curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$response_server = curl_exec($ch);
				$response = json_decode($response_server);
				curl_close($ch);
				
				
			if (isset($response->error)){
				if ($response->update!='0') {
					// DONT NEED TO UPDATE THE NEXT NOTE
				}
				
				echo '<h2>Erro na emissão: '.$response->error.'</h2>';
				if (isset($response->log)){
					echo '<h3>Log:</h3>';
					echo '<ul>';
					foreach ($response->log as $erros){
						foreach ($erros as $erro) {
							echo '<li>'.$erro.'</li>';
						}
					}
					echo '</ul>';
				}
				
				
				// explicação para erros:
    			$arrayExplicacao = array(
				"245" => "Seu CNPJ ou Inscrição estadual não está permitido para emitir notas no momento, entre em contato com o SEFAZ do seu Estado para regularizar.",
				"301" => "Deverá verificar se a sua Inscrição Estadual está em situação Regular / Ativa. É possível realizar a consulta de sua situação cadastral através do site do SINTEGRA ou no Cadastro Centralizado de Contribuinte. Nas consultas é exibido os termos “Habilitado” ou “Não Habilitado”. O resultado “Habilitado” é uma indicação de que não há qualquer restrição em relação à Inscrição Estadual consultada, enquanto o termo “Não Habilitado” indica que a Inscrição Estadual existe algum bloqueio no cadastro da Secretaria de Fazenda.",
				"328" => "No Emissor de notas fiscais, altere o campo Finalidade para a opção 4 - Devolução.",
				"539" => "Já foi emitida um nota com esse mesmo número antes em outro sistema.<br><br>1) Altere o número da próxima NFC-e ou NF-e (depende de qual estiver emitindo) que será emitida em <a target='_blank' href='".site_url("settings")."'>Em Configurações</a> (para isso terá que saber qual o número da última nota emitida). Exemplo: Se a última emitida foi 100 altere para 101, guarde e envie novamente nota.<br>2) Se não sabe qual foi a última nota emitida, poderá alterar a série também, colocando a série 2 por exemplo e alterar a próxima nota para 1, assim irá reiniciar a contagem das notas.",
				"868" => "O grupo de transporte não poderá ser usado nesse nota. Remova os dados do transporte, placa, estado, RNTC e etc. Envie novamente a nota",
				"464" => "<a target='_blank' href='".site_url("settings")."'>Em Configurações</a> -> Notas fiscais: Verifique o código CSC, lembre-se que o CSC é diferente para os ambiente homologação e produção, use o correto para o ambiente que está emitindo. Verifique também o CSC ID, ele deve ter 6 digitos, exemplo: 000001."
			);
    
    			if(!empty($arrayExplicacao[$response->cstat])){
    
    				$info_ajuda = $arrayExplicacao[$response->cstat];
    
    				echo '<div style="width: calc(100% - 30px); margin: 20px 0px; padding: 10px; border-radius: 8px; border: 2px solid #000000; font-size: 18px; background: #ecffe8; font-family: arial;"><b style="font-size:20px;">Te ajudamos a resolver :)</b><br><br>'.$info_ajuda.'</div>';
    
    			}
			
			
			}elseif($response->teste == "ok"){

				$cont = "tipo=teste&";
				header("location: ". $endpoint ."danfe/?".$cont."chave=".$response->chave."&logo=".$this->Settings->logo);
				exit;


			}elseif(!$response){
				
				echo '<h2>Erro no servidor ao emitir, contacte com o suporte.</h2>';
				var_dump($response_server);
			
			}else {

				if($tipo==1){
					$this->pos_model->UpdateLastNFCNumero($ultimoNF);
				}else{
					$this->pos_model->UpdateLastNFNumero($ultimoNF);
				}
				
				$status = (string) $response->status; // aprovado, reprovado, cancelado, processamento ou contingencia
				$nfe = (int) $response->nfe; // número da NF-e
				$serie = (int) $response->serie; // número de série
				$recibo = (int) $response->recibo; // número do recibo
				$chave = $response->chave; // número da chave de acesso
				$xml = (string) $response->xml; // URL do XML


				if($status=="processamento" || $status=="em processamento"){
					$danfe = $recibo;
					// salva o recibo no campo danfe
				}
				// fazer upload dos dados
				if($tipo==1){ // NFC
					$this->pos_model->UpdateDadosNFC($sale_id, $status, $nfe, $chave, $danfe, $xml, 65);
					$user = $this->session->userdata('user_id');
					// também adicionamos a nota fiscal NFC ao listado de notas fiscais
					$this->pos_model->InsertDadosNF($status, $nfe, $chave, $danfe, $xml, 65, $user, $customer_id);
				}else{
				   	if($origempos){$this->pos_model->UpdateDadosNFC($sale_id, $status, $nfe, $chave, $danfe, $xml, 55);	}
					// todo: inserir em vendas
					$user = $this->session->userdata('user_id');
					$idcont = $this->input->post('idContato');
					$this->pos_model->InsertDadosNF($status, $nfe, $chave, $danfe, $xml, 55, $user, $idcont);
				}

				if($status == "processamento" || $status == "em processamento"){
					$cont = "tipo=processamento&";
				}

				if($status == "contingencia"){
					$cont = "tipo=conti&";
				}

				// Envio de email
				if($status == "aprovado"){

					$enviopara = ($tipo==2)? $this->input->post('email') : $data['customer']->email;

					if($enviopara!="" && $tipo==2){

						$url = $endpoint.'/lib-local/envio_email_nf.php';
						$fieldsEM = array(
							'para' => urlencode($enviopara),
							'danfe' => urlencode($endpoint ."danfe/?".$cont."chave=".$chave."&logo=".$this->Settings->logo."&tamanhopapel".$this->Settings->tamanhopapel),
							'xml' => urlencode($endpoint."gerador/xml/autorizadas/$chave.xml")
						);

						foreach($fieldsEM as $key=>$value) { $fieldsEM_string .= $key.'='.$value.'&'; }
						$fieldsEM_string = rtrim($fieldsEM_string, '&');

						$ch = curl_init();
						curl_setopt($ch,CURLOPT_URL, $url);
						curl_setopt($ch,CURLOPT_POST, count($fieldsEM));
						curl_setopt($ch,CURLOPT_POSTFIELDS, $fieldsEM_string);
						$result = curl_exec($ch);
						curl_close($ch);

					}
					
					$urlcompleto = ($endpoint ."danfe/?".$cont."chave=".$chave."&logo=".$this->Settings->logo."&tamanhopapel=".$this->Settings->tamanhopapel);
					$prxnf = $ultimoNF + 1;
					echo "<script>try { window.opener.reloadframe(); } catch(err) { }
					        window.location = '$urlcompleto'; 
					    </script>";
					exit;

				}elseif($status == "processamento" || $status == "em processamento"){
					
					$cont = "tipo=processamento&";
					$urlcompleto = ($endpoint ."danfe/?".$cont."chave=".$chave."&logo=".$this->Settings->logo."&tamanhopapel=".$this->Settings->tamanhopapel);
					$prxnf = $ultimoNF + 1;
					echo "<script>try { window.opener.reloadframe(); } catch(err) { }
						window.location = '$urlcompleto'; 
					</script>";
					exit;

				}elseif($status == "contingencia"){
					
					$cont = "tipo=conti&";
					$urlcompleto = ($endpoint ."danfe/?".$cont."chave=".$chave."&logo=".$this->Settings->logo."&tamanhopapel=".$this->Settings->tamanhopapel);
					$prxnf = $ultimoNF + 1;
					echo "<script>try { window.opener.reloadframe(); } catch(err) { }
						window.location = '$urlcompleto'; 
					</script>";
					exit;

				} else{

					echo "<h2>Sua nota não foi aprovado no momento.<br>Status: $status <br>Entre em contato com o suporte.<h2>";
					die;

				}

			}

		}	
		else	
		{ 

			if($escolha==1){
				
				// Imprimir Danfe
				if($inv->nf_status=="contingencia") $cont = "tipo=conti&";
				if($inv->nf_status=="cancelado") $cont = "tipo=cancelado&";
				if($inv->nf_status=="processamento" || $inv->nf_status=="em processamento") $cont = "tipo=processamento&";

				header("location: ". $endpoint ."danfe/index.php?".$cont."chave=".$inv->nf_chave."&logo=".$this->Settings->logo."&tamanhopapel=".$this->Settings->tamanhopapel);
				exit;
			
			}elseif($escolha==2){
			
				$file_url =  $endpoint.$inv->nf_xml;
				$file_url = str_replace("api-nfe/api-nfe", "api-nfe", $file_url);

				header('Content-type: text/xml');
				header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
				$myXMLData = file_get_contents($file_url);
				echo $myXMLData;
				exit;
			
			}elseif($escolha==3){
				
				if($inv->nf_status=="aprovado"){
					echo "<style>select{font-size:16px;}.btn{ cursor:pointer; font-size: 18px;color: #FFF; padding: 6px;background: #1db31d;margin: 10px;font-family: sans-serif;font-weight: bold; border:0px; border-radius: 4px;text-decoration: none; }</style>";
					echo '<script>function Clique(){  document.getElementById("btn1").disabled = true;  document.getElementById("btn1").innerHTML = "Enviando... Aguarde..."; }</script>';
				
					$vat_no = limparString( $this->Settings->vat_no);
					$ie = limparString( $this->Settings->ie);
					$im = limparString( $this->Settings->im);
					$tel = limparString( $this->Settings->phone_number);
					
					$data_sett = array(
						"tpAmb" => strval($this->Settings->tpAmb), 
						"razaosocial" => strval($this->Settings->razaosocial),
						"cnpj" => strval($vat_no),
						"fantasia" => strval($this->Settings->fantasia), 
						"ie" => strval($ie), 
						"im" => strval($im),
						//"cnae" => strval($this->Settings->cnae),
						"crt" => strval($this->Settings->crt),
						"rua" => strval($this->Settings->address),
						"numero" => strval($this->Settings->numero),
						"bairro" => strval($this->Settings->bairro),
						"cidade" => strval($this->Settings->city),
						"ccidade" => strval($this->Settings->ccidade), 
						"cep" => strval($this->Settings->postal_code), 
						"siglaUF" => strval($this->Settings->estado), 
						"codigoUF" => strval($this->Settings->codigoUF), 
						"fone" => strval($tel),
						"tokenIBPT" => strval($this->Settings->tokenIBPT), 
						"CSC" => strval($this->Settings->CSC), 
						"CSCid" => strval($this->Settings->CSCid),
						"certificado" => strval($this->Settings->certificado), 
						"certificadosenha" => strval($this->Settings->certificadosenha),
						"timezone" => strval($this->timezone)
					);
					$fields_sett = http_build_query($data_sett);
					
		
					echo "<form method='POST' action='". $endpoint ."gerador/CancelarNota.php?nocache=".date("ymsddhiss")."'>
					Selecione o motivo do cancelamento:<br><br>
					<select name='motivo'><option value='ERRO PREENCIMENTO DADOS'>ERRO PREENCIMENTO DADOS</option>
					<option value='REFAZER COM PRECO MENOR'>REFAZER COM PRECO MENOR</option>
					<option value='ERRO IMPRESSAO'>ERRO IMPRESSAO</option>
					<option value='PEDIDO DE TESTE'>PEDIDO DE TESTE</option>
					<option value='CLIENTE CANCELOU A COMPRA'>CLIENTE CANCELOU A COMPRA</option>
					</select><br><br>
					<button id='btn1' type='submit' class='btn' value='Cancelar Nota'>Cancelar Nota</button>
					<input name='ID' type='hidden' value='".$sale_id."'>
					<input name='nfe' type='hidden' value='".$inv->nf_numero."'>
					<input name='chave' type='hidden' value='".$inv->nf_chave."'>
					<input name='endpoint' type='hidden' value='". PDV_URL_BASE ."'>
					<input name='nocache' type='hidden' value='".date("ymsddhissss")."'>";
					
					foreach($data_sett as $k => $v) {
						echo "<input type='hidden' name='$k' value='$v'>";
					}
					echo "</form>";

					//echo "<a class='btn' href='".site_url('pos')."/nfe/".$sale_id."/2/-/$origem'>Voltar</a>";

					die;
					
				}else{

					echo "Não é possivel cancelar esta nota.";
					die;
				}	
			}elseif($escolha==4){
				
				if($inv->nf_status=="aprovado"){
					echo "<style>input { font-family: sans-serif; padding:5px; width:100%;} input:invalid { border: 2px solid red; } input:invalid:focus { border: 2px solid red; } input{font-size:14px;}.btn{ cursor:pointer; font-size: 18px;color: #FFF; padding: 6px;background: #1db31d;margin: 10px;font-family: sans-serif;font-weight: bold; border:0px; border-radius: 4px;text-decoration: none; }</style>";
					echo '<script>function Clique(){  document.getElementById("btn1").disabled = true;  document.getElementById("btn1").innerHTML = "Enviando... Aguarde..."; }</script>';
				
					$vat_no = limparString( $this->Settings->vat_no);
					$ie = limparString( $this->Settings->ie);
					$im = limparString( $this->Settings->im);
					$tel = limparString( $this->Settings->phone_number);
					
					$data_sett = array(
						"tpAmb" => strval($this->Settings->tpAmb), 
						"razaosocial" => strval($this->Settings->razaosocial),
						"cnpj" => strval($vat_no),
						"fantasia" => strval($this->Settings->fantasia), 
						"ie" => strval($ie), 
						"im" => strval($im),
						//"cnae" => strval($this->Settings->cnae),
						"crt" => strval($this->Settings->crt),
						"rua" => strval($this->Settings->address),
						"numero" => strval($this->Settings->numero),
						"bairro" => strval($this->Settings->bairro),
						"cidade" => strval($this->Settings->city),
						"ccidade" => strval($this->Settings->ccidade), 
						"cep" => strval($this->Settings->postal_code), 
						"siglaUF" => strval($this->Settings->estado), 
						"codigoUF" => strval($this->Settings->codigoUF), 
						"fone" => strval($tel),
						"tokenIBPT" => strval($this->Settings->tokenIBPT), 
						"CSC" => strval($this->Settings->CSC), 
						"CSCid" => strval($this->Settings->CSCid),
						"certificado" => strval($this->Settings->certificado), 
						"certificadosenha" => strval($this->Settings->certificadosenha),
						"timezone" => strval($this->timezone)
					);
					$fields_sett = http_build_query($data_sett);

					echo "<form method='POST' action='". $endpoint ."gerador/CCNota.php'>
					Correção a ser considerada, texto livre.<br><br>
					<input placeholder='seu texto' name='motivo' required='true' minlength='10' maxlength='3000'>
					<input name='ID' type='hidden' value='".$sale_id."'>
					<input name='nfe' type='hidden' value='".$inv->nf_numero."'>
					<input name='chave' type='hidden' value='".$inv->nf_chave."'>
					<input name='endpoint' type='hidden' value='". PDV_URL_BASE ."'>";

					foreach($data_sett as $k => $v) {
						echo "<input type='hidden' name='$k' value='$v'>";
					}

					$data_sett2 = array(
						"razao" => strval($this->Settings->razaosocial),
						"cnpj" => strval($vat_no),
						"fantasia" => strval($this->Settings->fantasia), 
						"logradouro" => strval($this->Settings->address),
						"numero" => strval($this->Settings->numero),
						"bairro" => strval($this->Settings->bairro),
						"municipio" => strval($this->Settings->city),
						"CEP" => strval($this->Settings->postal_code), 
						"UF" => strval($this->Settings->estado), 
						"telefone" => strval($tel)
					);

					$fields_sett2 = "";
					foreach($data_sett2 as $k => $v) {
						$fields_sett2 .= "&a[$k]=$v";
					}


					$nomecomeza = $inv->nf_chave;

					$directory = __DIR__ . '/../../api-nfe/gerador/xml/correcao';
					$scanned_directory = scandir($directory);

					$last = 0;
					$correcoeslinks = "";
					foreach($scanned_directory as $d){
						if (strpos($d, $nomecomeza) !== false) {
							$e = explode("-", $d);
							if(!empty($e[1])){
								$s = explode(".", $e[1]);
								$s = $s[0];
								$correcoeslinks .= "<a id='btn1' onClick='Clique()' href='". $endpoint  ."danfe/index.php?tipo=correcao&sequencia=".$s."&chave=".$inv->nf_chave."&logo=".$this->Settings->logo.$fields_sett2."&tamanhopapel=".$this->Settings->tamanhopapel."' target='_blank'>$s - Abrir PDF do documento fiscal</a><br>";
								$last = $s;
							}
						} 
					}
					$next = $last + 1;
					echo "<br><br>Sequencia: <input name='sequencia' type='number' style='width:100px' value='$next'>";

					echo "<br><br><button id='btn1' type='submit' class='btn'>Corrigir Nota</button>";

					echo "</form>";
					// historico
					echo "<br><hr><h3>Histório de correções:</h3><br>";
					echo $correcoeslinks;

					die;
					
				}else{

					echo "<h2>Não é possivel corrigir esta nota, necesita estar aprovada</h2>";
					die;
				}	
		
			}else{
				// SELECIONAR OPCAO
				
				echo "<style>a{ font-size: 22px;color: #FFF; padding: 6px;background: #1db31d;margin: 10px;font-family: sans-serif;font-weight: bold;border-radius: 4px;text-decoration: none; }</style>";
				
				if($inv->nf_status==""){
					echo "<a id='btn1' onClick='Clique()' href='".site_url('pos')."/nfe/".$sale_id."/1/emitir/$origem'>Emitir NFC-e</a>";
					echo "<a id='btn2' onClick='Clique2()' href='".site_url('pos')."/nfe/".$sale_id."/2/emitir/$origem'>Emitir NF-e</a>";
					echo '<script>function Clique(){  document.getElementById("btn1").disabled = true; document.getElementById("btn1").innerHTML = "Aguarde...";   }</script>';
					echo '<script>function Clique2(){  document.getElementById("btn2").disabled = true; document.getElementById("btn2").innerHTML = "Aguarde...";  }</script>';
				}else{

					echo "Estado da nota: <b>".$inv->nf_status."</b><br><br>";

					if(($inv->nf_status=="cancelado") || ($inv->nf_status=="cancelada")){
				
						echo "<a target='_blank' href='".site_url('pos')."/nfe/".$sale_id."/1/1/$origem'>DANFE</a><a target='_blank' href='".site_url('pos')."/nfe/".$sale_id."/1/2/$origem'>Baixar XML</a>";
					
					} elseif($inv->nf_status=="negado"){
				
						echo "<a href='javascrtipt:void(0)' onclick='window.close()'>Fechar Janela</a>";
					
					}else{
						echo "<a target='_blank' href='".site_url('pos')."/nfe/".$sale_id."/1/1/$origem'>Ver DANFE</a><a href='".site_url('pos')."/nfe/".$sale_id."/1/2/$origem'>Baixar XML</a><a href='".site_url('pos')."/nfe/".$sale_id."/1/3/$origem'>Cancelar Nota</a>";
					}

					die;
					
				}
		
			}

		}
			
	}
  
   
   function nfe_updatadados() {

		$endpoint = APINFE_URL_BASE;
		$update = true;
		$directprint = true;
		
		$status = $this->input->get('status'); // aprovado, reprovado, cancelado, processamento ou contingencia
		$nfe = (int) $this->input->get('nfe'); // número da NF-e
    	$id = (int) $this->input->get('ID'); // número da NF-e
		$serie = (int) $this->input->get('serie'); // número de série
		$recibo = (int) $this->input->get('recibo'); // número do recibo
		$chave = $this->input->get('chave'); // número da chave de acesso
		$xml = (string) $this->input->get('xml'); // URL do XML
		$modelo = (string) $this->input->get('modelo'); // URL do XML
		$sequencia = (string) $this->input->get('sequencia'); // URL do XML
    
		if(($status=="cancelado") || ($status=="cancelada")){ 
			$directprint = false;
		}

		if($status=="correcao"){ 
			$update = false;
			$directprint = false;
		}

		if($update==true){
			if($modelo=="65"){
				// fazer upload dos dados
				$this->pos_model->UpdateDadosNFC($id, $status, $nfe, $chave, $danfe, $xml, 65);
				$this->pos_model->UpdateDadosNFbyNumero($status, $nfe, $chave, $danfe, $xml, 65);
			}else{
				// fazer upload dos dados
				$this->pos_model->UpdateDadosNFbyNumero($status, $nfe, $chave, $danfe, $xml, 55);
			}
		}

		if($status=="cancelado" || $status=="cancelada") echo "<h1>Nota Fiscal cancelada com sucesso</h1>"; 	
		if($status=="correcao") echo "<h1>Nota Fiscal corrigida com sucesso</h1>";
      
		if($status=="contingencia") $cont = "tipo=conti&";
		if($status=="processamento" || $status=="em processamento") $cont = "tipo=processamento&";
		if($status=="cancelado" || $status=="cancelada") $cont = "tipo=cancelado&";
		if($status=="correcao") $cont = "tipo=correcao&sequencia=".$sequencia."&";

		$data_sett2 = array(
			"razao" => strval($this->Settings->razaosocial),
			"cnpj" => strval($vat_no),
			"fantasia" => strval($this->Settings->fantasia), 
			"logradouro" => strval($this->Settings->address),
			"numero" => strval($this->Settings->numero),
			"bairro" => strval($this->Settings->bairro),
			"municipio" => strval($this->Settings->city),
			"CEP" => strval($this->Settings->postal_code), 
			"UF" => strval($this->Settings->estado), 
			"telefone" => strval($tel)
		);

		$fields_sett2 = "";
		foreach($data_sett2 as $k => $v) {
			$fields_sett2 .= "&a[$k]=$v";
		}

		if($directprint==true){ 
			header("location: ". $endpoint  ."danfe/index.php?".$cont."chave=".$chave."&logo=".$this->Settings->logo.$fields_sett2."&tamanhopapel=".$this->Settings->tamanhopapel); exit;
		}else{
			echo "<a href='". $endpoint  ."danfe/index.php?".$cont."chave=".$chave."&logo=".$this->Settings->logo.$fields_sett2."&tamanhopapel=".$this->Settings->tamanhopapel."' target='_blank'>Abrir PDF do documento fiscal</a>";
		}

  }
   
  
  function nfe_contingencia() {

	if(!$this->site->is_connected()){ 
		echo "<h2>Necesita estar conectado a internet para enviar as notas, verifique sua conexão e tente novamente.</h2>";
		die;
	}
	
    $erross = array();
	$endpoint = APINFE_URL_BASE;

	function limparString($str){
		if($str){
			return str_replace(array(".", "/", "-", " ", "(", ")"), "", $str);
		}else{
			return "";
		}
	}
    
	$countcont = 0;
    foreach($this->pos_model->getAllSalesContingencia() as $v){
		$countcont++;
		$vat_no = limparString( $this->Settings->vat_no);
		$ie = limparString( $this->Settings->ie);
		$im = limparString( $this->Settings->im);
		$tel = limparString( $this->Settings->phone_number);

		$data_nfe = array(
			"tpAmb" => strval($this->Settings->tpAmb),
			"razaosocial" => strval($this->Settings->razaosocial),
			"cnpj" => strval($vat_no),
			"fantasia" => strval($this->Settings->fantasia),
			"ie" => strval($ie),
			"im" => strval($im),
			//"cnae" => strval($this->Settings->cnae),
			"crt" => strval($this->Settings->crt),
			"rua" => strval($this->Settings->address),
			"numero" => strval($this->Settings->numero),
			"bairro" => strval($this->Settings->bairro),
			"cidade" => strval($this->Settings->city),
			"ccidade" => strval($this->Settings->ccidade),
			"cep" => strval($this->Settings->postal_code),
			"siglaUF" => strval($this->Settings->estado),
			"codigoUF" => strval($this->Settings->codigoUF),
			"fone" => strval($tel),
			"tokenIBPT" => strval($this->Settings->tokenIBPT),
			"CSC" => strval($this->Settings->CSC),
			"CSCid" => strval($this->Settings->CSCid),
			"certificado" => strval($this->Settings->certificado),
			"certificadosenha" => strval($this->Settings->certificadosenha)
		);

		$data_nfe["emissao"] = ($v->nf_status=="contingencia") ? "conti" : "processa";
		$data_nfe["ID"] = $v->id; 
		$data_nfe["chave"] = $v->nf_chave; 
		$data_nfe["nfe"] = $v->nf_numero; 
		$data_nfe["recibo"] = $v->nf_danfe;
		$data_nfe["endpoint"] = $endpoint;
		$data_nfe["modelo"] = $v->nf_modelo;

          $fields_string = http_build_query($data_nfe);
          $ch = curl_init();
          curl_setopt($ch,CURLOPT_URL, $endpoint."gerador/Emissor.php");
          curl_setopt($ch,CURLOPT_POST, count($data_nfe, COUNT_RECURSIVE));
          curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $response_server = curl_exec($ch);
          $response = json_decode($response_server);
          curl_close($ch);
      
     
      if (isset($response->error)){
       
        $erross[$v->nf_numero] = $response->error;
			//echo '<h2>Erro: '.$response->error.'</h2>';
			if (isset($response->log)){
			  echo '<h3>Log:</h3>';
			  echo '<ul>';
			  foreach ($response->log as $erros){
				foreach ($erros as $erro) {
				  echo '<li>'.$erro.'</li>';
				}
			  }
			  echo '</ul>';
			}
			
			if($response->lote == 104){

				if($v->nf_modelo=="65"){
					// fazer upload dos dados
					$this->pos_model->UpdateDadosNFC($v->id, "reprovado", $v->nf_numero, $v->nf_chave, $v->nf_danfe, "", 65);
					$this->pos_model->UpdateDadosNFbyNumero("reprovado", $v->nf_numero, $v->nf_chave, $v->nf_danfe, "", 65);
				}else{
					// fazer upload dos dados
					$this->pos_model->UpdateDadosNF($v->id, "reprovado", $v->nf_numero, $v->nf_chave, $v->nf_danfe, "", 55);
				}
			 
			
			}

      }elseif(!$response){

        echo '<h2>Erro no servidor ao emitir</h2>';
		var_dump($response);

      } else {

        $status = (string) $response->status; // aprovado, reprovado, cancelado, processamento ou contingencia
        $nfe = (int) $response->nfe; // número da NF-e
        $serie = (int) $response->serie; // número de série
        $recibo = (int) $response->recibo; // número do recibo
        $chave = $response->chave; // número da chave de acesso
        $xml = (string) $response->xml; // URL do XML
        
        if($status=="processamento" || $status=="em processamento"){
			$danfe = $recibo;
		}
        
		if($v->nf_modelo=="65"){
			// fazer upload dos dados
			$this->pos_model->UpdateDadosNFC($v->id, $status, $nfe, $chave, $danfe, $xml, 65);
			$this->pos_model->UpdateDadosNFbyNumero($status, $nfe, $chave, $danfe, $xml, 65);
		}else{
			// fazer upload dos dados
			$this->pos_model->UpdateDadosNF($v->id, $status, $nfe, $chave, $danfe, $xml, 55);
		}

        if($status=="aprovado"){
			echo "<script>window.open('".$endpoint ."danfe/index.php?chave=".$v->nf_chave."&logo=".$this->Settings->logo."&tamanhopapel=".$this->Settings->tamanhopapel."', '_blank');</script>";
		}

      }
       
    }

    if(!empty($erross)) foreach($erross as $key => $ers) echo "Número: ". $key . " | Erro: ".$ers."<br>";


	if($countcont==0){
		echo '<h2>Não há notas fiscais em contingência para serem enviadas</h2>';
		die;
	}

  }

  function nfe_validarcertificado() {
    
	$erross = array();
	
	$senha = $this->input->get('pass');

		$endpoint = APINFE_URL_BASE;

		function limparString($str){
			if($str){
				return str_replace(array(".", "/", "-", " ", "(", ")"), "", $str);
			}else{
				return "";
			}
		}

		$vat_no = limparString( $this->Settings->vat_no);
		$ie = limparString( $this->Settings->ie);
		$im = limparString( $this->Settings->im);
		$tel = limparString( $this->Settings->phone_number);

		$data_nfe = array(
			"tpAmb" => strval($this->Settings->tpAmb),
			"razaosocial" => strval($this->Settings->razaosocial),
			"cnpj" => strval($vat_no),
			"fantasia" => strval($this->Settings->fantasia),
			"ie" => strval($ie),
			"im" => strval($im),
			//"cnae" => strval($this->Settings->cnae),
			"crt" => strval($this->Settings->crt),
			"rua" => strval($this->Settings->address),
			"numero" => strval($this->Settings->numero),
			"bairro" => strval($this->Settings->bairro),
			"cidade" => strval($this->Settings->city),
			"ccidade" => strval($this->Settings->ccidade),
			"cep" => strval($this->Settings->postal_code),
			"siglaUF" => strval($this->Settings->estado),
			"codigoUF" => strval($this->Settings->codigoUF),
			"fone" => strval($tel),
			"tokenIBPT" => strval($this->Settings->tokenIBPT),
			"CSC" => strval($this->Settings->CSC),
			"CSCid" => strval($this->Settings->CSCid),
			"certificado" => strval($this->Settings->certificado),
			"certificadosenha" => ($senha)? $senha : strval($this->Settings->certificadosenha)
		);
      
		  $fields_string = http_build_query($data_nfe);
          $ch = curl_init();
          curl_setopt($ch,CURLOPT_URL, $endpoint."gerador/ValidarCertificado.php");
          curl_setopt($ch,CURLOPT_POST, count($data_nfe, COUNT_RECURSIVE));
          curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  $response_server = curl_exec($ch);
		  echo $response_server;
          //$response = json_decode($response_server);
          curl_close($ch);
      
      /*if (isset($response->error)){
    	echo '<h2>'.$response->error.'</h2>';
      }elseif(!$response){
        echo '<h2>Erro no servidor ao emitir</h2>';
		var_dump($response);
      } else {
        $validade = (int) $response->validade; 
	}*/

  }
  
	
	function nfe_error() {
			echo $this->input->get('dados');
	}

	function get_product($code = NULL) {

		if($this->input->get('code')) { $code = $this->input->get('code'); }
		$combo_items = FALSE;
		if($product = $this->pos_model->getProductByCode($code)) {
			unset($product->cost, $product->details);
			$product->qty = 1;
			$product->discount = '0';
			$product->real_unit_price = $product->price;
			$product->unit_price = $product->tax ? ($product->price+(($product->price*$product->tax)/100)) : $product->price;
			if ($product->type == 'combo') {
				$combo_items = $this->pos_model->getComboItemsByPID($product->id);
			}
			echo json_encode(array('id' => str_replace(".", "", microtime(true)), 'item_id' => $product->id, 'label' => $product->name . " (" . $product->code . ")", 'row' => $product, 'combo_items' => $combo_items));
		} else {
			echo NULL;
		}

	}

	function suggestions()
	{
		$term = $this->input->get('term', TRUE);
		$isCodigoBalanca = false;

		// leitor de codigos de barra
		// tipo = 1 - peso
		// tipo = 2 - valor
		if($this->Settings->balanca_tipodado!=0 &&
		!empty($this->Settings->balanca_tipodado) && 
		!empty($this->Settings->balanca_digitosiniciais) &&
		!empty($this->Settings->balanca_posicaopesovalor) &&
		!empty($this->Settings->balanca_tamanhoinfopesovalor)){
			// checamos si é um codigo de balança valido

			$inicial = substr($term, 0, 1); 
			//echo "INICIAL: $inicial<br>";

			if($inicial == $this->Settings->balanca_digitosiniciais){
				$isCodigoBalanca = true;
				$pesoOuValor = substr($term, ($this->Settings->balanca_posicaopesovalor-1), $this->Settings->balanca_tamanhoinfopesovalor); 
				//echo "pesoOuValor: $pesoOuValor<br>";

				if($this->Settings->balanca_tipodado == 1){
					$peso = number_format((float)($pesoOuValor/100), 3, '.', '');
					//echo "PESO: $peso<br>";
				}else{
					$valor = number_format((float)$this->tec->formatDecimal($pesoOuValor/100), 2, '.', '');
					//echo "VALOR: $valor<br>";
				}
				
				$term = substr($term, 0, ($this->Settings->balanca_posicaopesovalor -1));
				$term = ltrim($term, '0');
				//echo "codigoproduto: $term<br>";
			}
		}
		// codigo da balança


		$rows = $this->pos_model->getProductNames($term);
		if ($rows) {
			foreach ($rows as $row) {
				unset($row->cost, $row->details);
				
				$row->qty = 1;
				$row->discount = '0';
				$row->real_unit_price = $row->price;
				$row->unit_price = $row->tax ? ($row->price+(($row->price*$row->tax)/100)) : $row->price;

				if($isCodigoBalanca==true){
					if(empty($peso)){
						$row->qty = number_format((float)($valor / $row->real_unit_price), 3, '.', '');
					}else{
						$row->qty = $peso;
					}
					
				}

				$combo_items = FALSE;
				if ($row->type == 'combo') {
				    $combo_items = $this->pos_model->getComboItemsByPID($row->id);
				}
				$pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items);
			}
			echo json_encode($pr);
		} else {
			echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
		}
	}


	function registers()
	{
		if($this->session->userdata('acesso_nfc') != 1) {
			redirect("upgrade");
		}

		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['registers'] = $this->pos_model->getOpenRegisters();
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('pos'), 'page' => lang('pos')), array('link' => '#', 'page' => lang('open_registers')));
		$meta = array('page_title' => lang('open_registers'), 'bc' => $bc);
		$this->page_construct('pos/registers', $this->data, $meta);
	}

	function open_register()
	{
		if($this->session->userdata('acesso_nfc') != 1) {
			redirect("upgrade");
		}
		
		$this->form_validation->set_rules('cash_in_hand', lang("cash_in_hand"), 'trim|required');

		if ($this->form_validation->run() == true) {
			$data = array('date' => date('Y-m-d H:i:s'),
				'cash_in_hand' => str_replace(",", ".", str_replace(".", "", $this->input->post('cash_in_hand'))),
				'user_id' => $this->session->userdata('user_id'),
				'status' => 'open',
				);
		}
		if ($this->form_validation->run() == true && $this->pos_model->openRegister($data)) {
			$this->session->set_flashdata('message', lang("welcome_to_pos"));
			redirect("pos");
		} else {

			$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('open_register')));
			$meta = array('page_title' => lang('open_register'), 'bc' => $bc);
			$this->page_construct('pos/open_register', $this->data, $meta);
		}
	}


	function acc_operation_register()
	{
		if($this->session->userdata('acesso_nfc') != 1) {
			redirect("upgrade");
		}
		
		/*if(md5($this->input->post('senhaoperador')) != $this->Settings->pin_code){
			$this->session->set_flashdata('error', "Senha do operador incorreta, tente novamente.");
			redirect("pos");
		}*/

		$this->form_validation->set_rules('valor', "Digite o valor da operação", 'trim|required');

		if ($this->form_validation->run() == true) {
			$data = array(
				'date' => date('d/m/Y H:i'),
				'valor' => str_replace(",", ".", str_replace(".", "", $this->input->post('valor'))),
				'user_id' => $this->session->userdata('user_id'),
				'tipo' => $this->input->post('tipo'),
				'informacao' => $this->input->post('informacao'),
				);
		}

		$rid = $this->session->userdata('register_id');
		$user_id = $this->session->userdata('user_id');

		if ($this->form_validation->run() == true && $this->pos_model->operateRegister($rid, $user_id, $data)) {

			$this->session->set_flashdata('message', "Operação realizada com sucesso!");
			redirect("pos");
		} else {

			$this->session->set_flashdata('error', "Erro ao realiuzar operação, tente novamente");
			redirect("pos");

		}
	}

	function close_register($user_id = NULL)
	{
		if (!$this->Admin) {
			$user_id = $this->session->userdata('user_id');
		}
		$this->form_validation->set_rules('total_cash', lang("total_cash"), 'trim|required');
		$this->form_validation->set_rules('total_cheques', lang("total_cheques"), 'trim|required');
		$this->form_validation->set_rules('total_cc_slips', lang("total_cc_slips"), 'trim|required');

		if ($this->form_validation->run() == true) {
			if ($this->Admin) {
				$user_register = $user_id ? $this->pos_model->registerData($user_id) : NULL;
				$rid = $user_register ? $user_register->id : $this->session->userdata('register_id');
				$user_id = $user_register ? $user_register->user_id : $this->session->userdata('user_id');
			} else {
				$rid = $this->session->userdata('register_id');
				$user_id = $this->session->userdata('user_id');
			}

			$this->data['register_details'] = $this->pos_model->registerData($this->session->userdata('user_id'));

			$notes = "";
			$notes .= "Dinheiro ao abrir caixa: ".$this->tec->formatMoney($this->input->post('total_cash_open_caixa'))."<br>";
			$notes .= "Vendas em dinheiro: ".$this->tec->formatMoney($this->input->post('total_vendas_cash'))."<br>";
			$notes .= "Vendas em Crédito: ".$this->tec->formatMoney($this->input->post('total_vendas_cc'))."<br>";
			$notes .= "Vendas em Débito: ".$this->tec->formatMoney($this->input->post('total_vendas_stripe'))."<br>";
			$notes .= "Vendas em Pix: ".$this->tec->formatMoney($this->input->post('total_vendas_pix'))."<br>";
			if($this->input->post('total_vendas_other')>0) { $notes .= "Vendas em Outros: ".$this->tec->formatMoney($this->input->post('total_vendas_other'))."<br>";}
			if($this->input->post('total_vendas_boleto')>0) { $notes .= "Vendas em Boleto: ".$this->tec->formatMoney($this->input->post('total_vendas_boleto'))."<br>";}
			if($this->input->post('total_vendas_transf')>0) { $notes .= "Vendas em Transferência: ".$this->tec->formatMoney($this->input->post('total_vendas_transf'))."<br>";}
			if($this->input->post('total_vendas_fiado')>0) { $notes .= "Vendas em Fiado: ".$this->tec->formatMoney($this->input->post('total_vendas_fiado'))."<br>";}
			//if($this->input->post('total_cheques_submitted')>0) { $notes .= "Vendas em Transferência: ".$this->input->post('total_cheques_submitted')."<br>";}
			$notes .= "<hr><br>Total de vendas: ".$this->tec->formatMoney($this->input->post('total_vendas'))."<br><hr><br>";
		
			if($this->input->post('total_reforco')>0) { $notes .= "Reforços de caixa (+): ".$this->tec->formatMoney($this->input->post('total_reforco'))."<br>";}
			if($this->input->post('total_sangrias')>0) { $notes .= "Sangrias de caixa (-): ".$this->tec->formatMoney($this->input->post('total_sangrias'))."<br>";}
		
			if(!empty($this->data['register_details']->note_sangrias)){ 
				$notas_ref = json_decode($this->data['register_details']->note_sangrias, true);
				foreach($notas_ref as $n){
					echo '--> '.$n.'<br>';
				}
			} 

			$notes .= "<hr><br>Total em dinheiro: ".$this->tec->formatMoney($this->input->post('total_cash'))."<br>";
			$notes .= "Dinheiro (Informado): ".$this->tec->formatMoney($this->input->post('total_cash_submitted'))."<br>";
			$notes .= "Diferença: ". $this->tec->formatMoney(($this->input->post('total_cash_submitted') - $this->input->post('total_cash')))."<br>";

			if($this->input->post('note')>0) { $notes .= "<hr>Info: <br>".$this->input->post('note'); }
			
			$data = array('closed_at' => date('Y-m-d H:i:s'),
				'total_cash' => $this->input->post('total_cash'),
				//'total_cheques' => str_replace(",", ".", str_replace(".", "", $this->input->post('total_cheques'))),
				//'total_cc_slips' => str_replace(",", ".", str_replace(".", "", $this->input->post('total_cc_slips'))),
				'total_cash_submitted' => str_replace(",", ".", str_replace(".", "", $this->input->post('total_cash_submitted'))),
				//'total_cheques_submitted' => str_replace(",", ".", str_replace(".", "", $this->input->post('total_cheques_submitted'))),
				//'total_cc_slips_submitted' => str_replace(",", ".", str_replace(".", "", $this->input->post('total_cc_slips_submitted'))),
				'status' => 'close',
				'note' => $notes,
				'transfer_opened_bills' => $this->input->post('transfer_opened_bills'),
				'closed_by' => $this->session->userdata('user_id'),
			);

		} elseif ($this->input->post('close_register')) {
			$this->session->set_flashdata('error', (validation_errors() ? validation_errors() : $this->session->flashdata('error')));
			redirect("pos");
		}

		if ($this->form_validation->run() == true && $this->pos_model->closeRegister($rid, $user_id, $data)) {
			$this->session->set_flashdata('message', lang("register_closed"));
			redirect("welcome");
		} else {
			if ($this->Admin) {
				$user_register = $user_id ? $this->pos_model->registerData($user_id) : NULL;
				$register_open_time = $user_register ? $user_register->date : $this->session->userdata('register_open_time');
				$this->data['cash_in_hand'] = $user_register ? $user_register->cash_in_hand : NULL;
				$this->data['register_open_time'] = $user_register ? $register_open_time : NULL;
			} else {
				$register_open_time = $this->session->userdata('register_open_time');
				$this->data['cash_in_hand'] = NULL;
				$this->data['register_open_time'] = NULL;
			}
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['ccsales'] = $this->pos_model->getRegisterCCSales($register_open_time, $user_id);
			$this->data['cashsales'] = $this->pos_model->getRegisterCashSales($register_open_time, $user_id);
			$this->data['pixsales'] = $this->pos_model->getRegisterPixSales($register_open_time, $user_id);
			$this->data['chsales'] = $this->pos_model->getRegisterChSales($register_open_time, $user_id);
			$this->data['stripesales'] = $this->pos_model->getRegisterStripeSales($register_open_time, $user_id);
			$this->data['boletosales'] = $this->pos_model->getRegisterBoletoSales($register_open_time, $user_id);
			$this->data['fiadosales'] = $this->pos_model->getRegisterFiadoSales($register_open_time, $user_id);
			$this->data['transfsales'] = $this->pos_model->getRegisterTransfSales($register_open_time, $user_id);
			$this->data['outrosales'] = $this->pos_model->getRegisterOtherSales($register_open_time, $user_id);
			
			$this->data['totalsales'] = $this->pos_model->getRegisterSales($register_open_time, $user_id);
			$this->data['expenses'] = $this->pos_model->getRegisterExpenses($register_open_time);
			$this->data['users'] = $this->tec->getUsers($user_id);
			$this->data['suspended_bills'] = $this->pos_model->getSuspendedsales($user_id);
			$this->data['user_id'] = $user_id;
			$this->data['register_details'] = $this->pos_model->registerData($this->session->userdata('user_id'));
			$this->load->view($this->theme . 'pos/close_register', $this->data);
		}
	}

	function ajaxproducts( $category_id = NULL, $return = NULL) {

		if($this->input->get('category_id')) { $category_id = $this->input->get('category_id'); } elseif(!$category_id) { $category_id = $this->Settings->default_category; }
		if($this->input->get('per_page') == 'n' ) { $page = 0; } else { $page = $this->input->get('per_page'); }
		if($this->input->get('tcp') == 1 ) { $tcp = TRUE; } else { $tcp = FALSE; }

		$products = $this->pos_model->fetch_products($category_id, $this->Settings->pro_limit, $page);
		$pro = 1;
		$prods = "<div>";
		if($products) {
			if($this->Settings->bsty == 1) {
				foreach($products as $product) {
					$count = $product->id;
					if($count < 10) { $count = "0".($count /100) *100;  }
					if($category_id < 10) { $category_id = "0".($category_id /100) *100;  }
					$prods .= "<button type=\"button\" data-name=\"".$product->name."\" id=\"product-".$category_id.$count."\" type=\"button\" value='".$product->code."' class=\"btn btn-name btn-default btn-flat product\">".$product->name."</button>";
					$pro++;
				}
			} elseif($this->Settings->bsty == 2) {
				foreach($products as $product) {
					$count = $product->id;
					if($count < 10) { $count = "0".($count /100) *100;  }
					if($category_id < 10) { $category_id = "0".($category_id /100) *100;  }
					$prods .= "<button type=\"button\" data-name=\"".$product->name."\" id=\"product-".$category_id.$count."\" type=\"button\" value='".$product->code."' class=\"btn btn-img btn-flat product\"><img src=\"".base_url()."uploads/thumbs/".$product->image."\" alt=\"".$product->name."\" style=\"width: 110px; height: 110px;\"></button>";
					$pro++;
				}
			} elseif($this->Settings->bsty == 3) {
				foreach($products as $product) {
					$count = $product->id;
					if($count < 10) { $count = "0".($count /100) *100;  }
					if($category_id < 10) { $category_id = "0".($category_id /100) *100;  }
					$prods .= "<button type=\"button\" data-name=\"".$product->name."\" id=\"product-".$category_id.$count."\" type=\"button\" value='".$product->code."' class=\"btn btn-both btn-flat product\"><span class=\"bg-img\"><img src=\"".base_url()."uploads/thumbs/".$product->image."\" alt=\"".$product->name."\" style=\"width: 100px; height: 100px;\"></span><span><span>".$product->name."</span></span></button>";
					$pro++;
				}
			}
		} else {
			$prods .= '<h4 class="text-center text-info" style="margin-top:50px;">'.lang('category_is_empty').'</h4>';
		}

		$prods .= "</div>";

		if(!$return) {
			if(!$tcp) {
				echo $prods;
			} else {
				$category_products = $this->pos_model->products_count($category_id);
				header('Content-Type: application/json');
				echo json_encode(array('products' => $prods, 'tcp' => $category_products));
			}
		} else {
			return $prods;
		}

	}

	function view($sale_id = NULL, $noprint = NULL)
	{
	    
		if($this->input->get('id')){ $sale_id = $this->input->get('id'); }
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['message'] = $this->session->flashdata('message');
		$inv = $this->pos_model->getSaleByID($sale_id);
		//$this->tec->view_rights($inv->created_by);
		$this->load->helper('text');
		$this->data['rows'] = $this->pos_model->getAllSaleItems($sale_id);
		$this->data['customer'] = $this->pos_model->getCustomerByID($inv->customer_id);
		$this->data['inv'] = $inv;
		$this->data['sid'] = $sale_id;
		$this->data['noprint'] = $noprint;
		$this->data['modal'] = false;
		$this->data['payments'] = $this->pos_model->getAllSalePayments($sale_id);
		$this->data['meiopagamento'] = $this->site->getAllmeiopagamento();
		$this->data['created_by'] = $this->site->getUser($inv->created_by);
		if($inv->vendedor!=""){
			$this->data['created_by'] = $this->site->getUser($inv->vendedor);
		}
	
		$this->data['page_title'] = lang("invoice");
		$this->load->view($this->theme.'pos/view', $this->data);

	}

	function posvendanfc($sale_id = NULL, $noprint = NULL)
	{
		if($this->input->get('id')){ $sale_id = $this->input->get('id'); }
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['message'] = $this->session->flashdata('message');
		$inv = $this->pos_model->getSaleByID($sale_id);
		$this->load->helper('text');
		$this->data['inv'] = $inv;
		$this->data['sid'] = $sale_id;
		$this->data['noprint'] = $noprint;
		$this->data['modal'] = false;
		$this->data['page_title'] = lang("invoice");
		$this->load->view($this->theme.'pos/posvendanfc', $this->data);

	}

	function email_receipt($sale_id = NULL, $to = NULL)
	{
		if($this->input->post('id')) { $sale_id = $this->input->post('id'); }
		if($this->input->post('email')){ $to = $this->input->post('email'); }
		if(!$sale_id || !$to) { die(); }

		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['message'] = $this->session->flashdata('message');
		$inv = $this->pos_model->getSaleByID($sale_id);
		$this->tec->view_rights($inv->created_by);
		$this->load->helper('text');
		$this->data['rows'] = $this->pos_model->getAllSaleItems($sale_id);
		$this->data['customer'] = $this->pos_model->getCustomerByID($inv->customer_id);
		$this->data['inv'] = $inv;
		$this->data['sid'] = $sale_id;
		$this->data['noprint'] = NULL;
		$this->data['modal'] = false;
		$this->data['payments'] = $this->pos_model->getAllSalePayments($sale_id);
		$this->data['created_by'] = $this->site->getUser($inv->created_by);

		$receipt = $this->load->view($this->theme.'pos/view', $this->data, TRUE);
		$subject = lang('email_subject');

		if($this->tec->send_email($to, $subject, $receipt)) {
			echo json_encode(array('msg' => lang("email_success")));
		} else {
			echo json_encode(array('msg' => lang("email_failed")));
		}

	}


	function register_details()
	{

		$register_open_time = $this->session->userdata('register_open_time');
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['ccsales'] = $this->pos_model->getRegisterCCSales($register_open_time);
		$this->data['cashsales'] = $this->pos_model->getRegisterCashSales($register_open_time);
		$this->data['pixsales'] = $this->pos_model->getRegisterPixSales($register_open_time);
		$this->data['chsales'] = $this->pos_model->getRegisterChSales($register_open_time);
		$this->data['meiopagamento'] = $this->site->getAllmeiopagamento();
		$this->data['stripesales'] = $this->pos_model->getRegisterStripeSales($register_open_time);
		$this->data['totalsales'] = $this->pos_model->getRegisterSales($register_open_time);
		$this->data['expenses'] = $this->pos_model->getRegisterExpenses($register_open_time);
		$this->data['register_details'] = $this->pos_model->registerData($this->session->userdata('user_id'));
		$this->load->view($this->theme . 'pos/register_details', $this->data);
	}

	function today_sale()
	{
		if (!$this->Admin) {
			$this->session->set_flashdata('error', lang('access_denied'));
			redirect($_SERVER["HTTP_REFERER"]);
		}

		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['ccsales'] = $this->pos_model->getTodayCCSales();
		$this->data['cashsales'] = $this->pos_model->getTodayCashSales();
		$this->data['chsales'] = $this->pos_model->getTodayChSales();
		$this->data['pixsales'] = $this->pos_model->getTodayPIXSales();
		$this->data['meiopagamento'] = $this->site->getAllmeiopagamento();
		$this->data['stripesales'] = $this->pos_model->getTodayStripeSales();
		$this->data['totalsales'] = $this->pos_model->getTodaySales();
		// $this->data['expenses'] = $this->pos_model->getTodayExpenses();
		$this->load->view($this->theme . 'pos/today_sale', $this->data);
	}

	function shortcuts()
	{
		$this->load->view($this->theme . 'pos/shortcuts', $this->data);
	}

	function view_bill()
    {
		if( $this->input->get('hold') ) { $sid = $this->input->get('hold'); }

		if(isset($sid) && !empty($sid)) {
			$suspended_sale = $this->pos_model->getSuspendedSaleByID($sid);
			$inv_items = $this->pos_model->getSuspendedSaleItems($sid);
			krsort($inv_items);
			$c = rand(100000, 9999999);
			foreach ($inv_items as $item) {
				$row = $this->site->getProductByID($item->product_id);
				if (!$row) {
					$row = json_decode('{}');
				}
				$row->price = $item->net_unit_price+($item->item_discount/$item->quantity);
				$row->unit_price = $item->unit_price+($item->item_discount/$item->quantity)+($item->item_tax/$item->quantity);
				$row->real_unit_price = $item->real_unit_price;
				$row->discount = $item->discount;
				$row->qty = $item->quantity;
				$row->comment = str_replace('"', '', $item->note);
				$row->name = str_replace('"', '\"', $row->name);
				$row->is_new = $item->is_new;
				$combo_items = FALSE;
				$ri = $this->Settings->item_addition ? $row->id : $c;
				$pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items);
				$c++;
			}

			$this->data['items'] = json_encode( $pr,JSON_UNESCAPED_UNICODE); 
			$this->data['sid'] = $sid;
			$this->data['suspend_sale'] = $suspended_sale;
		}


        $this->load->view($this->theme . 'pos/view_bill', $this->data);
    }

	function view_sale_display()
    {

        $this->load->view($this->theme . 'pos/view_sale_display', $this->data);
    }

    function promotions()
    {
        $this->load->view($this->theme . 'promotions', $this->data);
    }

    function stripe_balance()
    {
        if (!$this->Owner) {
            return FALSE;
        }
    }

	function language($lang = false)
    {
        if ($this->input->get('lang')) {
            $lang = $this->input->get('lang');
        }
        //$this->load->helper('cookie');
        $folder = 'app/language/';
        $languagefiles = scandir($folder);
        if (in_array($lang, $languagefiles)) {
            $cookie = array(
                'name' => 'language',
                'value' => $lang,
                'expire' => '31536000',
                'prefix' => 'spos_',
                'secure' => false
            );

            $this->input->set_cookie($cookie);
        }
        redirect($_SERVER["HTTP_REFERER"]);
    }

    function validate_gift_card($no)
    {
        if ($gc = $this->pos_model->getGiftCardByNO(urldecode($no))) {
            if ($gc->expiry) {
                if ($gc->expiry >= date('Y-m-d')) {
                    echo json_encode($gc);
                } else {
                    echo json_encode(false);
                }
            } else {
                echo json_encode($gc);
            }
        } else {
            echo json_encode(false);
        }
    }


	function gerar_pix(){
		
		$valor = $this->input->get('valor');

		function limparString($str){
			if($str){
				return str_replace(array(".", "/", "-", " ", "(", ")"), "", $str);
			}else{
				return "";
			}
		}

		function tirarAcentos($string){
			return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
		}

		// via settings
		if(empty($this->Settings->pagamento_pix)){
			echo json_encode(array("status" => false));
		}

		$pix = json_decode($this->Settings->pagamento_pix);

		$tipo_chave_pix = $pix->pagamento_pix_tipochave;
		$chave_pix = $pix->pagamento_pix_chave;
		$beneficiario_pix = $pix->pagamento_pix_beneficiario;
		$cidade_pix = $pix->pagamento_pix_cidade;   

		if ($tipo_chave_pix!="" && !empty($chave_pix) && !empty($beneficiario_pix) && !empty($cidade_pix)) {
			
			$chave_pix = strtolower($chave_pix);
			if($tipo_chave_pix!="aleatorio"){
				$chave_pix = limparString($chave_pix);
			}
	
			if($tipo_chave_pix=="celular"){
				$chave_pix = "+55".$chave_pix; // "+55".
			}
	
			$beneficiario_pix = tirarAcentos($beneficiario_pix);
			if (strlen($beneficiario_pix) > 25) {
				$beneficiario_pix= substr($beneficiario_pix,0,25);
			}
			
			$cidade_pix = tirarAcentos($cidade_pix);
			if (strlen($cidade_pix) > 15) {
				$cidade_pix=substr($cidade_pix,0,15);
			}
	
			$identificador="***";

			$valor_pix = $valor;
	
			if(is_numeric($valor_pix)){
				$valor_pix=preg_replace("/[^0-9.]/","",$valor_pix);
			}
			else {
				$valor_pix="0.00";
			}

			$gerar_qrcode=true;
	
		} else {
			$gerar_qrcode=false;
		}
			
		if (!$gerar_qrcode){
	
			$err = "";
			if($tipo_chave_pix==""){ $err .= "- Selecione um tipo de chave<br>"; }
			if($chave_pix==""){ $err .= "- A chave PIX vazia<br>"; }
			if($beneficiario_pix==""){ $err .= "- O nome do beneficiário está vazio<br>"; }
			if($cidade_pix==""){ $err .= "- A cidade do beneficiário está vazia<br>"; }
			
			echo json_encode(array("status" => false, "err" =>  $err));
			die;
			
		}else{ 
		

			// includes espefificos
			include(__dir__."/../../lib-local/php_qrcode_pix/phpqrcode/qrlib.php"); 
			include(__dir__."/../../lib-local/php_qrcode_pix/funcoes_pix.php");
	
			// pode gerar
			try {
		
				$px[00]="01"; //Payload Format Indicator, Obrigatório, valor fixo: 01
				// Se o QR Code for para pagamento único (só puder ser utilizado uma vez), descomente a linha a seguir.
				//$px[01]="12"; //Se o valor 12 estiver presente, significa que o BR Code só pode ser utilizado uma vez. 
				$px[26][00]="br.gov.bcb.pix"; //Indica arranjo específico; “00” (GUI) obrigatório e valor fixo: br.gov.bcb.pix
				$px[26][01]=$chave_pix;
				if (!empty($descricao)) {
				/* 
				Não é possível que a chave pix e infoAdicionais cheguem simultaneamente a seus tamanhos máximos potenciais.
				Conforme página 15 do Anexo I - Padrões para Iniciação do PIX  versão 1.2.006.
				*/
				$tam_max_descr=99-(4+4+4+14+strlen($chave_pix));
				if (strlen($descricao) > $tam_max_descr) {
					$descricao=substr($descricao,0,$tam_max_descr);
				}
				$px[26][02]=$descricao;
				}
				$px[52]="0000"; //Merchant Category Code “0000” ou MCC ISO18245
				$px[53]="986"; //Moeda, “986” = BRL: real brasileiro - ISO4217
				if ($valor_pix > 0) {
					// Na versão 1.2.006 do Anexo I - Padrões para Iniciação do PIX estabelece o campo valor (54) como um campo opcional.
					$px[54]=$valor_pix;
				}
				$px[58]="BR"; //“BR” – Código de país ISO3166-1 alpha 2
				$px[59]=$beneficiario_pix; //Nome do beneficiário/recebedor. Máximo: 25 caracteres.
				$px[60]=$cidade_pix; //Nome cidade onde é efetuada a transação. Máximo 15 caracteres.
				$px[62][05]=$identificador;
			//   $px[62][50][00]="BR.GOV.BCB.BRCODE"; //Payment system specific template - GUI
			//   $px[62][50][01]="1.2.006"; //Payment system specific template - versão
				$pix=montaPix($px);
				
				$pix.="6304"; //Adiciona o campo do CRC no fim da linha do pix.
				$pix.=crcChecksum($pix); //Calcula o checksum CRC16 e acrescenta ao final.
							
				ob_start();
				QRCode::png($pix, null,'M',5);
				$imageString = base64_encode( ob_get_contents() );
				ob_end_clean();
				
				// Exibe a imagem diretamente no navegador codificada em base64.
				echo json_encode(array("status" => true, "pix" => $pix,  "qrcode" => "data:image/png;base64," . $imageString));
				die;
			} catch (\Exception $th) {
				//throw $th;
				error_log($th->getMessage());
	
				echo json_encode(array("status" => false, "err" => "genqr"));
				die;
	
			}
		}

	}



}