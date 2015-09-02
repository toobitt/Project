<?php
define('MOD_UNIQUEID','agenda');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/agenda_mode.php');
class agenda_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new agenda_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
	    $title       = $this->input['title'];//议程标题
	    $brief       = $this->input['brief'];//议程简介
	    $date_id     = $this->input['date_id'];//所属日期
	    $special_id  = $this->input['special_id'];//所属专题id
	    $guest_id    = $this->input['guest_id'];//演讲人id
	    $stime       = $this->input['stime'];//开始时间
	    $etime       = $this->input['etime'];//结束时间
	    $url         = $this->input['url'];//外链
	    $starttime   = $this->input['starttime'];//开始时间（用于本地提醒）
	    
	    if(!$title)
	    {
	        $this->errorOutput(NO_TITLE);
	    }
	    
	    /*
	    if(!$brief)
	    {
	        $this->errorOutput(NO_TITLE);
	    }
	    */
	    
	    if(!$date_id)
	    {
	        $this->errorOutput(NO_DATE_ID);
	    }
	    
	    /*
	    if(!$special_id)
	    {
	        $this->errorOutput(NO_SPECIAL_ID);
	    }
	    
	    if(!$guest_id)
	    {
	        $this->errorOutput(NO_GUEST_ID);
	    }	  
	    */  
	    
		$data = array(
			'title'       => $title,
			'brief'       => $brief,
			'date_id'     => $date_id,
			'special_id'  => $special_id,
			'guest_id'    => $guest_id,
		    'stime'		  => $stime,
		    'etime'		  => $etime,
		    'starttime'   => strtotime($starttime),
		    'url'		  => $url,
		    'user_id'	  => $this->user['user_id'],
		    'user_name'	  => $this->user['user_name'],
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建演讲嘉宾',$data,'','创建演讲嘉宾' . $vid);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$title       = $this->input['title'];//议程标题
	    $brief       = $this->input['brief'];//议程简介
	    $date_id     = $this->input['date_id'];//所属日期
	    $special_id  = $this->input['special_id'];//所属专题id
	    $guest_id    = $this->input['guest_id'];//演讲人id
	    $stime       = $this->input['stime'];//开始时间
	    $etime       = $this->input['etime'];//结束时间
	    $url         = $this->input['url'];//外链
	    $starttime   = $this->input['starttime'];//开始时间（用于本地提醒）
	    
	    if(!$title)
	    {
	        $this->errorOutput(NO_TITLE);
	    }
	    
	    /*
	    if(!$brief)
	    {
	        $this->errorOutput(NO_TITLE);
	    }
	    */
	    
	    if(!$date_id)
	    {
	        $this->errorOutput(NO_DATE_ID);
	    }
	    
	    /*
	    if(!$special_id)
	    {
	        $this->errorOutput(NO_SPECIAL_ID);
	    }
	    
	    if(!$guest_id)
	    {
	        $this->errorOutput(NO_GUEST_ID);
	    }	    
	    */
	    
		$update_data = array(
			'title'       => $title,
			'brief'       => $brief,
			'date_id'     => $date_id,
			'special_id'  => $special_id,
			'guest_id'    => $guest_id,
		    'stime'		  => $stime,
		    'etime'		  => $etime,
		    'starttime'   => strtotime($starttime),
		    'url'		  => $url,
			'update_time' => TIMENOW,
		);

		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addLogs('更新演讲嘉宾',$ret,'','更新演讲嘉宾' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除演讲嘉宾',$ret,'','删除演讲嘉宾' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			$this->addLogs('审核','',$ret,'审核' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}

    public function sort()
    {
        $this->drag_order('agenda', 'order_id');
        $ids = explode(',', $this->input['content_id']);
        $this->addItem(array('id' => $ids));
        $this->output();
    }
    
    //生成议程的离线数据
    public function create_off_line()
    {
        $data = $this->getSaveFileData();
        //获取分类
        $sortUrl = OFF_LINE_URL . 'get_agenda_config.php?appkey=' . APPKEY . '&appid=' . APPID;
        $sortData = $this->getUrlData($sortUrl);
        if($sortData)
        {
            $data[$sortUrl] = $sortData;
            
            $agendaUrl = OFF_LINE_URL . 'get_agenda.php?appkey=' . APPKEY . '&appid=' . APPID;
            $agendaDetailUrl = OFF_LINE_URL . 'news_detail.php?appkey=' .APPKEY. '&appid=' .APPID;
            //按专题获取数据
            foreach ($sortData['special'] AS $k => $v)
            {
                $agendaDataByspecial = array();
                $agendaDataByspecial = $this->getUrlData($agendaUrl . '&special_id=' . $v['id']);
                if(!$agendaDataByspecial)
                {
                    continue;
                }
                $data[$agendaUrl . '&special_id=' . $v['id']] = $agendaDataByspecial;
            }
            
            //按日期获取的数据
            foreach ($sortData['date'] AS $k => $v)
            {
                $agendaDataByDate = array();
                $agendaDataByDate = $this->getUrlData($agendaUrl . '&date_id=' . $v['id']);
                if(!$agendaDataByDate)
                {
                    continue;
                }
                $data[$agendaUrl . '&date_id=' . $v['id']] = $agendaDataByDate;
                
                //获取议程详情
                foreach ($agendaDataByDate AS $kk => $vv)
                {
                    if(!$vv['url'])
                    {
                        continue;
                    }
                    
                    $_urlArr = explode('#',$vv['url']);
                    $_url = $agendaDetailUrl . '&id=' . $_urlArr[1];
                    $_agenda_detail = $this->getUrlData($_url);
                    if(!$_agenda_detail)
                    {
                        continue;
                    }
                    $data[$_url] = $_agenda_detail;
                }
            }
        }
         //保存到DATA目录
        file_put_contents(DATA_DIR . 'off_line_data.json', json_encode($data));
        $this->addItem('success');
        $this->output();
    }
    
    private function getSaveFileData()
    {
        $data = array();
        if(file_exists(DATA_DIR . 'off_line_data.json'))
        {
            $data = file_get_contents(DATA_DIR . 'off_line_data.json');
            $data = json_decode($data,1);
        }
        else 
        {
            //创建文件
            file_put_contents(DATA_DIR . 'off_line_data.json',json_encode($data));
        }
        return $data;
    }
    
    private function getUrlData($url)
    {
        $data = file_get_contents($url);
        if($data)
        {
            return json_decode($data,1);
        }
        else 
        {
            return array();
        }
    }

	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new agenda_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();