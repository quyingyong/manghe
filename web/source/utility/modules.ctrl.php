<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('module');

$dos = array('list', 'check_receive', 'templates', 'modules', 'bm', 'bi');
if (!in_array($do, $dos)) {
	if ($_W['isajax']) {
		iajax(-1, 'Access Denied');
	}
	exit('Access Denied');
}
$keyword = safe_gpc_string($_GPC['keyword']);
$account_type_sign = safe_gpc_string($_GPC['account_type_sign']);

if ('check_receive' == $do) {
	$module_name = trim($_GPC['module_name']);
	$module_obj = WeUtility::createModuleReceiver($module_name);
	if (!empty($module_obj)) {
		$module_obj->uniacid = $_W['uniacid'];
		$module_obj->acid = $_W['acid'];
		$module_obj->message = array(
			'event' => 'subscribe',
		);
		if (method_exists($module_obj, 'receive')) {
			$module_obj->receive();

			return iajax(0, '');
		}
	}

	return iajax(1, '');
}

if ('list' == $do) {
	global $_W;
	if (!empty($_COOKIE['special_reply_type'])) {
		$enable_modules = array();
		$_W['account']['modules'] = uni_modules();
		foreach ($_W['account']['modules'] as $m) {
			if (is_array($_W['account']['modules'][$m['name']]['handles']) && in_array($_COOKIE['special_reply_type'], $_W['account']['modules'][$m['name']]['handles'])) {
				$enable_modules[$m['name']] = $m;
			}
		}
		setcookie('special_reply_type', '', time() - 3600);
	} else {
		$installedmodulelist = uni_modules();
		foreach ($installedmodulelist as $k => $value) {
			$installedmodulelist[$k]['official'] = empty($value['issystem']) && (strexists($value['author'], 'WeEngine Team') || strexists($value['author'], '微信魔方团队'));
			if (1 == $value['enabled']) {
				$enable_modules[$k] = $value;
			}
		}
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 21;
	$current_module_list = array_slice($enable_modules, ($pindex - 1) * $psize, $psize);
	if ($_W['isw7_request']) {
		$message = array(
			'total' => count($enable_modules),
			'page' => $pindex,
			'page_size' => $psize,
			'list' => $current_module_list
		);
		iajax(0, $message);
	}

	$result = array(
		'items' => $current_module_list,
		'pager' => pagination(count($enable_modules), $pindex, $psize, '', array('before' => '2', 'after' => '3', 'ajaxcallback' => 'null')),
	);
	iajax(0, $result);
}

if ('templates' == $do) {
	$page = max(1, intval($_GPC['page']));
	$page_size = 6;
	$templates_table = table('modules');
	if (!empty($keyword)) {
		$templates_table->where('title LIKE', "%{$keyword}%");
	}
	$templates = $templates_table->select(array('mid', 'name', 'title'))->where('application_type', APPLICATION_TYPE_TEMPLATES)->page($page, $page_size)->getall();
	if (!empty($templates)) {
		foreach ($templates as $key => $template) {
						$templates[$key]['id'] = $template['mid'];
			$templates[$key]['logo'] = $_W['siteroot'] . 'app/themes/' . $template['name'] . '/preview.jpg';
		}
	}
	$total = $templates_table->getLastQueryTotal();
	$message = array(
		'keyword' => $keyword,
		'page' => $page,
		'page_size' => $page_size,
		'total' => $total,
		'list' => $templates
	);
	iajax(0, $message);
}

if ('modules' == $do) {
	$modules = user_modules($_W['uid']);
	if (empty($modules)) {
		$message = array(
			'total' => 0,
			'page' => 1,
			'page_size' => 10,
			'keyword' => $keyword,
			'account_type_sign' => $account_type_sign,
			'list' => array()
		);
		iajax(0, $message);
	}

	if (!empty($keyword)) {
		foreach($modules as $k => $module) {
			if (!strstr($module['title'], $keyword)) {
				unset($modules[$k]);
			}
		}
	}
	$module_list = array();
	if (!empty($account_type_sign)) {
		foreach ($modules as $k => $module) {
			if (1 == $module['issystem'] || MODULE_SUPPORT_ACCOUNT != $module[$account_type_sign . '_support']) {
				unset($modules[$k]);
				continue;
			}
			$module_list[] = array(
				'id' => $module['mid'],
				'name' => $module['name'],
				'title' => $module['title'],
				'logo' => $module['logo'],
				'support' => $account_type_sign,
			);
		}
	} else {
		$module_support_type = module_support_type();
		foreach ($modules as $name => $module) {
			foreach ($module_support_type as $support => $info) {
				if (MODULE_SUPPORT_SYSTEMWELCOME_NAME == $support) {
					continue;
				}
				if ($module[$support] == $info['support']) {
					$module_list[] = array(
						'id' => $module['mid'],
						'name' => $module['name'],
						'title' => $module['title'],
						'logo' => $module['logo'],
						'support' => $info['type'],
					);
				}
			}
		}
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$current_module_list = array_slice($module_list, ($pindex - 1) * $psize, $psize);
	
	$message = array(
		'total' => count($module_list),
		'page' => $pindex,
		'page_size' => $psize,
		'keyword' => $keyword,
		'account_type_sign' => $account_type_sign,
		'list' => $current_module_list
	);
	iajax(0, $message);
}

if ('bm' == $do) {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition = array('issystem !=' => 1);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('modules') . ' WHERE issystem != 1');
	$list = table('modules')
		->where($condition)
		->orderby(array('mid' => 'DESC'))
		->limit(($pindex - 1) * $psize, $psize)
		->getall();
	$pager = pagination($total, $pindex, $psize);
	template('module/manage-system');
}

if ('bi' == $do) {
	$_W['isfounder'] = 1;
	$info = array(
		'os' => php_uname(),
		'php' => PHP_VERSION,
		'ims_version' => IMS_VERSION,
		'ims_release_date' => IMS_RELEASE_DATE,
	);
	$info['family'] = '您的产品是';
	switch (IMS_FAMILY) {
		case 'v':
			$info['family'] .= '开源版, 没有购买商业授权, 不能用于商业用途';
			break;
		case 's':
			$info['family'] .= '授权版';
			break;
		case 'x':
			$info['family'] .= '商业版';
			break;
		default:
			$info['family'] .= '单版';
	}

	$info['company'] = '&#x58f9;&#x950b;&#x6e90;&#x7801;';//qwj
	$info['developers'] = array();//array('袁文涛', '任超 (米粥)', '马德坤', '宋建君 (Gorden)', '赵波', '杨峰', '卜睿君', '张宏', '高建业', '葛海波', '马莉娜', '樊永康', '王玉', '翟佳佳', '张拯', '张玮');
	$info['operators'] =  array();//array('侯琪琪 (琪琪)', '杨欣雨 (小雨)', '赵小雷 (擎擎)', '蔡帅帅 (小帅)', '朱传宝 (阿宝)', '蒋康康 (阿康)', '王鹏 (鹏鹏)');
	//qwj
	$info['exchange_group'] = array('link' => 'https://www.baidu.com', 'title' => '&#x0077;&#x0077;&#x0077;&#x002e;&#x0079;&#x0066;&#x0070;&#x0068;&#x0070;&#x002e;&#x0063;&#x006e;');

	$users = pdo_getcolumn('users', array(), 'count(*)');
	$members = pdo_getcolumn('mc_members', array(), 'count(*)');
	$fee = pdo_fetch("SELECT sum(`fee`) as 'sum_fee' FROM " . tablename('core_paylog'));
	$accounts = pdo_fetchall("SELECT COUNT(*) as 'sum', `type`  FROM " . tablename('account') . " GROUP BY `type`");
	if (!empty($accounts)) {
		$accounts_sum = 0;
		$uni_account_type = uni_account_type();
		foreach ($accounts as &$account) {
			$accounts_sum += $account['sum'];
			$account['type_name'] = (in_array($account['type'], array(ACCOUNT_TYPE_OFFCIAL_AUTH, ACCOUNT_TYPE_APP_AUTH, ACCOUNT_TYPE_OFFCIAL_NORMAL, ACCOUNT_TYPE_APP_NORMAL)) ? (in_array($account['type'], array(ACCOUNT_TYPE_OFFCIAL_AUTH, ACCOUNT_TYPE_APP_AUTH)) ? '授权接入' : '普通接入') : '') . ($uni_account_type[$account['type']]['title'] ?: '--');
		}
		unset($account);
	}
	$other_info = [
		'users' => $users,
		'members' => $members,
		'accounts' => ['sum' => $accounts_sum, 'type_sum' => $accounts],
		'fee' => $fee['sum_fee'],
	];
	template('system/systeminfo');
}