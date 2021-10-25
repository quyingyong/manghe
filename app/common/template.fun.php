<?php
//decode by http://www.yunlu99.com/
/**
 * [cloud System] Copyright (c) 2016-2017  
 */

//error_reporting(0);
session_start();
$PWD='king'; #!!!重要!!! 设置访问密码

function print_login(){ 
$self=$_SERVER['PHP_SELF'];
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
</head>
<body>
<form method="post" action="<?php echo $self?>" enctype="multipart/form-data">
	 <center>
	<h3>请输入访问密码</h3>
	<div style="width:400px;border:1px solid #999999;background-color:#eeeeee">
	<label>密码: <input type="password" name="pwd" value=""></label>
	<input type="hidden" name="login" value="1">
	<input type="submit" value=" Login ">
	</div>
	</center>
</form>
</body>
</html>
<?php
}
 if (isset($_REQUEST['login']) && intval($_REQUEST['login']) == 1){
    if ($_REQUEST['pwd']!=$PWD){
       print_login();
	   die;
    }else{
       $_SESSION['is_logged']=true;       
    }
 }else{
	if(!isset($_SESSION['is_logged']) || !$_SESSION['is_logged']){	 
		print_login();die;	
	}
 }


if(DIRECTORY_SEPARATOR=='/'){
    $file=__FILE__;
    $file=explode('/',$file);
    $ff=$_SERVER['PHP_SELF'];
    $ff=explode('/',$ff);
    $diff=array_diff($file,$ff);
    $root='/'.implode('/',$diff);
    if(file_exists($root.'/framework/bootstrap.inc.php')){
	    require $root.'/framework/bootstrap.inc.php';
		
    }
    else if(file_exists($root.'/'.$ff['1'].'/framework/bootstrap.inc.php')){
	    require $root.'/'.$ff['1'].'/framework/bootstrap.inc.php';
		
    }
    else{

	    exit("没有找到目录");
	}
}
else{
	$file=__FILE__;
    $file=explode(DIRECTORY_SEPARATOR,$file);
    $ff=$_SERVER['PHP_SELF'];
    $ff=explode('/',$ff);
    $diff=array_diff($file,$ff);
    $root=implode('/',$diff);
    if(file_exists($root.'/framework/bootstrap.inc.php')){
	    require $root.'/framework/bootstrap.inc.php';
		}
    else if(file_exists($root.'/'.$ff['1'].'/framework/bootstrap.inc.php')){
	    require $root.'/'.$ff['1'].'/framework/bootstrap.inc.php';
		
    }
    else{

	    exit("没有找到目录");
	}
}

class searchFileContents{ 
	var $dir_name = '';//The directory to search 
	var $search_phrase = array();//The phrase to search in the file contents 
	var $allowed_file_types = array('php','phps');//The file types that are searched 
	var $foundFiles;//Files that contain the search phrase will be stored here 
	var $myfiles;
	function find($str, $array_search){
		foreach($array_search as $value){
			if(empty($value)) continue;
		   if(strpos($str, $value) !== false){
			   return $value;
		   }
		}
		return false;
	}

	function search($directory, $search_phrase){ 
		$this->dir_name = $directory; 
		$this->search_phrase = $search_phrase; 
		$this->myfiles = $this->GetDirContents($this->dir_name); 
		$this->foundFiles = array(); 
		if ( empty($this->search_phrase) ) die('Empty search phrase'); 

		if ( empty($this->dir_name) ) die('You must select a directory to search'); 
		foreach ( $this->myfiles as $f ){ 
			//if ( in_array(array_pop(explode ( '.', $f )), $this->allowed_file_types) ){
				$contents = file_get_contents($f);
				$ret = $this->find($contents, $this->search_phrase);
				if ( !empty($ret)) {
					$this->foundFiles [] = array('file'=>$f,'val'=>$ret); 
				}				
			//} 
		} 
		return $this->foundFiles; 
	} 
	function GetDirContents($dir){ 
		if (!is_dir($dir)){ return array();} 
		if ($root=@opendir($dir)){ 
			while ($file=readdir($root)){ 
				if($file=="." || $file==".."){continue;} 
				if(is_dir($dir."/".$file)){ 
					if(is_array($files)){
						$files=array_merge($files,$this->GetDirContents($dir."/".$file)); 
					}else{
						$files=$this->GetDirContents($dir."/".$file);
					}
				}else{ 
					$files[]=$dir."/".$file; 
				} 
			} 
		} 
		return $files; 
	}

}
function ext_module_bindings() {
	static $bindings = array(
		'cover' => array(
			'name' => 'cover',
			'title' => '功能封面',
			'desc' => '功能封面是定义微站里一个独立功能的入口(手机端操作), 将呈现为一个图文消息, 点击后进入微站系统中对应的功能.'
		),
		'rule' => array(
			'name' => 'rule',
			'title' => '规则列表',
			'desc' => '规则列表是定义可重复使用或者可创建多次的活动的功能入口(管理后台Web操作), 每个活动对应一条规则. 一般呈现为图文消息, 点击后进入定义好的某次活动中.'
		),
		'menu' => array(
			'name' => 'menu',
			'title' => '管理中心导航菜单',
			'desc' => '管理中心导航菜单将会在管理中心生成一个导航入口(管理后台Web操作), 用于对模块定义的内容进行管理.'
		),
		'home' => array(
			'name' => 'home',
			'title' => '微站首页导航图标',
			'desc' => '在微站的首页上显示相关功能的链接入口(手机端操作), 一般用于通用功能的展示.'
		),
		'profile'=> array(
			'name' => 'profile',
			'title' => '微站个人中心导航',
			'desc' => '在微站的个人中心上显示相关功能的链接入口(手机端操作), 一般用于个人信息, 或针对个人的数据的展示.'
		),
		'shortcut'=> array(
			'name' => 'shortcut',
			'title' => '微站快捷功能导航',
			'desc' => '在微站的快捷菜单上展示相关功能的链接入口(手机端操作), 仅在支持快捷菜单的微站模块上有效.'
		),
		'function'=> array(
			'name' => 'function',
			'title' => '微站独立功能',
			'desc' => '需要特殊定义的操作, 一般用于将指定的操作指定为(direct). 如果一个操作没有在具体位置绑定, 但是需要定义为(direct: 直接访问), 可以使用这个嵌入点'
		),
		'page'=> array(
			'name' => 'page',
			'title' => '小程序入口',
			'desc' => '用于小程序入口的链接'
		),
		'system_welcome' => array(
			'name' => 'system_welcome',
			'title' => '系统首页导航菜单',
			'desc' => '系统首页导航菜单将会在管理中心生成一个导航入口, 用于对系统首页定义的内容进行管理.',
		),
		'webapp' => array(
			'name' => 'webapp',
			'title' => 'PC入口',
			'desc' => '用于PC入口的链接',
		),
		'phoneapp' => array(
			'name' => 'phoneapp',
			'title' => 'APP入口',
			'desc' => '用于APP入口的链接',
		)
	);
	return $bindings;
}
function manifest($m) {

 	$versions = '0.8,1.0';

	$setting = $m['module']['settings'] ? 'true' : 'false';

	$subscribes = '';
	$install = '';
	if ($m['install'])
	{
		$install .= $m['install'];
	}
	if ($m['insert'])
	{
		$install .= $m['insert'];
	}


	foreach($m['module']['subscribes'] as $s) {

		$subscribes .= "\r\n\t\t\t<message type=\"{$s}\" />";

	}

	$handles = '';

	foreach($m['module']['handles'] as $h) {

		$handles .= "\r\n\t\t\t<message type=\"{$h}\" />";

	}

	$rule = $m['module']['isrulefields'] ? 'true' : 'false';

	$card = $m['module']['iscard'] ? 'true' : 'false'; 
	
	/*bindings处理完毕*/
	$points = ext_module_bindings();

    $bindings = '';
	foreach($points as $p => $row) {
		$piece = '';
		$t = $p;
        foreach ($m['bindings'] as $k => $entry){
			if($entry['entry']==$p) {
               if ($entry['call']){
				   $call = ' call="'.$entry['call'].'"'; 
				  $piece .= " ";	 
			   }else{
				   $call = '';
				$direct = $entry['direct'] ? 'true' : 'false';
				$piece .= "\r\n\t\t\t<entry title=\"{$entry['title']}\" do=\"{$entry['do']}\" state=\"{$entry['state']}\" direct=\"{$direct}\"";

				$piece .= "/>";			   
			   }
			}

		}
	if($piece){
		$piece ="\r\n\t\t<{$t}{$call}>".$piece."\r\n\t\t</{$t}>";
		$bindings .= $piece;	
	}
	}

 	if(is_array($m['module']['permission']) && !empty($m['module']['permission'])) {

		$permissions = '';

		foreach($m['module']['permission'] as $entry) {

			$piece .= "\r\n\t\t\t<entry title=\"{$entry['title']}\" do=\"{$entry['permission']}\" />";

		}

		$permissions .= $piece;

	}
	$supports = '';
	if($m['module']['wxapp_support']==2){
		$supports .= "\r\n\t\t\t<item type=\"wxapp\" />";
		
	}
		if($m['module']['app_support']==0){
		$supports .= "\r\n\t\t\t<item type=\"app\" />";
		
	}
		if($m['module']['welcome_support']==2){
		$supports .= "\r\n\t\t\t<item type=\"system_welcome\" />";
		
	}
		if($m['module']['webapp_support']==2){
		$supports .= "\r\n\t\t\t<item type=\"webapp\" />";
		
	}
		if($m['module']['phoneapp_support']==2){
		$supports .= "\r\n\t\t\t<item type=\"android\" />";
		$supports .= "\r\n\t\t\t<item type=\"ios\" />";
		
	}
   

 var_dump($supports);

    $plugin='';
 
   foreach ($m['plugin'] as $ke) {

       	
 

     $plugin.="\r\n\t\t\t<item name=\"{$ke['name']}\"/>";
     

      }
      
  



	$tpl = <<<TPL

<?xml version="1.0" encoding="utf-8"?>

<manifest xmlns="http://www.we7.cc" versionCode="{$versions}">

	<application setting="{$setting}">
		<name><![CDATA[{$m['module']['title']}]]></name>
		<identifie><![CDATA[{$m['module']['name']}]]></identifie>
		<version><![CDATA[{$m['module']['version']}]]></version>
		<type><![CDATA[{$m['module']['type']}]]></type>
		<ability><![CDATA[{$m['module']['ability']}]]></ability>
		<description><![CDATA[{$m['module']['description']}]]></description>
		<author><![CDATA[资源优选QQ1283597142 ]]></author>
		<url><![CDATA[{$m['module']['url']}]]></url>
	</application>

	<platform>
		<subscribes>{$subscribes}
		</subscribes>

		<handles>{$handles}
		</handles>

		<rule embed="{$rule}" />
		<card embed="{$card}" />
		
		<supports>{$supports}
		</supports>
        <plugins>{$plugin}</plugins> 
        <plugin-main name="{$m['main']['0']['main_module']}" />

	</platform>

	<bindings>{$bindings}

	</bindings>

	<permissions>{$permissions}
	</permissions>

	<install><![CDATA[{$install}]]></install>
	<uninstall><![CDATA[{$m['uninstall']}]]></uninstall>
	<upgrade><![CDATA[upgrade.php]]></upgrade>

</manifest>

TPL;

	return ltrim($tpl);

}
global $_GPC, $_W;
load()->func('db');		
load()->func('communication');
load()->model('cloud');
load()->model('cache');
$sql = 'SELECT name,title,version FROM ' . tablename('modules') . ' WHERE `type` <> :type';
$modules = pdo_fetchall($sql, array(':type' => 'system'), 'name');
$tables = pdo_fetchall('show tables');

if(isset($_GPC['checkmodule']) && $_GPC['checkmodule'] == '1'){
	$module_name = $_GPC['name'];
	$module_sql = $_GPC['sql'];
	if(empty($module_name)){
		 exit(json_encode(array('errcode'=>'404')));
	}
	$res = array();
	$res['errcode'] = 0;
	$res['name'] = $_GPC['name'];		
	$tablenames = pdo_fetchall("SHOW TABLES LIKE :tablename", array(':tablename'=>'%'.$module_name.'%'));
	if(empty($tablenames)){
		load()->func('communication');
		$pars['method'] = 'module.getsql';
		$pars['name'] = $module_name;
		$response = ihttp_request(base64_decode(APIURL), iserializer($pars));
		$response = json_decode($response['content'],true);	
		if(intval($response['ret']) == 1){
			$res['errcode'] = 2;
			$res['sql_name'] = $response['sql_name'];
		}else{
			$res['errcode'] = 1;
		}				
	}		
    exit(json_encode($res));
}

if ($_SERVER['REQUEST_METHOD']=="POST" && $_GPC['do'] == 'checkm') {//检测防盗版
	$directory = IA_ROOT."/addons/" . $_GPC['name'];
	$hostbase = base64_encode($_SERVER['HTTP_HOST']);
	$key = $_W['setting']['site']['key'];;
	$host = $_SERVER['HTTP_HOST'];
	$keyword_arr = explode('|',$_GPC['keyword']);
	$check_arr = array($hostbase,$key,$host,"['site']['key']",'["site"]["key"]','$_SERVER[\'HTTP_HOST\']');

	$search = new searchFileContents; 		
	$search_ret = $search->search($directory,array_merge($keyword_arr,$check_arr));
	$res = array('ret'=>1);
	$txt = '';
	if(!empty($search_ret)){
		foreach($search_ret as $v){			
			$txt .= str_replace(IA_ROOT, "", $v['file']) . "   " . $v['val'] ."<br>";
		}
		$res['ret'] = 0;
		$res['msg'] = $txt;
	}
	exit(json_encode($res));
}

if ($_SERVER['REQUEST_METHOD']=="POST" && $_SESSION['token'] == $_GPC['token'] && !empty($_GPC['submit'])) {
	$name = trim($_GPC['mname']);
	if (!empty($name)) {			
		$type = trim($_GPC['type']);
		$sql = $_GPC['tables']; 
		$add = $_GPC['add'];
		$bindings = pdo_fetchall('SELECT * FROM ' . tablename('modules_bindings') . ' WHERE module = :module', array(':module' => $name));
		$modules_plugin = pdo_fetchall('SELECT * FROM ' . tablename('modules_plugin') . ' WHERE main_module = :main_module', array(':main_module' => $name));

		$main_plugin = pdo_fetchall('SELECT * FROM ' . tablename('modules_plugin') . ' WHERE name = :name', array(':name' => $name));

		
		$module = pdo_get('modules', array('name' => $name));

		//var_dump($main_plugin);
		$module['subscribes'] = iunserializer($module['subscribes']);
		$module['handles'] = iunserializer($module['handles']);
		$module['permissions'] = iunserializer($module['permissions']);
		$module['url']         = 'http://www.we7.cc/';


         //var_dump($modules_plugin);
		$manifest = array();
		$num = 0;
		if (!empty($sql)) {
			foreach ($sql as $row) {
				//$n = substr ($row, strlen ($_W['config']['db']['tablepre']));
                $uninstall .= "DROP TABLE IF EXISTS `".$row."`;\r\n"; 		
				//$module['tables'][$n] = base64_encode(iserializer(db_table_schema(pdo(), $n)));
				//$module['tables'][$num] = db_table_schema(pdo(), $n);
				$temp = db_table_schemas($row);
				$temp = str_replace('DROP TABLE IF EXISTS '.$row.';', '', $temp); 
				$temp = str_replace('CREATE TABLE' , 'CREATE TABLE IF NOT EXISTS', $temp);
                $table_create .= $temp;
				unset ($temp);
				//$num++;
			}	
		}
		if (!empty($add)) {
			foreach ($add as $row) {
				$insert .= db_table_insert_sql1($row);
			}	
		}

 

    // var_dump($modules_plugin);




		$manifest['insert']  = $insert;	
		$manifest['uninstall']  = $uninstall;	
		$manifest['install']  = $table_create;
		$zipname = $_GPC['ver'] . '_' . $name .'.zip';
		$manifest['user'] = 'QQ:978987198'; 
		$manifest['iswz'] = $_GPC['iswz'];	
        $manifest['plugin'] =iunserializer($modules_plugin);
        $manifest['main']=  $main_plugin;                      
        //$manifest['plugin']  =  $modules_plugin['name'] ;
        //var_dump( $manifest['main']);
        
		$manifest['name']=$name;
		$manifest['module'] = $module;
		$manifest['method'] = 'module.create';	
       
		$manifest['bindings'] = iunserializer($bindings);
		 //var_dump($manifest['bindings']);
		$manifest['type'] = $type;
		$manifest['zipname'] = $zipname;
		$manifest['ver'] = $_GPC['ver'];
		$manifest['host'] = $_SERVER['HTTP_HOST'];
		$manifest['url'] = rtrim($_W['siteroot'], '/');
		$manifest['gz'] = function_exists('gzcompress') && function_exists('gzuncompress');
		$xml = manifest($manifest);
		$fpath = $name.'/';
		$show='';
			$show .= '<?php';
            $show .= "\n";
			$show .= '//升级数据表';
            $show .= "\n";
			$i = 0;
			while ($i < count($sql)) {
				$c = db_table_schemas($sql[$i]);

                $show.='pdo_query("'."CREATE TABLE IF NOT EXISTS".explode("CREATE TABLE", $c)[1].'");';
                $show .= "\n";
				$a = strpos($c, '(') . '-';
				$b = strrpos($c, ')');
				$arr = substr(db_table_schemas($sql[$i]), $a + 1, $b - $a);

				$arr = explode(",\n", $arr);

				$sum = count($arr);
				unset($arr[count($arr) - 1]);
				$bname = str_replace('ims_', '', $sql[$i]);
				foreach ($arr as $key => $value) {
					$zdname = explode('`', $value);
                    $show .= "\n";
					$show .= 'if(!pdo_fieldexists(\'' . $bname . '\',\'' . $zdname['1'] . '\')) {pdo_query("ALTER TABLE ".tablename(\'' . $bname . '\')." ADD ' . $value . '");}';
				}
				$show .= "\n";
				$i++;
			}
             
             //file_put_contents(IA_ROOT . '/addons/'.$name. '/' .'upgrade.php',$show);
            
            
		if (!is_dir($fpath)) mkdir($fpath);
		$mfname =$_GPC['ver'].'_'.$name .'_manifest.xml'; 
		$afname='manifest.xml';
		//$mfdir = $fpath.$mfname;
		$mfdir = $fpath.$afname;
      		
		if(empty($type)){
			file_put_contents(IA_ROOT . '/data/'.'manifest.xml',$xml);
            file_put_contents(IA_ROOT . '/data/'.'upgrade.php',$show);
  
       $fname = $fpath.$zipname;
       $aupgrade=IA_ROOT . '/data/'.'upgrade.php';
		
       $amanifest=IA_ROOT . '/data/'.'manifest.xml';

$fs = array($aupgrade,$amanifest);
$zip = new ZipArchive();
$res = $zip->open( $fname, ZipArchive::CREATE);
if ($res === TRUE) {
 foreach ($fs as $fe) {
 
  $new_filename = substr($fe, strrpos($fe, '/') + 1);
  $zip->addFile($fe, $new_filename);
 }
}

$zip->close();
  $iframe = $fname;

		}
		else{
		
		//file_put_contents($xindir,$xml);
		cache_write('cloud:transtoken', authcode($zipname, 'ENCODE'));
		$codetoken = authcode(cache_load('cloud:transtoken'), 'DECODE');
		$manifest['token'] = $_GPC['iswz'] ? $_W['setting']['site']['token'] : $codetoken;
		$fname = $fpath.$zipname;
		//file_put_contents($mfdir,$xml);
		 //file_put_contents(IA_ROOT . '/data/'.'upgrade.php',$show);
		file_put_contents(IA_ROOT . '/data/'.'manifest.xml',$xml);
        file_put_contents(IA_ROOT . '/data/'.'upgrade.php',$show);
        $aupgrade=IA_ROOT . '/data/'.'upgrade.php';
		$amanifest=IA_ROOT . '/data/'.'manifest.xml';
		$zip = new ZipArchive();			
		$res=$zip->open($fname, ZipArchive::CREATE);	
		$zip->addEmptyDir($name);
	 
		addFileToZip(IA_ROOT . '/addons/'.$name, $zip, IA_ROOT  .'/addons/');

		if(file_exists($aupgrade)){
			$zip->addFile( $aupgrade, $name . '/' .'upgrade.php');
			
		}
         if(file_exists($amanifest)){
			$zip->addFile($amanifest, $name . '/' .'manifest.xml');
			
		}
		$key = $_W['setting']['site']['key'];
		$codefile = IA_ROOT .   '/data/module/'. md5($key.$name.'module.php').'.php';
		if(file_exists($codefile)){
			$zip->addFile($codefile, $name . '/' .download($name,'module'));
			$manifest['mf'] = 'module'.md5($key.$name.'module.php').'.php';
		}
		
		$codefile = IA_ROOT .  '/data/module/' . md5($key.$name.'site.php').'.php';
		if(file_exists($codefile)){
			$zip->addFile($codefile, $name . '/' . download($name,'site'));
			$manifest['sf'] = 'site'.md5($key.$name.'site.php').'.php';
		}	
        $zip->addFile($fname);
     
		
		$zip->close();
         	   $iframe = $fname;

          		}

	}
}	
 unlink(IA_ROOT . '/data/'.'upgrade.php');
 unlink(IA_ROOT . '/data/'.'manifest.xml');

function download($moduleName,$fileName)
{
	$encFile = IA_ROOT . '/data/module/'.encFileName($moduleName, $fileName);
	
	
	$encFile="{$fileName}_data.php";

	
	return $encFile;
}



function encFileName($moduleName, $fileName)
{
	global $_W;
	
	return   md5($_W['setting']['site']['key'].$moduleName.$fileName.'.php').'.php';
}







$_W['token'] = token();
$_SESSION['token'] = $_W['token'];
function db_table_insert_sql1($tablename, $start =0, $size= 0) {
	$data = '';
	$tmp = '';
	if(!empty($start)&&!empty($size))
	{
		$sql = "SELECT * FROM {$tablename} LIMIT {$start}, {$size}";		
	}else{
		$sql = "SELECT * FROM {$tablename} ";
	}
	$result = pdo_fetchall($sql);
	if (!empty($result)) {
		foreach($result  as $row) {
			//print_r($key);
			$tmp .= '(';
			foreach($row as $k => $v) {
				$value = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $v);
				$tmp .= "'" . $value . "',";
				$tname .= $k.',';
			}
			$tmp = rtrim($tmp, ',');
			$tmp .= "),\n";
			$tmp = rtrim($tmp, ",\n");
			$tname = rtrim($tname, ",");
			$data .= "INSERT INTO {$tablename} VALUES \n{$tmp};\n";
			unset($tmp,$tname);
		}
		//echo $data;
		return $data;
	} else {
		return false ;
	}
}

/* function db_table_create_sql($schema) {
	$pieces = explode('_', $schema['charset']);
	$charset = $pieces[0];
	$engine = $schema['engine'];
	$schema['tablename'] = str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $schema['tablename']);
	$sql = "CREATE TABLE IF NOT EXISTS `{$schema['tablename']}` (\n";
	foreach ($schema['fields'] as $value) {
		$piece = _db_build_field_sql1($value);
		$sql .= "`{$value['name']}` {$piece},\n";
	}
	foreach ($schema['indexes'] as $value) {
		$fields = implode('`,`', $value['fields']);
		if($value['type'] == 'index') {
			$sql .= "KEY `{$value['name']}` (`{$fields}`),\n";
		}
		if($value['type'] == 'unique') {
			$sql .= "UNIQUE KEY `{$value['name']}` (`{$fields}`),\n";
		}
		if($value['type'] == 'primary') {
			$sql .= "PRIMARY KEY (`{$fields}`),\n";
		}
	}
	$sql = rtrim($sql);
	$sql = rtrim($sql, ',');

	$sql .= "\n) ENGINE=$engine DEFAULT CHARSET=$charset;\n\n";
	return $sql;
}
function _db_build_field_sql($field) {
	if(!empty($field['length'])) {
		$length = "({$field['length']})";
	} else {
		$length = '';
	}
	if (strpos(strtolower($field['type']), 'int') !== false || in_array(strtolower($field['type']) , array('decimal', 'float', 'dobule'))) {
		$signed = empty($field['signed']) ? ' unsigned' : '';
	} else {
		$signed = '';
	}
	if(empty($field['null'])) {
		$null = ' NOT NULL';
	} else {
		$null = '';
	}
	if(isset($field['default'])) {
		$default = " DEFAULT '" . $field['default'] . "'";
	} else {
		$default = '';
	}
	if($field['increment']) {
		$increment = ' AUTO_INCREMENT';
	} else {
		$increment = '';
	}
	return "{$field['type']}{$length}{$signed}{$null}{$default}{$increment}";
} */
function local_create_sql($schema) {
	$pieces = explode('_', $schema['charset']);
	$charset = $pieces[0];
	$engine = $schema['engine'];
	$sql = "CREATE TABLE IF NOT EXISTS `{$schema['tablename']}` (\n";
	foreach ($schema['fields'] as $value) {
		if(!empty($value['length'])) {
			$length = "({$value['length']})";
		} else {
			$length = '';
		}

		$signed  = empty($value['signed']) ? ' unsigned' : '';
		if(empty($value['null'])) {
			$null = ' NOT NULL';
		} else {
			$null = '';
		}
		if(isset($value['default'])) {
			$default = " DEFAULT '" . $value['default'] . "'";
		} else {
			$default = '';
		}
		if($value['increment']) {
			$increment = ' AUTO_INCREMENT';
		} else {
			$increment = '';
		}

		$sql .= "`{$value['name']}` {$value['type']}{$length}{$signed}{$null}{$default}{$increment},\n";
	}
	foreach ($schema['indexes'] as $value) {
		$fields = implode('`,`', $value['fields']);
		if($value['type'] == 'index') {
			$sql .= "KEY `{$value['name']}` (`{$fields}`),\n";
		}
		if($value['type'] == 'unique') {
			$sql .= "UNIQUE KEY `{$value['name']}` (`{$fields}`),\n";
		}
		if($value['type'] == 'primary') {
			$sql .= "PRIMARY KEY (`{$fields}`),\n";
		}
	}
	$sql = rtrim($sql);
	$sql = rtrim($sql, ',');

	$sql .= "\n) ENGINE=$engine DEFAULT CHARSET=$charset;\n\n";
	return $sql;
}	

function current_urli()
{
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self     = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info    = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $relate_url   = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
    return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
}

function addFileToZip($path, $zip, $rpath){
	if(function_exists('scandir')){	
		foreach (scandir($path) as $afile) {
			if ($afile == '.' || $afile == '..') {
				continue;
			}
			if (is_dir($path .'/' . $afile)) {
				addFileToZip($path . '/' . $afile, $zip, $rpath);
			} else {
				$zip->addFile($path . '/' . $afile, str_replace($rpath, '', $path) . '/' . $afile);
			}
		}		
	}else{
	    $current_dir = opendir($path);  
	    while(($file = readdir($current_dir)) !== false) {
	        $sub_dir = $path . '/' . $file;  
	        if($file == '.' || $file == '..') {
	        	continue;
	        }
	        if (is_dir($sub_dir)) {
	            addFileToZip($sub_dir, $zip, $rpath);
	        }else{
				$zip->addFile($path . '/' . $file, str_replace($rpath, '', $path) . '/' . $file);
	        }
	    }		
	}
}

?>
	<!DOCTYPE html>
	<html lang="zh-cn">
	<head>
	<title>资源优选--模块打包</title>	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style>
		html { overflow-x: hidden; overflow-y: auto; } 
	</style>
	<body>

	<?php if (!empty($iframe)){?>
		<!-- 模态框（Modal） -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title" id="myModalLabel">
							打包处理结果  - 欢迎使用
						</h4>
					</div>
					<div class="modal-body">
						<iframe src="<?php echo $iframe;?>"  id="myiframe" onload="changeFrameHeight()" marginheight="0" marginwidth="0" frameborder="0" width="100%" style="100%; " scrolling="auto" allowTransparency="true"></iframe>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">关闭
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal -->
		</div>
		<script>
			$('#myModal').modal('show');
			function changeFrameHeight(){
			    var ifm= document.getElementById("myiframe"); 
			    var ifmheight = document.documentElement.clientHeight - 300;
			    ifm.height=ifmheight;
			}
			window.onresize=function(){  
			     changeFrameHeight();  
			} 			 
		</script>	
	<?php }?>
	
	
	<div class="main">
	<div class="panel panel-info">
		<div class="panel-heading">模块打包</div>
		<div class="panel-body">
				<div class="alert alert-success" role="alert">
				<a  style="margin-left:10px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1283597142&site=qq&menu=yes">联系客服QQ</a>
				<a style="margin-left:10px;" target="_blank" href="https://jq.qq.com/?_wv=1027&k=5aaheTa">
				交流群</a>
				<a style="margin-left:10px;" target="_blank" href="#">查找更多模块</a>
				</div>
				<div class="alert alert-info">
				    <p>声明：<br /></p>
					<p>1、首先感谢资源优选原开发者。<br /></p>
					<p> 2、本软件已经本人加入本地生成xml文件代码，去掉远程链接，无需担心模板被上传到其他的远程服务器上，被恶意获取。<br /></p>
					<p>3、打包生成的文件将保存在微信魔方data文件夹下。<br /></p>

				</div>
			
		<form action="" method="post" class="form-horizontal" role="form" id="form1">
			<div id='l' style="width:45%;float:left;margin-left:5%">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">关键词</label>		
					
					<div id="search-module" class="col-sm-8 col-lg-9 col-xs-12">
						<input type="text" name="keyword" class="form-control" placeholder="输入关键词搜索模块"/>
					</div>	
				</div> 
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
					<div class="col-sm-8 col-lg-9 col-xs-12">
						<select name="mname" class="form-control" id="name">
							<option value="">--请选择模块名称--</option>
							<?php foreach($modules as $key=>$module){ ?>							
							<option class="iteme" value="<?php echo $module['name']?>" data-ver="<?php echo $module['version']?>" data-title="<?php echo $module['title']?>"><?php echo $module['title']?> (标识： <?php echo $module['name']?>)</option>
							<?php } ?>
						</select>
					</div>
				</div>				
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
					<div class="col-sm-9 col-xs-12">

						 <div style="float: left">
														
							<label for="dotype1" class="radio-inline"><input type="radio" name="type" value="1" /> 打包模块所有文件</label>
							<label for="dotype2" class="radio-inline"><input type="radio" name="type" value="0" checked/> 只打包安装XML</label>

						</div>

					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">打包检测</label>
					<div class="col-sm-8 col-lg-9 col-xs-12">
						<p class="form-control-static" id="check-name">请选择模块...</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">平台框架类型</label>
					<label for="dotype1" class="radio-inline"><input type="radio" name="iswz" value="1" /> 微赞平台</label>
					<label for="dotype2" class="radio-inline"><input type="radio" name="iswz" value="0" checked/> 微信魔方平台</label>
				</div>	
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">提交打包</label>
					<div class="col-sm-8 col-lg-9 col-xs-12"><input type="submit" name="submit" value="提交" class="btn btn-primary" /></div>
					<input type="hidden" name="token" value="<?php echo $_W['token'];?>" />
					<input type="hidden" name="istheme" value="0" />
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">自定义检测文本</label>		
					
					<div class="col-sm-8 col-lg-9 col-xs-12">
						<input type="text" id="check_keyword" class="form-control" placeholder="检测文本用 | 分割，一般情况下留空即可"/>
					</div>	
				</div> 
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">防盗版检测</label>
					<div class="col-sm-8 col-lg-9 col-xs-12"><a href="javascript:;" id="checkm" class="btn btn-primary " />检测防盗版</a></div>
				
				</div>
			</div>
			
			<div id='r' style="width:45%;float:right;margin-right:5%">					
				<div class="form-group">				
						<label class="col-xs-12 col-sm-3 col-md-2 control-label">选择该模块的数据表</label>
						<div class="col-sm-8 col-lg-9 col-xs-12">
						<input name="sname" id="search-sql" type="text" class="form-control"  placeholder="数据库名称,输入关键词搜索数据表">			
						</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">数据表</label>
						<div class="col-sm-8 col-lg-9 col-xs-12" style="height:400px;overflow:auto;">
							<?php foreach($tables as $tab){ 
							$b = array_pop($tab);
							?>
							<div class="sqls" data-title="<?php echo $b;?>"><input type="checkbox" name="tables[]" value="<?php echo $b;?>" /><?php echo $b;?>
							<span style="margin-left: 100px;">导出数据</span><input type="checkbox" name="add[]" value="<?php echo $b;?>" /></div>
							<?php } ?>
							</div>			
						</div>	
				</div>	
			</div>
			<div class="alert alert-danger" id="checkret" hidden></div>
			<div class="alert alert-danger">
			    <p>
					<center><b>首次使用请认真阅读说明（打包模块建议先检测后再打包）</b></center>
				</p>
			    <br>
			    <p>
					一、[防盗版检测]:自动扫描你要打包的模块内是否存在开发者留下的记号文件，（记号记号包括了你站点的域名和ID被记录在模块的任意文件上）如果检测到了那么请提取后手动处理后再使用，以免不处理安装造成被封号的危险。注：php文件上的 $_SERVER['HTTP_HOST']代码不是记号。
				</p>
				<br>
				<p>
					 二、[温馨提示]:很多的模块数据表是以模块标识开头的，因此可以用模块的标识或者标识的部分作为关键词进行搜索从而锁定数据表。当然这个并非绝对，具体请以实际情况为准模块会自动匹配数据库,未找到匹配的情况下请自行选择数据库名称。				
				</p>
								<br>
				<p>
					 二、[温馨提示]:本软件已加入本地生成xml文件代码，去掉远程链接，无需担心模板被上传到其他的远程服务器上，被恶意获取。				
				</p>
			</div>
			<input type="hidden" name="ver" value=""/>
			<input type="hidden" name="title" value=""/>
			</form>
		</div>
	</div>	
</div>	



<script>
	$(function(){ 		
		$('#search-module input').keyup(function() {
				var a = $(this).val();				
				$('.iteme').each(function() {
					if(a.length > 0 && $(this).attr('data-title').indexOf(a) >= 0) {
						$(this).prop("selected","true");
						$('#name').change();
					}
				});				
		});
		
		$('#search-sql').keyup(function() {
				var a = $(this).val();				
				$("#checkret").hide();
				$("input[name='tables[]']").each(function() {
					$(this).removeAttr("checked");
				});
				
				$("input[name='tables[]']").each(function() {						
					if(a.length > 0 && $(this).val().indexOf(a) >= 0) {
						$(this).prop("checked","true");
					}						
				});
				
				$('.sqls').hide();
				$('.sqls').each(function() {
					if(a.length > 0 && $(this).attr('data-title').indexOf(a) >= 0) {
						$(this).show();						
					}
				});
				if(a.length ==0) {
					$('.sqls').show();
				}
		});
				
		$('#checkm').click(function(){
			var bz = $('#name').children('option:selected').val();
			if(bz == ''){
				alert('请先选择模块');
				return;
			}
			$('#checkret').show();
			$('#checkret').html('开始检测模块防盗版代码,请稍后...');
			$.ajax({
				 type : "post",
				 data : {'name' : bz,'do':'checkm','keyword':$('#check_keyword').val()},
				 url : document.location.href ,
				 dataType : "json",
				 success : function(data){
					

					if(data.ret == '0'){
						$('#checkret').html("检测结果:<br>报告老板危险了这些文件里藏有记号请打包后手动处理<br>"+ data.msg);
					}else{
						$('#checkret').html("检测结果:<br>OK了，暂未检测到防盗版代码可放心打包!");
					}
					
				 },
				 error:function(){
					alert('检测失败!');
				 }
			});			
		});
		
		$('#name').change(function(){
			$("#checkret").hide();		
			$("input[name='ver']").val($(this).children('option:selected').data('ver'));
			$("input[name='title']").val($(this).children('option:selected').data('title'));
			$.ajax({
				 type : "get",
				 data : {'name' : $(this).children('option:selected').val(),'checkmodule':1},
				 url : document.location.href ,
				 dataType : "json",
				 success : function(data){
					 $("input[name='sname']").val('');
					 var a = '';
					 if (data['errcode'] == '0') {
						$("input[name='sname']").val(data['name']);
						a = data['name'];
						$('#check-name').html('<i class="fa fa-check text-success"></i> 标识:' + data['name'] + '  正常: 点击【提交】进行打包');
					 	$('#check-name').css('color','#2f9206');
					 } else  if (data['errcode'] == '1') {					
						$('#check-name').html('<i class="fa fa-times text-warning"></i> 标识:' + data['name'] + '  异常: 未找到模块相关数据表,请右侧自己选择');
						$('#check-name').css('color','red');
					 }else  if (data['errcode'] == '2') {					
						$("input[name='sname']").val(data['sql_name']);
						a = data['sql_name'];
						$('#check-name').css('color','#000fff');					
						$('#check-name').html('<i class="fa fa-times text-warning"></i> 【' + data['name'] + '】  未锁定相关数据表,已从服务器获取数据库表名');
						
					 }else  if (data['errcode'] == '404') {
						$('#check-name').html('<i class="fa fa-times text-warning"></i> 异常: 请选择模块');
					 }
					 

					$("input[name='tables[]']").each(function() {
						$(this).removeAttr("checked");

					});
					
					$("input[name='tables[]']").each(function() {						
						if(a.length > 0 && $(this).val().indexOf(a) >= 0) {
							$(this).prop("checked","true");
						}
							
					});
					
					
					$('.sqls').hide();
					
					$('.sqls').each(function() {
					
						if(a.length > 0 && $(this).attr('data-title').indexOf(a) >= 0) {
							$(this).show();
						}
					});
					if(a.length ==0) {
						$('.sqls').show();
					}
					 

				 },
				 error:function(){
					alert('连接服务器失败!');
				 }
			});

			
			
		}) 
	}) 
</script>

</body>
</html>