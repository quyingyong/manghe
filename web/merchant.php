<?php

define('IN_SYS', true);
require '../framework/bootstrap.inc.php';
load()->web('common');
load()->web('template');
header('Content-Type: text/html; charset=UTF-8');
$uniacid = intval($_GPC['i']);
$cookie = $_GPC['__uniacid'];
if (empty($uniacid) && empty($cookie))
{
    $result = pdo_get('ewei_shop_domain_bindings',['domain'=>$_SERVER['HTTP_HOST']]);
    if (isset($result['uniacid']))
    {
        $_W['uniacid'] = $result['uniacid'];
        $uniacid = $_W['uniacid'];
    }
}
if (empty($uniacid) && empty($cookie)) {
	exit('Access Denied.');
}


session_start();

if (!empty($uniacid)) {
	$_SESSION['__merch_uniacid'] = $uniacid;
	isetcookie('__uniacid', $uniacid, 7 * 86400);
}


$site = WeUtility::createModuleSite('ewei_shopv2');

if (!is_error($site)) {
	$method = 'doWebWeb';
	$site->uniacid = $uniacid;
	$site->inMobile = false;

	if (method_exists($site, $method)) {
		$site->$method();
		exit();
	}

}


?>