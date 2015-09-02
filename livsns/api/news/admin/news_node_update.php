<?phpdefine('MOD_UNIQUEID','news_node');require_once('global.php');require_once(ROOT_PATH . 'frm/node_frm.php');class news_node_update extends nodeFrm{    public function __construct()    {            parent::__construct();            //检测是否具有配置权限           	$this->verify_setting_prms();   			$this->setNodeTable('sort');            $this->setNodeVar('news_node');    }    public function __destruct()    {            parent::__destruct();    }    public function update()     {            if (!$this->input['id'])            {                    $this->errorOutput(NOID);                    return ;            }            if (!$this->input['name'])            {                    $this->errorOutput(NOSORTNAME);            }    		$data = array(                'id' => intval($this->input['id']),                'name' => trim(urldecode($this->input['name'])),                'brief' => trim(urldecode($this->input['brief'])),//                'update_time' =>TIMENOW,//  				'user_name'=>$this->user['user_name'],//    			'ip'=>  hg_getip(),    			'fid'=>intval($this->input['fid']),            );            $this->verify_update_node($data['fid']);		    //初始化		    $this->initNodeData();		    //设置新增或者需要更新的节点数据		    $this->setNodeData($data);		    //设置操作的节点ID		    $this->setNodeID($data['id']);		    //更新方法		    $this->updateNode();		    $this->addLogs('修改文稿节点','',$data,$data['name']);            $this->addItem($data);            $this->output();    }    public function delete()    {        if (!$this->input['id'])        {            $this->errorOutput(NOID);        }    		//查询主分类		$sql = 'SELECT id FROM '.DB_PREFIX.'sort WHERE fid = 0';		$q = $this->db->query($sql);		while($r = $this->db->fetch_array($q))		{			$fids[] = $r['id'];		}			//非管理员不能删除主分类		if ($this->user['group_type'] > MAX_ADMIN_TYPE)	    {	    	if(in_array(intval($this->input['id']),$fids))	    	{	    		$this->errorOutput(NO_PRIVILEGE);	    	}		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])	        {	        	$this->errorOutput(NO_PRIVILEGE);	        }	    }        //判断分类下时候有数据        $sql = "SELECT id FROM ".DB_PREFIX."article WHERE sort_id IN('".$this->input['id']."')";         $q = $this->db->query_first($sql);         if ($q) {            $this->errorOutput("分类下有内容,暂不能删除!");        }                   $this->initNodeData();        //判断是否成功删除        if($this->batchDeleteNode($this->input['id']))        {            $this->addItem(array('id' => urldecode($this->input['id'])));        }        $this->addLogs('删除文稿节点','','','删除文稿节点+' . $this->input['id']);        $this->output();    }    public function create()    {            $data = array(		        'ip'=>hg_getip(),		        'create_time'=>TIMENOW,		        'fid'=>intval($this->input['fid']),		        'update_time'=>TIMENOW,		        'name'=>trim(urldecode($this->input['name'])),		        'brief'=>trim(urldecode($this->input['brief'])),		        'user_name'=>trim(urldecode($this->user['user_name']))            );            if (!$data['name'])            {                    $this->errorOutput(NOSORTNAME);            }            $this->verify_create_node($data['fid']);		    $this->initNodeData();		    //设置新增或者需要更新的节点数据		    $this->setNodeData($data);		    //增加节点无需设置操作节点ID		    if($nid = $this->addNode())		    {		            $data['id'] = $nid;		            $this->addItem($data);		    }		    $this->addLogs('创建文稿节点','',$data,$data['name']);    		$this->output();    }    //排序    public function drag_order()    {            $sort = json_decode(html_entity_decode($this->input['sort']),true);            if(!empty($sort))            {                    foreach($sort as $key=>$val)                    {                            $data = array(                                    'order_id' => $val,                            );                            if(intval($key) && intval($val))                            {                                    $sql ="UPDATE " . DB_PREFIX . "sort SET";                                    $sql_extra=$space=' ';                                    foreach($data as $k => $v)                                    {                                            $sql_extra .=$space . $k . "='" . $v . "'";                                            $space=',';                                    }                                    $sql .=$sql_extra.' WHERE id='.$key;                                    $this->db->query($sql);                            }                            $id[] = $key;                    }            }            $this->addLogs('文稿节点排序','','','文稿节点排序+' . implode(',',$id));            $this->addItem('success');            $this->output();    }}$out = new news_node_update();if(!method_exists($out, $_INPUT['a'])){	$action = 'unknow';}else {	$action = $_INPUT['a'];}$out->$action(); ?>