<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: photoedit_update.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/

require_once './global.php';
require_once CUR_CONF_PATH . 'lib/photoedit.class.php';
define('MOD_UNIQUEID', 'photoedit'); //模块标识


class photoeditUpdateApi extends outerUpdateBase
{
	private $photoedit;
	private $mMaterial;
	
	public function __construct()
	{
		parent::__construct();
		$this->photoedit = new photoeditClass();
		
		require_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
		
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_material']['host'], $gGlobalConfig['App_material']['dir'] . 'admin/' );
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->photoedit);
		unset($this->mMaterial);
	}
	
	/**
	 * 图片入库处理
	 */
	public function create()
	{
		$imgdata = $this->input['imgdata'];
		$oldurl = $this->input['oldurl'];
		$file_content_n = file_get_contents($oldurl);
		if(!empty($imgdata) && !empty($oldurl))
		{
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a','replaceImg');
			$this->curl->addRequestData('imgdata',$imgdata);
			$this->curl->addRequestData('oldurl',$oldurl);
			$ret = $this->curl->request('material_update.php');
			$ret = $ret[0];
			
			$pictypetmp = explode('?',$ret['filename']);
			$ret['filename'] = $pictypetmp[0];
			
			$createData = array();
			$localfilename = $this->photoedit->extis_pic($ret['filename']);
			if(is_array($localfilename) &&!empty($localfilename) && count($localfilename)>0)
			{
				$createData['host'] = $localfilename['host'];
				$createData['dir'] = $localfilename['dir'];
				$createData['filepath'] = $localfilename['filepath'];
				$createData['filename'] = $localfilename['filename'];
				$createData['id'] = $localfilename['id'];
			}
			else
			{
				$createData['host'] = $ret['host'];
				$createData['dir'] = $ret['dir'];
				$createData['filepath'] = $ret['filepath'];
				$createData['filename'] = $ret['filename'];
				$createData['order_id'] = 9999;
				$createData['active'] = 1;
				$createData['create_time'] = TIMENOW;
				$createData['update_time'] = TIMENOW;
				$createData['ip'] = hg_getip();
				$createData['appid']=$this->user['appid'];
				$createData['appname']=$this->user['display_name'];
				$createData['user_id']=$this->user['user_id'];
				$createData['user_name']=$this->user['user_name'];
				$result = $this->photoedit->create($createData);
				$createData['id'] = $result['id'];
				
				//第一次入库 本地化原始图
				//图片后缀
				$pictypetmp_n = explode('.',$oldurl);
				$picfiletype_n = strtolower($pictypetmp_n[count($pictypetmp_n)-1]);
				//图片本地化名称、
				$imgname_n="local_p_".rand(10,1000)."_".TIMENOW;
				
				//更改的图片本地化
			    file_put_contents( "./data/" . $imgname_n.".".$picfiletype_n,$file_content_n);
	
			      
				//附表新增纪录
				$updateData_list_n = array();
				$updateData_list_n['fid'] = $createData['id'];
				$updateData_list_n['filename'] = $imgname_n.".".$picfiletype_n;
				$updateData_list_n['update_time'] = TIMENOW;
				//$updateData_list_n['active'] = 1;
				$updateData_list_n['is_delete'] = 2;
				
				if ($updateData_list_n)
				{
					$result_n = $this->photoedit->create_list($updateData_list_n);
				}
				
			}
			
			//替换的图片本地化
			//图片后缀
			$pictypetmp = explode('.',$ret['filename']);
			$picfiletype = strtolower($pictypetmp[count($pictypetmp)-1]);
			//图片本地化名称、
			$imgname="local_p_".rand(10,1000)."_".TIMENOW;
			
			//更改的图片本地化
		    $files = $ret['host'].$ret['dir'].$ret['filepath'].$ret['filename'];
		    //$file_content = file_get_contents($files);
		    $file_content = base64_decode($imgdata);
		    file_put_contents( "./data/" . $imgname.".".$picfiletype,$file_content);

		      
			//附表新增纪录
			$updateData_list = array();
			$updateData_list['fid'] = $createData['id'];
			$updateData_list['filename'] = $imgname.".".$picfiletype;
			$updateData_list['update_time'] = TIMENOW;
			$updateData_list['active'] = 1;
			
			if ($updateData_list)
			{
				$result = $this->photoedit->create_list($updateData_list);
				if($result['insert_id'])
				{
					//更新active字段
					$sql = "SELECT COUNT(*) AS total,MAX(id) AS max_id,MIN(id) AS min_id FROM " .DB_PREFIX. "photoedit_list WHERE fid = " .$result['fid']. " ORDER BY id DESC";
					$re = $this->db->query_first($sql);
					if($re['total'] > 2)
					{
						$sql = "UPDATE " .DB_PREFIX. "photoedit_list SET active = 0,is_delete = 1 WHERE fid = " .$result['fid']. " AND id NOT IN(" .$re['max_id'].','.$re['min_id']. ")";
						$this->db->query($sql);
					}
				}
			}
			
			unset($result);
			unset($updateData_list);
		}
			
		$this->addItem($createData);
		$this->output();
	}
	
	//设置文件/文件夹的写属性
	public function set_writeable($filename){
		
		if (is_dir($filename)==false)
		{
			if(@mkdir($filename, 0777))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			if(is_writable($filename))
			{
				return true;
			}
			else{
				if(@chmod($filename,0777))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
	}


	/**
	** 信息更新操作
	**/
	public function update()
	{		
	}

	public function delete()
	{
			
	}

	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
	
}

$out = new photoeditUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>