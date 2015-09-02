<?php

/*
	[UCenter] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: badword.php 3195 2011-03-30 10:01:09Z zdl $
*/

!defined('IN_UC') && exit('Access Denied');
define("ROOT_PATH","../");

class control extends adminbase {
	
	var $banw;
	
	function __construct() {
		$this->control();
	}

	function control() {
		parent::__construct();
		$this->check_priv();
		if(!$this->user['isfounder'] && !$this->user['allowadminbadword']) {
			$this->message('no_permission_for_this_module');
		}
		$this->load('badword');
	}

	function onls() {
		$page = getgpc('page');
		$find = getgpc('find', 'P');
		$findnew = getgpc('findnew', 'P');
		$delete = getgpc('delete', 'P');
		$replacement = getgpc('replacement','P');
		$replacementnew = getgpc('replacementnew', 'P');
		
		/*操作添加后台程序*/
		include(ROOT_PATH."lib/class/banword.class.php");
		include(ROOT_PATH."lib/func/functions.php");//取cookies
		$this->banw = new banword();
		if(!empty($findnew) && !empty($replacementnew))
		{
			$this->banw->addBanword($findnew,$replacementnew);//sns_badword liv_banword表
		}
		
		if($find) {
			foreach($find as $id => $arr) {

				$this->banw->update_badword($find[$id], $replacement[$id], $id);//sns_badword liv_banword表

				$_ENV['badword']->update_badword($find[$id], $replacement[$id], $id);
			}
		}
		$status = 0;
		if($findnew) {
			$_ENV['badword']->add_badword($findnew, $replacementnew, $this->user['username']);
			$status = 1;
			$this->writelog('badword_add', 'findnew='.htmlspecialchars($findnew).'&replacementnew='.htmlspecialchars($replacementnew));
		}
		
		if(@$delete) {
			$sns_delete = serialize($delete); 
			$this->banw->delete_badword($sns_delete);//sns_badword liv_banword表

			$_ENV['badword']->delete_badword($delete);
			$status = 2;
			$this->writelog('badword_delete', "delete=".implode(',', $delete));
		}

		if(getgpc('multisubmit', 'P')) {
			$badwords = getgpc('badwords', 'P');
			$type = getgpc('type', 'P');

			if($type == 0) {
				$this->banw->truncate_badword();//清空表数据
				$_ENV['badword']->truncate_badword();
				$type = 1;
			}
			$arr = explode("\n", str_replace(array("\r", "\n\n"), array("\r", "\n"), $badwords));

			foreach($arr as $k => $v) {
				$arr2 = explode("=", $v);

				$arr2[1] = empty($arr2[1]) ? '**' : $arr2[1];
				$this->banw->addBanword($arr2[0],$arr2[1]);//sns_badword liv_banword表
				
				$_ENV['badword']->add_badword($arr2[0], $arr2[1], $this->user['username'], $type);
			}
		}

		if($status > 0) {
			$notedata = $_ENV['badword']->get_list($page, 1000000, 1000000);
			$this->load('note');
			$_ENV['note']->add('updatebadwords', '', $this->serialize($notedata, 1));
			$_ENV['note']->send();

			$this->load('cache');
			$_ENV['cache']->updatedata('badwords');
		}

		$num = $_ENV['badword']->get_total_num();
		$badwordlist = $_ENV['badword']->get_list($page, UC_PPP, $num);
		$multipage = $this->page($num, UC_PPP, $page, 'admin.php?m=badword&a=ls');

		$this->view->assign('status', $status);
		$this->view->assign('badwordlist', $badwordlist);
		$this->view->assign('multipage', $multipage);

		$this->view->display('admin_badword');
		
	}

	function onexport() {
		$data = $_ENV['badword']->get_list(1, 1000000, 1000000);
		$s = '';
		if($data) {
			foreach($data as $v) {
				$s .= $v['find'].'='.$v['replacement']."\r\n";
			}
		}
		@header('Content-Disposition: inline; filename=CensorWords.txt');
		@header("Content-Type: text/plain");
		echo $s;

	}

}

?>