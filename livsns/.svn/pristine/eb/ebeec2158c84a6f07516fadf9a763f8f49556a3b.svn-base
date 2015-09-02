<?php
require('global.php');
define('MOD_UNIQUEID','special_plan');//模块标识
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/logs.class.php');
class publishPlanApi extends adminBase
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		$this->pubplan = new publishplan();
		$this->pub_content = new publishcontent();
		$this->logs = new logs();
		include(CUR_CONF_PATH . 'lib/special_queue.class.php');
		$this->que = new specialQueue();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function check_plan()
	{
		//$data content_id
		$ret = $this->pub_content->get_content($data);
		//取出最前面一条发布队列
		//$plan = $this->obj->get_plan_first();
		if(empty($plan))
		{
			echo "没有发布队列";
			exit;
		}
		
		//删除这条队列
		//if(!$this->obj->delete_plan($plan['pid']))
		{
			exit;
		}
		
		//判断当前操作属于插入，删除，更新
		switch($plan['action_type'])
		{
			case 'insert' : $result = $this->insert_content($plan);break;
			case 'delete' : $result = $this->delete_content($plan);break;
			case 'update' : $result = $this->update_content($plan);break;
		}
		
		//插入这条队列到日志
		$plan['status'] = $result?1:2;
		if(!$plan['fid'])
		{
			$this->logs->addLogs($plan['action_type'] , array() , $plan , $plan['pid'] , '');
		}
	}
	
	public function insert_content($plan)
	{
		//到对应接口取发布的内容,内容格式：二维数组 多条内容 每条内容所有的参数就是发布系统里需要的参数
		$this->pubplan->setAttribute($plan['host'],$plan['path'],$plan['filename'],$plan['action_get_content']);
		$contentdata = $this->pubplan->get_content($plan['from_id'],$plan['sort_id'],$plan['offset'],$plan['num']);
		//print_r($contentdata);exit;
		if(empty($contentdata) || !is_array($contentdata))
		{
			return false;
		}
		//将内容进行发布
		foreach($contentdata as $key=>$value)
		{
			if(!is_array($value))
			{
				continue;
			}
			//内容发布到发布系统里  遍历value 加上配置里的属性 column_id,应用标识
			$value['plan_set_id'] = $plan['id'];
			$value['column_id'] = $plan['column_id'];
			$value['bundle_id'] = $plan['bundle_id'];
			$value['module_id'] = $plan['module_id'];
			$value['struct_id'] = $plan['struct_id'];
			$value['struct_ast_id'] = $plan['struct_ast_id'];
			$value['publish_time'] = $plan['publish_time'];
			$value['publish_user'] = $plan['publish_user'];
			$expand = $this->pub_content->insert_content($value); 
			//print_r($expand);exit;
			//发布后的内容id传给各自模块的接口记录
			$expand_data = array(
				'column_id' => $plan['column_id'],
				'from_id' => $value['content_fromid'],
				'expand_id' => $expand['expand_id'],
				'content_url' => $expand['content_url'],
			);
			
			$this->pubplan->setAttribute($plan['host'],$plan['path'],$plan['filename'],$plan['action_insert_contentid']);
			$this->pubplan->insert_pub_content_id($expand_data);
			
			
			//内容发布后，查找各自内容是否有子级，如果有，则添加每条内容的队列
			//查询出plan_set子级
			if(!empty($plan['is_publish_child']))
			{
				$child_set_other = $this->obj->get_child_set($plan['sid']);
				if(!empty($child_set_other))
				{
					foreach($child_set_other as $k=>$v)
					{
						$newchildplan = array(
							'fid' => $plan['pid'],
							'set_id' => $v['id'],
							'from_id' => $value['content_fromid'],
							'sort_id' => $plan['sort_id'],
							'column_id' => $plan['column_id'],
							'title' => $plan['title'],
							'action_type' => 'insert',
							'offset' => 0,
							'publish_time' => TIMENOW,
							'publish_user' => $plan['publish_user'],
							'ip' => $plan['ip'],
							'status' => $plan['status'],
						);
						$this->obj->insert_plan($newchildplan);
					}
				}
			}
		}
		//插入剩余数的新计划  $offset 才开始为0  如果offset为no，表示没有内容了
		$offset = count($contentdata)<$plan['num']?'no':($plan['offset']+count($contentdata));
		if($offset != 'no')
		{
			$newplan = array(
				'set_id' => $plan['sid'],
				'from_id' => $plan['from_id'],
				'sort_id' => $plan['sort_id'],
				'column_id' => $plan['column_id'],
				'title' => $plan['title'],
				'action_type' => 'insert',
				'offset' => $offset,
				'publish_time' => TIMENOW,
				'publish_user' => $plan['publish_user'],
				'ip' => $plan['ip'],
				'status' => $plan['status'],
			);
			$this->obj->insert_plan($newplan);
		}
		return true;
	}
	
	public function delete_content($plan)
	{
		$data = array(
			'bundle_id' => $plan['bundle_id'],
			'module_id' => $plan['module_id'],
			'struct_id' => $plan['struct_id'],
			'struct_ast_id' => $plan['struct_ast_id'],
			'column_id' => $plan['column_id'],
			'content_fromid' => $plan['from_id'],
			'delete_all' => $plan['delete_all'],
		);
		
		if($data['struct_ast_id'])
		{
			$result = $this->pub_content->delete_child_content($data);
		}
		else
		{
			$result = $this->pub_content->delete_content($data);
		}
		
		//0表示不去更新各自模块的expand_id（只是减少栏目，内容还在），1表示更新（全部内容都删除了）
		if($result['msg'] == 1)
		{
			//发布后的内容id传给各自模块的接口记录
			$expand_data = array(
				'column_id' => $plan['column_id'],
				'from_id' => $plan['from_id'],
				'expand_id' => 0,
			);
			$this->pubplan->setAttribute($plan['host'],$plan['path'],$plan['filename'],$plan['action_insert_contentid']);
			$this->pubplan->insert_pub_content_id($expand_data);
		}
		
		return true;
	}
	
	public function update_content($plan)
	{
		//到对应接口取发布的内容,内容格式：二维数组 多条内容 每条内容所有的参数就是发布系统里需要的参数
		$this->pubplan->setAttribute($plan['host'],$plan['path'],$plan['filename'],$plan['action_get_content']);
		$contentdata = $this->pubplan->get_content($plan['from_id'],$plan['sort_id'],$plan['offset'],$plan['num'],true);
		$value = $contentdata[0];
		
		$value['column_id'] = $plan['column_id'];
		$value['bundle_id'] = $plan['bundle_id'];
		$value['module_id'] = $plan['module_id'];
		$value['struct_id'] = $plan['struct_id'];
		$value['struct_ast_id'] = $plan['struct_ast_id'];
		$value['publish_time'] = $plan['publish_time'];
		$value['publish_user'] = $plan['publish_user'];
		
		//把内容发布到发布系统里  遍历value 加上配置里的属性 column_id,应用标识
		$this->pub_content->update_content($value);
		return true;
	}
	
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new publishPlanApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'check_plan';
}
$out->$action();

?>