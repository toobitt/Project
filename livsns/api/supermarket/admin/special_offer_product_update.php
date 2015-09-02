<?php
define('MOD_UNIQUEID','special_offer_product');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/special_offer_product_mode.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH . 'lib/class/recycle.class.php');
class special_offer_product_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new special_offer_product_mode();
		$this->recycle = new recycle();
		/******************************权限*************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$actions = (array)$this->user['prms']['app_prms']['supermarket']['action'];
			if(!in_array('manger',$actions))
			{
				$this->errorOutput('您没有权限访问此接口');
			}
		}
		/******************************权限*************************/
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		if(!$this->input['name'])
		{
			$this->errorOutput(NO_NAME);
		}
		
		if($this->input['img_id'])
		{
			$img_id = implode(',' , array_filter($this->input['img_id']));  //多图图片数组去空
		}
		
		$old_price 	= (float)$this->input['old_price'];
		$now_price 	= (float)$this->input['now_price'];
		$price_diff = (float)($old_price - $now_price);
		$discount 	= round($price_diff/$old_price,2);
		
		$data = array(
			'name' 				=> $this->input['name'],
			'market_id' 		=> $this->input['market_id'],
			'activity_id' 		=> $this->input['activity_id'],
			'img_id' 			=> isset($img_id) ? $img_id : '',
			'index_img_id' 		=> $this->input['index_img_id'],
			'brief' 			=> $this->input['brief'],
			'product_sort_id' 	=> $this->input['product_sort_id'],
			'product_standard' 	=> $this->input['product_standard'],
			'product_unit' 		=> $this->input['product_unit'],
			'vender' 			=> $this->input['vender'],
			'old_price' 		=> $old_price,
			'now_price' 		=> $now_price,
			'price_diff'		=> $price_diff,
			'discount'			=> $discount,
            'url'               => $this->input['url'],
		    'user_name' 		=> $this->user['user_name'],
			'user_id' 			=> $this->user['user_id'],
			'org_id' 			=> $this->user['org_id'],
		    'update_user_name' 	=> $this->user['user_name'],
			'update_user_id' 	=> $this->user['user_id'],
			'create_time' 		=> TIMENOW,
			'update_time' 		=> TIMENOW,
			'ip' 				=> hg_getip(),
		);
	    $ret = $this->mode->create($data);
		if($ret)
		{
			$this->addLogs('创建商品','',$ret,'创建商品'.$ret['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		if($this->input['img_id'])
		{
			$img_id = implode(',' , array_filter($this->input['img_id']));  //多图图片数组去空
		}
		
		$old_price 	= (float)$this->input['old_price'];
		$now_price 	= (float)$this->input['now_price'];
		$price_diff = (float)($old_price - $now_price);
		$discount 	= round($price_diff/$old_price,2);
		
		$update_data = array(
			'name' 				=> $this->input['name'],
			'img_id' 			=> isset($img_id) ? $img_id : '',
			'index_img_id' 		=> $this->input['index_img_id'],
			'brief' 			=> $this->input['brief'],
			'product_sort_id' 	=> $this->input['product_sort_id'],
			'product_standard' 	=> $this->input['product_standard'],
			'product_unit' 		=> $this->input['product_unit'],
			'vender' 			=> $this->input['vender'],
			'old_price' 		=> $old_price,
			'now_price' 		=> $now_price,
			'price_diff'		=> $price_diff,
			'discount'			=> $discount,
            'url'               => $this->input['url'],
		    'update_user_name' 	=> $this->user['user_name'],
			'update_user_id' 	=> $this->user['user_id'],
			'update_time' 		=> TIMENOW,
		);
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addLogs('更新商品',$ret,'','更新商品' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			foreach ($ret AS $k => $v)
			{
				//记录回收站的数据
				$recycle[$v['id']] = array(
					'title' 		=> $v['name'],
					'delete_people' => $this->user['user_name'],
					'cid' 			=> $v['id'],
					'content'		=> array('special_offer_product' => $v),
				);
			}
			
			/********************************回收站***********************************/
			if($recycle)
			{
				foreach($recycle as $key => $value)
				{
					$this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
				}
			}
			/********************************回收站***********************************/
			$this->addLogs('删除特惠商品',$ret,'','删除特惠商品' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			$this->addLogs('审核商品','',$ret,'审核商品' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sort(){}
	public function publish(){}
	
	/********************************************扩展操作*********************************************/
	//上传特惠商品的图片
	public function uploadProductImg()
	{
		if(!$_FILES['logo'])
		{
			$this->errorOutput(NO_FILE);
		}
		$_FILES['Filedata'] = $_FILES['logo'];
		$material_pic = new material();
		$img_info = $material_pic->addMaterial($_FILES);
		if($img_info)
		{
			$product_pic = array(
				'host'     => $img_info['host'],
				'dir'      => $img_info['dir'],
				'filepath' => $img_info['filepath'],
				'filename' => $img_info['filename'],
				'imgwidth' => $img_info['imgwidth'],
				'imgheight'=> $img_info['imgheight'],
			);
			
			$sql = " INSERT INTO " . DB_PREFIX . "material SET img_info = '" . serialize($product_pic) ."'";
			$this->db->query($sql);
			$vid = $this->db->insert_id();
			$this->addItem(array('id' => $vid,'img_info' => hg_fetchimgurl($product_pic,160)));
			$this->output();
		}
	}
	
	//推荐商品/取消推荐
	public function recommendProduct()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->recommendProduct($this->input['id']);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
	}

	/********************************************扩展操作*********************************************/
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new special_offer_product_update();
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