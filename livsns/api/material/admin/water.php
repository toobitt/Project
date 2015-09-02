<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: water.php 6406 2012-04-12 09:47:23Z wangleyuan $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','water_conf');
class waterApi extends adminReadBase
{
	/**
	 * 构造函数
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/water.class.php');
		$this->obj = new water();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

    public function index()
    {
    	
    }
	
	function show()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 50;	
				
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->obj->show($condition . $data_limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}


	function show_water_config()
	{
		$ret = $this->obj->show_water_config();
		if($ret)
		{
			$this->addItem($ret);
		}
		$this->output();
	}


	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count($condition);
		echo json_encode($info);
	}


	/**
	 * 显示单条水印 水印ID不存在默认为最新第一条
	 * @name detail
	 * @access public
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param int $id 水印ID
	 * @return $info array 水印内容
	 */
	function detail()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if($this->input['id'])
		{
			$data_limit = ' and id=' . intval($this->input['id']);
		}
		else
		{
			$data_limit = ' LIMIT 1';
		}		
		$ret = $this->obj->detail($data_limit);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('查询失败');
		}
	}


	
	/*参数:
	 *功能:查询系统水印
	 *返回值:
	 * */
	public function waterSystem()
	{
		$info = $this->obj->waterSystem();
		if(!empty($info))
		{
			foreach($info as $k => $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}

	/**
	 * 检索条件
	 * @name get_condition
	 * @access private
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{
		$condition = '';

        //查询关键字
        if($this->input['k'])
        {
            $condition .=' AND config_name LIKE \'%' . trim(urldecode($this->input["k"])) . '%\'';
        }
                
        //查询发布的时间
        if($this->input['date_search'])
        {
            $today = strtotime(date('Y-m-d'));
            $tomorrow = strtotime(date('Y-m-d',TIMENOW+24*3600));
            switch(intval($this->input['date_search']))
            {
                case 1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
                    $condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
                    $condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
                    $condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
                    break;
                default://所有时间段
                    break;
            }
        }

        //查询创建的起始时间
        if($this->input['start_time'])
        {
            $condition .= " AND create_time > " . strtotime($this->input['start_time']);
        }
        
        //查询创建的结束时间
        if($this->input['end_time'])
        {
            $condition .= " AND create_time < " . strtotime($this->input['end_time']);  
        }


		return $condition;	
	}


	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
	
	public function get_water_config()
	{
		$sql = " SELECT * FROM ".DB_PREFIX."water_config ";
		$ret = $this->db->fetch_all($sql);
		$this->addItem($ret);
		$this->output();
	}
}

$out = new waterApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>


			