<?php
define('MOD_UNIQUEID', 'fleamarket');
require('global.php');
class roadUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/fleamarket.class.php');
		$this->obj = new road();
		include_once(ROOT_PATH . 'lib/class/share.class.php');
		$this->share = new share();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function sort(){}
	public function publish(){}
	public function create()
	{
		if(empty($this->input['content']))
		{
			$this->errorOutput('内容不能为空！');
		}
		$data = array(
			"content" 				=> $this->input["content"] ?  $this->input["content"] : '',
			"address" 				=> $this->input["address"] ? $this->input["address"] : '',
			"price" 				=> $this->input["price"] ? $this->input["price"] : '',
			"real_name" 			=> $this->input["real_name"] ? $this->input["real_name"] : '',
			"tel" 					=> $this->input["tel"] ? $this->input["tel"] : '',						
			"longitude" 			=> $this->input['longitude'] ? $this->input['longitude'] : '',
			"latitude" 				=> $this->input['latitude'] ? $this->input['latitude'] : '',
			"baidu_longitude"       => $this->input['baidu_longitude'] ? $this->input['baidu_longitude'] : '',
			"baidu_latitude"        => $this->input['baidu_latitude'] ? $this->input['baidu_latitude'] : '',
			"user_id" 				=> $this->user['user_id'],
			"user_name" 			=> $this->user['user_name'],
			"source" 				=> $this->input["source"],
			"group_id" 				=> $this->input["group_id"] > 0 ? intval($this->input['group_id']) : 3,
			"roadname" 				=> $this->input['roadname'],
			"state" 				=> intval($this->input["state"]),
			"effect_time" 			=> $this->input["effect_time"] ? $this->input['effect_time'] : 40,
			"create_time" 			=> TIMENOW,
			"update_time"           => TIMENOW,
			"ip" 					=> hg_getip(),
			"appid" 				=> intval($this->user["appid"]),
			"appname" 				=> $this->user["appname"],
			"pic"                   => json_encode($this->input['pic']),
			"is_hot"				=> intval($this->input['is_hot'])>0?1:0,
			"is_sale"				=> intval($this->input['is_sale'])>0?1:0,
		);		
		$ret = $this->obj->create($data);
		
		//2013.07.11 scala 如果需要增加地区参数
		if(isset($this->input['area']))
		{
			$this->add_road_area($ret['id'],$this->input['area']);
		}
		//2013.07.11 scala  end 
		$this->addLogs('添加路况','',$data,$data['content']);	
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput('NOID');
		}
		if(empty($this->input['content']))
		{
			$this->errorOutput('NOCONTENT');
		}
		$data = array(
			"content" 			=> $this->input["content"] ?  $this->input["content"] : '',
			"address" 			=> $this->input["address"] ? $this->input["address"] : '',
			"price" 			=> $this->input["price"] ? $this->input["price"] : '',
			"real_name" 		=> $this->input["real_name"] ? $this->input["real_name"] : '',
			"tel" 				=> $this->input["tel"] ? $this->input["tel"] : '',				
			"longitude"			=> $this->input['longitude'] ? $this->input['longitude'] : '',
			"latitude" 			=> $this->input['latitude'] ? $this->input['latitude'] : '',
			"baidu_longitude"   => $this->input['baidu_longitude'] ? $this->input['baidu_longitude'] : '',
			"baidu_latitude"    => $this->input['baidu_latitude'] ? $this->input['baidu_latitude'] : '',
			"group_id" 			=> $this->input["group_id"] > 0 ? intval($this->input['group_id']) : 3,
			"roadname" 			=> $this->input['roadname'],
			"state" 			=> intval($this->input["state"]),
			"effect_time" 		=> $this->input["effect_time"] ? $this->input['effect_time'] : 40,
			"update_time"       => TIMENOW,
			"is_hot"		    => intval($this->input['is_hot'])>0?1:0,
			"is_sale"			=> intval($this->input['is_sale'])>0?1:0,
		);
		if(isset($this->input['pic']))
		{
			$data['pic'] = json_encode($this->input['pic']);
		}
		$con = " WHERE id = " . intval($this->input['id']);		
		$ret = $this->obj->update($data,$con);
		//2013.07.12 scala 如果需要增加地区参数
		if(isset($this->input['area']))
		{
			
			$this->obj->delete_road_area($this->input['id']);
			$this->add_road_area($this->input['id'],$this->input['area']);
				
		}
		//2013.07.12 scala  end 
		$this->addLogs('更新路况','','',$data['content']);
		$this->addItem($ret);
		$this->output();	
	}
	
	public function delete()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('ID不能为空！');
		}
		$id = $this->obj->delete(urldecode($this->input['id']));
		$this->obj->delete_road_area(urldecode($this->input['id']));
		$ret['id'] = $id;
		$this->addLogs('删除路况','','','删除路况+' . $ret['id']);
		$this->addItem($ret);
		$this->output();
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput('ID不能为空！');
		}
		$ids = urldecode($this->input['id']);
		$audit = intval($this->input['audit']);
		$ret = $this->obj->audit($ids,$audit);
		$this->addLogs($ret['opration'],'','',$ret['opration'] . '+' . $ids);
		$this->addItem($ret);
		$this->output();
	}
	
	/*参数:video_id(路况的id可以多个),order_id(圈子的排序id),table_name(需要排序的表名)
	 *功能:对圈子列表进行排序操作
	 *返回值:将圈子id以逗号隔开，字符串的形式返回
	 * */
	public function drag_order()
	{
		if(!$this->input['content_id'])
		{
			$this->errorOutput(NOID);
		}
		$ids       = explode(',', urldecode($this->input['content_id']));
		$order_ids = explode(',', urldecode($this->input['order_id']));
		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " .DB_PREFIX. "road  SET orderid = ".$order_ids[$k]."  WHERE id = ".$v;
			$this->db->query($sql);
		}
		$this->addLogs('拖动排序','','','拖动排序+' . $ids);
		$this->addItem($ids);
		$this->output();
	}
	
	/**
	 * 获取接入平台类型信息
	 * 
	 */
	public function show_plat_auth()
	{
		$sql = "SELECT plat_token FROM " . DB_PREFIX ."plat_token WHERE appid = " . intval($this->user['appid']);
		$ret = $this->db->query($sql);
		$plat_token = array();
		while($row = $this->db->fetch_array($ret))
		{
			$plat_token[] = $row['plat_token'];
		}
		$plat_token = implode(',',$plat_token);
		$plat = $this->share->get_plat($plat_token);
		$plat_ids = array();
		foreach ($plat as $k => $v)
		{
			$plat[$k]['expired_time'] = date('Y-m-d H:i:s',$plat[$k]['expired_time']);	
			$plat_ids[] = $v['id'];		
		}
		$plat_ids = implode(',',$plat_ids);
		$sql = "DELETE FROM " . DB_PREFIX ."plat_token WHERE platid NOT IN (".$plat_ids.")";			
		$this->db->query($sql);
		$this->addItem($plat);
		$this->output();
	}
	
	/**
	 * 请求接入平台token
	 * Enter description here ...
	 */
	public function request_auth()
	{
		$type = intval($this->input['type']);
		$platid = intval($this->input['platid']);
		$sql = "SELECT platid,plat_token FROM " .DB_PREFIX ."plat_token WHERE platid = " . $platid;
		$ret = $this->db->query_first($sql);
		$plat = $this->share->oauthlogin($platid,$ret['plat_token']);
		$plat = $plat[0];
		if(!$ret)
		{
			$sql = "INSERT INTO " . DB_PREFIX ."plat_token(appid,type,platid,plat_token) VALUES
			('{$this->user['appid']}','{$type}','{$platid}','{$plat['access_plat_token']}')";
			$this->db->query($sql);
		}
		else
		{
			$sql = "UPDATE " . DB_PREFIX ."plat_token SET lastusetime = '' ";
			$this->db->query($sql);
		}
		
		$plat['url'] = $plat['sync_third_auth'] . '?oauth_url=' . $plat['oauth_url'] . '&access_plat_token=' .$plat['access_plat_token']; 
		$this->addItem($plat);
		$this->output();
	}
	//2013.03.11 scala 增加road_area记录数据效验
	private function add_road_area($id,$areas)
	{
		if(!$id)
			return false;
		$this->obj->add_road_area($id,$areas);
	}
	public function update_road_area_type()
	{
		if(!isset($this->input['type']))
			return false;
			
		$ret = $this->obj->update_road_area_type(intval($this->input['type']));
		return $ret;
	}
	
	
	
	//2013.03.11 scala 
}

$out = new roadUpdateApi();
$action = $_INPUT["a"];
if (!method_exists($out,$action))
{
	$action = "create";
}
$out->$action();
?>