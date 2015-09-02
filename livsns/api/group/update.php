<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: create.php 2774 2011-03-15 06:58:54Z wang $
***************************************************************************/
//define('ROOT_DIR', '../../');
require('global.php');
define('MOD_UNIQUEID','cp_thread_m');//模块标识

class updateApi extends BaseFrm
{
	private $mUser;
	function __construct()
	{
		parent::__construct();
			
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->mUser = new user();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	//
	function testUser()
	{
		$userinfo['user_id'] = 84769;
		$userinfo['username'] = 'yunaOK';
	
		return $userinfo;
	}
	
	//默认调用方法
	public function show()
	{
		$this->errorOutput(PARAM_NO_FULL);
		
	}
	
	//根据group_id展示category信息
	public function showCategory()
	{
		$params = $this->getAuthParams(MOVE);
		$group_id = $params['0'];
		
		$option_arr = $this->getCategory($group_id);
		$this->setXmlNode('showCategory' , 'category');
		$this->addItem($option_arr);
		$this->output();
	}
	//获取某个category信息
	public function getCategory($group_id)
	{
		$option_arr[0] = '请选择分类';
		if($group_id)
		{
			$sql = 'SELECT id,category_name FROM '.DB_PREFIX.'thread_category WHERE group_id = '.$group_id.'';
			$res = $this->db->fetch_all($sql);
			if($res)
			{
				foreach($res as $reskey=>$resval)
				{
					$option_arr[$resval['id']] = $resval['category_name'];
				}
			}
		}
		return $option_arr;
	}
	//更新category信息
	public function thread_category_batch_edit()
	{
		$params = $this->getAuthParams(MOVE);
		$group_id = $params['0'];
		$user_id = $params['2'];
		$avalid = $this->input['categorys'];
		//获取当前值
		$clean = $to_delete = array();
		if($avalid)
		{
			$avalid_ids = array_keys($avalid);
			foreach ($avalid as $k => $v)
			{
				$v = trim($v);
				$v = $this->clean_value($v);
				if(!$v)
					$to_delete[] = $k;
				else
				{
					if(in_array($v,array_values($clean)))
						$to_delete[] = $k;
					else
						$clean[$k] = $v;
				}
			}
		}
		if($clean)
		{
			foreach ($clean as $k=>$v)
			{
				$this->updateCategory(" category_name='".$v."'"," group_id='".$group_id."' and id=".$k);
			}
		}
		if($to_delete)
		{
			$this->deleteCategory(" group_id='".$group_id."' and id in (".$to_delete.")");
			$this->updateThread(" category_id=0"," group_id='".$group_id."' and category_id in (".$to_delete.")");
		}
		//增加新categorys
		$new = $this->input['new_categorys'];
		$new = trim($new);
		$new = $this->clean_value($new);
		if($new != '请输入新的分类名')
		{
			$this->addCategory($user_id, $group_id, $new);
		}
		
		$option_arr = $this->getCategory($group_id);
		$this->setXmlNode('showCategory' , 'sucess');
		$this->addItem($option_arr);
		$this->output();
	}
	//更新帖子信息
	public function updateThread($filds, $condtion)
	{
		$ret =  false;
		$sql = '';
		$sql = "update ".DB_PREFIX."thread ".$filds ." where 1 ";
		if($condtion)
		{
			$sql .= " and ".$condtion;
		}
		$ret = $this->db->query($sql);
		return $ret;
	}
	
	//增加Category
	public function addCategory($user_id, $group_id, $category)
	{
		$ret = false;
		$sql = "insert into ".DB_PREFIX."thread_category (category_name, user_id, group_id) values";
		$sql .= "('".$category."',".$user_id.",".$group_id.");";
		$ret = $this->db->query($sql);
		return $ret;
	}
	//更新Category
	public function updateCategory($filds, $condtion)
	{
		$ret =  false;
		$sql = '';
		$sql = "update ".DB_PREFIX."thread_category set ".$filds ." where 1 ";
		if($condtion)
		{
			$sql .= " and ".$condtion;
		}
		$ret = $this->db->query($sql);
		return $ret;
	}
	//删除Category
	public function deleteCategory($condtion)
	{
		$sql = '';
		$ret =  false;
		$sql = 'delete from '.DB_PREFIX."thread_category where 1";
		if($condtion)
		{
			$sql .= " and ".$condtion;
		}
		$ret = $this->db->query($sql);
		return $ret;
	}
	//数据更新
	private function update_data($fields, $ids, $add_cond="")
	{
		$ret = false;
		$sql = "update ".DB_PREFIX."thread set $fields where thread_id in ($ids) $add_cond";
		//print_r($sql);exit;
		$ret = $this->db->query($sql);
		return $ret;
	}
	
	//置顶 或者 置顶取消
	public function sticky_thread()
	{
		$web_range = trim(urldecode($this->input['web_range']));
		
		$params = $this->getMainParams(STICKY);
		$group_id = $params['0'];
		$thread_id = $params['1'];
		
		//1 置顶 0取消
		$op_type = $this->input['op_type'] ? 1 : 0;
		
		if(!$web_range){
			$ret = $this->update_data("sticky=".$op_type, $thread_id, " and sticky !=".$op_type);
		}else{
			$ret = $this->update_data("sticky=".$web_range, $thread_id, " and sticky !=".$op_type);
		}
		
		$this->setXmlNode('sticky_thread' , 'success');
		$this->addItem($ret);
		$this->output();
	}
	
	//精华 或者 精华取消
	public function quintess_thread()
	{
		$params = $this->getMainParams(QUINTESSENCE);
		$group_id = $params['0'];
		$thread_id = $params['1'];
		//1加精 0取消
		$op_type = $this->input['op_type'] ? 1 : 0;
		
		$ret = $this->update_data("quintessence=".$op_type, $thread_id, " and quintessence !=".$op_type);
		$this->setXmlNode('quintess_thread' , 'success');
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 批量删除、批量还原话题
	 * @param ids => 要删除的话题id字符串
	 * @param complete=>0 删除到回收站 1:完全删除
	 * @param op_type=> 1 删除, 0:还原
	 */
	public function del_thread()
	{
		$ret = false;
		$complete = trim(urldecode($this->input['complete']));
		$op_type = $this->input['op_type'] ? trim(urldecode($this->input['op_type'])) : 0;
		if($complete == 1)
		{
			$params = $this->getMainParams(THREAD_COMPLETE_DEL);
			$group_id = $params['0'];
			$thread_id = $params['1'];
			
			$this->deleteCompleteThread($thread_id, $group_id);
			$ret = $this->delGroupThread($thread_id);
		}
		else
		{
			$params = $this->getMainParams(THREAD_DEL);
			$group_id = $params['0'];
			$thread_id = $params['1'];
			
			$this->update_data("delete_flag='".$op_type."',delete_time =".TIMENOW, $thread_id, " and delete_flag !=".$op_type);
			$ret = $this->deleteThread($thread_id, $group_id);
		}
		
		$this->setXmlNode('del_thread' , 'success');
		$this->addItem($ret);
		$this->output();
	}
	
	//删除更新
	public function deleteThread($ids, $group_id)
	{
		$pa = $this->db->query_first('select g.parents from ' . DB_PREFIX . 'group g left join ' . DB_PREFIX . 'thread t on g.group_id = t.group_id where t.thread_id in(' . $ids . ')');
		$pa = explode(',',$pa);
		array_pop($pa);
		if($pa)
		{
			$thread_idstr = explode(',',$ids);
			$cnt = count($thread_idstr);
			$pa = implode(',',$pa);
			$ret = $this->db->query('update ' . DB_PREFIX . 'group set thread_count  = case when thread_count - '.$cnt . ' > 0 then thread_count - ' . $cnt . ' else 0 end,post_count = case when post_count - ' . $cnt . ' > 0 then post_count - ' . $cnt . ' else 0 end where group_id in(' . $pa . ')');
		}
	}
	//完全删除更新
	public function deleteCompleteThread($ids, $group_id)
	{
		$sql = "delete from ".DB_PREFIX."thread where thread_id in('".$ids."') and group_id=".$group_id;
		$this->db->query($sql);
		
		$qarr = $this->db->query_first('select parents from ' . DB_PREFIX . 'group where group_id = ' . $group_id);
		
		//更新父级讨论组
		$qarr['parents'] = substr($qarr['parents'], 1, strlen($qarr['parents'])-1);
		
		$qarr = explode(',',$qarr['parents']);
		$arr = explode(',',$ids);
		$cnt = count($arr);
		if(count($qarr) > 1)
		{
			array_pop($qarr);
			$str = implode(',',$qarr);
			//更新父级
			$ret = $this->db->query("update " . DB_PREFIX . "group set thread_count= case when thread_count - $cnt > 0 then thread_count -$cnt else 0 end,post_count= case when post_count - $cnt > 0 then post_count-$cnt else 0 end,update_time=".TIMENOW." where group_id in($str)");
		}
	}
	
	//删除话题--讨论区关联表中对应的数据
	public function delGroupThread($ids)
	{
		$this->db->query('delete from ' . DB_PREFIX . 'group_thread where thread_id in(' . $ids . ')');
	}
	
	//开启和关闭
	public function open_thread()
	{
		$params = $this->getMainParams(OPEN);
		$group_id = $params['0'];
		$thread_id = $params['1'];
		// 1 开启 0关闭
		$op_type = $this->input['op_type'] ? 1 : 0;
		$ret = $this->update_data("open=".$op_type, $thread_id, " and open !=".$op_type);
		
		$this->setXmlNode('open_thread' , 'success');
		$this->addItem($ret);
		$this->output();
	}
	
	//归类type=1时,帖子归入已存分类中;type=2时,增加新加分类后加入
	public function move_thread()
	{
		$params = $this->getMainParams(MOVE);
		$group_id = $params['0'];
		$thread_id = $params['1'];
		$user_id = $params['2'];
		//
		$op_type = $this->input['op_type'] ? $this->input['op_type'] : 1;
		$category = trim(urldecode($this->input['category']));
		if($op_type == 2)
		{
			$category = $this->clean_value($category);
			$this->addCategory($user_id, $group_id, $category);
			$category_id = $this->db->insert_id();
		}
		else 
		{
			$category_id = intval($category);
			if(isset($category_id) && !is_numeric($category_id))
			{
				$this->errorOutput(CATEGORY_NULL);
			}
			$sql = "select id from ".DB_PREFIX."thread_category   WHERE group_id=".$group_id." AND id=".$category_id;
			$categoryStus = $this->db->result_first($sql);
			if(!$categoryStus)
			{
				$category_id = 0;
			}
		}
		$ret = $this->update_data("category_id=".$category_id, $thread_id, " and category_id !=".$op_type);
		if($ret)
		{
			$this->updateCategoryCache($group_id);
		}
		
		$this->setXmlNode('move_thread' , 'success');
		$this->addItem($ret);
		$this->output();
	}
	
	//更新Category缓存
	public function updateCategoryCache($group_id)
	{
		#重建分类缓存
		$this->db->query('update '.DB_PREFIX.'thread_category gc set gc.thread_count = (select count(*) from '.DB_PREFIX.'thread where category_id = gc.id and group_id = "'.$group_id.'") where gc.group_id = "'.$group_id.'"');
	}
	//获取主要参数$params输入为定义好的常量
	protected  function getMainParams($params)
	{
		//验证用户信息
		$userinfo = $this->mUser->verify_credentials();
		//测试数据
		$userinfo = $this->testUser();
		if(!$userinfo['user_id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		//获取参数
		$group_id = trim(urldecode($this->input['group_id']));
		$thread_id = trim(urldecode($this->input['thread_id']));
		
		if(!isset($group_id) || empty($group_id) || !$thread_id)
		{
			$this->errorOutput(PARAM_NO_FULL);
		}
		
		//判断帖子是否在讨论组里
		if($params == THREAD_DEL || $params == THREAD_COMPLETE_DEL)
		{
			//删除时不许删除全站置顶的帖子
			$sql = "select thread_id  from ".DB_PREFIX."thread  where group_id = ".$group_id." and thread_id in (".$thread_id.") and sticky >=0;";
		}
		else
		{
			$sql = "select thread_id  from ".DB_PREFIX."thread  where group_id = ".$group_id." and thread_id in (".$thread_id.");";
		}
		$thread  = $this->db->fetch_all($sql);
		$id = '';
		foreach ($thread as $threadKey)
		{
			$id .= $threadKey['thread_id'].",";
		}
		$id = substr($id, 0, -1);
		if($id != $thread_id)
		{
			if($params == THREAD_DEL)
			{
				$this->errorOutput(DEL_THREAD_NOROOT);
			}
			$this->errorOutput(GROUP_NO_THREAD);
		}
		/*
		 * 判断操作者与讨论组的关系
		 * 权限：管理者（创建者和管理员）和用户
		 * 用户没有任何权限；管理者只有被分配的权限；创建者拥有所有的权限
		 * 
		*/
		
		$sql = "select user_id,state,user_level,permission,blacklist from ".DB_PREFIX."group_members where group_id = ".$group_id." and user_id = ".$userinfo['user_id'];//." and user_level> 0";
		$user  = $this->db->query_first($sql);
		if(!$user)
		{
			$this->errorOutput(GROUP_MEMBER_ERROR);
		}
		
		if($user['user_level'] == 1)
		{
			//用户是讨论组的管理者，拥有地主授权的权限
			$sql = "select * from ".DB_PREFIX."group where group_id = ".$group_id."";
			$group = $this->db->query_first($sql);
			//完全删除只有地主有权限
			if($params == THREAD_COMPLETE_DEL)
			{
				$this->errorOutput(GROUP_SET_NOROOT);
			}
			//位运算判断某位是否是开启的
			if(!($group['permission'] & $params))
			{
				$this->errorOutput(GROUP_SET_NOROOT);
			}
		}
		else if($user['user_level'] == 2)
		{
			//用户是讨论组的创建者，拥有所有的权限
		}
		else
		{
			//用户只是普通会员
			$this->errorOutput(GROUP_MEMBER_NOROOT);
		}
		return array($group_id, $thread_id, $userinfo['user_id']);		
	}
	
	/**
	 * 过滤输入的数据
	 *
	 * @param unknown_type $val
	 * @return unknown
	 */
	function clean_value($val)
	{
		if (is_numeric($val))
		{
			return $val;
		}
		else if (empty($val))
		{
			return is_array($val) ? array() : '';
		}
	
		$val = preg_replace('/&(?!#[0-9]+;)/si', '&amp;', $val);
		$val = preg_replace("/<script/i", "&#60;script", $val);
	
		$pregfind = array('&#032;', '<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r");
		$pregreplace = array(' ', '&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', '<br />', '&#036;', '');
		$val = str_replace($pregfind, $pregreplace, $val);
	
		return preg_replace('/\\\(&amp;#|\?#)/', '&#092;', $val);
	}
	
	//
	public function getAuthParams($params)
	{
		$userinfo = $this->mUser->verify_credentials();
		$userinfo = $this->testUser();
		if(!$userinfo['user_id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		//获取参数
		$group_id = trim(urldecode($this->input['group_id']));
		
		if(!isset($group_id) || empty($group_id))
		{
			$this->errorOutput(PARAM_NO_FULL);
		}
		
		$sql = "select user_id,state,user_level,permission,blacklist from ".DB_PREFIX."group_members where group_id = ".$group_id." and user_id = ".$userinfo['user_id'];//." and user_level> 0";
		$user  = $this->db->query_first($sql);
		if(!$user)
		{
			$this->errorOutput(GROUP_MEMBER_ERROR);
		}
		
		if($user['user_level'] == 1)
		{
			//用户是讨论组的管理者，拥有地主授权的权限
			$sql = "select * from ".DB_PREFIX."group where group_id = ".$group_id."";
			$group = $this->db->query_first($sql);
			//位运算判断某位是否是开启的
			if(!($group['permission'] & $params))
			{
				$this->errorOutput(GROUP_SET_NOROOT);
			}
		}
		else if($user['user_level'] == 2)
		{
			//用户是讨论组的创建者，拥有所有的权限
		}
		else
		{
			//用户只是普通会员
			$this->errorOutput(GROUP_MEMBER_NOROOT);
		}
		
		return array($group_id, 0,$userinfo['user_id'],$userinfo['username']);
	}
}
/**
 *  程序入口
 */
$out = new updateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>