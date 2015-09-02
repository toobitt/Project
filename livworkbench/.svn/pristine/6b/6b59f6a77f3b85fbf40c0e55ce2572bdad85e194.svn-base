<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 343 2011-11-26 06:12:05Z develop_tong $
***************************************************************************/
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'user');
require_once './global.php';
require_once ROOT_PATH . 'lib/class/curl.class.php';
require_once ROOT_PATH . 'lib/class/status.class.php';

class user extends uiBaseFrm
{	
	private $status;
	
	public function __construct()
	{
		parent::__construct();
		$this->status = new status();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->status);
	}
	
	/**
	 * 获取最新微博信息(首页显示的数据)
	 */
	public function show()
	{
		$page = isset($this->input['pp']) ? intval($this->input['pp']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$statusinfo = $this->status->show(array('offset' => $page, 'count' => $count));
		$total = $this->status->count();
		if ($total['total'] > $count)
		{
			$this->tpl->addVar('hasMore', 1);
		}
		$extralink = '';
		foreach ($this->input AS $k => $v)
		{
			if ($k != 'mid' && $k != 'pp' && $k != 'referto')
			{
				$extralink .= '&amp;' . $k . '=' . $v;
			}
		}
		$data = array(
			'totalpages' => intval($total['total']),
			'perpage' => $count,
			'curpage' => $page,
			'pagelink' => '?' . $extralink,
		);
		$showpages = hg_build_pagelinks($data);
		$str = '<script type="text/javascript">
		//<![CDATA[
		var flash_url = "'.RESOURCE_URL.'swfupload/swfupload.swf";
		var button_image_url = "'.RESOURCE_URL.'swfupload/c_img_xz.png";
		var access_token = "'.$this->user['token'].'";
		//]]>
		</script>';
		$this->tpl->addHeaderCode($str);
		$this->tpl->addVar('statusinfo', $statusinfo);
		$this->tpl->addVar('pagelink', $showpages);
		$this->tpl->addVar('method', 'all');
		//获取频道信息
		$this->get_channel();
		//获取系统运行状态信息
		$this->running_info();
		//获取快捷操作信息
		$this->get_custom_menus();
		$this->tpl->outTemplate('mBlog');
	}
	
	/**
	 * 获取微博数据
	 */
	public function index()
	{
		$func = isset($this->input['fn']) ? trim(urldecode($this->input['fn'])) : '';
		$page = isset($this->input['pp']) ? intval($this->input['pp']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		switch ($func)
		{
			//获取所有人的微博信息
			case 'all' :
				$statusinfo = $this->status->show(array('offset' => $page, 'count' => $count));
				$total = $this->status->count();
			break;
			//获取所在部门的微博信息
			case 'department' :
				$statusinfo = $this->status->show(array(
					'depart_id' => $this->user['org_id'],
					'offset' => $page,
					'count' => $count
				));
				$total = $this->status->count(array('depart_id' => $this->user['org_id']));
			break;
			//获取提到我的微博信息
			case 'mention' :
				$statusinfo = $this->status->show(array(
					'mentions_id' => $this->user['id'],
					'offset' => $page,
					'count' => $count
				));
				$total = $this->status->count(array('mentions_id' => $this->user['id']));
			break;
			//获取评论我的微博信息
			case 'comment' :
				$statusinfo = $this->status->show(array(
					'commentMe_id' => $this->user['id'],
					'offset' => $page,
					'count' => $count
				));
				$total = $this->status->count(array('commentMe_id' => $this->user['id']));
			break;
			//获取我发布的微博信息
			case 'publish' :
				$statusinfo = $this->status->show(array(
					'member_id' => $this->user['id'],
					'offset' => $page,
					'count' => $count
				));
				$total = $this->status->count(array('member_id' => $this->user['id']));
			break;
		}
		if ($total['total'] > $count)
		{
			$this->tpl->addVar('hasMore', 1);
		}
		$this->tpl->addVar('statusinfo', $statusinfo);
		$this->tpl->addVar('method', $func);
		$this->tpl->outTemplate('mBlog_detail');
	}
	
	/**
	 * 异步加载更多信息操作
	 */
	public function more()
	{
		$method = isset($this->input['m']) ? trim(urldecode($this->input['m'])) : '';
		if (empty($method)) $this->ReportError('参数错误');
		$prepage = 20;
		switch ($method)
		{
			case 'all' :
				$total = $this->status->count();
			break;
			case 'department' :
				$total = $this->status->count(array('depart_id' => $this->user['org_id']));
			break;
			case 'mention' :
				$total = $this->status->count(array('mentions_id' => $this->user['id']));
			break;
			case 'comment' :
				$total = $this->status->count(array('commentMe_id' => $this->user['id']));
			break;
			case 'publish' :
				$total = $this->status->count(array('member_id' => $this->user['id']));
			break;
		}
		$totalRecords = intval($total['total']);
		$totalPages = ceil($totalRecords/$prepage);
		$currpage = isset($this->input['p']) ? intval($this->input['p']) : 2;
		if ($currpage < 0) $currpage = 2;
		if ($currpage >= $totalPages) $currpage = $totalPages;
		$start = $prepage * ($currpage - 1);
		switch ($method)
		{
			case 'all' :
				$statusinfo = $this->status->show(array('offset' => $start, 'count' => $prepage));
			break;
			case 'department' :
				$statusinfo = $this->status->show(array(
					'depart_id' => $this->user['org_id'],
					'offset' => $start,
					'count' => $prepage
				));
			break;
			case 'mention' :
				$statusinfo = $this->status->show(array(
					'mentions_id' => $this->user['id'],
					'offset' => $start,
					'count' => $prepage
				));
			break;
			case 'comment' :
				$statusinfo = $this->status->show(array(
					'commentMe_id' => $this->user['id'],
					'offset' => $start,
					'count' => $prepage
				));
			break;
			case 'publish' :
				$statusinfo = $this->status->show(array(
					'member_id' => $this->user['id'],
					'offset' => $start,
					'count' => $prepage
				));
			break;
		}
		$this->tpl->addVar('statusinfo', $statusinfo);
		$this->tpl->outTemplate('part_status', 'hg_load_more,' . $totalPages);
	}
	
	/**
	 * 发布微博信息操作
	 */
	public function add_status()
	{
		$content = isset($this->input['text']) ? trim($this->input['text']) : '';
		$img_ids = isset($this->input['p_id']) ? trim($this->input['p_id']) : '';
		$video = isset($this->input['v_id']) ? trim($this->input['v_id']) : '';
		if (empty($content))
		{
			echo json_encode(array('error' => 1, 'msg' => '发布内容不能为空'));
			exit;
		}
		$data = array();
		$data['content'] = $content;
		if (!empty($img_ids)) $data['images'] = $img_ids;
		if (!empty($video)) $data['videos'] = $video;
		$result = $this->status->addStatus($data);
		if (!$result)
		{
			echo '发布失败';exit;
		}
		$this->tpl->addVar('statusinfo', $result);
		$this->tpl->outTemplate('ind_status_line');
	}
	
	/**
	 * 转发微博
	 */
	public function transmit()
	{
		//获取要转发的微博ID
		$sid = isset($this->input['sid']) ? intval($this->input['sid']) : '';
		if (empty($sid))
		{
			echo '参数错误';
			exit;
		}
		$con = isset($this->input['text']) ? trim($this->input['text']) : '';
		$data = array(
			'status_id' => $sid,
			'content' => $con,
			'a' => 'transmit'
		);
		$info = $this->status->transmitStatus($data);
		$this->tpl->addVar('statusinfo', $info);
		$this->tpl->outTemplate('ind_status_line');
	}
	
	/**
	 * 获取未使用的图片素材信息
	 */
	public function notUsedPic()
	{
		$info = $this->status->getNoUsedPic();
		$this->tpl->addVar('materialinfo', $info);
		$this->tpl->outTemplate('notUsed_material');
	}
	
	/**
	 * 上传图片操作
	 */
	public function upload_img()
	{
		$config = array(
			'host' => '10.0.1.40',
			'dir' => 'livsns/api/material/admin/',
			'token' => '3e67f243c4965f5f233d2003cacfb16d',
		);
		$curl = new curl($config['host'], $config['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'addMaterial');
		$curl->addRequestData('cid', $this->user['id']);
		$curl->addFile($_FILES);
		$result = $curl->request('material_update.php');
		if (!$result)
		{
			echo json_encode(array('error' => 1, 'msg' => '上传失败'));
		}
		else
		{
			//本地化图片到附件数据库中
			$info = $result[0];
			$info['a'] = 'addMaterial';
			$pic = $this->status->localData($info);
			if (!$pic) {
				echo json_encode(array('error' => 1, 'msg' => '上传失败'));
			}else {
				echo json_encode($pic);
			}
		}
	}
	
	/**
	 * 删除图片操作
	 */
	public function drop_img()
	{
		$p_id = isset($this->input['pid']) ? intval($this->input['pid']) : '';
		if (empty($p_id))
		{
			echo json_encode(array('error' => 1, 'msg' => '参数错误'));
			exit;
		}
		$result = $this->status->dropPic($p_id);
		if ($result)
		{
			echo json_encode(array('error' => 0));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '删除失败'));
		}
	}
	
	/**
	 * 获取未使用的视频信息
	 */
	public function notUsedVideo()
	{
		$info = $this->status->getNoUsedVideo();
		$this->tpl->addVar('videoinfo', $info);
		$this->tpl->outTemplate('notUsed_video');
	}
	
	/**
	 * 上传视频操作
	 */
	public function upload_video()
	{
		$config = array(
			'protocol' => 'http://',
			'host' => 'vapi1.dev.hogesoft.com:233',
			'dir' => '',
			'token' => 'aldkj12321aasd',
			'port' => '',
		);
		$curl = new curl($config['host'], $config['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addFile($_FILES);
		$result = $curl->request('create.php');
		if (!$result)
		{
			$out = json_encode(array('error' => 1, 'msg' => '上传失败'));
		}
		else
		{
			//本地化视频到数据库中
			$info = $result[0];
			$img = $info['img']['host'] . '/' . $info['img']['dir'] . $info['img']['filepath'] . $info['img']['filename'];
			$url = $info['protocol'] . $info['host'] . $info['dir'] . $info['file_name'] . '.' . $info['type'];
			$addInfo = array(
				'vid' => $info['id'],
				'img' => $img,
				'url' => $url,
				'type' => 2,
				'a' => 'addVideo'
			);
			$video = $this->status->localData($addInfo);
			if (!$video) {
				$out = json_encode(array('error' => 1, 'msg' => '上传失败'));
			}else {
				$out = json_encode($video);
			}
		}
		echo '<script type="text/javascript">parent.video_callback(' . $out . ');</script>';
	}
	
	/**
	 * 在线视频地址解析
	 */
	public function upload_online()
	{
		$url = isset($this->input['v_url']) ? trim(urldecode($this->input['v_url'])) : '';
		if (empty($url))
		{
			echo json_encode(array('error' => 1, 'msg' => '参数错误'));
			exit;
		}
		include_once ROOT_PATH . 'lib/class/videoUrlParser.class.php';
		$video = new VideoUrlParser();
		$result = $video->parse($url);
		if (!$result)
		{
			echo json_encode(array('error' => 1, 'msg' => '上传失败'));
		}
		else
		{
			//本地化视频到数据库中
			$result['a'] = 'addVideo';
			$result['type'] = 1;
			$video = $this->status->localData($result);
			if (!$video) {
				echo json_encode(array('error' => 1, 'msg' => '上传失败'));
			}else {
				echo json_encode($video);
			}
		}
	}
	
	/**
	 * 删除视频信息
	 */
	public function drop_video()
	{
		$v_id = isset($this->input['vid']) ? intval($this->input['vid']) : '';
		if (empty($v_id))
		{
			echo json_encode(array('error' => 1, 'msg' => '参数错误'));
			exit;
		}
		$result = $this->status->dropVideo($v_id);
		if ($result)
		{
			echo json_encode(array('error' => 0));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '删除失败'));
		}
	}
	
	/**
	 * 删除微博数据
	 */
	public function drop_status()
	{
		$status_id = isset($this->input['sid']) ? trim($this->input['sid']) : '';
		if (empty($status_id))
		{
			echo json_encode(array('error' => 1, 'msg' => '参数错误'));
			exit;
		}
		$result = $this->status->dropStatus($status_id);
		if ($result)
		{
			echo json_encode(array('error' => 0, 'msg' => '删除成功'));
		}
		else
		{
			echo json_encode(array('error' => 1, 'msg' => '删除失败'));
		}
	}
	
	/**
	 * 获取某条微博的评论操作
	 */
	public function get_comment()
	{
		$status_id = isset($this->input['status_id']) ? intval($this->input['status_id']) : '';
		if(empty($status_id))
		{
			echo '传入微博ID';
			exit;
		}
		$perpage = 2;
		$result = $this->status->getCommentByStatus($status_id, 0, $perpage);
		$total = $this->status->getCommentCount($status_id);
		$total = intval($total['total']);
		if ($total > $perpage && $perpage != -1) $this->tpl->addVar('hasMoreComment', $total);
		$this->tpl->addVar('comment', $result);
		$this->tpl->addVar('sid', $status_id);
		$this->tpl->outTemplate('ind_comment_line');
	}
	
	/**
	 * 异步获取更多评论数据
	 */
	public function get_more_comment()
	{
		$status_id = isset($this->input['sid']) ? intval($this->input['sid']) : '';
		if(empty($status_id))
		{
			echo json_encode(array('error' => 1, 'msg' => '传入微博ID'));
			exit;
		}
		$total = $this->status->getCommentCount($status_id);
		$totalRecords = intval($total['total']);
		$prepage = 2;
		$totalPages = ceil($totalRecords/$prepage);
		$currpage = isset($this->input['p']) ? intval($this->input['p']) : 2;
		if ($currpage < 0) $currpage = 2;
		if ($currpage >= $totalPages) $currpage = $totalPages;
		$start = $prepage * ($currpage - 1);
		$commentInfo = $this->status->getCommentByStatus($status_id, $start, $prepage);
		$this->tpl->addVar('commentinfo', $commentInfo);
		$this->tpl->outTemplate('part_comments', 'hg_load_more,' . $totalPages);
	}
	
	/**
	 * 对某条微博发布评论操作
	 */
	public function add_comment()
	{
		$content = isset($this->input['con']) ? trim($this->input['con']) : '';
		$status_id = isset($this->input['sid']) ? intval($this->input['sid']) : '';
		$comment_id = isset($this->input['cid']) ? intval($this->input['cid']) : '';
		if (empty($content) || (empty($status_id) && empty($comment_id)))
		{
			echo '参数错误';
			exit;
		}
		$data = array();
		$data['comment_con'] = $content;
		$data['a'] = 'create';
		if ($status_id) $data['s_id'] = $status_id;
		if ($comment_id) $data['reply_cid'] = $comment_id;
		$result = $this->status->addComment($data);
		$this->tpl->addVar('comment', $result);
		$this->tpl->outTemplate('ind_comment_line');
	}
	
	/**
	 * 获取频道信息
	 */
	private function get_channel()
	{
		include_once(ROOT_PATH . 'lib/class/channel.class.php');
		$channel = new channel();
		$page = isset($this->input['pp']) ? intval($this->input['pp']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 4;
		$channel_info = $channel->get_channels($page, $count);
		$this->tpl->addVar('channel_info', $channel_info);
	}
	
	/**
	 * 获取系统运行状态信息
	 */
	private function running_info()
	{
		include(ROOT_DIR . 'lib/class/cron.class.php');
		$crond = new crond();
		if ($this->settings['croncmd'])
		{
			$crond->setCronCmd($this->settings['croncmd']);
		}
		$cron_status = $crond->isRun();
		$sql = "SELECT a.host AS ahost,a.dir AS adir,mo.host,mo.dir,mo.file_name,mo.file_type,mo.mod_uniqueid FROM " . DB_PREFIX . "menu me 
					LEFT JOIN " . DB_PREFIX . "modules mo ON me.module_id=mo.id 
					LEFT JOIN " . DB_PREFIX . "applications a ON mo.application_id=a.id 
				WHERE me.index=1 ORDER BY me.order_id asc, me.id ASC";
		
		$q = $this->db->query($sql);
		$menu = array();
		$curl = '';
		while($row = $this->db->fetch_array($q))
		{
			$host = $row['host'] ? $row['host'] : $row['ahost'];
			$dir = $row['dir'] ? $row['dir'] : $row['adir'];
			if (!$host || !$dir)
			{
				continue;
			}
			$curl = new curl($host, $dir);
			$curl->setReturnFormat('json');
			$curl->initPostData();
			$curl->addRequestData('a', 'index');
			$datas = $curl->request($row['file_name'] . $row['file_type']);
			$this->tpl->addVar('index_' . $row['mod_uniqueid'], $datas[0]);
		}
		$this->tpl->addVar('cron_status', $cron_status);
	}
	
	/**
	 * 获取自定义快捷菜单数据
	 */
	private function get_custom_menus()
	{
		$page = isset($this->input['pp']) ? intval($this->input['pp']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : -1;
		$menu_info = $this->status->get_custom_menus($page, $count);
		$this->tpl->addVar('menu_info', $menu_info);
	}
	
	/**
	 * 获取所有菜单数据
	 */
	public function all_menus()
	{
		$page = isset($this->input['pp']) ? intval($this->input['pp']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : -1;
		$menu_info = $this->status->get_all_menus($page, $count);
		$this->tpl->addVar('all_menus', $menu_info);
		$this->tpl->outTemplate('custom_menus');
	}
	
	/**
	 * 设置自定义菜单
	 */
	public function set_status()
	{
		$state = trim(urldecode($this->input['state']));
		$menu_id = trim(urldecode($this->input['menu_id']));
		if (empty($state) || empty($menu_id))
		{
			echo json_encode(array('msg' => '参数错误', 'error' => 1));
			exit;
		}
		if ($state == 'add')
		{
			$method = 'add_custom_menu';
		}
		elseif ($state == 'drop')
		{
			$method = 'drop_custom_menu';
		}
		else
		{
			echo json_encode(array('msg' => '参数错误', 'error' => 1));
			exit;
		}
		$result = $this->status->$method($menu_id);
		if ($result)
		{
			echo json_encode(array('msg' => '操作成功', 'error' => 0));
		}
		else
		{
			echo json_encode(array('msg' => '操作失败', 'error' => 1));
		}
	}
	
	/**
	 * 获取快捷菜单信息
	 */
	public function get_shortcut_menus()
	{
		$page = isset($this->input['pp']) ? intval($this->input['pp']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 5;
		$menu_info = $this->status->get_shortcut_menus($page, $count);
		$total = $this->status->get_shortcut_menus_count();
		$extralink = '';
		foreach ($this->input AS $k => $v)
		{
			if ($k != 'mid' && $k != 'pp' && $k != 'referto')
			{
				$extralink .= '&amp;' . $k . '=' . $v;
			}
		}
		$data = array(
			'totalpages' => intval($total['total']),
			'perpage' => $count,
			'curpage' => $page,
			'pagelink' => '?' . $extralink,
		);
		$showpages = hg_build_pagelinks($data);
		$this->tpl->addVar('menus', $menu_info);
		$this->tpl->addVar('pagelink', $showpages);
		$this->tpl->outTemplate('shortcut_menus');
	}
	
	/**
	 * 获取单个快捷菜单信息
	 */
	public function get_one_shortcut_menu()
	{
		$menu_id = isset($this->input['m_id']) ? intval($this->input['m_id']) : -1;
		if ($menu_id <= 0)
		{
			echo json_encode(array('msg' => '参数错误', 'error' => 1));
			exit;
		}
		$menu_info = $this->status->get_one_shortcut_menu($menu_id);
		echo json_encode($menu_info);
	}
	
	/**
	 * 添加快捷菜单操作
	 */
	public function add_shortcut_menu()
	{
		$menu_name = isset($this->input['menu_name']) ? trim(urldecode($this->input['menu_name'])) : '';
		$menu_link = isset($this->input['menu_link']) ? trim(urldecode($this->input['menu_link'])) : '';
		if (empty($menu_name) || empty($menu_link))
		{
			echo json_encode(array('msg' => '参数错误', 'error' => 1));
			exit;
		}
		$data = array(
			'm_name' => $menu_name,
			'm_link' => $menu_link,
		);
		$result = $this->status->create_shortcut_menu($data);
		if ($result)
		{
			echo json_encode(array('msg' => '操作成功', 'error' => 0, 'asyncData' => $result));
		}
		else
		{
			echo json_encode(array('msg' => '操作失败', 'error' => 1));
		}
	}
	
	/**
	 * 更新快捷菜单操作
	 */
	public function update_shortcut_menu()
	{
		$menu_id = isset($this->input['m_id']) ? intval($this->input['m_id']) : -1;
		if ($menu_id <= 0)
		{
			echo json_encode(array('msg' => '参数错误', 'error' => 1));
			exit;
		}
		$menu_name = isset($this->input['menu_name']) ? trim(urldecode($this->input['menu_name'])) : '';
		$menu_link = isset($this->input['menu_link']) ? trim(urldecode($this->input['menu_link'])) : '';
		if (empty($menu_name) || empty($menu_link))
		{
			echo json_encode(array('msg' => '参数错误', 'error' => 1));
			exit;
		}
		$data = array(
			'm_name' => $menu_name,
			'm_link' => $menu_link,
		);
		$result = $this->status->update_shortcut_menu($data);
		if ($result)
		{
			echo json_encode(array('msg' => '操作成功', 'error' => 0));
		}
		else
		{
			echo json_encode(array('msg' => '操作失败', 'error' => 1));
		}
	}
	
	/**
	 * 删除快捷菜单操作
	 */
	public function drop_shortcut_menu()
	{
		$menu_id = isset($this->input['m_id']) ? trim(urldecode($this->input['m_id'])) : '';
		if (empty($menu_id))
		{
			echo json_encode(array('msg' => '参数错误', 'error' => 1));
			exit;
		}
		if (!strpos($menu_id, ',')) $menu_id = intval($menu_id);
		
		$result = $this->status->drop_shortcut_menu($menu_id);
		
		if ($result)
		{
			echo json_encode(array('msg' => $menu_id, 'error' => 0, 'menu_id' => $menu_id));
		}
		else
		{
			echo json_encode(array('msg' => '操作失败', 'error' => 1));
		}
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>