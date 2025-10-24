<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function __construct() {
        parent::__construct();

        if (! $this->loggedIn) {
            redirect('login');
        }
        $this->load->model('welcome_model');
    }

    function index() {
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
        $filter = $this->input->get('f');
        if($filter=="") $filter = "hoje";

        if (!$this->Admin) {
            $user = $this->session->userdata('user_id');
        }
        
        $despesas = $this->welcome_model->getTotalExpenses($filter, $user);
        $vendas = $this->welcome_model->getTotalSales($filter, $user);
        if($vendas->balance>0) $vendas->balance = 0;
        if($vendas->balance<0) $vendas->balance = $vendas->balance * -1;

        $this->data['totalDespesas'] = $this->tec->formatMoney($despesas->total);
        $this->data['totalVendas'] = $this->tec->formatMoney($vendas->totalvendas);
        $this->data['totalEmaberto'] = $this->tec->formatMoney($vendas->balance);
        
        $this->data['filtro'] = $filter;

        $this->data['topProducts'] = $this->welcome_model->topProducts();
        $this->data['chartData'] = $this->welcome_model->getChartData();
        
        $this->data['page_title'] = lang('dashboard');
        $bc = array(array('link' => '#', 'page' => lang('dashboard')));
        $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);
        $this->page_construct('dashboard', $this->data, $meta);

    }
    
    function ping(){
        echo "ok";
        die;
    }

    function disabled() {
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('disabled_in_demo');
        $bc = array(array('link' => '#', 'page' => lang('disabled_in_demo')));
        $meta = array('page_title' => lang('disabled_in_demo'), 'bc' => $bc);
        $this->page_construct('disabled', $this->data, $meta);
    }

}