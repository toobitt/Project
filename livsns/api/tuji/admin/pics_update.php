<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID','tuji');//模块标识
class pics_update extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	function create()
	{
		
	}
	
	function sort()
	{
		
	}
	
	function publish()
	{
		
	}
	
	function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = "SELECT p.*,t.total_pic FROM ".DB_PREFIX.'pics p LEFT JOIN '.DB_PREFIX.'tuji t ON p.tuji_id = t.id WHERE p.id in('.urldecode($this->input['id']).')';
		$q = $this->db->query($sql);
		$tuji_ids = array();
		while($r = $this->db->fetch_array($q))
		{
			$tuji_ids[$r['tuji_id']][] = $r['id'];
			@unlink($r['path'].'/'.$r['new_name']);
		}
		if($tuji_ids)
		{
			$sql = "UPDATE ".DB_PREFIX.'tuji SET total_pic = total_pic - ';
			foreach($tuji_ids as $k=>$v)
			{
				$sql .= ' CASE id WHEN '.$k.' THEN '.count($tuji_ids[$k]);
			}
			$sql .= ' END';
			$this->db->query($sql);
		}
		$sql = "DELETE FROM ".DB_PREFIX.'pics WHERE id IN('.urldecode($this->input['id']).')';
		if($this->db->query($sql))
		{
			$this->addItem('success');
		}
		else
		{
			$this->addItem('error');
		}
		$this->output();
	}

	public function delete_comp()
	{
		return true;
	}
	
	function update1()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$this->input['id'] = urldecode($this->input['id']);
		
		if(strpos($this->input['id'],',')!== false)
		{
			$ids = explode(',',$this->input['id']);
			$sql = "UPDATE ".DB_PREFIX."pics SET `desc` = CASE id ";
			foreach($ids as $index=>$id)
			{
				$sql .= ' WHEN '.$id.' THEN "'.trim(urldecode($this->input['desc'][$index])).'"';
			}
			$sql .= ' END';
			if($this->db->query($sql))
			{
				$this->addItem('success');
			}
			$this->output();
		}
		else
		{
			$flag = false;
			$fields = ' SET ';
			if($this->input['desc'])
			{
				$flag = true;
				$fields .= '`desc` = "'.urldecode($this->input['desc']).'",';
			}
			if($this->input['name'])
			{
				$flag = true;
				$fields .= 'old_name = "'.urldecode($this->input['name']).'",';
			}
			if($this->input['new_name'])
			{
				$flag = true;
				$fields .= 'new_name = "'.urldecode($this->input['new_name']).'",';
			}
		}		
		if($flag)
		{
			$fields .= ' update_time = '.TIMENOW;
			$sql = "UPDATE ".DB_PREFIX.'pics '.$fields.' WHERE id = '.intval(urldecode($this->input['id']));	
			$this->db->query($sql);
			$this->addItem('success');
		}
		else
		{
			$this->addItem('error');
		}
		$this->output();
	}
	
	//更新图片编辑
	function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$pic_id 	 = $this->input['pic_id'];
		$pic_title   = $this->input['pic_title'];
		$pic_comment = $this->input['pic_comment'];
		if($pic_id&&is_array($pic_id))
		{
		foreach($pic_id AS $k => $v)
		{
			$sql  = "";
			$sql  = " UPDATE ".DB_PREFIX."pics SET ";
			$sql .= " old_name    = '".urldecode($pic_title[$k])."',".
			 		" description = '".urldecode($pic_comment[$k])."',".
			 		" update_time = '".TIMENOW."' WHERE id = '".intval($v)."'";
			$this->db->query($sql);
		}
		}
		
		//将更新的数据返回
		$ret = array();
		$sql = "SELECT * FROM ".DB_PREFIX."pics WHERE id IN (".	urldecode($this->input['id']) .")";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$r['img_info']=unserialize($r['img_info']);
			$ret[] = $r;
		}
		
		$this->addItem($ret);
		$this->output();
	}

	function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = "UPDATE ".DB_PREFIX.'pics SET status = 1 WHERE id in('.urldecode($this->input['id']).')';
		if($this->db->query($sql))
		{
			$this->addItem('success');
		}
		else 
		{
			$this->addItem('error');
		}
		$this->output();
	}
	
	//保存图片信息
	function save_image_info()
	{
		$pic_ids       = $this->input['image_ids'];//将要保存的图片id放入数组中
		$pic_order_ids = $this->input['order_ids'];//图片排序id放入数组中
		$pic_comment   = $this->input['pic_comment'];
		$tuji_id       = intval($this->input['tuji_id']);
		if($pic_ids)
		{
			//查出原图集下面所有的图片
			$sql = "SELECT * FROM ".DB_PREFIX."pics WHERE tuji_id = '".$tuji_id."'";
			$q = $this->db->query($sql);
			$all_pic_ids = array();
			while($r = $this->db->fetch_array($q))
			{
				$all_pic_ids[] = $r['id'];
			}
			//求出两个数组的差集(要删除的图片)
			$del_pic_ids = array_diff($all_pic_ids,$pic_ids);
			$del_ids = implode(',',$del_pic_ids);
			if($del_ids)
			{
				$sql = "DELETE FROM ".DB_PREFIX."pics WHERE id IN (".$del_ids.")";
				$this->db->query($sql);
			}
			
			//对图片进行编辑
			for($i = 0;$i<count($pic_ids);$i++)
			{
				$p_comment = (trim(urldecode($pic_comment[$i])) == '这里输入图片描述')?'':trim(urldecode($pic_comment[$i]));
				$sql = "UPDATE ".DB_PREFIX."pics SET description = '".$p_comment."',order_id = '".$pic_order_ids[$i]."' WHERE id = '".$pic_ids[$i]."'";
				$this->db->query($sql);
			}
			
			//如果设置了某一张的图为封面
			if($this->input['pic_cover_id'])
			{
				//查出这张图片的信息
				$sql = "SELECT * FROM ".DB_PREFIX."pics WHERE id = '".intval($this->input['pic_cover_id'])."'";
				$pic_info = $this->db->query_first($sql);
				//更新该图集封面
				$sql = "UPDATE ".DB_PREFIX."tuji SET cover_url = '".$pic_info['path'].$pic_info['new_name']."' WHERE id = '".intval($this->input['tuji_id'])."'";
				$this->db->query($sql);

				$pic_info['img_info'] = unserialize($pic_info['img_info']);
				$imgurl = hg_fetchimgurl($pic_info['img_info']);
				$change_info = array('tuji_id' => intval($this->input['tuji_id']),'img' => $imgurl,'flag' =>1);
			}
		}
		else 
		{
			$sql = "DELETE FROM ".DB_PREFIX."pics WHERE tuji_id = '".$tuji_id."'";
			$this->db->query($sql);
		}
		if($change_info)
		{
			$this->addItem($change_info);
		}
		else 
		{
			$this->addItem(array('flag' =>0));
		}
		$this->output();
	}
	
	//删除单张图片
	public function delete_pic()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$this->input['id'] = trim($this->input['id']);
		$sql = "SELECT * FROM " . DB_PREFIX ."pics WHERE id IN (" .$this->input['id'] .")";
		$r = $this->db->query($sql);
		while($row = $this->db->fetch_array($r))
		{
			$data[$row['id']] = array(
				'title' => $row['old_name'],
				'delete_people' => trim(urldecode($this->user['user_name'])),
				'cid' => $row['id'],
			);
			$data[$row['id']]['content']['pics'] = $row;
		}
		if(!empty($data))
		{
			foreach($data as $key => $value)
			{
				$this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
			}
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX ."pics WHERE id IN(" . urldecode($this->input['id']) .")";
		$ret = $this->db->query($sql);
		$is_cover = 0;
		$count = 0;
		while($row = $this->db->fetch_array($ret))
		{
			if($row['tuji_id'])
			{
				 $tuji_id = $row['tuji_id'];
			}
			if($row['is_cover'])
			{
			   $is_cover = 1;
			}
			if($row['expand_id'])
			{
				publish_insert_query($row, 'delete', '', 1, 'old_name');
			}
			if($row['id'])
			{
				$count++;
			}
		}
		$sql = " DELETE FROM ".DB_PREFIX."pics WHERE id IN (".urldecode($this->input['id']).")";
		$this->db->query($sql);
		//删除图集的封面
		$cover_url= '';
		if($is_cover&&$tuji_id)
		{
			$cover_url = 'cover_url = \'\',';
		}
		if($tuji_id)
		{
			$total_pic='total_pic = total_pic -'.$count;
			$sql = " UPDATE " . DB_PREFIX . "tuji SET {$cover_url}{$total_pic} WHERE id = '" .$tuji_id. "'";
			$this->db->query($sql);	
		}
		$this->addItem('success');
		$this->output();
	}
	
	function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$out = new pics_update();
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