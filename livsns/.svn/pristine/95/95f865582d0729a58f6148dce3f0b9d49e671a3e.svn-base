<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: albums.php 7586 2013-07-05 09:40:56Z yaojian $
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/albums_app.class.php';
define('MOD_UNIQUEID', 'albums');  //模块标识

class albumsApi extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new albums_app();
        
        include(CUR_CONF_PATH . 'lib/Core.class.php');
        $this->obj = new Core();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
	}
	
	/**
	 * 获取相册数据
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$condition = $this->filter_data();
		$data = array(
			'offset' => $offset,
			'count' => $count,
			'condition' => $condition
		);
		$albums_info = $this->api->show($data);
		$this->setXmlNode('albums_info', 'albums');
		if ($albums_info)
		{
			foreach ($albums_info as $albums)
			{
				$this->addItem($albums);
			}
		}
		$this->output();
	}
	
	/**
	 * 获取相册总数
	 */
	public function count()
	{
		$condition = $this->filter_data();
		$info = $this->api->count($condition);
        
		echo json_encode($info);
	}
	
	/**
	 * 获取单个相册数据
	 */
	public function detail()
	{
		$id = intval($this->input['id']);
		$user_id = $this->user['user_id'];
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$albums_info = $this->api->detail(array('id' => $id,'user_id'=>$user_id));
		if($albums_info)
		$this->addItem($albums_info);
		$this->output();
	}
	
	/**
	 * 创建相册
	 */
	public function create()
	{
		$user_info = array(
			'user_id' => $this->user['user_id'],
			'user_name' => $this->user['user_name'],
			'org_id' => $this->user['org_id'],
			'appid' => $this->user['appid'],
			'appname' => $this->user['display_name'],
			'create_time' => TIMENOW,
			'ip' => hg_getip()
		);
		if ($_FILES['Filedata'])
		{
			//上传相册封面
			include_once ROOT_PATH . 'lib/class/material.class.php';
			$material = new material();
			$cover_info = $material->addMaterial($_FILES);
		}
		$albums_name = trim(urldecode($this->input['albums_name']));
		if (empty($albums_name)) $this->errorOutput(PARAM_WRONG);
		//判断是否重名
		$info = $this->api->detail(array('albums_name' => $albums_name,'user_id' => $this->user['user_id']), 'id');
		if ($info) $this->errorOutput(NAME_EXISTS);
		$insertData = array(
			'albums_name' => $albums_name,
			'albums_cover' => $cover_info ? serialize($cover_info) : ''
		);
		$cate_id = intval($this->input['cate_id']);
		$cate_info = $this->api->get_category($cate_id);
		if ($cate_info) $insertData['cate_id'] = $cate_id;
		$insertData = array_merge($insertData, $user_info);
		$result = $this->api->create('albums', $insertData);
		if ($cate_info)
		{
			//更新数据
			$this->api->update('category', array('albums_num' => 1), array('id' => $cate_id), true);
		}
		$this->addItem($result);
		$this->output();
	}
/********************************************************************scala********************************************************************/
    /**
     * 增加相册设置封面 
     * 2013.12.26
     */
     public function set_surface_pic()
     {
        if(!isset($this->input['id'])||!$this->input['id'])
         {
             $this->errorOutput(NO_ID);
         }
         if(!isset($this->input['albums_id'])||!$this->input['albums_id'])
         {
             $this->errorOutput(ALBUMS_ID);
         }
         
         $album_id = intval($this->input['albums_id']);
         $id = intval($this->input['id']);
         
         include(CUR_CONF_PATH . 'lib/Core.class.php');
         $obj = new Core();
         $photos_info = $obj->detail('photos', " where id=$id");
         $params['albums_cover'] = $photos_info['photos_info'];
         
         $photos_info = unserialize($params['albums_cover']);
         $imgurl =
            $photos_info['host'].
            $photos_info['dir'].
            $photos_info['filepath'].
            $photos_info['filename'];
         $re = $obj->update('albums',$params," where id=".$album_id);
         
         $return['id'] = $id;
         if($re)
         {
             $return['msg'] = "更新成功";
             $return['status'] = 1;
             $return['photo'] = $photos_info;
             $return['imgurl'] = $imgurl;
         }
         else
         {
             $return['msg'] = "更新失败";
             $return['status'] = 2;
             $return['photo'] = $photos_info;
             $return['imgurl'] = $imgurl;
         }    
         $this->addItem($return); 
         $this->output();
     }

/********************************************************************\scala********************************************************************/

	/**
	 * 更新相册
	 */
	public function update()
	{
		$id = intval($this->input['id']);
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$albums_info = $this->api->detail(array('id' => $id));
		if (!$albums_info) $this->errorOutput(PARAM_WRONG);
		$albums_name = trim(urldecode($this->input['albums_name']));
		$updateData = array();
		if ($albums_name != $albums_info['albums_name'])
		{
			if (empty($albums_name)) $this->errorOutput(PARAM_WRONG);
			//判断是否重名
			$info = $this->api->detail(array('albums_name' => $albums_name,'user_id' => $this->user['user_id']), 'id');
			if ($info) $this->errorOutput(NAME_EXISTS);
			$updateData['albums_name'] = $albums_name;
		}
		if ($_FILES['Filedata'])
		{
			//上传相册封面
			include_once ROOT_PATH . 'lib/class/material.class.php';
			$material = new material();
			$cover_info = $material->addMaterial($_FILES);
			$updateData['albums_cover'] = serialize($cover_info);
		}
		$cate_id = intval($this->input['cate_id']);
		if ($albums_info['cate_id'] != $cate_id)
		{
			if ($cate_id)
			{
				$info = $this->api->get_category($cate_id);
				if (!$info) $this->errorOutput(PARAM_WRONG);
			}
			$updateData['cate_id'] = $cate_id;
		}
		if ($updateData)
		{
			$result = $this->api->update('albums', $updateData, array('id' => $id));
			//更新数据
			if ($albums_info['cate_id'])
			{
				$this->api->update('category', array('albums_num' => -1), array('id' => $albums_info['cate_id']), true);
			}
			if ($updateData['cate_id'])
			{
				$this->api->update('category', array('albums_num' => 1), array('id' => $updateData['cate_id']), true);
			}
			if($result)
			{
				$updateData['id'] = $id;
				$this->addItem($updateData);
			}
		}
		$this->output();
	}
	
    public function delete_album()
    {
        if(!$this->input['id'])
        {
            $this->errorOutput(ALBUMS_ID);
        }
        $id = intval($this->input['id']);
        $params['isdrop'] = 1;
        $cond = " where id=$id";
        $datas = $this->obj->update('albums',$params,$cond);
        $this->addItem($datas);
        $this->output();
        
    }
    
	/**
	 * 删除相册
	 */
	public function delete()
	{
		$id = trim(urldecode($this->input['id']));
		$id_arr = explode(',', $id);
		$ids = array_filter($id_arr, 'filter_arr');
		$id = implode(',', $ids);
		if (empty($id)) $this->errorOutput(PARAM_WRONG);
		$info = $this->api->show(array('count' => -1, 'condition' => array('id' => $id)));
		if (!$info) $this->errorOutput(OBJECT_NULL);
		$validate = $cate_id = array();
		foreach ($info as $v)
		{
			$validate[$v['id']] = $v['id'];
			if ($v['cate_id']) $cate_id[$v['cate_id']] = $v['id'];
		}
		$validate = implode(',', $validate);
		//删除相册下的照片
		$this->api->update('photos', array('isdrop' => 1), array('albums_id' => $validate));
		//删除相册下的评论
		$this->api->update('comment', array('isdrop' => 1), array('albums_id' => $validate));
		//删除相册下的赞
		$this->api->update('praise', array('isdrop' => 1), array('albums_id' => $validate));
		//删除相册
		$result = $this->api->update('albums', array('isdrop' => 1), array('id' => $validate));
		if ($cate_id)
		{
			foreach ($cate_id as $k => $v)
			{
				$this->api->update('category', array('albums_num' => -count($v)), array('id' => $k), true);
			}
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 过滤查询数据
	 */
	private function filter_data()
	{
		$user_id = $this->user['user_id']?$this->user['user_id']:(isset($this->input['uid']) ? intval($this->input['uid']) : 0);
		$name = isset($this->input['k']) ? trim(urldecode($this->input['k'])) : '';
		$time = isset($this->input['date_search']) ? intval($this->input['date_search']) : '';
		$start_time = trim($this->input['start_time']);
		$end_time = trim($this->input['end_time']);
		$albums_id = trim(urldecode($this->input['id']));
		$cate_id = $this->input['cate_id'];
        
        $params['user_id'] = $user_id;
        $params['keyword'] =$name;
        $params['date_search'] = $time;
        $params['start_time'] = $start_time;
        $params['end_time'] = $end_time;
        $params['cate_id'] = $cate_id ;
        if(isset($this->input['is_audit']))
        {
            $params['is_audit'] = intval($this->input['is_audit']);
        }
        if(isset($this->input['isdrop']))
        {
            $params['isdrop'] = intval($this->input['isdrop']);
        }
        
        return $params;
		return array(
			'user_id' => $user_id,
			'keyword' => $name,
			'date_search' => $time,
			'start_time' => $start_time,
			'end_time' => $end_time,
			'id' => $albums_id,
			'cate_id' => $cate_id
		);
	}


}

function filter_arr(&$value)
{
	$value = intval($value);
	return $value <= 0 ? false : true;
}

$out = new albumsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>