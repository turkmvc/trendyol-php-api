<?php

namespace turkmvc\PazarYeri\Trendyol\Services;

use turkmvc\PazarYeri\Trendyol\Helper\Request;

Class OrderService extends Request
{

	/**
	 *
	 * Default API Url Adresi
	 *
	 * @author Cuma KÖSE <turkmvc@gmail.com>
	 * @var string
	 *
	 */
	public $apiUrl = 'https://api.trendyol.com/sapigw/suppliers/{supplierId}/orders';

	/**
	 *
	 * Request sınıfı için gerekli ayarların yapılması
	 *
	 * @author Cuma KÖSE <turkmvc@gmail.com>
	 *
	 */
	public function __construct($supplierId, $username, $password)
	{
		parent::__construct($this->apiUrl, $supplierId, $username, $password);
	}

	/**
	 *
	 * Trendyol üzerinde siparişleri arar.
	 *
	 * @author Cuma KÖSE <turkmvc@gmail.com>
	 * @param string $degisken
	 * @return string 
	 *
	 */
	public function orderList($data = array())
	{

		$query = array(
			'startDate'          => array('format' => 'unixTime'),
			'endDate'            => array('format' => 'unixTime'),
			'page'               => '',
			'size'               => '',
			'orderNumber'        => '',
			'status'             => array('required' => array('Created', 'Picking', 'Invoiced', 'Shipped', 'Cancelled', 'Delivered', 'UnDelivered', 'Returned', 'Repack', 'UnSupplied')),
			'orderByField'       => array('required' => array('PackageLastModifiedDate', 'CreatedDate')),
			'orderByDirection'   => array('required' => array('ASC', 'DESC')),
			'shipmentPackagesId' => '',
		);

		return $this->getResponse($query, $data);
	}
	

}
