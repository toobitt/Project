<?php
require('global.php');
define('MOD_UNIQUEID','template_classify');//模块标识
class templateClassifyUpdateApi extends adminUpdateBase
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
		include(CUR_CONF_PATH . 'lib/template_classify.class.php');
		$this->obj = new templateClassify();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function create()
	{
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('template_classify',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		if(empty($this->input['name']))
		{
			$this->errorOutput("请添加模板分类名称");
		}
		
		$info = array();
		//新建页面默认值
		$info = array(
			'site_id'		=> $this->input['siteid'],
			'name'			=> $this->input['name'],
		);
		if(intval($this->input['fid']))
		{
			$info['fid'] = intval($this->input['fid']);
		}
		else 
		{
			$info['fid'] = 0;
		}
		$ret = $this->obj->create($info);
		$upinfo = array(
			'id' 			=> 		$ret,
			'sort_dir'		=>	 	CUR_TEMPLATE_PATH.$this->input['siteid'].'/'.$ret.'/',
		);
		$re = $this->obj->update($upinfo);
		$tmp = array();
		$tmp['id'] = $ret;
		$this->addItem($tmp);
		$this->output();
	}
	
	function update()
	{	
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('template_classify',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		$info = array();
		//获取模板分类id
		$info['id'] = $this->input['id'];
		//获取分类名称
		$info['name'] = urldecode($this->input['name']);
		//获取描述
		$info['brief'] = urldecode($this->input['brief']);
		//更新时间
		$info['update_time'] = TIMENOW;
		$ret = $this->obj->update($info);
		$this->addItem($ret);
		$this->output();
	}

	function delete()
	{			
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('template_classify',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		$id = $this->input['id'];
		if(empty($id))
		{
			$this->errorOutput("请选择需要删除的模板分类");
		}
		
		$sql_ = "select id from " . DB_PREFIX . "templates where  sort_id = " .$id;
		$q_ = $this->db->query_first($sql_);
		if($q_['id'])
		{
			$this->errorOutput("请删除分类下的模板");
		}
		$ret = $this->obj->delete($id);
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
                    $sql ="UPDATE " . DB_PREFIX . "template_sort SET";

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

$out = new templateClassifyUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>