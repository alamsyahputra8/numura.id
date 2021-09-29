<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function __construct(){
		date_default_timezone_set("Asia/Bangkok");
        parent::__construct();
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory','datatables'));
		$this->load->model('crud_model');
		
		ini_set('max_execution_time', 1704880640);
		ini_set("memory_limit","1704880640M");
    }
	
	public function index(){
		
	}

	function get_produk_json() { //data data produk by JSON object
		error_reporting(0);
	header('Content-Type: application/json');
	echo $this->crud_model->get_all_produk();
	}

	function simpan(){ //function simpan data
	$data=array(
	  'barang_kode'     => $this->input->post('kode_barang'),
	  'barang_nama'     => $this->input->post('nama_barang'),
	  'barang_harga'    => $this->input->post('harga'),
	  'barang_kategori_id' => $this->input->post('kategori')
	);
	$this->db->insert('barang', $data);
	redirect('crud');
	}

	function update(){ //function update data
	$kode=$this->input->post('kode_barang');
	$data=array(
	  'barang_nama'     => $this->input->post('nama_barang'),
	  'barang_harga'    => $this->input->post('harga'),
	  'barang_kategori_id' => $this->input->post('kategori')
	);
	$this->db->where('barang_kode',$kode);
	$this->db->update('barang', $data);
	redirect('crud');
	}

	function delete(){ //function hapus data
	$kode=$this->input->post('kode_barang');
	$this->db->where('barang_kode',$kode);
	$this->db->delete('barang');
	redirect('crud');
	}

}
