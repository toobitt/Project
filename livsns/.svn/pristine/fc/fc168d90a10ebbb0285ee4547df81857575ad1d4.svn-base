<?php
//print_r(1);exit;
require_once './global.php';
include_once ROOT_PATH . 'lib/class/mark.class.php';
include_once ROOT_PATH . 'lib/class/member.class.php';
class correctDataApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		require_once  CUR_CONF_PATH.'lib/activity.class.php';
		$this->libactivity = new activityLib();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 修正主题标签，插入本地获取冗余数据
	 */
	public  function getThemeTags()
	{
		echo "第一步:回复主题标签<br/>";
		$mark = new mark();
		
		 $this->recoverTeamThemeTags();
		//*/
		$result = $dataSeach = array();
		echo "第二步:清理标签表<br/>";
		//$mark->table();exit;
		echo "第三步:获取话题标签信息<br/>";
		$result = $dataSeach = array();
		$data = array();$i = 0;
		$result = $this->libactivity->get('topic','topic_id,source_id,creater_id,tags,state,pub_time',array(), 0, -1, array('topic_id'=>'desc'),array('tags'=>'!=""'));
		if($result)
		{
			foreach($result as $k=>$v)
			{
				//处理标签
				if(strpos($v['tags'], ','))
				{
					$topic_tags = explode(',', $v['tags']);
					foreach($topic_tags as $m=>$n)
					{
						$tag[$n] = $n;
						$data[$i]['action'] = 'topic_tag';
						$data[$i]['parent_id'] = $v['source_id'];
						$data[$i]['source'] = 'topic';
						$data[$i]['source_id'] = $v['topic_id'];
						$data[$i]['state'] = $v['state'];
						$data[$i]['user_id'] = $v['creater_id'];
						$data[$i]['create_time'] = $v['pub_time'];
						$data[$i]['name'] = $n;
						$i++;
					}
				}
				else 
				{
					$tag[$v['tags']] = $v['tags'];
					$data[$i]['action'] = 'topic_tag';
					$data[$i]['parent_id'] = $v['source_id'];
					$data[$i]['source'] = 'topic';
					$data[$i]['source_id'] = $v['topic_id'];
					$data[$i]['state'] = $v['state'];
					$data[$i]['user_id'] = $v['creater_id'];
					$data[$i]['create_time'] = $v['pub_time'];
					$data[$i]['name'] = $v['tags'];
					$i++;
				}
				//处理关系
			}
		}
		echo "第三步:获取小组标签信息<br/>";
		$result = $dataSeach = array();
		$result = $this->libactivity->get('activity','action_id,team_id,user_id,mark,create_time,state',array(), 0, -1, array('action_id'=>'desc'),array('mark'=>'!=""'));
		if($result)
		{
			foreach($result as $k=>$v)
			{
				//处理标签
				if(strpos($v['mark'], ','))
				{
					$topic_tags = explode(',', $v['mark']);
					foreach($topic_tags as $m=>$n)
					{
						$tag[$n] = $n;
						$data[$i]['action'] = 'topic_tag';
						$data[$i]['parent_id'] = $v['team_id'];
						$data[$i]['source'] = 'activity';
						$data[$i]['source_id'] = $v['action_id'];
						$data[$i]['state'] = $v['state'];
						$data[$i]['user_id'] = $v['user_id'];
						$data[$i]['create_time'] = $v['create_time'];
						$data[$i]['name'] = $n;
						$i++;
					}
				}
				else 
				{
					$tag[$v['tags']] = $v['tags'];
					$data[$i]['action'] = 'keywords';
					$data[$i]['source'] = 'activity';
					$data[$i]['source_id'] = $v['action_id'];
					$data[$i]['parent_id'] = $v['team_id'];
					$data[$i]['state'] = $v['state'];
					$data[$i]['user_id'] = $v['user_id'];
					$data[$i]['create_time'] = $v['create_time'];
					$data[$i]['name'] = $v['mark'];
					$i++;
				}
			}
		}
		echo "第四步:获取人的标签信息<br/>";
		$result = $dataSeach = array();
		$member = new member();
		$result = $member->get_all_mark('',0, -1);
		if($result)
		{
			foreach($result as $k=>$v)
			{
				if($v['mark'] !=0)
				{
					//处理标签
					if(strpos($v['mark'], ','))
					{
						$topic_tags = explode(',', $v['mark']);
						foreach($topic_tags as $m=>$n)
						{
							$tag[$n] = $n;
							$data[$i]['action'] = 'myself';
							//$data[$i]['parent_id'] = '';
							$data[$i]['source'] = 'user';
							$data[$i]['source_id'] = $v['member_id'];
							$data[$i]['state'] = 1;
							$data[$i]['user_id'] = $v['member_id'];
							$data[$i]['create_time'] = TIMENOW;
							$data[$i]['name'] = $n;
							$i++;
						}
					}
					else 
					{
						$tag[$v['tags']] = $v['tags'];
						$data[$i]['action'] = 'myself';
						$data[$i]['source'] = 'user';
						$data[$i]['source_id'] = $v['member_id'];
						//$data[$i]['parent_id'] = $v['team_id'];
						$data[$i]['state'] = 1;
						$data[$i]['user_id'] = $v['member_id'];
						$data[$i]['create_time'] = TIMENOW;
						$data[$i]['name'] = $v['mark'];
						$i++;
					}
				}
			}
		}
		echo "第五步:获取小组的标签信息<br/>";
		$result = $dataSeach = array();
		$result = $this->libactivity->get('team','team_id,creater_id,tags,theme_tags,pub_time,state',array(), 0, -1, array('team_id'=>'desc'),array());
		if($result)
		{
			foreach($result as $k=>$v)
			{
				if($v['tags'])
				{
					//处理标签
					if(strpos($v['tags'], ','))
					{
						$topic_tags = explode(',', $v['tags']);
						foreach($topic_tags as $m=>$n)
						{
							$tag[$n] = $n;
							$data[$i]['action'] = 'topic_tag';
							$data[$i]['parent_id'] = $v['team_id'];
							$data[$i]['source'] = 'team';
							$data[$i]['source_id'] = $v['team_id'];
							$data[$i]['state'] = $v['state'];
							$data[$i]['user_id'] = $v['creater_id'];
							$data[$i]['create_time'] = $v['pub_time'];
							$data[$i]['name'] = $n;
							$i++;
						}
					}
					else 
					{
						$tag[$v['tags']] = $v['tags'];
						$data[$i]['action'] = 'topic_tag';
						$data[$i]['parent_id'] = $v['team_id'];
						$data[$i]['source'] = 'team';
						$data[$i]['source_id'] = $v['team_id'];
						$data[$i]['state'] = $v['state'];
						$data[$i]['user_id'] = $v['creater_id'];
						$data[$i]['create_time'] = $v['pub_time'];
						$data[$i]['name'] = $v['tags'];
						$i++;
					}
				}
				if($v['theme_tags'])
				{
					//处理标签
					if(strpos($v['theme_tags'], ','))
					{
						$topic_tags = explode(',', $v['theme_tags']);
						foreach($topic_tags as $m=>$n)
						{
							$tag[$n] = $n;
							$data[$i]['action'] = 'theme_tags';
							$data[$i]['parent_id'] = $v['team_id'];
							$data[$i]['source'] = 'team';
							$data[$i]['source_id'] = $v['team_id'];
							$data[$i]['state'] = $v['state'];
							$data[$i]['user_id'] = $v['creater_id'];
							$data[$i]['create_time'] = $v['pub_time'];
							$data[$i]['name'] = $n;
							$i++;
						}
					}
					else 
					{
						$tag[$v['tags']] = $v['theme_tags'];
						$data[$i]['action'] = 'theme_tags';
						$data[$i]['parent_id'] = $v['team_id'];
						$data[$i]['source'] = 'team';
						$data[$i]['source_id'] = $v['team_id'];
						$data[$i]['state'] = $v['state'];
						$data[$i]['user_id'] = $v['creater_id'];
						$data[$i]['create_time'] = $v['pub_time'];
						$data[$i]['name'] = $v['theme_tags'];
						$i++;
					}
				}
				//处理关系
			}
		}
		echo "第六步:插入标签<br/>";
		$name = implode(',', $tag);
		$marks = $mark->insertMark(array('name'=>$name,'action'=>0));
		$s_marks = array_keys($marks);
		echo "第七步:插入标签关系<br/>";
		if($data)
		{
			$pnu = $pau = array();
			foreach($data as $k=>$v)
			{
				if(in_array($v['name'], $s_marks))
				{
					$pun[$k]['action'] = $v['action'];
					$pun[$k]['source'] = $v['source'];
					$pun[$k]['source_id'] = $v['source_id'];
					$pun[$k]['parent_id'] = $v['parent_id'];
					$pun[$k]['create_time'] = $v['create_time'];
					$pun[$k]['state'] = $v['state'];
					$pun[$k]['mark_id'] = $marks[$v['name']];
					$pau[$k] = $pun[$k];
					$pau[$k]['user_id'] = $v['user_id'];
				}
			}
		}
		if($pun)
		{
			$mark_u = $mark->insertMarkAction(array('marks_sss'=>serialize($pun)));
		}
		if($pau)
		{
			$mark_a = $mark->insertNameAction(array('marks_ttt'=>serialize($pau)));
		}
		echo "处理完毕<br/>";
		
	} 
	//回复小组主题的标签
	function recoverTeamThemeTags()
	{
		$mark = new mark();
	    $result = $dataSeach = array();
	    $dataSeach = array('source'=>'team','action'=>'team_theme','count'=>-1);//
		$result = $mark->get_id_by_name($dataSeach);
		if($result)
		{
			$theme_arr = array();
			foreach($result as $k=>$v)
			{
				$theme_arr[$v['source_id']][$v['action']][$v['mark_name']] = $v['mark_name'];
			}
			if($theme_arr)
			{
				echo "team主题标签更新开始<br/>";
				foreach($theme_arr as $k=>$v)
				{
					$data = array();
					if($v['team_theme'])
					{
						$data['theme_tags'] = implode(',', $v['team_theme']);
					}
					if($v['team_tag'])
					{
						$data['tags'] = implode(',', $v['team_tag']);
					}
					if($data)
					{
						$this->libactivity->update('team',$data,array('team_id'=> $k), array());
					}
					echo "...team".$k."主题标签更新完毕<br/>";
				}
				echo "team主题标签更新完毕<br/>";
			}
		}
	}
	//回复topic的标签
	function recoverTopicTags()
	{
		$mark = new mark();
	    $result = $dataSeach = array();
		$dataSeach = array('source'=>'topic','count'=>-1);//
		$result = $mark->get_id_by_name($dataSeach);
		if($result)
		{
			$topic_arr = array();
			foreach($result as $k=>$v)
			{
				$topic_arr[$v['source_id']] [] = $v['mark_name'];
			}
			if($topic_arr)
			{
				echo "topic标签更新开始<br/>";
				foreach($topic_arr as $k=>$v)
				{
					$data = array();
					if($v)
					{
						$data['tags'] = implode(',', $v);
					}
					if($data)
					{
						$this->libactivity->update('topic',$data,array('topic_id'=> $k), array());
						echo "...topic".$k."标签更新完毕<br/>";
					}
				}
				echo "topic标签更新完毕<br/>";
			}
		}
	}
	/**
	 * 方法不存在的时候调用的方法
	 */
	public function none()
	{
		$this->errorOutput('调用的方法不存在');
	}
}

$out = new correctDataApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();