<?php
define('MOD_UNIQUEID','fleamarket');
require_once ('./global.php');
class doUpdate extends outerUpdateBase
{
		
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/fleamarket.class.php');
		$this->obj = new road();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
        public function create(){

                if(empty($this->input['c-title']))
                {
                        $return = array(
                                'success' => false,
                                'errorinfo' => '没有标题',
                        );
                        $this->addItem($return);
                        $this->output();
                }
                if(empty($this->input['c-content']))
                {
                        $return = array(
                                'success' => false,
                                'errorinfo' => '没有内容',
                        );
                        $this->addItem($return);
                        $this->output();
                }
                //
                $area_id = intval($this->input['areas']);
                $road_area = $this->obj->get_road_area($area_id);
                $areas = $road_area['areaname'];

                $data = array(
                "areas"                                 => $areas ?  $areas : '',
                "is_sale"                               => $this->input["c-issale"] ?  $this->input["c-issale"] : '',
                "roadname"                                      => $this->input["roadname"] ?  $this->input["roadname"] : '',
                "tel"                                   => $this->input["tel"] ?  $this->input["tel"] : '',
                "price"                                         => $this->input["c-price"] ?  $this->input["c-price"] : '',
                "content"                               => $this->input["c-content"] ?  $this->input["c-content"] : '',
                "address"                               => $this->input["c-title"] ? $this->input["c-title"] : '',
                "longitude"                     => $this->input['longitude'] ? $this->input['longitude'] : '',
                "latitude"                              => $this->input['latitude'] ? $this->input['latitude'] : '',
                "baidu_longitude"       => $this->input['baidu_longitude'] ? $this->input['baidu_longitude'] : '',
                "baidu_latitude"        => $this->input['baidu_latitude'] ? $this->input['baidu_latitude'] : '',
                "user_id"                               => $this->user['user_id'],
                "user_name"                     => $this->user['user_name'],
                "source"                                => $this->input["source"],
                "group_id"                              => $this->input["group_id"] > 0 ? intval($this->input['group_id']) : 3,
                "state"                                 => intval($this->input["state"]),
                "effect_time"                   => $this->input["effect_time"] ? $this->input['effect_time'] : 60,
                "create_time"                   => TIMENOW,
                "update_time"           => TIMENOW,
                "ip"                                    => hg_getip(),
                "appid"                                 => intval($this->user["appid"]),
                "appname"                               => $this->user["appname"],
                "pic"                   => json_encode($this->input['Filedata']),
                "is_hot"                                => intval($this->input['is_hot'])>0?1:0,
                "real_name"                     => $this->input["real_name"] ? $this->input["real_name"] : ''
                );
                $ret = $this->obj->create($data);
                $this->obj->add_road_area($ret['id'],$area_id);
                
                //$this->addLogs('添加路况','',$data,$data['content']);
                $this->addItem($ret);
                $this->output();                                
	}
	public function update(){
                if(!$this->input['id'])
                {
                        $this->errorOutput('NOID');
                }
                if(empty($this->input['content']))
                {
                        $this->errorOutput('NOCONTENT');
                }
                $data = array(
                        "uid"                           => $this->input["uid"] ?  $this->input["uid"] : '',
                        "tel"                           => $this->input["tel"] ?  $this->input["tel"] : '',
                        "areas"                                 => $this->input["areas"] ?  $this->input["areas"] : '',
                        "sort_name"                             => $this->input["sort_name"] ?  $this->input["sort_name"] : '',
                        "content"                       => $this->input["content"] ?  $this->input["content"] : '',
                        "address"                       => $this->input["address"] ? $this->input["address"] : '',
                        "price"                                 => $this->input["price"] ? $this->input["price"] : '',
                        "longitude"                     => $this->input['longitude'] ? $this->input['longitude'] : '',
                        "latitude"                      => $this->input['latitude'] ? $this->input['latitude'] : '',
                        "baidu_longitude"   => $this->input['baidu_longitude'] ? $this->input['baidu_longitude'] : '',
                        "baidu_latitude"    => $this->input['baidu_latitude'] ? $this->input['baidu_latitude'] : '',
                        "group_id"                      => $this->input["group_id"] > 0 ? intval($this->input['group_id']) : 3,
                        "roadname"                      => $this->input['roadname'],
                        "state"                         => intval($this->input["state"]),
                        "effect_time"           => $this->input["effect_time"] ? $this->input['effect_time'] : 40,
                        "update_time"       => TIMENOW,
                        "is_hot"                    => intval($this->input['is_hot'])>0?1:0,
                        "is_sale"                       => intval($this->input['is_sale'])>0?1:0,
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
                //$this->addLogs('更新','','',$data['content']);
                $this->addItem($ret);
                $this->output();
	}

	public function delete(){
                if(empty($this->input['road_id']))
                {
                        $return = array(
                                'success' => false,
                                'errorinfo' => '没有id',
                        );
                $this->addItem($return);
                $this->output();
                }

                $id = $this->obj->delete(urldecode($this->input['road_id']));
                $this->obj->delete_road_area(urldecode($this->input['road_id']));
                $ret['id'] = $id;
                //$this->addLogs('删除路况','','','删除路况+' . $ret['id']);
                $this->addItem($ret);
                $this->output();
	}

	public function show(){
	}

	private function add_road_area($id,$areas)
	{
		if(!$id)
			return false;
		$this->obj->add_road_area($id,$areas);
	}	
}
$out = new doUpdate();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>