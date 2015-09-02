<?php
require('global.php');
define('MOD_UNIQUEID','access');
class accessUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/access.class.php');
		$this->obj = new access();
	}


	public function __destruct()
	{
		parent::__destruct();
	}


	public function create(){}
	public function update(){}
	public function delete(){}
	public function audit(){}
	public function sort(){}
	public function publish(){}

    public function save_set()
    {
        //查询发布的时间
        if($this->input['access_time'])
        {
            $today = date('Y-m-d');
            $tomorrow = date('Y-m-d',TIMENOW+24*3600);
            switch(intval($this->input['access_time']))
            {
                case 1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday = date('y-m-d',TIMENOW-24*3600);
                    $this->input['start_time'] = $yesterday;
                    $this->input['end_time'] = $today;
                    break;
                case 3://今天的数据
                    $this->input['start_time'] = $today;
                    $this->input['end_time'] = $tomorrow;
                    break;
                case 4://最近3天
                    $last_threeday = date('y-m-d',TIMENOW-2*24*3600);
                    $this->input['start_time'] = $last_threeday;
                    $this->input['end_time'] = $tomorrow;
                    break;
                case 5://最近7天
                    $last_sevenday = date('y-m-d',TIMENOW-6*24*3600);
                    $this->input['start_time'] = $last_sevenday;
                    $this->input['end_time'] = $tomorrow;
                    break;
                default://所有时间段
                    break;
            }
        }
        //查询创建的起始时间
        if($this->input['start_time'])
        {
            $this->input['start_time'] =  strtotime($this->input['start_time']);
        }
        //查询创建的结束时间
        if($this->input['end_time'])
        {
            $this->input['end_time'] =  strtotime($this->input['end_time']);
        }
        if ($this->input['start_time'] || $this->input['end_time'])
        {
            if (!$this->input['end_time'])
            {
                $this->input['end_time'] = TIMENOW;
            }
            $this->input['duration'] = intval(($this->input['end_time'] - $this->input['start_time'])/60);
        }
        $title = '';
        if (!$this->input['app_uniqued'])
        {
            $title = '全部类型-';
        }
        else
        {
            $title = $this->settings['App_' . $this->input['app_uniqued']]['name'] . '-';
        }


        if ( !$this->input['start_time'] && !$this->input['end_time'])
        {
            $title .= '所有时间段';
        }
        else
        {
            $title .= date('Y-m-d H:i', $this->input['start_time']) . '--' . date('Y-m-d H:i', $this->input['end_time']);
        }

        if ($this->input['k'])
        {
            $title .= '_' . $this->input['k'];
        }

        $info = array(
            'title' 	=> addslashes($title),
            'start_time'=> $this->input['start_time'],
            'duration'  => $this->input['duration'],
            'limit_num' => $this->input['limit_num'] ? intval($this->input['limit_num']) : '50',
            'user_id'   => $this->user['user_id'],
            'user_name' => $this->user['user_name'],
            'create_time' => TIMENOW,
            'update_time' => TIMENOW,
            'output_type' => $this->input['output_type'] ? 1: 0,
            'type'        => $this->input['app_uniqued'],
            'k'         => $this->input['k'],
        );
        $sql = "INSERT INTO ".DB_PREFIX."ranking_sort SET ";
        $space = '';
        foreach($info as $key => $value)
        {
            $sql .= $space . $key ."='".$value."'";
            $space = ',';
        }
        $this->db->query($sql);
        $this->addItem('true');
        $this->output();
    }

	public function unknow()
	{
		$this->errorOutput('此方法不存在');
	}

}
$out = new accessUpdateApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>