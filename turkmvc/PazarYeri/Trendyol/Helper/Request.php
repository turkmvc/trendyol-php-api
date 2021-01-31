<?php

namespace turkmvc\PazarYeri\Trendyol\Helper;

Class Request
{

	/**
	 *
	 * Trendyol Api Url
	 * @var string
	 *
	 */
	public $apiUrl;

	/**
	 *
	 * Trendyol Api SupplierId
	 * @var int
	 *
	 */
	protected $apiSupplierId;

	/**
	 *
	 * Trendyol Api Kullanıcı Adı
	 * @var string
	 *
	 */
	protected $apiUsername;

	/**
	 *
	 * Trendyol Api Şifre
	 * @var string
	 *
	 */
	protected $apiPassword;

	/**
	 *
	 * Trendyol Api Şifre
	 * @var string
	 *
	 */
	protected $method;

	/**
	 *
	 * Get veya post için verileri barındırır.
	 *
	 * @author Cuma KÖSE <turkmvc@gmail.com>
	 * @var array
	 *
	 */
	protected $datas = array();

	/**
	 *
	 * Service Ayarlarını yapar
	 * @param string 
	 *
	 */
	public function __construct($apiUrl, $supplierId, $username, $password, $method = 'GET')
	{
		$this->setApiSupplierId($supplierId);
		$this->setApiUsername($username);
		$this->setApiPassword($password);
		$this->setApiUrl($apiUrl);
		$this->setMethod($method);
	}

	/**
	 *
	 * API SupplierId değerini değiştirir.
	 *
	 * @param string $apiUrl
	 *
	 */
	public function setApiSupplierId($supplierId)
	{
		$this->apiSupplierId = $supplierId;
	}

	/**
	 *
	 * API Kullanıcı adını değiştirir.
	 *
	 * @param string $apiUrl
	 *
	 */
	public function setApiUsername($username)
	{
		$this->apiUsername = $username;
	}

	/**
	 *
	 * API Şifresini değiştirir.
	 *
	 * @param string $apiUrl
	 *
	 */
	public function setApiPassword($password)
	{
		$this->apiPassword = $password;
	}

	/**
	 *
	 * Api Linkini ayarlar.
	 *
	 * @param string $apiUrl
	 *
	 */
	public function setApiUrl($apiUrl)
	{
		$this->apiUrl = $apiUrl;
	}

	/**
	 *
	 * Method türünü ayarlama POST|GET...
	 *
	 * @param string $method
	 *
	 */
	public function setMethod($method)
	{
		$this->method = strtoupper($method);
	}

	/**
	 *
	 * Trendyol için basic auth döndürür
	 *
	 * @author Cuma KÖSE <turkmvc@gmail.com>
	 * @return string 
	 *
	 */
	protected function authorization()
	{
		return base64_encode($this->apiUsername . ':' . $this->apiPassword);
	}

	/**
	 *
	 * Api url bilgisini döner.
	 *
	 * @author Cuma KÖSE <turkmvc@gmail.com>
	 * @param array $datas
	 *
	 */
	public function getApiUrl($requestData)
	{

		$apiUrl = $this->apiUrl;
		foreach (Format::getUrlSpecialParameters($apiUrl) as $key) 
		{
			if (isset($requestData[$key])) 
			{
				$apiUrl = str_replace('{' . $key . '}',  $requestData[$key], $apiUrl);
				unset($requestData[$key]);
			}
		}

		$apiUrl = str_replace('{supplierId}', $this->apiSupplierId, $apiUrl);
		if ($this->method == 'POST' || !is_array($requestData) || count($requestData) <= 0) {
			return $apiUrl;
		}

		return $apiUrl . '?' . http_build_query($requestData);

	}

	/**
	 *
	 * Hazırlanan isteği apiye iletir ve yanıtı json olarak döner.
	 *
	 * @author Cuma KÖSE <turkmvc@gmail.com>
	 * @param array $query
	 * @param array $data
	 * @param boolean $authorization
	 * @return array 
	 *
	 */
	public function getResponse($query, $data, $authorization = true)
	{

		$requestData = Format::initialize($query, $data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->getApiUrl($requestData));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);	

		if ($authorization) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $this->authorization()));
		}

		if ($this->method == 'POST') {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestData));
		}

		$response = trim(curl_exec($ch));
		if (empty($response)) {
			throw new TrendyolException("Trendyol boş yanıt döndürdü.");
		}

		$response = json_decode($response);
		curl_close($ch);
		return $response;
	}

}
