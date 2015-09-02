<?php
define('MOD_UNIQUEID','email_token');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/email_token_mode.php');
require_once(CUR_CONF_PATH . 'lib/member.class.php');
class email_token extends outerReadBase
{
	private $mode;
    private $email;
    private $mMember;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new email_token_mode();
        $this->mMember = new member();

	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

    /**
     * 检查邮箱token
     */
    public function checkEmailToken()
    {
        //接受参数
        $this->email = $this->input['email'];
        $token = $this->input['token'];
        if(!$this->email)
        {
            $this->errorOutput(NO_EMAIL);
        }
        if(!$token)
        {
            $this->errorOutput(NO_EMAIL_TOKEN);
        }

        //验证token 有效期
        $condition = " AND email='".$this->email."' AND status=0 ";
        $orderby = '  ORDER BY id DESC ';
        $limit = 'limit 1';
        $ret = $this->mode->show($condition,$orderby,$limit);
        if($ret)
        {
            if($ret[0]['token'] != $token)
            {
                $this->errorOutput(EMAIL_TOKEN_WRONG);
            }
        }
        else
        {
            $this->errorOutput(EMAIL_TOKEN_WRONG);
        }

        $condition = " AND mb.platform_id='".$this->email."' AND mb.type='email' ";
        $leftjoin = " LEFT JOIN " . DB_PREFIX . "member_bind as mb ON m.member_id=mb.member_id";
        $memberInfo = $this->mMember->get_member_info($condition, ' m.* ',$leftjoin,'',0);
        if(empty($memberInfo))
        {
            $this->errorOutput(NO_MEMBER_INFO);
        }

        //返回
        if($memberInfo)
        {
            $this->addItem($memberInfo[0]);
        }
        $this->output();
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
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
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
		$data = array(
			/*
				code here;
				key => value
			*/
		);
		
		$vid = $this->mode->create($data);
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
		if(!$this->input['email'])
		{
			$this->errorOutput(NO_EMAIL);
		}

		$ret = $this->mode->update($this->input['email'],array('status' => 1));
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

$out = new email_token();
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