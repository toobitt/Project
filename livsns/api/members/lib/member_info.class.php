<?php 
/***************************************************************************

* $Id: member_info.class.php 45988 2015-06-02 01:07:41Z tandx $

***************************************************************************/
include_once(CUR_CONF_PATH . 'lib/app_extension_mode.php');
class memberInfo extends InitFrm
{
	private $appExtension;
    public function __construct()
	{
		parent::__construct();
		$this->mMemberExtensionField = new memberExtensionField();	
		$this->appExtension = new app_extension_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition,$batch=false)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "member_info ";
		$sql.= " WHERE 1 " . $condition;
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			if($batch)
			{
				$return[$row['member_id']][] = $row;
			}
			else {
				$return[] = $row;
			}
			
		}
		
		return $return;
	}
	//数据处理
	public function extendDataProcess($member_info,$type = 1,$condition='')
	{
		$_member_info = array();
		$_member_info = $this->extendDataFormat($member_info);
		//扩展字段表信息
		$extension_field = $this->mMemberExtensionField->show($condition);
		$extension = array();
		if (!empty($extension_field))
		{
			foreach ($extension_field AS $k => $v)
			{
				$_member_info[$v['extension_field']] = $this->extendValueFormat($_member_info[$v['extension_field']],$v['type']);
				if($type){
				$extension[$v['extension_field']]['field']  = $v['extension_field'];
				$extension[$v['extension_field']]['name']   = $v['extension_field_name'];
				$extension[$v['extension_field']]['value']  = $_member_info[$v['extension_field']];
				}
				else {
					$extension[$v['extension_field']]  = array('value'=>$_member_info[$v['extension_field']],'type'=>$v['type']);
				}
			}
		}
		return $extension;
	}
	
	/**
	 * ©根据app配置的扩展属性 获取会员的属性值
	 * @param unknown $member_info
	 * @param number $type
	 * @param string $condition
	 * @return Ambigous <unknown, multitype:multitype:unknown  >
	 */
	public function extendDataProcessByApp($member_info,$type = 1,$app_id = 0)
	{
	    $_member_info = array();
	    $_member_info = $this->extendDataFormat($member_info);
	    //扩展字段表信息
	    if(!$app_id)
	    {
	        return false;
	    }
	    $condition = ' AND app_id='.$app_id.'';
	    $extension_field = $this->appExtension->show($condition);
	    $extension = array();
	    if (!empty($extension_field))
	    {
	        foreach ($extension_field AS $k => $v)
	        {
	            $_member_info[$v['extension_field']] = $this->extendValueFormat($_member_info[$v['extension_field']],$v['type']);
	            if($type){
	                $extension[$k]['field']  = $v['extension_field'];
	                $extension[$k]['name']   = $v['extension_field_name'];
	                $extension[$k]['value']  = $_member_info[$v['extension_field']];
                    $extension[$k]['isRequired'] = $v['is_required'];
	            }
	            else {
	                $extension[$v['extension_field']]  = array('value'=>$_member_info[$v['extension_field']],'type'=>$v['type']);
	            }
	        }
	    }
	    return $extension;
	}
	
	//数据格式化
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
	//值处理(按照类型转换输出)
	public function extendValueFormat($value,$type = 'text')
	{
		if(empty($value)||empty($type))
		{
			return $value?$value:'';
		}
		if($type=='img')
		{
			return $value?maybe_unserialize($value):array();
		}
		elseif($type=='text')
		{
			return $value?$value:'';					
		}
		return $value?$value:'';
	}
	public function extension_edit($member_id,$member_info,$files = array())
	{		
		if(!is_array($member_info)&&!is_array($files))
		{
			return false;
		}
		//编辑扩展信息
		$extension_field = $this->mMemberExtensionField->show();
		if (is_array($extension_field)&&$extension_field)
		{
			foreach ($extension_field AS $v)
			{
				if(!isset($member_info[$v['extension_field']])&&empty($files[$v['extension_field']]))
				{
					continue;
				}
					$member_info_data = array(
						'member_id'	=> $member_id,
						'field'		=> $v['extension_field'],
							);
				if ($v['type'] == 'text')
				{
					$member_info_data['value'] = $member_info[$v['extension_field']]?$member_info[$v['extension_field']]:'';
				}
				elseif($v['type'] == 'img')
				{
					$member_info_data['value'] = $this->img_upload($files[$v['extension_field']]);
				}
				$this->edit($member_info_data);
			}
		}
	}
	
	/**
	 * 根据app配置的会员扩展属性 编辑会员扩展信息
	 * @param unknown $member_id
	 * @param unknown $member_info
	 * @param unknown $files
	 * @return boolean
	 */
	public function extension_editByApp($member_id, $member_info, $app_id, $files = array())
	{
	    if(!is_array($member_info)&&!is_array($files))
	    {
	        return false;
	    }
	    //编辑扩展信息
	    if(!$app_id)
	    {
	        return array();
	    }
	    $condition = ' AND app_id='.$app_id.'';
	    $extension_field = $this->appExtension->show($condition);
	    if (is_array($extension_field) && $extension_field)
	    {
	        foreach ($extension_field AS $v)
	        {
	            if(!isset($member_info[$v['extension_field']]) && empty($files[$v['extension_field']]))
	            {
	                continue;
	            }
	            $member_info_data = array(
	                    'member_id'	=> $member_id,
	                    'field'		=> $v['extension_field'],
	            );
	            if ($v['type'] == 'text')
	            {
	                $member_info_data['value'] = $member_info[$v['extension_field']]?$member_info[$v['extension_field']]:'';
	            }
	            elseif($v['type'] == 'img')
	            {
	                $member_info_data['value'] = $this->img_upload($files[$v['extension_field']]);
	            }
	            $this->edit($member_info_data);
	        }
	    }
	}
	
	public function edit($data)
	{
		$sql = "REPLACE INTO " . DB_PREFIX . "member_info SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . '`' .$key . "`=" . "'" . $value . "'";
			$space = ",";
		}

		//$sql .= " WHERE member_id = " . $data['member_id'] . " AND field = '" . $data['field'] . "'";
		if ($this->db->query($sql))
		{
			return $data;
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
	
	public function delete($member_id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "member_info WHERE member_id IN (" . $member_id . ")";
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
}

?>