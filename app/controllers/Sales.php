<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales extends MY_Controller {

	function __construct()
	{
		parent::__construct();

		if (!$this->loggedIn) {
			redirect('login');
		}
		$this->load->library('form_validation');
		$this->load->model('sales_model');
        $this->load->model('reports_model');
         $this->load->model('pos_model');

		$this->digital_file_types = 'zip|pdf|doc|docx|xls|xlsx|jpg|png|gif';

	}

	function index()
	{
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['page_title'] = lang('sales');
        $this->data['isAdmin'] = $this->Admin;

        $this->data['customers'] = $this->reports_model->getAllCustomers();
        $this->data['users'] = $this->reports_model->getAllStaff();

		$bc = array(array('link' => '#', 'page' => lang('sales')));
		$meta = array('page_title' => lang('sales'), 'bc' => $bc);
		$this->page_construct('sales/index', $this->data, $meta);
    }
    
    function comissoes()
	{
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['page_title'] = "Comissões";
        $this->data['isAdmin'] = $this->Admin;
        $this->data['users'] = $this->reports_model->getAllStaff();

		$bc = array(array('link' => site_url('sales'), 'page' => lang('sales')),array('link' => '#', 'page' => "Comissões"));
		$meta = array('page_title' => "Comissões", 'bc' => $bc);
		$this->page_construct('sales/comissoes', $this->data, $meta);
    }

    function notasfiscais()
	{
        if($this->session->userdata('acesso_nf') != 1 ) {
            redirect('upgrade');
        }

		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['page_title'] = "Notas Fiscais";
		$bc = array(array('link' => '#', 'page' => "Notas Fiscais"));
		$meta = array('page_title' => "Notas Fiscais", 'bc' => $bc);
		$this->page_construct('sales/notasfiscais', $this->data, $meta);
    }
    
    function creador_notas()
	{

        $this->load->library('datatables');

        if($this->session->userdata('acesso_nf') != 1 ) {
            redirect('upgrade');
        }
        
        if($this->session->userdata('limite_nf') <= $this->site->getTotalNF()) {
           redirect('upgrade');
        }

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = "Notas Fiscais";
        $this->data["proxima_nf"] = $this->Settings->ultima_nf;
		$bc = array(array('link' => '#', 'page' => "Notas Fiscais"));
		$meta = array('page_title' => "Notas Fiscais", 'bc' => $bc);
		$this->page_construct('sales/creador_notas', $this->data, $meta);

	}
	
	function get_sales()
	{

		$this->load->library('datatables');
        $this->datatables->select("id, date, customer_name, total, total_tax, total_discount, grand_total, (paid - troco) as paidtotal, status");
        $this->datatables->from('sales');

        $customer = $this->input->get('customer') ? $this->input->get('customer') : NULL;
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;
        $user = $this->input->get('user') ? $this->input->get('user') : NULL;
        $ispaid = $this->input->get('ispaid') ? $this->input->get('ispaid') : NULL;
        //$paid_by = $this->input->get('paid_by') ? $this->input->get('paid_by') : NULL;
        //$vendedor = $this->input->get('vendedor') ? $this->input->get('vendedor') : NULL;
        // filtros
        if($customer!=0) { $this->datatables->where('customer_id', $customer); }
        if($start_date!="") { $this->datatables->where('date >=', $this->tec->FormatarDataTimeBRParaDB($start_date));  } // formatar data
        if($end_date!="") { $this->datatables->where('date <=', $this->tec->FormatarDataTimeBRParaDB($end_date)); } // formatar data
        // 1- so pagos, 2 - só não pagos
        if($ispaid == "1") {  $this->datatables->where('status', "Pago"); } 
        if($ispaid == "2") {  $this->datatables->where('status !=', "Pago"); } 
        if($ispaid == "3") {  $this->datatables->where('status', "Parcial"); } 
        if($ispaid == "4") {  $this->datatables->where('status', "Não pago"); } 
        if(!$this->Admin) {
            $user_id = $this->session->userdata('user_id');
            $this->datatables->where('created_by', $user_id);
        }else{
            if($user!=0){ 
                $this->datatables->where('created_by', $user); 
                $this->datatables->or_where('vendedor', $user);
            }
        }

        $this->datatables->add_column("Actions", "<div class='text-center'><div class='btn-group' style='width:140px'>
        <a data-fancybox data-type='iframe' href='" . site_url('pos/view/$1/1') . "?from=sales_list' title='".lang("view_invoice")."' class='tip btn btn-primary btn-xs'><i class='fa fa-list'></i></a> 
        <a href='javascript:void(0)' onclick='window.open(\"" . site_url('pos/nfe/$1')."\", \"nfe\", \"width=800,height=800\")' title='Nota Fiscal' class='tip btn btn-success btn-xs'><i class='fa fa-barcode'></i></a> 
        <a href='".site_url('sales/payments/$1')."' title='" . lang("view_payments") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'><i class='fa fa-money'></i></a> 
        <a href='".site_url('sales/add_payment/$1')."' title='" . lang("add_payment") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'><i class='fa fa-briefcase'></i></a> <a href='" . site_url('pos/?edit=$1') . "' title='".lang("edit_invoice")."' class='tip btn btn-warning btn-xs'><i class='fa fa-edit'></i></a> <a href='" . site_url('sales/delete/$1') . "' onClick=\"return confirm('". lang('alert_x_sale') ."')\" title='".lang("delete_sale")."' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>", "id");
        
        //$this->datatables->unset_column('id');
        //$this->datatables->unset_column('total');
        
        echo $this->datatables->generate();

    }
    
    function get_notasfiscais()
	{

		$this->load->library('datatables');
        $this->datatables->select("id, data, nf_numero, nf_modelo, nf_status, nf_chave");
        $this->datatables->from('notasfiscais');
        if(!$this->Admin) {
            $user_id = $this->session->userdata('user_id');
            $this->datatables->where('userid', $user_id);
        }
        $this->datatables->add_column("Actions", "<div class='text-center'><div class='btn-group'>    
        <a href='".site_url('pos/nfe/$1/1/1/nf')."' target='_blank' title='DANFE' class='tip btn btn-success btn-xs'><i class='fa fa-barcode'></i></a>
        <a href='".site_url('pos/nfe/$1/1/2/nf')."' target='_blank' title='XML' class='tip btn btn-primary btn-xs'><i class='fa fa-list'></i></a>
        <a href='".site_url('sales/iframeredi')."?link=".site_url('pos/nfe/$1/1/4/nf')."' data-toggle='ajax' title='Corrigir Nota' class='tip btn btn-warning btn-xs'><i class='fa fa-edit'></i></a>
        <a href='".site_url('sales/iframeredi')."?link=".site_url('pos/nfe/$1/1/3/nf')."' data-toggle='ajax' title='Cancelar Nota' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a>
        </div></div>", "id");
        $this->datatables->unset_column('id');
        echo $this->datatables->generate();

	}


	function get_comissoes()
	{

		$this->load->library('datatables');
        $this->datatables->select("sales.id, sales.date, (first_name || ' ' || last_name) as nname, grand_total, comissao");
        $this->datatables->from('sales');
        $this->datatables->join('users', 'vendedor=users.id', 'left');
        $this->datatables->where('comissao >', 0); 
 
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;
        $user = $this->input->get('user') ? $this->input->get('user') : NULL;
        // filtros
        if($start_date!="") { $this->datatables->where('date >=', $this->tec->FormatarDataTimeBRParaDB($start_date));  } // formatar data
        if($end_date!="") { $this->datatables->where('date <=', $this->tec->FormatarDataTimeBRParaDB($end_date)); } // formatar data

        if(!$this->Admin) {
             $user_id = $this->session->userdata('user_id');
            $this->datatables->where('vendedor', $user_id);
        }else{
            if($user!=0){ 
                $this->datatables->where('vendedor', $user);
            }
        }
       
       $this->datatables->add_column("Actions", "<div class='text-center'><div class='btn-group' style='width:140px'><a data-fancybox href='" .  site_url('pos/view/$1/1') . "?from=sales_list' title='".lang("view_invoice")."' class='tip btn btn-primary btn-xs'><i class='fa fa-list'></i></a></div></div>", "sales.id");
        
        //$this->datatables->unset_column('id');
        //$this->datatables->unset_column('total');
        echo $this->datatables->generate();

    }
    
    function comissoes_note()
    {


        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;
        $user = $this->input->get('user') ? $this->input->get('user') : NULL;
        // filtros
        if($start_date!="") { $data['date >='] = $this->tec->FormatarDataTimeBRParaDB($start_date);  } // formatar data
        if($end_date!="") { $data['date <='] = $this->tec->FormatarDataTimeBRParaDB($end_date); } // formatar data
        if($user!="") { $data['vendedor'] = $user; }
        
        $tcomisao = $this->sales_model->getTotalComissao($data);
        $this->data['total'] = $tcomisao;
        $this->data['page_title'] = "Recibo Comissão";
        
        
        $this->data['user'] = $this->site->getUser($user);
  
        $this->load->view($this->theme . 'sales/comissoes_note', $this->data);
        
    }

	function opened()
	{
        if($this->Settings->modelonegocio=="restaurante"){
            redirect("sales/opened_mesas");
        }

		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['page_title'] = lang('opened_bills');
		$bc = array(array('link' => '#', 'page' => lang('opened_bills')));
		$meta = array('page_title' => lang('opened_bills'), 'bc' => $bc);
		$this->page_construct('sales/opened', $this->data, $meta);
	}

	function get_opened_list()
	{

		$this->load->library('datatables');
		$this->datatables
		->select("id, date, customer_name, ('<a href=".site_url('sales/iframeredi')."?link=".site_url('pos/view_bill/?hold=')."' ||  id ||  ' class=\"tip btn btn-success\" data-toggle=ajax title=\"".lang("gerar_orcamento")."\"><i class=\"fa fa-list\"></i> '  ||  hold_ref  ||  '</a>') as hold_ref, (total_items ||  ' (' ||  total_quantity ||  ')') as items, grand_total, (grand_total - paid) as faltatotal", FALSE)
		->from('suspended_sales');
        if(!$this->Admin) {
            //$user_id = $this->session->userdata('user_id');
            //$this->datatables->where('created_by', $user_id);
        }
		$this->datatables->add_column("Actions",
			"<div class='text-center'><div class='btn-group'>
            <a href='" . site_url('pos/?hold=$1') . "' title='".lang("click_to_add")."' class='tip btn btn-info btn-xs'><i class='fa fa-th-large'></i></a>
            <a href='".site_url('sales/payments/open_$1')."' title='" . lang("view_payments") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'><i class='fa fa-money'></i></a> 
            <a href='".site_url('sales/add_payment/open_$1')."' title='" . lang("add_payment") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'><i class='fa fa-briefcase'></i></a>
			<a href='" . site_url('sales/delete_holded/$1') . "' onClick=\"return confirm('". lang('alert_x_holded') ."')\" title='".lang("delete_sale")."' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>", "id,hold_ref")
		->unset_column('id');

		echo $this->datatables->generate();

	}

    function opened_mesas()
	{   
        $vendas_abertas = array();
        foreach($this->site->getUserSuspenedSales() as $m){
            $vendas_abertas[$m->hold_ref] = (array) $m;
        }
        $this->data["vendas_abertas"] = $vendas_abertas;
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['page_title'] = lang('opened_bills');
		$bc = array(array('link' => '#', 'page' => lang('opened_bills')));
		$meta = array('page_title' => lang('opened_bills'), 'bc' => $bc);
		$this->page_construct('sales/opened_mesas', $this->data, $meta);
	}


	function delete($id = NULL)
	{
		if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }

		if($this->input->get('id')){ $id = $this->input->get('id'); }

		if (!$this->Admin) {
			$this->session->set_flashdata('error', lang("access_denied"));
			redirect('sales');
		}

		if ( $this->sales_model->deleteInvoice($id) ) {
			$this->session->set_flashdata('message', lang("invoice_deleted"));
			redirect('sales');
		}

	}

	function delete_holded($id = NULL)
	{

		if($this->input->get('id')){ $id = $this->input->get('id'); }

		if (!$this->Admin) {
			$this->session->set_flashdata('error', lang("access_denied"));
			redirect('sales/opened');
		}

		if ( $this->sales_model->deleteOpenedSale($id) ) {
			$this->session->set_flashdata('message', lang("opened_bill_deleted"));
			redirect('sales/opened');
		}

	}

	/* -------------------------------------------------------------------------------- */

    function payments($id = NULL)
    {
        $this->data['payments'] = $this->sales_model->getSalePayments($id);
        $this->data['meiopagamento'] = $this->site->getAllmeiopagamento();
        $this->load->view($this->theme . 'sales/payments', $this->data);
    }

    function payment_note($id = NULL)
    {
        $payment = $this->sales_model->getPaymentByID($id);
        $inv = $this->sales_model->getSaleByID($payment->sale_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['inv'] = $inv;
        $this->data['payment'] = $payment;
        $this->data['page_title'] = $this->lang->line("payment_note");

        $this->load->view($this->theme . 'sales/payment_note', $this->data);
    }

    function add_payment($id = NULL, $cid = NULL)
    {
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->Admin) {
                $date = $this->tec->FormatarDataTimeBRParaDB($this->input->post('date'));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $payment = array(
                'date' => $date,
                'sale_id' => $id,
                'customer_id' => $cid,
                'reference' => $this->input->post('reference'),
                'amount' => $this->tec->formatDolar($this->input->post('amount-paid')),
                'paid_by' => $this->input->post('paid_by'),
                'cheque_no' => $this->input->post('cheque_no'),
                'gc_no' => $this->input->post('gift_card_no'),
                'cc_no' => $this->input->post('pcc_no'),
                'cc_holder' => $this->input->post('pcc_holder'),
                'cc_month' => $this->input->post('pcc_month'),
                'cc_year' => $this->input->post('pcc_year'),
                'cc_type' => $this->input->post('pcc_type'),
                'note' => $this->input->post('note'),
                'created_by' => $this->session->userdata('user_id'),
            );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = 2048;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $payment['attachment'] = $photo;
            }

            //$this->tec->print_arrays($payment);

        } elseif ($this->input->post('add_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            $this->tec->dd();
        }


        if ($this->form_validation->run() == true && $this->sales_model->addPayment($payment)) {
            $this->session->set_flashdata('message', lang("payment_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {

            $payments = $this->sales_model->getSalePayments($id);
            $countcash = 0;
            $amount_total = 0;
            if($payments) {
                foreach ($payments as $payment) {
                    $amount_total += $payment->amount;
                    if($payment->paid_by=="cash"){
                        //$countcash++;
                    }
                }
            }
    
            $this->data['countcash'] = $countcash;

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $sale = $this->sales_model->getSaleByID($id);
            $sale->id = $id;
            $sale->grand_total = $sale->grand_total - $amount_total;
            $this->data['meiopagamento'] = $this->site->getAllmeiopagamento();
            $this->data['inv'] = $sale;
            $this->load->view($this->theme . 'sales/add_payment', $this->data);
        }
    }

    function edit_payment($id = NULL, $sid = NULL)
    {

    	if (!$this->Admin) {
			$this->session->set_flashdata('error', lang("access_denied"));
			redirect($_SERVER["HTTP_REFERER"]);
		}
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            $payment = array(
                'sale_id' => $sid,
                'reference' => $this->input->post('reference'),
                'amount' => $this->tec->formatDolar($this->input->post('amount-paid')),
                'paid_by' => $this->input->post('paid_by'),
                'cheque_no' => $this->input->post('cheque_no'),
                'gc_no' => $this->input->post('gift_card_no'),
                'cc_no' => $this->input->post('pcc_no'),
                'cc_holder' => $this->input->post('pcc_holder'),
                'cc_month' => $this->input->post('pcc_month'),
                'cc_year' => $this->input->post('pcc_year'),
                'cc_type' => $this->input->post('pcc_type'),
                'note' => $this->input->post('note'),
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if ($this->Admin) {
                $payment['date'] =  $this->tec->FormatarDataTimeBRParaDB($this->input->post('date'));
            }

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = 2048;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $payment['attachment'] = $photo;
            }

            //$this->sma->print_arrays($payment);

        } elseif ($this->input->post('edit_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            $this->tec->dd();
        }

        if ($this->form_validation->run() == true && $this->sales_model->updatePayment($id, $payment)) {
            $this->session->set_flashdata('message', lang("payment_updated"));
            die;
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $payment = $this->sales_model->getPaymentByID($id);
            if($payment->paid_by != 'cash') {
            	$this->session->set_flashdata('error', lang('only_cash_can_be_edited'));
            	$this->tec->dd();
            }
            $sale->id = $id;
            $this->data['payment'] = $payment;
            $this->data['meiopagamento'] = $this->site->getAllmeiopagamento();
            $this->load->view($this->theme . 'sales/edit_payment', $this->data);
        }
    }

    function delete_payment($id = NULL)
    {

		if($this->input->get('id')){ $id = $this->input->get('id'); }

		if (!$this->Admin) {
			$this->session->set_flashdata('error', lang("access_denied"));
			redirect($_SERVER["HTTP_REFERER"]);
		}

		if ( $this->sales_model->deletePayment($id) ) {
			$this->session->set_flashdata('message', lang("payment_deleted"));
            redirect($_SERVER["HTTP_REFERER"]);
		}
    }


    function iframeredi()
	{
		$this->data['page_title'] = "iframe";
		$bc = array(array('link' => '#', 'page' => "iframe"));
        $meta = array('page_title' => "iframe", 'bc' => $bc);
        
        $link = $this->input->get('link');
        $this->data['link'] = $link;

        $this->load->view($this->theme . 'sales/iframeredi', $this->data);
	}

    /* --------------------------------------------------------------------------------------------- */


}