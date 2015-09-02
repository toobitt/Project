<?php
/*
 * 扩展字段类
 */
require CUR_CONF_PATH . 'lib/extendField.class.php';
class extendInfo extends InitFrm
{
	private $extendField;
	private $db1;
	public function __construct()
	{
		parent::__construct();
		$this->extendField = new extendField();
		$this->db1 = hg_ConnectDB();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$batch=false)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "extendinfo ";
		$sql.= " WHERE 1 " . $condition;		
		$q = $this->db1->query($sql);
		$return = array();
		while ($row = $this->db1->fetch_array($q))
		{
			if($batch)
			{
				$return[$row['user_id']][] = $row;
			}
			else {
				$return[] = $row;
			}
		}
		return $return;
	}
	//数据处理
	public function extendDataProcess($extendinfo)
	{
		$_extendinfo = array();
		$_extendinfo = $this->extendDataFormat($extendinfo);//数据格式化，防止以后扩展
		$extension = array();
		if (!empty($_extendinfo))
		{
			foreach ($_extendinfo AS $k => $v)
			{
				$value = $this->extendValueFormat($v);//为了以后扩展使用
				$extension[$k]  = array(
					'value'=>$value,
					);
			}
		}
		return $extension;
	}
	//值处理(按照类型转换输出)
	public function extendValueFormat($value,$type = 'text')
	{
		$re = array();
		if(empty($value)||empty($type))
		{
			return $value;
		}
		if($type=='img')
		{
			$re =$value?maybe_unserialize($value):array();
		}
		elseif($type=='text')
		{
			$re = $value?$value:'';					
		}
		return $re;
	}
	//数据库值格式化
	public function extendDataFormat($extendinfo)
	{
		$_extendinfo = array();
		if (!empty($extendinfo))
		{
			foreach ($extendinfo AS $v)
			{
				$_extendinfo[$v['field']] = $v['value'];
			}
		}
		return $_extendinfo;
	}
	//扩展字段编辑
	public function extendEdit($user_id,$extendinfo,$files = array())
	{		
		if(!is_array($extendinfo)&&!is_array($files)||empty($user_id))
		{
			return false;
		}
		//编辑扩展信息
		$_extendfield = $this->extendField->show();
		if (is_array($_extendfield)&&$_extendfield)
		{
			foreach ($_extendfield AS $v)
			{	
				if(!isset($extendinfo[$v['field']])&&empty($files[$v['field']]))
				{
					continue;
				}
					$extendInfoData = array(
						'user_id'	=> $user_id,
						'fieldid'		=> $v['id'],
						'field'		=> $v['field'],
							);
				if ($v['type'] == 'text')
				{					
					$extendInfoData['value'] = isset($extendinfo[$v['field']])?$extendinfo[$v['field']]:'';
				}
				elseif($v['type'] == 'img')
				{
					$extendInfoData['value'] = $this->img_upload($files[$v['field']]);
				}				
				$this->edit($extendInfoData);
			}			
			return true;
		}
		return false;
	}
	
	public function edit($data)
	{
		$sql = "REPLACE INTO " . DB_PREFIX . "extendinfo SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . '`' .$key . "`=" . "'" . $value . "'";
			$space = ",";
		}
		if ($this->db1->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function img_upload($img_file)
	{
		if(empty($img_file))
		{
			return '';
		}
			include_once(ROOT_PATH.'lib/class/material.class.php');
			$img['Filedata']=$img_file;
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('图片服务器未安装！');
			}
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($img);
			$img_data = array(
				'host' 			=> $img_info['host'],
				'dir' 			=> $img_info['dir'],
				'filepath' 		=> $img_info['filepath'],
				'filename' 		=> $img_info['filename'],
			);
			return maybe_serialize($img_data);
	}
	
	public function delete($idsArr = array())
	{
		if(empty($idsArr))
		{
			return false;
		}
		$sql = "DELETE FROM " . DB_PREFIX . "extendinfo WHERE 1".$this->where($idsArr);
		if ($this->db1->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function where($idsArr)
	{
		$where = '';
		if (is_array($idsArr))
		{
			foreach ($idsArr as $key => $val)
			{
				if (is_int($val) || is_float($val)||is_numeric($val))
				{
					$where .= ' AND ' . $key . ' = ' . $val;
				}
				elseif (is_string($val)&&(stripos($val, ',')!==false))
				{
					$where .= ' AND ' . $key . ' in (\'' . $val . '\')';
				}
				elseif (is_array($val))
				{
					$where .= ' AND ' . $key . ' in (\'' .implode("','", $val ) . '\')';
				}
				elseif(is_string($val))
				{
					$where .= ' AND ' . $key . ' = \'' . $val . '\'';
				}
			}
		}
		return $where;
	}

}
?>