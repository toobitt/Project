<?php
define('MOD_UNIQUEID','feedback_result');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/feedback_mode.php');
include_once(ROOT_DIR . 'lib/class/publishconfig.class.php');
include_once CUR_CONF_PATH . 'lib/mail/PHPMailerAutoload.php';

class feedback_result extends adminReadBase
{
	private $mode;
	private $mPublishColumn;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new feedback_mode();
		$this->mPublishColumn = new publishconfig();	
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$id = intval($this->input['fid']);
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$orderby = ' ORDER BY rp.order_id DESC , rp.id DESC';
		$condition = $this->get_condition();
		$feedback = $this->mode->get_feedback(' id='.$id);
		if(!$feedback)
		{
			$this->errorOutput('没有此反馈表单！');
		}
		######获取默认数据状态
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $feedback['admin_user'])
		{
			$admin_user = array();
			$admin_user = explode(',',$feedback['admin_user']);
			if(!in_array($this->user['user_name'],$admin_user))
			{
				$this->errorOutput("对不起，您没有此表单的权限");
			}
		}
		elseif($this->user['group_type'] > MAX_ADMIN_TYPE && !$feedback['admin_user'])
		{
			$this->verify_content_prms(array('_action'=>'show_result'));
		}
		
		$result['title'] = $feedback['title'];
		if(!$list_name = $this->get_list_title($id))
		{
			$this->errorOutput('未设置表单回收名称');
		}
		if($feedback['is_login'])//如果不需要用户登录，则读取设定的单行文本作为列表的title
		{
			if($this->input['k'])
			{
				$condition .= ' AND user_name like %'.trim($this->input['k']).'%';
			}
			$orderby = ' ORDER BY rp.order_id DESC , rp.id DESC';
			$sql = 'SELECT * FROM ' .DB_PREFIX.'record_person rp WHERE rp.feedback_id IN ('.$id.') '. $condition;
			$sql .= $orderby .' LIMIT ' . $offset . ' , ' . $count;
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$column_id = intval($r['column_id']);
				if($column_id)
				{
					$column = $this->mPublishColumn->get_columnname_by_ids('id,name',$column_id);
				}
				$r['create_time'] = $r['create_time'] ? date('Y-m-d H:i:s',$r['create_time']) : 0;
				$result['list'][] = array(
				    'id'    => $r['id'],
					'user_id'    => $r['user_id'],
				    'title' => $r['user_name'] ? $r['user_name'] : '匿名用户（IP:'.$r['ip'].')',
				    'column'=> $column[$column_id] ? $column[$column_id] :'',
				    'create_time' => $r['create_time'],
				    'process'    => $r['process'],
					'reply'    => $r['new_reply'],
				    'order_id'   => $r['order_id'],
				);
			}
		}
		else
		{
			if($this->input['k'])
			{
				$rcondition = ' AND r.value like "%'.trim($this->input['k']).'%"';
			}
			$sql = 'SELECT distinct rp.id as id,rp.* FROM ' .DB_PREFIX.'record_person rp LEFT JOIN ' .DB_PREFIX.'record r ON rp.id = r.person_id  WHERE rp.feedback_id IN ('.$id.') '. $condition.$rcondition;
			$sql .= $orderby .' LIMIT ' . $offset . ' , ' . $count ;
			$qs = $this->db->query($sql);
			while ($rs = $this->db->fetch_array($qs))
			{
				$rec_person[] = $rs['id'];
				$column_id = intval($rs['column_id']);
				if($column_id)
				{
					$column = $this->mPublishColumn->get_columnname_by_ids('id,name',$column_id);
				}
				$rs['column'] = $column[$column_id] ? $column[$column_id] :'';
				$rs['create_time'] = $rs['create_time'] ? date('Y-m-d H:i:s',$rs['create_time']) : 0;
				$rec_ot[$rs['id']] = $rs;
			}
			if(!$rec_person)
			{
				$this->addItem($result['list']);
				$this->output();
			}
			$persons = implode(',',$rec_person);
			$sql = 'SELECT * FROM ' .DB_PREFIX.'record r WHERE r.person_id IN ('.$persons.') AND r.form_id = '.$list_name['id'].' AND type = "'.$list_name['type'].'"';
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$r['value'] = $r['value'] ? $r['value'] : '未填写';
				$rec[$r['person_id']] = $r;
			}
			foreach ($rec_person as $v)
			{
				if($rec[$v]['value'])
				{
					$result['list'][] = array(
					    'id'    => $rec_ot[$v]['id'],
						'user_id'    => $rec_ot[$v]['user_id'],
					    'title' => $rec[$v]['value'],
					    'column'=> $rec_ot[$v]['column'],
					    'create_time' => $rec_ot[$v]['create_time'],
					    'process'    => $rec_ot[$v]['process'],
						'reply'    	=> $rec_ot[$v]['new_reply'],
					    'order_id'   => $rec_ot[$v]['order_id'],
					);
				}
			}
		}
		$this->addItem($result);
		$this->output();
	}

	public function count()
	{
		$id = $this->input['fid'];
		$condition = $this->get_condition();
		if(trim($this->input['k']))
		{
			$list = $this->mode->get_feedback_list(' id = '.$id,'is_login');
			if($list['is_login'])
			{
				$sql = 'SELECT count(*) as total FROM ' .DB_PREFIX.'record_person rp WHERE rp.feedback_id IN ('.$id.')'. $condition ;
				$info = $this->db->query_first($sql);
			}
			else 
			{
				$listName = $this->get_list_title($id);
				$sql = 'SELECT count(*) as total FROM ' .DB_PREFIX.'record r LEFT JOIN ' .DB_PREFIX.'record_person rp ON rp.id = r.person_id WHERE r.feedback_id IN ('.$id.')  AND form_id = '.$listName['id'].' AND type = "'.$listName['type'].'" AND value like "%'.trim($this->input['k']).'%" '.$condition;
				$info = $this->db->query_first($sql);
			}
		}
		else 
		{
			//$sql = 'SELECT count(distinct r.person_id) as total FROM ' .DB_PREFIX.'record r LEFT JOIN ' .DB_PREFIX.'record_person rp ON rp.id = r.person_id WHERE r.feedback_id IN ('.$id.')';
			$sql = 'SELECT count(*) as total FROM ' .DB_PREFIX.'record_person rp WHERE rp.feedback_id IN ('.$id.')'. $condition ;
			$info = $this->db->query_first($sql);
		}
    	echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition = '';

		if(isset($this->input['process']) && $this->input['process'] !=-1)
		{
			$condition .= " AND rp.process = ".intval($this->input['process']);
		}
		
		return $condition;
	}
	
	public function detail()
	{
		$person_id = $this->input['id'];
		$feedback_id = $this->input['fid'];
		if(!$person_id)
		{
			$this->errorOutput(NOID);
		}
		if(!$feedback_id)
		{
			$this->errorOutput('没有输入反馈的id');
		}

		$feedback = $this->mode->detail($feedback_id);
		
		$forms = $feedback['forms'];
		if(!$forms)
		{
			$this->errorOutput('该反馈没有设置表单！');
		}
		foreach ($forms as $k=>$v)
		{
			$rec[$v['type']][$v['id']] = array(
			   'type' => $v['type'],
			   'name' => $v['name'],
			   'fixed_id' => $v['fixed_id'],
			);
		}
		/*作为回收表单名的组件id*/
		$sql = 'SELECT MIN(id) as id FROM ' .DB_PREFIX.'fixed WHERE fid IN ('.$feedback_id.') AND is_name = 1';
		$use_fix_name = $this->db->query_first($sql);
		if($use_fix_name['id'])
		{
			$list_name['id'] = $use_fix_name['id'];
			$list_name['type'] = 'fixed';
		}
		else
		{
			$sql = 'SELECT MIN(id) as id FROM ' .DB_PREFIX.'standard WHERE fid IN ('.$feedback_id.') AND is_name = 1';
			$use_st_name = $this->db->query_first($sql);
			if($use_st_name['id'])
			{
				$list_name['id'] = $use_st_name['id'];
				$list_name['type'] = 'standard';
			}
		}
		$all_person = array();
		$sql = 'SELECT * FROM ' .DB_PREFIX.'record_person WHERE feedback_id IN ('.$feedback_id.') ORDER BY order_id DESC' ;
		$all_person = $this->db->fetch_all($sql);
		if($all_person)
		{
			foreach ($all_person as $k=>$v)
			{
				$per[$v['id']] =  $k;
			}
		}
		$sql = 'SELECT * FROM ' .DB_PREFIX . 'materials WHERE content_id = ' .$feedback_id;	
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			if($r['filename'])
			{
				$r['tp'] = '2';
				$r['m3u8'] = $r['host'].'/'.$r['dir'].$r['filename'].'.m3u8';
			}
			if($r['pic_name'])
			{
				$r['tp'] = '1';
			}
			$mat[$r['id']] = $r;
		}
		$sql = 'SELECT * FROM ' .DB_PREFIX . 'record_person rp LEFT JOIN ' .DB_PREFIX .'record r ON rp.id = r.person_id WHERE rp.id = ' .$person_id . ' ORDER BY r.order_id' ;	
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$result['person_id'] = $person_id;
			$result['last_id'] = $all_person[$per[$person_id]-1]['id'];
			$result['next_id'] = $all_person[$per[$person_id]+1]['id'];
			$result['user_id'] = $r['user_id'];
			$result['user_name'] = $r['user_name'];
			$recycle[$r['type']][$r['form_id']] = $r['value'];
			$result['feedback_id'] = $feedback_id;
			$result['title'] = $feedback['title'];
			$result['create_time'] = $r['create_time'] ? date('Y-m-d H:i:s',$r['create_time']) : 0;
			$result['ip'] = $r['ip'];
			$column_id = intval($r['column_id']);
			if($column_id)
			{
				$column = $this->mPublishColumn->get_columnname_by_ids('id,name',$column_id);
			}
			$result['column'] = $column[$column_id];
		    if($r['value'] && $r['type'] == 'file')
			{
				$mat_ids = explode(',',$r['value']);
				foreach ($mat_ids as  $k=>$v)
				{
					if($v)
					{
						$mat_id = @explode('_',$v);
						if($mat_id[1])
						{
							$mat[$mat_id[1]]['index_img'] = $mat[$mat_id[0]];
							$file_value[$k] =  $mat[$mat_id[1]];
						}else 
						{
							$file_value[$k] =  $mat[$mat_id[0]];
						}
						
					}
				}
				$r['value'] = $file_value;
			}
			$result['answer'][] = array(
				'name' => $r['form_name'] ? $r['form_name'] : $rec['standard'][$r['form_id']]['name'],
			    'value' => $r['value'],
				);
			
		}
		if($result)
		{
			if($feedback['is_login'])
			{
				$result['recycle_name'] = $result['user_name'] ? $result['user_name'] : '匿名用户（IP:'.$result['ip'].')';
			}
			elseif($list_name && $rec[$list_name['type']][$list_name['id']])
			{
				$result['recycle_name'] = $recycle[$list_name['type']][$list_name['id']] ? $recycle[$list_name['type']][$list_name['id']] : '未填写';
			}
			else
			{
				$result['recycle_name'] = $result['user_name'] ? $result['user_name'] : '匿名用户';
			}
			$this->addItem($result);
			$this->output();
		}
	}
	
	public function delete()
	{
		$id = trim($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$sql = 'SELECT id,feedback_id FROM ' .DB_PREFIX . 'record_person WHERE id in( '.$id .')';
		$ps = $this->db->fetch_all($sql);
		if($ps[0]['feedback_id'])
		{
			$sql = 'SELECT counts,user_id,org_id,admin_user FROM ' .DB_PREFIX . 'feedback WHERE id ='.$ps[0]['feedback_id'];
			$count = $this->db->query_first($sql);
		}
				######获取默认数据状态
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $count['admin_user'])
		{
			$admin_user = array();
			$admin_user = explode(',',$count['admin_user']);
			if(!in_array($this->user['user_name'],$admin_user))
			{
				$this->errorOutput("对不起，您没有此表单的权限");
			}
		}
		else if ($this->user['group_type'] > MAX_ADMIN_TYPE && !$count['admin_user'])
	    {
	    	$this->verify_content_prms(array('_action'=>'manage'));
	    	   	
			if(!$this->user['prms']['default_setting']['manage_other_data'])
			{
				if($count['user_id'] != $this->user['user_id'])
				{
					 $this->errorOutput(NO_PRIVILEGE);
				}
			};
			if ($this->user['prms']['default_setting']['manage_other_data'] == 1 && $this->user['slave_org'])
	        {
	        	 if (!in_array($count['org_id'], explode(',', $this->user['slave_org'])))
	             {
	             	$this->errorOutput(NO_PRIVILEGE);
	             }
	        }
	    }
		$sql = 'DELETE FROM ' .DB_PREFIX . 'record WHERE person_id in( '.$id .')';
		$q = $this->db->query($sql);
		$sql = 'DELETE FROM ' .DB_PREFIX . 'record_person WHERE id in( '.$id .')';
		$q = $this->db->query($sql);
		$num = count($ps);
		if($num < $count['counts'])
		{
			$sql = 'UPDATE ' .DB_PREFIX . 'feedback SET counts = counts - '.$num.' WHERE id = '.$ps[0]['feedback_id'] ;
			$q = $this->db->query($sql);
		}
		else 
		{
			$sql = 'UPDATE ' .DB_PREFIX . 'feedback SET counts = 0 WHERE id = '.$ps[0]['feedback_id'] ;
			$q = $this->db->query($sql);
		}
		/****计算已通过处理人数和未通过处理人数******/
		$result[1] = $result[2] = 0;     
		$sql = 'SELECT process,count(process) as count FROM '.DB_PREFIX.'record_person  WHERE feedback_id = '.$ps[0]['feedback_id'] .' GROUP BY process';
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$result[$r['process']] = $r['count'];
		}
		$sql = 'UPDATE '.DB_PREFIX.'feedback SET processed_count = '.$result[1] .', unprocessed_count = '.$result[2].' WHERE id ='.$ps[0]['feedback_id'] ;
		$this->db->query($sql);
		/****计算已通过处理人数和未通过处理人数******/
		$this->addItem($id);
		$this->output();
	}
	
	public function sort()
	{
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->drag_order('record_person', 'order_id');
	
		$this->addItem($ret);
		$this->output();
	}
	
	public function process()
	{
		$ids = trim($this->input['id']);
		$sql = 'SELECT id,feedback_id,user_id,credit FROM ' .DB_PREFIX . 'record_person WHERE id in( '.$ids .')';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$feedback_id = $r['feedback_id'];
			if(!$r['credit'] && $r['user_id']) //未加过积分的会员
			{
				$uncredit_user[] = $r['user_id'];
			}
			if($r['credit'] && $r['user_id']) //加过积分的会员
			{
				$credit_user[] = $r['user_id'];
			}
		}
		if($feedback_id)
		{
			$sql = 'SELECT user_id,org_id,is_credit,credit1,credit2,title,admin_user FROM ' .DB_PREFIX . 'feedback WHERE id ='.$feedback_id;
			$count = $this->db->query_first($sql);
		}
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $count['admin_user'])
		{
			$admin_user = array();
			$admin_user = explode(',',$count['admin_user']);
			if(!in_array($this->user['user_name'],$admin_user))
			{
				$this->errorOutput("对不起，您没有此表单的权限");
			}
		}
		elseif ($this->user['group_type'] > MAX_ADMIN_TYPE && !$count['admin_user'])
	    {	    	
			$this->verify_content_prms(array('_action'=>'manage'));
	    	if(!$this->user['prms']['default_setting']['manage_other_data'])
			{
				if($count['user_id'] != $this->user['user_id'])
				{
					 $this->errorOutput(NO_PRIVILEGE);
				}
			};
			if ($this->user['prms']['default_setting']['manage_other_data'] == 1 && $this->user['slave_org'])
	        {
	        	 if (!in_array($count['org_id'], explode(',', $this->user['slave_org'])))
	             {
	             	$this->errorOutput(NO_PRIVILEGE);
	             }
	        }
	    }
		if(!$ids)
		{
			$this->output(NOID);
		}
		$pro = intval($this->input['process']);
		$sql = 'UPDATE '.DB_PREFIX.'record_person SET process = '.$pro.' WHERE id in('.$ids.')' ;
		$this->db->query($sql);
		$ret = array(
		       'id' => explode(',',$ids),
		       'process' => $pro
		      );
		/******************会员报名通过审核后添加积分*******************/     
		if($feedback_id && $ret && $count['is_credit'] && AUDIT_ADD_CRIDET && $pro ===1 && $uncredit_user)
		{
			require_once ROOT_DIR.'lib/class/members.class.php';
			$members = new members();
			if(is_array($uncredit_user))
			{
				$uncredit_user = array_unique($uncredit_user);
				foreach ($uncredit_user as $k =>$user_id)
				{
					$re = $members->add_credit(
		            	$user_id,
		            	array('credit1'=>$count['credit1'],'credit2'=>$count['credit2']),
		            	$feedback_id,
		            	APP_UNIQUEID,
		            	MOD_UNIQUEID,
		            	'audit',
		            	'参与:'.$count['title'],
		            	'反馈表单'
		           );
					if($re[0]['logid'])
					{
						$addcredit_user[] = $user_id;
					}
				}
			}
			if($addcredit_user)
			{
				$addcredit_user = implode(',',$addcredit_user);
				$sql = 'UPDATE '.DB_PREFIX.'record_person SET credit = 1 WHERE feedback_id = ' .$feedback_id. ' and user_id in('. $addcredit_user .')';
				$this->db->query($sql);
			}
			
		}
		/******************会员报名通过审核后添加积分*******************/     
		/******************会员报名打回后减积分*******************/     
		if($feedback_id && $ret && $count['is_credit'] && AUDIT_ADD_CRIDET && $pro !==1 && $credit_user)
		{
			require_once ROOT_DIR.'lib/class/members.class.php';
			$members = new members();
			if(is_array($credit_user))
			{
				$credit_user = array_unique($credit_user);
				foreach ($credit_user as $k =>$user_id)
				{
					$re = $members->sub_credit(
	            	$user_id,
	            	array('credit1'=>$count['credit1'],'credit2'=>$count['credit2']),
	            	$feedback_id,
	            	APP_UNIQUEID,
	            	MOD_UNIQUEID,
	            	'audit',
	            	'删除：'.$count['title'],
	            	'反馈表单'
	           		);
					if($re[0]['logid'])
					{
						$subcredit_user[] = $user_id;
					}
				}
			}
			if($subcredit_user)
			{
				$subcredit_user = implode(',',$subcredit_user);
				$sql = 'UPDATE '.DB_PREFIX.'record_person SET credit = 0 WHERE feedback_id = ' .$feedback_id. ' and user_id in('. $subcredit_user .')';
				$this->db->query($sql);
			}
		}
		/******************会员报名打回后减积分*******************/     
		/****计算已通过处理人数和未通过处理人数******/
		$result[1] = $result[2] = 0;     
		$sql = 'SELECT process,count(process) as count FROM '.DB_PREFIX.'record_person  WHERE feedback_id = '.$feedback_id .' GROUP BY process';
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$result[$r['process']] = $r['count'];
		}
		$sql = 'UPDATE '.DB_PREFIX.'feedback SET processed_count = '.$result[1] .', unprocessed_count = '.$result[2] ;
		$sql .= " WHERE id = '"  .$feedback_id. "'";
		$this->db->query($sql);
		/****计算已通过处理人数和未通过处理人数******/
		$this->addItem($ret);
		$this->output();
	}
	
	public function download_excel()
	{ 
		$this->verify_content_prms(array('_action'=>'show_result'));
		$feedback_id = $this->input['fid'];
		if(!$feedback_id)
		{
			$this->errorOutput(NO_FEEDBACK_ID);
		}
		$person = array();
		$condition = $this->get_condition();
		$sql = "SELECT * FROM ".DB_PREFIX."record_person rp  WHERE rp.feedback_id IN(".$feedback_id.")".$condition;
		$person = $this->db->fetch_all($sql);
		if($person && count($person)>0)
		{
			foreach ($person as $k=>$v)
			{
				$fid[] = $v['id'];
			}
			$fids = implode(',',$fid);
		}
		if($fids)
		{
			$forms = $this->mode->get_forms($feedback_id);
			if($forms && is_array($forms))
			{
				foreach ($forms as $k=>$v)
				{
					$form_name[$v['type']][$v['id']] = $v['name'];
				}
			}
			$form_ids = array();
			$sql = 'SELECT * FROM ' .DB_PREFIX . 'materials WHERE content_id = ' .$feedback_id;	
			$q = $this->db->query($sql);
			while ($rs = $this->db->fetch_array($q))
			{
				if($rs['vodid'])
				{
					if($this->settings['App_mediaserver'])
					{
						$rs['url'] = $this->settings['App_mediaserver']['protocol'].$this->settings['App_mediaserver']['host'] . '/' . $this->settings['App_mediaserver']['dir'] . '/admin/download.php?id='.$rs['vodid'];
					}
					else
					{
						$rs['url'] = $rs['host'].'/'.$rs['dir'].$rs['filename'].'.mp4';
					}
				}
				if($rs['original_id'])
				{
					$rs['url'] = hg_material_link($rs['host'], $rs['dir'], $rs['material_path'], $rs['pic_name']);;
				}
				$mat[$rs['id']] = $rs['url'];
			}			
			$sql = "SELECT r.*,f.title,f.brief FROM ".DB_PREFIX."record r LEFT JOIN ".DB_PREFIX."feedback f ON r.feedback_id = f.id WHERE r.person_id in( ".$fids .") ORDER BY r.order_id desc";
			$q = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				$rec['title'] = $r['title'];
				$rec['brief'] = $r['brief'];
				if($r['type']=='file')
				{
					$r['type'] = 'standard';
					$fvalue = trim($r['value'],',');
					if($fvalue)
					{
						$file_value = array();
						$mat_ids = explode(',',$fvalue);
						foreach ($mat_ids as  $k=>$vm)
						{
							if($vm)
							{
								$mat_id = @explode('_',$vm);
								$file_value[] = $mat[$mat_id[0]];
							}
						}
						$r['value'] = implode(", ",$file_value);
					}
				}
				$formname = $r['form_name'] ? $r['form_name'] : ($form_name[$r['type']][$r['form_id']] ? $form_name[$r['type']][$r['form_id']] : '组件'.$r['form_id']) ;
				$rec['names'][$r['type'].'_'.$r['form_id']] = $formname;
				$rec['data'][$r['person_id']][$r['type'].'_'.$r['form_id']] = $r['value'];				
			}
			$rec['names']['user_name'] = '用户昵称';
			$rec['names']['create_time'] = '填写时间';
			$rec['names']['process'] = '是否处理';
			$rec['names']['device_token'] = '设备号';
			if(is_array($person) && count($person)>0)
			{
				foreach ($person as $k=>$v)
				{
					$rec['data'][$v['id']]['user_name'] = $v['user_name'] ? $v['user_name'] : '未登录';
					$rec['data'][$v['id']]['create_time'] = $v['create_time'] ? date('Y-m-d H:i:s',$v['create_time']) : '';
					$rec['data'][$v['id']]['process'] = $v['process'] ? '已处理' : '未处理';
					$rec['data'][$v['id']]['device_token'] = $v['device_token'] ? $v['device_token'] : '';
				}
			}
		}
		if(!$rec)
		{
			$this->errorOutput(NO_CONTENT);
		}
		include_once CUR_CONF_PATH.'lib/XmlExcel.php';
		$xls=new XmlExcel();
		$xls->setDefaultWidth(80);
		$xls->setDefaultAlign("center");
		$xls->setDefaultHeight(18);
		$xls->addTitle($rec['title'],$rec['title']);
		$xls->addHead($rec['names'],$rec['title']);
		if(is_array($rec['data']) && count($rec['data'])>0)
		{
			foreach ($rec['data'] as $k=> $value)
			{
				foreach($rec['names'] as $key=>$rs)
				{
					$mac[$k][] = $value[$key];
				}
				$xls->addRow($mac[$k],$rec['title']);
			}
		}
		$xls->export($rec['title']);
		exit();
	}
	
	public function print_result()
	{ 
		$this->verify_content_prms(array('_action'=>'show_result'));
		$feedback_id = $this->input['fid'];
		if(!$feedback_id)
		{
			$this->errorOutput(NO_FEEDBACK_ID);
		}
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."record_person rp  WHERE rp.feedback_id IN(".$feedback_id.")".$condition;
		$re = $this->db->query_first($sql);
		$count = $this->input['num'] ? intval($this->input['num']) : 20;
		$pp = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页
		$offset = intval(($pp - 1)*$count) > 0 ? intval(($pp - 1)*$count) : 0;
		$limit = ' LIMIT '.$offset.','.$count;
		$order = ' ORDER BY rp.order_id desc ,rp.id desc';
		$sql = "SELECT rp.id,rp.user_name,rp.create_time,rp.device_token FROM ".DB_PREFIX."record_person rp  WHERE rp.feedback_id IN(".$feedback_id.")".$condition.$order.$limit;
		$query = $this->db->query($sql);
		while($r = $this->db->fetch_array($query))
		{
			$fid[] = $r['id'];
			$person[$r['id']] = array(
				'user_name'	=> $r['user_name'] ? $r['user_name'] : '未登录',
				'create_time'=> $r['create_time'] ? date('Y-m-d H:i:s',$r['create_time']) : '',
				'process'	=> $r['process'] ? '已处理' : '未处理',
				'device_token' => $r['device_token'],
			);
		}
		$fids = $fid ? implode(',',$fid) : '';
		if($fids)
		{
			$forms = $this->mode->get_forms($feedback_id);
			if($forms && is_array($forms))
			{
				foreach ($forms as $k=>$v)
				{
					$form_name[$v['type'].'_'.$v['id']] = $v['name'];
				}
			}
			$form_ids = array();
			$sql = 'SELECT * FROM ' .DB_PREFIX . 'materials WHERE content_id = ' .$feedback_id;	
			$q = $this->db->query($sql);
			while ($rs = $this->db->fetch_array($q))
			{
				$mat[$rs['id']] = array(
					'host'		=>$rs['host'],
					'dir'		=>$rs['dir'],
					'filepath'	=>$rs['material_path'],
					'filename'	=>$rs['pic_name'],
				);
			}			
			$sql = "SELECT r.*,f.title,f.brief FROM ".DB_PREFIX."record r LEFT JOIN ".DB_PREFIX."feedback f ON r.feedback_id = f.id WHERE r.person_id in( ".$fids .") ORDER BY r.order_id desc";
			$q = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				$rec['title'] = $r['title'];
				$rec['brief'] = $r['brief'];
				if($r['type']=='file')
				{
					$r['type'] = 'standard';
					if($fvalue = trim($r['value'],','))
					{
						$file_value = array();
						$mat_ids = explode(',',$fvalue);
						foreach ($mat_ids as  $k=>$vm)
						{
							if($vm)
							{
								$mat_id = explode('_',$vm);
								$file_value[] = $mat[$mat_id[1]] ? $mat[$mat_id[1]] : $mat[$mat_id[0]];
							}
						}
						$r['value'] = $file_value;
					}
				}
				$formname = $r['form_name'] ? $r['form_name'] : ($form_name[$r['type']][$r['form_id']] ? $form_name[$r['type']][$r['form_id']] : '组件'.$r['form_id']) ;
				$rec['names'][$r['type'].'_'.$r['form_id']] = $formname;
				$person[$r['person_id']][$r['type'].'_'.$r['form_id']] = $r['value'];
			}
			foreach ($fid as $v)
			{
				$rec['data'][] = $person[$v];	
			}
			$rec['names']['user_name'] = '用户';
			$rec['names']['create_time'] = '填写时间';
			$rec['names']['process'] = '状态';
			$rec['names']['device_token'] = '设备号';
		}
		if(!$rec)
		{
			$this->errorOutput(NO_CONTENT);
		}
		$rec['page_info'] = array(
			'current_page' => $pp,
			'page_num' => $count,
			'total_num'=> $re['total'],
			'is_next_page'	=> $pp < ceil($re['total'] / $count) ? 1 : 0,
		);
		$this->addItem($rec);
		$this->output();
	}	
	/**
	 * 以邮件形式发送邀请码给用户
	 */
	public function sendMail()
	{
	    $id = intval($this->input['id']);
	    $email = $this->get_form_value($id);
	    if (!$email) $this->errorOutput(NO_DATA);
	    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	    {
	        $this->errorOutput(EMAIL_ERROR);
	    }
    	$subject = '叮当邀请码';//主题
    	include_once ROOT_DIR . 'lib/class/dingdoneuser.class.php';
    	$dingdoneuser = new dingdoneuser();
    	$codeInfo = $dingdoneuser->createCode();
    	if (!$codeInfo['id'])
    	{
    	    $this->errorOutput(CODE_FAIL);
    	}
    	$code = $codeInfo['code'];//邀请码
    	$content = file_get_contents(CUR_CONF_PATH . 'data/htmlMail/html_mail.html');
    	$search = array('{@$code@}', '{@$date@}');
    	$replace = array($code, date('Y年m月d日'));
    	$content = str_replace($search, $replace, $content);
    	$result = $this->send($email, $subject, $content);
    	if ($result['code'] === 0)
    	{
    	    $dingdoneuser->sendCode($codeInfo['id']);
    	}
        $this->addItem($result);
        $this->output();
	}
	
	/**
	 * 发送邮件
	 * @param string $sendTo   待发送的邮件
	 * @param string $subject  邮件主题
	 * @param string $content  邮件内容
	 */
	private function send($sendTo, $subject, $content)
	{
	    $mail = new PHPMailer();
	    $mail->isSMTP();
	    $mail->Host = 'smtp.qq.com';
	    $mail->SMTPAuth = true;
	    $mail->Username = 'dingdone@hoge.cn';
	    $mail->Password = 'dd@hoge833';
	    $mail->SMTPSecure = 'tls';
	    $mail->Port = 587;
	    $mail->From = 'dingdone@hoge.cn';
	    $mail->FromName = '叮当';
	    $mail->addAddress($sendTo);
	    $mail->isHTML(true);
	    $mail->Subject = $subject;
	    $mail->Body = $content;
	    if (!$mail->send())
	    {
	        $out = array('code' => 1, 'msg' => $mail->ErrorInfo);
	    }
	    else
	    {
	        $out = array('code' => 0, 'msg' => '发送成功');
	    }
	    return $out;
	}
	
	//获取某个组件的值 供叮当邀请码使用
	public function get_form_value($pid)
	{
	    if (!$pid) return false;
	    //表单id
		$fid = 1;
		//组件id
		$form_id = 1;
		//组件类型
		$type = 'standard';
		
		$sql = 'SELECT value FROM ' . DB_PREFIX . 'record 
		WHERE feedback_id = ' . $fid . ' AND person_id = ' . $pid . ' 
		AND form_id = ' . $form_id . ' AND type= "' . $type . '"';
		
		$result = $this->db->query_first($sql);
		
		if ($result && $result['value'])
		{
		    return $result['value'];
		}
		return false;
	}
	
	/**
	 * 获取参与某个表单所有会员id
	 * @return member_id array
	 */
	public function get_feed_members()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput('请输入id');
		}
		$condition = '';
		$condition .= ' AND user_id > 0 ';
		if(!isset($this->input['process']) || $this->input['process'] != -1)
		{
			$process = trim($this->input['process']);
			$processArr = explode(',', $process);
			$pacount = count($processArr);
			if($pacount==1)
			{
				$condition .= ' AND process = \''.(int)$processArr[0].'\'';
			}
			else if ($pacount>1)
			{
				$condition .= 'AND process IN (\'' .implode("','", $processArr ) . '\')';
			}
		}
		$sql = 'SELECT user_id FROM '.DB_PREFIX.'record_person WHERE feedback_id = '.$id . $condition.' group by user_id';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$members['member_id'][] = $r['user_id'];
		}
		if(!$members)
		{
			$this->errorOutput(NO_MEMBERS);
		}
		$this->addItem($members);
		$this->output();
	}
	
	private function get_list_title($id)
	{
		$sql = 'SELECT MIN(id) as id FROM ' .DB_PREFIX.'fixed WHERE fid IN ('.$id.') AND is_name = 1';
		$use_fix_name = $this->db->query_first($sql);
		$sql = 'SELECT MIN(id) as id FROM ' .DB_PREFIX.'standard WHERE fid IN ('.$id.') AND is_name = 1';
		$use_st_name = $this->db->query_first($sql);
		if($use_fix_name['id'])
		{
			$list_name['id'] = $use_fix_name['id'];
			$list_name['type'] = 'fixed';
		}
		elseif($use_st_name['id'])
		{
			$list_name['id'] = $use_st_name['id'];
			$list_name['type'] = 'standard';
		}
		return $list_name;
	}
	
	public function template($forms = array(),$feedback_id)
	{
		
		
		foreach ($forms as $k=>$v)
		{
			if($v['mode_type'] != 'split')
			{
				$ret[] = $v['type'].'_'.$v['id'];
			}
		}
		$ret[] = 'title';
		$ret[] = 'user_name';
		$ret[] = 'create_time';
		$ret[] = 'ip';
		$ret[] = 'device_token';
		$ret[] = 'appname';
		if(file_exists(CORE_DIR.'preview/index.html'))
		{
			$template = file_get_contents(CORE_DIR.'preview/index.html');
			preg_match_all('/<tbody>(.*?)<\/tbody>/s',$template,$match);
			if($match)
			{
				$tp1 = $match[1][0];
				foreach ($ret as $v)
				{
					$tp .= str_replace(array('{key}','{value}'),array($v,$v.'_value'),$tp1);
				}
				$template = str_replace($tp1,$tp,$template);
				if(!is_dir(DATA_DIR.'preview/'))
				{
					hg_mkdir(DATA_DIR.'preview/');
				}
				file_put_contents(DATA_DIR.'preview/'.$feedback_id.'_preview.html', $template);
			}
			return $template;
		}
	}
	
	public function preview()
	{
		$person_id = $this->input['id'];
		$feedback_id = $this->input['fid'];
		if(!$person_id)
		{
			$this->errorOutput(NOID);
		}
		if(!$feedback_id)
		{
			$this->errorOutput('没有输入反馈的id');
		}
		$forms = $this->mode->get_forms($feedback_id,SORT_ASC);
		if(!$forms)
		{
			$this->errorOutput('该反馈没有设置表单！');
		}
		if(!file_exists(DATA_DIR.'preview/'.$feedback_id.'_preview.html'))
		{
			$template = $this->template($forms, $feedback_id);
		}else 
		{
			$template = file_get_contents(DATA_DIR.'preview/'.$feedback_id.'_preview.html');
		}
		$sql = 'SELECT * FROM ' .DB_PREFIX . 'materials WHERE content_id = ' .$feedback_id;	
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$file = array(
					'host'		=>$r['host'],
					'dir'		=>$r['dir'],
					'filepath'	=>$r['material_path'],
					'filename'	=>$r['pic_name'],
				);
			$mat[$r['id']] = $file ? hg_fetchimgurl($file,100) : '';
		}
		$sql = 'SELECT * FROM ' .DB_PREFIX .'record WHERE person_id = ' .$person_id . ' ORDER BY order_id' ;	
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
		    if($r['value'] && $r['type'] == 'file')
			{
				$mat_ids = explode(',',$r['value']);
				foreach ($mat_ids as  $k=>$v)
				{
					if($v)
					{
						$mat_id = @explode('_',$v);
						if($mat_id[1])
						{
							$file_value = $mat[$mat_id[1]] ? '<img src="'.$mat[$mat_id[1]].'" /> ' : '';
						}else 
						{
							$file_value = $mat[$mat_id[0]] ? '<img src="'.$mat[$mat_id[0]].'" /> ' : '';
						}
						
					}
				}
				$r['type'] = 'standard';
				$r['value'] = $file_value;
			}
			$mr[$r['type'].'_'.$r['form_id']]['name'] = $r['form_name'];
			$mr[$r['type'].'_'.$r['form_id']]['value'] = $r['value'];
		}
		foreach ($forms as $v)
		{
			$td = $v['type'].'_'.$v['id'];
			if($v['mode_type'] != 'split')
			{
				$result[$td]['name'] = $v['name'] ?  $v['name'] : '组件'.$v['id'];
				$result[$td]['value'] = $mr[$td] && $mr[$td]['value'] ? $mr[$td]['value'] : '';
			}
		}
		$sql = 'SELECT rp.*,f.title FROM ' .DB_PREFIX . 'record_person rp LEFT JOIN ' .DB_PREFIX .'feedback f ON rp.feedback_id = f.id WHERE rp.id = ' .$person_id  ;	
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$result['title'] = array(
				'name'	=> '表单名',
				'value'	=> $r['title'],
			);
			$result['user_name'] = array(
				'name'	=> '会员名',
				'value'	=> $r['user_name'],
			);
			$result['create_time'] = array(
				'name'	=> '回收时间',
				'value'	=> $r['create_time'] ? date('Y-m-d H:i:s',$r['create_time']) : '',
			);
			$result['ip'] = array(
				'name'	=> '来源IP',
				'value'	=> $r['ip'],
			);
			$result['device_token'] = array(
				'name'	=> '设备号',
				'value'	=> $r['device_token'],
			);
			$result['appname'] = array(
				'name'	=> '来自客户端',
				'value'	=> $r['appname'],
			);
		}
		preg_match_all('/{#(.*?)}/s',$template,$match);
		$keys = array_keys($result);
		if($match)
		{
			$match_all = array_unique($match[1]);
			foreach ($match_all as $k=>$v)
			{
				$find[] = $match[0][$k];
				$find2[] ='{$'.$v.'$}';
				if(in_array($v,$keys))
				{
					$replace[] = $result[$v]['name'];
				}else if(in_array(str_replace('_value','',$v),$keys))
				{
					$replace[] = $result[str_replace('_value','',$v)]['value'];
				}else 
				{
					$replace[] = '';
				}
			}
			$template = str_replace($find2,$replace,$template);
			$html = str_replace($find,$replace,$template);
		}
		$data['preview'] = $html;
		$data['edit'] = $template;
		$this->addItem($data);
		$this->output();
	}
	
	public function edithtml()
	{
		if(!$id = $this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		if(!$this->input['html'])
		{
			$this->errorOutput('模板不能为空');
		}
		file_put_contents(DATA_DIR.'preview/'.$id.'_preview.html', $this->input['html']);
		$this->addItem('success');
		$this->output();
	}
	
	public function delcache()
	{
		if(!$id = $this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		unlink(DATA_DIR.'preview/'.$id.'_preview.html');
		if(!file_exists(DATA_DIR.'preview/'.$id.'_preview.html'))
		{
			$this->addItem('success');
		}else 
		{
			$this->addItem('fail');
		}
		$this->output();
	}
	
	
	public function result_detail()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$sql = 'SELECT title,start_time,end_time,create_time FROM '.DB_PREFIX.'feedback WHERE id = '. $id ;
		$info = $this->db->query_first($sql);
		$info['start_time'] ? $info['start_time'] : $info['create_time'];
		$info['create_time'] = date('Y-m-d H:i:s',$info['create_time']);
		if(!$info)
		{
			$this->errorOutput(NODATA);
		}
		if($info['end_time'] && date('Y-m-d',$info['start_time']) == date('Y-m-d',$info['end_time']))
		{
			$unit = 'Hour';
		}else 
		{
			$unit = 'Today';
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'statistics WHERE id = '. $id ;
		$query = $this->db->query_first($sql);
		if($query && (TIMENOW - $query['update_time'] < RESULT_CACHE_TIME || $info['end_time'] < TIMENOW+RESULT_CACHE_TIME+1) )
		{
			$ret = array(
				'total'		=> intval($query['total']),
				'click'		=> intval($query['click']),
				'new_total'		=> $query['total'] - $query['last_total'],
				'new_click'		=> $query['click'] - $query['last_click'],
				'realtime'		=> $query['realtime'] ? unserialize($query['realtime']) : array(),
			);
		}
		else
		{
			$ct = $this->getTotalClick($id); //获取总点击量
			$st = $this->getTotalSubmit($id); //获取总提交量
			$r = $this->get_clicknum($id,1);//获取实时数据
			$data =array(
				'id'			=> $id,
				'total'			=> $st['total'],
				'click'			=> $ct['total'],
				'realtime'		=> $r ? serialize($r) : '',
				'update_time'	=> TIMENOW,
			);
			if(!$query || date('Y-m-d',$query['update_time']) != date('Y-m-d',TIMENOW))
			{
				$yestoday_e = strtotime(date('Y-m-d 23:59:59',TIMENOW-86400));
				$cl = $this->getTotalClick($id,$yestoday_s,$yestoday_e);//获取昨日总点击量
				$sl = $this->getTotalSubmit($id,$yestoday_s,$yestoday_e); //获取昨日总提交量
				$data['last_total'] = $sl['total'];
				$data['last_click'] = $cl['total'];
			}else if($query)
			{
				$data['last_total'] = $query['last_total'];
				$data['last_click'] = $query['last_click'];
			}
			if(!$query)
			{
				$this->mode->create($data,'statistics',false);
			}else 
			{
				$this->mode->update($id,'statistics',$data);
			}
			$ret = array(
				'total'		=> intval($data['total']),
				'click'		=> intval($data['click']),
				'new_total'		=> $data['total'] - $data['last_total'],
				'new_click'		=> $data['click'] - $data['last_click'],
				'realtime'		=> $r ? $r : array(),
			);
		}
		$ret['title'] = $info['title'];
		$ret['unit'] = $unit;
		$ret['create_time'] = $info['create_time'];
		$ret['id'] = $id;
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_click()
	{
		$type = intval($this->input['type']);
		$unit = trim($this->input['unit']);
		$start_time = $this->input['start_time'];
		$end_time = $this->input['end_time'];
		$id = $this->input['id'];
		$func = $this->input['func']; //浏览量还是提交量
		$func = $func ? $func : 'submit';
		$start_time = $start_time ? strtotime($start_time) : 0;
		$end_time = $end_time ? strtotime($end_time) : TIMENOW;
		$param = array(
			'start_time'	=> $start_time,
			'end_time'		=> $end_time,
			'id'			=> $id,
			'func'			=> $func,
			'type'			=> $type,
			'unit'			=> $unit,
		);
		$condition = md5(json_encode($param));
		//优先从缓存中获取数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'search_cache WHERE search_data = "'. $condition .'" AND cache_time > '. (TIMENOW-RESULT_CACHE_TIME);
		$query = $this->db->query_first($sql);
		
		if(!$query)
		{
			if($func == 'click')
			{
				$ret = $this->get_clicknum($id,$type,$start_time,$end_time,$condition,$unit); //实时数据
			}else{
				$ret = $this->get_submitnum($id,$type,$start_time,$end_time,$condition,$unit); //实时数据
			}
			$data = array(
				'id'			=> $id,
				'search_data' 	=> $condition,
				'data'			=> serialize($ret),
				'cache_time'	=> TIMENOW,
			);
			$this->mode->create($data,'search_cache',0);
		}else
		{
			$ret = $query['data'] ? unserialize($query['data']) : array();
		}
		$this->addItem($ret);
		$this->output();
	}
	
	private function get_clicknum($id,$type = '',$start_time = '',$end_time = '',$condition = '',$unit ='')
	{
		if(intval($type) === 1 || $type == 2 || $unit == 'hour') //查询实时数据
		{
			if(intval($type) === 1 ) 
			{
				$start_time = strtotime(date('Y-m-d 00:00:00',TIMENOW));
				$end_time = TIMENOW;
			}
			$sql = 'SELECT start_time FROM '.DB_PREFIX.'clicks WHERE sid = '. $id .' AND start_time >= '.$start_time .' AND start_time <= '.$end_time;
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$hour = intval(date('H',$r['start_time']));
				$ret[$hour]++;
			}
		}else //查询其他数据
		{
			$group = $unit ? ','.$unit : '';
			$sql = 'SELECT count(*) as total'.$group.' FROM '.DB_PREFIX.'clicks WHERE sid = '. $id ;
			if($start_time)
			{
				$sql .= ' AND start_time >= '.$start_time ;
			}
			if($end_time)
			{
				$sql .= ' AND start_time <= '.$end_time ;
			}
			$sql .= ' GROUP BY '.$unit;
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$ret[$r[$unit]] = intval($r['total']);
			}
		}
		return $ret ? $ret : array();
	}
	
	private function get_submitnum($id,$type = '',$start_time = '',$end_time = '',$condition = '',$unit ='')
	{
		if(intval($type) === 1 || $type == 2 || $unit == 'hour') //查询实时数据
		{
			if(intval($type) === 1 ) 
			{
				$start_time = strtotime(date('Y-m-d 00:00:00',TIMENOW));
				$end_time = TIMENOW;
			}
			$sql = 'SELECT create_time FROM '.DB_PREFIX.'record_person WHERE feedback_id = '. $id .' AND create_time >= '.$start_time .' AND create_time <= '.$end_time;
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$hour = intval(date('H',$r['create_time']));
				$ret[$hour]++;
			}
		}else //查询其他数据
		{
			$group = $unit ? ','.$unit : '';
			$sql = 'SELECT count(*) as total'.$group.' FROM '.DB_PREFIX.'record_person WHERE feedback_id = '. $id ;
			if($start_time)
			{
				$sql .= ' AND start_time >= '.$start_time ;
			}
			if($end_time)
			{
				$sql .= ' AND start_time <= '.$end_time ;
			}
			$sql .= 'GROUP BY '.$unit;
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$ret[$r[$unit]] = intval($r['total']);
			}
		}
		return $ret;
	}
	
	private function getTotalClick($id,$start_time = '',$end_time = '')
	{
		if(!$id)
		{
			return false;
		}
		$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'clicks WHERE sid = '. $id ;
		if($start_time)
		{
			$sql .= ' AND start_time >= '.$start_time ;
		}
		if($end_time)
		{
			$sql .= ' AND start_time <= '.$end_time ;
		}
		$q = $this->db->query_first($sql);
		$ret['total'] = intval($q['total']);
		return $ret;
	}

	private function getTotalSubmit($id,$start_time = '',$end_time = '')
	{
		if(!$id)
		{
			return false;
		}
		$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'record_person WHERE feedback_id = '. $id ;
		if($start_time)
		{
			$sql .= ' AND create_time >= '.$start_time ;
		}
		if($end_time)
		{
			$sql .= ' AND create_time <= '.$end_time ;
		}
		$q = $this->db->query_first($sql);
		$ret['total'] = intval($q['total']);
		return $ret;
	}
}
$out = new feedback_result();
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