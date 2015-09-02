<?php
define('MOD_UNIQUEID','member_favorite');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_favorite_mode.php');
require_once(CUR_CONF_PATH . 'lib/member_mycount_mode.php');
class member_favorite_update extends outerReadBase
{
	private $mode;
	private $mycount;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new member_favorite_mode();
		$this->mycount = new member_mycount_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$member_id = intval($this->user['user_id']);
		$type = trim($this->input['type']);
		$relation_id = $this->input['relation_id'];
        $title = trim($this->input['title']);
        $brief = trim($this->input['brief']);
        $picture = $this->input['picture'];
        $picture_count = intval($this->input['picture_count']);
        $template_id = trim($this->input['template_id']);
		if(!$member_id)
		{
		    $this->errorOutput(NO_MEMBER_ID);
		}
		if(!$type)
		{
		    $this->errorOutput(NO_TYPE);
		}

        //查询是否收藏过
        if($relation_id)
        {
            $condition = " AND member_id=".$member_id." AND type='".$type."' AND relation_id=".$relation_id."";
        }
        elseif($type == 'img')
        {
            $condition = " AND member_id=".$member_id." AND type='".$type."' AND picture LIKE '%".$picture."%'";
        }
        $info = $this->mode->show($condition);

        if($info)
        {
            //已经收藏过 特殊返回 返回收藏的内容
            $this->addItem(array('error' => 1,'data' => $info));
            $this->output();
        }
		
	    $data = array(
			    'member_id' => $member_id,
		        'type' => $type,
		        'relation_id' => $relation_id,
                'title' => $title,
                'brief' => $brief,
                'picture' => $picture,
                'picture_count' => $picture_count,
                'template_id' => $template_id,
		        'create_time' => TIMENOW,
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->updateMemberCount($member_id,'create');
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$data['create_time'] = date('Y-m-d H:i:s',$data['create_time']);
			$data['picture'] = $this->input['picture'];
			$this->addItem($data);
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$update_data = array(
			/*
				code here;
				key => value
			*/
		);
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
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
		if(!$this->user['user_id'])
		{
		    $this->errorOutput(NO_MEMBER_ID);
		}
		$member_id = intval($this->user['user_id']);
		
		$ret = $this->mode->delete($this->input['id']);
		
		$count = sizeof(explode(",", $this->input['id']));
		
		if($ret)
		{
		    $this->updateMemberCount($member_id,'delete',$count);
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
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
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}

	/**
	 * 会员收藏数量统计
	 */
	private function updateMemberCount($member_id, $operation, $count = 1)
	{
	    $mycountInfo = $this->mycount->detail($member_id);
	    $action = 'favorite';
	    if(empty($mycountInfo))
	    {
    	    $res = array();
	    }
	    else
	    {
	        $old_num = $mycountInfo[$action];
	        if($operation == 'create')
	        {
	            $new_num = $old_num + $count;
	        }
	        elseif ($operation == 'delete')
	        {
	            $new_num = $old_num - $count;
                if($new_num < 0)
                {
                    $new_num = 0;
                }
	        }
	        $res = $this->mycount->update($member_id,array(
	                'favorite' => $new_num,
	        ));
	    }
	
	    return $res;
	}
	
	public function show(){}
	public function detail(){}
	public function count(){}
	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new member_favorite_update();
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