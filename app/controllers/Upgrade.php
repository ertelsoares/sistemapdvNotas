<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upgrade extends MY_Controller
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
        $this->data['page_title'] = "Upgrade";
        $bc = array(array('link' => '#', 'page' => "Upgrade"));
        $meta = array('page_title' => "Upgrade", 'bc' => $bc);
        $this->page_construct('upgrade', $this->data, $meta);

    }

}
