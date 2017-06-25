<?php
include_once(__DIR__.'/../../../include/functions.inc.php');
$ppaConnector = get_webhosting_ppa_instance();
$subscription_id = intval($_SERVER['argv'][1]);
$request = array(
	'subscription_id' => $subscription_id,
	'get_resources' => true,
);
$result = $ppaConnector->getSubscription($request);
echo preg_replace("/$\s*array\s+\(/msiU", "array(", var_export($result, true));
echo "\n";
try {
	PPAConnector::checkResponse($result);
} catch (Exception $e) {
	echo 'Caught exception: '.$e->getMessage()."\n";
}
echo "Got Account ID: {$result['result']['owner_id']}\n";
