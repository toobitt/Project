<?php
define('MOD_UNIQUEID','app_config');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/app_config_mode.php');
class app_config extends outerReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new app_config_mode();
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
		$orderby = '  ORDER BY order_id DESC,id DESC ';
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
		if($this->input['app_id'])
		{
			$condition .= " AND app_id IN (".($this->input['app_id']).")";
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
		if($this->input['app_id'])
		{
			$ret = $this->mode->detail($this->input['app_id']);
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
	public function saveAppconfig()
	{
        $app_id = intval($this->input['app_id']);
        $seekhelp_audit = $this->input['seekhelp_audit'];
        $comment_audit = $this->input['comment_audit'];
        if(empty($app_id))
        {
            $this->errorOutput(NO_APPID);
        }

        $data = array(
            'app_id' => $app_id,
            'seekhelp_audit'    => $seekhelp_audit ? $seekhelp_audit : 0,
            'comment_audit'     => $comment_audit ? $comment_audit : 0,
        );
        $ret = $this->mode->detail($data['app_id']);
        if(empty($ret))
        {
            $vid = $this->mode->create($data);
        }
        else
        {
            if(!is_null($seekhelp_audit))
            {
                $update_data['seekhelp_audit'] = $seekhelp_audit;
            }
            if(!is_null($comment_audit))
            {
                $update_data['comment_audit'] = $comment_audit;
            }

            $vid = $this->mode->update($app_id,$update_data);
        }

		if($vid)
		{
			$data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem('success');
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
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
}

$out = new app_config();
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