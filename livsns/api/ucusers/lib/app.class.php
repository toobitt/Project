<?php 
/***************************************************************************

* $Id: app.class.php 6901 2012-05-30 05:15:12Z lijiaying $

***************************************************************************/
class app extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		require_once(ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($this->settings['uc_api']['host'], $this->settings['uc_api']['dir']);
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function getAppInfo()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('m', 'hg_app');
		$this->curl->addRequestData('a', 'ls');
		$this->curl->addRequestData('open', $this->settings['ucenter']['open']);
		$ret = $this->curl->request('admin.php');
		return $ret;
	}
	
	/**
	 * 获取单条信息
	 */
	public function getDetail($appid)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('m', 'hg_app');
		$this->curl->addRequestData('a', 'hgdetail');
		$this->curl->addRequestData('open', $this->settings['ucenter']['open']);
		$this->curl->addRequestData('appid', $appid);
		$ret = $this->curl->request('admin.php');

		return $ret;
	}

	public function detail()
	{
		if ($this->input['id'])
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "applications WHERE appid = " . urldecode($this->input['id']);
			$row = $this->db->query_first($sql);
			
			$row['tagtemplates'] = $tagtemplates = $this->hg_unserialize($row['tagtemplates']);
			$row['template'] = $template = htmlspecialchars($tagtemplates['template']);
			$tmp = '';
			if(is_array($tagtemplates['fields']))
			{
				foreach($tagtemplates['fields'] as $field => $memo)
				{
					$tmp .= $field.','.$memo."\n";
				}
			}
			$row['fields'] = $tagtemplates['fields'] = $tmp;
			$getDetail_info = $this->getDetail($this->input['id']);
			$row['authkey'] = $getDetail_info['authkey'];
		}
		
		if (!empty($row))
		{
			return $row;
		}

		return FALSE;
	}
	
	public function getConfig()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('m', 'hg_app');
		$this->curl->addRequestData('a', 'getConfig');
		$this->curl->addRequestData('open', $this->settings['ucenter']['open']);
		
		$ret = $this->curl->request('admin.php');
		return $ret;
	}

	public function create()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('m', 'hg_app');
		$this->curl->addRequestData('a', 'add');
		$this->curl->addRequestData('submitcheck', $this->settings['submitcheck']['open']);
		$this->curl->addRequestData('open', $this->settings['ucenter']['open']);

		$this->curl->addRequestData('type', urldecode($this->input['type']));
		$this->curl->addRequestData('name', urldecode($this->input['name']));
		$this->curl->addRequestData('url', urldecode($this->input['url']));
		$this->curl->addRequestData('ip', urldecode($this->input['ip']));
		$this->curl->addRequestData('viewprourl', urldecode($this->input['viewprourl']));
		$this->curl->addRequestData('apifilename', urldecode($this->input['apifilename']));
		$this->curl->addRequestData('authkey', urldecode($this->input['authkey']));
		$this->curl->addRequestData('synlogin', urldecode($this->input['synlogin']));
		$this->curl->addRequestData('recvnote', urldecode($this->input['recvnote']));
		$this->curl->addRequestData('extraurl', urldecode($this->input['extraurl']));
		$this->curl->addRequestData('apppath', urldecode($this->input['apppath']));
		$this->curl->addRequestData('tagtemplates', htmlspecialchars_decode(urldecode($this->input['tagtemplates'])));
		$this->curl->addRequestData('tagfields', urldecode($this->input['tagfields']));
		
		$ret = $this->curl->request('admin.php');
		return $ret;
	}

	public function update()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('m', 'hg_app');
		$this->curl->addRequestData('a', 'detail');
		$this->curl->addRequestData('submitcheck', $this->settings['submitcheck']['open']);
		$this->curl->addRequestData('open', $this->settings['ucenter']['open']);

		$this->curl->addRequestData('appid', urldecode($this->input['id']));
		$this->curl->addRequestData('type', urldecode($this->input['type']));
		$this->curl->addRequestData('name', urldecode($this->input['name']));
		$this->curl->addRequestData('url', urldecode($this->input['url']));
		$this->curl->addRequestData('ip', urldecode($this->input['ip']));
		$this->curl->addRequestData('viewprourl', urldecode($this->input['viewprourl']));
		$this->curl->addRequestData('apifilename', urldecode($this->input['apifilename']));
		$this->curl->addRequestData('authkey', urldecode($this->input['authkey']));
		$this->curl->addRequestData('synlogin', urldecode($this->input['synlogin']));
		$this->curl->addRequestData('recvnote', urldecode($this->input['recvnote']));
		$this->curl->addRequestData('extraurl', urldecode($this->input['extraurl']));
		$this->curl->addRequestData('apppath', urldecode($this->input['apppath']));
		$this->curl->addRequestData('tagtemplates', htmlspecialchars_decode(urldecode($this->input['tagtemplates'])));
		$this->curl->addRequestData('tagfields', urldecode($this->input['tagfields']));
		
		$ret = $this->curl->request('admin.php');
		return $ret;
	}

	public function delete()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('m', 'hg_app');
		$this->curl->addRequestData('a', 'ls');
		$this->curl->addRequestData('open', $this->settings['ucenter']['open']);
		$this->curl->addRequestData('delete', urldecode($this->input['id']));
		$ret = $this->curl->request('admin.php');
		return $ret;
	}

	public function ping()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('m', 'hg_app');
		$this->curl->addRequestData('a', 'ping');
		$this->curl->addRequestData('open', $this->settings['ucenter']['open']);
		$this->curl->addRequestData('appid', urldecode($this->input['id']));
		$this->curl->addRequestData('ip', urldecode($this->input['ip']) ? urldecode($this->input['ip']) : '');
		$this->curl->addRequestData('url', urldecode($this->input['url']) ? urldecode($this->input['url']) : '');
		$ret = $this->curl->request('admin.php');
		return $ret;
	}

	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= urldecode($this->input['k']);
		}
		return $condition;
	}

	private function hg_serialize($s, $htmlon = 0)
	{
		include_once 'xml.class.php';
	
		return xml_serialize($s, $htmlon);
	}

	private function hg_unserialize($s)
	{	
		include_once 'xml.class.php';

		return xml_unserialize($s);
	}
	
}
?>