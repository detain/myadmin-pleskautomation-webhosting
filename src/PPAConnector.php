<?php

namespace Detain\MyAdminPleskAutomation;

use Detain\MyAdminPleskAutomation\POA_FailedRequest_Exception;
use Detain\MyAdminPleskAutomation\POA_MalformedRequest_Exception;
use Detain\MyAdminPleskAutomation\POA_DomainDoesNotExist_Exception;

require_once('XML/RPC2/Client.php');

/**
 * Parallels Plesk Automation connector class provicding xml/rpc2 access to the service
 */
class PPAConnector
{
	static protected $_xmlrpcProxy;

	protected function __construct() {
		/* this stuff was up top */
		if (!isset($GLOBALS['HTTP_RAW_POST_DATA']))
			$GLOBALS['HTTP_RAW_POST_DATA'] = file_get_contents('php://input');
		//require_once('Zend/XmlRpc/Client.php');			// http://framework.zend.com/manual/1.12/en/zend.xmlrpc.client.html
		//require('XML_RPC.php');							// http://gggeek.github.io/phpxmlrpc/
	}

	/**
	 * @param $IP
	 * @param $login
	 * @param $password
	 * @return mixed
	 */
	static public function getInstance($IP, $login, $password) {
		$password = str_replace('?', '%3F', $password);
		if (!self::$_xmlrpcProxy) {
			// Here go communication parameters for our management node
/*
			// Zend/XmlRpc
			$xmlrpcClient = new Zend_XmlRpc_Client("https://{$IP}:8440/RPC2");
			$httpClient = $xmlrpcClient->getHttpClient();
			$httpClient->setAuth($login, $password, Zend_Http_Client::AUTH_BASIC);
			$httpClient->setConfig(array('timeout' => 45));
			self::$_xmlrpcProxy = $xmlrpcClient->getProxy('pem'); //The pem prefix for API method names
*/
/*
			// XML_RPC
			$url = "https://{$login}:{$password}@{$IP}:8440/RPC2";
			$options = array(
				'prefix' => 'pem.',
				'debug' => false,
				'sslverify' => false,
			);
			$xmlrpcClient = new xmlrpc_client($url, $options);
*/
			if (!isset($GLOBALS['HTTP_RAW_POST_DATA']))
				$GLOBALS['HTTP_RAW_POST_DATA'] = file_get_contents('php://input');
			// XML/RPC2
			$url = "https://{$login}:{$password}@{$IP}:8440/RPC2";
			//echo "$url\n";exit;
			$options = array(
				'prefix' => 'pem.',
				'debug' => false,
				'sslverify' => false,
			);
			$xmlrpcClient = XML_RPC2_Client::create($url, $options);
			self::$_xmlrpcProxy = $xmlrpcClient;
		}
		return self::$_xmlrpcProxy;
	}

	/**
	 * processing the response
	 *
	 * @param $response
	 * @return bool
	 * @throws Detain\MyAdminPleskAutomation\POA_FailedRequest_Exception
	 * @throws Detain\MyAdminPleskAutomation\POA_MalformedRequest_Exception
	 */
	public static function checkResponse($response) {
		if (isset($response['status'])) {
			if ($response['status'] != 0) {
				// Here should go some error handling
				throw new POA_FailedRequest_Exception($response['error_message']);
			} else {
				return true;
			}
		} else {
			throw new POA_MalformedRequest_Exception('Malformed answer from POA');
		}
	}

}
