<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: card.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/card.class.php';
require_once(ROOT_PATH . 'frm/node_frm.php');
require_once(ROOT_PATH . 'lib/class/auth.class.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishconfig.class.php');	
define('MOD_UNIQUEID', 'card'); //模块标识

class cardApi extends adminReadBase
{
	private $card;
	
	public function __construct()
	{
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'create'	=>'增加',
		'update'	=>'修改',
		'delete'	=>'删除',
		'audit'		=>'审核',
		'display'   =>'启用',
		/*'_node'=>array(
			'name'=>'',
			'filename'=>'',
			'node_uniqueid'=>'',
			),*/
		);
		
		parent::__construct();
		$this->card = new cardClass();
		$this->auth = new Auth();
		$this->publishcontent = new publishcontent();
		$this->pubconfig = new publishconfig();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->card);
	}
	
	public function index()
	{
		
	}
	
	/**
	 * 信息列表
	 */
	public function show()
	{
		/***********权限控制**************/
		$this->verify_content_prms();
		/*************************/
		
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$card_info = array();
		$card_info['data'] = array();
		$card_info['data'] = $this->card->show($offset, $count, $condition);
		$card_info['css'] = array();
		$card_info['css'] = $this->card->showcss();
		$this->setXmlNode('card_info', 'card');
		
		if ($card_info)
		{	
			foreach($card_info as $k=>$v){
				$this->addItem_withkey($k,$v);
			}
		}
		
		$this->output();
	}
	
	/**
	 * 信息数据总数
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->card->count($condition);
		echo json_encode($info);
	}

	/**
	**	卡片编辑
	**/
	public function detail()
	{
		
		/***********权限控制**************/
		$this->verify_content_prms(array('_action'=>'show'));
		/***********权限控制**************/
		
		$id = trim($this->input['id']);
		if(!$id){
			$this->errorOutput(OBJECT_NULL);
		}
		
		$info = array();
		$info = $this->card->detail($id);
		
		//卡片所有包含新闻
		$info['newslist'] = array();
		$info['newslist'] = $this->card->shownewslist($id);
		foreach($info['newslist'] as $key=>$value)
		{
			$info_indexpic = unserialize($value['indexpic']);
			$info['newslist'][$key]['indexpic'] = $info_indexpic['host'].$info_indexpic['dir'].$info_indexpic['filepath'].$info_indexpic['filename'];

			if($value['source_from']==0) {
				$info['newslist'][$key]['content_id'] = $info['newslist'][$key]['id'];
			}
			
			$info['newslist'][$key]['childs_data'] = unserialize($value['childs_data']);
		}
		
		$this->addItem($info);
		$this->output();
	}
	
	
	/**
	** 发布库的内容
	**/
	public function showpublish($sea_info)
	{
		require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
		$this->puscont = new publishcontent();
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['countt'] ? intval($this->input['countt']) : 20;
		$offset = intval(($pp - 1)*$count);		
		$data = array(
			'offset'	  		=> $offset,
			'count'		  		=> $count,
			'client_type'		=>	'2',
			'need_count'		=> '1',
		);
		$info = array();
		$info = $sea_info;
		//查询
		if($info['modules'])
		{
			$data['bundle_id'] = $info['modules'];
		}
		
		//查询栏目名称
		if($info['column_name'])
		{
			$data['column_name'] = $info['column_name'];
		}
		
		//查询时间
		if($info['date_search'])
		{
			$data['date_search'] = $info['date_search'];
		}
		
		//查询标题
		if($info['k'])
		{
			$data['k'] = $info['k'];
		}
		
		//查询创建的起始时间
		if($info['start_time'])
		{
			$data['starttime'] = $info['start_time'];
		}
		
		//查询创建的结束时间
		if($info['end_time'])
		{
			$data['endtime'] = $info['end_time'];
		}
		
		//查询权重
		if(isset($info['start_weight']) && intval($info['start_weight'])>=0)
		{
			$data['start_weight'] = $info['start_weight'];
		}
		if(isset($info['end_weight']) && intval($info['end_weight'])>=0)
		{
			$data['end_weight'] = $info['end_weight'];
		}
		
		$re = $this->puscont->get_content($data);
		$return = $this->publishcontent->get_pub_content_type();

		if(is_array($return[0]))
		{
			foreach($return[0] as$k=>$v)
			{
				$bundles[$v['bundle']] = $v['name'];
			}
		}
		$columns = $this->get_column();
		if(is_array($re['data']))
		{
			foreach($re['data'] as $k=>$v)
			{
				$co_names = array();
				if($v['column_id'])
				{
					$co_arr = explode(" ",$v['column_id']);
					foreach($co_arr as $ke=>$va)
					{
						$co_names[] = $columns[$va];
					}
				}
				$v['column_name'] = implode(" ",$co_names);
				//$v['app_name']	=	$apps[$v['bundle_id']];
				$v['module_name']	=	$bundles[$v['bundle_id']];
				$v['pic'] = json_encode($v['indexpic']);
				$ret[] = $v;
			}
		}
		
		$total_num =$re['total'];//总的记录数
		$page_info = array();
		//总页数
		if(intval($total_num%$count) == 0)
		{
			$page_info['total_page']    = intval($total_num/$count);
		}
		else 
		{
			$page_info['total_page']    = intval($total_num/$count) + 1;
		}
		$page_info['total_num'] = $total_num;//总的记录数
		$page_info['page_num'] = $count;//每页显示的个数
		$page_info['current_page']  = $pp;//当前页码
		
		$retu['info'] = $ret;
		$retu['column_info'] = $return;
		$retu['page_info'] = $page_info;
		$retu['page_data'] = $data;
		
		
//分页开始
if($retu['page_info']['total_page']>1)
{
$prgf_page = $retu['page_info']['current_page'] - 1;
$next_page = $retu['page_info']['current_page'] + 1;
if(!$retu['page_data']['date_search'])
{
	$retu['page_data']['date_search'] = 1;
}
if(!$retu['page_data']['bundle_id'])
{
	$retu['page_data']['bundle_id'] = 0;
}
if(!$retu['page_data']['start_weight'])
{
	$retu['page_data']['start_weight'] = 0;
}
if(!$retu['page_data']['end_weight'])
{
	$retu['page_data']['end_weight'] = 100;
}
$sea_con = "date_search=".$retu['page_data']['date_search']."&modules=".$retu['page_data']['bundle_id']."&k=".$retu['page_data']['k']."&start_time=".$retu['page_data']['starttime']."&end_time=".$retu['page_data']['endtime']."&start_weight=".$retu['page_data']['start_weight']."&end_weight=".$retu['page_data']['end_weight'];

$pagelink = '<div align="center" class="hoge_page">';
$pagelink .= '<span class="page_all">'.$retu['page_info']['total_page'].'页/'.$retu['page_info']['total_num'].'条</span>';
$page_arr = array(20,40,60,80,100);
$pagelink .= '<select style="float:left;vertical-align:middle;margin:0 5px;" onchange="location.href=this.value;">';
for($i=0;$i<count($page_arr);$i++)
{
	$selected = $count == $page_arr[$i] ? 'selected' : '';
	$pagelink .= '<option value="?mid=492&infrm=1&a=newslist&page='.$prgf_page.'&date_search=1&modules=0&k=&start_time=&end_time=&start_weight=0&end_weight=100&countt='.$page_arr[$i].'" '.$selected.'>每页'.$page_arr[$i].'</option>';
}
$pagelink .= '</select>';
if($retu['page_info']['current_page']>1)
{
$pagelink .= '<span class="page_next"><a  href="?mid=492&infrm=1&countt='.$count.'&a=newslist&page=1&'.$sea_con.'" id="firstpage_" >|&lt;</a></span>';
$pagelink .= '<span class="page_next"><a  href="?mid=492&infrm=1&countt='.$count.'&a=newslist&page='.$prgf_page.'&'.$sea_con.'">&lt;&lt;</a></span>';
}

$pre_f = $retu['page_info']['current_page']-2;
if($pre_f<=1)
   {
      $pre_f = 1;
   }
$next_f = $retu['page_info']['current_page']+2;
if($next_f>=$retu['page_info']['total_page'])
{
   $next_f = $retu['page_info']['total_page'];
}
for($i=1;$i<=$retu['page_info']['total_page'];$i++)
    {
       if($i>=$pre_f && $i<=$next_f)
       {
                    if($i==$retu['page_info']['current_page'])
                    {
                        $pagelink .= "<span class='page_cur' id='pagelink_". $i ."'>".$i."</span>";
                    }
                    else
                    {
                    	$pagelink .= "<span id='pagelink_". $i ."'><a class='page_bur' title='". $i ."' href='?mid=492&infrm=1&countt=".$count."&a=newslist&page= ". $i ."&".$sea_con."  ' >".$i."</a></span>";
                    }
        }
}
 
if($retu['page_info']['current_page']<$retu['page_info']['total_page'])
{
         $pagelink .= '<span class="page_next"><a href="?mid=492&infrm=1&countt='.$count.'&a=newslist&page='.$next_page.'&'.$sea_con.'">&gt;&gt;</a></span>';
  		 $pagelink .= '<span class="page_next"><a href="?mid=492&infrm=1&countt='.$count.'&a=newslist&page='.$retu['page_info']['total_page'].'&'.$sea_con.'" id="lastpage_">&gt;|</a></span>';
}
$pagelink .= '</div>';
}
//分页结束
		$retu['pagelink'] = $pagelink;
		return $retu;
	}
	
	
	//获取栏目
	public function get_column()	
	{	
		$publish_columns = $this->pubconfig->get_column();
		foreach($publish_columns as $k=>$v)
		{
			$columns[$v['id']]	= $v['name'];
		}
		return $columns;
	}
	
	//根据栏目名称模糊获取栏目
	public function get_column_by_name()	
	{	
		$name = trim($this->input['column_name']);
		if($name)
		{
			$ret = $this->pubconfig->get_column(' id,name ',' and name like "%'.$name. '%"');
		}
		if(!$ret)
		{
			$ret = array();
		}
		$out = !empty($ret) ? $ret : array();
		$this->addItem($out);
		$this->output();		
	}
	
	//输出iframe里面的发布库列表
	public function newslist()
	{
		$sea_info = array();
		$sea_info = $this->input;
		$newslist = array();
		$newslist = $this->showpublish($sea_info);
		$this->addItem($newslist);
		$this->output();
	}
	
	
	/**
	 * 查询条件
	 * @param Array $data
	 */
	private function get_condition()
	{	
		
		####是否有权限查看他人数据####
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else
			{
				//组织以内
				if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
				{
					$condition .= ' AND org_id IN('.$this->user['slave_org'].')';
				}
			}
		}
		####增加权限控制 用于显示####
		
		//关键字查询 received_phone
		if(trim(urldecode($this->input['key'])))
		{
			$condition .= ' AND  title  LIKE "%'.trim(($this->input['key'])).'%"';
		}
		
		return $condition;
	}
}
$out = new cardApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();

?>