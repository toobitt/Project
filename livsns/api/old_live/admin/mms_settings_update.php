<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: vod.php 6468 2012-04-20 01:51:14Z develop_tong $
***************************************************************************/
require_once('global.php');
class  mms_settings_update extends adminUpdateBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function update()
	{
		if(!$this->input['cfg_type'])
		{
			$this->errorOutput('没有类型');
		}
		
		if(intval($this->input['cfg_type']) == 3)
		{
			$sql = " DELETE FROM ".DB_PREFIX."settings  WHERE type = 0 ";
		}
		else 
		{
			$sql = " DELETE FROM ".DB_PREFIX."settings  WHERE type = '".intval($this->input['cfg_type'])."'";
		}
		
		$this->db->query($sql);
		
		//再插入数据
		switch (intval($this->input['cfg_type']))
		{
			case 1:
					  $data = array(
				  			'host' 	   		=> urldecode($this->input['host']),
					  		'user' 	   		=> urldecode($this->input['user']),
					  		'pass'	   		=> urldecode($this->input['pass']),
					  		'database' 		=> urldecode($this->input['database']),
					  		'charset'     	=> urldecode($this->input['charset']),
					  		'db_prefix'     => urldecode($this->input['db_prefix']),
					  		'desc'     		=> urldecode($this->input['desc']),
					  		'pconncet'      => urldecode($this->input['pconncet']),
					  );
					  
					  $sql  = " INSERT INTO ".DB_PREFIX."settings SET ";
					  $sql .= " type = 1,".
							  " var_name = '',".
							  " value = '".serialize($data)."',".
							  " is_edit = 1,".
							  " is_open = 1 ";

					  $this->db->query($sql);
					  break;
					
			case 2:
					 $key_arr = $this->input['df_key'];
					 $val_arr = $this->input['df_val'];
					 for($i = 0;$i<count($key_arr);$i++)
					 {
					 	$sql = "";
					 	$sql  = " INSERT INTO ".DB_PREFIX."settings SET ";
						$sql .= " type = 2,".
								" var_name = '".urldecode($key_arr[$i])."',".
								" value	   = '".urldecode($val_arr[$i])."',".
								" is_edit = 1,".
								" is_open = 1 ";
						$this->db->query($sql);
					 }
					 break;
			case 3:	
					 $key_arr = $this->input['gg_key'];
					 $val_arr = $this->input['gg_val'];
					 for($i = 0;$i<count($key_arr);$i++)
					 {
					 	eval('$val = '.urldecode($val_arr[$i]).';');
					 	$sql = "";
					 	$sql  = " INSERT INTO ".DB_PREFIX."settings SET ";
						$sql .= " type = 0,".
								" var_name = '".urldecode($key_arr[$i])."',".
								" value	   = '".serialize($val)."',".
								" is_edit = 1,".
								" is_open = 1 ";
						$this->db->query($sql);
					 }
					 break;
		}
		
		$this->addItem('success');
		$this->output();
	}
	public function create()
	{
		
	}

	public function delete()
	{
		
	}
	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
}

$out = new mms_settings_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>