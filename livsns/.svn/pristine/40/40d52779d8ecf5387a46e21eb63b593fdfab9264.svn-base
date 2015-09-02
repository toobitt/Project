<?php
define('MOD_UNIQUEID','city_node');
require_once './global.php';
require_once(ROOT_PATH . 'frm/node_frm.php');
require_once CUR_CONF_PATH.'lib/getPinyinByChinese.php';
require_once CUR_CONF_PATH.'lib/city.php';
require_once CUR_CONF_PATH.'core/get_cityCode.php';
require_once CUR_CONF_PATH.'core/get_weatherInfo.php';
require_once CUR_CONF_PATH.'lib/weather.class.php';
class city_node_update extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
        $this->setNodeTable('weather_city');
		$this->setNodeVar('weather_city');
		$this->pinyin = new getPinyinByChineseApi();
		$this->city = new city();
		$this->cityCode = new get_cityCode();
		$this->weatherInfo = new get_weatherInfo();
		$this->weather = new weather();
	}

	public function __destruct()
	{
		parent::__destruct();
	}


	public function update() 
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
			return ;
		}
		if (!$this->input['name'])
		{
			$this->errorOutput(NOSORTNAME);
		}
        $data = array(
			'id' => intval($this->input['id']),
			'name' => trim(urldecode($this->input['name'])),
			'brief' => trim(urldecode($this->input['brief'])),
			'update_time' =>TIMENOW,
            'user_name'=>$this->user['user_name'],
            'ip'=>  hg_getip(),
            'fid'=>intval($this->input['fid']),
		);
			
        //初始化
        $this->initNodeData();      
        //设置新增或者需要更新的节点数据
        $this->setNodeData($data);
        //设置操作的节点ID
        $this->setNodeID($data['id']);
        //更新方法
        $this->updateNode();
		$this->addItem($data);
		$this->output();
	}

	public function delete()
	{
		$this->verify_setting_prms(array('_action'=>'manage'));
		if (!$this->input['id'])
	    {
	    	$this->errorOutput(NOID);
	    }
		 $this->initNodeData();
		//判断是否成功删除
		if($this->batchDeleteNode($this->input['id']))
		{
			$this->weather->delete($this->input['id']);
			$this->addItem(array('id' => urldecode($this->input['id'])));
		}
		
		$this->output();
	}
	
	public function create()
	{
		$this->verify_setting_prms(array('_action'=>'manage'));
		if (!$this->input['name'])
		{
			$this->errorOutput(NOSORTNAME);
		}
		$name = $this->city->filter(trim(urldecode($this->input['name'])));
		
		$data = array(
            'ip'=>hg_getip(),
            'create_time'=>TIMENOW,
            'fid'=>intval($this->input['fid']),
            'update_time'=>TIMENOW,
            'name'=>$name,
            'brief'=>trim(urldecode($this->input['brief'])),
			'user_id'=>trim(urldecode($this->user['user_id'])),
            'user_name'=>trim(urldecode($this->user['user_name']))
		);
		
		$ret = $this->pinyin->Pinyin($data['name'],'utf8');
		
		$data['en_name'] = $ret[1] ? $ret[1] : '';
		$data['abbr_name'] = $ret[2] ? $ret[2] : '';
        $this->initNodeData();
        $this->setExtraNodeTreeFields(array('en_name','abbr_name','user_id'));
        //设置新增或者需要更新的节点数据
        $this->setNodeData($data);
        //增加节点无需设置操作节点ID
        if($nid = $this->addNode())
        {
        	$data['id'] = $nid;
        	$userInfo = ($this->input['userInfo'])?($this->input['userInfo']) : array('user_id'=>$data['user_id'],'user_name'=>$data['user_name'],'ip'=>$data['ip']);
        	//获取城市的城市代码
        	$ret = $this->cityCode->cityCode(array($data['id']),$userInfo,'');
        	if (!empty($ret))
        	{
        		$ret = $this->weatherInfo->getWeather(array($data['id']),$userInfo);
        	}else {
				$this->deletecity($data['id']);		
        	}
        	$this->addItem($data);
        }
        $this->output();
	}
	private function deletecity($id)
	{
		if (!$id)
		{
			return false;
		}
		$this->initNodeData();
		//判断是否成功删除
		if($this->batchDeleteNode($id))
		{
			$this->weather->delete($id);
			return $id;
		}else {
			return false;			
		}			
	}
	//排序
	public function drag_order()
	{
		$sort = json_decode(html_entity_decode($this->input['sort']),true);
		
		if(!empty($sort))
		{
			foreach($sort as $key=>$val)
			{
				$data = array(
					'order_id' => $val,
				);
				if(intval($key) && intval($val))
				{
					$sql ="UPDATE " . DB_PREFIX . "weather_city SET";
		
					$sql_extra=$space=' ';
					foreach($data as $k => $v)
					{
						$sql_extra .=$space . $k . "='" . $v . "'";
						$space=',';
					}
					$sql .=$sql_extra.' WHERE id='.$key;
					$this->db->query($sql);
				}
			}
		}
		$this->addItem('success');
		$this->output();
	}
}
$out = new city_node_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>