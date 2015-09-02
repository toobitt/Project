<?phprequire_once './global.php';require_once '../lib/team.class.php';class teamOpApi extends adminReadBase{	private $team;		public function __construct()	{		parent::__construct();		$this->team = new teamClass();	}		public function __destruct()	{		parent::__destruct();		unset($this->team);	}		public function index()	{			}		/**	 * 获取小组信息	 */	public function show()	{		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;		$condition = $this->filter_data();		$teams = $this->team->show($offset, $count, $condition);		$this->setXmlNode('team_info', 'team');		if (!empty($teams))		{			foreach($teams as $team)			{				$this->addItem($team);			}		}		$this->output();	}		/**	 * 获取所有状态的小组数量	 */	public function count()	{		$condition = $this->filter_data();		$info = $this->team->count($condition);		echo json_encode($info);	}		private function filter_data()	{		return array(			'key' => trim(urldecode($this->input['k'])),			'start_time' => strtotime(trim(urldecode($this->input['start_time']))),			'end_time' => strtotime(trim(urldecode($this->input['end_time']))),			'date_search' => trim($this->input['date_search']),			'state' => isset($this->input['state']) ? trim($this->input['state']) : 3,			'team_type' => trim($this->input['team_type']),			'team_category' => trim($this->input['team_category']),			'hgupdn' => trim(urldecode($this->input['hgupdn'])),			'hgorder' => trim(urldecode($this->input['hgorder'])),			'_type' => trim($this->input['_type']),		);	}		/**	 * 获取单个小组信息	 */	public function detail()	{		if (isset($this->input['id']))		{			$team_id = intval($this->input['id']);		}		elseif (isset($this->input['team_id']))		{			$team_id = intval($this->input['team_id']);		}		else		{			$team_id = -1;		}		if ($team_id < 0) $this->errorOutput(PARAM_WRONG);		$team_info = $this->team->detail($team_id);		$this->setXmlNode('team_info', 'team');		$this->addItem($team_info);		$this->output();	}}$out = new teamOpApi();$action = $_INPUT['a'];if (!method_exists($out, $action)){	$action = 'show';}$out->$action();