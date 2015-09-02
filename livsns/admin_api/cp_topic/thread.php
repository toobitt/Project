<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: thread.php 7928 2012-07-14 06:59:20Z hanwenbin $
***************************************************************************/

define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','cp_thread_m');//模块标识
require(ROOT_DIR . 'global.php');

/**
 * 
 * 帖子数据获取API
 * 
 * 提供的方法：
 * 1) 获取所有帖子数据
 * 2) 获取单条帖子数据
 * 3) 获取指定帖子的总数
 * 
 */
class threadShowApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 
	 * 获取所有帖子数据
	 */
	public function show()
	{
		//分页参数设置
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;		

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
		$condition = $this->get_condition();		
		$sql = $sql . $condition . $data_limit;		
		$q = $this->db->query($sql);

		$this->setXmlNode('thread_info' , 'thread');
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			$row['pub_time'] = date('Y-m-d H:i:s' , $row['pub_time']);
			$row['last_post_time'] = date('Y-m-d H:i:s' , $row['last_post_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);					
			$row['category_name'] = $row['category_name'] ? $row['category_name'] : '未分类';
			//$row['type_name']=$row['type_name'];
			$row['category_id'] = $row['category_id'] ? $row['category_id'] : -1;
			$row['state_tags'] = $this->settings['state'][$row['state']];	
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
			
			if($this->settings['rewrite'])
			{
				$row['link'] = SNS_TOPIC . "thread-" . $row['thread_id'].".html";	
			}
			else 
			{
				$row['link'] = SNS_TOPIC . "?m=thread&thread_id=" . $row['thread_id'] . "&a=detail&group_id=".$row['group_id'];	
			}
			

			$attachType=$this->settings['attach_type'];
			$imgsrc=$attachType['img']['host'] . $attachType['img']['dir'] . 'img.png';
			$realsrc=$attachType['real']['host'] . $attachType['real']['dir'] . 'real.png';
			$extra_title='';
			if($row['contain_img'])
			{
				$extra_title .="&nbsp;&nbsp;<img src='" . $imgsrc . "' alt='图片' title='包含图片' />";
				$sql="select * from " . DB_PREFIX . "material where id=" . $row['thread_id'] . " limit 1";
				$ret=$this->db->query_first($sql);
				if(!empty($ret))
				{
					$row['logo']=$this->settings['livime_upload_url'] . $ret['filepath'] . $ret['filename'];
				}
			}
			if($row['contain_media'])
			{
				$extra_title .="&nbsp;&nbsp;<img src='" . $realsrc . "' alt='视频' title='包含视频' />";
			}
			$row['title'] .=$extra_title;
			
			if($row['sticky'])
			{
				$row['arrow_img1'] ="<img src='".$this->settings["arrow_img"]["host"] . $this->settings["arrow_img"]["dir"] . "arrowTop.png' alt='置顶'  style='vertical-align:middle;margin-right:5px;' title='置顶' />";
			}
		    if($row['quintessence'])
			{
				$row['arrow_img2'] ="<img src='".$this->settings["arrow_img"]["host"] . $this->settings["arrow_img"]["dir"] . "filesOrange.png' alt='置顶'  style='vertical-align:middle;margin-right:5px;' title='置顶' />";
			}
			else
			{
				$row['arrow_img3'] ="<img src='".$this->settings["arrow_img"]["host"] . $this->settings["arrow_img"]["dir"] . "fileGreen.png' alt='置顶'  style='vertical-align:middle;margin-right:5px;' title='置顶' />";
			}
			$row['arrow_img']=$row['arrow_img1'] . $row['arrow_img2'] . $row['arrow_img3'];

			$this->addItem($row);
		}
		
		$this->output();
	}
	
	/**
	 * 获取帖子总数
	 * 默认为全部频道的总数
	 */
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "thread AS t LEFT JOIN " . DB_PREFIX . "group AS g ON t.group_id = g.group_id WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);
		
		echo json_encode($r);
	}
	
	/**
	 * 获取单条帖子信息
	 */
	public function detail()
	{
		if (isset($this->input['id']))
		{
			$this->input['thread_id'] = urldecode($this->input['id']);
		}

		$this->input['thread_id'] = urldecode($this->input['thread_id']);

		if(!$this->input['thread_id'])
		{
			$condition = ' ORDER BY t.thread_id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE t.thread_id in(' . $this->input['thread_id'] .')';
		}
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
		$this->setXmlNode('thread_info' , 'thread');
		
		if(is_array($r) && $r)
		{
			$r['pub_time'] = date('Y-m-d H:i:s' , $r['pub_time']);
			$r['last_post_time'] = date('Y-m-d H:i:s' , $r['last_post_time']);
			$r['update_time'] = date('Y-m-d H:i:s' , $r['update_time']);
			$r['category_name'] = $r['category_name'] ? $r['category_name'] : '未分类';
			$r['category_id'] = $r['category_id'] ? $r['category_id'] : -1;			
			
			if($this->settings['rewrite'])
			{
				$r['link'] = SNS_TOPIC."thread-".$r['thread_id'].".html";	
			}
			else 
			{
				$r['link'] = SNS_TOPIC."?m=thread&thread_id=".$r['thread_id']."&a=detail&group_id=".$r['group_id'];
			}

			$r['status'] = $r['state'] ? 2 : 0;
			$r['pubstatus'] = $r['status'] ? 1 : 0; 

			$this->addItem($r);
			$this->output();
		}
		else
		{
			$this->errorOutput('帖子不存在');	
		} 							
	}

    /**
	* 获取帖子类型
	*/
	public function thread_type()
	{
		$sql="select * from " . DB_PREFIX . "thread_type where 1";
		$ret=$this->db->query($sql);
		while($row=$this->db->fetch_array($ret))
		{
			$this->addItem($row);
		}
		$this->output();
	}

		/**
	* 根据地盘ID检索地盘信息
	* @name show_opration
	* @access public 
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	* @param id int 地盘ID
	* @return array $return 文章信息
	*/
	public function show_opration()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput('未传入帖子ID');
		}
		$sql="SELECT t.*,tt.*
				FROM " . DB_PREFIX."thread t 
				LEFT JOIN " . DB_PREFIX ."thread_type tt
				ON t.thread_type = tt.t_typeid where t.thread_id=" . $this->input['id'];
		$return=$this->db->query_first($sql);
		if($return['contain_img'])
		{
				$sql="select * from " . DB_PREFIX . "material where id=" . $return['thread_id'] . " limit 1";
				$ret=$this->db->query_first($sql);
				$return['logo']=$this->settings['livime_upload_url'] . $ret['filepath'] . $ret['filename'];
		}
		if(!$return)
		{
			$this->errorOutput('文章不存在或已被删除');
		}

	    //记录页面的所处的类型与类别
		if($this->input['frame_type'])
		{
			$return['frame_type'] = intval($this->input['frame_type']);
		}
		else
		{
			$return['frame_type'] = '';
		}
		
		if($this->input['frame_sort'])
		{
			$return['frame_sort'] = intval($this->input['frame_sort']);
		}
		else
		{
			$return['frame_sort'] = '';
		}
        $return['create_time']=date('Y-m-d H:i',$return['create_time']);
		$return['update_time']=date('Y-m-d H:i',$return['update_time']);
		$return['pub_time']=date('Y-m-d H:i',$return['pub_time']);
		$this->addItem($return);
		$this->output();
	}


	/**
	 * 获取查询条件
	 */
	public function get_condition()
	{
		$condition = '';
		
		//查询的关键字
		if($this->input['k'])
		{
			$condition .= " AND t.title LIKE '%" . trim(urldecode($this->input['k'])) . "%' ";
		}
		
		//查询帖子用户
		if($this->input['user_name'])
		{
			$condition .= " AND t.user_name = '" . trim($this->input['user_name']) . "' ";	
		}
		
		//查询的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND t.pub_time > " . strtotime($this->input['start_time']);
		}
		
		//查询的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND t.pub_time < " . strtotime($this->input['end_time']);	
		}

        //查询发布的时间
        if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('Y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
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
		if(isset($this->input['state']))
		{
			$state=intval($this->input['state']);
			switch($state)
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
		if($this->input['group_id'])
		{
			$condition .= " AND t.group_id = " . intval($this->input['group_id']);		
		}

		//查询类型下的帖子
		if($this->input['thread_type'])
		{
			if($this->input['thread_type']==-1)
			{
				$condition .="";
			}
			else
			{
				$condition .=" AND t.thread_type=" . intval($this->input['thread_type']);
			}
		}

		//查询图片贴，视频贴
		if($this->input['thread_img'])
		{
			switch(intval($this->input['thread_img']))
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
		
		
		//查询某地盘下的帖子
		if($this->input['_id'])
		{
			$condition .= " AND t.group_id = " . intval($this->input['_id']);	
		}

		$thread_type_hgupdn=array(
				1 => 'last_post_time',
				2 =>'post_count',
		);
		
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (!in_array($this->input['hgorder'], $thread_type_hgupdn))
		{
			$this->input['hgorder'] = 'pub_time';
		}
		if($this->input['_type'])
		{
			$this->input['hgorder']=$thread_type_hgupdn[$this->input['_type']];
		}
		
		$orderby = ' ORDER BY t.' . $this->input['hgorder']  . ' ' . $descasc ;
		
		return $condition . $orderby;	
	}
}

/**
 *  程序入口
 */
$out = new threadShowApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
