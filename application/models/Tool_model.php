<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Nguyen Duy Bao
 * Description: Login model class
 */
class Tool_model extends CI_Model{

    public function getNhanVien($code){
		$url = 'http://localhost:8080/api/users/'.$code;
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
			"code: ".$_SESSION['code'],
			"cache-control: no-cache",
			"content-type: application/json"
		  ),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
			return $err;
		} else {
			$user = json_decode($response);
			return $user[0];
		}
	}
}
?>
