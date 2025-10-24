<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Controller
{

    function __construct() {
        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        $this->load->library('form_validation');
        $this->load->model('settings_model');

    }

  	 //configuracaoes do sistema editar varios parametros aqui

    function index() {

        $this->form_validation->set_rules('site_name', lang('site_name'), 'required');
        //$this->form_validation->set_rules('tel', lang('tel'), 'required');
        //$this->form_validation->set_rules('language', lang('language'), 'required');
        // $this->form_validation->set_rules('currency_prefix', lang('currency_code'), 'required|max_length[3]|min_length[3]');
        // $this->form_validation->set_rules('default_discount', lang('default_discount'), 'required');
        // $this->form_validation->set_rules('tax_rate', lang('default_tax_rate'), 'required');
        //$this->form_validation->set_rules('rows_per_page', lang('rows_per_page'), 'required');
        //$this->form_validation->set_rules('display_product', lang('display_product'), 'required');
        //$this->form_validation->set_rules('pro_limit', lang('pro_limit'), 'required');
        //$this->form_validation->set_rules('display_kb', lang('display_kb'), 'required');
        //$this->form_validation->set_rules('default_category', lang('default_category'), 'required');
        //$this->form_validation->set_rules('default_customer', lang('default_customer'), 'required');
        //$this->form_validation->set_rules('dateformat', lang('date_format'), 'required');
        // $this->form_validation->set_rules('timeformat', lang('time_format'), 'required');
        //$this->form_validation->set_rules('item_addition', lang('item_addition'), 'required');
        // if ($this->input->post('protocol') == 'smtp') {
        //    $this->form_validation->set_rules('smtp_host', lang('smtp_host'), 'required');
        //   $this->form_validation->set_rules('smtp_user', lang('smtp_user'), 'required');
        //   $this->form_validation->set_rules('smtp_pass', lang('smtp_pass'), 'required');
        //   $this->form_validation->set_rules('smtp_port', lang('smtp_port'), 'required');
        //}
        //if ($this->input->post('stripe')) {
        //  $this->form_validation->set_rules('validade_certificado', lang('validade_certificado'), 'required');
        // $this->form_validation->set_rules('stripe_publishable_key', lang('stripe_publishable_key'), 'required');
        //}
        //$this->form_validation->set_rules('bill_header', lang('bill_header'), 'required');
        //$this->form_validation->set_rules('bill_footer', lang('bill_footer'), 'required');
        //$this->load->library('encrypt');


        if ($this->form_validation->run() == true) {

            if(DEMO) {
                $this->session->set_flashdata('info', "Esta é um versão de DEMONSTRAÇÃO, por segurança não será permitido alterar dados.");
                redirect('settings');
            }

            $arrPix = array(
                "pagamento_pix_tipochave" => $this->input->post('pagamento_pix_tipochave'),
                "pagamento_pix_chave" => $this->input->post('pagamento_pix_chave'),
                "pagamento_pix_beneficiario" => $this->input->post('pagamento_pix_beneficiario'),
                "pagamento_pix_cidade" => $this->input->post('pagamento_pix_cidade')
            );

            $data = array(
                'site_name' => DEMO ? 'TudoNet' : $this->input->post('site_name'),
                'tel' => $this->input->post('tel'),
                //'currency_prefix' => DEMO ? 'USD' : strtoupper($this->input->post('currency_prefix')),
                //'default_tax_rate' => $this->input->post('tax_rate'),
                //'default_discount' => $this->input->post('default_discount'),
                'rows_per_page' => $this->input->post('rows_per_page'),
                'bsty' => $this->input->post('display_product'),
                'pro_limit' => $this->input->post('pro_limit'),
                'display_kb' => $this->input->post('display_kb'),
                'default_category' => $this->input->post('default_category'),
                'default_customer' => $this->input->post('default_customer'),
                //'barcode_symbology' => $this->input->post('barcode_symbology'),
                // 'dateformat' => DEMO ? 'jS F Y' : $this->input->post('dateformat'),
                //'timeformat' => DEMO ? 'h:i A' : $this->input->post('timeformat'),
                'header' => $this->input->post('bill_header'),
                'footer' => $this->input->post('bill_footer'),
                'default_email' => DEMO ? 'tudonetrn@gmail.com' : $this->input->post('default_email'),
                'protocol' => $this->input->post('protocol'),
                'smtp_host' => $this->input->post('smtp_host'),
                'smtp_user' => $this->input->post('smtp_user'),
                'smtp_pass' => $this->input->post('smtp_pass'),
                'smtp_port' => $this->input->post('smtp_port'),
                'smtp_crypto' => $this->input->post('smtp_crypto'),
                'mailpath' => $this->input->post('naturezaoperacao'), // se tornou natureza
                'pin_code' => $this->input->post('pin_code') ? $this->input->post('pin_code') : NULL,
                'receipt_printer' => $this->input->post('receipt_printer'),
                'cash_drawer_codes' => $this->input->post('cash_drawer_codes'),
                //'focus_add_item' => $this->input->post('focus_add_item'),
                //'add_customer' => $this->input->post('add_customer'),
                //'toggle_category_slider' => $this->input->post('toggle_category_slider'),
                //'cancel_sale' => $this->input->post('cancel_sale'),
                //'suspend_sale' => $this->input->post('suspend_sale'),
                //'print_order' => $this->input->post('print_order'),
                //'print_bill' => $this->input->post('print_bill'),
                //'finalize_sale' => $this->input->post('finalize_sale'),
                //'today_sale' => $this->input->post('today_sale'),
                //'open_hold_bills' => $this->input->post('open_hold_bills'),
                //'close_register' => $this->input->post('close_register'),
                'pos_printers' => $this->input->post('pos_printers'),
                'java_applet' => '0',
                //'rounding' => $this->input->post('rounding'),
                'item_addition' => 0, //$this->input->post('item_addition'),
                'stripe' => $this->input->post('stripe'),
                'ultima_nf' => $this->input->post('ultima_nf'),
                'ultima_nfc' => $this->input->post('ultima_nfc'),
                'serie_nf' => $this->input->post('serie_nf'),
                'serie_nfc' => $this->input->post('serie_nfc'),
                'postal_code' => $this->input->post('postal_code'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'phone_number' => $this->input->post('phone_number'),
                'estado' => $this->input->post('estado'),
                'vat_no' => $this->input->post('vat_no'),
                'ie' => $this->input->post('ie'),
                "tpAmb" => $this->input->post('tpAmb'),
                "razaosocial" => $this->input->post('razaosocial'),
                "fantasia" => $this->input->post('fantasia'),
                "im" => $this->input->post('im'),
                "cnae" => $this->input->post('cnae'),
                "crt" => $this->input->post('crt'),
                "numero" => $this->input->post('numero'),
                "bairro" => $this->input->post('bairro'),
                "ccidade" => $this->input->post('ccidade'), 
                "codigoUF" => $this->input->post('codigoUF'), 
                "tokenIBPT" => $this->input->post('tokenIBPT'), 
                "CSC" => $this->input->post('CSC'),
                "CSCid" => $this->input->post('CSCid'),
                'tamanhopapel' => $this->input->post('tamanhopapel'),
                'pdvdiretonfc' => $this->input->post('pdvdiretonfc'),
                'modelonegocio' => $this->input->post('modelonegocio'),
                'balanca_tipodado' => $this->input->post('balanca_tipodado'),
                'balanca_digitosiniciais' => $this->input->post('balanca_digitosiniciais'),
                'balanca_posicaopesovalor' => $this->input->post('balanca_posicaopesovalor'),
                'balanca_tamanhoinfopesovalor' => $this->input->post('balanca_tamanhoinfopesovalor'),
                'balanca_casadecimais' => $this->input->post('balanca_casadecimais'),
                'pagamento_pix' => json_encode($arrPix),
                'total_mesas' => !empty($this->input->post('total_mesas')) ? $this->input->post('total_mesas') : 0,
                'ativar_emissao_notas' => $this->input->post('ativar_emissao_notas'),
                'transmissaoNFe' => $this->input->post('transmissaoNFe'),
                'timezone' => $this->input->post('timezone'),
            );

            $_FILES['userfile'] = $_FILES['logonota'];
            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = '3000';
                $config['max_width'] = '5000';
                $config['max_height'] = '5000';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    //redirect("products/add", 'refresh'); // don't need to reload.
                }else{

                    $photo = $this->upload->file_name;
                    $data['logo'] = $config['upload_path'].$photo;
    
                }

            }

        
            if ($_FILES['certificadofile']['size'] > 0) {

                $dir = './certs_upclientes/';
                $ext = strtolower(pathinfo(basename($_FILES["certificadofile"]["name"]), PATHINFO_EXTENSION));
                $certificado_nome = md5($this->input->post('vat_no')).'_'.sha1($this->input->post('vat_no')).".".$ext;

                if($ext != "pfx" && $ext != "p12") {
                    $this->session->set_flashdata('error', "Certificado não permitido, use arquivos .pfx ou .p12");
                    //redirect('settings');
                }else{

                    $this->load->helper('file');

                    if(move_uploaded_file($_FILES["certificadofile"]["tmp_name"], dirname(__FILE__)."/cert_digitais/".$certificado_nome)) 
                    {
                        chmod(dirname(__FILE__)."/cert_digitais/".$certificado_nome,0777);
                        $data["certificado"] = $certificado_nome; 

                    }else{

                        $this->session->set_flashdata('error', "Erro ao fazer upload do certificado.");
                        //redirect('settings');

                    }
                }
            }

            if($this->input->post('certificadosenha')!="")
            {
                $data["certificadosenha"] = $this->input->post('certificadosenha');
            }
        
            if($_POST['bairro_ids']!="")
            {           
                $bairros_entregas_values = $_POST['bairro_ids'];
                $bairro_nome_values = $_POST['bairro_nome'];
                $bairro_valor_values = $_POST['bairro_valor'];
            }
            $bairros_entregas = array();
            foreach( $bairros_entregas_values as $k => $v){
                $bairros_entregas[$k] = array( "nome" => $bairro_nome_values[$k], "valor" => $this->tec->formatDolar($bairro_valor_values[$k]));
            }
            $data["loja_bairros_entrega"] = json_encode($bairros_entregas);
            if(!empty($_FILES["banner_1"]["tmp_name"])){
                move_uploaded_file($_FILES["banner_1"]["tmp_name"], __DIR__."/../../../../../loja/banners/banner1.png");
            }
            if(!empty($_FILES["banner_2"]["tmp_name"])){
                move_uploaded_file($_FILES["banner_2"]["tmp_name"], __DIR__."/../../../../../loja/banners/banner2.png");
            }
            if(!empty($_FILES["banner_3"]["tmp_name"])){
                move_uploaded_file($_FILES["banner_3"]["tmp_name"], __DIR__."/../../../../../loja/banners/banner3.png");
            }

            $actualiza = $this->settings_model->updateSetting($data);
            if (!empty($data) && $actualiza) {

                $this->session->set_flashdata('message', lang('setting_updated'));
                redirect('settings');
    
            } else {
                $this->session->set_flashdata('error', "Erro ao guardar dados, tente novamente.");
                redirect('settings');
            }

        } else {

            if ($actualiza) {
                $this->session->set_flashdata('error', "Erro ao guardar dados, tente novamente.");
                redirect('settings');
            }

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['settings'] = $this->site->getSettings();
            $this->data['customers'] = $this->site->getAllCustomers();
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['plano'] = $this->session->userdata('plano_nome');
            $this->data['page_title'] = lang('settings');
            $this->data['contacadastro'] = $this->session->userdata('plano_nome');
            $bc = array(array('link' => '#', 'page' => lang('settings')));
            $meta = array('page_title' => lang('settings'), 'bc' => $bc);

            //$this->session->set_flashdata('error', "Erro ao guardar dados, tente novamente.");
            if(LOJA==1){
                $this->data['bairros'] = ($this->Settings->loja_bairros_entrega!="")? json_decode($this->Settings->loja_bairros_entrega, true) : null;
                $this->data['banners'] = glob('./loja/banners/*', GLOB_BRACE);
            }

            $this->page_construct('settings/index', $this->data, $meta);

        }
    }

    function backups()
    {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
        $this->data['files'] = glob('./files/backups/*.zip', GLOB_BRACE);
        $this->data['dbs'] = glob('./files/backups/*.db', GLOB_BRACE);
		 $this->data['notas'] = glob('./files/backups_notas/*.zip', GLOB_BRACE);
        $bc = array(array('link' => site_url('settings'), 'page' => lang('settings')), array('link' => '#', 'page' => lang('backups')));
        $meta = array('page_title' => lang('backups'), 'bc' => $bc);
        $this->page_construct('settings/backups', $this->data, $meta);
    }

    function create_backup()
    {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        
        if(file_put_contents("./files/backups/tnet_backup_banco_".date("Y-m-d-H-i-s").".db", file_get_contents("./files/database/tnet.db"))){
          
        }
        
        $name = 'tnet_backup_arquivos_' . date("Y-m-d-H-i-s");
        $this->tec->zip("./uploads/", './files/backups/', $name);
        
        $name_notas = 'tnet_backup_notas_autorizadas_' . date("Y-m-d-H-i-s");
        $this->tec->zip("./api-nfe/gerador/xml/autorizadas/", './files/backups_notas', $name_notas);
		
		$name_notas2 = 'tnet_backup_notas_canceladas_' . date("Y-m-d-H-i-s");
        $this->tec->zip("./api-nfe/gerador/xml/canceladas/", './files/backups_notas', $name_notas2);
        
		 $this->session->set_flashdata('message', 'Backup do banco de dados, arquivos e notas criado com sucesso');
        redirect("settings/backups");
        
    }
    
    function download_database($dbfile)
    {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->load->helper('download');
        force_download('./files/backups/' . $dbfile . '.db', NULL);
        exit();
    }

    function download_backup($zipfile)
    {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->load->helper('download');
        force_download('./files/backups/' . $zipfile . '.zip', NULL);
        exit();
    }
	
	 function download_backup_notas($zipfile)
    {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->load->helper('download');
        force_download('./files/backups_notas/' . $zipfile . '.zip', NULL);
        exit();
    }
	
	
	function restore()
    {
		
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
		
        $bc = array(array('link' => site_url('settings'), 'page' => lang('settings')), array('link' => site_url('settings/backups'), 'page' => lang('backups')));
        $meta = array('page_title' => lang('restore'), 'bc' => $bc);
        $this->page_construct('settings/restore', $this->data, $meta);
    }
	
	function restore_database()
    {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
		
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
		
		
		 if ($_FILES['userfile']['size'] > 0) {

			$this->load->library('upload');

			$config['upload_path'] = 'files/database/';
			$config['allowed_types'] = '*';
			$config['file_name'] = 'tnet.db';
			$config['overwrite'] = TRUE;
			$config['encrypt_name'] = FALSE;
			
			$this->upload->initialize($config);

			if (!$this->upload->do_upload()) {
				$error = $this->upload->display_errors();
				$this->session->set_flashdata('error', $error);
				redirect("settings/restore");
			}else{
				$this->session->set_flashdata('message', 'Banco de dados restaurado, entre novamente.');
				redirect("auth/logout");
				
			}

		}
		
		redirect("settings/restore");
       
    }

    function restore_backup()
    {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }

	    if ($_FILES['userfile']['size'] > 0) {
			
			$this->tec->unzip($_FILES['userfile']['tmp_name'], './');
			$this->session->set_flashdata('message', 'Arquivos restaurados com sucesso!');
			redirect("settings/restore");
			
		}
		
		redirect("settings/restore");
		
    }
	
	function restore_backup_notas()
    {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }

	    if ($_FILES['userfile']['size'] > 0) {
			
			$this->tec->unzip($_FILES['userfile']['tmp_name'], './');
			$this->session->set_flashdata('message', 'Notas restauradas com sucesso!');
			redirect("settings/restore");
			
		}
		
		redirect("settings/restore");
		
    }
    
    function delete_database($dbfile)
    {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        unlink('./files/backups/' . $dbfile . '.db');
        $this->session->set_flashdata('messgae', lang('db_deleted'));
        redirect("settings/backups");
    }

    function delete_backup($zipfile)
    {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        unlink('./files/backups/' . $zipfile . '.zip');
        $this->session->set_flashdata('messgae', lang('backup_deleted'));
        redirect("settings/backups");
    }
	
	function delete_backup_notas($zipfile)
    {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        unlink('./files/backups_notas/' . $zipfile . '.zip');
        $this->session->set_flashdata('messgae', lang('backup_deleted'));
        redirect("settings/backups");
    }

}