<?php
/*$Id: destroy_batch.php 2774 2011-03-15 06:58:54Z wang $*/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
//批量删除评论
class commentsBatch extends BaseFrm
{
	private $mUser,$mComment;
	function __construct()
	{
		parent::__construct();
			
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->mUser = new user();
		 
		include_once (ROOT_PATH . 'lib/class/comment.class.php');
		$this->mComment = new comment();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function commDeleteMore()
	{
		$userinfo = $this->mUser->verify_credentials(); 
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
	  	
		$commstr = urldecode($this->input['commentIds']);
		$type = intval($this->input['type']);
		$array = array();
		$array = array_filter(explode(',',$commstr));
		if(is_array($array))
		{  
			$nn = array();
			if($type)
			{//删除我发出的评论或回复
				$commByMe = array();
				$commByMe = $this->mComment->get_my_comments(0,0,0,50);
				
				if($commByMe)
				{
					foreach($commByMe as $id => $info)
					{
						$tmp_new[] = $info['id'];
						$status_arr[] = $info['status_id'];
						$nn[$info['status_id']][] = $info['id']; 
					}
					$array = array_intersect($tmp_new,$array);//只删除包含在”我发出的评论列表“中的数据
					$status_arr = array_filter($status_arr);
				}
			}
			else
			{//删除我收到的评论或回复
				$arr = array();
				$iid = $this->db->query('SELECT id,status_id FROM ' .DB_PREFIX . 'status_comments WHERE flag = 0 and id IN (' . implode(',',$array) . ')');
				while($result = $this->db->fetch_array($iid))
				{
					$nn[$result['status_id']][] = $result['id'];  
					$arr[] = $result['status_id'];
				}
			}		
			$str = implode(',',$array);
			
			
			$sql = 'UPDATE ' . DB_PREFIX . 'status_comments SET flag = 1 WHERE id IN (' . $str . ')'; 
			$this->db->query($sql);
			
			//更新”我“评论过的点滴的评论数，将其减对应的数目
			$tmp_array = array();
			$tmp_array = ($type > 0) ? $status_arr : $arr;
			$tmp_array = array_unique($tmp_array);
			foreach($tmp_array as $k => $id)
			{
				
				$cnt = count($nn[$id]);
				$case .= ' WHEN status_id = ' . $id . ' THEN CASE WHEN comment_count >= ' . $cnt . ' THEN comment_count - ' . $cnt . ' ELSE 0 END';
			}
			$case .='  END ';
			$var = 'UPDATE ' . DB_PREFIX . 'status_extra SET comment_count = CASE ' . $case;
 			$this->db->query($var);
			
 			$this->setXmlNode('comments','comment');
 			if($type)
 			{
				foreach($commByMe as $inedx => $content)
				{
					foreach($array as $kkey => $vval)
					{
						if($vval == $content['id'])
						{
							$this->addItem($content);
						}
					}
				}
 			}
 			else
 			{
 				$this->addItem($arr);//删除我收到的回复，返回点滴的id（暂定返回此数据）
 			}
			//$this->addItem($var);
			$this->output();	
			
		}
		else
		{
			$this->errorOutput(OBJECT_NULL);
		}
		 
	}
}

$out = new commentsBatch();
$out->commDeleteMore();