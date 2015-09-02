<?php
require('global.php');
define('MOD_UNIQUEID','jf_mall');
class JfMallUpdate extends adminUpdateBase
{
    public function __construct()
    {
        parent::__construct();
        include_once CUR_CONF_PATH . 'lib/good.class.php';
        include_once CUR_CONF_PATH . 'lib/material.class.php';
        $this->goods_mode = new GoodMode();
        $this->material_mode = new MaterialMode();
    }

    public function create() {
        if (!$this->input['title']) {
            $this->errorOutput('标题不能为空');
        }

        #####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
        if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['node_id'])
        {
            $sql = 'SELECT id, parents FROM '.DB_PREFIX.'node WHERE id IN('.$this->input['node_id'].')';
            $query = $this->db->query($sql);
            while($row = $this->db->fetch_array($query))
            {
                $nodes['nodes'][$row['id']] = $row['parents'];
            }
        }
        $nodes['_action'] = 'create';
        $this->verify_content_prms($nodes);
        #####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点

        $goods = array(
            'node_id'           => $this->input['node_id'],
            'title'             => $this->input['title'],
            'keywords' 			=> str_replace(' ',',',trim($this->input['keywords'])),
            'indexpic'          => $this->input['indexpic'],
            'score'             => abs($this->input['score']),
            'price'             => abs($this->input['price']),
            'market_price'      => abs($this->input['market_price']),
            'total_limit'       => intval(abs($this->input['total_limit'])),   //总量限制
            'period_limit'      => intval(abs($this->input['period_limit'])),  //每周期限制
            'order_limit'       => intval(abs($this->input['order_limit'])),   //每个订单数量限制
            'amount_limit'      => intval(abs($this->input['amount_limit'])),  //账号限制
            'period_type'       => $this->input['period_type'] ? intval(abs($this->input['period_type'])) : 1,    //周期类型  1、每天 2、每星期 3、每月
            'type'              => $this->input['type'] ? intval($this->input['type']) : 1,
            'need_extras_info'  => $this->input['need_extras_info'],
            'create_time'       => TIMENOW,
            'update_time'       => TIMENOW,
            'user_id'           => $this->user['user_id'],
            'user_name'         => $this->user['user_name'],
            'org_id'            => $this->user['org_id'],
            'status'            => $this->get_status_setting('create'),
        		'outlink_title'     => trim($this->input['outlink_title']),
        		'outlink_url'       => trim($this->input['outlink_url']),
	        	'pick_up_way'		=> intval($this->input['pick_up_way']),
	        	'contact_way'		=> $this->input['contact_way'],
	        	'pick_address'		=> $this->input['pick_address'],
	        	'comments'			=> $this->input['comments'],
        );

        /** 时间限制 **/
        $this->input['start_date'] = $this->input['start_date'] ? strtotime($this->input['start_date']) : TIMENOW;
        if ($this->input['end_date'] && ($this->input['start_date'] > strtotime($this->input['end_date']))) {
        	$this->errorOutput('开始日期不能大于等于结束日期');
        }
        $goods['start_date'] = date('Ymd', $this->input['start_date']);
        //留空不限制结束日期
        $goods['end_date'] = $this->input['end_date'] ? date('Ymd', strtotime($this->input['end_date'])) : 0;
        $this->input['start_time'] = $this->input['start_time'] ? $this->input['start_time'] : '00:00:00';
        $this->input['start_time'] = strtotime($this->input['start_time']);
        $this->input['end_time'] = $this->input['end_time'] ? $this->input['end_time'] : '23:59';
        $this->input['end_time'] = strtotime($this->input['end_time']);
        if ($this->input['start_time'] >= $this->input['end_time']) {
        	$this->errorOutput('开始时间不能大于等于结束时间');
        }
        $goods['start_time'] = date('His', $this->input['start_time']);
        $goods['end_time'] = date('His', $this->input['end_time']);

        if ( $this->input['week_day'] && is_array($this->input['week_day']) && count($this->input['week_day']) > 0 ) {
            $this->input['week_day'] = implode(', ', $this->input['week_day']);
        }
        $goods['week_day'] = $this->input['week_day'];
        /** 时间限制 **/

        $insert_id = $this->goods_mode->insert($goods);

        if (!$insert_id) {
            $this->errorOutput('商品添加失败');
        }

        $goods_detail = array(
            'good_id'           => $insert_id,
            'brief'              => $this->input['brief'],
            'exchange_state'    => $this->input['exchange_state'],
            'exchange_rule'     => $this->input['exchange_rule'],
        );

        if (!$this->goods_mode->insert_detail($goods_detail)) {
            $this->errorOutput('商品添加失败');
        }

        //更改已上传图片good_id
        $material_id = $this->input['material_id'];
        if (is_array($material_id) && count($material_id) > 0 ) {
            $material_id = implode(', ' , $material_id);
            $where = ' id IN('.$material_id.')';
            if (!$this->material_mode->update(array('good_id' => $insert_id), $where)) {

            }
        }
        $return = array_merge($goods, $goods_detail);
        $return['id'] = $insert_id;
        $this->addItem($return);
        $this->output();
    }

    public function update() {

        if (!$this->input['id']) {
            $this->errorOutput('NO ID');
        }

        if (!$this->input['title']) {
            $this->errorOutput('标题不能为空');
        }

        $good_id = intval($this->input['id']);

        $ori_good_info = $this->goods_mode->getOne(' AND g.id = ' . $good_id);

        #####节点权限检测数据收集
        if($this->user['group_type'] > MAX_ADMIN_TYPE) {
            $_sort_ids = '';
            if($ori_good_info['node_id']) {
                $_sort_ids = $ori_good_info['node_id'];
            }
            if($this->input['node_id']) {
                $_sort_ids  = $_sort_ids ? $_sort_ids . ',' . $this->input['node_id'] : $this->input['node_id'];
            }
            if($_sort_ids) {
                $sql = 'SELECT id, parents FROM '.DB_PREFIX.'node WHERE id IN('.$_sort_ids.')';
                $query = $this->db->query($sql);
                while($row = $this->db->fetch_array($query)) {
                    $data['nodes'][$row['id']] = $row['parents'];
                }
            }
        }
        #####节点权限
        #####验证是否允许修改他人数据
        $data['id'] = $good_id;
        $data['user_id'] = $ori_good_info['user_id'];
        $data['org_id'] = $ori_good_info['org_id'];
        #####验证是否允许修改他人数据
        $data['_action'] = 'update';
        $this->verify_content_prms($data);

        //获取商品原始的素材
        $where = ' AND good_id = ' . $good_id;
        $old_material = $this->material_mode->select($where);
        $old_material_id = array();
        foreach ((array) $old_material as $key => $val) {
            $old_material_id[] = $val['id'];
        }

         //更改商品表
        $goods = array(
            'node_id'           => $this->input['node_id'],
            'title'             => $this->input['title'],
            'keywords' 			=> str_replace(' ',',',trim($this->input['keywords'])),
            'indexpic'          => $this->input['indexpic'],
            'score'             => abs($this->input['score']),
            'price'             => abs($this->input['price']),
            'market_price'      => abs($this->input['market_price']),
            'total_limit'       => intval(abs($this->input['total_limit'])),   //总量限制
            'period_limit'      => intval(abs($this->input['period_limit'])),  //每周期限制
            'order_limit'       => intval(abs($this->input['order_limit'])),   //每个订单数量限制
            'amount_limit'      => intval(abs($this->input['amount_limit'])),  //账号限制
            'period_type'       => $this->input['period_type'] ? intval(abs($this->input['period_type'])) : 1,    //周期类型  1、每天 2、每星期 3、每月
            'type'              => $this->input['type'] ? intval($this->input['type']) : 1,
            'need_extras_info'  => $this->input['need_extras_info'],
        		'outlink_title'     => trim($this->input['outlink_title']),
        		'outlink_url'       => trim($this->input['outlink_url']),
	        	'pick_up_way'		=> intval($this->input['pick_up_way']),
	        	'contact_way'		=> $this->input['contact_way'],
	        	'pick_address'		=> $this->input['pick_address'],
	        	'comments'			=> $this->input['comments'],
        );

        /** 时间限制 **/
        $this->input['start_date'] = $this->input['start_date'] ? strtotime($this->input['start_date']) : TIMENOW;
        if ($this->input['end_date'] && ($this->input['start_date'] > strtotime($this->input['end_date']))) {
        	$this->errorOutput('开始日期不能大于等于结束日期');
        }        
        $goods['start_date'] = date('Ymd', $this->input['start_date']);
        //留空不限制结束日期
        $goods['end_date'] = $this->input['end_date'] ? date('Ymd', strtotime($this->input['end_date'])) : 0;
        $this->input['start_time'] = $this->input['start_time'] ? $this->input['start_time'] : '00:00:00';
        $this->input['start_time'] = strtotime($this->input['start_time']);
		$this->input['end_time'] = $this->input['end_time'] ? $this->input['end_time'] : '23:59';        
		$this->input['end_time'] = strtotime($this->input['end_time']);
        if ($this->input['start_time'] >= $this->input['end_time']) {
        	$this->errorOutput('开始时间不能大于等于结束时间');
        }
        $goods['start_time'] = date('His', $this->input['start_time']);
        $goods['end_time'] = date('His', $this->input['end_time']);
        if ( $this->input['week_day'] && is_array($this->input['week_day']) && count($this->input['week_day']) > 0 ) {
            $this->input['week_day'] = implode(', ', $this->input['week_day']);
        }
        $goods['week_day'] = $this->input['week_day'];
        /** 时间限制 **/

        $good_update_ret = $this->goods_mode->update($goods, ' `id` = ' . $good_id);

        //更改商品详情表
        $goods_detail = array(
            'brief'             => $this->input['brief'],
            'exchange_state'    => $this->input['exchange_state'],
            'exchange_rule'     => $this->input['exchange_rule'],
        );
        $content_update_ret = $this->goods_mode->update_detail($goods_detail, ' `good_id` = ' . $good_id);

        //更改素材
        $material_id = $this->input['material_id'];

        if(is_string($material_id)) {
            $material_id = explode(', ', $material_id);
        }
        $del_material_id = array_unique(array_diff($old_material_id, $material_id));
        if ( is_array($del_material_id) && count($del_material_id) > 0 ) {
            $where = ' AND id IN('.implode(', ', $del_material_id).')';
            $del_material_ret = $this->material_mode->delete($where);
        }
        if (is_array($material_id) && count($material_id) > 0 ) {
            $material_id = implode(', ' , $material_id);
            $where = ' id IN('.$material_id.')';
            if (!($update_material_ret = $this->material_mode->update(array('good_id' => $good_id), $where))) {

            }
        }

        if ($good_update_ret || $content_update_ret || $del_material_ret || $update_material_ret) {
            $data = array(
                'update_time' => TIMENOW,
                'status'      => $this->get_status_setting('update_audit', $ori_good_info['status']),
            );
            $this->goods_mode->update($data, ' `id` = ' . $good_id);
        }
        $return = array_merge($goods, $goods_detail);
        $return['id'] = $good_id;
        $this->addItem($return);
        $this->output();
    }

    public function delete(){
        if (!$this->input['id']) {
            $this->errorOutput('NO ID');
        }
        $id = $this->input['id'];

        if (is_array($id)) {
            $id = implode(', ', $id);
        }

        if ($this->user['group_type'] > MAX_ADMIN_TYPE) {
            $goods = $this->goods_mode->select(' AND g.id IN(' . $id . ')');
            $nodes = array();
            foreach ((array)$goods as $k => $v) {
                //验证是否有权限修改他人数据
                $this->verify_content_prms(array('id' => $v['id'], 'user_id' => $v['user_id'], 'org_id' => $v['org_id'], '_action' => 'delete'));
                $nodes[] = $v['node_id'];
            }
            $data = array();
            if ($nodes && count($nodes) > 0) {
                $sql = 'SELECT id,parents FROM '.DB_PREFIX.'node WHERE id IN('.implode(',', $nodes).')';
                $q = $this->db->query($sql);

                while($row = $this->db->fetch_array($q)) {
                    $data['nodes'][$row['id']] = $row['parents'];
                }
            }
            $data['_action'] = 'delete';
            $this->verify_content_prms($data);
        }

        if (!$this->goods_mode->delete(' AND g.id IN(' . $id . ')')) {
            $this->errorOutput('删除失败');
        }

        $this->addItem($id);
        $this->output();
    }

    public function audit()
    {
        $id = urldecode($this->input['id']);
        if(!$id) {
            $this->errorOutput("未传入商品ID");
        }
        $idArr = explode(',',$id);

        if ($this->user['group_type'] > MAX_ADMIN_TYPE) {
            $goods = $this->goods_mode->select(' AND g.id IN(' . $id . ')');
            $nodes = array();
            foreach ((array)$goods as $k => $v) {
                //验证是否有权限修改他人数据
                $this->verify_content_prms(array('id' => $v['id'], 'user_id' => $v['user_id'], 'org_id' => $v['org_id'],  '_action' => 'audit'));
                $nodes[] = $v['node_id'];
            }
            $data = array();
            if ($nodes && count($nodes) > 0) {
                $sql = 'SELECT id,parents FROM '.DB_PREFIX.'node WHERE id IN('.implode(',', $nodes).')';
                $q = $this->db->query($sql);

                while($row = $this->db->fetch_array($q)) {
                    $data['nodes'][$row['id']] = $row['parents'];
                }
            }
            $data['_action'] = 'audit';
            $this->verify_content_prms($data);
        }

        if(intval($this->input['audit']) == 1)
        {
            $this->goods_mode->update(array('status' => 1), " id IN({$id})");
            $return = array('status' => 1,'id'=> $idArr);
        }
        else if(intval($this->input['audit']) == 0)
        {
            $this->goods_mode->update(array('status' => 2), " id IN({$id})");
            $return = array('status' =>2,'id' => $idArr);
        }
        $this->addItem($return);
        $this->output();
    }
    
    /**
     * 拖动排序
     */
    public function drag_order() {

        //$this->verify_content_prms(array('_action' => 'update'));

    	parent::drag_order('goods', 'order_id');
    }

    public function sort(){

    }

    public function publish(){

    }
    
    public function upload() {

        $this->verify_content_prms(array('_action' => 'create'));

		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();
		$material = $this->mater->addMaterial($_FILES);
		if (!empty($material) && is_array($material)) {
			$material['pic'] = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
			$data = array(
				'material_id'	=> $material['id'],
				'pic'			=> addslashes(json_encode($material['pic'])),
			);	
			$insert_id = $this->material_mode->insert($data);
			$return = array(
				'id'	=> $insert_id,
				'filename'   => $material['filename'] . '?' . hg_generate_user_salt(4),
				'name'       => $material['name'],
				'mark'       => $material['mark'],
				'type'       => $material['type'],
				'filesize'   => $material['filesize'],
				'path'       => $material['host'] . $material['dir'],
				'dir'        => $material['filepath'],
			);		
		} 
		else {
			$return = array(
				'error' => '文件上传失败',
			);
		}
		$this->addLogs('上传图片','','', $return['name']);
		$this->addItem($return);
		$this->output();		   	 
    }

    public function unknow() {
        $this->errorOutput('方法不存在');
    }
    
    public function __destruct() {
        parent::__destruct();
    }

}
$out = new JfMallUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action)) {
    $action = 'unknow';
}
$out->$action();