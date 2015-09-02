<?php
define('MOD_UNIQUEID','seekhelp_node');
require_once('global.php');
require_once CUR_CONF_PATH.'lib/sort_mode.php';
require_once(ROOT_PATH . 'frm/node_frm.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
class seekhelp_node_updateApi extends nodeFrm
{
	private $material;
	public function __construct()
    {
    	parent::__construct();
    	$this->material = new material();
    	$this->sort = new sort_mode();
   		$this->setNodeTable('sort');
        $this->setNodeVar('seekhelp_node');
        $this->setNodeTreeFields(array_merge($this->nodeTreeFields,array(
        		'app_id',
        		'avatar',
        		'background',
        )));
    }

    public function __destruct()
    {
    	parent::__destruct();
    }


    public function update() 
    {
    	if (!$this->input['id'])
        {
        	$this->errorOutput(NOID);
            return ;
        }
        //更新错误数据
        $app_id = $this->input['app_id'];
        
//         if (!$this->input['name'])
//         {
//             $this->errorOutput(NO_SORT_NAME);
//         }
        $avatar = $this->uploadimg("avatar");
        $background = $this->uploadimg('background');
        
    	$data = array(
        	 'id' => intval($this->input['id']),
    		 'fid'=>intval($this->input['fid']),
        );
    	
    	if($this->input['app_id'])
    	{
    		$data['app_id'] = intval($this->input['app_id']);
    	}
    	if($this->input['brief'])
    	{
    		$data['brief'] = trim(urldecode($this->input['brief']));
    	}
    	if($this->input['name'])
    	{
    		$data['name'] = trim(urldecode($this->input['name']));
    	}
    	
    	if($avatar)
    	{
    		$data['avatar'] = $avatar;
    	}
    	if($background)
    	{
    		$data['background'] = $background;
    	}
    	
        //$this->verify_create_node($data['fid']);
        
		//初始化
		$this->initNodeData();
		//设置新增或者需要更新的节点数据
		$this->setNodeData($data);
		//设置操作的节点ID
		$this->setNodeID($data['id']);
		//更新方法
		$this->updateNode();
		$data['avatar'] = unserialize($avatar);
		$data['background'] = unserialize($background);
        $this->addItem($data);
        $this->output();
    }
    
    public function delete()
    {
    	if (!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }
        $this->initNodeData();
        //判断是否成功删除
        if($this->batchDeleteNode($this->input['id']))
        {
            $this->addItem(array('id' => urldecode($this->input['id'])));
        }
        $this->output();
    }

    public function create()
    {
    	$avatar = $this->uploadimg("avatar");
    	$background = $this->uploadimg('background');

    	$data = array(
		        'name'=>trim(urldecode($this->input['name'])),
    			'app_id' => intval($this->input['app_id']),
    			'avatar' => $avatar,
		        'brief'=>trim(urldecode($this->input['brief'])),
    			'background' => $background,
		        'user_name'=>trim(urldecode($this->user['user_name'])),
		        'ip'=>hg_getip(),
		        'create_time'=>TIMENOW,
		        'update_time'=>TIMENOW,
    			'fid'=>intval($this->input['fid']),
        );
        if (!$data['name'])
        {
                $this->errorOutput(NO_SORT_NAME);
        }
		$this->initNodeData();
		//设置新增或者需要更新的节点数据
		$this->setNodeData($data);
		//增加节点无需设置操作节点ID
		if($nid = $this->addNode())
		{
		       $data['id'] = $nid;
		       $avatar = unserialize($avatar);
		       $background = unserialize($background);
		       $data['avatar'] = $avatar;
		       $data['background'] = $background;
		       $this->addItem($data);
		 }
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
                                    $sql ="UPDATE " . DB_PREFIX . "sort SET";

                                    $sql_extra=$space=' ';
                                    foreach($data as $k => $v)
                                    {
                                            $sql_extra .=$space . $k . "='" . $v . "'";
                                            $space=',';
                                    }
                                    $sql .=$sql_extra.' WHERE id='.$key;
                                    $this->db->query($sql);
                            }
                            $id[] = $key;
                    }
            }
            $this->addItem('success');
            $this->output();
    }
    
    /**
     * 获取sort_id
     */
    public function getSortByappId()
    {
    	$app_id = $this->input['app_id'];
    	$condition = " AND app_id='".$app_id."'";
    	//社区数据 sort_id
    	$sort_data = $this->getNodesList($condition,true);
    	foreach ($sort_data as $k=>$v)
    	{
    		$sort_data = $v;
    	}
    	$this->addItem($sort_data);
    	$this->output();
    }
    
    /**
     * 删除一个app重复的社区
     * @param unknown $app_id
     */
    public function delete_repeat_data()
    {
    	$app_id = intval($this->input['app_id']);
    	if(!$app_id)
    	{
    		return false;
    	}
    	$condition = " AND app_id=".$app_id."";
    	$app_data = $this->getNodesList($condition,true);
    	sort($app_data);
    	if($app_data)
    	{
    		unset($app_data[0]);
    	}
    	foreach ($app_data as $v)
    	{
    		$this->sort->delete($v['id']);
    	}
    }
    
    private function uploadimg($var_name)
    {
    	if($_FILES[$var_name])
    	{
    		//处理avatar图片
    		if($_FILES[$var_name] && !$_FILES[$var_name]['error'])
    		{
    			$_FILES['Filedata'] = $_FILES[$var_name];
    			$material = new material();
    			$img_info = $material->addMaterial($_FILES);
    			if($img_info)
    			{
    				$avatar = array(
    						'host' 		=> $img_info['host'],
    						'dir' 		=> $img_info['dir'],
    						'filepath' 	=> $img_info['filepath'],
    						'filename' 	=> $img_info['filename'],
    						'width'		=> $img_info['imgwidth'],
    						'height'	=> $img_info['imgheight'],
    						'id'        => $img_info['id'],
    				);
    				$avatar = @serialize($avatar);
    			}
    		}
    	}
    	
		return $avatar;
    }
}
$out = new seekhelp_node_updateApi();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'create';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>