<?php
define('MOD_UNIQUEID','verify_code');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/verify_code_mode.php');
//require_once(CUR_CONF_PATH . 'lib/functions.php');
class verify_code_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new verify_code_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		//权限
		$this->verify_content_prms(array('_action'=>'manage_verify_change'));
		
		//默认值在模板里给定
		$data = array(
			"name" 			=> 	trim($this->input['name']),		//名称
			"type_id"		=> 	$this->input['type_id'],			//类别
			"operation"		=>	$this->input['operation'],		//运算方式
			"length" 		=> 	trim($this->input['length']) ? trim($this->input['length']) : 4,	//字符个数
			"is_dipartite"	=>	$this->input['is_dipartite'],	//是否区分大小写
			"fontface_id"	=>	$this->input['fontface_id'],		//字体
			//"width" 			=> 	trim($this->input['width']) ? trim($this->input['width']) : 120,		//宽	
			//"height" 		=> 	trim($this->input['height']) ? trim($this->input['height']) : 35,	//高
			//"fontsize" 		=> 	trim($this->input['fontsize']),	//字体大小
			"font_space" 	=> 	trim($this->input['font_space']),//字符间隙
			"translation"	=>	trim($this->input['translation']) ? trim($this->input['translation']) : 0,//平移量
			//"angle" 			=> 	$this->input['angle'],		//倾斜角度
			"line_num" 		=> 	trim($this->input['line_num']) ? trim($this->input['line_num']) : 0,	//干扰线数量
			"point_num" 		=> 	trim($this->input['point_num']) ? trim($this->input['point_num']) : 0,	//干扰点数量
			"create_time" 	=> 	TIMENOW,
			"update_time" 	=> 	TIMENOW,
			"org_id" 		=> 	$this->user['org_id'],
			"update_org_id" 	=> 	$this->user['org_id'],
			"user_id"		=> 	$this->user['user_id'],
			"update_user_id"		=> 	$this->user['user_id'],
			"user_name" 		=> 	$this->user['user_name'],
			"update_user_name" 	=> 	$this->user['user_name'],
			"ip" 			=>	hg_getip(),
			"update_ip" 		=>	hg_getip(),
		);
		//接收的参数
		$can['is_size'] = $this->input['is_size'];	 //字体大小随机
		$can['font_size'] = $this->input['font_size']; //字体大小区间
		$can['is_color'] = $this->input['is_color'];	 //字体颜色随机
		$can['is_bgcolor'] = $this->input['is_bgcolor'];	 //使用背景图片
		$can['is_wid_hei'] = $this->input['is_wid_hei'];	 //自适应宽高
		$can['is_angle'] = $this->input['is_angle'];	 //是否旋转
		//数据处理
		$data = $this->data_check($data,$can);
		//创建内容状态
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$create_status = $this->user['prms']['default_setting']['create_content_status'];
			switch($create_status)
			{
				case 0 : $none;break; //系统默认
				case 1 : $data['status'] = 0;break; //待审核
				case 2 : $data['status'] = 1;break; //已审核
			}
		}
		
		//第一个验证码需要默认开启
		$sql = "SELECT id FROM " .DB_PREFIX. "verify LIMIT 0,1";
		$q = $this->db->query_first($sql);
		if(!$q)
		{
			$data['is_default'] = 1;
		}
		
		//入库
		$vid = $this->mode->create($data);
		if($vid)
		{
			//将使用到的字体标记为'被使用'
			$sql = "UPDATE ". DB_PREFIX . "font SET is_using=1 WHERE id=" .$data['fontface_id'];
			$this->db->query($sql);
			
			if($data['bgpicture_id'])
			{
				//将使用到的背景图片标记为'被使用'
				$sql = "UPDATE " .DB_PREFIX. "bgpicture SET is_using=1 WHERE id=" .$data['bgpicture_id'];
				$this->db->query($sql);
			}
			$data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		/**************更新数据权限判断***************/
		$sql = "select * from " . DB_PREFIX ."verify where id = " . $this->input['id'];
		$q = $this->db->query_first($sql);
		$info['id'] = $q['id'];
		$info['org_id'] = $q['org_id'];
		$info['user_id'] = $q['user_id'];
		$info['_action'] = 'manage_verify_change';
		$this->verify_content_prms($info);
		/*********************************************/
		
		$update_data = array(
			"name" 			=> 	trim($this->input['name']),		//名称
			"type_id"		=> 	$this->input['type_id'],			//类别
			"operation"		=>	$this->input['operation'],		//运算方式
			"length" 		=> 	trim($this->input['length']),	//字符个数
			"is_dipartite"	=>	$this->input['is_dipartite'],	//是否区分大小写
			//"bgpicture_id"	=>	$this->input['picture_id'],		//背景图片id
			//"bg_color"		=>	$this->input['bg_color'],		//背景颜色
			"fontface_id"	=>	$this->input['fontface_id'],		//字体
			//"width" 			=> 	trim($this->input['width']) ? trim($this->input['width']) : 120,		//宽	
			//"height" 		=> 	trim($this->input['height']) ? trim($this->input['height']) : 35,	//高
			//"fontsize" 		=> 	trim($this->input['fontsize']),	//字体大小
			//"fontcolor"		=>	$this->input['fontcolor'],		//字体颜色
			"font_space" 	=> 	trim($this->input['font_space']),//字符间隙
			"translation"	=>	trim($this->input['translation']),//平移量
			//"angle" 			=> 	$this->input['angle'],			//倾斜角度
			"line_num" 		=> 	trim($this->input['line_num']),	//干扰线数量
			"point_num" 		=> 	trim($this->input['point_num']),	//干扰点数量
		);
		//接收的参数
		$can['is_size'] = $this->input['is_size'];	 //字体大小随机
		$can['font_size'] = $this->input['font_size']; //字体大小区间
		$can['is_color'] = $this->input['is_color'];	 //字体颜色随机
		$can['is_bgcolor'] = $this->input['is_bgcolor'];	 //使用背景图片
		$can['is_wid_hei'] = $this->input['is_wid_hei'];	 //自适应宽高
		$can['is_angle'] = $this->input['is_angle'];	 //是否旋转
		//数据验证
		$update_data = $this->data_check($update_data,$can,trim($this->input['old_name']));
		
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			//修改审核内容(权限里设置)
			if($this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				$update_status = $this->user['prms']['default_setting']['update_audit_content'];
				switch($update_status)
				{
					case 0 : $info['status'] = $q['status'];break; //保持原状态
					case 1 : $info['status'] = 0;break; //待审核
					case 2 : $info['status'] = 1;break; //已审核
					case 3 : $info['status'] = 2;break; //已打回
				}
			}
			else
			{
				$info['status'] = $q['status'];
			}
			
			//如果内容更改了,继续更新以下内容
			$info['update_user_name'] = $this->user['user_name'];
			$info['update_user_id'] = $this->user['user_id'];
			$info['update_org_id'] = $this->user['org_id'];
			$info['update_time'] = TIMENOW;
			$info['update_ip'] = $this->user['ip'];
			$sql = "UPDATE " . DB_PREFIX . "verify SET 
					update_user_name ='" . $info['update_user_name'] . "',
					update_user_id = '".$info['update_user_id']."',
					update_org_id = '".$info['update_org_id']."',
					status = '".$info['status']."',
					update_ip = '" . $info['update_ip'] . "', 
					update_time = '". TIMENOW . "' WHERE id=" . $this->input['id'];
			$this->db->query($sql);
		}
		//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
		$this->addItem('success');
		$this->output();
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		/**************删除权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'verify WHERE id IN ('.$this->input['id'].')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage_verify_change'));
			}
		}
		/*********************************************/	
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret == 'is_default')
		{
			$this->errorOutput('默认验证码不可删除');
		}
		if($ret)
		{
			//节点表
			$sql = "SELECT type_id FROM " .DB_PREFIX. "verify GROUP BY type_id";
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$type_ids[] = $row['type_id'];
			}
			$types = array_keys($this->settings['verify_type']);
			if($type_ids)
			{
				$different = array_diff($types,$type_ids);
				$node_ids = implode(',',$different);
			}
			else
			{
				$node_ids = implode(',',$types);
			}
			
			$sql = "DELETE FROM " .DB_PREFIX. "verify_node WHERE id IN (" .$node_ids. ")";
			$this->db->query($sql);
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		$id = urldecode($this->input['id']);	//验证码id们
		$audit = $this->input['audit']; //操作标识,'审核'或'打回'
		
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		/**************审核权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'verify WHERE id IN ('. $id .')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'audit'));
			}
		}
		/*********************************************/
		
		if($audit == 1)	//'审核'操作
		{
			$status = 1;
			$audit_status = '已审核';
		}
		elseif($audit == 0)	//'打回'操作
		{
			$status = 2;
			$audit_status = '已打回';
			//默认验证码不可打回
			$sql = "SELECT id FROM " .DB_PREFIX. "verify WHERE is_default = 1";
			$re = $this->db->query_first($sql);
			if(in_array($re['id'], explode(',',$id)))
			{
				$this->errorOutput('默认验证码不可打回');
			}
		}
		
		$sql = " UPDATE " .DB_PREFIX. "verify SET status = " .$status. " WHERE id in (" . $id . ")";
		$this->db->query($sql);
		$ret = array('status' => $status,'id' => $id,'audit'=>$audit_status);
	
		if($ret)
		{
			$this->addLogs('审核','',$ret,'审核验证码' . $id);	//此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}
	
	/**
	 * 设置默认验证码,在没有选择特定验证码的情况下使用默认验证码
	 */
	public function set_default()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		//检查是否是已审核内容
		$sql = "SELECT status FROM " .DB_PREFIX. "verify WHERE id = " .$this->input['id'];
		$re = $this->db->query_first($sql);
		if($re['status'] != 1)
		{
			$this->errorOutput('该验证码未经审核,不能设为默认');
		}
		$sql_close = "UPDATE " .DB_PREFIX. "verify SET is_default = 0";
		$sql_open = "UPDATE " .DB_PREFIX. "verify SET is_default = 1 WHERE id = " .$this->input['id'];
		$this->db->query($sql_close);
		$this->db->query($sql_open);
		$this->addItem('success');
		$this->output();		
	}
	
	/**
	 * 数据检测
	 */
	private function data_check($data = array(),$can = array(),$old_name = "")
	{
		if($data['fontface_id'] <= 0)
		{
			$this->errorOutput('请选择字体');
		}
		//角度处理
		if($can['is_angle'])
		{
			$data['angle'] = $this->input['angle'];
			$data['angle'][0] ? $data['angle'][0] = round($data['angle'][0]) : $data['angle'][0] = 0;
			$data['angle'][1] ? $data['angle'][1] = round($data['angle'][1]) : $data['angle'][1] = 0;
			$data['angle'] = $data['angle'][0].','.$data['angle'][1];
		}
		else
		{
			$data['angle'] = '0,0';
		}
		//字体大小判断
		if($can['is_size'])
		{
			$fontsize = array_filter($can['font_size']);
			if(!$fontsize || count($fontsize) != '2')
			{
				$this->errorOutput('字体大小变化区间不能为空');
			}
			else
			{
				$data['fontsize'] = round($fontsize[0]).','.round($fontsize[1]);
			}
		}
		else
		{
			$data['fontsize'] = trim($this->input['fontsize']);
		}	
		
		//字体颜色判断
		if($can['is_color'])
		{
			$data['fontcolor'] = 1;
		}
		else
		{
			$fontcolor = $this->input['fontcolor'][0];
			if(!$fontcolor)
			{
				$data['fontcolor'] = "#000000";	//不填默认是黑色
			}
			else
			{
				//$color = hex2rgb($data['fontcolor'][0]);
				//$data['fontcolor'] = $color['r'].','.$color['g'].','.$color['b'];
				$data['fontcolor'] = $fontcolor;
			}
		}
		
		//背景判断
		if($can['is_bgcolor'])
		{
			$data['bg_color'] = 0;
			$data['bgpicture_id'] =	$this->input['picture_id'];	//背景图片id
		}
		else
		{
			$bgcolor = $this->input['bg_color'][0];
			if(!$bgcolor)
			{
				$data['bg_color'] = "#FFFFFF";	//不填默认是白色
				$data['bgpicture_id'] =	0;
			}
			else
			{
				//$color = hex2rgb($data['bg_color'][0]);
				//$data['bg_color'] = $color['r'].','.$color['g'].','.$color['b'];
				$data['bg_color'] = $bgcolor;
				$data['bgpicture_id'] =	0;
			}	
		}
		//宽高处理
		if($can['is_wid_hei'])
		{
			$data['width'] = 0;
			$data['height'] = 0;
		}
		else
		{
			if($this->input['width'] && $this->input['height'])
			{
				$data['width'] = round($this->input['width']);
				$data['height'] = round($this->input['height']);
			}
			else
			{
				$data['width'] = 0;
				$data['height'] = 0;
			}
		}
		
		if(!$data['name'])
		{
			$this->errorOutput("请填写验证码名称");
		}
		/*	
		if(!$data['type_id'])
		{
			$this->errorOutput("没有type_id");
		}
		*/
		//检查名字是否重复
		$sql = "SELECT id FROM " . DB_PREFIX . "verify WHERE name='" . $data['name'] . "'";
		$arr = $this->db->query_first($sql);
		$c_id = $arr['id'];
		if(!$old_name)//创建
		{
			if($c_id)
			{
				$this->errorOutput("该名称已存在");
			}
		}
		else //更新
		{
			if($c_id && $data['name'] != $old_name)
			{
				$this->errorOutput("该名称已存在");
			}
		}
		//节点表
		$sql = "SELECT id FROM " . DB_PREFIX . "verify_node  WHERE id=" .$data['type_id'];
		$re = $this->db->query_first($sql);
		if(!$re['id'])
		{
			$name = $this->settings['verify_type'][$data['type_id']];
			$sql = "INSERT INTO " . DB_PREFIX . "verify_node SET id=".$data['type_id'].",name='".$name."',is_last=1";
			$this->db->query($sql);
		}
		
		return $data;	
	}
	
	/**
	 * 验证码预览功能
	 * Enter description here ...
	 */
	public function preview()
	{
		$parameter = array(
		  'type_id' => $this->input['type_id'] ? $this->input['type_id'] : '1',
		  'operation' => $this->input['operation'] ? $this->input['operation'] : '5',
		  'length' => $this->input['length'] ? $this->input['length'] : '4',
		  //'width' => $this->input['width'] ? $this->input['width'] : '0',
		  //'height' => $this->input['height'] ? $this->input['height'] : '0',
		  'fontface_id' => $this->input['fontface_id'] ? $this->input['fontface_id'] : '60',
		  'font_space' => $this->input['font_space'] ? $this->input['font_space'] : '0',
		  'translation' => $this->input['translation'] ? $this->input['translation'] : '0',
		  'angle' => $this->input['angle'] ? $this->input['angle'][0] : '0,0',
		  'line_num' => $this->input['line_num'] ? $this->input['line_num'] : '0',
		  'point_num' => $this->input['point_num'] ? $this->input['point_num'] : '0',
		  'fontface' => $this->input['character'],
		  'bg_pic' => $this->input['bg_pic'],
		  'pic_type' => $this->input['pic_type'],
		);
		if($this->input['is_wid_hei'])
		{
			$parameter['width'] = 0;
			$parameter['height'] = 0;
		}
		else
		{
			if($this->input['width'] && $this->input['height'])
			{
				$parameter['width'] = $this->input['width'];
				$parameter['height'] = $this->input['height'];
			}
			else
			{
				$parameter['width'] = 0;
				$parameter['height'] = 0;
			}
		}
		if($this->input['is_size'])
		{
			$parameter['fontsize'] = $this->input['font_size'][0].','.$this->input['font_size'][1];
		}
		else
		{
			$parameter['fontsize'] = $this->input['fontsize'];
		}
		
		if($this->input['is_color'])
		{
			$parameter['fontcolor'] = 1;
		}
		else
		{
			$parameter['fontcolor'] = $this->input['fontcolor'][0];
			if(!$parameter['fontcolor'])
			{
				$parameter['fontcolor'] = '#000000';
			}
		}
		
		if($this->input['is_angle'])
		{
			$parameter['angle'] = $this->input['angle'][0].','.$this->input['angle'][1];
		}
		else
		{
			$parameter['angle'] = '0,0';
		}
		
		if($this->input['is_bgcolor'])
		{
			$parameter['bg_color'] = 0;
			$parameter['bgpicture_id'] = $this->input['picture_id'];
		}
		else
		{
			$parameter['bg_color'] = $this->input['bg_color'][0];
			if(!$parameter['bg_color'])
			{
				$parameter['bg_color'] = '#FFFFFF';
			}
			$parameter['bgpicture_id'] = 0;
		}
		$parameter = json_encode($parameter);
		require_once(ROOT_PATH . 'lib/class/curl.class.php');
      	$curl = new curl($this->settings['App_verifycode']['host'],$this->settings['App_verifycode']['dir'].'admin/');
		$curl->setSubmitType('post');
        $curl->setReturnFormat('json');
        $curl->initPostData();
        $curl->addRequestData('a','preview');
        $curl->addRequestData('parameter',$parameter);
        $curl->addRequestData('html', true);
        $ret = $curl->request('verify_code.php');
        header('Content-type:image/png');
		$file_content = base64_encode($ret);
		$img = 'data:image/png;base64,'.$file_content;
		$data['img'] = $img;
        $this->addItem($data);
		$this->output();
	}
	
	public function sort()
	{
        $ret = $this->drag_order('verify', 'order_id');
        $this->addItem($ret);
        $this->output();
	}
	
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new verify_code_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>