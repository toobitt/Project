<?php
require('global.php');
define('MOD_UNIQUEID','activity');
class createApi extends adminBase
{
	function __construct()
	{
		parent::__construct();
		require_once  '../lib/activity.class.php';
		$this->libactivity = new activityLib();
		require_once (ROOT_PATH . 'lib/class/team.class.php');
		$this->team = new team();
		require_once (ROOT_PATH . 'lib/class/mark.class.php');
		$this->libmark = new mark();
		require_once (ROOT_PATH . 'lib/class/option.class.php');
		$this->liboption = new option();
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->aUploadClass = new material();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
            $offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
            $count = isset($this->input['count']) ? intval($this->input['count']) : 10;

            //获取选取条件
            $ce = $data = array();
            if($this->input['time_type'])
            {
                $time_type = $this->input['time_type'];
                if($time_type ==1)
                {
                    $ce['start_time'] = '>'.TIMENOW;
                }
                if($time_type ==2)
                {
                    $ce['start_time'] = '<'.TIMENOW;
                    $ce['end_time'] = '>'.TIMENOW;
                }
                if($time_type ==3)
                {
                    $ce['end_time'] = '<'.TIMENOW;
                }
            }
            $data = $this->getCondition();

            //获取选取条件
            $this->setXmlNode('activity', 'show');
            $total = $this->libactivity->get('activity','count(action_id) as total',$data,0, 1, array(),$ce);

            $this->addItem_withkey('total', $total);
            if($total)
            {
                if(isset($this->input['time']) && $this->input['time'] == 1 )
                {
                    $lis = array('yet_join'=>'desc','create_time'=>'desc');
                }
                else
                {
                    $lis = array('create_time'=>'desc');
                }
                $result = $this->libactivity->get('activity', '*', $data, $offset, $count, $lis,$ce);
                if($result)
                {
                    foreach ($result as $k=>$v)
                    {
                        if($v['action_img'])
                        {
                            $v['action_img'] = unserialize(htmlspecialchars_decode($v['action_img']));
                        }
                        $arr[$v['action_id']] =$v;
                    }
                    $this->addItem_withkey('data', $arr);
                }
            }
            $this->output();
    }

    function delete()
    {
        //把action_id addItem出来
        $action_id = trim($this->input['action_id']);

        if($this->input['action_id'])
		{
			$data['action_id'] = trim($this->input['action_id']);
		}
		if($this->input['team_id'])
		{
			$data['team_id'] = trim($this->input['team_id']);
		}
		$ids = $this->libactivity->get('activity', 'action_id,team_id', $data, 0, -1, array());
		if($ids)
		{
			$team_id = $action_id = $sp = ''; 
			foreach($ids as $k=>$v)
			{
				$action_id .= $sp . $v['action_id'];
				$team_id .= $sp . $v['team_id'];
				$sp = ','; 
				if($v['isopen'])
				 {
				 	$this->libactivity->update('team', array('action_num'=>-1), array('team_id'=>$v['team_id']), array('action_num'=>-1));
				 }
			}
			//删除活动评论
			$this->libactivity->delete('commtent',  array('action_id'=>$action_id));
			//删除回顾
			if(isset($this->input['team_id']))
			{
				$this->libactivity->delete('activity_review_date',  array('team_id'=>$team_id));
			}
			else
			{
				
			}
			//删除附件
			$this->libactivity->delete('material',  array('action_id'=>$action_id));
			//删除视频
			$this->libactivity->delete('video',  array('sid'=>$action_id,'source'=>'activity'));
			//删除视频
			$this->libactivity->delete('video',  array('sid'=>$action_id,'source'=>'action'));
			//删除回顾
			$this->libactivity->delete('activity_review',  array('action_id'=>$action_id));
			
			//删除活动参加
			$this->libactivity->delete('activity_apply',  array('action_id'=>$action_id));
			
			//删除足迹
			$this->team-> delete_visit($action_id,'action');
			//删除赞
			$this->liboption->delete(array('source'=>'activity', 'source_id'=>$action_id, 'action'=>'parise'));
			//删除标签
			$this->libmark->delete_source_id_mark(array('source'=>'activity', 'source_id'=>$action_id, 'action'=>'keywords'));
			//删除活动
			$this->libactivity->delete('activity',  $data);
		}
    	
        $this->addItem($action_id);
        $this->output();
    }
	function op()
	{
		$set = $post = $data = array();

		$set['state'] = trim($this->input['state']);
		if($set['state'] == 1)
		{
			$data['type_state'] = $post['type_state'] = trim(urldecode($this->input['type']));
			$set['type_state'] = '';
		}
		else
		{
			$set['type_state'] = trim(urldecode($this->input['type']));
			//更新状态
			$post['state'] = 1;
		}
		if(isset($this->input['action_id']))
		{
			$post['action_id'] = $data['action_id'] = trim($this->input['action_id']);
		}
		if(isset($this->input['team_id']))
		{
			$data['team_id'] = trim($this->input['team_id']);
		}

		$result = array();
		$result = $this->libactivity->get('activity','action_id,team_id', $data, 0, -1, array());
		if($result)
		{
			if(!(isset($post['action_id']) && !empty($post['action_id'])))
			{
				$post['action_id'] = $sp = '';
				foreach($result as $k=>$v)
				{
					$post['action_id'] .= $sp . $v['action_id'];
					$sp = ',';
					$team[$v['action_id']][] = $v['team_id'];
				}
			}
		}

		if($this->libactivity->update('activity', $set, $post, array()))
		{
			//更新报名表
			$this->libactivity->update('activity_apply', $set, $post, array());
			//更新活动评论表
			$this->libactivity->update('commtent', $set, $post, array());
			//更新回顾表
			if(isset($this->input['team_id']))
			{
				//更新所有的小组统计
				$this->libactivity->update('activity_review_date', $set, array('team_id'=>implode(',', $team)), array());
			}
			else
			{
				$list = array();
				$list =  $this->libactivity->get('activity_review','create_time,action_id,state', $post, 0, -1, array());
				if($list)
				{
					foreach($list as $k=>$v)
					{
						$data = array();
						$data['year'] = date("Y", $v['create_time']);
						$data['month']  = date("m", $v['create_time']);
						$data['day']  = date("d", $v['create_time']);
						$data['team_id'] =  $team[$v['action_id']];
						if($list[$v['state']] ==1 && ($set['state'] ==0))
						{
							$this->libactivity->update('activity_review_date', array('sign'=>-1), $data,  array('sign'=>-1));
						}
						elseif($set[$v['state']] ==1 && ($list['state'] ==0))
						{
							$this->libactivity->update('activity_review_date', array('sign'=>1), $data,  array('sign'=>1));
						}
						else
						{
							//TODO
						}
					}
				}
			}
			$this->libactivity->update('activity_review', $set, $post, array());
			//更新附件表
			$this->libactivity->update('material', $set, $post, array());
			//更新视频表
			$this->libactivity->update('video', $set, $post, array());
			//更新标签表
			$set['source'] = 'activity';
			$set['source_id'] = implode(',', $post['action_id']);
			$this->libmark->update_mark_state($set);
			//更新赞
			$this->liboption->updateState($set);
		}
		
		//迅搜接口
		if ($set['state'])
		{
			$this->team->add_search($post['action_id'], 'action');
		}
		else 
		{
			$this->team->delete_search($post['action_id'], 'action');
		}
		$this->addItem($action_id);
		$this->output();
	}

	function detail()
	{
		$data = $this->getCondition();
		$action_id = trim(urldecode($this->input['action_id']));
		//获取选取条件
		$data['action_id'] = $action_id;
		$result = array();
		$result = $this->libactivity->get('activity', '*', $data, 0 , 1, array());
		$this->setXmlNode('activity', 'detail');
		if(!$result)
		{
			$this->errorOutput("你搜索得活动不存在");
		}
		else
		{
			foreach ($result as $k=>$v)
			{
				if($k == 'action_img' && $v)
				{
					$v = unserialize($v);
				}
				$this->addItem_withkey($k,$v);
			}
			$review_info = $this->libactivity->get('material', '*', array('action_id'=>$data['action_id']), 0, -1, array(),array());
			$img = array();
			if($review_info)
			{
				foreach($review_info as $k=>$v)
				{
					$v['img_info'] = unserialize(htmlspecialchars_decode($v['img_info']));
					if($v['r_id'] >=0)
					{
						$img[$v['m_id']] = $v;
					}
					else 
					{
						$action_compic[$v['m_id']] = $v;
					}
				}
			}
			$this->addItem_withkey('action_compic', $action_compic);
			$this->addItem_withkey('review_img', $img);
			//回顾视频
			$video_info = $this->team->show_video($data['action_id'], 'activity');
			//回顾视频
			$video_info = $this->team->show_video($data['action_id'], 'action');
			$this->addItem_withkey('action_video', $video_info);
		}
		$this->output();
	}
        //参数获取
	public function getUpdateData()
	{
        $data = array();
        $data['action_id'] = trim($this->input['q_action_id']);
        //初始化
        if($this->input['q_action_name'])
        {
        	$data['action_name'] = trim(htmlspecialchars_decode(urldecode($this->input['q_action_name'])));
        }
        else
        {
        	return '缺少行动名';
        }
        if($this->input['q_team_type'])
        {
        	$data['team_type'] = trim(htmlspecialchars_decode(urldecode($this->input['q_team_type'])));
        }
        if($this->input['q_action_sort'])
        {
        	$data['action_sort'] =  trim($this->input['q_action_sort']);
        }
        //时间设置
        $data['register_time'] =  $this->input['has_register_time'] ? trim(htmlspecialchars_decode(urldecode($this->input['q_register_time']))) : '';

        //时间设置
        $data['start_time'] =  strtotime(trim($this->input['q_start_time']));
        if(!$data['start_time'])
        {
        	return '活动开始报名时间错误';
        }
        $data['end_time'] =  strtotime(trim($this->input['q_end_time']));
        if(!$data['end_time'])
        {
        	return '活动结束报名时间错误';
        }
        if($data['start_time'] >= $data['end_time'])
        {
        	return '活动报名时间错误';
        }


        //活动小组
        $data['team_id'] =  trim($this->input['q_team_id']);
        if(!$data['team_id'] || !is_numeric($data['team_id']))
        {
        	return '缺少行动所在小组';
        }
        $data['action_img']  =  trim(htmlspecialchars_decode(urldecode($this->input['q_action_img'])));


        if(!$data['action_img'] || strlen($data['action_img']) < 30)
        {
        	return '缺少行动封面';
        }
        $data['slogan'] =  nl2br(trim(htmlspecialchars_decode(urldecode($this->input['q_slogan']))));
        if(!$data['slogan'])
        {return '缺少行动口号';
        }
        $data['summary'] =  trim(htmlspecialchars_decode(urldecode($this->input['q_summary'])));
        if(!$data['summary'])
        {
        	return '缺少行动详情';
        }
        //
        $action_compic = '';
        $review_img = $this->input['review_img'];
        if($review_img)
        {
        	if(count($review_img) < 20)
        	{
        		
        	}
        	else
        	{
        			return '你上传的图片过多';
        	}
        }
        $data['action_video'] = $this->input['url'] ? trim($this->input['url']) : '';
        $data['topic_mark'] = $this->input['q_topic_mark'] ? trim($this->input['q_topic_mark']) : '';
        /**地点设置**/
        //地图设置
        $data['location'] =  $this->input['has_address'] ? trim(htmlspecialchars_decode(urldecode($this->input['q_location']))) : '';
        //地质设置
        $data['province'] =  $this->input['has_address'] ? trim($this->input['q_province']) : '';
        $data['city'] =  $this->input['has_address'] ? trim($this->input['q_city']) : '';
        $data['area'] =  $this->input['has_address'] ? trim($this->input['q_area']) : '';
        $data['address'] =  $this->input['has_address'] ? trim(htmlspecialchars_decode(urldecode($this->input['q_address']))) : '';
        //行动标签
        $data['mark'] =  $this->input['q_mark'] ? trim(htmlspecialchars_decode(urldecode($this->input['q_mark']))) : '';
        /*******报名限制*******/
        $data['need_pay'] =  $this->input['has_need_pay'] ? trim($this->input['q_need_pay']) : '';
        $data['need_num'] =  $this->input['has_need_num'] ? trim($this->input['q_need_num']) : '';
        $data['need_info'] =  $this->input['has_need_info'] ? implode(',', $this->input['q_need_info']) : '';
            return $data;
	}
	function update()
	{
		$data = array();
		$data = $this->getUpdateData();
		$result = array();
		//获取原始数据
		$rawData = $this->libactivity->getActivity('*', array('action_id'=>$data['action_id']), 0, 1, '');
		//更新数据
		$result = $this->libactivity->updateActivity($data, false, true);
		
		
		//添加图片
		$this->libactivity->delete('material', array('action_id'=>$data['action_id'],'r_id'=>'-1'));

		if (isset($this->input['review_img']))
		{
			$review_img = $this->input['review_img'];
	        $img_intro = $this->input['review_info'];
			//加载新图片
			if($review_img)
			{
				foreach($review_img as $k=>$v)
				{
					$t['img_info'] = htmlspecialchars_decode($v);
					$t['img_intro'] = nl2br(trim(htmlspecialchars_decode(urldecode($img_intro[$k]))));
					$t['action_id'] = $data['action_id'];
					$t['r_id'] = -1;
					$t['create_time']  = TIMENOW;

					$this->libactivity->insert('material', $t);
				}
			}
		}
		//视频
		if (isset($this->input['url']))
		{
			$video_url = trim(urldecode($this->input['url']));
			$video_info = $this->team->show_video($data['action_id'], 'action');
			if($video_info)
			{
				if ($video_url != $video_info['url'])
				{
					$this->team->update_video($video_info['id'], $video_url, 'action', $data['action_id']);
				}
			}
			else
			{
				$this->team->add_video($video_url, 'action', $data['action_id']);
			}
		}

		//比较数据，调用对应更新接口
		$currentData = $this->libactivity->getActivity('*',array('action_id'=>$data['action_id']), 0, 1, '');
		//更新小组数据
		if($rawData['team_id'] != $currentData['team_id'])
		{
			//TODO
			if($this->team->update_total(array('action_num'=>-1,'team_id'=>$rawData['team_id'])))
			{
				$this->team->update_total(array('action_num'=>1,'team_id'=>$currentData['team_id']));
			}
		}
		//更新标签

		$mark = ($this->input['q_mark']) ? trim(urldecode($this->input['q_mark'])) : '';
		if($rawData['mark'] || $mark )
		{
			$this->libmark->update_source_id_mark(array('parent_id'=>$rawData['team_id'],'source_id'=>$data['action_id'],'action'=>'keywords','source'=>'activity','name'=>$mark));
		}
		$this->addItem($data['action_id']);
		$this->output();
		

	}
	/**
	 * 封面上传接口
	 */
	public function upload()
	{
		$result = $this->aUploadClass->addMaterial($_FILES);
		$data = array('url'=>$result['host'].$result['dir'].'100x100/'.$result['filepath'].$result['filename'],'data'=>serialize($result));
		$this->addItem($data);
		$this->output();
	}
	/**
	 * 成果上传接口
	 */
	public function uploadReview()
	{
		$this->user['id'] = 0;
		$result = $this->aUploadClass->addMaterial($_FILES);
		if(isset($this->input['img_size']))
		{
			$img_size = $result['host'].$result['dir'].$this->input['img_size']."/".$result['filepath'].$result['filename'];
		}
		else
		{
			$img_size = $result['url'];
		}
		$data = array('id'=>$result['id'],
								'url'=>$img_size,
								'data'=>serialize($result));
		$this->addItem($data);
		$this->output();
	}
	//
	public function getCondition ()
	{
		$data = array ();
		//状态
		$data['state'] = '1';
		if(isset($this->input['state']))
		{
			$data['state'] = trim($this->input['state']);
		}
		//小组
		if(isset($this->input['team_id']))
		{
			$data['team_id'] = trim($this->input['team_id']);
		}
		//小组
		if(isset($this->input['action_id']))
		{
			$data['action_id'] = trim($this->input['action_id']);
		}
		//类型
		if(isset($this->input['team_type']) || isset($this->input['team_category']))
		{
			$post = array();
			if(isset($this->input['team_type']))
			{
				$post['team_type'] = $this->input['team_type'];
			}
			if(isset($this->input['team_category']))
			{
				$post['team_category'] = $this->input['team_category'];
			}
			$post['state'] = 1;
			if($post)
			{
				$teams = $this->libactivity->get('team','team_id',$post,0,-1,array());
				$data['team_id'] = $sp = '';
				foreach($teams as $k=>$v)
				{
					$data['team_id'] .= $sp . $v;
					$sp = ',';
				}
			}
		}
		if(isset($this->input['action_name']))
		{
			$data['action_n1ame'] = trim(htmlspecialchars_decode(urldecode($this->input['action_name'])));
		}
		if(isset($this->input['user_id']))
		{
			$data['user_id'] = trim(htmlspecialchars_decode(urldecode($this->input['user_id'])));
		}
		return $data;
	}
	
	//关闭或开启活动
	public function setCloseState()
	{
		$data = array();
		$data['state'] = 1;//活动必须有效才能关闭或者开启
		if(isset($this->input['action_id']))
		{
			$data['action_id'] = trim($this->input['action_id']);
		}
		$isopen = $this->input['isopen'] ? trim($this->input['isopen']) : 1;
		$action_info = $this->libactivity->get('activity','action_id,team_id', $data, 0, -1, array());
		if($action_info)
		{
			$action_ids = explode(',', $data['action_id']);
			if(count($action_id) != count($action_ids))
			{
				$this->errorOutput("操作ID含有无效ID");
			}
			$this->libactivity->update('activity',array('isopen' => $isopen), $data, array());		
		}
		else 
		{
			$this->errorOutput("操作ID全为无效ID");
		}
		$this->addItem($data['action_id']);
		$this->output();
	}
}

$out = new createApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>