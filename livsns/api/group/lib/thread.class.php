<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: create.php 10055 2012-08-29 08:03:44Z yaojian $
***************************************************************************/
class thread extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	//获取帖子信息
	public function show($offset, $count, $data = array())
	{	
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		$sql = "SELECT c.category_name , t.*,tt.* ,p.from_ip,p.pagetext AS content, g.name AS group_name
		FROM ".DB_PREFIX."thread AS t
		LEFT JOIN ".DB_PREFIX."thread_category AS c
		ON t.category_id = c.id
		LEFT JOIN ".DB_PREFIX."post AS p
		ON t.first_post_id = p.post_id
		LEFT JOIN ". DB_PREFIX ."group AS g
		ON t.group_id = g.group_id
		LEFT JOIN " . DB_PREFIX ."thread_type AS tt
		ON t.thread_type=tt.t_typeid
		WHERE 1 ";
		
		//获取查询条件
		$condition = $this->get_condition($data);
		$sql = $sql . $condition . $data_limit;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['pub_time'] = date('Y-m-d H:i:s' , $row['pub_time']);
			$row['last_post_time'] = date('Y-m-d H:i:s' , $row['last_post_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['category_name'] = $row['category_name'] ? $row['category_name'] : '未分类';
			$row['category_id'] = $row['category_id'] ? $row['category_id'] : -1;
			$row['content'] = html_entity_decode($row['content']);
//			$row['state_tags'] = $this->settings['state'][$row['state']];
			switch (intval($row['state']))
			{
				case 0:
					$row['audit'] = 0;
					break;
				case 1:
					$row['audit'] = 1;
					break;
				case 2:
					$row['audit'] = 0;
					break;
				default:
					break;
			}
//			if($this->settings['rewrite'])
//			{
//				$row['link'] = SNS_TOPIC . "thread-" . $row['thread_id'].".html";
//			}
//			else
//			{
//				$row['link'] = "./run.php?mid=8&thread_id=" . $row['thread_id'] . "&a=detail&group_id=".$row['group_id'];
//			}
//			$attachType=$this->settings['attach_type'];
//			$imgsrc=$attachType['img']['host'] . $attachType['img']['dir'] . 'img.png';
//			$realsrc=$attachType['real']['host'] . $attachType['real']['dir'] . 'real.png';
//			$extra_title='';
//			if($row['contain_img'])
//			{
//				$extra_title .="&nbsp;&nbsp;<img src='" . $imgsrc . "' alt='图片' title='包含图片' />";
//			}
//			if($row['contain_media'])
//			{
//				$extra_title .="&nbsp;&nbsp;<img src='" . $realsrc . "' alt='视频' title='包含视频' />";
//			}
//			$row['title'] .= $extra_title;
				
//			if($row['sticky'])
//			{
//				$row['arrow_img1'] ="<img src='".$this->settings["arrow_img"]["host"] . $this->settings["arrow_img"]["dir"] . "arrowTop.png' alt='置顶'  style='vertical-align:middle;margin-right:5px;' title='置顶' />";
//			}
//			if($row['quintessence'])
//			{
//				$row['arrow_img2'] ="<img src='".$this->settings["arrow_img"]["host"] . $this->settings["arrow_img"]["dir"] . "filesOrange.png' alt='置顶'  style='vertical-align:middle;margin-right:5px;' title='置顶' />";
//			}
//			else
//			{
//				$row['arrow_img3'] ="<img src='".$this->settings["arrow_img"]["host"] . $this->settings["arrow_img"]["dir"] . "fileGreen.png' alt='置顶'  style='vertical-align:middle;margin-right:5px;' title='置顶' />";
//			}
//			$row['arrow_img']=$row['arrow_img1'] . $row['arrow_img2'] . $row['arrow_img3'];
		
			$info[] = $row;
		}
		return $info;
	}
	
	//获取对应总数
	public function count($data = array())
	{	
		$sql  = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "thread AS t WHERE 1 ";
		$condition = $this->get_condition($data);		
		$sql = $sql . $condition;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	/**
	 * 获取查询条件
	 */
	public function get_condition($data = array())
	{
		$condition = '';
	
		//查询的关键字
		if($data['key'])
		{
			$condition .= " AND t.title LIKE '%" . $data['key'] . "%' ";
		}
	
		//查询帖子用户
		if($data['user_name'])
		{
			$condition .= " AND t.user_name = '" . $data['user_name'] . "' ";
		}
	
		//查询的起始时间
		if($data['start_time'])
		{
			$condition .= " AND t.pub_time > " . $data['start_time'];
		}
	
		//查询的结束时间
		if($data['end_time'])
		{
			$condition .= " AND t.pub_time < " . $data['end_time'];
		}
	
		//查询发布的时间
		if(is_numeric($data['date_search']))
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('Y-m-d',TIMENOW+24*3600));
			switch($data['date_search'])
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  t.pub_time > '".$yesterday."' AND t.pub_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  t.pub_time > '".$today."' AND t.pub_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  t.pub_time > '".$last_threeday."' AND t.pub_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  t.pub_time > '".$last_sevenday."' AND t.pub_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
	
		//查询帖子的状态
		if(is_numeric($data['state']))
		{	
			switch($data['state'])
			{
				case 1://所有状态
					break;
				case 2://已审核
					$condition .=" AND t.state=0";
					break;
				case 3://未审核
					$condition .=" AND t.state=1";
					break;
				default:
					break;
			}
		}
	
		//查询的讨论区分类
		if(is_numeric($data['group_id']))
		{
			$condition .= " AND t.group_id = " . $data['group_id'];
		}
	
		//查询类型下的帖子
		if(is_numeric($data['thread_type']))
		{
			if($data['thread_type'] == -1)
			{
				$condition .= "";
			}
			else
			{
				$condition .= " AND t.thread_type=" . $data['thread_type'];
			}
		}
	
		//查询图片贴，视频贴
		if(is_numeric($data['thread_img']))
		{
			switch($data['thread_img'])
			{
				case 1://所有帖子
					break;
				case 2://图片贴
					$condition .=" AND t.contain_img=1";
					break;
				case 3://视频贴
					$condition .=" AND t.contain_media=1";
					break;
				default:
					break;
			}
		}
	
		$thread_type_hgupdn=array(
			1 => 'last_post_time',
			2 => 'post_count',
		);
	
		$data['hgupdn'] = strtoupper($data['hgupdn']);
		
		if ($data['hgupdn'] != 'ASC')
		{
			$data['hgupdn'] = 'DESC';
		}
		if (!in_array($data['hgorder'], $thread_type_hgupdn))
		{
			$data['hgorder'] = 'pub_time';
		}
		if(is_numeric($data['_type']))
		{
			$data['hgorder'] = $thread_type_hgupdn[$data['_type']];
		}
		$orderby = ' ORDER BY t.' . $data['hgorder']  . ' ' . $data['hgupdn'] ;
		
		return $condition . $orderby;
	}
	
	public function detail($thread_id)
	{
		$condition = ' WHERE t.thread_id =' . $thread_id ." ";
		
		$sql = "SELECT c.category_name , t.* ,p.from_ip, p.pagetext AS content, g.name AS group_name
		FROM ".DB_PREFIX."thread AS t
		LEFT JOIN ".DB_PREFIX."thread_category AS c
		ON t.category_id = c.id
		LEFT JOIN ". DB_PREFIX ."group AS g
		ON t.group_id = g.group_id
		LEFT JOIN " . DB_PREFIX . "post AS p
		ON t.first_post_id = p.post_id
		" . $condition;
		
		$r = $this->db->query_first($sql);
		$r['pub_time'] = date('Y-m-d H:i:s' , $r['pub_time']);
		$r['last_post_time'] = date('Y-m-d H:i:s' , $r['last_post_time']);
		$r['update_time'] = date('Y-m-d H:i:s' , $r['update_time']);
		$r['category_name'] = $r['category_name'] ? $r['category_name'] : '未分类';
		$r['category_id'] = $r['category_id'] ? $r['category_id'] : -1;
		$r['column_id'] = unserialize($r['column_id']);
		if(is_array($r['column_id']))
		{
			$column_id = array();
			foreach($r['column_id'] as $k => $v)
			{
				$column_id[] = $k;
			}
			$column_id = implode(',',$column_id);
			$r['column_id'] = $column_id;
		}
		
			
//		if($this->settings['rewrite'])
//		{
//			$r['link'] = SNS_TOPIC."thread-".$r['thread_id'].".html";
//		}
//		else
//		{
//			$r['link'] = "./run.php?mid=8&thread_id=".$r['thread_id']."&a=detail&group_id=".$r['group_id'];
//		}

		$r['status'] = $r['state'] ? 2 : 0;
		$r['pubstatus'] = $r['status'] ? 1 : 0;
		
		return $r;
	}
// 	//过滤关键字
// 	public function filterParams($str)
// 	{
// 		//过滤&符号为！
// 		//$str = strtr(trim(urldecode($_SERVER['QUERY_STRING'])),'&','!');
// 		include_once ROOT_PATH.'lib/class/curl.class.php';
// 		$this->curl = new curl($this->settings['App_banword']['host'], $this->settings['App_banword']['dir']);
// 		$this->curl->initPostData();
// 		$this->curl->addRequestData('banword',$str);
// 		$result = $this->curl->request('banword.php');
// 		var_dump($result);exit;
// 		return $result;
// 	}

	//生成帖子
	public function thread($arr)
	{
		$space = $sql = '';
		if(is_array($arr))
		{
			$sql ="INSERT INTO `".DB_PREFIX."thread` SET ";
			foreach ($arr as $karr => $varr)
			{
				$sql .= $space .$karr."='".$varr."'";
				$space = ',';
			}
			$this->db->query($sql);
			return $this->db->insert_id();
		}
	}
	
	//编辑帖子
	public function updatethread($arr,$thread_id)
	{
		$space = $sql = '';
		if(is_array($arr))
		{
			$sql ="UPDATE `".DB_PREFIX."thread` SET ";
			foreach ($arr as $karr => $varr)
			{
				$sql .= $space .$karr."='".$varr."'";
				$space = ',';
			}
			$sql .= " where 1 and thread_id= ".$thread_id;
			$this->db->query($sql);
		}
	}
	
	public function post($arr)
	{
		$space = $sql = '';
		if(is_array($arr))
		{
			$sql ="INSERT INTO `".DB_PREFIX."post` SET ";
			foreach ($arr as $karr => $varr)
			{
				$sql .= $space .$karr."='".$varr."'";
				$space = ',';
			}
			$this->db->query($sql);
			return $this->db->insert_id();
		}
	}
	
	public function updatepost($arr,$post_id)
	{
		$space = $sql = '';
		if(is_array($arr))
		{
			$sql ="UPDATE `".DB_PREFIX."post` SET ";
			foreach ($arr as $karr => $varr)
			{
				$sql .= $space .$karr."='".$varr."'";
				$space = ',';
			}
			$sql .= " where 1 and post_id= ".$post_id;
			$this->db->query($sql);
		}
	}
	
	public function getUserAndGroup($user,$group_id,$parmas)
	{
		$result = true;
		$sql = "select state,permission from ".DB_PREFIX."group  where group_id = ".$group_id."";
		$groupStus = $this->db->fetch_all($sql);
		if(empty($groupStus))
		{
			$result = false;
		}
		else 
		{
			if($groupStus['0']['state'] < 1)
			{
				$result = false;
			}
			else 
			{
				$sql = 'SELECT user_level FROM ' . DB_PREFIX . 'group_members WHERE user_id = ' . $user . ' AND group_id = ' . $group_id;
				$level = $this->db->query_first($sql);
				
				
				if(!($groupStus['0']['permission'] & $parmas))
				{
					if(!$level)
					{
						$result = false;
					}
				}
			}
		}
		return $result;
	}
	
	//bbcode 转换 html
	public function bbcode2html($text)
	{
		return  $text;
	}
	
	//bbcode 转换 html
	public function html2bbcode($text)
	{
		return  $text;
	}
	
	//更新帖子附件数
	public function thread_material($num, $thread_id)
	{
		$sql = 'UPDATE ' . DB_PREFIX . 'thread SET attach_count = ' . $num . ' WHERE thread_id = ' . $thread_id;
		$this->db->query($sql);
	}
	
	//更新当前group的相关字段
	public function thread_updating($thread_info = array())
	{
		if(!$thread_info['group_id'] && !$thread_info['thread_id'])
		{
			$this->errorOutput(PARAM_NO_FULL);
		}
		//更新最后的帖子
		//$thread_updating = $thread_info['thread_updating'] ? unserialize($thread_info['thread_updating']) : array();
		$sql = "select thread_updating from ".DB_PREFIX."group  where group_id = ".$thread_info['group_id']."";
		$thread_updating = unserialize($this->db->result_first($sql));
			
		foreach ($thread_updating as $key=>$val)
		{
			if($val['thread_id'] == $thread_info['thread_id'])
			{
				unset($thread_updating[$key]);
				break;
			}
		}
	
		$thread_updating[] = array(
				'thread_id'=>$thread_info['thread_id'],
				'title'=>$thread_info['title'],
				'group_id'=>$thread_info['group_id'],
		);
	
		$n = count($thread_updating);
		$n = $n - 3;
		$n = $n>0?$n:0;
		$thread_updating = array_slice($thread_updating,$n,3);
	
		$sql = "update ".DB_PREFIX."group set thread_updating='".serialize($thread_updating)."',update_time=".$thread_info['pub_time']." where group_id=".$thread_info['group_id'];
	
		$this->db->query($sql);
	}
	
	//更新所有相关关系
	public function sonUpdate($thread_info)
	{
		$sql = "select parents from ".DB_PREFIX."group where group_id=".$thread_info['group_id'];
		$thread_infos = $this->db->query_first($sql);
		if($thread_infos['parents'])
		{
			$g_parent = explode(',', $thread_infos['parents']);
			$values = 'values';
			$sp = '';
			$tmp = array();
			$fa_ids = '';
			
			foreach($g_parent as $gids)
			{
				if($gids){
					if($gids != $thread_info['group_id'] )
					{
						$fa_ids .= $sp . $gids;
					}
					$values .= $sp . '(' . $thread_info['thread_id'] . ',' .$gids . ')';
					$sp = ',';
					$tmp[$gids] = $gids;
				}
			}
			
			//插入帖子与讨论组的关系
			$this->groupThreadUpdate($values);
			//更新今日发帖数
			$this->update_today_count($tmp);
			//更新当前group的所有上级group的相关字段'update_time'=>TIMENOW
			if($fa_ids)
			{
				//更新当前group的所有上级group的相关字段'update_time'=>TIMENOW
				$this->groupUpdate($fa_ids);
			}
		}
	}
	
	//插入帖子与讨论组的关系
	protected function groupThreadUpdate($values)
	{
		$sql = 'insert  into ' . DB_PREFIX . 'group_thread ' . $values;
		$this->db->query($sql);
	}
	
	//更新今日发帖数
	protected function update_today_count($array = array())
	{
		if(!empty($array))
		{
			$values = ' values';
			$sp = '';
			$time = time();
			$time = date("Y-m-d",$time);
			foreach ($array as $gid)
			{
				$values .= $sp . '(' . $gid . ', 1,"'.$time.'")';
				$sp = ',';
			}
	
			$sql = 'insert into ' . DB_PREFIX . 'gt_count(group_id,post_count,post_date) ' . $values . ' on duplicate key update post_count = post_count + values(post_count) ';
			$this->db->query($sql);
		}
	}
	
	//更新当前group的所有上级group的相关字段'update_time'=>TIMENOW
	protected function groupUpdate($fa_ids)
	{
		$this->db->query('update ' . DB_PREFIX . 'group set thread_count = thread_count + 1 ,post_count = post_count + 1 ,update_time = "' .TIMENOW.' " where group_id in(' .$fa_ids .')' );
	}
	
	//更新第一条post
	public function updatePostId($thread_id,$post_id)
	{
		$sql = "update " . DB_PREFIX . 'thread set first_post_id='.$post_id.',last_post_id='.$post_id." where thread_id=".$thread_id;
		$this->db->query($sql);
	}
	
	//更新第一条post
	public function updateLastPostId($thread_id,$post_id)
	{
		$sql = "update " . DB_PREFIX . 'thread set last_post_id='.$post_id." where thread_id=".$thread_id;
		$this->db->query($sql);
	}
	
	//获取帖子信息
	public function get_thread_info($thread_id)
	{
		if(intval($thread_id))
		{
			$sql = "select t.*,p.pagetext from ".DB_PREFIX."thread t , ".DB_PREFIX."post p where t.thread_id = ".$thread_id." and t.first_post_id=p.post_id";
			$thread_info = $this->db->query_first($sql);
			return $thread_info;
		}
		return false;
	}
	
	public function getpagetext($text)
	{
		$patten = array(
				"/<img([^>]+?)>/is",
				"/<a.*?(\shref\s*=\s*(['\"])?\s*[^\s]+?\s*\\2?)(?=\s|>)[^>]*?>/i"
		);
		$replace = array(
				"<img onload=\"javascript:if(this.width>555) this.style.width='555px';\" \\1>",
				"<a\\1 target='_blank'>",
		);
		
		return $pagetext = preg_replace($patten,$replace,$text);
	}
	
	public function clean_value($val)
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
	
	function cutchars($chars, $limitlen = '6', $cut_suffix = '…', $doubletoone = false)
	{
		$val = $this->csubstr($chars, $limitlen, $doubletoone);
		return $val[1] ? $val[0] . $cut_suffix : $val[0];
	}
	
	function csubstr($text, $limit = 12, $doubletoone = false)
	{
		if (function_exists('mb_substr') && !$doubletoone)
		{
			$more = (mb_strlen($text, 'UTF-8') > $limit) ? TRUE : FALSE;
			if($more)
			{
				$text = mb_substr($text, 0, $limit, 'UTF-8');
			}
			return array($text, $more);
		}
		elseif (function_exists('iconv_substr') && !$doubletoone)
		{
			$more = (iconv_strlen($text, 'UTF-8') > $limit) ? TRUE : FALSE;
			if($more)
			{
				$text = iconv_substr($text, 0, $limit, 'UTF-8');
			}
			return array($text, $more);
		}
		else
		{
			preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);
			$len = 0;
			$more = false;
			$ar = $ar[0];
			if (count($ar) <= $limit)
			{
				return array($text, $more);
			}
			$new_ar = array();
			$temp = '';
			foreach ($ar AS $k => $v)
			{
				if ($len >= $limit)
				{
					$more = true;
					break;
				}
				$sbit  =  ord($v);
				if($sbit  <  128)
				{
					$temp .= $v;
					if (strlen($temp) == 2)
					{
						$new_ar[$len] = $temp;
						$temp = '';
						$len++;
					}
				}
				elseif($sbit  >  223  &&  $sbit  <  240)
				{
					$new_ar[$len] = $temp . $v;
					$temp = '';
					$len++;
				}
			}
			$text = implode('', $new_ar);
			return array($text, $more);
		}
	}
	
	//更新父级信息
	public function updateFatherParams($thread_info)
	{
		$group_id = $thread_info['group_id'];
		$query = $this->db->query_first('select parents from ' . DB_PREFIX . 'group where group_id = ' . $group_id);
		$parents = $query['parents'];
		$parents = explode("," , $parents);
		$tmps = array();
			
		if(count($parents) > 1)
		{
			foreach($parents as $kk => $v)
			{
				if($v && $v != $group_id)
				{
					$tmps[$v] = $v;
				}
			}
			$p_str = implode(',',$tmps);
			unset($tmps);
			if($p_str){
				$this->db->query('update ' . DB_PREFIX . 'group set post_count = post_count + 1,update_time = "'.TIMENOW.'" where group_id in('.$p_str.')');
			}
		}
	}
	
	//获取当前楼层
	public function getThreadFloor($thread_id)
	{
		$sql = "SELECT floor  FROM `" . DB_PREFIX . "post`  WHERE `thread_id`=".$thread_id." ORDER BY `post_id` DESC  LIMIT 1";
		$floor = $this->db->query_first($sql);
		return ($floor['floor']+1);
	}
}

?>