<?php
define('MOD_UNIQUEID','app_store');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/app_store_mode.php');
class app_store extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new app_store_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY update_time DESC';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  app_name  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		if(isset($this->input['status']) && $this->input['status']!=-1)
		{
		    $condition .= " AND status = '".$this->input['status']."' ";
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
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

		return $condition;
	}
	
	public function detail()
	{
		if($this->input['id'])
		{
			$ret = $this->mode->detail($this->input['id']);
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}
	public function download()
	{
		$id = intval($this->input['id']);
		$ret = $this->mode->detail($this->input['id']);
		
		if(!is_array($ret))
		{
			$this->errorOutput(DATA_ERROR);
		}
		if(!is_array($ret['attach_id']))
		{
			$this->errorOutput(DATA_ERROR);
		}
		if(!is_array($ret['attach']))
		{
			$this->errorOutput(DATA_ERROR);
		}
		$attach = $ret['attach'];
		$root = '/tmp/app_store/'.$this->user['user_id'].'/';
		if($ret['app_icon'] && $attach[$ret['app_icon']])
		{
			if(!is_dir($root))
			{
				hg_mkdir($root);
			}
			exec('curl -s "' . hg_fetchimgurl($attach[$ret['app_icon']]) . '" > ' . $root . $attach[$ret['app_icon']]['filename']);
		}
		foreach($ret['attach_id'] as $k=>$v)
		{
			$dst = $root . $k;
			if(!is_dir($dst))
			{
				hg_mkdir($dst);
			}
			if($v)
			{
				$v = explode(',', $v);
				foreach($v as $aid)
				{
					if(!$attach[$aid])
					{
						continue;
					}
					$url = hg_fetchimgurl($attach[$aid]);
					$cmd = 'curl "'.$url.'" > ' . $dst . '/' . $attach[$aid]['filename'];
					//echo $cmd;exit;
					exec($cmd);
				}
			}
		}
		$filename = $ret['app_name'].'.zip';
		$zip_path = $root . $filename;
		$zipcmd = 'cd '.$root.'; zip -q -r '.$filename.' .';
		exec($zipcmd);
		header("Content-type: application/octet-stream");
		header("Accept-Ranges: bytes");
		header("Accept-Length: ".filesize($zip_path));
		header("Content-Disposition: attachment; filename=".$filename);
		readfile($zip_path);
		//@unlink($zip_path);
		exec("cd /tmp/app_store/;rm -Rf *");
	}
}

$out = new app_store();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>