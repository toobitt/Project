<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','adv');//模块标识
require(ROOT_PATH.'lib/class/curl.class.php');
class adv_content extends adminReadBase
{
	function __construct()
	{
		//权限设置数据
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'manage'	=>'管理',
		'put'	=>'投放',
		);
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index()
	{
		
	}
	function detail()
	{
		
	}
	//参数用于控制是否需要大图 默认缩略图 此处修改可能需要需要copy方法
	function show($large = false)
	{
		$this->verify_content_prms(array('_action'=>'show'));
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 10;
		$limit = " limit {$offset}, {$count}";
		//仅在弹出操作时候显示时间段
		if($this->input['id'])
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'adtime WHERE adid = '.intval($this->input['id']) . ' order by start_time ASC';
			$alltime = array();
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$alltime[$row['adid']][] = array('start_time'=>$row['start_time'] ? date('Y-m-d',$row['start_time']) : '', 'end_time'=>$row['end_time'] ? date('Y-m-d',$row['end_time']) : '');
			}
			
			//统计
			$sta = array();
			$sql = 'SELECT pu.pos_id,pu.group,pu.pos_flag,sta.*,pu.id FROM '.DB_PREFIX.'advpub pu LEFT JOIN '.DB_PREFIX.'advcontent co ON pu.ad_id = co.id LEFT JOIN '.DB_PREFIX.'statistics sta ON pu.id = sta.pubid WHERE pu.ad_id = '.intval($this->input['id']);
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$row['click'] 		= intval($row['click']);
				$row['output'] 		= intval($row['output']);
				$sta_index 			= $row['group'].'_'.$row['pos_flag'];
				$sta[$sta_index] 	= $row;
			}
		}
		//获取条件参数
		$condition = $this->get_condition();
		if($this->input['_id'])
		{
			$sql = 'SELECT co.*,cu.customer_name, st.output,st.click FROM '.DB_PREFIX.'advpub pu LEFT JOIN '.DB_PREFIX.'advcontent co ON co.id = pu.ad_id LEFT JOIN '.DB_PREFIX.'advcustomer cu ON cu.id = co.source LEFT JOIN '.DB_PREFIX.'advgroup g ON pu.group = g.flag LEFT JOIN '.DB_PREFIX.'statistics st ON st.pubid = pu.id WHERE 1'.$condition.' ORDER BY order_id DESC '.$limit;
		}
		else
		{
			$sql = 'SELECT co.*,cu.	customer_name FROM '.DB_PREFIX.'advcontent co LEFT JOIN '.DB_PREFIX.'advcustomer cu ON cu.id = co.source WHERE 1'.$condition.' ORDER BY order_id DESC '.$limit;
		}
		$query = $this->db->query($sql);
		$this->setXmlNode('adv_contents','adv_content');
		//读取广告数据
		while($r = $this->db->fetch_array($query))
		{
			if($large)
			{
				$r['murl'] 	= format_ad_material($r['material'],$r['mtype'],'380', '330');
			}
			else
			{
				$r['murl'] 	= format_ad_material($r['material'],$r['mtype'], '40', '30');
			}
			if($r['mtype'] 	== 'video')
			{
				$r['vurl'] 	= format_ad_video($r['material']);
			}
			$r['material'] = unserialize($r['material'])?unserialize($r['material']):array();
			$r['distribution'] = unserialize($r['distribution']);
			if($this->input['id'])
			{
				$r['alltime'] 	= $alltime[$r['id']];
				$r['statistic'] = $sta; 
			}
			$r['output'] 		= intval($r['output']);
			$r['click'] 		= intval($r['click']);
			$r['create_time'] 	= date('Y-m-d h:i',$r['create_time']);
			$r['start_time'] 	= $r['start_time'] ? date('Y-m-d',$r['start_time']) : '';
			$r['end_time'] 		= $r['end_time'] ? date('Y-m-d',$r['end_time']) : '';
			$this->addItem($r);
		}
		$this->output();
	}
	function get_condition()
	{
		$condition = '';
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND co.user_id = '.$this->user['user_id'];
			}
			else
			{
				//组织以内
				if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
				{
					$condition .= ' AND co.org_id IN('.$this->user['slave_org'].')';
				}
			}
		}
		if($this->input['_id'])
		{
			if(strstr($this->input['_id'], SPLIT_FLAG))
			{
				$tmp = explode(SPLIT_FLAG, $this->input['_id']);
				$condition .= ' AND pu.pos_id ="'.$tmp[1].'" AND pu.group="'.$tmp[0].'"';
			}
			else
			{
				$condition .= ' AND pu.group ="'.$this->input['_id'].'"';
			}
		}
		if($this->input['k'])
		{
			$condition .= ' AND co.title LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND co.id = '.intval($this->input['id']);
		}
		if(isset($this->input['status']) && $this->input['status'] != -1)
		{
			$condition .= ' AND co.status = '.intval($this->input['status']);
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND co.create_time >= '".$start_time."'";
		}
		if($this->input['customer'])
		{
			$condition .= " AND co.source = ".intval($this->input['customer']);
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND co.create_time <= '".$end_time."'";
		}
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  co.create_time > '".$yesterday."' AND co.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  co.create_time > '".$today."' AND co.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  co.create_time > '".$last_threeday."' AND co.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  co.create_time > '".$last_sevenday."' AND co.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	function count()
	{
		if(!$this->input['_id'])
		{
			$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'advcontent '.$this->get_condition();
		}
		else
		{
			$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'advpub pu LEFT JOIN '.DB_PREFIX.'advcontent co ON co.id = pu.ad_id  LEFT JOIN '.DB_PREFIX.'advgroup g ON pu.group = g.flag WHERE 1'.$this->get_condition();
		}
		echo json_encode($this->db->query_first($sql));
		
		
	}
	//广告第一步内容编辑操作
	function detail_content($ad = 0)
	{
		if($this->input['latest'])
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'advcontent ORDER BY id DESC LIMIT 1';
		}
		else if($ad)
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'advcontent WHERE id = '.intval($ad);
		}
		else
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'advcontent WHERE id = '.intval($this->input['id']);
		}
		$r = $this->db->query_first($sql);
		$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
		$r['start_time'] =  $r['start_time'] ? date('Y-m-d', $r['start_time']) : '';;
		$r['end_time'] = $r['end_time'] ? date('Y-m-d', $r['end_time']) : '';
		$r['murl'] = format_ad_material($r['material'], $r['mtype']);
		$q = $this->db->query("SELECT * FROM ".DB_PREFIX.'adtime WHERE adid='.intval($this->input['id']));
		while($row = $this->db->fetch_array($q))
		{
			$r['pub_time']['start_time'][] =  $row['start_time'] ? date('Y-m-d H:00', $row['start_time']) : '';
			$r['pub_time']['end_time'][] =  $row['end_time'] ? date('Y-m-d H:00', $row['end_time']) : '';
		}
		if(intval($ad))
		{
			return $r;
		}
		$this->addItem($r);
		$this->output();
	}
	//广告第二步发布编辑操作
	function detail_publish()
	{
		$this->verify_content_prms(array('_action'=>'put'));
		$sql = 'SELECT * FROM '.DB_PREFIX.'advpub WHERE ad_id = '.intval($this->input['content_id']);
		$q = $this->db->query($sql);
		$formdata = $group = $pos_ids = $ani_ids = array();
		//获取广告内容
		$formdata['ad_content'] = $this->detail_content(intval($this->input['content_id']));
		while($row = $this->db->fetch_array($q))
		{
			$formdata[$row['id']] = array('group'=>$row['group'],'pos_id'=>$row['pos_id'],'ani_id'=>$row['ani_id'],'param'=>unserialize($row['param']));
			$group[] = $row['group'];
			$pos_ids[] = $row['pos_id'];
			$ani_ids[] = $row['ani_id'];
		}
		//编辑获取客户端对应的广告位
		$advpos = $this->getadvpos($group);
		//编辑获取广告位的中文参数
		$pospara = $this->getadvpara($pos_ids);
		//编辑获取广告效果参数
		$anipara = $this->get_animation($ani_ids);
		if($formdata)
		{
			foreach($formdata as $k=>$v)
			{
				$formdata[$k]['advpos'] = $advpos[$v['group']];
				//广告位
				$formdata[$k]['zh_advpara'][$v['pos_id']] = $pospara[$v['pos_id']];
				//广告效果
				$formdata[$k]['zh_anipara'][$v['pos_id']] = $anipara[$v['ani_id']];
			}
		}
		$this->addItem($formdata);
		$this->output();
	}
	//广告高级选项
	function advanced_settings()
	{	
		$groupflag = trim(urldecode($this->input['groupflag']));
		$policy = $conditions_values = array();
		if($this->input['edit'] && $groupflag && $this->input['pos_id'])
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'advpub WHERE ad_id = '.intval($this->input['edit']).' AND  pos_id = '.intval($this->input['pos_id']).' AND `group` in ("'.$groupflag.'")';
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$conditions_values[$row['group']]['con'] = $row['conditions'] ? @unserialize($row['conditions']) : array();
				$conditions_values[$row['group']]['col'] = $row['columnid'] ? @unserialize($row['columnid']) : array();
			}
		}
		if(!$groupflag)
		{
			return;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'advgroup WHERE flag in ("'.$groupflag.'")';
		//exit($sql);
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$policy[$row['flag']]['field'] = @unserialize($row['policy']);
			$policy[$row['flag']]['value'] = $conditions_values[$row['flag']]['con'];
			$policy[$row['flag']]['col'] = $conditions_values[$row['flag']]['col'];
		}
		$this->addItem($policy);
		$this->output();
	}
	function getgroup()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'advgroup WHERE is_use=1 ' . $conditions;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$group[$row['flag']] = $row['name'];
		}
		$this->addItem($group);
		$this->output();
	}
	//二级联动 广告客户端获取广告位
	//参数是为了编辑时显示已选中的客户端的广告位 所以这里做了小的处理
	function getadvpos($group = array())
	{
		//
		$advpos = array();
		$groups = $this->input['flag'] ? trim(urldecode($this->input['flag'])) : @implode('","', $group); 
		if(!$groups)
		{
			return $advpos;
		}
		$auth_node = get_auth_group_or_pos(MOD_UNIQUEID, 'publish', $this->user);
		$sql = 'SELECT gp.pos_id,p.zh_name,gp.group_flag FROM '.DB_PREFIX.'group_pos gp LEFT JOIN '.DB_PREFIX.'advpos p ON gp.pos_id=p.id WHERE p.is_use=1 AND gp.group_flag IN("'.$groups.'")';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			if($auth_node)
			{
				if(!in_array($row['group_flag'], (array)$auth_node['first']) && !in_array($row['pos_id'], (array)$auth_node['three'][$row['group_flag']]))
				{
					continue;
				}
			}
			$advpos[$row['group_flag']][$row['pos_id']] = $row['zh_name'];
		}
		//ajax请求根据客户端返回广告位
		if($this->input['flag'])
		{
			$this->addItem($advpos[$this->input['flag']]);
			$this->output();
		}
		return $advpos;
	}
	//ajax获取广告位和效果参数
	//参数是为了编辑时显示广告位参数中文名称和标志 所以这里做了小的处理 同广告处理
	function getadvpara($pos_ids = array())
	{
		if(!$this->input['pid'] && !$pos_ids)
		{
			return;
		}
		$sql = 'SELECT ani_id FROM '.DB_PREFIX.'advpos WHERE id = '.intval($this->input['pid']);
		$posinfo = $this->db->query_first($sql);
		if($posinfo['ani_id'] == 1 && !$this->input['ani_id'])
		{
			$this->get_animation();
		}
		//
		if($this->input['ani_id'])
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'animation WHERE id = '.intval($this->input['ani_id']);
			$ani = $this->db->query_first($sql);
		}
		$ids = $this->input['pid'] ? intval($this->input['pid']) : @implode(',', $pos_ids);
		$sql = 'SELECT * FROM '.DB_PREFIX.'advpos  WHERE id IN('.$ids.')';
		$q= $this->db->query($sql);
		while($para = $this->db->fetch_array($q))
		{
			$return[$para['id']][0] = unserialize($para['para']);
			$return[$para['id']][1] = unserialize($para['form_style']);
			if($apara = unserialize($ani['para']))
			{
				//合并效果和广告位的表单表现样式
				$return[$para['id']][1] = array_merge((array)$return[$para['id']][1], (array)unserialize($ani['form_style']));
				//广告位效果参数添加到广告位参数
				foreach($apara as $k=>$v)
				{
					$return[$para['id']][0][$k]=$v;
				}
			}
		}
		if($this->input['pid'])
		{
			$this->addItem($return);
			$this->output();
		}
		return $return;
	}
	function get_animation($ani_ids = array())
	{
		//创建发布策略调用
		if(!$ani_ids && $this->input['pid'])
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'advpos WHERE id = '.intval($this->input['pid']);
			$pos = $this->db->query_first($sql);
			if($pos['ani_id'])
			{
				$page = $this->input['page'] ? intval($this->input['page']) : 0;
				$count = 5;
				$offset = $page * $count;
				$limit = " limit {$offset}, {$count}";
				if(urldecode($this->input['condition']))
				{
					$condition = " AND name LIKE '%".urldecode($this->input['condition']).'%\'';
				}
				$sql = 'SELECT * FROM '.DB_PREFIX.'animation WHERE float_fixed = '.intval($pos['ani_id']) . $condition .$limit;
				//file_put_contents('1.txt', $sql);
				$q = $this->db->query($sql);
				$animation = array();
				while($row = $this->db->fetch_array($q))
				{
					$animation[-1][] = $row;
				}
			}
			$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'animation WHERE float_fixed = '.intval($pos['ani_id']) . $condition;
			$total = $this->db->query_first($sql);
			$animation['total'] = $total['total'];
			$tp = ceil($total['total']/$count);
			$animation['nextpage']  = (($page+1) < $tp) ? $page+1 : ($tp-1);
			$animation['prepage']  = (($page-1 <= 0) ? 0 : $page-1);
			$animation['tp'] = $tp;
			$animation['cp'] = $page+1;
			$animation['__tpl__'] = 'true';//区分模板用;
			$animation['pid'] = intval($this->input['pid']);
			$animation['id'] = $this->input['id'];
			$animation['condition'] = urldecode($this->input['condition']);
			$this->addItem($animation);
			$this->output();
		}
		else
		{
			//编辑时候调用
			$return = array();
			if($ani_ids)
			{
				$sql = 'SELECT * FROM '.DB_PREFIX.'animation WHERE id IN('.implode(',', $ani_ids).')';
				$q = $this->db->query($sql);
				while($row = $this->db->fetch_array($q))
				{
					$row['para'] = unserialize($row['para']);
					$row['form_style'] = unserialize($row['form_style']);
					$return[$row['id']] = $row;
				}
				return $return;
			}
		}
	}
	//点击单条广告操作
	function show_opration()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$this->show(true);
	}

	//获取广告客户
	function getAllCustomer()
	{
		$query = $this->db->query('SELECT * FROM '.DB_PREFIX.'advcustomer order by create_time desc');
		$customers = array();
		while($row = $this->db->fetch_array($query))
		{
			$customers[$row['id']] = $row['customer_name'];
		}
		$this->addItem($customers);
		$this->output();
	}
	//复制广告
	function copy()
	{
		$adid = intval($this->input['id']);
		if(!$adid)
		{
			return;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'advcontent WHERE id='.$adid;
		$ad = $this->db->query_first($sql);
		if($ad)
		{
			//记录复制的广告ID  默认是0 代表原创
			$iscopy = $ad['iscopy'] = $ad['id'];
			$sql = 'INSERT INTO '.DB_PREFIX.'advcontent SET 
			title="'.$ad['title'].'",
			order_id="'.$ad['order_id'].'",
			brief="'.$ad['brief'].'",
			link="'.$ad['link'].'",
			mtype="'.$ad['mtype'].'",
			source="'.$ad['source'].'",
			start_time = "'.TIMENOW.'",
			status="1",
			material=\''.addslashes($ad['material']).'\',
			ip="'.hg_getip().'",
			iscopy="'.$iscopy.'",
			create_time="'.TIMENOW.'",
			user_id="'.$this->user['user_id'].'",
			org_id="'.$this->user['org_id'].'",
			user_name="'.$this->user['user_name'].'"
			';
			$this->db->query($sql);
			$ad['id'] = $this->db->insert_id();
			$this->db->query("UPDATE " . DB_PREFIX . 'advcontent SET order_id='.$ad['id'] . '  WHERE id='.$ad['id']);
			$this->addLogs('复制广告', '', $ad, $ad['title']);
			include_once '../lib/adfunc.class.php';
			$adfunc = new adfunc();
			$adfunc->insert_ad_time(array(),array(),$ad['id']);
			$this->input['id'] = $ad['id'];
			$this->show();
		}
	}
	//广告简单预览
	function adpreview()
	{
		if(!($id = intval($this->input['content_id'])))
		{
			$this->errorOutput(NOID);
		}
		$data = array();
		$sql = 'SELECT id,pos_id,pos_flag,`group`,ani_id FROM '.DB_PREFIX.'advpub WHERE ad_id = '.$id;
		$q = $this->db->query($sql);
		$current_pos = 0;
		while($row = $this->db->fetch_array($q))
		{
			$data[$row['group']][$row['pos_flag']] = $row['id'];
			$_posids .= $row['pos_id'] . ',';
			if($this->input['pub_id'] && ($this->input['pub_id'] == $row['id']))
			{
				//选择具体广告位进行预览
				$group_flag = $row['group'];
				$current_pos = $row['pos_id'];
			}
		}
		if($data)
		{
			$sql = 'SELECT id,name,zh_name, group_flag,ani_id FROM '.DB_PREFIX.'advpos WHERE id IN('.trim($_posids,',').')';
			$q = $this->db->query($sql);
			$group_name= $pos_name = array();
			$ani_id = -1;
			while($row = $this->db->fetch_array($q))
			{
				$group_name = array_merge($group_name, unserialize($row['group_flag']));
				$pos_name[$row['name']] = $row['zh_name'];
				if($current_pos == $row['id'])
				{
					$ani_id = intval($row['ani_id']);
				}
			}
			$_data = array('pos_type'=>$ani_id,'group_flag'=>$group_flag, 'currentpos'=>$current_pos);
			foreach($data as $k=>$v)
			{
				foreach($v as $kk=>$vv)
				{
					$_data[$group_name[$k]][$pos_name[$kk]] = $vv;
				}
			}
		}
		$this->addItem($_data);
		$this->output();
	}
}
$ouput= new adv_content();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
