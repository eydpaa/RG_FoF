/**
 * Name:  RgapiClient
 *
 *
 * Author: Costas Apostolopoulos
 *		  costas_a@yahoo.com
 *
 * Created:  12.02.2021
 *
 * Description:  Rural Development Guarantee Fund of Funds REST API client
 *
 * Requirements: cURL library (https://www.php.net/manual/en/book.curl.php)
 */

class RgapiClient {
	
	protected $email;
	protected $password;
	
	public function __construct()
	{
		$this->baseUrl = 'https://fi.agrotikianaptixi.gr/pskeapi';			
		$this->authUrl = 'https://fi.agrotikianaptixi.gr/auth-api/login';			
	}
	
	public function setCredentials ($email, $password)
	{
		$this->email = $email;
		$this->password = $password;
	}
	
	public function getJWT()
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(['email' => $this->email, 'password' => $this->password]));
		curl_setopt($curl, CURLOPT_URL, $this->authUrl);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		return json_decode(curl_exec($curl));
		curl_close($curl);
	}
	
	public function getResource($resource)
	{
		$jwt = $this->getJWT();
		if(isset($jwt->access_token)){
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_HTTPGET, 1);
			curl_setopt($curl, CURLOPT_URL, $this->baseUrl."/".$resource);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Authorization: Bearer '.$jwt->access_token,
			));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			return json_decode(curl_exec($curl));
			curl_close($curl);
		}
		else {
			return $jwt;
		}
	}
	
}
