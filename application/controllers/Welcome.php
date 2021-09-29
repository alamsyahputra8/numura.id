<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
	public function index()
	{
		echo date('Y');
		//$this->load->view('welcome_message');
	}
	

	public function cek(){
		$origin 	= '23';
		$city 		= '428';
		// $weight 	= trim(strip_tags(stripslashes($this->input->post('weight',true))));
		$weight 	= '1';
		$courier 	= 'sicepat';

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "origin=$origin&destination=$city&weight=$weight&courier=$courier",
		  CURLOPT_HTTPHEADER => array(
		    "content-type: application/x-www-form-urlencoded",
		    "key: e5f7bc2524a38de1cf4d946ec8404739"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  // echo $response;
		  $someArray = json_decode($response, true);

		  header('Content-type: application/json; charset=UTF-8');
		  foreach($someArray["rajaongkir"]["results"] as $data) {
		  	foreach ($data['costs'] as $datasub) {
	  			$datasubsub = array_shift($datasub['cost']);

		  		$row = array(
					'id'	=> $datasubsub['value'],
					'text'	=> $datasub['service'].' ('.$datasub['description'].') - '.$datasubsub['etd'].' Hari'
					);
				$page = array(
					'more'	=> 'true'
				);
				
				$json[] 		= $row;
		  	}
		  }
		  echo json_encode($json);
		}
	}
}
