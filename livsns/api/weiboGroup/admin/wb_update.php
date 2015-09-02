<?phprequire('./global.php');define('MOD_UNIQUEID', 'wb');class wbUpdate extends adminUpdateBase{	public function __construct()	{		parent::__construct();		include_once(ROOT_PATH . 'lib/class/share.class.php');		$this->share = new share();	}		public function __destruct()	{		parent::__destruct();	}	public function create(){}	public function update(){}	public function sort(){}	public function publish(){}	public function delete()	{		if(empty($this->input['id']))		{			$this->errorOutput("ID不能为空");		}		$ids = urldecode($this->input['id']);		$sql = "DELETE FROM " . DB_PREFIX . "weibo WHERE id IN(". $ids .")";		$this->db->query($sql);		$sql = "DELETE FROM " . DB_PREFIX ."weibo_circle WHERE weibo_id IN(". $ids .")";		$this->db->query($sql);		$this->addLogs('删除微博','','', '删除微博+' . $ids);	 			$this->addItem($ids);		$this->output();	}			public function audit()	{		if(!$this->input['id'])		{			$this->errorOutput('ID不能为空！');		}		$ids = urldecode($this->input['id']);		$audit = intval($this->input['audit']);		$arr_id = explode(',',$ids);		if($audit == 1) //审核操作		{			$sql = "UPDATE " . DB_PREFIX ."weibo SET status = 1 WHERE id IN(" . $ids . ")";			$this->db->query($sql);			$opration = '审核';			$return =  array('id' => $arr_id,'status' => 1);		}		else if($audit == 0) //打回操作 		{			$sql = "UPDATE " . DB_PREFIX ."weibo SET status = 2 WHERE id IN(" . $ids . ")";			$this->db->query($sql);			$opration = '打回';			$return =  array('id' => $arr_id,'status' => 2);		}		$this->addLogs($opration, '', '', $opration .'+'. $ids);			$this->addItem($return);		$this->output();	}			/**	 * 获取接入平台类型信息	 * 	 */	public function show_plat_auth()	{		$sql = "SELECT plat_token FROM " . DB_PREFIX ."plat_token WHERE appid = " . intval($this->user['appid']);		$ret = $this->db->query($sql);		$plat_token = array();		while($row = $this->db->fetch_array($ret))		{			$plat_token[] = $row['plat_token'];		}		$plat_token = implode(',',$plat_token);		$plat = $this->share->get_plat($plat_token);		$plat_ids = array();		foreach ($plat as $k => $v)		{			$plat[$k]['expired_time'] = date('Y-m-d H:i:s',$plat[$k]['expired_time']);				$plat_ids[] = $v['id'];				}		$plat_ids = implode(',',$plat_ids);		$sql = "DELETE FROM " . DB_PREFIX ."plat_token WHERE platid NOT IN (".$plat_ids.")";		$this->db->query($sql);		$this->addItem($plat);		$this->output();	}		/**	 * 请求接入平台token	 * Enter description here ...	 */	public function request_auth()	{		$type = intval($this->input['type']);		$platid = intval($this->input['platid']);		$sql = "SELECT platid,plat_token FROM " .DB_PREFIX ."plat_token WHERE platid = " . $platid;		$ret = $this->db->query_first($sql);		$plat = $this->share->oauthlogin($platid,$ret['plat_token']);		$plat = $plat[0];		if(!$ret)		{			$sql = "INSERT INTO " . DB_PREFIX ."plat_token(appid,type,platid,plat_token) VALUES			('{$this->user['appid']}','{$type}','{$platid}','{$plat['access_plat_token']}')";			$this->db->query($sql);		}		else		{			$sql = "UPDATE " . DB_PREFIX ."plat_token SET lastusetime = '' ";			$this->db->query($sql);		}				$plat['url'] = $plat['sync_third_auth'] . '?oauth_url=' . $plat['oauth_url'] . '&access_plat_token=' .$plat['access_plat_token']; 		$this->addItem($plat);		$this->output();	}			public function unknow()	{		$this->errorOutput('方法不存在');	}}$out = new wbUpdate();if(!method_exists($out, $_INPUT['a'])){	$action = 'unknow';}else {	$action = $_INPUT['a'];}$out->$action(); ?>