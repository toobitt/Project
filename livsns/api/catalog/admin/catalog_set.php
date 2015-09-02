<?php
/**
 **编目管理*
 */
require('./global.php');
define('MOD_UNIQUEID','catalog_set');
require_once(CUR_CONF_PATH . 'core/catalog.core.php');
include_once (CUR_CONF_PATH . 'lib/manage.class.php');

class catalogSet extends adminReadBase
{   
	
	public function __construct()
	{
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'manage'	=>'管理',
		);
		parent::__construct();
		/*********权限验证开始*********/
		$this->verify_content_prms(array('_action'=>'show'));
		/*********权限验证结束*********/
		$this->manage = new manage();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index()
	{
		//
	}
	
 	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$sql = "SELECT f.*,s.zh_name AS form_style_name,fs.catalog_sort_name AS form_sort_name
               FROM " . DB_PREFIX . "field AS f ";
	   $sql .= " LEFT JOIN " . DB_PREFIX . "style AS s ON f.form_style = s.id LEFT JOIN ". DB_PREFIX ."field_sort AS fs ON f.catalog_sort_id = fs.id";
	   $sql .= " LEFT JOIN " . DB_PREFIX . "app_map AS am ON am.field_id = f.id WHERE 1";
	   $condition = $this->get_condition();		
	   if ($condition) $sql .= $condition;
	   $sql .= " ORDER BY f.order_id DESC ";
	   if($offset || $count)
	   {
	   	  $sql .= " LIMIT " . $offset . " , " . $count ;  //分页
	   }
	  
	   $q = $this->db->query($sql);
       while($data = $this->db->fetch_array($q))
	   {	
			//$data['form_style_name'] = $data['form_style_name'];		//编目样式	
			$data['create_time'] = date('Y-m-d H:i:s',$data['create_time']);
			$data['update_time'] = date('Y-m-d H:i:s',$data['update_time']);
			$this->addItem($data);
		}
		$this->output();		
	}
	public function sortname()
	{
		$sql = "SELECT id as catalog_sort_id,catalog_sort_name FROM " . DB_PREFIX . "field_sort WHERE 1 ";
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			$this->addItem($row);
		}

		$this->output();
	}
	/**
	 * 
	 * 输出所有样式,用于编目表单编辑页展示(在关联数据里调用) ...
	 */
	public function typename()
	{
		$styles=array();
		$styles = $this->manage->get_styles();           //获取所有样式
		if(is_array($styles)&&$styles)
		{
			foreach ($styles as $key => $val)
			{
				$this->addItem($val);
			}
		}
		else{
			$this->addItem($styles);
		}
		$this->output();
	}

	
	public function count()
	{
		$condition = $this->get_condition();		
		$sql = 'SELECT COUNT(*) AS total FROM ' .DB_PREFIX. 'field f WHERE 1';
		if ($condition) $sql .= $condition;
		//exit($sql);
		exit(json_encode($this->db->query_first($sql)));
	}
    
	//获取某个编目的配置
	public function detail()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
        $sql = "SELECT f.*,s.zh_name as form_style_name,am.app_uniqueid
               FROM " . DB_PREFIX . "field f ";
	    $sql .= " LEFT JOIN " . DB_PREFIX . "style s ON f.form_style = s.id ";
	    $sql .= " LEFT JOIN " . DB_PREFIX . "app_map AS am ON am.field_id = f.id ";
	    $sql .= "WHERE f.id=" . $id;
	    $q = $this->db->query($sql);
		while($data = $this->db->fetch_array($q))
		{
	//		$data['form_style_name'] = $data['form_style_name'];		//编目样式	
			$data['create_time'] = date('Y-m-d H:i:s',$data['create_time']);
			$data['update_time'] = date('Y-m-d H:i:s',$data['update_time']);	
			$data['catalog_default']=explode(',', $data['catalog_default']);		
		    if($data['app_uniqueid'])
			{
				$data['app_uniqueid'] = explode(',',$data['app_uniqueid']);
			} //编目所属应用
			$this->addItem($data);
		}
		$this->output();
	}

	//获取应用
	
	function get_catalog_app()   
	{
		include_once ROOT_PATH . 'lib/class/curl.class.php';
		$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
		$curl->initPostData();
		$curl->addRequestData('use_catalog', 1);
		$ret = $curl->request('applications.php');
		if(is_array($ret) && $ret)
		{
			foreach($ret as $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	function get_condition()
	{
		$conditon = '';
		//编目类型
		$form_style = isset($this->input['form_style_id']) ? intval($this->input['form_style_id']) : '';
        if($form_style && $form_style > 0)
        {
        	$conditon .= " AND f.form_style = " . $form_style ; 
        }
        $catalog_sort_id = isset($this->input['_id']) ? intval($this->input['_id']) : 0;
        
        /**************权限控制开始**************/
        /**
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{			
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND f.user_id = '.$this->user['user_id'];//不允许查看他人数据
			}
			elseif ($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_org'])
			{	
				$condition .= ' AND f.org_id IN (' . $this->user['slave_org'] .')';//查看组织内的数据
			}
			if($authnode=$this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'])
			{
				if(is_array($authnode))
				{	
					//如果没有_id就查询出所有权限所允许的节点下的视频包括其后代元素
					if(!$catalog_sort_id)
					{
						$condition .= " AND f.catalog_sort_id IN (".implode(',', $authnode).",0)";
					}
					else if(in_array($catalog_sort_id,$authnode))
					{
						$conditon .= " AND f.catalog_sort_id  = " . $catalog_sort_id ; 
					}
					else 
					{
						$this->errorOutput(NO_PRIVILEGE);
					}
				}
			}
		}
		else 
		{
			if($catalog_sort_id)
			{
					$conditon .= " AND f.catalog_sort_id  = " . $catalog_sort_id ; 
			}
		}
		*/
		/**************权限控制结束**************/
		if($catalog_sort_id)
		{
			$conditon .= " AND f.catalog_sort_id  = " . $catalog_sort_id ; 
		}	
		if (isset($this->input['switch']) && $this->input['switch'] != -1)//开关
		{
			$conditon .= ' AND f.switch = '.intval($this->input['switch']);
		}
		if (isset($this->input['bak']) && $this->input['bak'] != -1)//开关
		{
			$conditon .= ' AND f.bak = '.intval($this->input['bak']);
		}
		if (isset($this->input['required']) && $this->input['required'] != -1)//开关
		{
			$conditon .= ' AND f.required = '.intval($this->input['required']);
		}
        //编目应用
		$app_name = isset($this->input['app_uniqueid']) ? trim(urldecode($this->input['app_uniqueid'])): ''; 		
		$app_field = $this->manage->get_app_name($app_name);//编目所属应用		   	   
	    if($app_field)
	    {
		    if (count($app_field) == 1)
		    {
			    $app_field = intval(current($app_field));
		    }
		    else
		    {
			    $app_field = implode(',', $app_field);
		    }
	   	
	   	    $conditon .= " AND f.id in (".$app_field.") ";
	    }
	    
	    //编目名字
	    $zh_name = isset($this->input['k']) ? trim(urldecode($this->input['k'])): '';
        if($zh_name)
        {
        	$conditon .= " AND f.zh_name like '%" . $zh_name ."%' "; 
        }
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$conditon .= " AND f.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$conditon .= " AND f.create_time <= ".$end_time;
		}
		if($this->input['catalog_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['catalog_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$conditon .= " AND  f.create_time > ".$yesterday." AND f.create_time < ".$today;
					break;
				case 3://今天的数据
					$conditon .= " AND  f.create_time > ".$today." AND f.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$conditon .= " AND  f.create_time > ".$last_threeday." AND f.create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$conditon .= " AND f.create_time > ".$last_sevenday." AND f.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}    
        return $conditon;
        
	}


}

$out=new catalogSet();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='show';
}
$out->$action();
?>