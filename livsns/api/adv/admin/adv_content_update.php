<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
require_once('../lib/functions.php');
define('MOD_UNIQUEID','adv');//模块标识
require_once(ROOT_PATH.'lib/class/curl.class.php');
class adv_content_update extends adminUpdateBase
{
	private $curl;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
		include_once '../lib/adfunc.class.php';
		$this->adfunc = new adfunc();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function create()
	{
		
	}
	function sort()
	{
		$ret = $this->drag_order('advcontent', 'order_id');
		$this->addItem($ret);
		$this->output();
	}
	function publish()
	{
		
	}
	function update()
	{
		
	}
	function delete()
	{
		if(!$this->input['id'])
		{
			return;
		}
		$ids = trim(urldecode($this->input['id']));
		//放入回收箱开始
		$sql = "SELECT * FROM " . DB_PREFIX . "advcontent WHERE id in (".$ids.")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['title'],
					'cid' => $row['id'],
					'org_id'=>$row['org_id'],
					'user_id'=>$row['user_id'],
			);
			$this->addLogs('删除广告', $row, array(), $row['title']);
			$data2[$row['id']]['content']['adv_content'] = $row;
		}
		foreach ($data2 as $id=>$v)
		{
			$this->verify_content_prms(array('_action'=>'manage','id'=>$id,'user_id'=>$v['user_id'],'org_id'=>$v['org_id']));
		}
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		if($res['sucess'])
		{
			//删除发布策略关联数据
			$sql = 'DELETE FROM '.DB_PREFIX.'advpub WHERE ad_id IN('.$ids.')';
			$this->db->query($sql);
			$sql = 'DELETE FROM '.DB_PREFIX.'advcontent WHERE id in('.$ids.')';
			$this->db->query($sql);
			$sql = 'DELETE FROM '.DB_PREFIX.'adtime WHERE adid in('.$ids.')';
			$this->db->query($sql);
			$this->addItem('success');
			$this->output();
		}
	}
	//彻底删除
	public function delete_comp()
	{
		if(empty($this->input['cid']))
		{
			return false;
		}
		$ids = urldecode($this->input['cid']);
		//删除物理文件 视频调用视频删除接口 其余调用delete_ad_material方法删除
		$sql  = 'SELECT * FROM '.DB_PREFIX.'advcontent WHERE id in('.$ids.')';
		$q = $this->db->query($sql);
		$delete_ad_material_ids = array();
		while($r = $this->db->fetch_array($q))
		{
			if($r['type'] == '.video')
			{
				$this->delete_video($r['material']);
			}
			else
			{
				$delete_ad_material_ids[]=$r['id'];
			}
		}
		if($delete_ad_material_ids)
		{
			$this->delete_ad_material($delete_ad_material_ids);
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'advpub WHERE ad_id in('.$ids.')';
		$q = $this->db->query($sql);
		$pos_ids = $count = array();
		while($r = $this->db->fetch_array($q))
		{
			$count[$r['pos_id']][$r['group']]++;
			$pos_ids[] = $r['pos_id'];
			$ad_ids[] = $r['ad_id'];
		}
		//修改分组广告位映射表
		$sql = 'UPDATE '.DB_PREFIX.'group_pos SET `count` = CASE ';
		if($count)
		{
			foreach ($count as $k=>$v)
			{
				foreach ($v as $kk=>$vv)
				{
					$sql .= ' WHEN pos_id = '.$k;
					$sql .= ' AND group_flag = "'.$kk.'" THEN `count` - '.$vv;
				}
			}
			$sql .=  ' ELSE `count` END WHERE pos_id in('.implode(',', array_unique($pos_ids)).')';
			$this->db->query($sql);
		}
		//删除发布策略数据
		$pos_ids = $pos_ids ? implode(',', $pos_ids) : 0;
		$sql = 'DELETE FROM '.DB_PREFIX.'advpub WHERE ad_id in('.$ids.')';
		$this->db->query($sql);
		if($pos_ids)
		{
			$this->rebuild_group_used($pos_ids);
		}
		
		return $ids;
	}
	private function delete_video($video_id)
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('id',$video_id);
		$this->curl->request('delete.php');
	}
	private function delete_ad_material($content_id)
	{
		if(!$content_id)
		{
			return;
		}
		if(is_array($content_id))
		{
			$content_id = implode(',', $content_id);
		}
		$sql = 'SELECT id,material FROM '.DB_PREFIX.'advcontent WHERE id IN('.$content_id.')';
		$q = $this->db->query($sql);
		$total = array();
		while($row = $this->db->fetch_array($q))
		{
			$total[$row['id']] = $row['material'];
		}
		$_total = array_count_values($total);
		$content_id = explode(',', $content_id);
	}
	function audit()
	{
	}
	
	//策略数据创建
	function create_policy()
	{
		$adid = intval($this->input['content_id']);
		//$this->verify_content_prms(array('_action'=>'publish','id'=>$adid,'org_id'=>$this->user['org_id'],'user_id'=>$this->user['user_id']),true);
		$sql = 'SELECT id,org_id,user_id,title FROM '.DB_PREFIX.'advcontent WHERE id = '.$adid;
		$record = $this->db->query_first($sql);
		if(!$record)
		{
			$this->errorOutput(NO_CONTENT);
		}
		$this->verify_content_prms(array('_action'=>'put','id'=>$record['id'],'org_id'=>$record['org_id'],'user_id'=>$record['user_id']));
		//广告发布重复性检测
		if($this->input['advpub'])
		{
			$_advpub = is_array($this->input['advpub']) ? $this->input['advpub'] : (array)$this->input['advpub'];
			$_distribution = array();
			foreach($_advpub as $v)
			{
				$_tmp = trim(urldecode($this->input['group'][$v])).intval($this->input['advpos'][$v]);
				if(in_array($_tmp, $_distribution))
				{
					$this->errorOutput('相同的广告位不可以重复投放广告！');
				}
				$_distribution[] = $_tmp;
			}
		}
		
		//发布策略高级选项数据处理
		$conditions = $column = array();
		if($this->input['group'] && is_array($this->input['group']))
		{
			foreach($this->input['group'] as $index=>$f)
			{
				$field = $f.'fields_'.$index;
				if($this->input[$field])
				{
					foreach($this->input[$field] as $k=>$v)
					{
						$con = $f.'con_'.$v;
						$val = $f.'value_'.$index;
						if($this->input[$con][$index] && $this->input[$val][$k])
						{
							$v = trim(urldecode($v));
							$c = trim(urldecode($this->input[$con][$index]));
							$conditions[$index][$v] = array('simbol'=>$c,'value'=>trim(urldecode($this->input[$val][$k])));
						}
					}
				}
				$coltype = intval(@array_sum($this->input[$f.'col_'.$index.'_attr']));
				$coltype = $coltype ? $coltype : 1;
				$_col_id = @array_filter(explode(',',$this->input[$f.'col_'.$index]));
				$_col_name = explode(',',$this->input[$f.'col_'.$index.'_name']);
				$_column_settins = array();
				if($_col_id)
				{
					foreach($_col_id as $key=>$val)
					{
						$_column_settins[$val] = $_col_name[$key];
					}
				}
				$column[$index] =$_column_settins ? array($coltype=>$_column_settins) : array();
			}
		}
		
		//发布策略数据处理
		if($this->input['advpub'])
		{
			$advpub = is_array($this->input['advpub']) ? $this->input['advpub'] : (array)$this->input['advpub'];
			$pos_ids = $distribution = array();
			foreach($advpub as $v)
			{
				$data = array(
				'ad_id' => $adid,
				'pos_id'=>intval(trim(urldecode($this->input['advpos'][$v]))),
				'ani_id'=>intval(trim(urldecode($this->input['ani_id'][$v]))),
				'conditions'=>serialize($conditions[$v] ? $conditions[$v] : array()),
				'columnid'=>$column[$v] ? serialize($column[$v]) : '',
				'group_flag'=>$this->input['group'][$v],
				'pos_flag'=>'',
				);
				if(!$data['ad_id'] || !$data['pos_id'])
				{
					continue;
				}
				//取出广告位参数 遍历赋值
				$sql = 'SELECT * FROM '.DB_PREFIX.'advpos WHERE id = '.intval($data['pos_id']);
				$pos = $this->db->query_first($sql);
				$para = array();
				if($pos['para'])
				{
					//广告发布表中冗余广告位标志
					$data['pos_flag'] = $pos['name'];
					//存放于二位数组$para['pos']中
					foreach (unserialize($pos['para']) as $kk=>$vv)
					{
						if($this->input['sec_'.$kk][$v])
						{
							$this->input[$kk][$v] = intval($this->input[$kk][$v])*1000;
						}
						if($this->input['period_'.$kk][$v])
						{
							$this->input[$kk][$v] = $this->input[$kk][$v] . '@' .trim($this->input['period_'.$kk][$v]);
							if(in_array($this->input['period_'.$kk][$v], array(6,7,8)))
							{
								$this->input[$kk][$v] .= '@' .trim($this->input['value_period_'.$kk][$v]);
							}
							else
							{
								$this->input[$kk][$v] .= '@';
							}
							$this->input[$kk][$v] .= '@'.TIMENOW;
						}
						$para['pos'][$kk] = trim($this->input[$kk][$v]);
					}
					//优先级和权重单独处理 注意这里的下标是固定的
					if($pos['weight'])
					{
						$para['pos']['weight'] = trim(urldecode($this->input['weight'][$v]));
					}
					if($pos['priority'])
					{
						$para['pos']['priority'] = trim(urldecode($this->input['priority'][$v]));
					}
				}
				//广告内容数据处理并入库
				//$adv_content_id  = $this->create_content($pos['zh_name']);
				//$data['ad_id'] = $adv_content_id;
				//取出广告位效果参数 遍历赋值
				$sql = 'SELECT * FROM '.DB_PREFIX.'animation WHERE id = '.intval($data['ani_id']);
				$ani = $this->db->query_first($sql);
				if($ani['para'])
				{
					//存放于二位数组$para['ani']中
					foreach (unserialize($ani['para']) as $kk=>$vv)
					{
						if($this->input['sec_'.$kk][$v])
						{
							$this->input[$kk][$v] = intval($this->input[$kk][$v])*1000;
						}
						$para['ani'][$kk] = trim(urldecode($this->input[$kk][$v]));
					}
				}
				$data['para'] = serialize($para);
				//发布策略数据写入
				if($data['group_flag'])
				{
					//发布到多个分组 以多条记录写入数据库
					$sql = 'INSERT INTO '.DB_PREFIX.'advpub SET ad_id = "'.$data['ad_id'].'",'.
					'pos_id = "'.$data['pos_id'].'",'.
					'ani_id = "'.$data['ani_id'].'",'.
					'pos_flag = "'.$data['pos_flag'].'",'.
					'param = \''.$data['para'].'\','.
					'conditions = \''.$data['conditions'].'\','.
					'columnid = \''.$data['columnid'].'\',';
					$sql .= '`group` = "'.$data['group_flag'].'"';
					//发送sql查询语句
					$this->db->query($sql);
					
					//广告位所发布的分组 总数自增1
					$sql_update = 'UPDATE '.DB_PREFIX.'group_pos SET `count`=`count`+1 WHERE pos_id='
					.$data['pos_id'] .' AND group_flag in("'.$data['group_flag'].'")';
					$this->db->query($sql_update);
				}
				$pos_ids[] = $data['pos_id'];
				$tmp = array();
				$tmp = @unserialize($pos['group_flag']);
				$distribution[] = array($data['group_flag']=>$tmp[$data['group_flag']],$pos['name']=>$pos['zh_name']);
			}
			//触发更新pos表中的group_used字段 保持统一
			$this->rebuild_group_used($pos_ids);
			if($distribution)
			{
				$sql = 'UPDATE '.DB_PREFIX.'advcontent SET distribution = \''.serialize($distribution).'\' WHERE id = '.$data['ad_id'];
				$this->db->query($sql);
			}
		}
		$this->addLogs('发布广告', '', '', $record['title']);
		$this->addItem(array('a'=>'advanced_settings', 'content_id'=>intval($this->input['content_id'])));
		$this->output();
	}
	function update_policy()
	{
		$aid = $this->input['content_id'] ? intval($this->input['content_id']) : $this->input['ad_id'] ? intval($this->input['ad_id']) : 0;
		$sql = 'SELECT id,org_id,user_id,title FROM '.DB_PREFIX.'advcontent WHERE id = '.$aid;
		$record = $this->db->query_first($sql);
		if(!$record)
		{
			$this->errorOutput(NO_CONTENT);
		}
		$this->verify_content_prms(array('_action'=>'put','id'=>$record['id'],'org_id'=>$record['org_id'],'user_id'=>$record['user_id']),true);
		//重复性检测
		if($this->input['advpub'])
		{
			$_advpub = is_array($this->input['advpub']) ? $this->input['advpub'] : (array)$this->input['advpub'];
			$_distribution = array();
			foreach($_advpub as $v)
			{
				$_tmp = trim(urldecode($this->input['group'][$v])).intval($this->input['advpos'][$v]);
				if(in_array($_tmp, $_distribution))
				{
					$this->errorOutput('相同的广告位不可以重复投放广告！');
				}
				$_distribution[] = $_tmp;
			}
		}
		//取出此广告已发布策略数据
		if(!$aid)
		{
			$this->errorOutput(NOID);
		}
		//发布策略高级选项数据处理
		$conditions = $column = array();
		if($this->input['group'] && is_array($this->input['group']))
		{
			foreach($this->input['group'] as $index=>$f)
			{
				$field = $f.'fields_'.$index;
				if($this->input[$field])
				{
					foreach($this->input[$field] as $k=>$v)
					{
						$con = $f.'con_'.$v;
						$val = $f.'value_'.$index;
						if($this->input[$con][$index] && $this->input[$val][$k])
						{
							$v = trim(urldecode($v));
							$c = trim(urldecode($this->input[$con][$index]));
							$conditions[$index][$v] = array('simbol'=>$c,'value'=>trim(urldecode($this->input[$val][$k])));
						}
					}
				}
				if($this->input['needupdate'][$index])
				{
					$coltype = intval(@array_sum($this->input[$f.'col_'.$index.'_attr']));
					$coltype = $coltype ? $coltype : 1;
					$_col_id = @array_filter(explode(',',$this->input[$f.'col_'.$index]));
					$_col_name = explode(',',$this->input[$f.'col_'.$index.'_name']);
					$_column_settins = array();
					if($_col_id)
					{
						foreach($_col_id as $key=>$val)
						{
							$_column_settins[$val] = $_col_name[$key];
						}
					}
					$column[$index] = $_column_settins ? array($coltype=>$_column_settins) : array();
				}
			}
		}
		//$this->errorOutput(var_export($column,1));
		//file_put_contents('1.txt', var_export($column,1));
		$sql = 'SELECT * FROM '.DB_PREFIX.'advpub WHERE ad_id = '.$aid;
		$q = $this->db->query($sql);
		$haspublished = $publishinfo = $distribution = array();
		while($row = $this->db->fetch_array($q))
		{
			$haspublished[] = $row['id'];
			//用于删除数据时提供信息 无其他用途
			$publishinfo[$row['id']] = $row;
		}
		$input_p = $this->input['advpub'] ? $this->input['advpub'] : array();
		//插入
		$insert = array_diff($input_p,$haspublished);
		$pos_ids  = array();
		if($insert)
		{
			foreach($insert as $v)
			{
				$data = array(
				'ad_id' => $aid,
				'pos_id'=>trim(urldecode($this->input['advpos'][$v])),
				'ani_id'=>trim(urldecode($this->input['ani_id'][$v])),
				'conditions'=>serialize($conditions[$v] ? $conditions[$v] : array()),
				'columnid'=>$column[$v] ? serialize($column[$v]) : '',
				'group_flag'=>$this->input['group'][$v],
				'pos_flag'=>'',
				);
				if(!$data['ad_id'] || !$data['pos_id'])
				{
					continue;
				}
				//取出广告位参数 遍历赋值
				$sql = 'SELECT * FROM '.DB_PREFIX.'advpos WHERE id = '.intval($data['pos_id']);
				$pos = $this->db->query_first($sql);
				$data['pos_flag'] = $pos['name'];
				$para = array();
				if($pos['para'])
				{
					//存放于二位数组$para['pos']中
					foreach (unserialize($pos['para']) as $kk=>$vv)
					{
						if($this->input['sec_'.$kk][$v])
						{
							$this->input[$kk][$v] = intval($this->input[$kk][$v])*1000;
						}
						if($this->input['period_'.$kk][$v])
						{
							$this->input[$kk][$v] = $this->input[$kk][$v] . '@' .trim($this->input['period_'.$kk][$v]);
							if(in_array($this->input['period_'.$kk][$v], array(6,7,8)))
							{
								$this->input[$kk][$v] .= '@' .trim($this->input['value_period_'.$kk][$v]);
							}
							else
							{
								$this->input[$kk][$v] .= '@';
							}
							$this->input[$kk][$v] .= '@'.TIMENOW;
						}
						$para['pos'][$kk] = trim(urldecode($this->input[$kk][$v]));
					}
				}
				//广告内容数据处理并入库
				//取出广告位效果参数 遍历赋值
				$sql = 'SELECT * FROM '.DB_PREFIX.'animation WHERE id = '.intval($data['ani_id']);
				$ani = $this->db->query_first($sql);
				if($ani['para'])
				{
					//存放于二位数组$para['ani']中
					foreach (unserialize($ani['para']) as $kk=>$vv)
					{
						if($this->input['sec_'.$kk][$v])
						{
							$this->input[$kk][$v] = intval($this->input[$kk][$v])*1000;
						}
						$para['ani'][$kk] = trim(urldecode($this->input[$kk][$v]));
					}
				}
				$data['para'] = serialize($para);
				//发布策略数据写入
				if($data['group_flag'])
				{
					//发布到多个分组 以多条记录写入数据库
					$sql = 'INSERT INTO '.DB_PREFIX.'advpub SET ad_id = "'.$data['ad_id'].'",'.
					'pos_id = "'.$data['pos_id'].'",'.
					'ani_id = "'.$data['ani_id'].'",'.
					'pos_flag = "'.$data['pos_flag'].'",'.
					'param = \''.$data['para'].'\','.
					'conditions = \''.$data['conditions'].'\','.
					'columnid = \''.$data['columnid'].'\',';
					$sql .= '`group` = "'.$data['group_flag'].'"';
					$this->db->query($sql);
					//广告位所发布的分组 总数自增1
					$sql_update = 'UPDATE '.DB_PREFIX.'group_pos SET `count`=`count`+1 WHERE pos_id='
					.$data['pos_id'] .' AND group_flag in("'.$data['group_flag'].'")';
					$this->db->query($sql_update);
				}
				$pos_ids[] = $data['pos_id'];
				$tmp = array();
				$tmp = @unserialize($pos['group_flag']);
				$distribution[] = array($data['group_flag']=>$tmp[$data['group_flag']],$pos['name']=>$pos['zh_name']);
				//$distribution[] = $data['group_flag'];
			}
		}
		//删除
		$delete = array_diff($haspublished,$input_p);
		if($delete)
		{
			foreach($delete as $pid)
			{
				$sql_update = 'UPDATE '.DB_PREFIX.'group_pos SET `count`=`count`-1 WHERE pos_id='
					.$publishinfo[$pid]['pos_id'] .' AND group_flag = "'.$publishinfo[$pid]['group'].'"';
				$this->db->query($sql_update);
				$pos_ids[] = $publishinfo[$pid]['pos_id'];
			}
			$sql = 'DELETE FROM '.DB_PREFIX.'advpub WHERE id IN('.implode(',', $delete).')';
			$this->db->query($sql);
		}
		//更新的策略数据 交集
		$update = array_intersect($haspublished, $input_p);
		if($update)
		{
			foreach($update as $v)
			{
				$data = array(
				'ad_id' => $aid,
				'pos_id'=>trim(urldecode($this->input['advpos'][$v])),
				'ani_id'=>trim(urldecode($this->input['ani_id'][$v])),
				'conditions'=>serialize($conditions[$v] ? $conditions[$v] : array()),
				'columnid'=>$column[$v] ? serialize($column[$v]) : '',
				'group_flag'=>$this->input['group'][$v]
				);
				//取出广告位参数 遍历赋值
				$sql = 'SELECT * FROM '.DB_PREFIX.'advpos WHERE id = '.intval($data['pos_id']);
				$pos = $this->db->query_first($sql);
				$data['pos_flag'] = $pos['name'];
				$para = array();
				if($pos['para'])
				{
					//存放于二位数组$para['pos']中
					foreach (unserialize($pos['para']) as $kk=>$vv)
					{
						if($this->input['sec_'.$kk][$v])
						{
							$this->input[$kk][$v] = intval($this->input[$kk][$v])*1000;
						}
						if($this->input['period_'.$kk][$v])
						{
							$this->input[$kk][$v] = $this->input[$kk][$v] . '@' .trim($this->input['period_'.$kk][$v]);
							if(in_array($this->input['period_'.$kk][$v], array(6,7,8)))
							{
								$this->input[$kk][$v] .= '@' .trim($this->input['value_period_'.$kk][$v]);
							}
							else
							{
								$this->input[$kk][$v] .= '@';
							}
							$this->input[$kk][$v] .= '@' . TIMENOW;
						}
						$para['pos'][$kk] = trim(urldecode($this->input[$kk][$v]));
					}
				}
				//取出广告位效果参数 遍历赋值
				$sql = 'SELECT * FROM '.DB_PREFIX.'animation WHERE id = '.intval($data['ani_id']);
				$ani = $this->db->query_first($sql);
				if($ani['para'])
				{
					//存放于二位数组$para['ani']中
					foreach (unserialize($ani['para']) as $kk=>$vv)
					{
						if($this->input['sec_'.$kk][$v])
						{
							$this->input[$kk][$v] = intval($this->input[$kk][$v])*1000;
						}
						$para['ani'][$kk] = trim(urldecode($this->input[$kk][$v]));
					}
				}
				$data['para'] = serialize($para);
				//发布策略数据更新
				if($data['group_flag'])
				{
					//发布到多个分组 以多条记录写入数据库
					$sql = 'UPDATE '.DB_PREFIX.'advpub SET ad_id = "'.$data['ad_id'].'",'.
					'pos_id = "'.$data['pos_id'].'",'.
					'ani_id = "'.$data['ani_id'].'",'.
					'pos_flag = "'.$data['pos_flag'].'",'.
					'param = \''.$data['para'].'\',';

					//判断是否需要更新高级选项 只要点击了高级选项按钮此处则为真
					if($this->input['needupdate'][$v])
					{
						$sql .= 'conditions = \''.$data['conditions'].'\','.
								'columnid = \''.$data['columnid'].'\',';
					}
					$sql .= '`group` = "'.$data['group_flag'].'" WHERE id = '.intval($v);
					$this->db->query($sql);

				}
				$pos_ids[] = $data['pos_id'];
				$tmp = array();
				$tmp = @unserialize($pos['group_flag']);
				$distribution[] = array($data['group_flag']=>$tmp[$data['group_flag']],$pos['name']=>$pos['zh_name']);
				//$distribution[] = $data['group_flag'];
			}
		}
		//修改该广告表中冗余字段广告位和发布分组数据
		if($distribution || $haspublished)
		{
			$sql = 'UPDATE '.DB_PREFIX.'advcontent SET distribution = \''.serialize($distribution).'\' WHERE id = '.$aid;
			$this->db->query($sql);
		}
		//修改group_used表中广告个数统计
		$this->rebuild_group_used($pos_ids);
		$this->addLogs('发布广告更新', '', '', $record['title']);
		$this->addItem(array('a'=>'advanced_settings', 'content_id'=>intval($this->input['content_id'])));
		$this->output();
	}
	//广告内容数据创建
	function create_content()
	{
		$this->verify_content_prms(array('_action'=>'manage'),true);
		//收集表单数据
		$data = array(
		'title' => trim($this->input['title']),
		'link'=>in_array(strtolower($this->input['link']), array('http://', 'https://'))? '' : trim($this->input['link']),
		'user_name'=>$this->user['user_name'],
		'create_time'=>TIMENOW,
		'brief'=>trim($this->input['brief']),
		'mtype'=>trim($this->input['mtype']),
		'user_id'=>$this->user['user_id'],
		'org_id'=>$this->user['org_id'],
		'priority'=>intval($this->input['priority']),
		'weight'=>'',
		'start_time'=>'',
		'end_time'=>'',
		'ip'=>hg_getip(),
		'source'=>trim(urldecode($this->input['source'])),
		);
		if(!$data['mtype'])
		{
			$this->errorOutput('无效的广告素材');
		}
		if($data['link'])
		{
			if(!preg_match('/^(http:\/\/|https:\/\/){1}/i', $data['link']) && !preg_match('/(.*?)#(.*?)/i', $data['link']))
			{
				$this->errorOutput(AD_LINK_ERROR);
			}
		}
		//判断是否勾选权重
		if($this->input['isvalidweight'])
		{
			$data['weight'] = intval(trim($this->input['weight']));
		}
		$data['material'] = $this->get_material_info();
		//表单提交名称为start开始时间和end结束时间
		$publishTime = get_ad_publishTime($this->input['start'], $this->input['end'], false);
		if($publishTime === false)
		{
			$this->errorOutput(AD_TIME_ERROR);
		}
		$data['status'] = get_ad_status($publishTime['start_time'], $publishTime['end_time']);
		$sql = 'INSERT INTO '.DB_PREFIX.'advcontent SET title = "'.$data['title'].'",'.
		'link = "'.$data['link'].'",'.
		'user_name = "'.$data['user_name'].'",'.
		'create_time = "'.$data['create_time'].'",'.
		'mtype = "'.$data['mtype'].'",'.
		'brief = "'.$data['brief'].'",'.
		'source = "'.$data['source'].'",'.
		'user_id = "'.$data['user_id'].'",'.
		'org_id = "'.$data['org_id'].'",'.
		'material = \''.$data['material'].'\','.
		'priority = "'.$data['priority'].'",'.
		'weight = "'.$data['weight'].'",'.
		'start_time = "'.$publishTime['start_time'].'",'.
		'end_time = "'.$publishTime['end_time'].'",'.
		'status = "'.$data['status'].'",'.
		'ip = "'.$data['ip'].'"';
		$this->db->query($sql);
		$adv_content_id = $this->db->insert_id();
		$data['id'] = $adv_content_id;
		$this->update_self_orderid($adv_content_id);
		$this->adfunc->insert_ad_time($this->input['start'], $this->input['end'], $adv_content_id);
		$this->addItem(array('content_id'=>$adv_content_id));
		$this->addLogs('创建广告', array(), $data, $data['title']);
		$this->output();
	}
	private function update_self_orderid($adid = 0)
	{
		$sql = 'UPDATE '.DB_PREFIX.'advcontent set order_id = '.$adid.' WHERE id = '.$adid;
		$this->db->query($sql);
	}
	function get_material_info()
	{
		$material = '';
		
		if(!$this->input['mtype'])
		{
			return $material;
		}
		switch($this->input['mtype'])
		{
			//flash和image上传到图片服务器
			case 'flash':
			case 'image':
				{
					$material = serialize($this->upload_attatch2server($this->input['material']));
					@unlink(CUR_CONF_PATH . 'data/tmp/' . basename($this->input['material']));
					break;	
				}
			//上传至视频服务器
			case 'video':
				{
					$material = serialize($this->getvideobyid($this->input['material']));
					break;
				}
			case 'javascript':
				{
					if($this->input['js_code'])
					{
						$material = addslashes($this->input['js_code']);
					}
					break;
				}
			case 'text':
				{
					$material = $this->input['brief'];
					break;
				}
			default:
				{
					$material = '';
				}
		}
		return $material;
	}
	//内容更新
	function update_content()
	{
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'advcontent WHERE id = '.intval($this->input['ad_id']);
		$ad = $this->db->query_first($sql);
		if(!$ad)
		{
			$this->errorOutput(NOID);
		}
		$data = array(
		'id'=>intval($this->input['ad_id']),
		'title' => trim($this->input['title']),
		'link'=>in_array(strtolower($this->input['link']), array('http://', 'https://'))? '' : trim($this->input['link']),
		'brief'=>trim($this->input['brief']),
		'source'=>trim($this->input['source']),
		'org_id'=>$this->user['org_id'],
		'priority'=>intval(trim($this->input['priority'])),
		'ip'=>hg_getip(),
		'weight'=>'',
		'end_time'=>'',
		'start_time'=>'',
		);
		if(!$this->input['mtype'])
		{
			$this->errorOutput('无效的广告素材');
		}
		
		if($data['link'])
		{
			if(!preg_match('/^(http:\/\/|https:\/\/){1}/i', $data['link']) && !preg_match('/(.*?)#(.*?)/i', $data['link']))
			{
				$this->errorOutput(AD_LINK_ERROR);
			}
		}
		if($this->input['isvalidweight'])
		{
			$data['weight'] = intval(trim($this->input['weight']));
		}
		$update_material_sql = '';
		if($this->input['material'] || $this->input['js_code'] || $this->input['ad_text'])
		{
			//如果更新素材信息
			$data['mtype'] = $this->input['mtype'];
			$data['material'] = $this->get_material_info($this->input['material']);
			$update_material_sql = 'material = \''.$data['material'].'\','.'mtype = "'.$data['mtype'].'",';
		}
		//表单提交名称为start开始时间和end结束时间
		$publishTime = get_ad_publishTime($this->input['start'], $this->input['end'], false);
		if($publishTime === false)
		{
			$this->errorOutput(AD_TIME_ERROR);
		}
		//下架广告状态不更新
		$sql = 'SELECT status,id,org_id,user_id FROM '.DB_PREFIX.'advcontent WHERE id = '.$data['id'];
		$record  = $this->db->query_first($sql);
		#####
		$this->verify_content_prms(array('_action'=>'manage','id'=>$record['id'],'org_id'=>$record['org_id'],'user_id'=>$record['user_id']));
		#####
		if($record['status'] == 6)
		{
			$data['status'] = 6;
		}
		else
		{
			$data['status'] = get_ad_status($publishTime['start_time'], $publishTime['end_time']);
		}
		//更新广告主要内容
		$sql = 'UPDATE '.DB_PREFIX.'advcontent SET title = "'.$data['title'].'",'.
		'link = "'.$data['link'].'",'.
		'brief = "'.$data['brief'].'",'.
		'source = "'.$data['source'].'",'.
		'priority = "'.$data['priority'].'",'.
		$update_material_sql.
		'weight = "'.$data['weight'].'",'.
		'start_time = "'.$publishTime['start_time'].'",'.
		'end_time = "'.$publishTime['end_time'].'",'.
		'status = "'.$data['status'].'",'.
		'ip = "'.$data['ip'].'" WHERE id = '.$data['id'];
		$this->db->query($sql);
		if($this->db->affected_rows())
		{
			$this->db->query("UPDATE ".DB_PREFIX.'advcontent set update_user_id='.$this->user['user_id'].', update_user_name="'.$this->user['user_name'].'" WHERE id = '.$data['id']);
		}
		//重新计算广告时间
		$sql = 'DELETE FROM '.DB_PREFIX.'adtime WHERE adid = '.$data['id'];
		$q = $this->db->query($sql);
		$adtime = $this->adfunc->insert_ad_time($this->input['start'], $this->input['end'], $data['id']);
		if(!$adtime)
		{
			$this->errorOutput(ADTIME_ERROR);
		}
		$this->addLogs('更新广告内容', $ad, $data, $ad['title']);
		$this->addItem('success');
		$this->output();
	}
	private function upload_attatch2server($url = '')
	{
		if(!$url)
		{
			return;
		}
		//上传图片服务器返回素材信息
		$attatch = array();
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
		$attatch = $this->mMaterial->localMaterial($url, '','', -1);	
		$attatch = $attatch[0];
		return array(
		'host'=>$attatch['host'],
		'dir'=>$attatch['dir'],
		'filepath'=>$attatch['filepath'],
		'filename'=>$attatch['filename'],
		);
	}
	private function rebuild_group_used($pos_id)
	{
		if(!$pos_id)
		{
			return;
		}
		if(!is_array($pos_id))
		{
			$pos_id = array($pos_id);
		}
		$pos_id = array_unique($pos_id);
		//查询各分组使用情况
		$sql_select = 'SELECT * FROM '.DB_PREFIX.'group_pos WHERE pos_id in('.implode(',', $pos_id).')';
		$q = $this->db->query($sql_select);
		$group_used = array();
		while($r = $this->db->fetch_array($q))
		{
			$group_used[$r['pos_id']][$r['group_flag']] = $r['count'];
		}
		if($group_used)
		{
			//修改pos表中的group_used字段
			$sql_update = 'UPDATE '.DB_PREFIX."advpos SET group_used = CASE ";
			foreach ($group_used as $k=>$v)
			{
				$sql_update .= ' WHEN id = '.$k." THEN '".serialize($group_used[$k])."' ";
			}
			$sql_update .= " END WHERE id in(".implode(',', $pos_id).')';
			$this->db->query($sql_update);
		}
	}
	//上传本地广告素材只广告临时文件夹
	function upload_ad_material()
	{
		if(!($token = $this->input['ad_token']))
		{
			$this->errorOutput(AD_TOKEN_ERROR);
		}
		$material = $_FILES['Filedata'];
		$return = array();
		//取文件的
		if($material['name'])
		{
			//视频素材
			$ad_material_type = get_material_type($material['name']);
			if(!$ad_material_type)
			{
				$this->errorOutput(UNKNOW_MATERIAL_TYPE);
			}
			if($ad_material_type['mtype'] == 'video')
			{
				 $vodinfo = $this->get_video_id($_FILES);
				 if (!empty($vodinfo))
				 {
				 	$return['mhidden'] = $vodinfo['id'];
				 	$return['murl'] = $vodinfo['img']['host'].$vodinfo['img']['dir'].$vodinfo['img']['filepath'].$vodinfo['img']['filename'];
				 }
			}
			else if($ad_material_type['mtype'] == 'image')
			{
				//图像
				hg_mkdir(ADV_DATA_DIR  . 'tmp/');
				$img_url = ADV_DATA_DIR . 'tmp/' . $token.$ad_material_type['suffix'];
				@move_uploaded_file($material['tmp_name'], $img_url);
				$return['murl'] = ADV_DATA_URL . 'tmp/' .$token.$ad_material_type['suffix'];
				$return['mhidden'] = $return['murl'];
			}
			else if($ad_material_type['mtype'] == 'flash')
			{
				//动画 实际和图像同样处理 这里然让做了分开
				hg_mkdir(ADV_DATA_DIR  . 'tmp/');
				$img_url = ADV_DATA_DIR  . 'tmp/'.$token.$ad_material_type['suffix'];
				@move_uploaded_file($material['tmp_name'], $img_url);
				$return['murl'] = ADV_DATA_URL . 'tmp/' .$token.$ad_material_type['suffix'];
				$return['mhidden'] = $return['murl'];
			}
			$return['mtype'] = $ad_material_type['mtype'];
		}
		$this->addItem($return);
		$this->output();
	}
	//视屏转码
	function get_video_id($file)
	{
		foreach($file['Filedata'] AS $k =>$v)
		{
			$video['videofile'][$k] = $file['Filedata'][$k];
		}
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addFile($video);
		$curl->addRequestData('vod_leixing',1);
		$ret = $curl->request('create.php');
		return $ret[0];
	}
	//获取转码信息
	function get_video_info()
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $this->input['id']);
		$return = $this->curl->request('getVideoInfo.php');//请求create.php接口
		$this->addItem($return);
		$this->output();
	}
	//广告下架操作
	function adcancell()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = 'SELECT id,org_id,user_id,title FROM '.DB_PREFIX.'advcontent WHERE id IN('.$this->input['id'].')';
		$query  = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$this->addLogs('广告下架', '', '', $row['title']);
			$this->verify_content_prms(array('_action'=>'manage','id'=>$row['id'], 'org_id'=>$row['org_id'],'user_id'=>$row['user_id']));
		}
		$sql = 'UPDATE '.DB_PREFIX.'advcontent SET status = 6 WHERE id IN('.$this->input['id'].')';
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	//广告上架
	function adonline()
	{
		$adids = $this->input['id'];
		if(!$adids)
		{
			$this->errorOutput(NOID);
		}
		$status = $this->adfunc->get_ad_status_by_id($adids);
		//无限期广告无需检测
		$sql = 'SELECT * FROM '.DB_PREFIX.'advcontent WHERE id IN('.$this->input['id'].') AND end_time=""';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$this->verify_content_prms(array('_action'=>'manage','id'=>$row['id'], 'org_id'=>$row['org_id'],'user_id'=>$row['user_id']));
			$status[$row['id']] = 1;
			$this->addLogs('广告上架', '', '', $row['title']);
		}
		foreach($status as $aid=>$s)
		{
			$sql = 'UPDATE '.DB_PREFIX.'advcontent SET status = '.intval($s).' WHERE id = '.$aid;
			$this->db->query($sql);
		}
		$this->addItem($status);
		$this->output();
	}
	function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}

	function getvideobyid($id)
	{
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','id2videoid');
		$curl->addRequestData('id',$id);
		$ret = $curl->request('vod.php');
		$vodinfo = $ret[0];
		return $vodinfo = array('id'=>$vodinfo['id'],'img'=>$vodinfo['img_info'],'host'=>$vodinfo['hostwork'],'dir'=>$vodinfo['video_path'],'file_name'=>$vodinfo['video_filename']);
	}
}
$ouput= new adv_content_update();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>