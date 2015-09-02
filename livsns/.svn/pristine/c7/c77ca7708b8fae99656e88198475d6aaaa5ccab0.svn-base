<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :message.php
 * package  :package_name
 * Created  :2013-5-23,Writen by scala
 * 
 ******************************************************************/
 
define('MOD_UNIQUEID', 'mail'); //模块标识
require ('global.php');
class mailApi extends adminReadBase {
	public function __construct() 
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/mail.class.php');
		$this->obj = new mail();
		
		if(!$this->auth)
		{
			include_once ROOT_PATH."lib/class/auth.class.php";
			$this->auth = new auth();
		}
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count($condition);		
		echo json_encode($info);
	}
	
	public function show() 
	{
		$cond 			= $this->get_condition();
		$offset 		= $this->input['offset'] ? $this->input['offset'] : 0;			
		$count 			= $this->input['count'] ? intval($this->input['count']) : 20;					
		$data_limit 	= ' LIMIT ' . $offset . ' , ' . $count;		
		
		$datas			= $this->obj->get_mail($cond . $data_limit);
		
		
		if($datas && is_array($datas))
		{
			
				$this->addItem($datas);
		}
		$this->output();
	}
	
	
	public function detail() 
	{
//		$id = intval($this->input['id']);
//		if($id)
//		{
//			//notice_content a
//			$data_limit = ' AND a.id=' . $id.' LIMIT 1';
//		}
//		else
//		{
//			$data_limit = ' LIMIT 1';
//		}
//		$info = $this->obj->get_mail($data_limit);
//		if(!$info)
//		{
//			$this->errorOutput('mail不存在');
//		}
//		//export_var('detail_mail',$info);	
//		$this->addItem($info);
//		$this->output();
		$this->show();
				
	}
	
		
	/*
	 * @function   	:获取用户组织架构，如果未传参数，则获取全部的架构信息，否则未单个组织下的架构的信息
	 * @param		:$org_id
	 * @return		:output
	 */
	public function get_org(){
		if(!isset($this->input['org_id']))
		{
			$this->input['org_id'] = 0;
		}
		
		$orgs = $this->auth->get_org($this->input['org_id']);
		
		
		
		$this->addItem($orgs);
			
		$this->output();
	}
	
	/*
	 *  @function:获取某个组织架构的用户
	 */
	public function get_org_users()
	{
		$org_id = $this->input['org_id'];
		if(!$org_id)
		{
			$this->errorOutput("没有选择部门");
		}
		$users = $this->auth->getMemberByOrg($org_id);
		$this->addItem($users); 
		$this->output();
		
	}
	
	
	public function index() 
	{
		

	}
	
	
	/*
	 * 条件获取
	 */
	private function get_condition()
	{
		
		//send a,
		$cond 		 = '';
        if(isset($this->input['token_id']))
        	$cond	.= " AND a.token_id=".$this->input['token_id'];
        	
        if(isset($this->input['show_token_list']))	
        	$cond   .= ' group by a.token_id';
		
		//根据时间
		$cond		.= " ORDER BY b.id  ";
		//查询排序方式(升序或降序,默认为降序)
		$cond 		.= $this->input['descasc'] ? $this->input['descasc'] : ' DESC ';

		return $cond;	
	}
	
	
	function unknow() 
	{
		$this->errorOutput("此方法不存在！");
		//echo "此方法不存在！";
	}

	public function __destruct() 
	{
		parent :: __destruct();
	}
}	

$out = new mailApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out-> $action ();
?>
 
 
 
