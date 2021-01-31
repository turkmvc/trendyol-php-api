<?php

namespace turkmvc\PazarYeri\Trendyol\Services;

use turkmvc\PazarYeri\Trendyol\Helper\Request;

Class ProductService extends Request
{

	/**
	 *
	 * Default API Url Adresi
	 *
	 * @author Cuma KÖSE <turkmvc@gmail.com>
	 * @var string
	 *
	 */
	public $apiUrl = 'https://api.trendyol.com/sapigw/suppliers/{supplierId}/products';

	/**
	 *
	 * Request sınıfı için gerekli ayarların yapılması
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 *
	 */
	public function __construct($supplierId, $username, $password)
	{
		parent::__construct($this->apiUrl, $supplierId, $username, $password);
	}

	/**
	 *
	 * Trendyol üzerindeki ürünleri filtrelemek için kullanılır.
	 *
	 * @author Cuma KÖSE <turkmvc@gmail.com>
	 * @return array 
	 *
	 */
	public function filterProducts($data = array())
	{

		$query = array(
			'approved'      => '',
			'barcode'       => '',
			'startDate'     => array('format' => 'unixTime'),
			'endDate'       => array('format' => 'unixTime'),
			'page'          => '',
			'dateQueryType' => array('required' => array('CREATED_DATE' , 'LAST_MODIFIED_DATE')),
			'size'          => ''
		);

		return $this->getResponse($query, $data);
	}
}

