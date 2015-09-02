<?php
define('MOD_UNIQUEID', 'news');
require('./global.php');
class news_request_info extends adminUpdateBase
{
   public function __construct()
	{
	   parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	public function update(){}
	public function delete(){}
	public function audit(){}
	public function sort(){}
	public function publish(){}	
    
	/**
	* 根据文章ID检索文章信息
	* @name show_opration
	* @access public 
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	* @param id int 文章id
	* @return array $return 文章信息
	*/
	public function show_opration()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput('ID不能为空');
		}
		$sql="select a.* ,c.*,s.name from ((" . DB_PREFIX . "article a left join " . DB_PREFIX . "article_contentbody c on a.id=c.articleid) left join " .DB_PREFIX . "sort s on a.sort_id=s.id) where a.id=" . $this->input['id'];
		$return=$this->db->query_first($sql);

        if(!empty($return['indexpic']))
		{
			//查询索引图片地址
			$sql="select * from " .DB_PREFIX ."material where material_id=" . $return['indexpic'];
			$q=$this->db->query_first($sql);
			$size=array(
				'label' =>'400x300',
				'width' =>400,
				'height' =>300,
				);
			if(!empty($q))
			{
				 $return['url']=hg_material_link($q['host'],$q['dir'],$q['filepath'],$q['filename'],$size['label'] . '/');
			}
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

}

$out=new news_request_info();
if(!method_exists($out,$_INPUT['a']))
{
	$action='show_opration';
}
else
{
	$action=$_INPUT['a'];
}
$out->$action();
?>