<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','adv_effect');//模块标识
class adv_animation_update extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		$this->verify_setting_prms();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function delete()
	{
		
		if(!$this->input['id'])
		{
			return;
		}
		//放入回收箱开始
		$sql = "SELECT * FROM " . DB_PREFIX . "animation WHERE id in (".urldecode($this->input['id']).")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['name'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['animation'] = $row;
			
			//记录日志
			$this->addLogs("删除广告动画",$row, array(), $row['name']);
			//记录日志结束	
			
		}
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		if($res['sucess'])
		{
			$sql = 'DELETE FROM '.DB_PREFIX.'animation WHERE id in('.urldecode($this->input['id']).')';
			$this->db->query($sql);
			$this->addItem('success');
			$this->output();
		}
	}
	function update()
	{
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'animation WHERE id = '.intval($this->input['id']);
		$animation = $this->db->query_first($sql);
		if(!$animation)
		{
			$this->errorOutput(NOID);
		}
		$data = array(
		'name'=>trim(urldecode($this->input['name'])),
		'float_fixed'=>trim(urldecode($this->input['show_format'])),
		'brief'=>trim(urldecode($this->input['brief'])),
		'tpl'=>addslashes(trim(urldecode($this->input['tpl']))),
		'js_para'=>addslashes(trim(urldecode($this->input['js_para']))),
		'is_use'=>intval(trim($this->input['is_use'])),
		);
		$para = $form_style = array();
		if(is_array($this->input['para_zh']) && !empty($this->input['para_zh']))
		{
			foreach ($this->input['para_zh'] as $k=>$v)
			{
				if(trim(urldecode($v)))
				{
					$para[$this->input['para_en'][$k]] = urldecode($v);
					$form_style[$this->input['para_en'][$k]] = $this->input['form_style'][$k];
				}
			}
		}
		$para = $para ? serialize($para) : '';
		$form_style = $form_style ? serialize($form_style) : '';
		//本地上传优先级高
		if(!($file_name = $this->uploadjs()))
		{
			$file_name = '';
			if($this->input['lib_loadjs'])
			{
				$file_name = trim(urldecode($this->input['lib_loadjs']));
				$data['include_js'] = $file_name;
			}
		}
		$sql = 'UPDATE '.DB_PREFIX.'animation SET name = "'.$data['name'].
		'",developer = "'.trim(urldecode($this->input['developer'])).
		'",para = \''.$para.
		'\',form_style = \''.$form_style.
		'\',cost = "'.trim(urldecode($this->input['cost'])).
		'",ip = "'.hg_getip();
		if($file_name)
		{
			$sql .= '",include_js = "'.$file_name;
		}
		$sql .= 
		'",float_fixed = "'.$data['float_fixed'].
		'",brief = "'.$data['brief'].
		'",tpl = "'.$data['tpl'].
		'",js_para = "'.$data['js_para'].
		'",is_use = '.$data['is_use'] . ' WHERE id = '.intval($this->input['id']);
		$this->db->query($sql);
		if($this->db->affected_rows())
		{
			$this->db->query("UPDATE ".DB_PREFIX.'animation set update_user_id='.$this->user['user_id'].', update_user_name="'.$this->user['user_name'].'" WHERE id = '.$animation['id']);
		}
		//记录日志
		$this->addLogs("更新广告动画", $animation, $data,$data['name']);
		//记录日志结束	
		$this->addItem('success');
		$this->output();
	}
	function uploadjs()
	{
		$file = $_FILES['loadjs'];
		if(!$file)
		{
			return false;
		}
		if($file['type'] != 'text/javascript')
		{
			$this->errorOutput(TYPE_ERROR . $file['type'] );
		}
		if(!is_uploaded_file($file['tmp_name']))
		{
			return false;
		}
		$file_name = ADV_DATA_DIR  . 'script/' . $file['name'];
		hg_mkdir(ADV_DATA_DIR  . 'script/');
		if(is_file($file_name))
		{
			$this->errorOutput(JS_FILE_EXISTS);
		}
		$this->addLogs('上传js文件', '', '', $file['name']);
		if(move_uploaded_file($file['tmp_name'], $file_name))
		{
			$sql = 'REPLACE INTO '.DB_PREFIX.'loadjs SET jsname = "'.$file['name']. '", jstext="'.addslashes(@file_get_contents($file_name)).'"';
			$this->db->query($sql);
			return $file['name'];
		}
		return false;
	}
	function audit()
	{
		
	}
	function create()
	{
		
		$data = array(
		'name'=>trim($this->input['name']),
		'developer'=>trim(urldecode($this->input['developer'])),
		);
		if(!$this->input['name'])
		{
			$this->errorOutput('动画名称必须');
		}
		//本地上传优先级高
		if(!($file_name = $this->uploadjs()))
		{
			$file_name = '';
			if($this->input['lib_loadjs'])
			{
				$file_name = trim(urldecode($this->input['lib_loadjs']));
			}
		}
		$para = $form_style = array();
		if(is_array($this->input['para_zh']) && !empty($this->input['para_zh']))
		{
			foreach ($this->input['para_zh'] as $k=>$v)
			{
				if(trim(urldecode($v)))
				{
					$para[$this->input['para_en'][$k]] = urldecode($v);
					$form_style[$this->input['para_en'][$k]] = $this->input['form_style'][$k];
				}
			}
		}
		$para = $para ? serialize($para) : '';  
		$sql = 'INSERT INTO '.DB_PREFIX.'animation SET name = "'.$data['name'].
		'",developer = "'.$data['developer'].
		'",para = \''.$para.
		'\',cost = "'.trim(urldecode($this->input['cost'])).
		'",float_fixed = "'.trim(urldecode($this->input['show_format'])).
		'",brief = "'.trim(urldecode($this->input['brief'])).
		'",tpl = "'.addslashes(trim(urldecode($this->input['tpl']))).		
		'",js_para = "'.addslashes(trim(urldecode($this->input['js_para']))).
		'",ip = "'.hg_getip().
		'",include_js = "'.$file_name.
		'",user_id = "'.$this->user['user_id'].
		'",create_time = "'.TIMENOW.
		'",user_name = "'.trim(urldecode($this->user['user_name'])).
		'",is_use = '.intval(trim($this->input['is_use']));
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		//记录日志
		$this->addLogs("新增广告动画",array(), $data, $data['name'],$data['id']);
		//记录日志结束
		$this->addItem('success');
		$this->output();
	}
	function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
	function get_animation_js()
	{
		$jsname = $this->input['jsname'];
		if(!$jsname)
		{
			return;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'loadjs WHERE jsname = "'.strval($jsname).'"';
		$result = $this->db->query_first($sql);
		$this->addItem($result);
		$this->output();
	}
	function getAllanimationJS()
	{
		$sql = 'SELECT jsname FROM '.DB_PREFIX.'loadjs';
		$q = $this->db->query($sql);
		$r = array();
		while($row = $this->db->fetch_array($q))
		{
			$r[$row['jsname']] = $row['jsname'];
		}
		$this->addItem($r);
		$this->output();
	}
	function savejs()
	{
		
		$data = array(
		'jstext'=>$this->input['jstext'],
		'jsname'=>$this->input['jsname'],
		);
		$sql = 'SELECT jsname,jstext FROM '.DB_PREFIX.'loadjs WHERE jsname = "'.$data['jsname'].'"';
		$js = $this->db->query_first($sql);
		if(!$js)
		{
			$this->errorOutput("js".$data['jsname']."文件存在");
		}
		$sql = 'UPDATE '.DB_PREFIX.'loadjs SET jstext="'.addslashes($data['jstext']).'" WHERE jsname="'.$data['jsname'].'"';
		$this->db->query($sql);
		hg_mkdir(ADV_DATA_DIR  . 'script/');
		file_put_contents(ADV_DATA_DIR . 'script/' .$data['jsname'],$data['jstext']);
		//记录日志
		$this->addLogs("修改动画js文件", $js,$data,$data['jsname']);
		//记录日志结束
		$this->addItem('success');
		$this->output();
	}
	function publish()
	{
		
	}
	function sort()
	{
		
	}
}
$ouput= new adv_animation_update();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();