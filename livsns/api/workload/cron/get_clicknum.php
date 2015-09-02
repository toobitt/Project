<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','workload_clicknum');
require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
class getClicknum extends cronBase
{
    public function __construct()
    {
    	parent::__construct();
    	$this->puscont = new publishcontent();
    }
    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,  
            'name' => '获取点击数',    
            'brief' => '获取点击数',
            'space' => '10', //运行时间间隔，单位秒
            'is_use' => 1,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }
    
    public function get_work()
    {
    	//队列执行开始
		$sql = 'SELECT offset,locked FROM '.DB_PREFIX.'user_queue where locked=1';
		$queue = $this->db->query_first($sql);
		if($queue['locked'])
		{	
			$offset = $queue['offset']?$queue['offset']:0;
			$count = $this->input['count']?intval(urldecode($this->input['count'])):1;
			$limit = " limit {$offset}, {$count}";
			$sql = 'SELECT DISTINCT user_name FROM '.DB_PREFIX.'workload  ORDER BY user_id ASC '.$limit;
			$result = $this->db->query_first($sql);
			$user_name = $result['user_name'];
			if($user_name)
			{
				$this->update_queue($offset+$count);
				$data = array(
					'user_name'			=> $user_name,
				);
				$re = $this->puscont->get_clicknum($data);
				if($re[0]['total'])
				{
					$newclick = $re[0]['data'];
					$sql = 'SELECT click_num,comment_num,date FROM '.DB_PREFIX.'workload WHERE user_name LIKE "'.$user_name.'" GROUP BY date';
					$q = $this->db->query($sql);
					while($r = $this->db->fetch_array($q))
					{
						$date = date('Ymd',$r['date']);
						if($newclick[$date]['click_num'])
						{
							if($newclick[$date]['click_num'] != $r['click_num'] || $newclick[$date]['comment_num'] != $r['comment_num'] ) //如果点击数修改
							{
								$update[$r['date']] = array(
									'click_num'	=> intval($newclick[$date]['click_num']),
									'comment_num'	=> intval($newclick[$date]['comment_num']),
								);
							}
						}
					}
					if($update)
					{
						$dates = implode(',', array_keys($update));
						$sql = 'UPDATE '.DB_PREFIX.'workload SET click_num = CASE date ';
						foreach ($update as $key => $value)
						{
						    $sql .= ' WHEN '.$key.' THEN '. $value['click_num'] ;
						}
						$sql .= ' END WHERE  user_name LIKE "'.$user_name.'" AND date in ('.$dates.')';
						$this->db->query($sql);
						$this->addItem(true);
					}
				}
				$this->addItem($user_name);
			}
			else
			{
				$this->reset_queue();
				$this->addItem(QUEUE_HAS_FINISHED);
			}
		}
		else 
		{
			$this->addItem(QUEUE_HAS_NO_START);
		}
		$this->output();
    }

    public function reset_queue()
	{
		$this->db->query('UPDATE '.DB_PREFIX.'user_queue SET offset = 0, locked=0');
	}
	
	public function update_queue($offset=0)
	{
		//锁定队列准备开始执行
		$this->db->query('UPDATE '.DB_PREFIX.'user_queue SET offset = '.intval($offset));
	}
    
}

$out = new getClicknum();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'get_work';
}
$out->$action();
?>
