<?phprequire_once './global.php';require_once '../lib/team.class.php';class teamApplyApi extends adminReadBase{	private $team;		public function __construct()	{		parent::__construct();		$this->team = new teamClass();	}		public function __destruct()	{		parent::__destruct();		unset($this->team);	}		public function index()	{			}		/**	 * 获取申请活动召集者信息	 */	public function show()	{		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;		$condition = '';		if (isset($this->input['state']))		{			$condition .= 'state = ' . intval($this->input['state']);		}		$limit = array($offset, $count);		$apply_info = $this->team->get_apply_list($condition, $limit);		$this->setXmlNode('apply_info' , 'apply');		foreach ($apply_info as $apply)		{			$this->addItem($apply);		}		$this->output();	}		/**	 * 获取申请活动召集者总数	 */	public function count()	{		$condition = '';		if (isset($this->input['state']))		{			$condition .= 'state = ' . intval($this->input['state']);		}		$info = $this->team->get_apply_count($condition);		echo json_encode($info);	}		public function detail()	{			}		/**	 * 处理申请活动召集者	 */	public function check_apply()	{		$ids = trim(urldecode($this->input['id']));		$ids = str_replace('，', ',', $ids);			$id_array = explode(',', $ids);		//过滤数组中的空值		$id_array = array_filter($id_array);		if(empty($id_array))		{			$this->errorOutput(PARAM_WRONG);		}		$ids = implode(',', $id_array);		$state = intval($this->input['state']);		if ($state != 0 && $state != 1)		{			$this->errorOutput(PARAM_WRONG);		}		$condition = 'state != ' . $state . ' AND a_id in (' . $ids . ')';		$apply_info = $this->team->get_apply_list($condition, '');		$update_ids = array();		$team = array();		foreach ($apply_info as $v)		{			$update_ids[] = $v['a_id'];			$team[$v['team_id']][] = $v;		}		if ($update_ids)		{			$data = array(				'state' => $state,				'accept_time' => TIMENOW,			);			$result = $this->team->update_apply($data, implode(',', $update_ids));		}		else		{			$result = true;		}		//更新申请信息		if ($team)		{			foreach ($team as $k=>$v)			{				$num = $state ? count($v) : -count($v);				$this->team->update(array('apply_num' => $num), $k, true);			}		}		$this->addItem($result);		$this->output();	}}$out = new teamApplyApi();$action = $_INPUT['a'];if (!method_exists($out,$action)){	$action = 'show';}$out->$action();