<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: $
***************************************************************************/
define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','livcms');//模块标识
require(ROOT_DIR . 'global.php');
require('livcms_frm.php');
class site extends LivcmsFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function getSiteinfo()
	{
		
	}
	public function create()
	{
		if(!$this->input['site_name'])
		{
			exit(0);
		}
		$data = array(
		'siteid' => intval($this->input['site_id']),
		'sitename' => trim($this->input['site_name']),
		'content' => trim(urldecode($this->input['content'])),
		'sitedir' => trim(urldecode($this->input['sitedir'])),
		'weburl' => trim(urldecode($this->input['weburl'])),
		'sitekeywords' =>  trim(urldecode($this->input['site_keywords'])),
		'token'=>trim(urldecode($this->input['token'])),
		'indexname'=>'index',
		'maketype'=>1,
		'suffix'=>'.html',
		'template_img_dir'=>'res/',
		
		'material_fmt' => trim(urldecode($this->input['material_fmt'])),
		'material_url' =>  trim(urldecode($this->input['material_url'])),
		'template_img_url' => trim(urldecode($this->input['tem_material_url'])),
		'program_dir' => trim(urldecode($this->input['program_dir'])),
		'program_url' => trim(urldecode($this->input['program_url'])),
		'jsphpdir' =>  trim(urldecode($this->input['jsphpdir'])),
		);
		$sql = 'INSERT INTO '.DB_PREFIX.'siteconf SET ';
		foreach ($data as $k=>$v)
		{
			$sql .=  $k . ' = \''.$v.'\',';
		}
		$sql  = (trim($sql, ','));
		$this->db->query($sql);
		echo $this->db->insert_id();
		exit();
	}
	public function update()
	{
		if(!$this->input['cms_siteid'])
		{
			exit('0');
		}
		$data = array(
		'sitename' => trim($this->input['site_name']),
		'content' => trim(urldecode($this->input['content'])),
		'sitedir' => trim(urldecode($this->input['sitedir'])),
		'weburl' => trim(urldecode($this->input['weburl'])),
		'sitekeywords' =>  trim(urldecode($this->input['site_keywords'])),
		
		'maketype' => intval($this->input['produce_format']),
		'indexname' => trim(urldecode($this->input['indexname'])),
		'suffix' => trim(urldecode($this->input['suffix'])),
		'material_fmt' => trim(urldecode($this->input['material_fmt'])),
		'material_url' =>  trim(urldecode($this->input['material_url'])),
		'template_img_url' => trim(urldecode($this->input['tem_material_url'])),
		'template_img_dir' => trim(urldecode($this->input['tem_material_dir'])),
		'program_dir' => trim(urldecode($this->input['program_dir'])),
		'program_url' => trim(urldecode($this->input['program_url'])),
		'jsphpdir' =>  trim(urldecode($this->input['jsphpdir'])),
		);
		$sql = 'UPDATE '.DB_PREFIX.'siteconf SET ';
		foreach ($data as $k=>$v)
		{
			$sql .=  $k . ' = \''.$v.'\',';
		}
		$sql  = trim($sql, ',') . ' WHERE siteid = '.intval($this->input['cms_siteid']);
		if($this->db->query($sql))
		{
			exit('1');
		}
		exit('0');
	}
	public function delete()
	{
		if(!$this->input['siteid'])
		{
			return;
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'siteconf WHERE siteid = '.intval($this->input['siteid']);
		//file_put_contents('1.txt', $sql);
		$this->db->query($sql);
		echo $this->db->affted_rows();
		exit;
	}
	public function unknown()
	{
		exit('0000000');
	}
}
$out = new site();
$action = $_INPUT['a'];
if(!method_exists($out, $action))
{
	$action = 'unknown';
}
$out->$action();
?>