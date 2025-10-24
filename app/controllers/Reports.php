<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller
{

    function __construct() {
        parent::__construct();


        if ( ! $this->loggedIn) {
            redirect('login');
        }

        if ( ! $this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        $this->load->model('reports_model');
    }

    function ConvertDateTime($dataBR){

        if(empty($dataBR)) return "";

        $d1 = explode(" ", $dataBR);
        $dias = $d1[0];
        $dias = explode("/", $dias);

        $dias = $dias[2]."-".$dias[1]."-".$dias[0];
        $horas = ($d1[1])? $d1[1] : "";

        return trim($dias." ".$horas);

    }

    function daily_sales($year = NULL, $month = NULL)
    {
        if (!$year) { $year = date('Y'); }
        if (!$month) { $month = date('m'); }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->lang->load('calendar');
        $config = array(
            'show_next_prev' => TRUE,
            'next_prev_url' => site_url('reports/daily_sales'),
            'month_type' => 'long',
            'day_type' => 'long'
            );
        $config['template'] = '

        {table_open}<table border="0" cellpadding="0" cellspacing="0" class="table table-bordered" style="min-width:522px;">{/table_open}

        {heading_row_start}<tr class="active">{/heading_row_start}

        {heading_previous_cell}<th><div class="text-center"><a href="{previous_url}">&lt;&lt;</div></a></th>{/heading_previous_cell}
        {heading_title_cell}<th colspan="{colspan}"><div class="text-center">{heading}</div></th>{/heading_title_cell}
        {heading_next_cell}<th><div class="text-center"><a href="{next_url}">&gt;&gt;</a></div></th>{/heading_next_cell}

        {heading_row_end}</tr>{/heading_row_end}

        {week_row_start}<tr>{/week_row_start}
        {week_day_cell}<td class="cl_equal"><div class="cl_wday">{week_day}</div></td>{/week_day_cell}
        {week_row_end}</tr>{/week_row_end}

        {cal_row_start}<tr>{/cal_row_start}
        {cal_cell_start}<td>{/cal_cell_start}

        {cal_cell_content}<div class="cl_left">{day}</div><div class="cl_right">{content}</div>{/cal_cell_content}
        {cal_cell_content_today}<div class="cl_left highlight">{day}</div><div class="cl_right">{content}</div>{/cal_cell_content_today}

        {cal_cell_no_content}{day}{/cal_cell_no_content}
        {cal_cell_no_content_today}<div class="highlight">{day}</div>{/cal_cell_no_content_today}

        {cal_cell_blank}&nbsp;{/cal_cell_blank}

        {cal_cell_end}</td>{/cal_cell_end}
        {cal_row_end}</tr>{/cal_row_end}

        {table_close}</table>{/table_close}
        ';

        $this->load->library('calendar', $config);

        $sales = $this->reports_model->getDailySales($year, $month);

        if(!empty($sales)) {
            foreach($sales as $sale){
                $daily_sale[$sale->date] = "<span class='text-warning'>". $this->tec->formatMoney($sale->tax)."</span><br>".$this->tec->formatMoney($sale->discount)."<br><span class='text-success'>".$this->tec->formatMoney($sale->total)."</span><br><span style='border-top:1px solid #DDD;'>".$this->tec->formatMoney($sale->grand_total)."</span>";
            }
        } else {
            $daily_sale = array();
        }

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['calender'] = $this->calendar->generate($year, $month, $daily_sale);

        $start = $year.'-'.$month.'-01 00:00:00';
        $end = $year.'-'.$month.'-'.days_in_month($month, $year).' 23:59:59';
        $this->data['total_purchases'] = $this->reports_model->getTotalPurchases($start, $end);
        $this->data['total_sales'] = $this->reports_model->getTotalSales($start, $end);
        $this->data['total_expenses'] = $this->reports_model->getTotalExpenses($start, $end);

        $this->data['page_title'] = $this->lang->line("daily_sales");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('daily_sales')));
        $meta = array('page_title' => lang('daily_sales'), 'bc' => $bc);
        $this->page_construct('reports/daily', $this->data, $meta);

    }


    function monthly_sales($year = NULL)
    {
        if(!$year) { $year = date('Y'); }
        $this->lang->load('calendar');
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $start = $year.'-01-01 00:00:00';
        $end = $year.'-12-31 23:59:59';
        $this->data['total_purchases'] = $this->reports_model->getTotalPurchases($start, $end);
        $this->data['total_sales'] = $this->reports_model->getTotalSales($start, $end);
        $this->data['total_expenses'] = $this->reports_model->getTotalExpenses($start, $end);
        $this->data['year'] = $year;
        $this->data['sales'] = $this->reports_model->getMonthlySales($year);
        $this->data['page_title'] = $this->lang->line("monthly_sales");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('monthly_sales')));
        $meta = array('page_title' => lang('monthly_sales'), 'bc' => $bc);
        $this->page_construct('reports/monthly', $this->data, $meta);
    }

    function index()
    {

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        if($this->input->get('customer')) {
            $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
            $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;
            $user = $this->input->get('user') ? $this->input->get('user') : NULL;
            $this->data['total_sales'] = $this->reports_model->getTotalSalesforCustomer($this->input->get('customer'), $user, $start_date, $end_date);
            $totasales = $this->reports_model->getTotalSalesValueforCustomer($this->input->get('customer'), $user, $start_date, $end_date);
            $this->data['total_sales_value'] = ($totasales!="")? number_format((float)$totasales, 2, ',', '.') : "0,00";
        }
        $this->data['isAdmin'] = $this->Admin;
        $this->data['customers'] = $this->reports_model->getAllCustomers();
        $this->data['users'] = $this->reports_model->getAllStaff();
        $this->data['page_title'] = $this->lang->line("sales_report");
       
        if ($this->input->get('tipo')=="contas_a_receber") {
            $this->data['page_title'] .= " - Contas a receber";
        }

        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('sales_report')));
        $meta = array('page_title' =>  $this->data['page_title'], 'bc' => $bc);
        $this->page_construct('reports/sales', $this->data, $meta);
    }

    function get_sales()
    {
        
        $this->load->library('datatables');
        $this->datatables
        ->select(" ('<a href=".site_url('pos/view')."/' ||  ".$this->db->dbprefix('sales').".id || '?from=sales_list class=linkcustomer_1 target=_blank title=Venda alt=Venda>' ||  ".$this->db->dbprefix('sales').".id || '</a><span class=linkcustomer_2 style=display:none;>' ||  ".$this->db->dbprefix('sales').".id  || '</span>') as idsale, sales.date, ('<a href=reports?customer=' ||  customer_id || ' class=linkcustomer_1 alt=Vendas>' ||  COALESCE(NULLIF(customer_name, ''), tec_customers.cf1, '*Desconhecido*') || '</a><span class=linkcustomer_2 style=display:none;>' ||  COALESCE(NULLIF(customer_name, ''), tec_customers.cf1, '*Desconhecido*') || '</span>') as customer_name, total, total_tax, total_discount, grand_total, (paid - troco) as paidtotal, CASE WHEN (paid - grand_total) > 0 then 0 ELSE (paid - grand_total) END as balance, troco, sales.status")
        ->join('tec_customers', 'tec_customers.id=customer_id', 'left')
        ->from('sales');

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
      
        echo $this->datatables->generate();
        
    }

    function products()
    {

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

        $this->data['products'] = $this->reports_model->getAllProducts();
        $this->data['page_title'] = $this->lang->line("products_report");
        $this->data['page_title'] = $this->lang->line("products_report");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('products_report')));
        $meta = array('page_title' => lang('products_report'), 'bc' => $bc);
        $this->page_construct('reports/products', $this->data, $meta);
    }

    function get_products()
    {
        $product = $this->input->get('product') ? $this->input->get('product') : NULL;
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;

        //COALESCE(sum(".$this->db->dbprefix('sale_items').".quantity)*".$this->db->dbprefix('products').".cost, 0) as cost,
        $this->load->library('datatables');

        $this->datatables
        ->select(" ".$this->db->dbprefix('products').".code, ('<a class=linkcustomer_1 href=".site_url('products/edit/')."/' ||  ".$this->db->dbprefix('products').".id || ' class=tip target=_blank title=Ver/Editar alt=Editar>' || ".$this->db->dbprefix('products').".name || '</a><span class=linkcustomer_2 style=display:none;>' ||   ".$this->db->dbprefix('products').".name  || '</span>') as prodname, COALESCE(sum(".$this->db->dbprefix('sale_items').".quantity), 0) as sold, COALESCE(sum(".$this->db->dbprefix('sale_items').".quantity)*".$this->db->dbprefix('sale_items').".cost, 0) as cost, COALESCE(sum(".$this->db->dbprefix('sale_items').".subtotal), 0) as income,
            ROUND((COALESCE(sum(".$this->db->dbprefix('sale_items').".subtotal), 0)) - COALESCE(sum(".$this->db->dbprefix('sale_items').".quantity)*".$this->db->dbprefix('sale_items').".cost, 0) -COALESCE(((sum(".$this->db->dbprefix('sale_items').".subtotal)*".$this->db->dbprefix('products').".tax)/100), 0), 2)
            as profit", FALSE)
        ->from('sale_items')
        ->where($this->db->dbprefix('products').".code is NOT NULL", NULL, FALSE)
        ->join('products', 'sale_items.product_id=products.id', 'left' )
        ->join('sales', 'sale_items.sale_id=sales.id', 'left' )
        ->group_by('products.id');

        if($product) { $this->datatables->where('products.id', $product); }
        if($start_date) { $this->datatables->where('date >=',  $this->ConvertDateTime($start_date)); }
        if($end_date) { $this->datatables->where('date <=',  $this->ConvertDateTime($end_date)); }

        echo $this->datatables->generate();
    }

    

    function profit( $income, $cost, $tax)
    {
        return floatval($income)." - ".floatval($cost)." - ".floatval($tax);
    }

    function top_products()
    {

        $this->data['topProducts'] = $this->reports_model->topProducts();
        $this->data['topProducts1'] = $this->reports_model->topProducts1();
        $this->data['topProducts3'] = $this->reports_model->topProducts3();
        $this->data['topProducts12'] = $this->reports_model->topProducts12();

        $this->data['page_title'] = $this->lang->line("top_products");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('top_products')));
        $meta = array('page_title' => lang('top_products'), 'bc' => $bc);
        $this->page_construct('reports/top', $this->data, $meta);
    }

    function registers()
    {

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['users'] = $this->reports_model->getAllStaff();
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('registers_report')));
        $meta = array('page_title' => lang('registers_report'), 'bc' => $bc);
        $this->page_construct('reports/registers', $this->data, $meta);
    }

    function get_register_logs()
    {

        $user = $this->input->get('user') ? $this->input->get('user') : NULL;
        $start_date = $this->input->get('start_date') ?  $this->ConvertDateTime($this->input->get('start_date')) : NULL;
        $end_date = $this->input->get('end_date') ?  $this->ConvertDateTime($this->input->get('end_date')) : NULL;

        $this->load->library('datatables');
        $this->datatables
        ->select($this->db->dbprefix('registers') . ".id as rid, date, closed_at, (" . $this->db->dbprefix('users') . ".first_name ||  ' ' ||  " . $this->db->dbprefix('users') . ".last_name ||  ' / ' || " . $this->db->dbprefix('users') . ".email) as user, cash_in_hand, (total_cc_slips || ' (' || total_cc_slips_submitted ||  ')') as cc_slips, (total_cheques ||  ' (' || total_cheques_submitted ||  ')') as total_cheques, (total_cash ||  ' (' ||  total_cash_submitted ||  ')') as total_cash", FALSE)
        ->from("registers")
        ->join('users', 'users.id=registers.user_id', 'left');

        if ($user) {
            $this->datatables->where('registers.user_id', $user);
        }
        if ($start_date) {
            $this->datatables->where('date >=', $start_date);
            $this->datatables->where('date <=', $end_date);
        }

        $this->datatables->add_column("Actions", "<div class='text-center'><a href='".site_url("reports/registers_view/$1")."' target='_blank' class='btn btn-xs btn-primary tip'  title='Ver'><i class='fa fa-plus'></i> Ver detalhes</a></div>", "rid");
        $this->datatables->unset_column('rid');

        echo $this->datatables->generate();

    }

    function registers_view($id)
    {
        $info = $this->reports_model->getRegisterInfo($id);

        echo '<style>body{font-family:arial}@media print { .no-print, .no-print * { display: none !important; } }</style>';
        echo ' <a href="#" onclick="window.print();" class="no-print">Imprimir</a><br><br>
            <b>Abertura do Caixa: '.$this->tec->hrld().'</b><br>
            <b>Fechamento do Caixa: '.$this->tec->hrld().'</b><br><hr><br>';

        echo $info->note;
       
    }

    function payments()
    {
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['users'] = $this->reports_model->getAllStaff();
        $this->data['customers'] = $this->reports_model->getAllCustomers();
        $this->data['meiopagamento'] = $this->site->getAllmeiopagamento();
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('payments_report')));
        $meta = array('page_title' => lang('payments_report'), 'bc' => $bc);
        $this->page_construct('reports/payments', $this->data, $meta);
    }

    function get_payments()
    {
        $user = $this->input->get('user') ? $this->input->get('user') : NULL;
        $ref = $this->input->get('payment_ref') ? $this->input->get('payment_ref') : NULL;
        $sale_id = $this->input->get('sale_no') ? $this->input->get('sale_no') : NULL;
        $customer = $this->input->get('customer') ? $this->input->get('customer') : NULL;
        $paid_by = $this->input->get('paid_by') ? $this->input->get('paid_by') : NULL;
        $start_date = $this->input->get('start_date') ?  $this->ConvertDateTime($this->input->get('start_date')) : NULL;
        $end_date = $this->input->get('end_date') ?  $this->ConvertDateTime($this->input->get('end_date')) : NULL;

        $this->load->library('datatables');
        $this->datatables
        ->select($this->db->dbprefix('payments') . ".date, " . $this->db->dbprefix('payments') . ".reference as ref, ('<a href=".site_url('pos/view')."/' ||  ".$this->db->dbprefix('sales').".id || '?from=sales_list class=linkcustomer_1 target=_blank title=Venda alt=Venda>' ||  ".$this->db->dbprefix('sales').".id || '</a><span class=linkcustomer_2 style=display:none;>' ||  ".$this->db->dbprefix('sales').".id  || '</span>') as sale_no, paid_by, amount")
        ->from('payments')
        ->join('sales', 'payments.sale_id=sales.id', 'left')
        ->group_by('payments.id');

        if ($user) {
            $this->datatables->where('payments.created_by', $user);
        }
        if ($ref) {
            $this->datatables->where('payments.reference', $ref);
        }
        if ($paid_by) {
            $this->datatables->where('payments.paid_by', $paid_by);
        }
        if ($sale_id) {
            $this->datatables->where('sales.id', $sale_id);
        }
        if ($customer) {
            $this->datatables->where('sales.customer_id', $customer);
        }
        if ($customer) {
            $this->datatables->where('sales.customer_id', $customer);
        }
        if ($start_date) {
            $this->datatables->where($this->db->dbprefix('payments').'.date >=', $start_date);
            $this->datatables->where($this->db->dbprefix('payments').'.date <=', $end_date);
        }

        echo $this->datatables->generate();

    }

    function alerts() {
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('stock_alert');
        $bc = array(array('link' => '#', 'page' => lang('stock_alert')));
        $meta = array('page_title' => lang('stock_alert'), 'bc' => $bc);
        $this->page_construct('reports/alerts', $this->data, $meta);

    }

    function get_alerts() {

        $this->load->library('datatables');
        $this->datatables->select($this->db->dbprefix('products').".id as pid, ".$this->db->dbprefix('products').".image as image, ".$this->db->dbprefix('products').".code as code, ".$this->db->dbprefix('products').".name as pname, type, ".$this->db->dbprefix('categories').".name as cname, quantity, alert_quantity, cost, price", FALSE)
        ->join('categories', 'categories.id=products.category_id')
        ->from('products')
        ->where('quantity < alert_quantity', NULL, FALSE)
        ->group_by('products.id');
        $this->datatables->add_column("Actions", "<div class='text-center'><a href='#' class='btn btn-xs btn-primary ap tip' data-id='$1' title='".lang('add_to_purcahse_order')."'><i class='fa fa-plus'></i></a></div>", "pid");
        $this->datatables->unset_column('pid');
        echo $this->datatables->generate();

    }

}
