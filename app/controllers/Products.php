<?php defined('BASEPATH') OR exit('No direct script access allowed');
setlocale(LC_MONETARY, 'pt_BR');

class Products extends MY_Controller
{

    function __construct() {
        parent::__construct();


        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->library('form_validation');
        $this->load->model('products_model');
        $this->load->model('categories_model');
        $this->load->model('suppliers_model');
    }
   
  
    function index() {
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('products');
        $bc = array(array('link' => '#', 'page' => lang('products')));
        $meta = array('page_title' => lang('products'), 'bc' => $bc);
        $this->page_construct('products/index', $this->data, $meta);
    }

    function impostos() {
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = "Impostos";
        $bc = array(array('link' => '#', 'page' => "Impostos"));
        $meta = array('page_title' => "Impostos", 'bc' => $bc);
        $this->page_construct('products/impostos', $this->data, $meta);

    }

    function get_impostos() {

        $this->load->library('datatables');
        $this->datatables->select($this->db->dbprefix('impostos').".id as code, ".$this->db->dbprefix('impostos').".nome as name, ".$this->db->dbprefix('impostos').".tipo", FALSE)
        ->from('impostos');

        $this->datatables->add_column("Actions", "<div class='text-center'><div class='btn-group'><a href='".site_url('products/impostos_add_edit/$1?editarImpostos=1')."' title='" . lang("view") . "/" . lang("edit") . "' class='tip btn btn-primary btn-xs'><i class='fa fa-edit'></i></a></div></div>", "code, name");

        echo $this->datatables->generate();

    }

    function save_impostos() {

        $data = array();
        $id = $this->input->get('id', TRUE);
        $data["nome"] = $this->input->get('nome', TRUE);
        $data["tipo"] = $this->input->get('tipo', TRUE);
        $data["regras"] = $this->input->get('regras', TRUE);

        $d = $this->products_model->updateImpostos($id, $data);
        if($d){
            echo json_encode(array("status" => true, "id" => $id)); die;
        } else{
            echo json_encode(array("status" => false, "id" => $id)); die;
        }
    }

    function insert_impostos() {

        $data = array();
        $data["nome"] = $this->input->get('nome', TRUE);
        $data["tipo"] = $this->input->get('tipo', TRUE);
        $data["regras"] = $this->input->get('regras', TRUE);

        if($this->products_model->getImpostosbyNome($data["nome"])){
            echo json_encode(array("status" => "ya_existe")); die;
        }

        $d = $this->products_model->insertImpostos($data);
        if($d){
            echo json_encode(array("status" => true, "id" => $d)); die;
        } else{
            echo json_encode(array("status" => false, "id" => $d)); die;
        }
    }


    function get_products() {

        $this->load->library('datatables');
        if ($this->Admin) {
            $this->datatables->select($this->db->dbprefix('products').".id as pid, ".$this->db->dbprefix('products').".image as image, ".$this->db->dbprefix('products').".code as code, ".$this->db->dbprefix('products').".name as pname, type, ".$this->db->dbprefix('categories').".name as cname, quantity, cost, (cost * quantity) as total_cost, price, (price * quantity) as total_price,barcode_symbology", FALSE);
        } else {
            $this->datatables->select($this->db->dbprefix('products').".id as pid, ".$this->db->dbprefix('products').".image as image, ".$this->db->dbprefix('products').".code as code, ".$this->db->dbprefix('products').".name as pname, type, ".$this->db->dbprefix('categories').".name as cname, quantity, price, barcode_symbology", FALSE);
        }

        $this->datatables->join('categories', 'categories.id=products.category_id', 'left')
        ->from('products')
        ->group_by('products.id');

        $this->datatables->add_column("Actions", "<div class='text-center'><div class='btn-group'><a href='".site_url('products/view/$1')."' title='" . lang("view") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'><i class='fa fa-file-text-o'></i></a><!--<a onclick=\"window.open('".site_url('products/single_label/$1')."', 'pos_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;\" href='#' title='".lang('print_labels')."' class='tip btn btn-default btn-xs'><i class='fa fa-print'></i></a>--> <a id='$4 ($3)' href='" . site_url('products/gen_barcode/$3/$5') . "' title='" . lang("view_barcode") . "' class='barcode tip btn btn-primary btn-xs'><i class='fa fa-barcode'></i></a> <!--<a class='tip image btn btn-primary btn-xs' id='$4 ($3)' href='" . base_url('uploads/$2') . "' title='" . lang("view_image") . "'><i class='fa fa-picture-o'></i></a>--> <a href='" . site_url('products/edit/$1') . "' title='" . lang("edit_product") . "' class='tip btn btn-warning btn-xs'><i class='fa fa-edit'></i></a> <a href='" . site_url('products/delete/$1') . "' onClick=\"return confirm('" . lang('alert_x_product') . "')\" title='" . lang("delete_product") . "' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>", "pid, image, code, pname, barcode_symbology");

        $this->datatables->unset_column('pid')->unset_column('barcode_symbology');
        echo $this->datatables->generate();

    }

    function impostos_add_edit($id = NULL) {

        $this->data['impostos'] = $this->products_model->getImpostos($id);

        $this->data['page_title'] = "Impostos";
        $bc = array(array('link' => '#', 'page' => "Impostos"));
        $meta = array('page_title' => "Impostos", 'bc' => $bc);
        $this->page_construct('products/impostos_add_edit', $this->data, $meta);

    }

    function getProductbyID()
	{
		$id = $this->input->get('id', TRUE);

		$product = $this->site->getProductByID($id);
		if ($product) {
			echo json_encode($product);
		} else {
			echo json_encode(array());
		}
	}

    function view($id = NULL) {
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $product = $this->site->getProductByID($id);
        $this->data['product'] = $product;
        $this->data['category'] = $this->site->getCategoryByID($product->category_id);
        $this->data['combo_items'] = $product->type == 'combo' ? $this->products_model->getComboItemsByPID($id) : NULL;
        $this->load->view($this->theme.'products/view', $this->data);

    }

    function barcode($product_code = NULL) {
        if ($this->input->get('code')) {
            $product_code = $this->input->get('code');
        }

        $data['product_details'] = $this->products_model->getProductByCode($product_code);
        $data['img'] = "<img src='" . base_url() . "index.php?products/gen_barcode&code={$product_code}' alt='{$product_code}' />";
        $this->load->view('barcode', $data);

    }

    function product_barcode($product_code = NULL, $bcs = 'code39', $height = 60) {
        if ($this->input->get('code')) {
            $product_code = $this->input->get('code');
        }
        return "<img src='" . base_url() . "products/gen_barcode/{$product_code}/{$bcs}/{$height}' alt='{$product_code}' />";
    }

    function gen_barcode($product_code = NULL, $bcs = 'code39', $height = 60, $text = 1) {
        $drawText = ($text != 1) ? FALSE : TRUE;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $product_code, 'barHeight' => $height, 'drawText' => $drawText);
        $rendererOptions = array('imageType' => 'png', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle');
        $imageResource = Zend_Barcode::render($bcs, 'image', $barcodeOptions, $rendererOptions);
        return $imageResource;
    }


    function print_barcodes() {
        $this->load->library('pagination');

        $per_page = $this->input->get('per_page') ? $this->input->get('per_page') : 0;

        $config['base_url'] = site_url('products/print_barcodes');
        $config['total_rows'] = $this->products_model->products_count();
        $config['per_page'] = 16;
        $config['num_links'] = 5;

        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);
      // configura impressar de barcode
        $margemim = "160px";
        $products = $this->products_model->fetch_products($config['per_page'], $per_page);
        $r = 1;
        $html = "";
        $html .= '<table class="table" style="margin:0px!important">
        <tbody><tr>';
        foreach ($products as $pr) {
             if((($r-1) % 3) == 0){
                $html .= '</tr><tr>';
              
            }
            $html .= '<td style="font-size:12px; width:160px;  border: 0px!important; margin-left:20px;padding: 0px!important;"><br><strong style="font-size:12px;">' . $this->Settings->site_name . '</strong><br><strong style="font-size:10px;">' . $pr->name . '</strong><br>' . $this->product_barcode($pr->code, $pr->barcode_symbology, 20) . '<br><span class="price"><b>' .$this->Settings->currency_prefix. ' ' . number_format((float)$pr->price, 2, ",", ".") . '</b></span><br><span style="font-size:10px;">Prazo de Troca: 15 dias.<br>_____/_____/______</span><br><br><strong style="font-size:10px;">' . $pr->name . '</strong><br><span class="price"><b>' .$this->Settings->currency_prefix. ' ' . number_format((float)$pr->price, 2, ",", ".") . '</b></span><br><div style="margin-top:7px"><strong style="font-size:10px;">' . $pr->name . '</strong><br><span class="price"><b>' .$this->Settings->currency_prefix. ' ' . number_format((float)$pr->price, 2, ",", ".") . '</b></span></div></td>';
            $r++;
        }
        $html .= '</tr></tbody>
        </table>';

        $this->data['html'] = $html;
        $this->data['page_title'] = lang("print_barcodes");
        $this->load->view($this->theme.'products/print_barcodes', $this->data);

    }

    function print_labels() {
        $this->load->library('pagination');

        $per_page = $this->input->get('per_page') ? $this->input->get('per_page') : 10;

        $config['base_url'] = site_url('products/print_labels');
        $config['total_rows'] = $this->products_model->products_count();
        $config['per_page'] = 10;
        $config['num_links'] = 5;

        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);

        $products = $this->products_model->fetch_products($config['per_page'], $per_page);

        $html = "";

        foreach ($products as $pr) {
            $html .= '<div class="labels"><strong>' . $pr->name . '</strong><br>' . $this->product_barcode($pr->code, $pr->barcode_symbology, 25) . '<br><span class="price">' .$this->Settings->currency_prefix. ' ' . number_format((float)$pr->price, 2, ",", ".") . '</span></div>';
        }

        $this->data['html'] = $html;
        $this->data['page_title'] = lang("print_labels");
        $this->load->view($this->theme.'products/print_labels', $this->data);

    }

    function single_barcode($product_id = NULL)
    {

        $product = $this->site->getProductByID($product_id);
        $quantidade = $product->quantity;
		if($quantidade>0 && $quantidade<=3){$imprime = 3;}elseif($quantidade>=4 && $quantidade<=6){$imprime = 6;}elseif($quantidade>=7 && $quantidade<=9){$imprime = 9;}elseif($quantidade>=10 && $quantidade<=12){$imprime = 12;}elseif($quantidade>=13 && $quantidade<=15){$imprime = 15;}elseif($quantidade>=16 && $quantidade<=18){$imprime = 18;}elseif($quantidade>=19 && $quantidade<=21){$imprime = 21;}elseif($quantidade>=22 && $quantidade<=24){$imprime = 24;}elseif($quantidade>=25 && $quantidade<=27){$imprime = 27;}elseif($quantidade>=28 && $quantidade<=30){$imprime = 30;}
      
      
        $html = "";
        $html .= '<table class="table" style="margin:0px!important">
        <tbody><tr>';
       if($product->quantity == 0) {
                
         
       } else {
        
            for ($r = 1; $r <= 3; $r++) {
            if((($r-1) % 3) == 0){
                $html .= '</tr><tr>';
              
            }
             $html .= '<td style="font-size:12px; width:160px;  border: 0px!important; margin-left:20px;padding: 0px!important;"><br><strong style="font-size:12px;">' . $this->Settings->site_name . '</strong><br><strong style="font-size:10px;">' . $product->name . '</strong><br>' . $this->product_barcode($product->code, $product->barcode_symbology, 20) . '<br><span class="price"><b>' .$this->Settings->currency_prefix. ' </span><span> ' . number_format((float)$product->price, 2, ",", ".") . '</b></span><br><span style="font-size:10px;">Prazo de Troca: 15 dias.<br>_____/_____/______</span><br><br><strong style="font-size:10px;">' . $product->name . '</strong><br><span class="price"><b>' .$this->Settings->currency_prefix. ' ' . number_format((float)$product->price, 2, ",", ".") . '</b></span><br><div style="margin-top:7px"><strong style="font-size:10px;">' . $product->name . '</strong><br><span class="price"><b>' .$this->Settings->currency_prefix. ' ' . number_format((float)$product->price, 2, ",", ".") . '</b></span></div></td>';
         }
          
        } 
        $html .= '</tr></tbody>
        </table>';

        $this->data['html'] = $html;
        $this->data['page_title'] = lang("print_barcodes");
        $this->load->view($this->theme . 'products/single_barcode', $this->data);
    }

    function single_label($product_id = NULL, $warehouse_id = NULL)
    {

        $product = $this->site->getProductByID($product_id);
        $html = "";
        if($product->quantity > 0) {
            for ($r = 1; $r <= $product->quantity; $r++) {
                $html .= '<div class="labels"><strong>' . $product->name . '</strong><br>' . $this->product_barcode($product->code, $product->barcode_symbology, 25) . ' <br><span class="price">'.lang('price') .': ' .$this->Settings->currency_prefix. ' ' .  number_format((float)$product->price, 2, ",", ".") . '</span></div>';
            }
        } else {
            for ($r = 1; $r <= 10; $r++) {
                $html .= '<div class="labels"><strong>' . $product->name . '</strong><br>' . $this->product_barcode($product->code, $product->barcode_symbology, 25) . ' <br><span class="price">'.lang('price') .': ' .$this->Settings->currency_prefix. ' ' .  number_format((float)$product->price, 2, ",", ".") . '</span></div>';
            }
        }
        $this->data['html'] = $html;
        $this->data['page_title'] = lang("barcode_label");
        $this->load->view($this->theme . 'products/single_label', $this->data);

    }


    function add() {
        
        if (!$this->Admin) {
            //$this->session->set_flashdata('error', lang('access_denied'));
            //redirect('products');
        }

        $this->form_validation->set_rules('code', lang("product_code"), 'trim|is_unique[products.code]|min_length[1]|max_length[50]|required|alpha_dash');
        $this->form_validation->set_rules('name', lang("product_name"), 'required');
        $this->form_validation->set_rules('category', lang("category"), 'required');
        $this->form_validation->set_rules('price', lang("product_price"), 'required');
        //$this->form_validation->set_rules('cost', lang("product_cost"), 'required');
        //$this->form_validation->set_rules('product_tax', lang("product_tax"), 'required|is_numeric');
        //$this->form_validation->set_rules('quantity', lang("quantity"), 'is_numeric');
        //$this->form_validation->set_rules('alert_quantity', lang("alert_quantity"), 'is_numeric');

        if ($this->form_validation->run() == true) {
          
            $data = array(
                'type' => $this->input->post('type'),
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'category_id' => $this->input->post('category'),
                'price' => $this->tec->formatDolar($this->input->post('price')),
                'cost' =>  $this->tec->formatDolar($this->input->post('cost')),
                'tax' => $this->input->post('product_tax'),
                'tax_method' => $this->input->post('tax_method'),
                'quantity' => $this->tec->formatDolar($this->input->post('quantity'), 3),
                'alert_quantity' => $this->input->post('alert_quantity'),
                'details' => $this->input->post('details'),
                'unit' => $this->input->post('unit'),
                'ncm' => $this->input->post('ncm'),
                'cest' => $this->input->post('cest'),
                'origem' => $this->input->post('origem'),
                'cfop' => $this->input->post('cfop'),
                'cfop2' => $this->input->post('cfop2'),
                'imposto' => $this->input->post('imposto'),
                'comissao' => ($this->input->post('comissao')!="")?$this->tec->formatDolar($this->input->post('comissao')):0,
                'composicao_codigo' => $this->input->post('composicao_codigo'),
                'composicao_quantidade' => $this->input->post('composicao_quantidade'),
            );

            if ($this->input->post('type') == 'combo') {
                $c = sizeof($_POST['combo_item_code']) - 1;
                for ($r = 0; $r <= $c; $r++) {
                    if (isset($_POST['combo_item_code'][$r]) && isset($_POST['combo_item_quantity'][$r])) {
                        $items[] = array(
                            'item_code' => $_POST['combo_item_code'][$r],
                            'quantity' => $_POST['combo_item_quantity'][$r]
                        );
                    }
                }
            } else {
                $items = array();
            }

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = '1500';
                $config['max_width'] = '1800';
                $config['max_height'] = '1800';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    //redirect("products/add", 'refresh'); // don't need to reload.
                }else{

                    $photo = $this->upload->file_name;
                    $data['image'] = $photo;
    
                    $this->load->library('image_lib');
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = 'uploads/' . $photo;
                    $config['new_image'] = 'uploads/thumbs/' . $photo;
                    $config['maintain_ratio'] = TRUE;
                    $config['width'] = 110;
                    $config['height'] = 110;
    
                    $this->image_lib->clear();
                    $this->image_lib->initialize($config);
    
                    if (!$this->image_lib->resize()) {
                        $this->session->set_flashdata('error', $this->image_lib->display_errors());
                        //redirect("products/add"); // ???
                    }
                }

            }
           //$this->tec->print_arrays($data, $items);
        }

        if ($this->form_validation->run() == true && $this->products_model->addProduct($data, $items)) {

            $this->session->set_flashdata('message', lang("product_added"));
              if($this->input->post('isframe')=="1"){
                redirect('products/add?isframe=1');
            }else{
                redirect('products');
            }

        } else {
            
            
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['impostos'] = $this->products_model->getImpostos();
            //$this->data['nextcode'] = $this->products_model->getLastProdCode();

            $this->data['page_title'] = lang('add_product');
            $bc = array(array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('add_product')));
            $meta = array('page_title' => lang('add_product'), 'bc' => $bc);
            $this->page_construct('products/add', $this->data, $meta);

        }
    }

    function edit($id = NULL) {
        
        if (!$this->Admin) {
           // $this->session->set_flashdata('error', lang('access_denied'));
            //redirect('products');
        }
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $pr_details = $this->site->getProductByID($id);
        if ($this->input->post('code') != $pr_details->code) {
            $this->form_validation->set_rules('code', lang("product_code"), 'is_unique[products.code]');
        }
        $this->form_validation->set_rules('code', lang("product_code"), 'trim|min_length[1]|max_length[50]|required|alpha_dash');
        $this->form_validation->set_rules('name', lang("product_name"), 'required');
        $this->form_validation->set_rules('category', lang("category"), 'required');
        $this->form_validation->set_rules('price', lang("product_price"), 'required');
        //$this->form_validation->set_rules('cost', lang("product_cost"), 'required');
        //$this->form_validation->set_rules('product_tax', lang("product_tax"), 'required|is_numeric');
        //$this->form_validation->set_rules('quantity', lang("quantity"), 'is_numeric');
        //$this->form_validation->set_rules('alert_quantity', lang("alert_quantity"), 'is_numeric');

        if ($this->form_validation->run() == true) {
            

            $data = array(
                'type' => $this->input->post('type'),
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'category_id' => $this->input->post('category'),
                'price' => $this->tec->formatDolar($this->input->post('price')),
                'cost' => $this->tec->formatDolar($this->input->post('cost')),
                'tax' => $this->input->post('product_tax'),
                'tax_method' => $this->input->post('tax_method'),
                'quantity' => $this->tec->formatDolar($this->input->post('quantity'), 3),
                'alert_quantity' => $this->input->post('alert_quantity'),
                'details' => $this->input->post('details'),
                'unit' => $this->input->post('unit'),
                'ncm' => $this->input->post('ncm'),
                'cest' => $this->input->post('cest'),
                'origem' => $this->input->post('origem'),
                'imposto' => $this->input->post('imposto'),
                'cfop' => $this->input->post('cfop'),
                'cfop2' => $this->input->post('cfop2'),
                 'comissao' => ($this->input->post('comissao')!="")?$this->tec->formatDolar($this->input->post('comissao')):0,
                 'composicao_codigo' => $this->input->post('composicao_codigo'),
                 'composicao_quantidade' => $this->input->post('composicao_quantidade'),
                );

            if ($this->input->post('type') == 'combo') {
                $c = sizeof($_POST['combo_item_code']) - 1;
                for ($r = 0; $r <= $c; $r++) {
                    if (isset($_POST['combo_item_code'][$r]) && isset($_POST['combo_item_quantity'][$r])) {
                        $items[] = array(
                            'item_code' => $_POST['combo_item_code'][$r],
                            'quantity' => $_POST['combo_item_quantity'][$r]
                        );
                    }
                }
            } else {
                $items = array();
            }

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = '1500';
                $config['max_width'] = '1800';
                $config['max_height'] = '1800';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    //redirect("products/edit/" . $id);
                    $photo = NULL;
                }else{

                    $photo = $this->upload->file_name;

                    $this->load->helper('file');
                    $this->load->library('image_lib');
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = 'uploads/' . $photo;
                    $config['new_image'] = 'uploads/thumbs/' . $photo;
                    $config['maintain_ratio'] = TRUE;
                    $config['width'] = 310;
                    $config['height'] = 310;

                    $this->image_lib->clear();
                    $this->image_lib->initialize($config);

                    if (!$this->image_lib->resize()) {
                        $this->session->set_flashdata('error', $this->image_lib->display_errors());
                        //redirect("products/edit/" . $id);
                    }
                }

            } else {
                $photo = NULL;
            }
            //$this->tec->print_arrays($data, $items);
        }
       
        if ($this->form_validation->run() == true && $this->products_model->updateProduct($id, $data, $items, $photo)) {

            $this->session->set_flashdata('message', lang("product_updated"));
            if($this->input->post('isframe')=="1"){
                redirect('products/add?isframe=1');
            }else{
                redirect('products');
            }

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $product = $this->site->getProductByID($id);
            if($product->type == 'combo') {
                $combo_items = $this->products_model->getComboItemsByPID($id);
                foreach ($combo_items as $combo_item) {
                    $cpr = $this->site->getProductByID($combo_item->id);
                    $cpr->qty = $combo_item->qty;
                    $items[] = array('id' => $cpr->id, 'row' => $cpr);
                }
                $this->data['items'] = $items;
            }
            $this->data['product'] = $product;
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['impostos'] = $this->products_model->getImpostos();
            $this->data['page_title'] = lang('edit_product');
            $bc = array(array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('edit_product')));
            $meta = array('page_title' => lang('edit_product'), 'bc' => $bc);
            $this->page_construct('products/edit', $this->data, $meta);

        }
    }
    
    function tirarAcentos($string){
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
    }

    function import() {

        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('products');
        }
        
        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('warning', lang("disabled_in_demo"));
                redirect('pos');
            }

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');

                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = 'xlsx';
                $config['max_size'] = '100000000';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("products/import");
                }

                $csv = $this->upload->file_name;
    
                $arrResult = array();
                
                 function formatarValores($number, $decimais = 2){

                    try {
                        if(is_numeric($number)==true){ // já está em formato dollar
                            return $number;
                        }
                        return number_format(str_replace(",", ".", str_replace(".", "", $number)), $decimais, '.', '');
                   } catch (\Throwable $th) {
                        return 0;
                   }

                }
                
                /*
                 
                function detectDelimiter($csvFile)
                {
                    $delimiters = [";" => 0, "," => 0, "\t" => 0, "|" => 0];

                    $handle = fopen($csvFile, "r");
                    $firstLine = fgets($handle);
                    fclose($handle); 
                    foreach ($delimiters as $delimiter => &$count) {
                        $count = count(str_getcsv($firstLine, $delimiter));
                    }

                    return array_search(max($delimiters), $delimiters);
                }
                

                $delimiter = (detectDelimiter("uploads/" . $csv));
                $handle = fopen("uploads/" . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 10000000, ",")) !== FALSE) {
                        $arrResult[] = (count($row) == 1)? explode($delimiter, $row[0]) : $row;
                    }
                    fclose($handle);
                }
                array_shift($arrResult);

                $keys = array('codigo', 'nome', 'categoria_id', 'tipo', 'custo', 'preço', 'quantidade', 'unidade', 'origem', 'ncm', 'cest', 'cfop');

                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                if (sizeof($final) > 10000000) {
                    $this->session->set_flashdata('error', lang("more_than_allowed"));
                    redirect("products/import");
                }
                */
                
                
                $count = 0;
                $count_colunas = 0;
                $campos_encontrados = array();
                $campos = array(
                    "codigo",
                    "nome",
                    "preço",
                    "custo",
                    "estoque",
                    "categoria",
                    "ncm",
                    "origem",
                    "cfop",
                    "tipo",
					"cest",
					"imposto"
                );
                
                require_once __DIR__.'/../../lib-local/spout/src/Spout/Autoloader/autoload.php';
                $fdestino = __DIR__."/../../uploads/".$this->upload->file_name;
                $reader = Box\Spout\Reader\Common\Creator\ReaderEntityFactory::createReaderFromFile($fdestino);
                $reader->open($fdestino);
                
                foreach ($reader->getSheetIterator() as $sheet) {
                     
                      foreach ($sheet->getRowIterator() as $row) {
    
                        $fields_linha = array();
                        $cells = $row->getCells();
                        
                        if($count==0){

                            foreach ($cells as $k => $v) {
                                $v = mb_strtolower($v->getValue());
                                if( in_array($v, $campos) ){
                                    $campos_encontrados[$k] = $v;
                                }
                            }
    
                        } else {

                            foreach ($cells as $k => $v) {
        
                                if(!empty($campos_encontrados[$k])){
                                    $fields_linha[$campos_encontrados[$k]] = $v->getValue();
                                }

                            }
    
                        }
                        

                        $arrResult[] = $fields_linha;
                        $count++;
                        
                    }

                }
                
                $reader->close();
                
                if(count($arrResult) == 0){
                    $this->session->set_flashdata('error', "Não foi possível reconheçer os dados no Excel (1)");
                    redirect('products/import');
                }
                
                $prodsInsert = array();
                $x = 0;
                $countProds = 0;
                $countImported = 0;
                $countUpdated = 0;
                $countNotImported = 0;
    
                foreach ($arrResult as $csv_pr) {
                    
                    $x++;
                    $insert = true;
                    $product_exist = false;

                    if (empty($csv_pr['codigo']) || $this->products_model->getProductByCode(trim($csv_pr['codigo']))) {
                        $insert = false;
                    }
            
                    if($insert==true){

                        if(!empty($csv_pr['categoria'])){
                            if(!$category = $this->site->getCategoryByName($csv_pr['categoria'])) {
                                $code_new = rand(10000000,99999999);
                                $dataCat = array('code' => $code_new, 'name' => $csv_pr['categoria']);
                                try {
                                    if($addID = $this->categories_model->addCategory($dataCat)){
                                         $category = (object) array("id" => $addID);
                                    }
                                    //$category = $this->site->getCategoryByCode($code_new);
                                } catch (\Throwable $th) {
                                    //throw $th;
                                }
                            }
                        }

                        if(strtolower($csv_pr['tipo'])=="serviço"){
                            $prodtype = 'service';
                        } else {
                            $prodtype = 'standard';
                        }

                        $data[] = array(
                            'type' => $prodtype,
                            'code' => trim($csv_pr['codigo']),
                            'name' => $this->tirarAcentos(trim($csv_pr['nome'])),
                            'category_id' => (!empty(@$category->id))? $category->id: 1,
                            'cost' => formatarValores($csv_pr['custo']),
                            'tax' => 0,
                            'price' => formatarValores($csv_pr['preço']),
                            'quantity' => formatarValores($csv_pr['estoque']),
                            'unit' => (!empty($csv_pr['unidade']))? strtoupper($csv_pr['unidade']): "UN",
                            'origem' => (!empty($csv_pr['origem']) && is_numeric($csv_pr['origem']))? $csv_pr['origem']:0, 
                            'ncm' => str_replace(".", "", $csv_pr['ncm']), 
                            'cest' => str_replace(".", "", $csv_pr['cest']), 
                            'cfop' => (!empty($csv_pr['cfop']))? str_replace(".", "", $csv_pr['cfop']): "5102",
                            'barcode_symbology' => 'code39',
                            'imposto' => ($csv_pr['imposto']!="")? $csv_pr['imposto'] : ((str_replace(".", "", $csv_pr['cfop'])=="5405")? '3':'1')
                        );

                    }
                   
                }
                
                
               //var_dump($data);
               //die();
               
               if(count($data)==0){
                    $this->session->set_flashdata('error', "Não foi possível adicionar produtos do excel, verifique se eles já existem no sistema.");
                    redirect('products/import');
               }
               
            }

        }

        if($this->form_validation->run() == true && $this->products_model->add_products($data) == true) {

            $this->session->set_flashdata('message', lang("products_added"));

            if($this->input->post('isframe')=="1"){
                redirect('products/add?isframe=1');
            }else{
                redirect('products');
            }

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['page_title'] = lang('import_products');
            $bc = array(array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('import_products')));
            $meta = array('page_title' => lang('import_products'), 'bc' => $bc);
            $this->page_construct('products/import', $this->data, $meta);

        }
    }

    function importar_xml(){

        function tagValue($node,$tag){
            try {
                return $node->getElementsByTagName("$tag")->item(0)->nodeValue;
            } catch (\Throwable $th) {
                return "";
            }
           
        }

        // XML DA NOTA
        if ($_FILES['nfxml']['size'] > 0) {

            $ext = strtolower(pathinfo(basename($_FILES["nfxml"]["name"]), PATHINFO_EXTENSION));
            $xmlname = md5(date("ymddhiss").date("ymddhiss")).sha1(date("ymddhiss").date("ymddhiss")).".".$ext;

            if($ext != "xml") {
                $this->session->set_flashdata('error', "Formato invalido, use apenas apenas arquivos (.xml)");
                redirect('products/importar_xml');
            }else{
               $docxml = file_get_contents($_FILES["nfxml"]["tmp_name"]);

            }

              // não temos nenhum dado
            if($docxml==""){

                $erro_importar = "Erro ao encontrar os dados no XML, verifique se está usando um XML válido.";

                $this->session->set_flashdata('error', $erro_importar);
                redirect("products/importar_xml");

            }


            $dados = array();

            // leitura do xml
            try {
                $doc = new DOMDocument();
                $doc->preservWhiteSpace = FALSE; //elimina espaços em branco
                $doc->formatOutput = FALSE;
                $doc->loadXML($docxml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            } catch (\Exception $th) {
                //echo $th->getMessage();
                $this->session->set_flashdata('error', "Erro ao realizar tente novamente");
                redirect("products/importar_xml");
            }
           
            $node = $doc->getElementsByTagName('infNFe');

            // Tag det dos itens unitários:
            $det=$doc->getElementsByTagName('det');
            $itens=array();
            $NFProdutos = array();
            for ($i = 0; $i < $det->length; $i++) {
                $item=$det->item($i);

                /*
                $s=array();
                $s['ean']=tagValue($item,"cEAN");
                $s['quantidade']=tagValue($item,"qCom");
                $s['valorTotal']=tagValue($item,"vProd");
                $s['valoricms']=tagValue($item,"vICMS");
                $s['valoripi']=tagValue($item,"vIPI");
                */

                $codigoProduto = (trim(tagValue($item,"cEAN"))!="" && tagValue($item,"cEAN") != "SEM GTIN")? trim(tagValue($item,"cEAN")) : trim(tagValue($item,"cProd"));
                $quantidadeProduto = (trim(tagValue($item,"qTrib"))!="") ? trim(tagValue($item,"qTrib")) : 0;
                $cfopProduto = trim(tagValue($item,"CFOP"));
                if($cfopProduto!=""){
                    $cfopProduto = substr($cfopProduto, 1);
                    $cfopProduto = "5".$cfopProduto; // adicionamos o produto com cfop do entrado
                }

                $fator = $this->input->post('fator');
                if($fator!=""){ $fator = $this->tec->formatDolar(str_replace("%", "", $fator)); } else{ $fator = 0; }
                $custo = trim(tagValue($item,"vUnTrib"));
                $precoprod = $custo + (($custo/100) * $fator);
                
                $NFProdutos[] = array(
                    'type' => 'standard',
                    'code' => $codigoProduto,
                    'name' => trim(tagValue($item,"xProd")),
                    'category_id' => 1,
                    'cost' => trim(tagValue($item,"vUnTrib")), // es estiver comprando, o valor do custo é o mesmo do produto
                    'tax' => 0,
                    'price' => $precoprod,
                    'quantity' => $quantidadeProduto,
                    'unit' => trim(tagValue($item,"uCom"))!="" ? strtoupper(trim(tagValue($item,"uCom"))) : "UN",
                    'origem' => 0, 
                    'ncm' => trim(tagValue($item,"NCM")), 
                    'cfop' => $cfopProduto,
                    'cest' => "", 
                    'barcode_symbology' => 'ean13',
                    'imposto' => '1'
                );

            }

            // ADICIONAR PRODUTOS:
            foreach($NFProdutos as $prod){
                $p = $this->products_model->getProductByCode($prod["code"]);
                if($p==false){
                   $this->products_model->addProduct($prod);
                }else{
                   $this->products_model->updateProduct($p->id, array("quantity" => ($p->quantity + $prod["quantity"]), 'cost' => $prod["cost"], "price" => $prod["price"], 'barcode_symbology' => $prod["barcode_symbology"]));
                }
            }
            
            // Emitente  / fornecedor:
            $emi=$doc->getElementsByTagName('emit')->item(0);
            $cnpj_emitente=tagValue($emi,"CNPJ");
            $cpf_emitente=trim(tagValue($emi,"CPF"));
            $c2=substr($c1,0,2).".".substr($c1,2,3).".".substr($c1,5,3)."/".substr($c1,8,4)."-".substr($c1,12,2);

            if($cnpj_emitente!=NULL){ // cnpj
				$tipoCadastro_emitente = "1";
                $documentoCadastro_emitente = $cnpj_emitente;
                $inscricaoEstadual_emitente = tagValue($emi,"IE");
            }elseif($cpf_emitente!=NULL){ // cnpj
				$tipoCadastro_emitente = "2";
                $documentoCadastro_emitente = $cpf_emitente;
                $inscricaoEstadual_emitente = "";
			}

            $NFEmitente = array();
            if($documentoCadastro_emitente!=""){
                $NFEmitente = array(
                    'name' => trim(str_replace("'", "", tagValue($emi,"xNome"))) . ((tagValue($emi,"xFant")!="")? " -". trim(tagValue($emi,"xFant")) : ""),
                    'email' => (trim(tagValue($emi,"email"))!="") ? trim(tagValue($emi,"email")) : "",
                    'phone' => trim(tagValue($emi,"fone")),
                    'cf1' => trim($documentoCadastro_emitente),
                    'cf2' => trim($inscricaoEstadual_emitente),
                );
            }
            
            
            // ADICIONAR EMITENTE COMO FORNECEDOR
            $NFEmitente_count = 0;
            if(!empty($NFEmitente)){
                $supplierExist = $this->suppliers_model->getSupplierByDoc($NFEmitente['cf1']);
                if($supplierExist == false){ // não existe
                      if($this->suppliers_model->addSupplier($NFEmitente)){
                        $NFEmitente_count++;
                      }
                }else{
                    $NFEmitente = array();
                }
                
            }

            $this->session->set_flashdata('message', "Importação realizada com sucesso! Produtos importados: ".count($NFProdutos)." / Fornecedor importado: ".$NFEmitente_count."");
            redirect("products/importar_xml");

        } else {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $bc = array(array('link' => '#', 'page' => "Importar Produtos / Fornecedor com XML"));
            $meta = array('page_title' => "Importar Produtos / Fornecedor com XML", 'bc' => $bc);
            $this->page_construct('products/import_xml', $this->data, $meta);

        }

    }

    function delete($id = NULL) {

        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('products');
        }

        if ($this->products_model->deleteProduct($id)) {
            $this->session->set_flashdata('message', lang("product_deleted"));
            redirect('products');
        }

    }

    function suggestions()
    {
         $term = $this->input->get('term', TRUE);

         $rows = $this->products_model->getProductNames($term);
         if ($rows) {
             foreach ($rows as $row) {
                 $row->qty = 1;
                 $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row);
             }
             echo json_encode($pr);
         } else {
             echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
         }
     }

    function suggestions_nfe()
    {
         $term = $this->input->get('term', TRUE);

         $rows = $this->products_model->getProductNames($term);
         if ($rows) {
             foreach ($rows as $row) {
                 $pr[] = $row;
             }
             echo json_encode($pr);
         } else {
             echo "";
         }
     }

    function municipios()
    {

        $term = $this->input->get('term', TRUE);

        $rows = $this->products_model->getMunicipio($term);
        if ($rows) {
            $pr[] = array("codigo" => "", "nome" => " - selecione - ");
            foreach ($rows as $row) {
                $pr[] = $row;
            }
            echo json_encode($pr);
        } else {
            echo "";
        }
    }


}