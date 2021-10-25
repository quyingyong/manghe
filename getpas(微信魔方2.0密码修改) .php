<?php
//定义你的访问密码后上传--rubysn0w修改支持wq2.0
$auth = '123456';

define('IN_SYS', true);
require './framework/bootstrap.inc.php';
load()->web('template');
load()->web('common');
load()->model('user');

if($_W['ispost'] && $_GPC['auth'] == $auth && $auth != '') {
	$isok = true;
	$username = trim($_GPC['username']);
	$password = $_GPC['password'];
	$repassword = $_GPC['password1'];
	if($password !== $repassword ){
		exit('<script>alert("两次密码输入不一致.");location.href = "./"</script>');
		}
	if(!empty($username) && !empty($password)) {
		
		$member = user_single(array('username'=>$username));
		if(empty($member)) {
			exit('<script>alert("输入的用户名不存在");location.href = "./"</script>');
		}
		$newpwd = user_password($password, $member['uid']);
		$newpwd_hash = user_password_hash($newpwd, $member['uid']);
		if ($newpwd_hash == $member['hash']) {
				exit('<script>alert("未作修改！");location.href = "./"</script>');
			}
		$result = pdo_update('users', array('password' => $newpwd), array('uid' => $member['uid']));		
		if($result){
		   exit('<script>alert("密码修改成功, 请重新登陆, 并尽快删除本文件, 避免密码泄露隐患.");location.href = "./"</script>');
		}else{
			exit('<script>alert("密码修改失败！！");location.href = "./"</script>');
		}
	}
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="./resource/favicon.png">
	<title>密码找回工具 FOR 2.0 - 微信魔方 - 公众平台自助引擎 -  Powered by WE7.CC</title>
	<link href="./web/resource/css/bootstrap.min.css" rel="stylesheet">
	<link href="./web/resource/css/font-awesome.min.css" rel="stylesheet">
	<link href="./web/resource/css/common.css" rel="stylesheet">
	<script src="./web/resource/js/require.js"></script>
	<script src="./web/resource/js/app/config.js"></script>
</head>
<body>
<div class="main">
	<form class="form-horizontal form" action="" method="post" enctype="multipart/form-data" onSubmit="return formcheck(this)">
		<div class="panel panel-default" style="margin:10px;">
			<div class="panel-heading">
				重置密码 <span class="text-muted">如果你的管理密码意外遗失, 请使用此工具重置密码, 重置成功后请尽快将此文件从服务器删除, 避免造成安全隐患</span>
			</div>
			<div class="panel-body">
				<?php if($isok) {?>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">用户名:</label>
					<div class="col-sm-9">
						<input name="auth" type="hidden" value="<?php echo $auth;?>" />
						<input name="username" type="text" class="form-control" placeholder="请输入你要重置密码的用户名">
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">新密码:</label>
					<div class="col-sm-9">
						<input name="password" type="password" class="form-control" placeholder="">
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">确认新密码:</label>
					<div class="col-sm-9">
						<input name="password1" type="password" class="form-control" placeholder="">
					</div>
				</div>				
				<?php } else {?>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">请输入访问密码</label>
					<div class="col-sm-9">
						<input name="auth" type="password" class="form-control" placeholder="">
					</div>
				</div>
				<?php }?>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label"></label>
					<div class="col-sm-9">
						<button type="submit" class="btn btn-primary btn-block" name="submit" value="提交">提交</button>
						<input type="hidden" name="token" value="{$_W['token']}" />
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
</body>
</html>