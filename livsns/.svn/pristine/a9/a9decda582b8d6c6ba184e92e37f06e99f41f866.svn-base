<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: card_update.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/card.class.php';	
require_once(ROOT_PATH . 'frm/node_frm.php');
require_once(ROOT_PATH . 'lib/class/auth.class.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishconfig.class.php');
define('MOD_UNIQUEID', 'card'); //模块标识

class cardUpdateApi extends adminUpdateBase
{
	private $card;
	
	public function __construct()
	{
		parent::__construct();
		$this->card = new cardClass();	
		$this->auth = new Auth();
		$this->publishcontent = new publishcontent();
		$this->pubconfig = new publishconfig();	
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->card);
	}
	

	/**
	** 更新操作
	**/
	public function update()
	{
		/***********权限控制**************/
		$this->verify_content_prms();
		/*************************/
		
		$id = intval($this->input['id']);
		if (empty($id)){
			$this->errorOutput(OBJECT_NULL);
		}
		else{
			$info = array();
			$data_qx = array();
			$info = $this->card->detail($id);
			if(is_array($info) &&!empty($info) && count($info)>0)
			{
				$data_qx['id'] = $info['id'];
				$data_qx['user_id'] = $info['user_id'];
				$data_qx['org_id'] = $info['org_id'];
			}
			else
			{
				$this->errorOutput(OBJECT_NULL);
			}
			
			//是否可以修改他人权限
			$this->verify_content_prms($data_qx);
			unset($data_qx);
			//结束
			
			
			$title = $this->input['add_title']?$this->input['add_title']:"";
			$is_title = $this->input['fore-show']?$this->input['fore-show']:0;
			$is_html = $this->input['html-editor']?$this->input['html-editor']:0;
			$is_form = $this->input['form_mode']?1:0; //表单模式
			$column_id = $this->input['column_id'] ? $this->input['column_id'] : 0; //绑定的栏目id
			if(!$this->input['is_dynamic']){$column_id = 0;}
			$is_dynamic = intval($this->input['is_dynamic'])?1:0; //动态配置模式
			$is_default_show = $this->input['default_show']?1:0; //默认展示卡片
			$dingbian_outer_show = $this->input['dingbian_outer_show']?1:0; //卡片是否顶边(外边距)
			$dingbian_inner_show = $this->input['dingbian_inner_show']?1:0; //卡片是否顶边(内边距)
			$is_fix_show = $this->input['fix_show']?1:0; //设为固定卡片
			$card_brief = $this->input['card_brief'];		
			$htmltext = $this->input['html_con']?$this->input['html_con']:"";
			$htmlwidth = $this->input['htmlwidth'];
			$htmlheight = $this->input['htmlheight'];
			$more_link = trim($this->input['title_url']); //卡片右上角"更多"所指向的地址
			//$card_order=$this->input['card_order']?$this->input['card_order']:1;
			if($this->input['valid-time-input']=="")
			{
				$validtime = "";
			}
			else
			{
				$validtime=$this->input['valid-time-input'];		
				$validtime = strtotime($validtime);
			}
			
			//审核状态的改变
			$status=$this->input['status'];
			if($status == 2) //如果是审核状态
			{
				$status = $this->get_status_setting('update_audit', $status);
				if($this->user['prms']['default_setting']['update_audit_content']!=0)
				{
					$status = $status + 1;
				}
			}
			else 
			{
				$status = $this->input['status'];
			}
			
			
			
			$source_id = array();
			$source_id = $this->input['source_id'];
			$contentnumber = count($source_id);
			$update_time = TIMENOW;
			$ip = hg_getip();
			
			$updateData = array();
			
			$updateData['title'] = $title;
			$updateData['is_title'] = $is_title;
			$updateData['is_html'] = $is_html;
			$updateData['is_form'] = $is_form;
			$updateData['is_dynamic'] = $is_dynamic;
			$updateData['column_id'] = $column_id;
			$updateData['is_default_show'] = $is_default_show;
			$updateData['dingbian_outer_show'] = $dingbian_outer_show;
			$updateData['dingbian_inner_show'] = $dingbian_inner_show;
			$updateData['is_fix_show'] = $is_fix_show;
			$updateData['htmltext'] = $htmltext;
			$updateData['htmlwidth'] = $htmlwidth;
			$updateData['htmlheight'] = $htmlheight;
			$updateData['validtime'] = $validtime;
			$updateData['status'] = $status;
			//$updateData['order_id'] = $card_order;
			$updateData['contentnumber'] = $contentnumber;
			$updateData['appid']=$this->user['appid'];
			$updateData['appname']=$this->user['display_name'];
			$updateData['user_id']=$this->user['user_id'];
			$updateData['user_name']=$this->user['user_name'];
			$updateData['org_id'] =  intval($this->user['org_id']);
			$updateData['update_time'] = $update_time;
			$updateData['ip'] = $ip;
			$updateData['more_link'] = $more_link;
			$updateData['card_brief'] = $card_brief;
			
			if($this->input['status'] == 3 || $updateData['status'] == 3)
			{
				$updateData['is_unusually'] = 2;
				$updateData['content'] = "";
				//创建异常纪录
				$result = $this->card->create_un($id);
				unset($result);
			}
			
			if(is_array($updateData) &&!empty($updateData) && count($updateData)>0)
			{
				$result = $this->card->update($updateData,$id);
			}
			else
			{
				$updateData = true;
			}
			
			
			//插入新内容
			$createDatacontent = array();
			$source_id = $this->input['source_id'];       //卡片新闻id
			$source_type = $this->input['source_type'];   //新闻对应样式id
			$source_order = $this->input['source_order']; //新闻排序
			$source_from = $this->input['source_from']; //新闻排序
			$title = $this->input['title'];
			$brief = $this->input['brief'];
			for($m= 0;$m< count($source_id); $m++)
			{
				if($source_from[$m]=="0")
				{
					if(!strpos($source_id[$m],'enu'))
					{
						if($delete_id=="")
						{
							$delete_id = $source_id[$m];
						}
						else
						{
							$delete_id .= ",".$source_id[$m];
						}
					}
				}
			}

			//删除cardid 匹配的原内容，不包含自定义id
			$result_delete = $this->card->delete_all($id,$delete_id);
			//删除菜单数据
			$sql = "DELETE FROM " .DB_PREFIX. "card_content WHERE cssid=8 AND cardid=" .$id;
			$this->db->query($sql);
			/*************************** 处理菜单数据 **************************/
			//去除$source_id里面的menu
			foreach((array)$source_id as $key => $val)
			{
				if(strpos($val,'enu'))
				{
					$tmp = array();
					$tmp = explode(',',$val);
					for($i=0;$i<count($tmp);$i++)
					{
						$tmp[$i] = trim($tmp[$i],'menu');
					}
					$tmp = implode(',',$tmp);
					$menu_id[$key] = $tmp;
				}
			}
			if($menu_id)
			{
				//查出所有菜单的数据
				$str = '';
				foreach((array)$menu_id as $k => $v)
				{
					if($v)
					{
						$str .= $v . ',';
					}
				}
				$str = trim($str,',');
				$sql = "SELECT * FROM " .DB_PREFIX. "card_menu WHERE id IN(" .$str. ")";
				$q = $this->db->query($sql);
				while($row = $this->db->fetch_array($q))
				{
					$menu_data[] = $row;
				}
				//给菜单数据分组
				foreach((array)$menu_id as $k => $v)
				{
					if($v)
					{
						$tmp = array();
						$tmp = explode(',',$v);
						foreach((array)$menu_data as $kk => $vv)
						{
							if(in_array($vv['id'],$tmp))
							{
								$menu_content_data[$v][] = $vv;
							}
						}
					}
				}
				//按分组入库
				foreach((array)$menu_id as $k => $v)
				{
					if($v)
					{
						$m_data = array();
						foreach($menu_content_data[$v] as $ke => $va)
						{
							$pic = unserialize($va['indexpic']);
							$m_data[] = array(
								'id' => 'menu'.$va['id'],
								'title' => $va['title'],
								'brief' => $va['brief'],
								'outlink' => $va['outlink'],
								'host' => $pic['host'],
								'dir' => $pic['dir'],
								'filename' => $pic['filename'],
								'filepath' => $pic['filepath'],
							);
						}
						$childs_data = serialize($m_data);
						$sql = "INSERT INTO " .DB_PREFIX. "card_content SET childs_data='{$childs_data}'";
						$this->db->query($sql);
						$vid = $this->db->insert_id();
						$source_id[$k] = $vid;
						$sql = "UPDATE " .DB_PREFIX. "card_menu SET card_content_id = " .$vid. " WHERE id IN(" .$v. ")";
						$this->db->query($sql);
					}
				}
			}
			/*****************************************************************/
			if(is_array($source_id) && !empty($source_id) && $source_id[0])
			{
				for($i= 0;$i< count($source_id); $i++){
					
					//判断来源，发布库需要新增，自定义需要更新
					if($source_from[$i]=="0")
					{
						$update_from_data = array();
						$update_from_data['cardid'] = $id;
						$update_from_data['order_id'] = $i+1;
						$update_from_data['cssid'] = str_ireplace("style","",$source_type[$i]);
						//自定义内容更新
						if(is_array($update_from_data) &&!empty($update_from_data) && count($update_from_data)>0)
						{
							$result_up = $this->card->update_content($update_from_data,$source_id[$i]);
						}
						
					}
					else {
						
						$result = array();
						$data = array();
						$data['id'] = $source_id[$i];
						//读取单条新闻的详细信息
						$result = $this->publishcontent->get_content($data);
						$result = $result[0];
						$result_type = array();
						//读取类型的中文名称
						$this->publishcontent = new publishcontent();
						$result_type = $this->publishcontent->get_content_type_by_app($result['bundle_id'],$result['module_id']);
						
						$createDatacontent['content_id'] = $source_id[$i];
						$createDatacontent['cardid'] = $id;
						$createDatacontent['cssid'] = str_ireplace("style","",$source_type[$i]);
						$createDatacontent['order_id'] = $i+1;
						$createDatacontent['source_from'] = 1;
						$createDatacontent['module_id'] = $result['module_id'];
						$createDatacontent['module_name'] = $result_type['content_type'];
						$createDatacontent['title'] = $title[$i];
						$createDatacontent['brief'] = $brief[$i] ? $brief[$i] : $result['brief'];
						$createDatacontent['indexpic'] = serialize($result['indexpic']);
						$createDatacontent['outlink'] = $result['outlink'];
						$createDatacontent['create_time'] = TIMENOW;
						if($result['childs_data']==null)
						{
							$createDatacontent['childs_data'] = "";
						}
						else {
							$createDatacontent['childs_data'] = $result['childs_data'];
							$createDatacontent['childs_data'] = serialize($createDatacontent['childs_data']);
						}
						
						//内容入库
						if(is_array($createDatacontent) &&!empty($createDatacontent) && count($createDatacontent)>0)
						{
							$result_card_content = $this->card->create_content($createDatacontent);
							
						}
						
					}
					
					unset($createDatacontent);
					unset($result_card_content);
				}
			}
			
			
			
			if($status==2)
			{
				//如果状态为已审核，那么创建冗余内容,并且清除异常
				$sql_content = 'SELECT * FROM ' . DB_PREFIX . 'card_content WHERE active = 1 and cardid = '.$id.' order by order_id asc,id desc  ';
				$query_content = $this->db->query($sql_content);
				$info_content = array();
				while ($rows_content = $this->db->fetch_array($query_content))
				{
					if($rows_content['indexpic']!="")
					{
						$rows_content['indexpic'] = unserialize($rows_content['indexpic']);
					}
					if($rows_content['childs_data']!="")
					{
						$rows_content['childs_data'] = unserialize($rows_content['childs_data']);
					}
					
					$info_content[] = $rows_content;
				}
				//冗余数据
				$content = serialize($info_content);
				$update_time = TIMENOW;
				$sql = 'update '.DB_PREFIX.'card set status = '.$status.' , is_unusually = 1 , content = \''.$content.'\',update_time = '.$update_time.' where id = ' . $id;
				$this->db->query($sql);
				//清除异常纪录
				$sql_un = 'delete from '.DB_PREFIX.'card_unusually where id = '.$id.' ';
				$this->db->query($sql_un);
				unset($info_content);
				unset($rows_content);
			}
			
			$back_arr = array();
			$back_arr = $this->card->detail($id);;
			//更改缓存
			if(file_exists(CACHE_DIR . 'card.json'))
			{
				@unlink(CACHE_DIR . 'card.json');
			}
			$this->addItem($back_arr);
			$this->output();
		}
	}
	
	public function delete()
	{
		
		/***********权限控制**************/
		//$this->verify_content_prms();
		/*************************/
		
		$ids = trim(urldecode($this->input['id']));
		if(empty($ids))
		{
			$this->errorOutput(OBJECT_NULL);
		}
		/**************审核权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'card WHERE id IN ('. $ids .')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'delete'));
			}
		}
		/*********************************************/
		$result = $this->card->delete($ids);
		
		$back_arr = array();
		$back_arr = array('success' => "1");
		//更改缓存
		if(file_exists(CACHE_DIR . 'card.json'))
		{
			@unlink(CACHE_DIR . 'card.json');
		}
		$this->addItem($back_arr);
		$this->output();
	}
	
	public function display()
	{
		
		/***********权限控制**************/
		$this->verify_content_prms();
		/*************************/
		
		$id = intval($this->input['id']);
		$is_on = intval($this->input['is_on']);
		if(empty($id))
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$result = $this->card->display($id,$is_on);
		$back_arr = array();
		$back_arr = array('success' => "1");
		//更改缓存
		if(file_exists(CACHE_DIR . 'card.json'))
		{
			@unlink(CACHE_DIR . 'card.json');
		}
		$this->addItem($back_arr);
		$this->output();
	}

	public function create()
	{	
		//file_put_contents('2.txt', var_export($this->input,1));exit;
		/***********权限控制**************/
		$this->verify_content_prms();
		/*************************/
		$title = $this->input['add_title']?$this->input['add_title']:"";
		$is_title = $this->input['fore-show']?$this->input['fore-show']:0;
		$is_html = $this->input['html-editor']?$this->input['html-editor']:0;
		$is_form = $this->input['form_mode']?1:0; //表单模式 	
		$is_dynamic = intval($this->input['is_dynamic'])?$this->input['is_dynamic']:0; //动态配置模式
		$is_default_show = $this->input['default_show']?1:0; //默认展示卡片
		$dingbian_outer_show = $this->input['dingbian_outer_show']?1:0; //卡片是否顶边(外边距)
		$dingbian_inner_show = $this->input['dingbian_inner_show']?1:0; //卡片是否顶边(内边距)
		$is_fix_show = $this->input['fix_show']?1:0; //设为固定卡片
		$column_id = $this->input['column_id']; //绑定的栏目id
		$card_brief = $this->input['card_brief'];		
		$htmltext = $this->input['html_con']?$this->input['html_con']:"";
		$htmlwidth = $this->input['htmlwidth'];
		$htmlheight = $this->input['htmlheight'];
		$more_link = trim($this->input['title_url']); //卡片右上角"更多"所指向的地址
		//$card_order=$this->input['card_order']?$this->input['card_order']:1;
		if($this->input['valid-time-input']=="")
		{
			$validtime = "";
		}
		else 
		{
			$validtime=$this->input['valid-time-input'];
			$validtime = strtotime($validtime);
		}
		
		//创建新内容的，审核状态
		$status = $this->get_status_setting('create');
		$status = $status + 1;
		//结束
		
		$source_id = array();
		$source_type = array();
		$source_id = $this->input['source_id'];
		$contentnumber = count($source_id);
		$create_time = TIMENOW;
		$update_time = TIMENOW;
		$ip = hg_getip();
		
		$createData = array();
		$createData['title'] = $title;
		$createData['is_title'] = $is_title;
		$createData['is_html'] = $is_html;
		$createData['is_form'] = $is_form;
		$createData['is_dynamic'] = $is_dynamic;
		$createData['column_id'] = $column_id;
		$createData['is_default_show'] = $is_default_show;
		$createData['dingbian_outer_show'] = $dingbian_outer_show;
		$createData['dingbian_inner_show'] = $dingbian_inner_show;
		$createData['is_fix_show'] = $is_fix_show;
		$createData['htmltext'] = $htmltext;
		$createData['htmlwidth'] = $htmlwidth;
		$createData['htmlheight'] = $htmlheight;
		$createData['validtime'] = $validtime;
		$createData['status'] = $status;
		//$createData['order_id'] = $card_order;
		$createData['contentnumber'] = $contentnumber;
		$createData['appid'] = $this->user['appid'];
		$createData['appname'] = $this->user['display_name'];
		$createData['user_id'] = $this->user['user_id'];
		$createData['user_name'] = $this->user['user_name'];
		$createData['create_time'] = $create_time;
		$createData['update_time'] = $update_time;
		$createData['org_id'] =  intval($this->user['org_id']);
		$createData['ip'] = $ip;
		$createData['more_link'] = $more_link;;
		$createData['card_brief'] = $card_brief;
		
		if(is_array($createData) &&!empty($createData) && count($createData)>0)
		{
			$result_card = $this->card->create($createData);
			$this->update_card_order($result_card['id']);
			$createData['id'] = $result_card['id'];
			$createData['order_id'] = $createData['id'];
		}
		else
		{
			$createData = true;
		}
		
		
		if($result_card['id'])
		{
			//存入内容表
			$createDatacontent = array();
			$source_id = $this->input['source_id'];       //卡片新闻id
			$source_type = $this->input['source_type'];   //新闻对应样式id
			$source_order = $this->input['source_order']; //新闻排序
			$source_from = $this->input['source_from']; //内容来源 发布库1 自定义0
			$title = $this->input['title'];
			$brief = $this->input['brief']; //描述

			/*************************** 处理菜单数据 **************************/
			//去除$source_id里面的menu
			foreach((array)$source_id as $key => $val)
			{
				if(strpos($val,'enu'))
				{
					$tmp = array();
					$tmp = explode(',',$val);
					for($i=0;$i<count($tmp);$i++)
					{
						$tmp[$i] = trim($tmp[$i],'menu');
					}
					$tmp = implode(',',$tmp);
					$menu_id[$key] = $tmp;
				}
			}
			
			if($menu_id)
			{
				//查出所有菜单的数据
				$str = '';
				foreach((array)$menu_id as $k => $v)
				{
					if($v)
					{
						$str .= $v . ',';
					}
				}
				$str = trim($str,',');
				$sql = "SELECT * FROM " .DB_PREFIX. "card_menu WHERE id IN(" .$str. ")";
				$q = $this->db->query($sql);
				while($row = $this->db->fetch_array($q))
				{
					$menu_data[] = $row;
				}
				//给菜单数据分组
				foreach((array)$menu_id as $k => $v)
				{
					if($v)
					{
						$tmp = array();
						$tmp = explode(',',$v);
						foreach((array)$menu_data as $kk => $vv)
						{
							if(in_array($vv['id'],$tmp))
							{
								$menu_content_data[$v][] = $vv;
							}
						}
					}
				}
				//按分组入库
				foreach((array)$menu_id as $k => $v)
				{
					if($v)
					{
						$m_data = array();
						foreach($menu_content_data[$v] as $ke => $va)
						{
							$pic = unserialize($va['indexpic']);
							$m_data[] = array(
								'id' => 'menu'.$va['id'],
								'title' => $va['title'],
								'brief' => $va['brief'],
								'outlink' => $va['outlink'],
								'host' => $pic['host'],
								'dir' => $pic['dir'],
								'filename' => $pic['filename'],
								'filepath' => $pic['filepath'],
							);
						}
						$childs_data = serialize($m_data);
						$sql = "INSERT INTO " .DB_PREFIX. "card_content SET childs_data='{$childs_data}'";
						$this->db->query($sql);
						$vid = $this->db->insert_id();
						$source_id[$k] = $vid;
						$sql = "UPDATE " .DB_PREFIX. "card_menu SET card_content_id = " .$vid. " WHERE id IN(" .$v. ")";
						$this->db->query($sql);
					}
				}
			}
			
			/*****************************************************************/
			//处理其他数据
			if(is_array($source_id) && !empty($source_id) && $source_id[0])
			{
				for($i= 0;$i< count($source_id); $i++){
					//判断来源，发布库需要新增，自定义需要更新
					if($source_from[$i]=="0")
					{
						$update_from_data = array();
						$update_from_data['cardid'] = $result_card['id'];
						$update_from_data['order_id'] = $i+1;
						$update_from_data['cssid'] = str_ireplace("style","",$source_type[$i]);
						//自定义内容更新
						if(is_array($update_from_data) &&!empty($update_from_data) && count($update_from_data)>0)
						{
							$result_up = $this->card->update_content($update_from_data,$source_id[$i]);
						}
					}
					else {
						$result = array();
						$data = array();
						$data['id'] = $source_id[$i];
						//读取单条新闻的详细信息
						$result = $this->publishcontent->get_content($data);
						$result = $result[0];
						$result_type = array();
						//读取类型的中文名称
						$this->publishcontent = new publishcontent();
						$result_type = $this->publishcontent->get_content_type_by_app($result['bundle_id'],$result['module_id']);
						
						$createDatacontent['content_id'] = $source_id[$i];
						$createDatacontent['cardid'] = $result_card['id'];
						$createDatacontent['cssid'] = str_ireplace("style","",$source_type[$i]);
						$createDatacontent['order_id'] = $i+1;
						$createDatacontent['source_from'] = 1;
						$createDatacontent['module_id'] = $result['module_id'];
						$createDatacontent['module_name'] = $result_type['content_type'];
						//$createDatacontent['title'] = $result['title'];
						$createDatacontent['title'] = $title[$i];
						$createDatacontent['brief'] = $brief[$i] ? $brief[$i] : $result['brief'];
						$createDatacontent['indexpic'] = serialize($result['indexpic']);
						$createDatacontent['outlink'] = $result['outlink'];
						$createDatacontent['create_time'] = TIMENOW;
						if($result['childs_data']==null)
						{
							$createDatacontent['childs_data'] ="";
						}
						else {
							$createDatacontent['childs_data'] = $result['childs_data'];
							$createDatacontent['childs_data'] = serialize($createDatacontent['childs_data']);
						}
						//内容入库
						if(is_array($createDatacontent) &&!empty($createDatacontent) && count($createDatacontent)>0)
						{
							$result_card_content = $this->card->create_content($createDatacontent);
							
						}
						
					}
					
					unset($createDatacontent);
					unset($result_card_content);
				}
			}
			
			
			
			if($status==2)
			{
				$id = $result_card['id'];
				//如果状态为已审核，那么创建冗余内容,并且清除异常
				$sql_content = 'SELECT * FROM ' . DB_PREFIX . 'card_content WHERE active = 1 and cardid = '.$id.' order by order_id asc,id desc  ';
				$query_content = $this->db->query($sql_content);
				$info_content = array();
				while ($rows_content = $this->db->fetch_array($query_content))
				{
					if($rows_content['indexpic']!="")
					{
						$rows_content['indexpic'] = unserialize($rows_content['indexpic']);
					}
					if($rows_content['childs_data']!="")
					{
						$rows_content['childs_data'] = unserialize($rows_content['childs_data']);
					}
					
					$info_content[] = $rows_content;
				}
				//冗余数据
				$content = serialize($info_content);
				$update_time = TIMENOW;
				$sql = 'update '.DB_PREFIX.'card set status = '.$status.' , is_unusually = 1 , content = \''.$content.'\',update_time = '.$update_time.' where id = ' . $id;
				$this->db->query($sql);
				//清除异常纪录
				$sql_un = 'delete from '.DB_PREFIX.'card_unusually where id = '.$id.' ';
				$this->db->query($sql_un);
				unset($info_content);
				unset($rows_content);
			}
			
		}
		//更改缓存
		if(file_exists(CACHE_DIR . 'card.json'))
		{
			@unlink(CACHE_DIR . 'card.json');
		}
					
		$this->addItem($createData);
		$this->output();
	}
	protected function update_card_order($order_id = 0, $cid = 0)
	{
		$cid = $cid ? $cid : $order_id;
		$this->db->query('UPDATE ' . DB_PREFIX  . 'card SET order_id = '.$order_id.' WHERE id = '.$cid);
	}
	public function audit()
	{
		
		/***********权限控制**************/
		//$this->verify_content_prms();
		/*************************/
		
		$ids = trim(urldecode($this->input['id'])); //条目的id
		$status = trim(urldecode($this->input['status'])); //状态值
		if(empty($ids) || empty($status))
		{
			$this->errorOutput(OBJECT_NULL);
		}
		/**************审核权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'card WHERE id IN ('. $ids .')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'audit'));
			}
		}
		/*********************************************/
		$result = $this->card->audit($ids,$status);
		$back_arr = array();
		$back_arr = array('id' =>$result['id'],'status' => $result['status']);
		//更改缓存
		if(file_exists(CACHE_DIR . 'card.json'))
		{
			@unlink(CACHE_DIR . 'card.json');
		}
		$this->addItem($back_arr);
		$this->output();
	}
	
	public function diycontent()
	{
		/***********权限控制**************/
		$this->verify_content_prms(array('_action' => 'create'));
		/*************************/
		require_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
		
		$title = $this->input['title'];
		$brief 	= $this->input['brief'];
		$outlink = $this->input['outlink'];
		
		if(is_array($_FILES['picture']) &&!empty($_FILES['picture']) && count($_FILES['picture'])>0)
		{
			$indexpic = array();
			$file_square['Filedata'] = $_FILES['picture'];
			$material_square = $this->mMaterial->addMaterial($file_square, $id);
			//$this->errorOutput(var_export($material_square,1));
			if(!$material_square['host'])
			{
				$this->errorOutput("图片格式不在允许范围之内!");
			}		
			$indexpic['host'] = $material_square['host'];
			$indexpic['dir'] = $material_square['dir'];
			$indexpic['filepath'] = $material_square['filepath'];
			$indexpic['filename'] = $material_square['filename'];
		}
		
		$createData = array();
		
		$createData['title'] = $title;
		$createData['brief'] = $brief;
		$createData['outlink'] = $outlink;
		$createData['indexpic'] = serialize($indexpic);
		$createData['source_from'] = 0;
		$createData['create_time'] = TIMENOW;
		
		if(is_array($createData) &&!empty($createData) && count($createData)>0)
		{
			if($this->input['type'] == 'menu')
			{
				$sql = " INSERT INTO ".DB_PREFIX."card_menu SET ";
				foreach ($createData AS $k => $v)
				{
					$sql .= " {$k} = '{$v}',";
				}
				$sql = trim($sql,',');
				$this->db->query($sql);
				$vid = $this->db->insert_id();
				$createData['id'] = 'menu'.$vid;
			}
			else
			{
				$result_card = $this->card->create_content($createData);
				$createData['id'] = $result_card['id'];
			}
		}
		else
		{
			$createData = true;
		}
		$createData['indexpic'] = $indexpic['host'].$indexpic['dir'].$indexpic['filepath'].$indexpic['filename'];
		$this->addItem($createData);
		$this->output();
		
	}
	public function update_diycontent()
	{
		/***********权限控制**************/
		$this->verify_content_prms(array('_action' => 'update'));
		/*************************/
		require_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
		$title = $this->input['title'];
		$brief 	= $this->input['brief'];
		$outlink = $this->input['outlink'];
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput("无效纪录!");
		}
		if($this->input['type'] != 'menu')
		{
			$sql = 'SELECT id,indexpic FROM '.DB_PREFIX .'card_content WHERE id = '.$id;
			$row = $this->db->query_first($sql);
			if(!$row)
			{
				$this->errorOutput('无效纪录!');
			}
			$updateData = array();
			$updateData['indexpic'] = $row['indexpic'];
		}
		else
		{
			$menu_id = trim($id,'mune');
			$sql = 'SELECT indexpic FROM '.DB_PREFIX .'card_menu WHERE id = '.$menu_id;
			$row = $this->db->query_first($sql);
			if(!$row)
			{
				$this->errorOutput('无效纪录!');
			}
			$updateData = array();
			$updateData['indexpic'] = $row['indexpic'];
		}
		//图片服务器
		if(!$_FILES['picture']['error'] && $_FILES['picture']['tmp_name'])
		{
			$indexpic = array();
			$file_square['Filedata'] = $_FILES['picture'];
			$material_square = $this->mMaterial->addMaterial($file_square, $id);
			if($material_square['error'])
			{
				$this->errorOutput("文件上传错误!");
			}		
			$indexpic['host'] = $material_square['host'];
			$indexpic['dir'] = $material_square['dir'];
			$indexpic['filepath'] = $material_square['filepath'];
			$indexpic['filename'] = $material_square['filename'];
			
			$updateData['indexpic'] = serialize($indexpic);
		}
		$updateData['title'] = $title;
		$updateData['brief'] = $brief;
		$updateData['outlink'] = $outlink;
		$updateData['source_from'] = 0;
		if($this->input['type'] != 'menu')
		{
			$result_card = $this->card->update_content($updateData, $id);
		}
		else
		{
			$sql = "UPDATE " .DB_PREFIX. "card_menu SET ";
			foreach((array)$updateData as $k => $v)
			{
				$sql .= $k. " = '" .$v. "',";
			}
			$sql = trim($sql,',');
			$sql .= " WHERE id = " .$menu_id;
			$this->db->query($sql);
			$result_card = $updateData;
		}
		
		if(!$result_card)
		{
			$this->errorOutput('更新失败!');
		}
		if($tmp = unserialize($updateData['indexpic']))
		{
			$updateData['indexpic'] = hg_fetchimgurl($tmp);
			unset($tmp);
		}
		$this->addItem($updateData);
		$this->output();
	}
	public function publish()
	{
		
	}
	public function edit_title($content_id = '')
	{
		//是否同步
		$sycn = intval($this->input['sycn_title']);
		
		//标题
		$title = trim($this->input['title']);
		
		//描述
		$brief = trim($this->input['brief']);
		
		//卡片内容id
		$id = intval($this->input['id']);
		
		if(!$content_id)
		{
			$rid = $this->input['content_id'];
		}
		else
		{
			$rid = $content_id;
		}
		if($id)
		{
			$sql = 'SELECT id,content_id FROM ' . DB_PREFIX. 'card_content WHERE id='.$id;
			$is_exists = $this->db->query_first($sql);
			if(!$is_exists)
			{
				$this->errorOutput("内容不存在!");
			}
			$rid = $is_exists['content_id'];
		}
		//同步修改发布库标题
		if($sycn)
		{
			$data = array(
			'rid'=>$rid,
			'title'=>$title,
			'brief'=>$brief,
			);
			$is_success = $this->publishcontent->update_content_by_rid($data);
			//$this->errorOutput(var_export($is_success,1));
		}
		$sql = 'UPDATE ' . DB_PREFIX .'card_content set title="'.addslashes($title).'" WHERE id='.$id;
		$this->db->query($sql);
		//更改缓存
		if(file_exists(CACHE_DIR . 'card.json'))
		{
			@unlink(CACHE_DIR . 'card.json');
		}
		$this->addItem(array('content_id'=>$rid,'title'=>$title));
		$this->output();
	}
	public function sort()
	{
		$content_id = $this->input['content_ids'];
		$order_id 	= $this->input['order_ids'];
		$this->input['content_id'] = $this->input['content_ids'];
		$this->input['order_id'] 	= $this->input['order_ids'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->drag_order('card', 'order_id');
		//更改缓存
		if(file_exists(CACHE_DIR . 'card.json'))
		{
			@unlink(CACHE_DIR . 'card.json');
		}
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 根据栏目id获取栏目下数据
	 * Enter description here ...
	 */
	public function get_dynamic()
	{
		if(!$this->input['column_id'])
		{
			$this->errorOutput(NO_COLUMN);
		}
		$count = intval($this->input['number']) ? intval($this->input['number']) : 5;
		global $gGlobalConfig;
		if ($gGlobalConfig['App_publishcontent'])
		{
			include_once (ROOT_PATH . 'lib/class/curl.class.php');
			$publish = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir']);
		}
		if (!$publish)
		{
			$this->errorOutput('请求发布库错误');
		}
		$publish->setSubmitType('post');
		$publish->setReturnFormat('json');
		$publish->initPostData();
		$publish->addRequestData('count',$count);
		$publish->addRequestData('column_id',$this->input['column_id']);
		$publish->addRequestData('client_type', 1);
		$publish->addRequestData('a','get_content');
		$publish->addRequestData('html',true);
		$r = $publish->request('content.php');
		if($r)
		{
			$ret = array(
				'content' => $r,
				'style' => $this->input['style_id'],
			);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
}

$out = new cardUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>