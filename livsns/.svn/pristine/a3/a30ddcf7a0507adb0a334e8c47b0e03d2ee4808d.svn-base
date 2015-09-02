<?php
define('MOD_UNIQUEID','member_group');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_group_mode.php');
class member_group extends outerReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new member_group_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY mg.id DESC ';
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

	/**
	 * Display the count resource.
	 *
	 * @param  condition
	 * @return Response
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * input your param.
	 *
	 * @param  param
	 * @return Response
	 */
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}

        if($this->input['member_id'])
        {
            $condition .= " AND member_id IN (".($this->input['member_id']).")";
        }

        if($this->user['user_id'])
        {
            $condition .= " AND member_id IN (".($this->user['user_id']).")";
        }
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  name  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		return $condition;
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
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

	/**
	 * Create the resource.
	 *
	 * @param  condition
	 * @return Response
	 */
	public function create()
	{
        if($this->user['user_id'])
        {
            $member_id = $this->user['user_id'];
        }
        elseif($this->input['member_id'])
        {
            $member_id = $this->input['member_id'];
        }
        else
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        $group_id = $this->input['group_id'];
        if(!$group_id)
        {
            $this->errorOutput(NO_GROUP_ID);
        }
        $data = array(
			'member_id' => $member_id,
            'group_id'  => $group_id,
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem($data);
			$this->output();
		}
	}
	

	/**
	 * Update the resource.
	 *
	 * @param  condition
	 * @return Response
	 */
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


	/**
	 * Delete the resource.
	 *
	 * @param  condition
	 * @return Response
	 */
	public function delete()
	{
        if($this->user['user_id'])
        {
            $member_id = $this->user['user_id'];
        }
        elseif($this->input['member_id'])
        {
            $member_id = $this->input['member_id'];
        }
        else
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        $group_id = $this->input['group_id'];
        if(!$group_id)
        {
            $this->errorOutput(NO_GROUP_ID);
        }
        $data = array(
            'member_id' => $member_id,
            'group_id'  => $group_id,
        );

		$ret = $this->mode->delete($data);
		if($ret)
		{
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
}

$out = new member_group();
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