<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	function getToken(){
		// API URL
		$url = 'https://apigwsit.telkom.co.id:7777/invoke/pub.apigateway.oauth2/getAccessToken';

		// Create a new cURL resource
		$ch = curl_init($url);

		// Setup request to send json via POST
		$data = array(
		    'grant_type' 	=> 'client_credentials',
		    'client_id' 	=> '31acc20c-a879-49da-ad4b-5f27f2de0e47',
		    'client_secret' => 'd26b26d6-0d90-4f2c-89b6-81db20d76f5b',
		);
		$payload = json_encode($data,true);

		// Attach encoded JSON string to the POST fields
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

		// Set the content type to application/json
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

		// Return response instead of outputting
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Execute the POST request
		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		return $result;
	}

	function searchAnntena($post){
		// API URL
		$url 	= 'https://apigwsit.telkom.co.id:7777/gateway/telkom-kominfo-M2M/1.0/downloadAntenna';
		$json 	= getToken();
		$data 	= json_decode($json,true);
		$token 	= $data['token_type'].' '.$data['access_token'];

		$header = array(
		    'Content-Type: application/json',
		    'Authorization: '.$token.''
		);

		// Create a new cURL resource
		$ch = curl_init($url);

		// Setup request to send json via POST
		$payload = json_encode($post,true);

		// Attach encoded JSON string to the POST fields
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

		// Set the content type to application/json
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		// Return response instead of outputting
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Execute the POST request
		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		return $result;
	}

	function searchEquipment($post){
		// API URL
		$url 	= 'https://apigwsit.telkom.co.id:7777/gateway/telkom-kominfo-M2M/1.0/downloadEquipment';
		$json 	= getToken();
		$data 	= json_decode($json,true);
		$token 	= $data['token_type'].' '.$data['access_token'];

		$header = array(
		    'Content-Type: application/json',
		    'Authorization: '.$token.''
		);

		// Create a new cURL resource
		$ch = curl_init($url);

		// Setup request to send json via POST
		$payload = json_encode($post,true);

		// Attach encoded JSON string to the POST fields
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

		// Set the content type to application/json
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		// Return response instead of outputting
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Execute the POST request
		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		return $result;
	}

	function searchZona($post){
		// API URL
		$url 	= 'https://apigwsit.telkom.co.id:7777/gateway/telkom-kominfo-M2M/1.0/getMasterAddressData';
		$json 	= getToken();
		$data 	= json_decode($json,true);
		$token 	= $data['token_type'].' '.$data['access_token'];

		$header = array(
		    'Content-Type: application/json',
		    'Authorization: '.$token.''
		);

		// Create a new cURL resource
		$ch = curl_init($url);

		// Setup request to send json via POST
		$payload = json_encode($post,true);

		// Attach encoded JSON string to the POST fields
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

		// Set the content type to application/json
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		// Return response instead of outputting
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Execute the POST request
		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		return $result;
	}

	function submitAPP($post){
		// API URL
		$url 	= 'https://apigwsit.telkom.co.id:7777/gateway/telkom-kominfo-M2M/1.0/importXML';
		$json 	= getToken();
		$data 	= json_decode($json,true);
		$token 	= $data['token_type'].' '.$data['access_token'];

		$header = array(
		    'Content-Type: application/json',
		    'Authorization: '.$token.''
		);

		// Create a new cURL resource
		$ch = curl_init($url);

		// Setup request to send json via POST
		$payload = json_encode($post,true);

		// Attach encoded JSON string to the POST fields
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

		// Set the content type to application/json
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		// Return response instead of outputting
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Execute the POST request
		$result = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		return $result;
	}
	
/* End of file Template.php */
/* Location: ./application/libraries/Template.php */