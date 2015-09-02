<?php
include_once('./global.php');
define('MOD_UNIQUEID','cp_help_m');//模块标识

class helpupdateApi extends BaseFrm
{
	/**
	 * 初始化
	 * Enter description here ...
	 */
	function __construct()
	{
		parent::__construct();
		require_once  'lib/helpLib.class.php';
	}
	/**
	 * 输入方法名不存在
	 * Enter description here ...
	 */
	public function unknow()
	{
		$this->errorOutput("你搜索得方法不存在");
	}
	/**
	 * 用户在线判断
	 */
	public function checkUserExit()
	{
		//$this->user = array('user_id'=>84);
		if(!$this->user['user_id'])
		{
			$this->errorOutput("用户没有登录");
		}
		return $this->user['user_id'];
	}
	
	/**
	 * 增加帮助
	 */
	public function create()
	{
		$data = array();
		//标题
		$data['subject'] = trim(htmlspecialchars_decode(urldecode($this->input['subject'])));
		//内容
		$data['content'] = trim(htmlspecialchars_decode(urldecode($this->input['content'])));
		//分类id
		$data['sort_id'] = trim($this->input['sort_id']);
		//排序id
		$data['order_id'] = $this->input['order_id'] ? trim($this->input['order_id']) : 0;
		//初始化方法
		$this->libhelp = new helpLib();
		$result = array();
		$result = $this->libhelp->get('help','id,subject,content,sort_id,order_id', array('subject'=>$data['subject']), 0, -1, array(), array(), array());
		if(!$result)
		{
			//增加创建时间
			$data['create_time'] = TIMENOW;
			//来源部分
			$data['app_name'] = $this->user['display_name'];
			$id = $this->libhelp->insert('help',$data);
			if($id)
			{
				//添加关键词，加入迅搜
				
				//添加关联关系
				
			}
		}
		else 
		{
			$id = $result['id'];
		}
		$this->addItem($id);
		$this->output();
	}
	/**
	 * 添加关联关系
	 */
	public function  relatedCreate()
	{
		//帮助文档id
	 	$id = $this->input['id'] ? trim($this->input['id']) : 0;
		if($id)
	 	{
		 	$related_id = $this->input['related_id'] ? trim($this->input['related_id']) : 0;
		 	if($related_id)
		 	{
		 		$result = array();
		 		//初始化方法
				$this->libhelp = new helpLib();
				$result = $this->libhelp->get('help','id,related_num', array('id'=>$id.','.$related_id), 0, -1, array(), array(), array());
				if($result)
				{
					foreach($result as $k=>$v)
					{
						if($v['id'] == $id)
						{
							if($v['related_num'])
							{
								$this->libhelp->update('help',array('related_num'=>$v['related_num'].','.$related_id),array('id'=>$id),array());
							}
							else
							{
								$this->libhelp->update('help',array('related_num'=>$related_id),array('id'=>$id),array());
							}
						}
					}
				}
		 		$this->addItem($id);
				$this->output();
		 	}
		 	else 
			{
				$this->errorOutput('未传入关联ID');
			}
	 	}
	 	else 
		{
			$this->errorOutput('未传入被关联ID');
		}
	}
	/**
	 * 删除关联关系
	 */
	public function relatedDelete()
	{
		//帮助文档id
	 	$id = $this->input['id'] ? trim($this->input['id']) : 0;
		if($id)
	 	{
		 	$related_id = $this->input['related_id'] ? trim($this->input['related_id']) : 0;
		 	if($related_id)
		 	{
		 		$result = array();
		 		//初始化方法
				$this->libhelp = new helpLib();
				$result = $this->libhelp->get('help','related_num', array('id'=>$id), 0, 1, array(), array(), array());
				if($result)
				{
					$arr1 = $arr2 = array();
					$arr1 = explode(',',$related_id);
					$arr2 = explode(',',$result);
					$arr2 = array_diff($arr2, $arr1);
					$this->libhelp->update('help',array('related_num'=>(($arr2) ? implode(',',$arr2) : '')),array('id'=>$id),array());
				}
		 		$this->addItem($id);
				$this->output();
		 	}
		 	else 
			{
				$this->errorOutput('未传入关联ID');
			}
	 	}
	 	else 
		{
			$this->errorOutput('未传入被关联ID');
		}
	}
	/**
	 * 编辑关联关系
	 */
	public function relatedUpdate()
	{
		//帮助文档id
	 	$id = $this->input['id'] ? trim($this->input['id']) : 0;
		if($id)
	 	{
		 	$related_id = $this->input['related_id'] ? trim($this->input['related_id']) : 0;
		 	if($related_id)
		 	{
		 		$result = array();
		 		//初始化方法
				$this->libhelp = new helpLib();
				$result = $this->libhelp->get('help','related_num', array('id'=>$id), 0, 1, array(), array(), array());
				if($result)
				{
					$arr1 = $arr2 = array();
					$arr1 = explode(',',$related_id);
					$arr2 = explode(',',$result);
					$arr2 = array_diff($arr2, $arr1);
					$arr1 = array_diff($arr1, $arr2);
					$data = '';
					if($arr2)
					{
						$data = implode(',', $arr2);
					}
					if($arr1)
					{
						$data = ($data) ? $data.",".implode(',',$arr1) :  implode(',',$arr1);
					}
					$this->libhelp->update('help',array('related_num'=>$data),array('id'=>$id),array());
					
				}
		 		$this->addItem($id);
				$this->output();
		 	}
		 	else 
			{
				$this->errorOutput('未传入关联ID');
			}
	 	}
	 	else 
		{
			$this->errorOutput('未传入被关联ID');
		}
	}
	/**
	 * 编辑帮助文档
	 */
	 public function update()
	 {
	 	//帮助文档id
	 	$id = $this->input['id'] ? trim($this->input['id']) : 0;
	 	if($id)
	 	{
		 	//更新条件
		 	$data = array();
		 	//标题
		 	if (isset($this->input['subject']))
		 	{
				$data['subject'] = trim(htmlspecialchars_decode(urldecode($this->input['subject'])));
		 	}
		 	//内容
			if (isset($this->input['content']))
		 	{
				$data['content'] = trim(htmlspecialchars_decode(urldecode($this->input['content'])));
		 	}
		 	//分类id
			if (isset($this->input['sort_id']))
		 	{
				$data['sort_id'] = trim($this->input['sort_id']);
		 	}
			//排序id
			if (isset($this->input['order_id']))
		 	{
				$data['order_id'] = $this->input['order_id'] ? trim($this->input['order_id']) : 0;
		 	}
		 	$result = array();
		 	//初始化方法
			$this->libhelp = new helpLib();
		 	$result = $this->libhelp->get('help','id,subject,content,sort_id,order_id', array('id'=>$id), 0, 1, array(), array(), array());
		 	if($result)
		 	{
		 		if($result['subject'] != $data['subject'] || $result['content'] != $data['content'] || $result['sort_id'] != $data['sort_id'])
		 		{
		 			$state = $this->libhelp->update('help', $data, array('id'=>$id), array(), array());
		 			if($state)
		 			{
		 				//更新迅搜接口
		 				
		 				//更新数目
						
		 				//更新父集信息
		 				if ($result['sort_id'] != $data['sort_id'])
		 				{
		 					//TODO
		 				}
		 			}
		 		}		
		 	}
		 	$this->addItem($id);
			$this->output();
	 	}
	 	else 
		{
			$this->errorOutput('未传入查询ID');
		}
	 }
	 /**
	  * 删除帮助
	  */
	 public function delete()
	 {
	 	$id = trim($this->input['id']);
	 	if($id)
	 	{
	 		//初始化方法
			$this->libhelp = new helpLib();
	 		$result = array();
	 		$result = $this->libhelp->get('help','id', array('id'=>$id), 0, -1, array(), array(), array());
	 		if($result)
	 		{
	 			$state = false;
	 			$state = $this->libhelp->delete('help', array('id'=>$id), array(), array());
	 			if($state)
	 			{
	 				//更新迅搜接口，删除文件内容
	 				$this->addItem($id);
					$this->output();
	 			}
		 		else 
		 		{
		 			$this->errorOutput('删除操作失败');
		 		}
	 			
	 		}
	 		else 
	 		{
	 			$this->errorOutput('传入的删除ID信息不存在');
	 		}
	 	}
	 	else 
		{
			$this->errorOutput('未传入操作ID');
		}
	 }
	 /**
	  * 插入分类
	  */
	 public function sortCreate()
	 {
	 	$sort_id = 0;
	 	$data = array();
	 	//分类名
	 	$data['sort_name'] = trim(htmlspecialchars_decode(urldecode($this->input['sort_name'])));
	 	if($data)
	 	{
		 	//分类描述
		 	$data['sort_desc'] = trim(htmlspecialchars_decode(urldecode($this->input['sort_desc'])));
		 	//父集id
		 	$data['parent_id'] = $this->input['parent_id'] ? trim($this->input['parent_id']) : 0;
		 	//初始化方法
			$this->libhelp = new helpLib();
			$result = array();
			$result = $this->libhelp->get('help_sort','sort_id,sort_name,sort_desc,parent_id', $data,0,1,array(),array(),array());
			if(!$result)
			{
				//增加创建时间
				$data['create_time'] = TIMENOW;
				//来源部分
				$data['app_name'] = $this->user['display_name'];
				$sort_id = $this->libhelp->insert('help',$data);
				if($sort_id)
				{
					//更新父集id
					$this->libhelp->update('help_sort',array('is_end'=>0),array('sort_id'=>$data['parent_id']), array());
				}
			}
			else 
			{
				$sort_id = $result['sort_id'];
			}
			$this->addItem($sort_id);
			$this->output();
	 	}
	 	else 
		{
			$this->errorOutput('未传入分类名');
		}
	 }
	 /**
	  * 分类编辑
	  */
	 public function sortUpdate()
	 {
	 	$sort_id = $this->input['sort_id'] ? trim($this->input['sort_id']) : 0;
	 	
	 	if($sort_id)
	 	{
	 		$data = array();
	 		//分类名
	 		$data['sort_name'] = trim(htmlspecialchars_decode(urldecode($this->input['sort_name'])));
		 	//分类描述
		 	$data['sort_desc'] = trim(htmlspecialchars_decode(urldecode($this->input['sort_desc'])));
		 	//父集id
		 	$data['parent_id'] = $this->input['parent_id'] ? trim($this->input['parent_id']) : 0;
		 	//初始化方法
			$this->libhelp = new helpLib();
			$result = array();
			$result = $this->libhelp->get('help_sort','sort_id,sort_name,sort_desc,parent_id', array('sort_id'=>$sort_id),0,1,array(),array(),array());
			if(!$result)
			{
				$this->errorOutput('分类不存在');
			}
			else 
			{
				if($result['sort_name'] != $data['sort_name'] || $result['sort_desc'] != $data['sort_desc'] || $result['parent_id'] != $data['parent_id'])
				{
					$this->libhelp->update('help_sort',$data, array('sort_id'=>$sort_id), array(), array());
				}
			}
			$this->addItem($sort_id);
			$this->output();
	 	}
	 	else 
		{
			$this->errorOutput('未传入分类id');
		}
	 }
	 /**
	  * 删除分类
	  * 删除原则1：删除前先检测是否有下级文件存在，否则不许删除
	  * 存在两种可能：1.存在下级分类；2.存在分类数据
	  * 删除原则2: 无论下面如何，删除时，把底下元素归为其父集的元素，会存在问题：其父集既是枝叶节点又有子集
	  */
	 public function deleteSort()
	 {
	 	$sort_id = $this->input['sort_id'] ? trim($this->input['sort_id']) : 0;
	 	if($sort_id)
	 	{
	 		$data = array();
	 		$this->libhelp = new helpLib();
			$result = array();
			$result = $this->libhelp->get('help_sort','sort_id,parent_id,is_end', array('sort_id'=>$sort_id),0,-1,array(),array(),array());
			if(!$result)
			{
				$this->errorOutput('分类不存在');
			}
			else 
			{
				foreach($result as $k=>$v)
				{
					//是否是结束
					$state = 0;
					$state = $this->checkSortData($sort_id);
					if($state)
					{
						$this->errorOutput('传入的分类id有下集分类，不可已删除');
					}
				}
				//删除分类
				$this->libhelp->delete('help_sort',array('sort_id'=>$sort_id));
			}
			$this->addItem($sort_id);
			$this->output();
	 	}
	 	else 
		{
			$this->errorOutput('未传入分类id');
		}
	 }
	 /**
	  * 更新父集节点的信息
	  */
	 public function updateParentData($old = 0, $new = 0)
	 {
	 	//获取父集信息
	 	$id = ($new ? $new  : '') . ($old ? ',' . $old : '');
	 	//初始化方法
	 	$this->libhelp = new helpLib();
 	 	if($id)
 	 	{
 	 		$data = $this->libhelp->get('help_sort','sort_id,is_end');
 	 	}
	 	if($old)
	 	{
	 		
	 	}
	 }
	 /**
	  * 检测是否存在下级分类数据
	  * 返回1表示存在下级分类返回2表示存在下级数据
	  */
	 public function checkSortData($sort_id)
	 {
	 	$result = 0;
	 	if($sort_id)
	 	{
	 		//初始化方法
	 		$this->libhelp = new helpLib();
	 		//获取是否有下级分类
	 		if($this->libhelp->get('help_sort','count(*) num',array('parent_id'=>$sort_id),0,1,array(),array(),array()))
	 		{
	 			$resutl = 1;
	 		}
			if($this->libhelp->get('help','count(*) num',array('sort_id'=>$sort_id),0,1,array(),array(),array()))
			{
				$resutl = 2;
			}
	 	}
	 	return $result;
	 }
	/**
	 * (non-PHPdoc)
	 * @see BaseFrm::__destruct()
	 */
	function __destruct()
	{
		parent::__destruct();
	}
}
/**
 *  程序入口
 */
$out = new helpupdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'unknow';
}
$out->$action();
?>
