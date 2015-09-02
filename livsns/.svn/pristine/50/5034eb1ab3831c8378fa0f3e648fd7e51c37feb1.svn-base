<?php
require('global.php');
define('MOD_UNIQUEID','page_manage');//模块标识
class pageManageUpdateApi extends adminUpdateBase
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/page_manage.class.php');
		$this->obj = new pageManage();
		require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pub = new publishconfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('page_manage',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		$fid = $this->input['fid'];
		if(strstr($fid,"page_id")!==false)
		{
			$page_data_id = str_replace('page_id','',$fid);
			$get_page = explode($this->settings['separator'],$page_data_id);
			$sql = "SELECT * FROM  ". DB_PREFIX ."page_manage WHERE id = ".$get_page[0];
			$r = $this->db->query_first($sql);
			if($r)
			{
				$fid = $get_page[0];
			}
		}
		
		if(empty($this->input['name']))
		{
			$this->errorOutput("请添加页面名称");
		}
		
		$info = array();
		//新建页面默认值
		$info = array(
			'site_id'		=> $this->input['siteid'],
			'name'			=> $this->input['name'],
			'org_id'		=> $this->user['org_id'],
		);
		if($fid)
		{
			$info['fid'] = $fid;
		}
		else 
		{
			$info['fid'] = 0;
		}
		$ret = $this->obj->create($info);
		$tmp = array();
		$tmp['id'] = $ret;
		
		$info['id'] = $ret;
		
		$this->addLogs('新增页面' , '' , $info , $info['name']);
		
		$this->addItem($tmp);
		$this->output();
	}
	
	public function update()
	{	
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('page_manage',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		$info = array();
		
		if(!$name = $this->input['name'])
		{
			$this->errorOutput("请填写页面名称");
		}
		//获取页面标识
		if(!$sign = $this->input['sign'])
		{
			$this->errorOutput("请填写页面标识");
		}
		if($this->input['column_dir'])
		{
			$column_dir = trim(urldecode($this->input['column_dir']),'/');
			$column_dir = '/'.$column_dir;
		}
		else
		{
			$column_dir = '';
		}
		if($this->input['dir'])
		{
			$dir = trim(urldecode($this->input['dir']),'/');
			$dir = '/'.$dir;
		}
		else
		{
			$dir = '';
		}
		$id = $this->input['id'];
		if(strstr($id,"page_id")!==false)
		{
			//点击的页面类型
			$id = str_replace('page_id','',$id);
			$get_id = explode($this->settings['separator'],$id);
			$id = $get_id[0];
		}
		

		$info = array(
			'id'	        => 	 $id,
			'name'			=> 	 $name,
			'title'			=> 	 $name,
            'has_child'		=> 	 $this->input['has_child'],
            'has_content'	=> 	 $this->input['has_content'],
			'host'			=>	 rtrim(urldecode($this->input['host']), '/'),	
			'column_dir'	=>	 $column_dir,
			'dir'			=>	 $dir,
			'sign'			=>   $sign,
			'app'         	=>   $this->input['app'],
			'is_linkapp'    =>   $this->input['is_linkapp'],
			'is_sort'    	=>   $this->input['is_sort'],
			'domain'		=>   $this->input['domain'],
			'column_domain'	=>   $this->input['column_domain'],
            'file_name'		=>   $this->input['file_name'],
			'is_next_domain'=>   $this->input['is_next_domain'],
            'field'			=>   $this->input['field'],
            'count_field'	=>   $this->input['count_field'],
            'offset_field'	=>   $this->input['offset_field'],
            'name_field'	=>   $this->input['name_field'],
            'father_field'	=>   $this->input['father_field'],
            'last_field'	=>   $this->input['last_field'],
            'colindex'		=>   $this->input['colindex'],
            'list_name'		=>   $this->input['list_name'],
            'page_data_id'	=>   $this->input['page_data_id'],
			'update_time'	=>   TIMENOW,		
		);
		if($info['is_linkapp']  =='0')
		{
			$info['maketype'] 	= $this->input['maketype'];
		}
		$argument = array();
		if($this->input['argument_name'] || $this->input['ident'] || $this->input['value'])
		{
			if(is_array($this->input['argument_name']))
			{
				foreach($this->input['argument_name'] as $k=>$v)
				{
					$argument['argument_name'][$k] = urldecode($this->input['argument_name'][$k]);
				}
			}
			$argument['ident'] = $this->input['ident'];
			if(is_array($this->input['value']))
			{
				foreach($this->input['value'] as $k=>$v)
				{
					$argument['value'][$k] = urldecode($this->input['value'][$k]);
				}
			}
			$argument['add_status'] = $this->input['add_status'];
		}
		
		$info['argument'] = serialize($argument);
		if(!$info['domain'])
		{
			$sites = $this->pub->get_site_first('',$this->input['site_id']);
			$info['url']	=	$sites['weburl'].'/';
			if($info['next_domain'])
			{
				$info['url'] .= $info['next_domain'].'/'.$sign.'/';
			}
			else
			{
				$info['url'] .= $sites['sub_weburl'].'/'.$sign.'/';
			}
		}
		else
		{
			$info['url']	=	$info['domain'].'/'.$info['next_domain'].'/'.$sign.'/';
		}
		
		$s =  "SELECT * FROM " . DB_PREFIX . "page_manage WHERE id = " . $id;
		$pre_data = $this->db->query_first($s);
		
		$ret = $this->obj->update($info);
		
		$sq =  "SELECT * FROM " . DB_PREFIX . "page_manage WHERE id = " . $id;
		$up_data = $this->db->query_first($sq);
		
		$this->addLogs('更新页面' , $pre_data , $up_data , $pre_data['name']);
		
		$this->addItem($ret);
		$this->output();
	}

	public function delete()
	{		
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('page_manage',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		$id = $this->input['id'];
		if(strstr($id,"page_id")!==false)
		{
			//点击的页面类型
			$id = str_replace('page_id','',$id);
			$get_id = explode($this->settings['separator'],$id);
			$id = $get_id[0];
		}
		if(empty($id))
		{
			$this->errorOutput("请选择需要删除的页面");
		}
		
		$sqll =  "SELECT * FROM " . DB_PREFIX . "page_manage WHERE id IN (" . $id . ")";
		$sll = $this->db->query($sqll);
		$ret = array();
		while($rowl = $this->db->fetch_array($sll))
		{
			$pre_data[] = $rowl;
		}
		
		$ret = $this->obj->delete($id);
		
		$this->addLogs('删除页面' , $pre_data , '', '删除页面'.$id);
		
		
		$this->addItem($ret);
		$this->output();
		
	}
	
	    //排序
    public function drag_order()
    {
        $sort = json_decode(html_entity_decode($this->input['sort']),true);

        if(!empty($sort))
        {
            foreach($sort as $key=>$val)
            {
                $data = array(
                        'order_id' => $val,
                );
                if(intval($key) && intval($val))
                {
                    $sql ="UPDATE " . DB_PREFIX . "page_manage SET";

                    $sql_extra=$space=' ';
                    foreach($data as $k => $v)
                    {
                        $sql_extra .=$space . $k . "='" . $v . "'";
                        $space=',';
                    }
                    $sql .=$sql_extra.' WHERE id='.$key;
                    $this->db->query($sql);
                }
            }
        }
        $this->addItem('success');
        $this->output();
    }
	
	public function audit()
	{
	}
	public function sort()
	{
	}
	public function publish()
	{
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new pageManageUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>