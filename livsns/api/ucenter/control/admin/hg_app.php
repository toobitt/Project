<?php

/*
	[UCenter] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: app.php 1090 2011-05-03 07:33:28Z cnteacher $
*/

!defined('IN_UC') && exit('Access Denied');

class control extends adminbase {

	function __construct() {
		$this->control();
	}

	function control() {
		parent::__construct();
	/*
		$this->check_priv();
		if(!$this->user['isfounder'] && !$this->user['allowadminapp']) {
			$this->message('no_permission_for_this_module');
		}
	*/
		$this->load('app');
		$this->load('misc');
	}

	function onls() {
		$status = $affectedrows = 0;
		if(!empty($_REQUEST['delete'])) {
			$affectedrows += $_ENV['app']->delete_apps($_REQUEST['delete']);
			foreach($_REQUEST['delete'] as $k => $appid) {
				$_ENV['app']->alter_app_table($appid, 'REMOVE');
				unset($_REQUEST['name'][$k]);
			}
			$this->load('cache');
			$_ENV['cache']->updatedata();
			$this->writelog('app_delete', 'appid='.implode(',', $_POST['delete']));
			$status = 2;

			$this->_add_note_for_app();
		}

		$a = getgpc('a');
		$applist = $_ENV['app']->get_apps();
		
		if (getgpc('open', 'P'))
		{
			//暂时将 tagtemplates unset掉
			if (!empty($applist))
			{
				foreach ($applist AS $k=>$v)
				{
					unset($applist[$k]['tagtemplates']);
				}
			}
			echo json_encode($applist);exit;
		}

		$this->view->assign('status', $status);
		$this->view->assign('a', $a);
		$this->view->assign('applist', $applist);

		$this->view->display('admin_app');
	}

	function onadd() {
		if(!getgpc('submitcheck', 'P')) {
			$md5ucfounderpw = md5(UC_FOUNDERPW);
			$this->view->assign('md5ucfounderpw', $md5ucfounderpw);

			$a = getgpc('a');
			$this->view->assign('a', $a);
			$typelist = array('DISCUZX'=>'DiscuzX','UCHOME'=>'UCenter Home','XSPACE'=>'X-Space','DISCUZ'=>'Discuz!','SUPESITE'=>'SupeSite','SUPEV'=>'SupeV','ECSHOP'=>'ECShop','ECMALL'=>'ECMall','OTHER'=>$this->lang['other']);
			$this->view->assign('typelist', $typelist);
			$this->view->display('admin_app');
		} else {
			$type = urldecode(getgpc('type', 'P'));
			$name = urldecode(getgpc('name', 'P'));
			$url = urldecode(getgpc('url', 'P'));
			$ip = urldecode(getgpc('ip', 'P'));
			
			$viewprourl = urldecode(getgpc('viewprourl', 'P'));
			$authkey = urldecode(getgpc('authkey', 'P'));
			$authkey = $this->authcode($authkey, 'ENCODE', UC_MYKEY);
			$synlogin = getgpc('synlogin', 'P');
			$recvnote = getgpc('recvnote', 'P');
			$apifilename = urldecode(trim(getgpc('apifilename', 'P')));
			//$allowips = getgpc('allowips', 'P');

			$tagtemplates = array();
			$tagtemplates['template'] = urldecode(getgpc('tagtemplates', 'P'));
			$tagfields = explode("\n", urldecode(getgpc('tagfields', 'P')));
			foreach($tagfields as $field) {
				$field = trim($field);
				list($k, $v) = explode(',', $field);
				if($k) {
					$tagtemplates['fields'][$k] = $v;
				}
			}
			$tagtemplates = $this->serialize($tagtemplates, 1);

			if(!$_ENV['misc']->check_url($url)) {
				echo json_encode('app_add_url_invalid');exit;
			}
			if(!empty($ip) && !$_ENV['misc']->check_ip($ip)) {
				echo json_encode('app_add_ip_invalid');exit;
			}
			
			$app = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."applications WHERE name='$name'");
			if($app) {
				echo json_encode('app_add_name_invalid');exit;
			} else {
				$extra = serialize(array('apppath'=> urldecode(getgpc('apppath', 'P'))));
				$this->db->query("INSERT INTO ".UC_DBTABLEPRE."applications SET name='$name', url='$url', ip='$ip',
					viewprourl='$viewprourl', apifilename='$apifilename', authkey='$authkey', synlogin='$synlogin',
					type='$type', recvnote='$recvnote', extra='$extra',
					tagtemplates='$tagtemplates'");
				$appid = $this->db->insert_id();
			}

			$this->_add_note_for_app();

			$this->load('cache');
			$_ENV['cache']->updatedata('apps');

			$_ENV['app']->alter_app_table($appid, 'ADD');
			$this->writelog('app_add', "appid=$appid; appname=$_POST[name]");
			if (getgpc('open', 'P'))
			{
				echo json_encode(array('appid'=>$appid));exit;
			}
			header("location: admin.php?m=app&a=detail&appid=$appid&addapp=yes&sid=".$this->view->sid);
		}
	}

	function onping() {
		$ip = urldecode(getgpc('ip'));
		$url = urldecode(getgpc('url'));
		$appid = intval(getgpc('appid'));
		$app = $_ENV['app']->get_app_by_appid($appid);
		$status = '';
		if($app['extra']['apppath'] && @include $app['extra']['apppath'].'./api/'.$app['apifilename']) {
			$uc_note = new uc_note();
			$status = $uc_note->test($note['getdata'], $note['postdata']);
		} else {
			$this->load('note');
			$url = $_ENV['note']->get_url_code('test', '', $appid);
			$status = $_ENV['app']->test_api($url, $ip);
		}
	
		if($status == '1') {
			if (getgpc('open', 'P'))
			{
				echo 1;
				exit;
			}
			echo 'document.getElementById(\'status_'.$appid.'\').innerHTML = "<img src=\'images/correct.gif\' border=\'0\' class=\'statimg\' \/><span class=\'green\'>'.$this->lang['app_connent_ok'].'</span>";testlink();';
		} else {
			if (getgpc('open', 'P'))
			{
				echo 0;
				exit;
			}
			echo 'document.getElementById(\'status_'.$appid.'\').innerHTML = "<img src=\'images/error.gif\' border=\'0\' class=\'statimg\' \/><span class=\'red\'>'.$this->lang['app_connent_false'].'</span>";testlink();';
		}

	}

	function onhgdetail()
	{
		$appid = getgpc('appid');
		$updated = false;
		$app = $_ENV['app']->get_app_by_appid($appid);

		if (getgpc('open', 'P'))
		{
			//暂时将 tagtemplates unset掉
			if (!empty($app))
			{
				unset($app['tagtemplates']);	
			}
			echo json_encode($app);exit;
		}
	}

	function ongetConfig()
	{
		if (getgpc('open', 'P'))
		{
			$conf = array(
			'UC_CONNECT'	=> '',
			'UC_DBHOST'	=> UC_DBHOST,
			'UC_DBUSER'	=> UC_DBUSER,
			'UC_DBPW' => UC_DBPW,
			'UC_DBNAME' => UC_DBNAME,
			'UC_DBCHARSET' => UC_DBCHARSET,
			'UC_DBTABLEPRE' => UC_DBTABLEPRE,
			'UC_DBCONNECT' => UC_DBCONNECT,
			'UC_KEY' => '',
			'UC_API' => '',
			'UC_CHARSET' => UC_CHARSET,
			'UC_IP' => '',
			'UC_APPID' => '',
			'UC_PPP' => UC_PPP,
			);
		
			echo json_encode($conf);
		}
	}

	function ondetail() {
		$appid = getgpc('appid');
		$updated = false;
		$app = $_ENV['app']->get_app_by_appid($appid);

		if(getgpc('submitcheck', 'P')) {//$this->submitcheck()
			$type = urldecode(getgpc('type', 'P'));
			$name = urldecode(getgpc('name', 'P'));
			$url = urldecode(getgpc('url', 'P'));
			$ip = urldecode(getgpc('ip', 'P'));
			$viewprourl = urldecode(getgpc('viewprourl', 'P'));
			$apifilename = urldecode(trim(getgpc('apifilename', 'P')));
			$authkey = urldecode(getgpc('authkey', 'P'));
			$authkey = $this->authcode($authkey, 'ENCODE', UC_MYKEY);
			$synlogin = urldecode(getgpc('synlogin', 'P'));
			$recvnote = urldecode(getgpc('recvnote', 'P'));
			$extraurl = urldecode(getgpc('extraurl', 'P'));
			//$allowips = getgpc('allowips', 'P');
			if(getgpc('apppath', 'P')) {
				$app['extra']['apppath'] = $this->_realpath(urldecode(getgpc('apppath', 'P')));
				if($app['extra']['apppath']) {
					$apifile = $app['extra']['apppath'].'./api/uc.php';
					if(!file_exists($apifile)) {
						$this->message('app_apifile_not_exists', 'BACK', 0, array('$apifile' => $apifile));
					}
					$s = file_get_contents($apifile);
					preg_match("/define\(\'UC_CLIENT_VERSION\'\, \'([^\']+?)\'\)/i", $s, $m);
					$uc_client_version = @$m[1];

					//判断版本
					if(!$uc_client_version || $uc_client_version <= '1.0.0') {
						$this->message('app_apifile_too_low', 'BACK', 0, array('$apifile' => $apifile));
					}
				} else {
					$this->message('app_path_not_exists');
				}
			} else {
				$app['extra']['apppath'] = '';
			}
			$app['extra']['extraurl'] = array();
			if($extraurl) {
				foreach(explode("\n", $extraurl) as $val) {
					if(!$val = trim($val)) continue;
					$app['extra']['extraurl'][] = $val;
				}
			}
			$tagtemplates = array();
			$tagtemplates['template'] = MAGIC_QUOTES_GPC ? stripslashes(urldecode(getgpc('tagtemplates', 'P'))) : urldecode(getgpc('tagtemplates', 'P'));
			$tagfields = explode("\n", urldecode(getgpc('tagfields', 'P')));
			foreach($tagfields as $field) {
				$field = trim($field);
				list($k, $v) = explode(',', $field);
				if($k) {
					$tagtemplates['fields'][$k] = $v;
				}
			}
			$tagtemplates = $this->serialize($tagtemplates, 1);

			$extra = addslashes(serialize($app['extra']));
			$this->db->query("UPDATE ".UC_DBTABLEPRE."applications SET appid='$appid', name='$name', url='$url',
				type='$type', ip='$ip', viewprourl='$viewprourl', apifilename='$apifilename', authkey='$authkey',
				synlogin='$synlogin', recvnote='$recvnote', extra='$extra', 
				tagtemplates='$tagtemplates'
				WHERE appid='$appid'");
			$updated = true;
	
			$this->load('cache');
			$_ENV['cache']->updatedata('apps');
			$this->cache('settings');
			$this->writelog('app_edit', "appid=$appid");

			$this->_add_note_for_app();
			$app = $_ENV['app']->get_app_by_appid($appid);

			echo json_encode(array('appid' => $appid));
			exit;
		}
		$tagtemplates = $this->unserialize($app['tagtemplates']);
		$template = htmlspecialchars($tagtemplates['template']);
		$tmp = '';
		if(is_array($tagtemplates['fields'])) {
			foreach($tagtemplates['fields'] as $field => $memo) {
				$tmp .= $field.','.$memo."\n";
			}
		}
		$tagtemplates['fields'] = $tmp;
		$a = getgpc('a');
		$this->view->assign('a', $a);
		$app = $_ENV['app']->get_app_by_appid($appid);
		$this->view->assign('isfounder', $this->user['isfounder']);
		$this->view->assign('appid', $app['appid']);
		$this->view->assign('allowips', $app['allowips']);
		$this->view->assign('name', $app['name']);
		$this->view->assign('url', $app['url']);
		$this->view->assign('ip', $app['ip']);
		$this->view->assign('viewprourl', $app['viewprourl']);
		$this->view->assign('apifilename', $app['apifilename']);
		$this->view->assign('authkey', $app['authkey']);
		$synloginchecked = array($app['synlogin'] => 'checked="checked"');
		$recvnotechecked = array($app['recvnote'] => 'checked="checked"');
		$this->view->assign('synlogin', $synloginchecked);
		$this->view->assign('charset', $app['charset']);
		$this->view->assign('dbcharset', $app['dbcharset']);
		$this->view->assign('type', $app['type']);
		$this->view->assign('recvnotechecked', $recvnotechecked);
		$typelist = array('DISCUZX'=>'DiscuzX','UCHOME'=>'UCenter Home','XSPACE'=>'X-Space','DISCUZ'=>'Discuz!','SUPESITE'=>'SupeSite','SUPEV'=>'SupeV','ECSHOP'=>'ECShop','ECMALL'=>'ECMall','OTHER'=>$this->lang['other']);
		$this->view->assign('typelist', $typelist);
		$this->view->assign('updated', $updated);
		$addapp = getgpc('addapp');
		$this->view->assign('addapp', $addapp);
		$this->view->assign('extraurl', implode("\n", $app['extra']['extraurl']));
		$this->view->assign('apppath', $app['extra']['apppath']);
		$this->view->assign('tagtemplates', $tagtemplates);
		$this->view->display('admin_app');
	}

	function _add_note_for_app() {
		$this->load('note');
		$notedata = $this->db->fetch_all("SELECT appid, type, name, url, ip, viewprourl, apifilename, charset, synlogin, extra, recvnote FROM ".UC_DBTABLEPRE."applications");
		$notedata = $this->_format_notedata($notedata);
		$notedata['UC_API'] = UC_API;
		$_ENV['note']->add('updateapps', '', $this->serialize($notedata, 1));
		$_ENV['note']->send();	
	}

	function _format_notedata($notedata) {
		$arr = array();
		foreach($notedata as $key => $note) {
			$note['extra'] = unserialize($note['extra']);
			$arr[$note['appid']] = $note;
		}
		return $arr;
	}

	function _realpath($path) {
		return realpath($path).'/';
	}
}

?>