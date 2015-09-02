<?php
define('ROOT_DIR', './../../');
define('MOD_UNIQUEID','topic_update');
define('SCRIPT_NAME', 'topic_update');
require(ROOT_DIR . 'global.php');
require_once (CUR_CONF_PATH . 'lib/multifunctional.class.php');
require_once (CUR_CONF_PATH . 'lib/attach.class.php');
class topic_update extends adminBase
{
	protected $value = array();
	function __construct()
	{
		parent::__construct();
		$this->attach = new multifunc();
		$this->attachlib = new attach();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NO_ID);
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'topic WHERE id IN('.$this->input['id'].')';
		$this->db->query($sql);
		$this->addItem($this->input['id']);
		$this->output();
	}
	function create()
	{
		$this->initdata();
		unset($this->value['id']);
		$this->excute_sql();
		$this->end();
	}
	protected function end()
	{
		$this->addItem($this->value);
		$this->output();
	}
	function update()
	{
		$this->initdata();
		unset($this->value['create_time']);
		unset($this->value['status']);
		if(!$this->value['id'])
		{
			$this->errorOutput(NO_ID);
		}
		$this->excute_sql();
		$this->end();
	}
	function audit()
	{
		$status = intval($this->input['status']);
		$topic_id = explode(',', $this->input['id']);
		if(!$topic_id)
		{
			$this->errorOutput(NO_ID);
		}
		$sql = 'UPDATE ' .DB_PREFIX  . 'topic set status = '.$status . ' where id IN('.implode(',', $topic_id).')';
		$this->db->query($sql);
		$this->addItem($topic_id);
		$this->output();
	}
	protected function initdata()
	{
		$avatar = $this->attachlib->get_avatar($this->user['user_id']);
		
	 	$this->value = array(
		'id'		=>	$this->input['id'],
		'title'	=>	$this->input['title'],
	 	'brief'	=>	urldecode($this->input['brief']),
		'indexpic'		=>	'',
		'status'		=>	intval($this->input['status']),
		'user_id'	=>	$this->user['user_id'],
		'user_name'		=>	$this->user['user_name'],
	 	'avatar'=>$avatar ? addslashes(serialize($avatar)) : '',
		'create_time'		=>	TIMENOW,
	 	'sid'			=>intval($this->input['sid']),
		'update_time'	=>TIMENOW,
	 	'lon'=>$this->input['lon'],
	 	'lat'=>$this->input['lat'],
	 	
	 	'gpsx'=>$this->input['gpsx'],
	 	'gpsy'=>$this->input['gpsy'],
	 	'aid'=>$this->input['aid'],
		);
		if($this->value['lat'] && $this->value['lon'])
		{
			$loc = FromBaiduToGpsXY($this->value['lon'], $this->value['lat']);
			$this->value['gpsx'] = $loc['x'];
			$this->value['gpsy'] = $loc['y'];
		}
		if(!$this->value['title'])
		{
			$this->errorOutput("话题内容不能为空");
		}
	
		if($this->value['aid'])
		{
			$this->value['aid'] = $this->attachlib->tmp2att(urldecode($this->value['aid']));
		}
	
		if($this->value['id'])
		{
			$record = $this->db->query_first('SELECT aid FROM '.DB_PREFIX.'topic WHERE id IN('.$this->value['id'].')');
			if($record['aid'])
			{
				$this->value['aid'] .= ',' . $record['aid'];
			}
		}
		
		$location = array(
		'lat'=>$this->value['lat'],
	 	'lon'=>$this->value['lon'],
	 	'gpsx'=>$this->value['gpsx'],
	 	'gpsy'=>$this->value['gpsy'],
		'address'=>$this->input['address'],
		);
		$map_aid = $this->attachlib->map($location);
		if($map_aid)
		{
			$this->value['aid'] .= ',' . $map_aid;
		}
		$loc_aid = $this->attachlib->outlink(urldecode($this->input['outlink']));
		if($loc_aid)
		{
			$this->value['aid'] .= ',' . $loc_aid;
		}
		$this->value['aid'] = trim($this->value['aid'],',');
		if(!$_FILES['indexpic'])
		{
			unset($this->value['indexpic']);
		}
		else
		{//上传图片服务器
			$attach_info = $this->attach->upload($_FILES['indexpic'], 'img');
			$attach_info = !empty($attach_info[0]) ? $attach_info[0] : array();
			//file_put_contents(CACHE_DIR.'debug.txt', var_export($attach_info,1));
			if($attach_info)
			{
				$this->value['indexpic'] = addslashes(serialize(array(
				'host'=>$attach_info['host'],
				'dir'=>$attach_info['dir'],
				'filepath'=>$attach_info['filepath'],
				'filename'=>$attach_info['filename']
				)));
			}
		}
	}
	function show()
	{
		
	}
	protected function excute_sql()
	{
		if(!$this->value['id'])
		{
			$op = 'INSERT INTO ';
			$where = '';
		}
		else
		{
			$op = 'UPDATE ';
			$where = ' WHERE id = '. $this->value['id'];
			$_id = $this->value['id'];
			unset($this->value['id']);
		}
		$sql =  $op .DB_PREFIX.'topic SET ';
		foreach ($this->value as $k=>$v)
		{
			$sql .= " {$k} = \"{$v}\",";
		}
		$sql = trim($sql, ',') . $where;
		$this->db->query($sql);
		$this->value['id'] = !$where ? $this->db->insert_id() : $_id;
		$this->value['indexpic'] = ($tmp = unserialize(stripslashes($this->value['indexpic']))) ? $tmp : array();
		if(!$where)
		{
			$this->db->query('UPDATE ' . DB_PREFIX . 'topic set order_id='.$this->value['id'] . ' where id = '.$this->value['id']
			);
		}
	}
}
include(ROOT_PATH . 'excute.php');