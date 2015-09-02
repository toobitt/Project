<?php
define('MOD_UNIQUEID','feedback');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/feedback_mode.php');
include_once(ROOT_DIR . 'lib/class/publishconfig.class.php');
include_once(ROOT_DIR . 'lib/class/material.class.php');
require_once(CUR_CONF_PATH . 'lib/template_mode.php');
class feedback_update extends adminUpdateBase
{
	private $mode;
	private $mPublishColumn;
	private $updateCache;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new feedback_mode();
		$this->mPublishColumn = new publishconfig();
		$this->material = new material();
		$this->updateCache = intval($this->input['update_cache']);
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['node_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'feedback_node WHERE id IN('.$this->input['node_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}		
		$nodes['column_id'] = $this->input['column_id'];
		$nodes['published_column_id'] = '';
		###获取默认数据状态
		$status = $this->get_status_setting('create');
		$nodes['_action'] = 'manage';
		$this->verify_content_prms($nodes);
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if(!trim($this->input['title']))
		{
			$this->errorOutput(NO_TITLE);
		}
		if($this->input['end_time'] && strtotime($this->input['start_time'])>strtotime($this->input['end_time']))
		{
			$this->errorOutput('开始时间不能大于结束时间');
		}
		$standard = $this->input['standard'];
		$fixed = $this->input['fixed'];
		$is_name = array();
		if(is_array($standard) && $standard)
		{
			foreach ($standard as $ks=> $vs )
			{
				$sis_name = $vs['is_name'] ? explode('@',$vs['is_name']) : array();
				$is_name[] = array_sum($sis_name);
			}
		}
		if(is_array($fixed) && $fixed)
		{
			foreach ($fixed as $kf=> $vf )
			{
				$fis_name = $vf['is_name'] ? explode('@',$vf['is_name']) : array();
				$is_name[] = array_sum($fis_name);		
			}
		}
		if(!(@array_sum($is_name) || intval($this->input['is_login']))) // 如果既不需要登录而且没有设置回收表单名
		{
			$this->errorOutput("不需要登录时，必须勾选一个组件‘将答案设置为表单回收名称’!");
		}
		if(!$this->settings['App_verifycode'] && intval($this->input['is_verifycode']))
		{
			$this->errorOutput('验证码应用未安装！');
		}
		$data = array(
			'title'        => trim($this->input['title']),
		    'brief'        => trim($this->input['feedback_brief']),
		    'is_login'     => intval($this->input['is_login']),
			'userid_limit_time'	=> (float)$this->input['userid_limit_time'],
			'userid_limit_num'	=> intval($this->input['userid_limit_num']),
			'is_ip'    		=> intval($this->input['is_ip']),
			'ip_limit_time'	=> (float)$this->input['ip_limit_time'],
			'ip_limit_num'	=> intval($this->input['ip_limit_num']),
			'is_device'     => intval($this->input['is_device']),
			'device_limit_time'	=> (float)$this->input['device_limit_time'],
			'device_limit_num'	=> intval($this->input['device_limit_num']),
		    'is_verifycode'     => intval($this->input['is_verifycode']),
		    'verifycode_type'     => intval($this->input['is_verifycode']) ? intval($this->input['verifycode_type']) : 0,
		    'is_credit'    => intval($this->input['is_credit']),
		    'credit1'   	   => intval($this->input['is_credit']) ? intval($this->input['credit1']) : 0,
		    'credit2'   	   => intval($this->input['is_credit']) ? intval($this->input['credit2']) : 0,
			'remark'   	   => trim($this->input['remark']),
			'start_time'   => strtotime($this->input['start_time']),
			'end_time'     => strtotime($this->input['end_time']),
			'page_title'   => trim($this->input['page_title']),
			'jump_to'	   => trim($this->input['jump_to']),
			'node_id'      => intval($this->input['sort_id']),
		    'status'       => $status,
		    'org_id'       => $this->user['org_id'],
		    'user_id'      => $this->user['user_id'],
		    'user_name'    => $this->user['user_name'],
		    'create_time'  => TIMENOW,
		    'update_user_id'  => $this->user['user_id'],
		    'update_user_name'=> $this->user['user_name'],
		    'update_time'  => TIMENOW,
		    'ip'           => hg_getip(),
		    'appid'        => $this->user['appid'],
		    'appname'      => $this->user['display_name'],
			'admin_user'	=> $this->input['admin_user'],
			'style'			=> trim($this->input['style']),
			'template'		=> trim($this->input['template']),
			'submit_text'		=> trim($this->input['submit_text']),
			'template_id'		=> $this->input['template_id'] ? $this->input['template_id'] : 1,
		);	
		$column_id = $this->input['column_id'];
		$data['column_id'] = $this->mPublishColumn->get_columnname_by_ids('id,name',$column_id);
		$data['column_id'] = $data['column_id'] ? @serialize($data['column_id']) : '';	
		$vid = $this->mode->create($data,'feedback');
		
		if($_FILES['indexpic'])
		{
			$files['Filedata'] = $_FILES['indexpic'];
			$material = $this->material->addMaterial($files,$vid);
			$indexpic = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
		}
		$updata['indexpic'] = $indexpic ? serialize($indexpic) : '';
		$this->mode->update($vid, 'feedback',$updata);
		$orders = rtrim($this->input['order'],',');
		$order_id = explode(',',$orders);
		foreach ($order_id as $k=>$v)
		{
			$order[$v][] = $k;
		}
		if(is_array($standard) && $standard)
		{
			foreach ($standard as $k=> $v )
			{
				//初始化
			    $sis_unique = $sname = $sbrief = $swidth = $soption = $sheight = $schar_num = $sop_num = $scor = $slimit_type = $sis_require = $sis_name = $sspilter= $sis_member = $smember_field  = array();
				foreach ($v as $sk=>$sv)
				{
					$ssv = 's'.$sk;
					$$ssv = $this->exp($sv);
				}
				if($k==6)
				{
					$v['name'] = $v['spilter'];
					$sname = $sspilter;
				}
				if($v['name'])
				{
				foreach ($sname as $kk=>$vv)
				{
					if($soption[$kk])
					{
						$soption[$kk] = explode(',',$soption[$kk]);
					}
					if($k == 3 or $k == 4) //选择题
					{
						if($k == 3)
						{
							$scor[$kk] = $scor[$kk] ? $scor[$kk] : 1; //默认为单选
						}
						if($sop_num[$kk] < 0)
						{
							$sop_num[$kk] = 1;
						}
						if($sop_num[$kk] > count(array_filter($soption[$kk])))
						{
							$sop_num[$kk] = count(array_filter($soption[$kk]));
						}
					}
					if($k == 6)//分割线
					{
						$sspilter[$kk] = $sspilter[$kk] ? $sspilter[$kk] : 1;
					}
					if($k !=1 && $k !=2 )//除单行文本和多行文本，其他均不设置为回收表单名称
					{
						$sis_name[$kk] = 0;
					}
					$s_data = array();
					$s_data = array(
						'fid'      => $vid,  //反馈的id
						'name'     => ($k == 6 ) ? '分隔符' : $sname[$kk],
						'brief'    => trim($sbrief[$kk]),
						'form_type'=> $k,
						'width'    => $swidth[$kk],
						'height'   => $sheight[$kk],
						'char_num' => intval($schar_num[$kk]),
						'options'  => $soption[$kk] ? serialize(array_filter($soption[$kk])):'',
						'op_num'   => intval($sop_num[$kk]),
						'cor'      => intval($scor[$kk]),
						'limit_type'=> intval($slimit_type[$kk]),
						'is_required' => intval($sis_require[$kk]),
						'is_member'  => $sis_member[$kk] ? 1 : 0,
						'member_field'  => $sis_member[$kk] ? trim($smember_field[$kk]) : '',
						'is_name'  => $sis_name[$kk],
						'is_unique'    => intval($sis_unique[$kk]) ? 1 :0,
						'order_id' => $order['standard_'.$k][$kk],
						'spilter'  => $sspilter[$kk],
					);
					$standard_data[] = $s_data;
					if($sis_common[$kk])
					{
						$common_conf = array_slice($s_data,3,12);
						$common_data[] = array(
						'name'     => $vv,
						'brief'    => trim($sbrief[$kk]),
						'type'     => 'standard',
						'configs'  => $common_conf ? serialize($common_conf) : '',
						);
					}
				}
				}
			}
		}
		if(is_array($fixed) && $fixed)
		{
			foreach ($fixed as $fk=> $fv )
			{
				//初始化
				$fname = $fbrief = $fwidth = $fheight = $fchar_num = $fstart_time = $fend_time = array();
				$fis_unique = $fhour = $fmin = $fsecond = $fprovince = $fcity = $fcounty = $fdetail = $fis_require = $fis_name = $fis_member = $fmember_field =  array();
				foreach ($fv as $fkk=>$fvv)
				{
					$fsv = 'f'.$fkk;
					$$fsv = $this->exp($fvv);
				}
				if($k==6)
				{
					$v['name'] = $v['spilter'];
					$sname = $sspilter;
				}
				if($fv)
				{
				foreach ($fname as $kk=>$vv)
				{
					switch ($fk)
					{
						case 4:
							$ffixed_conf_ids[$kk] = array();
							$ffixed_conf_ids[$kk] = array_filter(array($fprovince[$kk],$fcity[$kk],$fcounty[$kk],$fdetail[$kk]));
							$conf[$kk] = implode(',',$ffixed_conf_ids[$kk]);
							break;						
						case 6:
							$ffixed_conf_ids[$kk] = array();
							$ffixed_conf_ids[$kk] = array($fhour[$kk],$fmin[$kk],$fsecond[$kk]);
							$conf[$kk] = implode(',',array_filter($ffixed_conf_ids[$kk]));
							break;					
						case 2:case 3:case 1:case 5:
							$fixed_conf[$kk] = array();
							$fixed_conf[$kk] = array(
							    'width' => $fwidth[$kk] ? $fwidth[$kk] : 450, 
							    'height' => $fheight[$kk]? $fheight[$kk] : 30,
							    'char_num' => intval($fchar_num[$kk]) ? intval($fchar_num[$kk]) : 20,
							    'start_time' => $fstart_time[$kk]>0 ? intval($fstart_time[$kk]) : 0,
							    'end_time' => $fend_time[$kk]>0 ? intval($fend_time[$kk]) : 0,
							);
							$conf[$kk] = $fixed_conf[$kk] ? serialize(array_filter($fixed_conf[$kk])) : '';
							break;
						default:
							break;
					}
					$f_data = array();
					$f_data = array(
						'fid'      => $vid,  //反馈的id
						'name'     => $vv,
						'brief'    => trim($fbrief[$kk]),
						'fixed_id' => $fk,
						'conf'     => $conf[$kk],
						'is_required' => intval($fis_require[$kk]),
						'order_id' => $order['fixed_'.$fk][$kk],
						'is_name'  => intval($fis_name[$kk]),
						'is_member'  => $fis_member[$kk] ? 1 : 0,
						'member_field'  => $fis_member[$kk] ? trim($fmember_field[$kk]) : '',
						'is_unique'    => intval($fis_unique[$kk]) ? 1 :0,
					);
					$fixed_data[] = $f_data;
					if($fis_common[$kk])
					{
						$common_conf = array_slice($f_data,3);
						$common_conf['form_type'] = $fk;
						$common_data[] = array(
						'name'     => $vv,
						'brief'    => trim($fbrief[$kk]),
						'type'     => 'fixed',
						'configs'     => $common_conf ? serialize($common_conf) : '',
						);
					}
				}
				}
			}
		}
		
		$data['forms'] = array();
		if($standard_data)
		{
			$standard_data = $this->mode->insert_datas('standard', $standard_data);
			$data['forms'] = array_merge($data['forms'], $standard_data);
		}
		if($fixed_data)
		{
			$fixed_data = $this->mode->insert_datas('fixed', $fixed_data);
			$data['forms'] = array_merge($data['forms'], $fixed_data);
		}
		if($common_data)
		{
			$common_data = $this->mode->insert_datas('common', $common_data);
		}
		if ($vid)
		{
			$data['id'] = $vid;
			//放入发布队列
			if(intval($data['status']) == 1  && !empty($column_id))
			{
				$op = 'insert';
				publish_insert_query($data, $op, $data['user_name']);
			}
		}
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建反馈表单',$data,'','创建'.$data['title'] . $vid);
			$this->addItem($data);
			$this->output();
		}
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		if(!trim($this->input['title']))
		{
			$this->errorOutput(NO_TITLE);
		}
		if($this->input['end_time'] && strtotime($this->input['start_time'])>strtotime($this->input['end_time']))
		{
			$this->errorOutput('开始时间不能大于结束时间');
		}
		$standard = $this->input['standard'];
		$fixed = $this->input['fixed'];
		$is_name = array();
		if(is_array($standard) && $standard)
		{
			foreach ($standard as $ks=> $vs )
			{
				$sis_name = $vs['is_name'] ? explode('@',$vs['is_name']) : array();
				$is_name[] = array_sum($sis_name);
			}
		}
		if(is_array($fixed) && $fixed)
		{
			foreach ($fixed as $kf=> $vf )
			{
				$fis_name = $vf['is_name'] ? explode('@',$vf['is_name']) : array();
				$is_name[] = array_sum($fis_name);		
			}
		}
		if(!(@array_sum($is_name) || intval($this->input['is_login']))) // 如果既不需要登录而且没有设置回收表单名
		{
			$this->errorOutput("不需要登录时，必须勾选一个组件‘将答案设置为表单回收名称’!");
		}
		if(!$this->settings['App_verifycode'] && intval($this->input['is_verifycode']))
		{
			$this->errorOutput('验证码应用未安装！');
		}
		$_feedback = $this->mode->get_feedback('id = '.$id, '*'); //获取问卷信息的初始数据
		$status = $_feedback['status'];
		
		#####节点权限检测数据收集
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$_node_ids = '';
			if($_feedback['node_id'])
			{
				$_node_ids = $_feedback['node_id'];
			}
			if($this->input['node_id'])
			{
				$_node_ids  = $_node_ids ? $_node_ids . ',' . $this->input['node_id'] : $this->input['node_id'];
			}
			if($_node_ids)
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'feedback_node WHERE id IN('.$_node_ids.')';
				$query = $this->db->query($sql);
				$nodes = array();
				while($row = $this->db->fetch_array($query))
				{
					$nodes['nodes'][$row['id']] = $row['parents'];
				}
			}
		}
		#####节点权限
		$nodes['id'] 		= $id;
		$nodes['user_id'] 	= $_feedback['user_id'];
		$nodes['org_id'] 	= $_feedback['org_id'];
		$nodes['column_id'] = $this->input['column_id'];
		
		$nodes['published_column_id'] = '';
	//	$nodes['weight'] = $vote['weight'];
		###获取默认数据状态
		if(!empty($_feedback['column_id']))
		{
			$status = $this->get_status_setting('update_publish', $_feedback['status']);
		}
		else 
		{			
			if(intval($_feedback['status']) == 1)
			{
				$status = $this->get_status_setting('update_audit', $_feedback['status']);
			}
		}
		
		$ori_column_id = array();
		$_feedback['column_id'] = unserialize($_feedback['column_id']);
		if(is_array($_feedback['column_id']))
		{
			$ori_column_id = array_keys($_feedback['column_id']);
			$nodes['published_column_id'] = implode(',', $ori_column_id);
		}
		$nodes['_action'] = 'manage';
		######获取默认数据状态
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $_feedback['admin_user'])
		{
			$admin_user = array();
			$admin_user = explode(',',$_feedback['admin_user']);
			if(!in_array($this->user['user_name'],$admin_user))
			{
				$this->errorOutput("对不起，您没有此表单的权限");
			}
		}
		if($this->user['group_type'] > MAX_ADMIN_TYPE && !$_feedback['admin_user'])
		{
			$this->verify_content_prms($nodes);
		}
		########权限#########
		$update_data = array(
			'title'        => trim($this->input['title']),
		    'brief'        => trim($this->input['feedback_brief']),
		    'node_id'      => intval($this->input['sort_id']),
		    'status'       => $status,
		    'is_login'     => intval($this->input['is_login']),
			'userid_limit_time'	=> (float)$this->input['userid_limit_time'],
			'userid_limit_num'	=> intval($this->input['userid_limit_num']),
			'is_ip'    		=> intval($this->input['is_ip']),
			'ip_limit_time'	=> (float)$this->input['ip_limit_time'],
			'ip_limit_num'	=> intval($this->input['ip_limit_num']),
			'is_device'     => intval($this->input['is_device']),
			'device_limit_time'	=> (float)$this->input['device_limit_time'],
			'device_limit_num'	=> intval($this->input['device_limit_num']),
		    'is_verifycode' => intval($this->input['is_verifycode']),
		    'verifycode_type' => intval($this->input['is_verifycode']) ? intval($this->input['verifycode_type']) : 0,
		    'is_credit'    => intval($this->input['is_credit']),
		    'credit1'   	   => intval($this->input['is_credit']) ? intval($this->input['credit1']) : 0,
		    'credit2'   	   => intval($this->input['is_credit']) ? intval($this->input['credit2']) : 0,
			'remark'   	   => trim($this->input['remark']),
			'start_time'   => strtotime($this->input['start_time']),
			'end_time'     => strtotime($this->input['end_time']),
			'page_title'   => trim($this->input['page_title']),
			'jump_to'	   => trim($this->input['jump_to']),
			'pub_time'     => strtotime($this->input['pub_time']),
			'admin_user'	=> $this->input['admin_user'],
			'style'			=> trim($this->input['style']),
			'template'		=> trim($this->input['template']),
			'submit_text'		=> trim($this->input['submit_text']),
			'template_id'		=> $this->input['template_id'] ? $this->input['template_id'] : 1,
		);
		if($_FILES['indexpic'])
		{
			$files['Filedata'] = $_FILES['indexpic'];
			$material = $this->material->addMaterial($files,$vid);
			$indexpic = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
			$update_data['indexpic'] = $indexpic ? serialize($indexpic) : '';
		}
		$column_id		   = $this->input['column_id'];
		$update_data['column_id'] = $this->mPublishColumn->get_columnname_by_ids('id,name', $column_id);
		$update_data['column_id'] = $update_data['column_id'] ? serialize($update_data['column_id']) : '';
		$update_data = $this->mode->update($id,'feedback',$update_data);
		$update_data['id'] = $id;
		if($update_data['affected_rows'])
		{
			$affect_rows = 1;
		}
		$is_name = array();
		
		/*********************输入数据处理*********************/
		
		$standard_ids = array();
		$fixed_ids = array();
		$orders = rtrim($this->input['order'],',');
		$order_id = explode(',',$orders);
		foreach ($order_id as $k=>$v)
		{
			$order[$v][] = $k;
		}
		
		if(is_array($standard) && $standard)
		{
			foreach ($standard as $k=> $v )
			{
				//初始化
			    $sis_unique = $sname = $sbrief = $swidth = $soption = $sheight = $schar_num = $sop_num = $scor = $slimit_type = $sis_require = $sis_member = $smember_field = $sis_name = $sspilter = array();
				foreach ($v as $sk=>$sv)
				{
					$ssv = 's'.$sk;
					$$ssv = $this->exp($sv);
				}
				$sform_id = $sid;
				if($k==6)
				{
					$v['name'] = $v['spilter'];
					$sname = $sspilter;
				}
				
				if($v['name'])
				{
					foreach ($sname as $kk=>$vv)
				{
					$type[] = 'standard';
					$standard_ids[] = $sform_id[$kk];
					if($soption[$kk])
					{
						$soption[$kk] = explode(',',$soption[$kk]);
					}
					if($k == 3 or $k == 4) //选择题
					{
						if($k == 3)
						{
							$scor[$kk] = $scor[$kk] ? $scor[$kk] : 1; //默认为单选
						}
						$slimit_type[$kk] = $scor[$kk] == 2 ? $slimit_type[$kk] : 2 ; //默认单选只选一个
						$sop_num[$kk] = $scor[$kk] == 2 ? $sop_num[$kk] : 1 ;
						if($sop_num[$kk] < 0)
						{
							$sop_num[$kk] = 1;
						}
						if($sop_num[$kk] > count(array_filter($soption[$kk])))
						{
							$sop_num[$kk] = count(array_filter($soption[$kk]));
						}
					}
					if($k == 6)//分割线
					{
						$sspilter[$kk] = $sspilter[$kk] ? $sspilter[$kk] : 1;
					}
					if($k !=1 && $k !=2)//除单行文本和多行文本，其他均不设置为回收表单名称
					{
						$sis_name[$kk] = 0;
					}
					$standard_data = array(
						'fid'      => $id,  //反馈的id
						'name'     => $k==6?'分隔符':$sname[$kk],
						'brief'    => trim($sbrief[$kk]),
						'form_type'=> $k,
						'width'    => $swidth[$kk],
						'height'   => trim($sheight[$kk]),
						'char_num' => intval($schar_num[$kk]),
						'options'  => $soption[$kk] ? serialize(array_filter($soption[$kk])):'',
						'op_num'   => $sop_num[$kk],
						'cor'      => $scor[$kk],
						'limit_type'=> $slimit_type[$kk],
						'is_required' => intval($sis_require[$kk]),
						'is_name'  => $sis_name[$kk],
						'is_member'  => $sis_member[$kk] ? 1 : 0,
						'member_field'  => $sis_member[$kk] ? trim($smember_field[$kk]) : '',
						'order_id' => $order['standard_'.$k][$kk],
						'spilter'  => $sspilter[$kk],
						'is_unique'    => intval($sis_unique[$kk]) ? 1 :0,
					);	
					
					if(intval($sform_id[$kk]))
					{
						$up_standard_data = $this->mode->update($sform_id[$kk], 'standard' , $standard_data);//更新标准组建
						if($up_standard_data['affected_rows'])
						{
							$affect_rows = 1;
						}
					}
					else
					{
						$in_standard_data[] = $standard_data;
					}
					//配置到常用组件中
					if($sis_common[$kk])
					{
						$common_conf = array_slice($standard_data,3,12);
						$common_data[] = array(
						'name'     => $vv,
						'brief'    => trim($sbrief[$kk]),
						'type'     => 'standard',
						'configs'  => $common_conf ? serialize($common_conf) : '',
						);
					}
				}
				}
			}
		}
		if(is_array($fixed) && $fixed)
		{
			foreach ($fixed as $fk=> $fv )
			{
				//初始化
				$fname = $fbrief = $fwidth = $fheight = $fchar_num = $fstart_time = $fend_time = array();
				$fis_unique = $fhour = $fmin = $fsecond = $fprovince = $fcity = $fcounty = $fdetail = $fis_require = $fis_name = $fis_member = $fmember_field = array();
				foreach ($fv as $fkk=>$fvv)
				{
					$fsv = 'f'.$fkk;
					$$fsv = $this->exp($fvv);
				}
				$fform_id = $fid;
				
				if($fv['name'])
				{
				foreach ($fname as $kk=>$vv)
				{
					$fixed_ids[] = $fform_id[$kk];
					switch ($fk)
					{
						case 4:
							$ffixed_conf_ids[$kk] = array();
							$ffixed_conf_ids[$kk] = array($fprovince[$kk],$fcity[$kk],$fcounty[$kk],$fdetail[$kk]);
							$conf[$kk] = implode(',',array_filter($ffixed_conf_ids[$kk]));							
							break;
							case 6:
							$ffixed_conf_ids[$kk] = array();
							$ffixed_conf_ids[$kk] = array($fhour[$kk],$fmin[$kk],$fsecond[$kk]);
							$conf[$kk] = implode(',',array_filter($ffixed_conf_ids[$kk]));
							break;					
						case 2:case 3:case 1:case 5:
							$fixed_conf[$kk] = array(
							    'width' => $fwidth[$kk] ? $fwidth[$kk] : 450, 
							    'height' => $fheight[$kk] ? $fheight[$kk] : 30,
							    'char_num' => intval($fchar_num[$kk]) ? intval($fchar_num[$kk]) : 30,
							    'start_time' => $fstart_time[$kk]>0 ? intval($fstart_time[$kk]) : 0,
							    'end_time' => $fend_time[$kk]>0 ? intval($fend_time[$kk]) : 0,
								);
							$conf[$kk] = $fixed_conf[$kk] ? serialize(array_filter($fixed_conf[$kk])) : '';
							break;
						default:
							break;
					}
					$fixed_data = array();
					$fixed_data = array(
						'fid'      => $id,  //反馈的id
						'name'     => $vv,
						'brief'    => trim($fbrief[$kk]),
						'fixed_id' => $fk,
						'conf'     => trim($conf[$kk]),
						'is_required' => intval($fis_require[$kk]),
						'is_name'  => intval($fis_name[$kk]),
						'is_member'  => $fis_member[$kk] ? 1 : 0,
						'member_field'  => $fis_member[$kk] ? trim($fmember_field[$kk]) : '',
						'is_unique'    => intval($fis_unique[$kk]) ? 1 :0,
						'order_id' => $order['fixed_'.$fk][$kk],
					);
					
					if(intval($fform_id[$kk]))
					{
						$up_fixed_data = $this->mode->update($fform_id[$kk], 'fixed' , $fixed_data);//更新标准组建
						if($up_fixed_data['affected_rows'])
						{
							$affect_rows = 1;
						}
					}
					else
					{
						$in_fixed_data[] = $fixed_data;
					}
					//配置到常用组件中
					if($fis_common[$kk])
					{
						$common_conf = array_slice($fixed_data,3,6);
						$common_conf['form_type'] = $fk;
						$common_data[] = array(
						'name'     => $vv,
						'brief'    => trim($fbrief[$kk]),
						'type'     => 'fixed',
						'configs'  => $common_conf ? serialize($common_conf) : '',
						);
					}
				}
				}
			}
		}
		/*********************输入数据处理*********************/

		//表单的更新 包括表单新增 修改 删除；
		$_forms = $this->mode->get_forms($id);
		//原反馈表单id
		$_standard_ids = array();
		$_fixed_ids = array();
		if(is_array($_forms) && count($_forms)>0)
		{
			foreach ($_forms as $v)
			{
				if($v['type'] == 'standard')
				{
					$_standard_ids[] = $v['id'];
				}
				if($v['type'] == 'fixed')
				{
					$_fixed_ids[] = $v['id'];
				}
				$_name[] = $v['name'];
			}
		}		
		$st_diff = array_diff($_standard_ids,$standard_ids);//比较标准组件，要删除的组件id 
		$fx_diff = array_diff($_fixed_ids,$fixed_ids);//比较固定组件，要删除的组件id 
								
		if(count($st_diff)>0)
		{
			$delete_stan_ids = implode(',',$st_diff);
		}
		if(count($fx_diff)>0)
		{
			$delete_fix_ids = implode(',',$fx_diff);
		}
		$data['forms'] = array();//新增组建
		if($in_standard_data)
		{
			$standard_data = $this->mode->insert_datas('standard', $in_standard_data);
			if($standard_data)
			{
				$affect_rows = 1;
			}
		}
		if($in_fixed_data)
		{
			$fixed_data = $this->mode->insert_datas('fixed', $in_fixed_data);
			if($fixed_data)
			{
				$affect_rows = 1;
			}
		}
		/***
		 * 添加到常用组件
		 */
		if($common_data)
		{
			$common_data = $this->mode->insert_datas('common', $common_data);
		}
		
		
		if($delete_stan_ids)//删除组建
		{
			$sql = "DELETE FROM " . DB_PREFIX."standard WHERE id in(".$delete_stan_ids.")";
			$this->db->query($sql);
			$affect_rows = 1;
		}
		if($delete_fix_ids)
		{
			$sql = "DELETE FROM " . DB_PREFIX."fixed WHERE id in(".$delete_fix_ids.")";
			$this->db->query($sql);
			$affect_rows = 1;
		}
		if($affect_rows)
		{
			$update_user = array(
			    'update_user_id'    => $this->user['user_id'],
			    'update_user_name'  => $this->user['user_name'],
			    'update_time'       => TIMENOW,
				'reupdate'			=> 1,
			);
			$update_user = $this->mode->update($id, 'feedback',$update_user);
			$update_data['update_user_id'] = $update_user['update_user_id'];
			$update_data['update_user_name'] = $update_user['update_user_name'];
			$update_data['update_time'] = $update_user['update_time'];
		}
		unset($update_data['affected_rows']);
		$update_data['sort_id'] = $update_data['node_id'];
		//发布系统
		$ret_feedback = $this->mode->get_feedback(" id = {$id}", 'column_id,status,expand_id');
		//更新的栏目
		$ret_feedback['column_id'] = unserialize($ret_feedback['column_id']);
		$new_column_id = array();
		if(is_array($ret_feedback['column_id']))
		{
			$new_column_id = array_keys($ret_feedback['column_id']);
		}
        //$data['id'] = $id;
		if($status == 1)
		{
			if(!empty($ret_feedback['expand_id']))   //已经发布过，对比修改先后栏目
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					publish_insert_query($update_data, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					publish_insert_query($update_data, 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					publish_insert_query($update_data, 'update',$same_column);
					//有新插入素材时需插入子队列
					//publish_insert_query($update_data, 'insert',$same_column,1);
				}			
			}
			else 							//未发布，直接插入
			{
				$op = "insert";
				publish_insert_query($update_data, $op);
			}				
		}
		else    //打回
		{
			if(!empty($ret_feedback['expand_id']))
			{
				$op = "delete";
				publish_insert_query($update_data,$op);
			}
		}
		$new_data = $this->mode->detail($id);
		
		if($id)
		{
			$this->addLogs('更新反馈表单',$_feedback,$new_data,'更新' .$new_data['title']. $this->input['id']);
			$this->addItem($new_data);
			$this->output();
		}
	}
	
	public function delete()
	{
		$nodes = $node_id = array();
		$sql = 'SELECT * FROM '.DB_PREFIX.'feedback WHERE id IN ('.$this->input['id'].')';
		$q = $this->db->query($sql);
		while($r=$this->db->fetch_array($q))
		{
			$node_id[] = $r['node_id'];
			$nodes[] = array(
				'title' 		=> $r['name'],
				'delete_people' => $this->user['user_name'],
				'cid' 			=> $r['id'],
				'catid' 		=> $r['node_id'],
				'user_id'		=> $r['user_id'],
				'org_id'		=> $r['org_id'],
				'id'			=> $r['id'],
				'admin_user'	=> $r['admin_user'],
			);
		}
		if($node_id)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'feedback_node WHERE id IN('.implode(',',$node_id).')';
			$query = $this->db->query($sql);
			$node_ids = array();
			while($row = $this->db->fetch_array($query))
			{
				$node_ids[$row['id']] = $row['parents'];
			}
		}
		if(!empty($nodes))
		{
			foreach ($nodes AS $node)
			{
				$admin_user = array();
				if($node['catid'])
				{
					$node['nodes'][$node['catid']] = $node_ids[$node['catid']];
				}
				$admin_user = explode(',',$node['admin_user']);
				if($this->user['group_type'] > MAX_ADMIN_TYPE && $node['admin_user'])
				{
					if(!in_array($this->user['user_name'],$admin_user))
					{
						$this->errorOutput('对不起，您没有权限');
					}
				}
				elseif($this->user['group_type'] > MAX_ADMIN_TYPE && !$node['admin_user'])
				{
					$node['_action'] = 'manage';
					$this->verify_content_prms($node);
				}
			}
		}
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$id = $this->input['id'];
		$sql = "SELECT * FROM " . DB_PREFIX . "feedback WHERE id IN(" . $id .")";
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$column_id = @unserialize($row['column_id']);
			if(intval($row['status']) == 1 && ($row['expand_id'] || $column_id))
			{
				$op = "delete";
				publish_insert_query($row,$op);
			}
			$dirs[] = $row['create_time'].$row['id'];
		}
		$ret = $this->mode->delete($id);
		if($dirs && is_array($dirs))
		{
			foreach ($dirs as $k=>$dir)
			{
				if(is_dir(CUR_CONF_PATH . 'data/'.$dir))
				{
					deldir(CUR_CONF_PATH . 'data/'.$dir);
				}
			}
		}
		if($ret)
		{
			$this->addLogs('删除反馈表单',$ret,'','删除反馈表单' . $this->input['id']); 
			$this->addItem($id);
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['node_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'feedback_node WHERE id IN('.$this->input['node_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}		
		$nodes['column_id'] = $this->input['column_id'];
		$nodes['published_column_id'] = '';
		###获取默认数据状态
		
		$id = trim($this->input['id']);
		$sql = 'SELECT * FROM '.DB_PREFIX.'feedback WHERE id IN ('.$id.')';
		$q = $this->db->query($sql);
		$admin_user = array();
		while($r=$this->db->fetch_array($q))
		{
			$_status[$r['id']] = $r['status'];
			if($this->user['group_type'] > MAX_ADMIN_TYPE && $r['admin_user'])
			{
				$admin_user = explode(',',$r['admin_user']);
				if(!in_array($this->user['user_name'],$admin_user))
				{
					$this->errorOutput("对不起，您没有此权限");
				}
			}
			elseif($this->user['group_type'] > MAX_ADMIN_TYPE && !$r['admin_user'])
			{
				$this->verify_content_prms($nodes);
			}
		}
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		$audit = intval($this->input['audit']);
		$ret = $this->mode->audit($id,$audit);
		
	    if($audit == 1)
	    {
	    	$ret_feedback = $this->mode->get_feedback_list(" id IN({$id})");
			if(is_array($ret_feedback) && count($ret_feedback) > 0 )
			{
				foreach($ret_feedback as $info)
				{
					if(!empty($info['expand_id']))
					{
						$op = "update";
					}
					else
					{
						if(@unserialize($info['column_id']))
						{
							$op = "insert";
						}
					}
					publish_insert_query($info, $op);
				}
			}
	    }
	    elseif($audit == 0)
	    {
	    	$ret_feedback = $this->mode->get_feedback_list(" id IN({$id})");
			if(is_array($ret_feedback) && count($ret_feedback) > 0 )
			{
				foreach($ret_feedback as $info)
				{
					$info['column_id'] = @unserialize($info['column_id']);
					if(!empty($info['expand_id']) || $info['column_id'])
					{
						$op= "delete";  		//expand_id不为空说明已经发布过，打回操作时应重新发布，发布时执行delete操作
					}
					else
					{
						$op = "";
					}
					publish_insert_query($info, $op);
				}
			}
	    	
	    }
		if($_status)
		{
			foreach ($_status as $k=>$v)
			{
				if($k && !$v)
				{
					$curl = new curl($this->settings['App_feedback']['host'],$this->settings['App_feedback']['dir']);
					$curl->setSubmitType('post');
					$curl->setReturnFormat('json');
					$curl->initPostData();
					$curl->addRequestData('id', $k);
					$curl->addRequestData('a', 'sc');
					$message = $curl->request('admin/feedback_update.php');
					$message = $message[0];
					$ret['state'] = $message['state'];
					$ret['url'] = $message['url'];
				}
			}
		}
		if($ret)
		{
			$this->addLogs('审核反馈表单','',$ret,'审核反馈表单' . $this->input['id']); 
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sort()
	{
		$table_name = $this->input['type'] ? $this->input['type'] : 'feedback';
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->drag_order($table_name, 'order_id');
	
		$this->addItem($ret);
		$this->output();
	}
	
	public function publish()
	{
	 	$id = urldecode($this->input['id']);
	 	if(!$id)
	 	{
	 		$this->errorOutput('No Id');
	 	}
	 	$pub_time = $this->input['pub_time'] ? strtotime($this->input['pub_time']) : TIMENOW;
	 	$column_id = urldecode($this->input['column_id']);
	 	$isbatch = strpos($id, ',');
	 	if($isbatch !== false && !$column_id)
	 	{
	 		$this->addItem(true);
	 		$this->output();
	 	} 
	 	include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
	 	$this->publish_column = new publishconfig();
	 	$column_id = $this->publish_column->get_columnname_by_ids('id,name,parents',$column_id);
	 	$sql = "SELECT * FROM " . DB_PREFIX ."feedback WHERE id IN( " . $id . ")";
	 	$q = $this->db->query($sql);
	 	while($row = $this->db->fetch_array($q))
	 	{
	 		$row['column_id'] = unserialize($row['column_id']);

	 		$ori_column_id = array();
	 		if(is_array($row['column_id']))
	 		{
	 			$ori_column_id = array_keys($row['column_id']);
	 		}
	 		$ori_column_id_str = $ori_column_id ? implode(',', $ori_column_id) : '';
	 		if($isbatch !== false)     //批量发布只能新增，so需要合并已经发布的栏目
	 		{
	 			$row['column_id'] = is_array($row['column_id']) ? ($row['column_id'] + $column_id) : $column_id;
	 		}
	 		else
	 		{
	 			$row['column_id'] = $column_id;
	 		}
	 		$new_column_id = array_keys($row['column_id']);
	 		
	 		/***************************权限控制***************************************/
	 		$this->verify_content_prms(array('column_id' =>$this->input['column_id'], 'published_column_id'=>$ori_column_id_str));
	 		/***************************权限控制***************************************/
	 		$sql = "UPDATE " . DB_PREFIX ."feedback SET column_id = '". addslashes(serialize($row['column_id'] )) ."',pub_time = ".$pub_time." WHERE id = " . $row['id'];
	 		$this->db->query($sql);
	 		if(intval($row['status']) ==1)
	 		{ 
	 			if(!empty($row['expand_id']))   //已经发布过，对比修改先后栏目
	 			{
	 				$del_column = array_diff($ori_column_id,$new_column_id);
	 				if(!empty($del_column))
	 				{
	 					publish_insert_query($row, 'delete',$del_column);
	 				}
	 				$add_column = array_diff($new_column_id,$ori_column_id);
	 				if(!empty($add_column))
	 				{
	 					publish_insert_query($row, 'insert',$add_column);
	 				}
	 				$same_column = array_intersect($ori_column_id,$new_column_id);
	 				if(!empty($same_column))
	 				{
	 					publish_insert_query($row, 'update',$same_column);
	 				}
	 			}
	 			else 							//未发布，直接插入
	 			{
	 				if ($new_column_id) {
	 					$op = "insert";
	 					publish_insert_query($row,$op,$new_column_id);
	 				}
	 			}
	 		}
	 		else    //打回
	 		{
	 			if(!empty($row['expand_id']))
	 			{
	 				$op = "delete";
	 				publish_insert_query($row,$op);
	 			}
	 		}
	 	}
	 	$this->addItem('true');
	 	$this->output();
	}
	
	public function delete_common()
	{
		$common_id = trim($this->input['id']);
		$sql = 'DELETE FROM '.DB_PREFIX.'common WHERE id in ('.$common_id.')';
		$q =$this->db->query($sql);
		$this->addItem($common_id);
		$this->output();
	}
	
	public function update_common()
	{
		$common_id = intval($this->input['id']);
		$is_display = intval($this->input['is_display']);
		$corder = intval($this->input['c_id']);
		if($is_display==1)
		{
			$is_display = 0;
			$corder = 0;
		}
		else
		{
			$is_display = 1;
		}
		$sql = 'UPDATE '.DB_PREFIX.'common SET is_display = '.$is_display.', order_id = '.$corder.' WHERE id ='.$common_id;
		$q =$this->db->query($sql);
		$ret = array(
		'is_display' => $is_display,
		'id' => $common_id ? explode(',',$common_id) : array(),
		'order_id' => $corder,
		);
		$this->addItem($ret);
		$this->output();
	}
	
	public function common_show()
	{
		$display = trim($this->input['display']);
		$noplay = trim($this->input['noplay']);
		if($display)
		{
			$sql = 'UPDATE '.DB_PREFIX.'common SET is_display = 1 WHERE id in('.$display.')';
			$q =$this->db->query($sql);
		}
		if($noplay)
		{
			$sql = 'UPDATE '.DB_PREFIX.'common SET is_display = 0 WHERE id in('.$noplay.')';
			$q =$this->db->query($sql);
		}
		$ret = array(
			'display' => $display,
			'noplay'  => $noplay,
		);
		$this->addItem($ret);
		$this->output();
	}
	
	private function exp($name,$sp = '@')
	{
		if(!$name) 
		return array();
		else 
		return explode($sp,$name);
	}
	
	public function create_form()
	{
		if(!$id = $this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$feedback = $this->mode->detail($id,SORT_ASC);
		$feedback['submit_text'] = $feedback['submit_text'] ? $feedback['submit_text'] : '确定';
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $feedback['admin_user'])
		{
			$admin_user = explode(',',$feedback['admin_user']);
			if(!in_array($this->user['user_name'],$admin_user))
			{
				$this->errorOutput("对不起，您没有此表单的权限");
			}
		}
		if(!$feedback)
		{
			$this->errorOutput(NO_FORM);
		}
		$forms = $feedback['forms'];
		$html = '';
		foreach ($forms as $k=>$v)
		{
			if($v['type'] == 'standard')
			{
				$html .= $this->mode->formtypes($v);
			}
			elseif($v['type'] == 'fixed')
			{
				switch ($v['form_type'])
				{
					case 1:case 2:case 3:case 5:
						$v['form_type'] = $v['standard_type'];
						$html .= $this->mode->formtypes($v);
						break;
					case 4:case 6:
						$html .= '<p class="title">'.$v['name'].'</p>';
						$html .= '<div class="item file-item">';
        				if(is_array($v['element']))
						{
							foreach ($v['element'] as $element)
        					{
        						$formname = $formname.'['.$element['id'].']';
        						$element['ele_id'] = $element['id'];
        						$element['id'] = $v['id'];
        						$element['options'] = $element['value'] ? $element['value'] : array();
        						$element['name'] = '';
      							$element['tips'] = '请填写详细地址';
      							$element['type'] = $v['type'];
          						if($element['form_type'] == 4)
        						{
        							$html .= $this->mode->formtypes($element);
        						}
        						elseif(($element['form_type'] == 1))
        						{
         							$html .= $this->mode->formtypes($element);
        						}
        					}
						}
						$html .= '</div>';
        				break;
					default:break;
				}
			}	
		}
		if(!defined('FB_DOMAIN') || !FB_DOMAIN)
		{
			$this->errorOutput(ERROR_URL);
		}
        $feedback['page_title'] = $feedback['page_title'] ? $feedback['page_title'] : $feedback['title'];
		$souce_dir =  CUR_CONF_PATH . 'core/';
		$dir = CUR_CONF_PATH . 'data/';
        $template_name = defined('TEMPLATE_NAME') && TEMPLATE_NAME ? TEMPLATE_NAME : 'Baoming';
		$form = @file_get_contents($souce_dir.'html'.$template_name.'/'.'html_'.strtolower($template_name).'.html');
		if(!$form)
		{
			$this->errorOutput('获取模板文件失败');
		}
		$indexpic = $feedback['indexpic'] ? hg_fetchimgurl($feedback['indexpic'],400) : '';
		unset($feedback['indexpic']);
		$feedback['indexpic'] = $indexpic ? $indexpic : '';
		if($feedback['is_verifycode'] && $this->settings['App_verifycode'])
		{
			$type = $feedback['verifycode_type'];
			$verifycode = @file_get_contents($souce_dir.'htmlVerifycode/'.'html_verifycode.html');
			if(!$verifycode)
			{
				$this->errorOutput('获取验证码模板失败');
			}
			$feedback['verifycode'] = str_replace('#type#',$type,$verifycode);
		}
        $feedback['url'] = $url = !defined('FB_DOMAIN') || !FB_DOMAIN ? '../feedback.php' : FB_DOMAIN.'feedback.php';
        $feedback['html'] = $html;
        $feedback['basecss'] = FB_DOMAIN.strtolower($template_name);
        $form = $this->mode->replace_cell($form,$feedback,'liv_');
		$form = str_replace($find,$replace,$form);
		if(!is_writeable($dir))
		{
			$this->errorOutput(NOWRITE);
		}
        if($this->input['encryption'])
        {
            $filename = create_filename(strtotime($feedback['create_time']).$feedback['id']);
            $fname = '/index.html';
        }
        else {
            $filename = strtotime($feedback['create_time']).$feedback['id'];
            $fname = '/'.$feedback['id'].'.html';
        }
		/**生成入口文件**/
        $this->mode->create_file();
        $this->generate_assist($souce_dir,$dir);
        $this->generate_assist($souce_dir.'html'.$template_name.'/',$dir.$template_name.'/');
		/**生成入口文件结束**/
		$html_dir = $dir.$filename.'/';
		hg_mkdir($html_dir);
        file_put_contents($html_dir.$id.'.html',$form);
        if(file_exists($souce_dir.'feedback.css'))
        {
            $fb_css = file_get_contents($souce_dir.'feedback.css');
            if(!is_dir($html_dir.'css/'))
            {
                hg_mkdir($html_dir.'css/');
            }
            file_put_contents($html_dir.'css/fb.css',$fb_css);
        }
        if(file_exists($souce_dir.'feedback.js'))
        {
            $fb_js = file_get_contents($souce_dir.'feedback.js');
            if(!is_dir($html_dir.'js/'))
            {
                hg_mkdir($html_dir.'js/');
            }
            file_put_contents($html_dir.'js/fb.js',$fb_js);
        }
        if(file_exists($souce_dir.'html'.$template_name.'/index.html'))
        {
            $this->create_index($id);
        }
		if(file_exists($html_dir.$fname))
		{
			$ret['state'] = 1;
			$ret['url'] = FB_DOMAIN.$filename.$fname;
		}
		else 
		{
			$ret['state'] = 0;
		}
		$this->addItem($ret);
		$this->output();
	}

    public function create_index($id,$file_name = 'index')
    {
        if(!$id)
        {
            return false;
        }
        $fb = $this->mode->get_feedback_list('id = '.$id);
        $fb = $fb[0];
        $souce_dir =  CUR_CONF_PATH . 'core/';
        $dir = CUR_CONF_PATH . 'data/';
        $template_name = defined('TEMPLATE_NAME') && TEMPLATE_NAME ? TEMPLATE_NAME : 'Baoming';
        if(file_exists($souce_dir.'html'.$template_name.'/'.$file_name.'.html'))
        {
            $content = file_get_contents($souce_dir.'html'.$template_name.'/'.$file_name.'.html');
            $indexpic = $fb['indexpic'] ? hg_fetchimgurl(unserialize($fb['indexpic']),400) : '';
            unset($fb['indexpic']);
            if($indexpic)
            {
                $fb['indexpic'] = '<img src="'.$indexpic.'" class="index-pic"/>';
            }
            $data_dir = $dir.$fb['create_time'].$id;
            $fb['start_time'] = $fb['start_time'] ? date('Y-m-d H:i',$fb['start_time']) : '';
            $fb['end_time'] = $fb['end_time'] ? date('Y-m-d H:i',$fb['end_time']) : '';
            $fb['btn_page'] = $id.'.html';
            $fb['flag'] = $fb['end_time'] && $fb['end_time'] < TIMENOW ? '活动已结束' : '活动进行中' ;
            $fb['flag'] = !$fb['start_time'] || $fb['start_time'] > TIMENOW ? '活动进行中' : '活动未开始' ;
            $fb['basecss'] = FB_DOMAIN.strtolower($template_name);
            $content = $this->mode->replace_cell($content,$fb,'liv_');
            if(!is_dir($data_dir))
            {
                hg_mkdir($data_dir);
            }
            file_put_contents($dir.$data_dir.'/'.$file_name.'.html',$content);
            return true;
        }else
        {
            return false;
        }

    }

	//消息回复
	public function send_message()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NO_ACCESS_TOKEN);
		}
		$sql = 'SELECT user_id,message_id FROM '.DB_PREFIX.'record_person WHERE id = '.$id .' ORDER BY create_time DESC';
		$backinfo = $this->db->query_first($sql);
		$msg_id = $backinfo['message_id'];
		if(!$msg_id)
		{
			$this->errorOutput(NOCONTENT);
		}
		if($this->settings['App_im'] && $msg_id)
		{
			require_once ROOT_PATH . 'lib/class/curl.class.php';
			$this->curl = new curl($this->settings['App_im']['host'],$this->settings['App_im']['dir']);
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('session_id', $msg_id);
			$this->curl->addRequestData('message', trim($this->input['message']));
			$this->curl->addRequestData('access_token', trim($this->input['access_token']));
			$this->curl->addRequestData('user_device_token', trim($this->input['device_token']));
			$this->curl->addRequestData('user_appid',$this->user['appid']);
			$this->curl->addRequestData('utype', 'admin');
			$this->curl->addRequestData('a', 'reply_session');
			$message = $this->curl->request('message.php');
			$message = $message[0];
			if($message['session_id'])
			{
				$sql = 'UPDATE '.DB_PREFIX.'record_person SET  admin_reply_count = admin_reply_count +1 , total_reply = total_reply + 1 WHERE id = '.$id ;
				$this->db->query($sql);
				/*************会员查看过消息之后，管理员的回复数量加新消息数************/
				if($this->settings['App_members'])
				{
					require_once ROOT_PATH . 'lib/class/members.class.php';
					$members = new members();
					$data = array(
						'member_id'	=> $backinfo['user_id'],
						'mark'		=> 'apply',
						'math'		=> 1,
						'total'		=> 1,
					);
					$ret = $members->updateMyData($data);
				}
				/*************会员查看过消息之后，管理员的回复数量加新消息数************/
			}
		}
		if(!$message)
		{
			$this->errorOutput(ERROR_SEND);
		}
		$this->addItem($message);
		$this->output();
	}
	
	//创建消息
	public function add_message()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NO_ACCESS_TOKEN);
		}
		$sql = 'SELECT rp.message_id, rp.user_id,rp.user_name,rp.device_token,rp.app_id,rp.feedback_id,f.title,f.indexpic,f.brief FROM '.DB_PREFIX.'record_person rp LEFT JOIN '.DB_PREFIX.'feedback f ON f.id = rp.feedback_id  WHERE rp.id = '.$id ;
		$backinfo = $this->db->query_first($sql);
		if(!$backinfo['user_id'] || !$backinfo['user_name'] )
		{
			$this->errorOutput(NO_TOUSER);
		}
		if($this->settings['App_im'])
		{
			require_once ROOT_PATH . 'lib/class/curl.class.php';
			$this->curl = new curl($this->settings['App_im']['host'],$this->settings['App_im']['dir']);
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$params = array(
				'touser_id'				=> $backinfo['user_id'],
				'touser_name'			=> $backinfo['user_name'],
				'touser_type'			=> '',
				'touser_device_token'	=> $backinfo['device_token'],
				'touser_appid'			=> $backinfo['app_id'],
				'utype'					=> 'admin',
				'access_token'			=> trim($this->input['access_token']),
				'user_device_token'		=> trim($this->input['device_token']),
				'user_appid'			=> $this->user['appid'],
				'title'					=> $backinfo['title'],
				'message'				=> trim($this->input['message']),
				'indexpic'				=> $backinfo['indexpic'],
				'brief'					=> $backinfo['brief'],
				'settings[push_notice]' => 1,
				'app_uniqueid'			=> APP_UNIQUEID,
				'a'						=> 'send_message',
			);
			foreach ($params as $k=>$v)
			{
				$this->curl->addRequestData($k, $v);
			}
			$message = $this->curl->request('message.php');
			$message = $message[0];
			if($message['session_id'])
			{
				$sql = 'UPDATE '.DB_PREFIX.'record_person SET message_id = '.$message['session_id'].' , admin_reply_count = admin_reply_count +1, total_reply = total_reply + 1  WHERE id = '.$id ;
				$this->db->query($sql);
				/*************会员查看过消息之后，管理员的回复数量加新消息数************/
				if($this->settings['App_members'])
				{
					require_once ROOT_PATH . 'lib/class/members.class.php';
					$members = new members();
					$data = array(
						'member_id'	=> $backinfo['user_id'],
						'mark'		=> 'apply',
						'math'		=> 1,
						'total'		=> 1,
					);
					$members->updateMyData($data);
				}
				/*************会员查看过消息之后，管理员的回复数量加新消息数************/
			}
			else 
			{
				$this->errorOutput(ERROR_SEND);
			}
		}
		if(!$message)
		{
			$this->errorOutput(ERROR_SEND);
		}
		$this->addItem($message);
		$this->output();
	}
	
	public function is_reply()
	{
		$is_on = intval($this->input['is_on']);
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		if($is_on)//开启
		{
			$sql = 'SELECT start_time,end_time,status FROM '.DB_PREFIX.'feedback WHERE id='.$id;
			$result = $this->db->query_first($sql);
			if($result['status'] == 0 || $result['status'] == 2)
			{
				$this->errorOutput('该表单尚未审核，不能开启回复');
			}
			if($result['start_time'] && $result['start_time'] > TIMENOW)
			{
				$this->errorOutput('该表单尚未开始，不能开启回复');
			}
			if($result['end_time'] && $result['end_time'] < TIMENOW)
			{
				$this->errorOutput('该表单已经结束，不能开启回复');
			}
		}
		$sql = 'UPDATE '.DB_PREFIX .'feedback SET is_reply = '.$is_on .' WHERE id='.$id;
		$this->db->query($sql);
		$data = array('id'=>$id,'is_reply' => $is_on);
		$this->addItem($data);
		$this->output();
	}
	
	    
    public function get_catagory()
    {
    	$id = intval($this->input['id']);
    	if(!$id)
    	{
    		$this->errorOutput(NOID);
    	}
    	$sql = 'SELECT n.name,n.fid,n.is_last,n.order_id,n.create_time,ni.* FROM '.DB_PREFIX.'feedback_node n LEFT JOIN '.DB_PREFIX.'node_info ni ON n.id = ni.id WHERE n.id = '.$id;
    	$cat = $this->db->query_first($sql);
    	if($cat['fid'])
    	{
    		$sql = 'SELECT title FROM '. DB_PREFIX.'node_info  WHERE id = '.$cat['fid'];
    		$fid = $this->db->query_first($sql);
    		$cat['parent_name'] = $fid['title'];
    	}
    	$cat['indexpic_arr'] = $cat['indexpic'] ? unserialize($cat['indexpic']) : array();
    	$cat['title']	 = $cat['title'] ? $cat['title'] : $cat['name'];
    	$cat['page_title'] = $cat['page_title'] ? $cat['page_title'] : $cat['title'];
    	$cat['start_time'] = $cat['start_time'] ? date('Y年m月d日',$cat['start_time']) : '';
    	$cat['end_time'] = $cat['end_time'] ? date('Y年m月d日',$cat['end_time']) : '';
    	$cat['brief'] = html_entity_decode(stripslashes($cat['brief']), ENT_QUOTES, 'UTF-8');
		$cat['more_brief'] = html_entity_decode(stripslashes($cat['more_brief']), ENT_QUOTES, 'UTF-8');//stripslashes($cat['more_brief']);
		$cat['more_info'] = html_entity_decode(stripslashes($cat['more_info']), ENT_QUOTES, 'UTF-8');//stripslashes($cat['more_info']);
    	$cat['more_picture_arr'] = $cat['more_picture'] ? unserialize($cat['more_picture']) : array();
		$cat['url'] = FB_DOMAIN.$cat['create_time'].$cat['id'].'/c'.$cat['id'].'.html?_ddtarget=push';
		$cat['indexpic'] = $cat['indexpic_arr'] ? hg_fetchimgurl($cat['indexpic_arr'],500) : array();
    	$cat['more_picture'] = $cat['more_picture_arr'] ? hg_fetchimgurl($cat['more_picture_arr'],500) : array();
    	$data = $cat;
    	if($cat['is_last'])	//明信片列表
    	{
    		$sql = 'SELECT id,title,indexpic,brief,counts,create_time FROM '.DB_PREFIX.'feedback WHERE node_id = '.$id.' AND status = 1 ORDER BY order_id ASC ';
    		$q = $this->db->query($sql);
    		while($r = $this->db->fetch_array($q))
    		{
    			if($r['title'] && $r['indexpic'])
    			{
	    			$r['indexpic_arr'] = $r['indexpic'] ? unserialize($r['indexpic']) : array();
	    			$r['url'] = FB_DOMAIN.$r['create_time'].$r['id'].'/'.$r['id'].'.html?_ddtarget=push';
	    			$sort[] = $r;
	    			$data['content'] = $sort;
    			}
    		}
    		if(!$this->generate($data,'c'))
    		{
    			$this->errorOutput('创建失败');
    		}
    		$url = FB_DOMAIN.$cat['create_time'].$id.'/c'.$id.'.html';
    	}
    	else	//子分类列表
    	{
    		$sql = 'SELECT n.id,n.name,n.is_last,n.create_time,n.order_id,ni.title,ni.indexpic FROM '.DB_PREFIX.'feedback_node n LEFT JOIN '.DB_PREFIX.'node_info ni ON n.id = ni.id WHERE n.fid = '.$id.' ORDER BY order_id ASC ';
    		$q = $this->db->query($sql);
    		while($r = $this->db->fetch_array($q))
    		{
    			if($r['title'] && $r['indexpic'])
    			{
	    			$r['title'] = $r['title'] ? $r['title'] : $r['name'];
	    			$r['indexpic_arr'] = $r['indexpic'] ? unserialize($r['indexpic']) : array();
	    			$tp = 'c';
	    			$r['url'] = FB_DOMAIN.$r['create_time'].$r['id'].'/'.$tp.$r['id'].'.html?_ddtarget=push';
	    			$sort[] = $r;
    			}
    		}
    		$data['content'] = $sort;
    		if(!$this->generate($data,'c'))
    		{
    			$this->errorOutput('创建失败');
    		}
    		$url = FB_DOMAIN.$cat['create_time'].$id.'/c'.$id.'.html';
    	}
    	$this->addItem($url);
    	$this->output();
    }
    
   
    public function create_form2()
    {
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$feedback = $this->mode->detail($id);
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $feedback['admin_user'])
		{
			$admin_user = array();
			$admin_user = explode(',',$feedback['admin_user']);
			if(!in_array($this->user['user_name'],$admin_user))
			{
				$this->errorOutput("对不起，您没有此表单的权限");
			}
		}
		$feedback['create_time'] = strtotime($feedback['create_time']);
		$feedback['indexpic_arr'] = $feedback['indexpic'];
		unset($feedback['indexpic']);
		$feedback['indexpic'] = $feedback['indexpic_arr'] ? hg_fetchimgurl($feedback['indexpic_arr'],500) : '';
		if(!$feedback)
		{
			$this->errorOutput(NO_FORM);
		}
		$forms = $feedback['forms'];
		$feedback['style'] = 'greeting';
		$feedback['template'] = 'form';
		$feedback['filturl'] = $feedback['url'] = !defined('FB_DOMAIN') || !FB_DOMAIN ? '../feedback.php' : FB_DOMAIN.'feedback.php';
		if(!$this->generate($feedback,'','generate_childs'))
    	{
    		$this->errorOutput('创建失败');
    	}
    	$url = FB_DOMAIN.$feedback['create_time'].$id.'/'.$id.'.html';
    			
		$greetfile = CORE_DIR.'greet_result.php';
		$source_file = CORE_DIR.$feedback['style'].'/cards.html';
		$feedback['page_title'] =  $feedback['page_title'] ? $feedback['page_title'] : $feedback['title'];
		if(file_exists($source_file))
		{
			$temp = @file_get_contents($source_file);
			if($feedback['node_id'])
			{
				$feedback['sort_name'] = $this->mode->get_sort_by_id($feedback['node_id']);
			}
			preg_match_all('/#(.*?)#/i',$temp,$match);
			$match = $match[1];
			if($match)
	    	{
	    		foreach ($match as $v)
	    		{
	    			$find[] = '#'.$v.'#';
	    			$replace[] = $feedback[$v];
	    		}
	    		$html = str_replace($find,$replace,$temp);
	    	}
	    	@file_put_contents(DATA_DIR.$feedback['create_time'].$feedback['id'].'/cards.php', $html);
		}
    	if( DATA_DIR.$feedback['create_time'].$feedback['id'].'/'.$feedback['id'].'.html')
		{
			$this->mode->update($feedback['id'], 'feedback',array('reupdate'=>0));
			$ret['state'] = 1;
			$ret['url'] = $url;
		}
		else 
		{
			$ret['state'] = 0;
		}
		$this->addItem($ret);
		$this->output();
    	
    }
    
    public function generate_childs($data,$clmatch,$dir)
    {
    	$temp_sorl = @file_get_contents($dir."childs/"."{$data['template']}_{$clmatch}.html");
    	preg_match_all('/#(.*?)#/i',$temp_sorl,$match_child);
    	$match_child = $match_child[1];
    	if($match_child && $data[$clmatch])
    	{
 		    foreach ($data[$clmatch] as $ck=>$cv)
		    {
		    	$temp_html = '';
		    	$match_child_type = array();
		    	preg_match_all('/{{'.$cv['mode_type'].'}}([\s\S]*?){{\/'.$cv['mode_type'].'}}/i',$temp_sorl,$match_child_type);
		    	$temp_html = $match_child_type[1][0];
		    	if(!$temp_html)
		    	{
		    		continue;
		    	}
			    foreach ($match_child as $val)
			    {
			    	$find_child[$ck][] = '#'.$val.'#';
			    	if($cv['mode_type'] == 'split')
			    	{
			    		$replace_child[$ck][] = $data[$val];
			    	}
			    	else 
			    	{
			    		$replace_child[$ck][] = $cv[$val];
			    	}
			    }
			 	$html_child .= str_replace($find_child[$ck],$replace_child[$ck],$temp_html);
		    }
    	}
    	return $html_child;
    }
     //生成页面
    public function generate($data,$tp,$child_func = 'generate_child_page')
    {
    	if(empty($data['style']))
    	{
    		$this->errorOutput('套系不能为空');
    	}
    	if(empty($data['template']))
    	{
    		$this->errorOutput('模板不能为空');
    	}
    	if(!defined('CORE_DIR') || !CORE_DIR)
    	{
    		define('CORE_DIR', CUR_CONF_PATH.'core/');//定义模板目录
    	}
    	if(is_dir(!$dir = CORE_DIR.$data['style'].'/'))
    	{
    		$this->errorOutput($data['style'].'套系文件夹不存在');
    	}
    	if(file_exists(!$source_file = $dir.$data['template'].'.html'))
    	{
    		$this->errorOutput($data['style'].'套系'.$data['template'].'模板文件不存在');
    	}
    	if(!$tem = @file_get_contents($source_file))
    	{
    		$this->errorOutput('无法获取模板内容');
    	}
    	$html = $tem;
    	$sort_html = '';
    	preg_match_all('/#(.*?)#/i',$tem,$match);
    	preg_match_all('/@(.*?)@/i',$tem,$clmatch);
    	$match = $match[1];
		$clmatch = $clmatch[1];
		if($clmatch)
    	{
    		foreach ($clmatch as $v)
    		{
    			$child_html = $this->$child_func($data, $v,$dir);
    			$find[] = '@'.$v.'@';
    			$replace[] .= $child_html;
    		}
    	}
    	if($match)
    	{
    		foreach ($match as $v)
    		{
    			$find[] = '#'.$v.'#';
    			$replace[] = $data[$v];
    		}
    		$html = str_replace($find,$replace,$tem);
    		$html = str_replace('{{3}}','#',$html);
    	}
    	if(!defined('DATA_DIR') || !DATA_DIR)
    	{
    		define('DATA_DIR', CUR_CONF_PATH.'data/');//定义模板目录
    	}
    	$html_dir = DATA_DIR.$data['create_time'].$data['id'].'/';
    	$this->generate_assist($dir,$html_dir);
    	$greettemp = @file_get_contents(CORE_DIR.'greet_result.php');
		file_put_contents(DATA_DIR.'greet_result.php', $greettemp);
		$feedtem = @file_get_contents(CORE_DIR.'feedback.php');
		file_put_contents(DATA_DIR.'feedback.php', $feedtem);
    	file_put_contents($html_dir.'/'.$tp.$data['id'].'.html', $html);
    	return true;
    }
    
    //生成css/js/
    public function generate_assist($dir,$html_dir)
    {
		hg_mkdir($html_dir);
    	if (is_dir($dir.'css'))
        {
        	hg_mkdir($html_dir.'css');
            if(!file_copy($dir.'css', $html_dir.'css', array()))
            {
                $this->errorOutput(realpath($html_dir.'css').'目录不可写');
            }
        }
    	if (is_dir($dir.'js'))
        {
        	hg_mkdir($html_dir.'js');
            if(!file_copy($dir.'js', $html_dir.'js', array()))
            {
                $this->errorOutput(realpath($html_dir.'js').'目录不可写');
            }
        }
   		if (is_dir($dir.'images'))
        {
        	hg_mkdir($html_dir.'images');
            if(!file_copy($dir.'images', $html_dir.'images', array()))
            {
                $this->errorOutput(realpath($html_dir.'images').'目录不可写');
            }
        }
    	return true;
    }
    
    public function generate_child_page($data,$clmatch,$dir)
    {
    	
    	$temp_sorl = @file_get_contents($dir."childs/"."{$data['template']}_{$clmatch}.html");
    	preg_match_all('/#(.*?)#/i',$temp_sorl,$match_child);
    	$match_child = $match_child[1];
    	if($match_child && $data[$clmatch])
    	{
    		foreach ($data[$clmatch] as $k=>$cv)
    		{
    			$cv['indexpic'] = $cv['indexpic_arr'] ? hg_fetchimgurl($cv['indexpic_arr'],500) : '';
	    		foreach ($match_child as $val)
	    		{
	    			$find_child[$k][] = '#'.$val.'#';
	    			$replace_child[$k][] = $cv[$val];
	    		}
	    		$html_child .= str_replace($find_child[$k],$replace_child[$k],$temp_sorl);
    		}
    	}
    	return $html_child;
    }
    
    /************************************云平台使用接口*****************************/
    public function yuncreate()
    {
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['node_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'feedback_node WHERE id IN('.$this->input['node_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}		
		$nodes['column_id'] = $this->input['column_id'];
		$nodes['published_column_id'] = '';
		###获取默认数据状态
		$status = $this->get_status_setting('create');
		$nodes['_action'] = 'manage';
		$this->verify_content_prms($nodes);
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$forms = $this->input['forms'];
		if($forms && is_array($forms))
		{
			foreach ($forms as $key=>$value)
			{
				$value['order_id'] = $key;
				$is_name += $value['is_name'];
				if($value['type'] == 'standard')
				{
					$standard[] = $value;
				}
				else 
				{
					$fixed[] = $value;
				}
			}
		}
		if(!$this->settings['App_verifycode'] && intval($this->input['is_verifycode']))
		{
			$this->errorOutput('验证码应用未安装！');
		}
		$data = $this->request_param();
		$data['status']				= $status;
		$data['org_id']				= $this->user['org_id'];
		$data['user_id']			= $this->user['user_id'];
		$data['user_name']			= $this->user['user_name'];
		$data['create_time']		= TIMENOW;
		$data['update_user_id']		= $this->user['user_id'];
		$data['update_user_name']	= $this->user['user_name'];
		$data['update_time']		= TIMENOW;
		$data['ip']					= hg_getip();
		$data['appid']				= $this->user['appid'];
		$data['appname']			= $this->user['display_name'];
    	if($_FILES['indexpic'])
		{
			$files['Filedata'] = $_FILES['indexpic'];
			$material = $this->material->addMaterial($files,$vid);
			$indexpic = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
		}elseif($this->input['indexpic'] && $this->input['indexpic']['filename'])
		{
			$indexpic = $this->input['indexpic'];
		}
		$data['indexpic'] = $indexpic ? serialize($indexpic) : '';
		$column_id = $this->input['column_id'];
		$data['column_id'] = $this->mPublishColumn->get_columnname_by_ids('id,name',$column_id);
		$data['column_id'] = $data['column_id'] ? @serialize($data['column_id']) : '';	
		$vid = $this->mode->create($data,'feedback');
		$data['id'] = $vid;
		if($standard)
		{
			$data_s = $this->mode->process_standard($standard, $vid);
		}
		if($fixed)
		{
			$data_f = $this->mode->process_fixed($fixed, $vid);
		}
		$data_s['common'] = $data_f['common'] = array();
		if($data_s['standard'])
		{
			$this->mode->insert_datas('standard', $data_s['standard']);
		}
		if($data_f['fixed'])
		{
			$this->mode->insert_datas('fixed', $data_f['fixed']);
		}
		$common_data = array_merge($data_s['common'],$data_f['common']);
		if($common_data)
		{
			$this->mode->insert_datas('common', $common_data,1);
		}
		if ($vid)
		{
			$data['id'] = $vid;
			$data['problem'] = $this->mode->forms($vid);
			//放入发布队列
			if(intval($data['status']) == 1  && !empty($column_id))
			{
				$op = 'insert';
				publish_insert_query($data, $op, $data['user_name']);
			}
		}
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建反馈表单',$data,'','创建'.$data['title'] . $vid);
			$this->addItem($data);
			$this->output();
		}
    }
    
    public function yunupdate()
    {
    	$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
    	$forms = $this->input['forms'];
    	if(!$forms)
		{
			$this->errorOutput('对不起，组件不能为空');
		}
		$standard_ids = $fixed_ids = array();
		foreach ($forms as $key=>$value)
		{
			$value['order_id'] = $key+1;
			$is_name = $is_name ? 1 : $value['is_name'];
			if($value['type'] == 'standard')
			{
				$standard[] = $value;
				$standard_ids[] = $value['id'];
			}
			else 
			{
				$fixed[] = $value;
				$fixed_ids[] = $value['id'];
			}
		}

		if(!$is_name && !$this->input['is_login']) //如果既不需要登录而且没有设置回收表单名
		{
			$this->errorOutput("上传附件和分割线组件不允许单独使用");
		}
		if(!$this->settings['App_verifycode'] && intval($this->input['is_verifycode']))
		{
			$this->errorOutput('验证码应用未安装！');
		}
		$_feedback = $this->mode->get_feedback('id = '.$id, '*'); //获取问卷信息的初始数据
		$status = $_feedback['status'];
		#####节点权限检测数据收集
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$_node_ids = '';
			if($_feedback['node_id'])
			{
				$_node_ids = $_feedback['node_id'];
			}
			if($this->input['node_id'])
			{
				$_node_ids  = $_node_ids ? $_node_ids . ',' . $this->input['node_id'] : $this->input['node_id'];
			}
			if($_node_ids)
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'feedback_node WHERE id IN('.$_node_ids.')';
				$query = $this->db->query($sql);
				$nodes = array();
				while($row = $this->db->fetch_array($query))
				{
					$nodes['nodes'][$row['id']] = $row['parents'];
				}
			}
		}
		#####节点权限
		$nodes['id'] 		= $id;
		$nodes['user_id'] 	= $_feedback['user_id'];
		$nodes['org_id'] 	= $_feedback['org_id'];
		$nodes['column_id'] = $this->input['column_id'];
		
		$nodes['published_column_id'] = '';
		###获取默认数据状态
		if(!empty($_feedback['column_id']))
		{
			$status = $this->get_status_setting('update_publish', $_feedback['status']);
		}
		else 
		{			
			if(intval($_feedback['status']) == 1)
			{
				$status = $this->get_status_setting('update_audit', $_feedback['status']);
			}
		}
		$ori_column_id = array();
		$_feedback['column_id'] = unserialize($_feedback['column_id']);
		if(is_array($_feedback['column_id']))
		{
			$ori_column_id = array_keys($_feedback['column_id']);
			$nodes['published_column_id'] = implode(',', $ori_column_id);
		}
		$nodes['_action'] = 'manage';
		######获取默认数据状态
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $_feedback['admin_user'])
		{
			$admin_user = array();
			$admin_user = explode(',',$_feedback['admin_user']);
			if(!in_array($this->user['user_name'],$admin_user))
			{
				$this->errorOutput("对不起，您没有此表单的权限");
			}
		}
		if($this->user['group_type'] > MAX_ADMIN_TYPE && !$_feedback['admin_user'])
		{
			$this->verify_content_prms($nodes);
		}
		########权限#########
		$update_data = $this->request_param();
		$vid = $id;
		if($_FILES['indexpic'])
		{
			$files['Filedata'] = $_FILES['indexpic'];
			$material = $this->material->addMaterial($files,$vid);
			$indexpic = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
		}elseif($this->input['indexpic'] && $this->input['indexpic']['filename'])
		{
			$indexpic = $this->input['indexpic'];
		}
		$update_data['indexpic'] = $indexpic ? serialize($indexpic) : '';
		$column_id		   = $this->input['column_id'];
		$update_data['column_id'] = $this->mPublishColumn->get_columnname_by_ids('id,name', $column_id);
		$update_data['column_id'] = $update_data['column_id'] ? serialize($update_data['column_id']) : '';
		$update_data = $this->mode->update($id,'feedback',$update_data);
		$update_data['id'] = $id;
		if($update_data['affected_rows'])
		{
			$affect_rows = 1;
		}
		//表单的更新 包括表单新增 修改 删除；
		$_forms = $this->mode->get_forms($id);
		//原反馈表单id
		$_standard_ids = $_fixed_ids = array();
		if(is_array($_forms) && count($_forms)>0)
		{
			foreach ($_forms as $v)
			{
				if($v['type'] == 'standard')
				{
					$_standard_ids[] = $v['id'];
				}
				if($v['type'] == 'fixed')
				{
					$_fixed_ids[] = $v['id'];
				}
			}
		}		
		$st_diff = array_diff($_standard_ids,$standard_ids);//比较标准组件，要删除的组件id 
		$fx_diff = array_diff($_fixed_ids,$fixed_ids);//比较固定组件，要删除的组件id 
		if(count($st_diff)>0)
		{
			$delete_stan_ids = implode(',',$st_diff);
		}
		if(count($fx_diff)>0)
		{
			$delete_fix_ids = implode(',',$fx_diff);
		}
		$data_s['common'] = $data_f['common'] = array();
		if($standard)
		{
			$data_s = $this->mode->process_standard($standard, $vid, 1);
		}
		if($fixed)
		{
			$data_f = $this->mode->process_fixed($fixed, $vid, 1);
		}
		$common_data = array_merge($data_s['common'],$data_f['common']);
		if($common_data)
		{
			$this->mode->insert_datas('common', $common_data,1);
		}
		if($data_s['standard'])
		{
			foreach ($data_s['standard'] as $v)
			{
				$up_standard_data = $this->mode->update($v['id'], 'standard' , $v);//更新标准组建
				if($up_standard_data['affected_rows'])
				{
					$affect_rows = 1;
				}
			}
		}
    	if($data_f['fixed'])
		{
			foreach ($data_f['fixed'] as $v)
			{
				$up_fixed_data = $this->mode->update($v['id'], 'fixed' , $v);//更新标准组建
				if($up_fixed_data['affected_rows'])
				{
					$affect_rows = 1;
				}
			}
		}
		if($data_s['new_standard'])
		{
			$this->mode->insert_datas('standard', $data_s['new_standard']);
			$affect_rows = 1;
		}
		if($data_f['new_fixed'])
		{
			$this->mode->insert_datas('fixed', $data_f['new_fixed']);
			$affect_rows = 1;
		}
		if($delete_stan_ids)//删除组建
		{
			$sql = "DELETE FROM " . DB_PREFIX."standard WHERE id in(".$delete_stan_ids.")";
			$this->db->query($sql);
			$affect_rows = 1;
		}
		if($delete_fix_ids)
		{
			$sql = "DELETE FROM " . DB_PREFIX."fixed WHERE id in(".$delete_fix_ids.")";
			$this->db->query($sql);
			$affect_rows = 1;
		}
		if($affect_rows)
		{
			$update_user = array(
			    'update_user_id'    => $this->user['user_id'],
			    'update_user_name'  => $this->user['user_name'],
			    'update_time'       => TIMENOW,
			);
			if($status)
			{
				$update_data['reupdate'] = $update_user['reupdate'] = 1;
			}
			$update_user = $this->mode->update($id, 'feedback',$update_user);
			$update_data['update_user_id'] = $update_user['update_user_id'];
			$update_data['update_user_name'] = $update_user['update_user_name'];
			$update_data['update_time'] = $update_user['update_time'];
		}
		unset($update_data['affected_rows']);
		$update_data['sort_id'] = $update_data['node_id'];
		//发布系统
		$ret_feedback = $this->mode->get_feedback(" id = {$id}", 'column_id,status,expand_id');
		//更新的栏目
		$ret_feedback['column_id'] = unserialize($ret_feedback['column_id']);
		$new_column_id = array();
		if(is_array($ret_feedback['column_id']))
		{
			$new_column_id = array_keys($ret_feedback['column_id']);
		}
        //$data['id'] = $id;
		if($status == 1)
		{
			if(!empty($ret_feedback['expand_id']))   //已经发布过，对比修改先后栏目
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					publish_insert_query($update_data, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					publish_insert_query($update_data, 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					publish_insert_query($update_data, 'update',$same_column);
					//有新插入素材时需插入子队列
					//publish_insert_query($update_data, 'insert',$same_column,1);
				}			
			}
			else 							//未发布，直接插入
			{
				$op = "insert";
				publish_insert_query($update_data, $op);
			}				
		}
		else    //打回
		{
			if(!empty($ret_feedback['expand_id']))
			{
				$op = "delete";
				publish_insert_query($update_data,$op);
			}
		}
		if($id)
		{
			$update_data['forms'] = $forms;
			$this->addLogs('更新反馈表单',$_feedback,$update_data,'更新' .$update_data['title']. $id);
			$this->addItem($update_data);
			$this->output();
		}
	
    }
    
    private function request_param()
    {
    	if($this->input['header_info'] && is_array($this->input['header_info']))
    	{
    		foreach ($this->input['header_info'] as $k=>$v)
    		{
    			if($v['key'] && !is_numeric($v['key']))
    			{
    				${$v['key']} = $v['value'];
    			}
    			if($v['label'] || $v['value'])
    			{
    				$header_info[] = $v;
    			}
    		}
    		$start_time = $start_time ? $start_time : $this->input['start_time'];
    		$end_time = $end_time ? $end_time : $this->input['end_time'];
    	}
    	if($this->input['footer_info'] && is_array($this->input['footer_info']))
    	{
    		foreach ($this->input['footer_info'] as $k=>$v)
    		{
    			if($v['key'] && !is_numeric($v['key']))
    			{
    				${$v['key']} = $v['value'];
    			}
    			if($v['label'] || $v['value'])
    			{
    				$footer_info[] = $v;
    			}
    		}
    	}
		if(!$title && !trim($this->input['title']))
		{
			$this->errorOutput(NO_TITLE);
		}
		if($end_time && strtotime($start_time) > strtotime($end_time))
		{
			$this->errorOutput('开始时间不能大于结束时间');
		}
    	$data = array(
			'title'        		=> $title ? $title : trim($this->input['title']),
		    'brief'        		=> $brief ? $brief : trim($this->input['brief']),
		    'is_login'     		=> $this->input['is_login'] ? 1 : 0,//是否需要登录
			'userid_limit_time'	=> $this->input['userid_limit_time'] > 0 ? $this->input['userid_limit_time'] : 0,//用户限制时间
			'userid_limit_num'	=> $this->input['is_login'] && $this->input['userid_limit_num'] > 0 ? intval($this->input['userid_limit_num']) : 1,//用户限制次数
			'is_ip'    			=> $this->input['is_ip'] ? 1 : 0,
			'ip_limit_time'		=> $this->input['ip_limit_time'] > 0 ? $this->input['ip_limit_time'] : 0,// ip限制时间
			'ip_limit_num'		=> $this->input['is_ip'] && $this->input['ip_limit_num'] > 0 ? intval($this->input['ip_limit_num']) : 1,//ip限制次数
			'is_device'     	=> $this->input['is_device'] ? 1 : 0,
			'device_limit_time'	=> $this->input['device_limit_time'] > 0 ? $this->input['device_limit_time'] : 0,//设备限制时间
			'device_limit_num'	=> $this->input['is_device'] && $this->input['device_limit_num'] > 0 ? intval($this->input['device_limit_num']) : 1,//设备限制次数
		    'is_verifycode'     => $this->input['is_verifycode'] ? 1 : 0,
		    'verifycode_type'   => $this->input['is_verifycode'] ? intval($this->input['verifycode_type']) : 0,
		    'is_credit'    		=> $this->input['is_credit'] ? 1 : 0,
		    'credit1'   	   	=> $this->input['is_credit'] ? intval($this->input['credit1']) : 0,
		    'credit2'   	   	=> $this->input['is_credit'] ? intval($this->input['credit2']) : 0,
			'remark'   	   		=> trim($this->input['remark']),//备注
			'start_time'   		=> $start_time ? strtotime($start_time) : 0,
			'end_time'     		=> $end_time ? strtotime($end_time) : 0,
			'page_title'   		=> trim($this->input['page_title']),//页面标题header
			'jump_to'	   		=> trim($this->input['jump_to']),//网页打开的时候自动跳转
			'node_id'      		=> intval($this->input['node_id']),
			'admin_user'		=> $this->input['admin_user'],//单独权限
			'submit_text'		=> trim($this->input['submit_text']),
		 	'reupdate' 			=> 0,
  			'template_id' 		=> intval($this->input['template_id']),//模板id
    		'header_info'		=> $header_info ? serialize($header_info) : '',
    		'footer_info'		=> $footer_info ? serialize($footer_info) : '',
		);	
		return $data;
    }
    
    public function upload()
    {
    	$file['Filedata'] = $_FILES['files'];
    	$content_id = intval($this->input['id']);
    	$material = $this->mode->uploadToPicServer($file, $content_id);
    	if(!$material)
    	{
    		$this->errorOutput('图片上传失败');
    	}
    	$ret = array(
    		'id'	=> $material['id'],
    		'host'	=> $material['host'],
    		'dir'	=> $material['dir'],
    		'filepath'	=> $material['filepath'],
    		'filename'	=> $material['filename'],
    	);
    	$this->addItem($ret);
    	$this->output();
    }
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
	
	public function save_header()
    {
    	if(!isset($this->input['id']) || !isset($this->input['header_info']) )
    	{
    		$this->errorOutput('缺少必要参数');
    	}
    	$id = intval($this->input['id']);
    	$dbdata = array();
    	foreach ($this->input['header_info'] as $k=>$v)
        {
        	if($v['key'] && !is_numeric($v['key']))
        	{
        		${$v['key']} = $v['value'];
        	}
        }
        if(!$title && !trim($this->input['title']))
        {
        	$this->errorOutput(NO_TITLE);
        }
        if($end_time && strtotime($start_time) > strtotime($end_time))
       	{
       		$this->errorOutput('开始时间不能大于结束时间');
       	}
       	$dbdata['title'] = isset($title) ? $title : trim($this->input['title']);
       	$dbdata['brief'] = isset($brief) ? $brief : trim($this->input['brief']);
       	$dbdata['start_time'] = isset($start_time) ? strtotime($start_time) : 0;
       	$dbdata['end_time'] = isset($end_time) ? strtotime($end_time) : 0;

       	if($_FILES['indexpic'])
       	{
       		$files['Filedata'] = $_FILES['indexpic'];
       		$material = $this->material->addMaterial($files,$id);
       		$indexpic = array(
       			'host' => $material['host'],
	       		'dir' => $material['dir'],
	       		'filepath' => $material['filepath'],
	       		'filename' => $material['filename'],
       			);
       	}elseif($this->input['indexpic'] && $this->input['indexpic']['filename'])
       	{
       		$indexpic = $this->input['indexpic'];
       	}
       	$dbdata['indexpic'] = $indexpic ? serialize($indexpic) : '';
       	$dbdata['header_info'] = serialize($this->input['header_info']);
       	$ret = $this->mode->update($id,'feedback', $dbdata);
       	$dbdata['affected_rows'] = $ret['affected_rows'];
       	$this->addItem($dbdata);
       	$this->output();
    }
        
        public function save_footer()
        {
            if(!isset($this->input['id']) || 
               !isset($this->input['footer_info']) )
            {
                $this->errorOutput('缺少必要参数');
            }
            
            if(!is_array($this->input['footer_info']) ||
               count($this->input['footer_info']) <= 0)
            {
                $this->errorOutput('格式错误');
            }
            
            $id = intval($this->input['id']);
            
            $dbdata = array();
//            foreach ($this->input['footer_info'] as $k=>$v)
//            {
//                if($v['key'] && !is_numeric($v['key']))
//                {
//                    ${$v['key']} = $v['value'];
//                }
//            }
            $dbdata['footer_info'] = serialize($this->input['footer_info']);
            
            $ret = $this->mode->update($id,'feedback',  $dbdata);
            $this->addItem($ret);
            $this->output();
            
        }
        public function save_other()
        {
            $id = intval($this->input['id']);
            if(!$id)
            {
                $this->errorOutput(NOID);
            }
            $updatedata = array(
                'is_login'     		=> $this->input['is_login'] ? 1 : 0,//是否需要登录
                'userid_limit_time'	=> $this->input['userid_limit_time'] > 0 ? $this->input['userid_limit_time'] : 0,//用户限制时间
                'userid_limit_num'	=> $this->input['is_login'] && $this->input['userid_limit_num'] > 0 ? intval($this->input['userid_limit_num']) : 1,//用户限制次数
                'is_ip'    		=> $this->input['is_ip'] ? 1 : 0,
                'ip_limit_time'		=> $this->input['ip_limit_time'] > 0 ? $this->input['ip_limit_time'] : 0,// ip限制时间
                'ip_limit_num'		=> $this->input['is_ip'] && $this->input['ip_limit_num'] > 0 ? intval($this->input['ip_limit_num']) : 1,//ip限制次数
                'is_device'     	=> $this->input['is_device'] ? 1 : 0,
                'device_limit_time'	=> $this->input['device_limit_time'] > 0 ? $this->input['device_limit_time'] : 0,//设备限制时间
                'device_limit_num'	=> $this->input['is_device'] && $this->input['device_limit_num'] > 0 ? intval($this->input['device_limit_num']) : 1,//设备限制次数
                'is_verifycode'         => $this->input['is_verifycode'] ? 1 : 0,
                'verifycode_type'       => $this->input['is_verifycode'] ? intval($this->input['verifycode_type']) : 0,
                'is_credit'    		=> $this->input['is_credit'] ? 1 : 0,
                'credit1'   	   	=> $this->input['is_credit'] ? intval($this->input['credit1']) : 0,
                'credit2'   	   	=> $this->input['is_credit'] ? intval($this->input['credit2']) : 0,
                'remark'   	   	=> trim($this->input['remark']),//备注
                //'start_time'   		=> strtotime($this->input['start_time']),
                //'end_time'     		=> strtotime($this->input['end_time']),
                'page_title'   		=> trim($this->input['page_title']),//页面标题header
                'jump_to'	   	=> trim($this->input['jump_to']),//网页打开的时候自动跳转
                'node_id'      		=> intval($this->input['node_id']),
                'admin_user'		=> trim($this->input['admin_user']),//单独权限
                'submit_text'		=> trim($this->input['submit_text']),
                'is_reply'              => $this->input['is_reply'] ? 1 : 0,
                'reupdate' 		=> 0,
                'template_id' 		=> intval($this->input['template_id']),//模板id
            );
            
            $ret = $this->mode->update($id,'feedback',  $updatedata);
            $this->addItem($ret);
            $this->output();
            
        }
        //创建表单组件
        public function create_component()
        {
            if(!isset($this->input['type']) || !isset($this->input['fid']) )
            {
                $this->errorOutput('缺少必要参数');
            }
            
            $type = trim($this->input['type']);
            $fid = intval($this->input['fid']);
            
            if(!in_array($type, array('standard','fixed')))
            {
                $this->errorOutput('组件类型不正确');
            }
            if($type == 'standard')
            {
            	$data[0] = $this->input;
                $sdata = $this->mode->process_standard($data, $fid);
                if($sdata['standard'][0])
                {
                	$vid = $this->mode->create($sdata['standard'][0],'standard',0);
                	$sdata['standard'][0]['id'] = $vid;
                }
                $sdata['common'][0] && $this->mode->create($sdata['common'][0],'common');
            }
            else
            {
                $fdata = $this->mode->process_fixed(array($this->input), $fid);
                if($fdata['fixed'][0])
                {
                	$vid = $this->mode->create($fdata['fixed'][0],'fixed',0);
                	$sdata['fixed'][0]['id'] = $vid;
                }
                $fdata['common'][0] && $this->mode->create($fdata['common'][0],'common');
            }
            $sdata[$type][0]['type'] = $type;
            $this->addItem($sdata[$type][0]);
            $this->output();
    }

    /**
     * @title 更新组件
     * @remark 只更新标准组件和固定组件
     */
    public function update_component()
    {
        
        if(!isset($this->input['id']) || !isset($this->input['type']) || !isset($this->input['fid'])  )
        {
            $this->errorOutput('缺少必须参数');
        }
        
        $id = intval($this->input['id']);
        $type = trim($this->input['type']);
        $fid = intval($this->input['fid']);
        
        $types = array('fixed','standard');
        if(!in_array($type, $types))
        {
            $this->errorOutput('更新组件类型错误');
        }
        
        $ret = array();
        if($type == 'standard')
        {
            $sdata = $this->mode->process_standard(array($this->input), $fid,1);
            $sdata['standard'] && $ret = $this->mode->update($id,'standard', $sdata['standard'][0]);
            $sdata['common'] && $this->mode->insert_datas('common', $sdata['common']);
        }
        else
        {
            $fdata = $this->mode->process_fixed(array($this->input), $fid);
            $fdata['fixed'] && $ret = $this->mode->update($id,'fixed', $fdata['fixed'][0]);
            $fdata['common'] && $this->mode->insert_datas('common', $fdata['common'],1);
        }
        
        if($ret['affected_rows'])
        {
            //更新feedback表
            if($this->user['user_id'] && $this->user['user_name'])
            {
                $sql = 'update '.DB_PREFIX.'feedback set update_user_id='.$this->user['user_id'].',update_user_name="'.$this->user['user_name'].'",update_time='.TIMENOW.' where id='.$fid;
                $this->db->query($sql);
            }
        }
        $this->addItem($ret);
        $this->output();
        
    }

    /**
     * 单次删除组件
     * @param $id 
     * @return 成功返回’success‘;失败返回错误码
     */
    public function delete_component()
    {
        if(!isset($this->input['id']) ||
           !isset($this->input['type']))
        {
            $this->errorOutput('缺少必须参数');
        }
        
        $id = intval($this->input['id']);
        $type = trim($this->input['type']);
        
        $types = array('fixed','standard');
        if(!in_array($type, $types))
        {
            $this->errorOutput('更新组件类型错误');
        }
        //删除组件表
        $sql = 'DELETE FROM ' .DB_PREFIX.$type.' WHERE id="'.$id.'"';
        $this->db->query($sql);
        
        $this->addItem('success');
        $this->output();
    }
    
    
   public function generate_callback()
   {
    	$id = $this->input['id'];
    	if(!$id)
    	{
    		$this->errorOutput(NOID);
    	}
    	$data = array(
    		'gen_status' => intval($this->input['gen_status']),
    		'gen_url'	 => $this->input['gen_status'] ? $this->input['gen_url'] : '',
    		'status'	 => intval($this->input['status']),
    		'reupdate'	 => intval($this->input['reupdate']),
    	);
    	$this->mode->update($id, 'feedback',$data);
     	$this->addItem($data);
    	$this->output();
    }
}

$out = new feedback_update();
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