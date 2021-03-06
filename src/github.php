<?php
require_once('github_base.class.php');
class Github extends Github_base{

	public function __construct($config = array()){
		$this->serverUrl	  = isset($config['serverUrl']) ? $config['serverUrl'] : 'https://api.github.com';
		$this->accessToken	  = $config['accessToken'];
		$this->bundleCertificate = isset($config['bundleCertificate']) ? $config['bundleCertificate'] : __DIR__ . '/ca_chain_bundle.crt';
	}
	
	/**
	 * Api request
	 * 
	 * @param str url: the request endpoint
	 * @param str method: the method of the request (GET,POST,PUT,DELETE)
	 * @param array args: POST,PUT: data | GET: where condition
	 * 
	 * @return array result: the result set from the API request
	 */
	public function api($url,$method='GET',$args=array()) {
		$params = array(
			'method' => $method
		);
		
		# Set parameters for request
		switch(strtoupper($method)){
			case 'GET':
				if(count($args) > 0) {
					$url = $this->addConditions($url,$args);
				}
				break;
			case 'PUT':
			case 'POST':
			case 'DELETE':
				$params['inputdata'] = $args;			
		}

		# Return result set
		return $this->makeRequest($url,$params);
	}
	
	/**
	* Set curl timeout in seconds
	* 
	* @param int $timeout
	*/
	public function setCurlTimeOut($timeout){
		if(!is_integer($timeout)){
			throw new Exception('The parameter must be an integer');
		}
		else{
			$this->curlTimeOut = $timeout;
		}
	}
	
	/**
	* Set accept header mimetype
	* 
	* @param string $mimeType
	*/
	public function setMimeType($mimeType='application/json'){
		$this->mimeType	= $mimeType;
	}
	
	/**
	 * Get error returned by api
	*/
	public function getError() {
		return $this->error;
	}
}
?>
