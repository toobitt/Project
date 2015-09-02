<?php
/***************************************************************************
* LivSNS 0.1
* (C)2009-2010 HOGE Software.
*
* $Id: status.class.php 12433 2012-10-11 09:59:16Z repheal $
***************************************************************************/
class status
{
	public function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		if ($gGlobalConfig['App_mcp_statues'])
		{
			$this->curl = new curl($gGlobalConfig['App_mcp_statues']['host'], $gGlobalConfig['App_mcp_statues']['dir'] . 'admin/');	
		}
	}

	public function __destruct()
	{
		unset($this->curl);
	}
	
	/**
	 * 获取微博数据
	 * @param Array $data
	 * depart_id  部门id
	 * mentions_id 提到的用户id
	 * commentMe_id 评论的用户id
	 * member_id 用户id
	 */
	public function show($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if ($data && is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}	
		$result = $this->curl->request('show.php');
		return $result;
	}
	
	/**
	 * 根据条件获取总数
	 * @param Array $data
	 * depart_id  部门id
	 * mentions_id 提到的用户id
	 * commentMe_id 评论的用户id
	 * member_id 用户id
	 */
	public function count($data = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if ($data && is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$this->curl->addRequestData('a', 'count');
		$result = $this->curl->request('show.php');
		return $result;
	}
	
	/**
	 * 发布微博操作
	 * @param Array $data
	 */
	public function addStatus($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if ($data && is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$this->curl->addRequestData('a', 'create');
		$result = $this->curl->request('update.php');
		return $result;
	}
	
	/**
	 * 转发微博
	 * @param Array $data
	 */
	public function transmitStatus($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if ($data && is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$result = $this->curl->request('update.php');		
		return $result;
	}
	
	/**
	 * 逻辑删除微博信息
	 * @param Int|String $status_id
	 */
	public function dropStatus($status_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('sid', $status_id);
		$this->curl->addRequestData('a', 'delete');
		$result = $this->curl->request('update.php');
		return $result[0];	
	}
	
	/**
	 * 获取当前用户未使用的图片素材
	 */
	public function getNoUsedPic()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_material');
		$result = $this->curl->request('show.php');
		return $result;
	}
	
	/**
	 * 获取当前用户未使用的视频
	 */
	public function getNoUsedVideo()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_video');
		$result = $this->curl->request('show.php');
		return $result;
	}
	
	/**
	 * 本地化图片和视频
	 * @param Array $data
	 */
	public function localData($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if ($data && is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$result = $this->curl->request('update.php');
		return $result[0];
	}
	
	/**
	 * 删除图片
	 * @param Int $pid
	 */
	public function dropPic($pid)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('p_id', $pid);
		$this->curl->addRequestData('a', 'dropMaterial');
		$result = $this->curl->request('update.php');
		return $result[0];
	}
	
	/**
	 * 删除视频
	 * @param Int $vid
	 */
	public function dropVideo($vid)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('v_id', $vid);
		$this->curl->addRequestData('a', 'dropVideo');
		$result = $this->curl->request('update.php');
		return $result[0];
	}
	
	/**
	 * 根据微博ID取评论信息
	 * @param Int $status_id
	 */
	public function getCommentByStatus($status_id, $page = 0, $count = -1)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('offset', $page);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('sid', $status_id);
		$this->curl->addRequestData('a', 'show');
		$result = $this->curl->request('comment.php');		
		return $result;
	}
	
	/**
	 * 获取某个微博的评论总数
	 * @param Int $status_id
	 */
	public function getCommentCount($status_id)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('sid', $status_id);
		$this->curl->addRequestData('a', 'count');
		$result = $this->curl->request('comment.php');		
		return $result;
	}
	
	/**
	 * 增加对应微博的评论信息
	 * @param Array $data
	 */
	public function addComment($data)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if ($data && is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$result = $this->curl->request('comment_update.php');
		return $result;
	}
	
	/**
	 * 删除评论
	 * @param Int|String $comment_id
	 */
	public function deleteComment($comment_id)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('comment_id', $comment_id);
		$this->curl->addRequestData('a', 'delete');
		$result = $this->curl->request('comment_update.php');		
		return $result[0];
	}
	
	/**
	 * 获取所有快捷菜单数据
	 */
	public function get_shortcut_menus($offset = 0, $count = -1)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('offset', $offset);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('a', 'show');	
		$result = $this->curl->request('shortcutMenu.php');
		return $result;
	}
	
	/**
	 * 获取快捷菜单总数
	 */
	public function get_shortcut_menus_count()
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'count');	
		$result = $this->curl->request('shortcutMenu.php');
		return $result;
	}
	
	/**
	 * 获取单个快捷菜单数据
	 * @param Int $menu_id
	 */
	public function get_one_shortcut_menu($menu_id)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('m_id', $menu_id);
		$this->curl->addRequestData('a', 'detail');	
		$result = $this->curl->request('shortcutMenu.php');
		return $result[0];
	}
	
	/**
	 * 创建快捷菜单
	 * @param Array $data
	 */
	public function create_shortcut_menu($data)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if (!$data || !is_array($data))
		{
			return false;
		}
		foreach ($data as $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$this->curl->addRequestData('a', 'create');
		$result = $this->curl->request('shortcutMenu_update.php');
		return $result[0];
	}
	
	/**
	 * 更新快捷菜单
	 * @param Array $data
	 */
	public function update_shortcut_menu($data)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if (!$data || !is_array($data))
		{
			return false;
		}
		foreach ($data as $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$this->curl->addRequestData('a', 'update');
		$result = $this->curl->request('shortcutMenu_update.php');
		return $result[0];
	}
	
	/**
	 * 单个删除快捷菜单
	 * @param Int $menu_id
	 */
	public function drop_shortcut_menu($menu_id)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('m_id', $menu_id);
		$this->curl->addRequestData('a', 'delete');
		$result = $this->curl->request('shortcutMenu_update.php');
		return $result[0];
	}
	
	/**
	 * 获取自定义菜单数据
	 * @param Int $offset
	 * @param Int $count
	 */
	public function get_custom_menus($offset = 0, $count = -1)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('offset', $offset);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('a', 'show');	
		$result = $this->curl->request('customMenu.php');
		return $result;
	}
	
	/**
	 * 获取所有的菜单数据
	 * @param Int $offset
	 * @param Int $count
	 */
	public function get_all_menus($offset = 0, $count = -1)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('offset', $offset);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('a', 'get_all_custom_menus');	
		$result = $this->curl->request('customMenu.php');
		return $result;
	}
	
	/**
	 * 自定义菜单添加
	 * @param Int|String $menu_id
	 */
	public function add_custom_menu($menu_id)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('menu_id', $menu_id);
		$this->curl->addRequestData('a', 'create');
		$result = $this->curl->request('customMenu_update.php');
		return $result[0];
	}
	
	/**
	 * 自定义菜单删除
	 * @param Int|String $menu_id
	 */
	public function drop_custom_menu($menu_id)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('menu_id', $menu_id);
		$this->curl->addRequestData('a', 'delete');
		$result = $this->curl->request('customMenu_update.php');
		return $result[0];
	}
}

?>