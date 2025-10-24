<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        
        $this->Settings = $this->site->getSettings();
        define('TIPONEGOCIO', $this->Settings->modelonegocio);
        $timezones = array( 'AC' => 'America/Rio_branco', 'AL' => 'America/Maceio', 'AP' => 'America/Belem', 'AM' => 'America/Manaus', 'BA' => 'America/Bahia', 'CE' => 'America/Fortaleza', 'DF' => 'America/Sao_Paulo', 'ES' => 'America/Sao_Paulo', 'GO' => 'America/Sao_Paulo', 'MA' => 'America/Fortaleza', 'MT' => 'America/Cuiaba', 'MS' => 'America/Campo_Grande', 'MG' => 'America/Sao_Paulo', 'PR' => 'America/Sao_Paulo', 'PB' => 'America/Fortaleza', 'PA' => 'America/Belem', 'PE' => 'America/Recife', 'PI' => 'America/Fortaleza', 'RJ' => 'America/Sao_Paulo', 'RN' => 'America/Fortaleza', 'RS' => 'America/Sao_Paulo', 'RO' => 'America/Porto_Velho', 'RR' => 'America/Boa_Vista', 'SC' => 'America/Sao_Paulo', 'SE' => 'America/Maceio', 'SP' => 'America/Sao_Paulo', 'TO' => 'America/Araguaia', );
        $this->timezone = $timezones[strtoupper($this->Settings->timezone)];
        if(function_exists('date_default_timezone_set')) date_default_timezone_set($this->timezone);
	    define('TIMEZONE', $this->timezone);
        $this->lang->load('app', $this->Settings->language);
        $this->Settings->pin_code = $this->Settings->pin_code ? md5($this->Settings->pin_code) : NULL;
        $this->theme = $this->Settings->theme.'/views/';
        $this->data['assets'] = base_url() . 'themes/default/assets/';
        $this->data['Settings'] = $this->Settings;
        $this->loggedIn = $this->tec->logged_in();
        $this->data['loggedIn'] = $this->loggedIn;
        $this->data['categories'] = $this->site->getAllCategories();
        $this->m = strtolower($this->router->fetch_class());
        $this->v = strtolower($this->router->fetch_method());

        $this->Admin = $this->tec->in_group('admin') ? TRUE : NULL;
        $this->data['Admin'] = $this->Admin;
        if($this->tec->in_group('admin')){
            $this->UserPerfil = "admin";
        }
        if($this->tec->in_group('staff')){
            $this->UserPerfil = "staff";
        }
        if($this->tec->in_group('vendas')){
            $this->UserPerfil = "vendas";
            // por segurança
            $this->allow = array(
                "welcome",
                "errors",
                "auth",
                "pos",
                "sales",
                "customers",
            );

            if(!in_array($this->m, $this->allow)){
                $this->session->set_flashdata('error', lang('access_denied'));
                 redirect('welcome/index');
            }

        }
        $this->data['UserPerfil'] = $this->UserPerfil;

        $this->data['m']= $this->m;
        $this->data['v'] = $this->v;

    }

    function page_construct($page, $data = array(), $meta = array()) {
        if(empty($meta)) { $meta['page_title'] = $data['page_title']; }
        $meta['message'] = isset($data['message']) ? $data['message'] : $this->session->flashdata('message');
        $meta['error'] = isset($data['error']) ? $data['error'] : $this->session->flashdata('error');
        $meta['warning'] = isset($data['warning']) ? $data['warning'] : $this->session->flashdata('warning');
        $meta['ip_address'] = $this->input->ip_address();
        $meta['Admin'] = $data['Admin'];
        $meta['UserPerfil'] = $data['UserPerfil'];
        $meta['loggedIn'] = $data['loggedIn'];
        $meta['Settings'] = $data['Settings'];
        $meta['assets'] = $data['assets'];
        $meta['suspended_sales'] = $this->site->getUserSuspenedSales();
        $meta['qty_alert_num'] = $this->site->getQtyAlerts();
        $this->load->view($this->theme . 'header', $meta);
        $this->load->view($this->theme . $page, $data);
        $this->load->view($this->theme . 'footer');
    }

}
