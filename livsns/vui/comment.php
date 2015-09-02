<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: comment.php 4103 2011-06-21 08:26:39Z repheal $
***************************************************************************/
define('ROOT_DIR', '../');
require('./global.php');
class comment extends uiBaseFrm
{
	private $mVideo;
	function __construct()
	{
		parent::__construct();	
		include_once (ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
		
	/**
	* 添加评论
	*/
	public function create_comment()
	{
		$com = array(
			'cid' => $this->input['cid']?$this->input['cid']:0,
			'content' => $this->input['content']?$this->input['content']:'',
			'reply_id' => $this->input['reply_id']?$this->input['reply_id']:0,
			'reply_user_id' => $this->input['reply_user_id']?$this->input['reply_user_id']:0,
			'checked' => $this->input['checked']?$this->input['checked']:0,
			'title' => $this->input['title']?$this->input['title']:'',
			'type' => $this->input['type']?$this->input['type']:0,
		);
		if($com['checked'])
		{
			switch ($com['type'])
			{
				case 0://视频
					$url = SNS_VIDEO.'video_play.php?id='.$com['cid'];
					$title = $com['title']? $com['title']:$url;
					$content_pre = '对视频<a target="_blank" href="'.$url.'" title="">'.$title.'</a>的评论：';
					break;
				case 1://频道
					$url = SNS_VIDEO.'station_play.php?sta_id='.$com['cid'];
					$title = $com['title']? $com['title']:$url;
					$content_pre = '对频道<a target="_blank" href="'.$url.'" title="">'.$title.'</a>的评论：';
					break;
				default:
					break;
			}
			$this->update_status($content_pre.$com['content']);		
		}
		if(!$com['cid']&&!$com['content'])
		{
			echo json_encode('');
			exit;
		}
		else 
		{
			$ret = $this->mVideo->create_comment($com);
			$ret[0]['content'] = hg_show_face($ret[0]['content']);
			$ret[0]['create_time'] = hg_get_date($ret[0]['create_time']);
			ob_end_clean();
			echo json_encode($ret);
			exit;
		}
	}

	
/**
	* 同步点滴的私有方法
	* 
	*/	
	private function update_status($status)
	{
		if($this->user['id'])
		{
			include_once(ROOT_PATH . 'lib/class/status.class.php');
			$this->status = new status();
			$text = $status?$status:($this->input['status']?$this->input['status']:"");
			$ret = $this->status->verifystatus();
			$source = $this->input['source']?$this->input['source']:"";
			$id = $this->input['status_id']? $this->input['status_id']:0;
			$type = $this->input['type']?$this->input['type']:"";  
			if($ret['total']&&$ret['text'] !== $text || !$ret['reply_status_id'])
			{
				$this->status->update($text,$source,$id,0,$type,0,'','',1); 
			}
		}
	}
	/**
	* 删除评论
	* @param $id 评论ID
	* @param $cid 评论对象ID
	* @param $type （0视频、1网台、2用户、3专辑）
	* @return $ret 评论信息
	*/
	public function del_comment()
	{
		$id = $this->input['id']?$this->input['id']:0;
		$cid = $this->input['cid']?$this->input['cid']:0;
		$type = $this->input['type']?$this->input['type']:0;
		if(!$id&&!$cid)
		{
			echo json_encode('');
			exit;
		}
		else 
		{
			$ret = $this->mVideo->del_comment($id,$cid,$type);
			ob_end_clean();
			echo json_encode($ret);
			exit;
		}
	}
	
	/**
	* 恢复评论
	* @param $id 评论ID
	* @param $cid 评论对象ID
	* @param $type （0视频、1网台、2用户、3专辑）
	* @return $ret 评论信息
	*/
	function recover_comment()
	{
		$id = $this->input['id']?$this->input['id']:0;
		$cid = $this->input['cid']?$this->input['cid']:0;
		$type = $this->input['type']?$this->input['type']:0;
		if(!$id&&!$cid)
		{
			echo json_encode('');
			exit;
		}
		else 
		{
			$ret = $this->mVideo->recover_comment($id,$cid,$type);
			ob_end_clean();
			echo json_encode($ret);
			exit;
		}
	}
	
	/**
	* 评论分页ajax
	* @param $pp 评论ID
	* @param $cid 评论对象ID
	* @param $user_id 评论对象的user_id
	* @param $type （0视频、1网台、2用户）
	* @param $count 每页显示条数
	* @return $ret 评论信息
	*/
	function comment_list()
	{
		$state = 1; //评论状态，0-待审核，1-已审核通过
		$cid = $this->input['cid']?$this->input['cid']:0;
		$user_id = $this->input['user_id']?$this->input['user_id']:0;
		$type = $this->input['type']?$this->input['type']:0;
		$count = $this->input['count']?$this->input['count']:0;
		$page = intval($this->input['pp']) / $count;
		$html = "";
		if($cid&&$user_id&&count)
		{
			$comment_list = $this->mVideo->get_comment_list($user_id,$cid,$type,$state,$page,$count);
			if(is_array($comment_list))
			{
				$total_nums = $comment_list['total'];
				unset($comment_list['total']);
				$data['totalpages'] = $total_nums;
				$data['perpage'] = $count;
				$data['curpage'] = $this->input['pp'];
				$data['onclick'] = ' onclick="comment_page(this,'.$cid.','.$user_id.','.$type.','.$count.');"';
				$data['pagelink'] = $this->input['user_id']?hg_build_link('' , array('user_id' => $this->input['user_id'])):"";
				$showpages = hg_build_pagelinks($data);
				
				$html = '<ul class="comment_list" id="comment_list">';
				$li = "";
				foreach($comment_list as $key=>$value)
				{
					if(!$value['reply_id'])
					{
						$li .= '<li id="com_'.$value['id'].'" class="clear">
						<div class="comment-img"><a target="_blank" href="'.hg_build_link(SNS_UCENTER.'user.php', array('user_id'=>$value['user']['id'],)).'"><img src="'.$value['user']['middle_avatar'].'"/></a></div>
						<div class="comment-bar">
						<a class="bar-left" target="_blank" href="'.hg_build_link(SNS_UCENTER.'user.php', array('user_id'=>$value['user']['id'],)).'">'.$value['user']['username'].'</a>
							<div class="bar-right">
								<span>'.hg_get_date($value['create_time']).'</span>
									<a href="javascript:void(0);" onclick="reply_comment('.$value['cid'].','.$value['id'].','.$value['user']['id'].');">回复</a>';
						if($value['relation'])
						{
							$li .='<a href="javascript:void(0);" onclick="del_comment('.$value['id'].','.$value['cid'].','.$type.');">删除</a>';
						}
						$li .='</div></div><div class="comment-con">'.hg_show_face($value['content']).'</div>';
						if(is_array($value['reply']))
						{
							$li .='<ul class="reply_list" id="rep_'.$value['id'].'">';
							$uli = '';
							foreach($value['reply'] as $k=>$v)
							{
								$uli .='<li id="com_'.$v['id'].'" class="clear">
									<div class="comment-img"><a target="_blank" href="'.hg_build_link(SNS_UCENTER.'user.php', array('user_id'=>$v['user']['id'],)).'"><img src="'.$v['user']['middle_avatar'].'"/></a></div>
									<div class="comment-bar">
										<a class="bar-left" target="_blank" href="'.hg_build_link(SNS_UCENTER.'user.php', array('user_id'=>$v['user']['id'],)).'">'.$v['user']['username'].'</a>
										<div class="bar-right">
											<span>'.hg_get_date($v['create_time']).'</span>
											<a href="javascript:void(0);" onclick="reply_comment('.$value['cid'].','.$value['id'].','.$value['user']['id'].');">回复</a>';
								if($v['relation'])
								{
									$uli .= '<a href="javascript:void(0);" onclick="del_comment('.$v['id'].','.$v['cid'].','.$type.');">删除</a>';
								}
								$uli .='</div></div><div class="comment-con">'.hg_show_face($value['content']).'</div></li>';
							}
							$li .= $uli.'</ul>';
						}
						$li .= '</li>';
					}
				}
				$html .= $li.'</ul>'.$showpages;
			}
		}
		ob_end_clean();
		echo $html;
		exit;
	}
	
	
	/**
	* 发布，转发点滴的处理方法
	* 
	*/	
	public function update()
	{
		if($this->user['id'])
		{
			include_once(ROOT_PATH . 'lib/class/status.class.php');
			$this->status = new status();
			$text = $this->input['status']?$this->input['status']:"";
			$ret = $this->status->verifystatus();
			if($ret['total']&&$ret['text'] === $text&&!$ret['reply_status_id'])
			{
				ob_end_clean();
				echo json_encode($ret);
				exit;
			}
			$source = $this->input['source']?$this->input['source']:"";
			$id = $this->input['status_id']? $this->input['status_id']:0;
			$type = $this->input['type']?$this->input['type']:"";  
			$ret = $this->status->update($text,$source,$id,0,$type); 
			ob_end_clean();
			echo json_encode($ret);
			exit;
		}
		else
		{
			ob_end_clean();
			echo json_encode('false');
			exit;
		}
	}
	
	public function get_face()
	{
		$face_con = $this->input['con'];
		$face_tab = $this->input['tab'];
		
		$this->tpl->addVar('face_con', $face_con);
		$this->tpl->addVar('face_tab', $face_tab);
		$this->tpl->outTemplate('face','hg_html_face,'.$face_tab);
	}
}

$out = new comment();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>