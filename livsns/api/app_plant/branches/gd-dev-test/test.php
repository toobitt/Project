<?php
define('MOD_UNIQUEID', 'test');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/app_info_mode.php');
require_once(CUR_CONF_PATH . 'lib/appModule.class.php');
require_once(CUR_CONF_PATH . 'lib/developer_auth_mode.php');
require_once(CUR_CONF_PATH . 'lib/app.class.php');

class getArea extends outerReadBase
{
    private $app_info_mode;
    private $app_module;
    private $developer_mode;
    private $app;
    public function __construct()
    {
        parent::__construct();
        $this->app_info_mode = new app_info_mode();
        $this->app_module = new appModule();
        $this->developer_mode = new developer_auth_mode();
        $this->app = new app();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function detail(){}
    public function count(){}

    public function show()
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "app_attribute";
        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q))
        {
            if($r['def_val'] && @unserialize($r['def_val']))
            {
                $r['def_val'] = @unserialize($r['def_val']);
            }
            $this->addItem($r);
        }
        $this->output();
    }
    
    public function revert_init()
    {
        $app_id = $this->input['app_id'];
        if(!$app_id)
        {
            $this->errorOutput('no id');
        }
        
        if(intval($this->input['z']) == 1)
        {
            $sql = "DELETE FROM " .DB_PREFIX. "attribute_main_value WHERE app_id = '" .$app_id. "'";
            $this->db->query($sql);
            
            $sql = "DELETE FROM " .DB_PREFIX. "ui_attribute_main_value WHERE app_id = '" .$app_id. "'";
            $this->db->query($sql);
        }
        else if(intval($this->input['z']) == 2)
        {
            $sql = "DELETE FROM " .DB_PREFIX. "attribute_list_value WHERE module_id IN (SELECT id FROM " .DB_PREFIX. "app_module WHERE app_id = '" .$app_id. "')";
            $this->db->query($sql);
            
            $sql = "DELETE FROM " .DB_PREFIX. "ui_attribute_list_value WHERE module_id IN (SELECT id FROM " .DB_PREFIX. "app_module WHERE app_id = '" .$app_id. "')";
            $this->db->query($sql);
        }
        else if(intval($this->input['z']) == 3)
        {
            $sql = "UPDATE " .DB_PREFIX. "app_module SET text_nor_bg = '',text_pre_bg = '',layout_pre_bg = '',layout_nor_bg = '',layout_pre_alpha = '',layout_nor_alpha = '',main_color = '',navbar = '',ui_bg = '',ui_padding_bottom = 0  WHERE app_id = '" .$app_id. "'";
            $this->db->query($sql);
        }
        else 
        {
            echo 'o no! there is nothing should be clean!';
            exit;
        }
        
        echo 'clean ok!';
    }
    
    public function is_mobile()
    {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $is_ipad = (strpos($agent, 'ipad')) ? true : false;
        $is_ipod = (strpos($agent, 'ipod')) ? true : false;
        $is_android = (strpos($agent, 'android')) ? true : false;
        define('ISIOS', ($is_iphone || $is_ipad || $is_ipod));
        define('ISANDROID', $is_android);   
        $is_phone = (ISIOS || ISANDROID)?TRUE:FALSE;
        
        if($is_phone)
        {
            echo 'YES' . $_SERVER['HTTP_USER_AGENT'];
        }
        else 
        {
            echo 'NO';
        }
    }
    
    //更新打包时间
    public function update_packtime()
    {
        $sql = "SELECT MAX(create_time) AS create_time,app_id FROM " .DB_PREFIX. "app_version GROUP BY app_id";
        $q = $this->db->query($sql);
        while($r = $this->db->fetch_array($q))
        {
            //更新
            $_sql = "UPDATE " . DB_PREFIX . "app_info SET pack_time = '" .$r['create_time']. "' WHERE id = '" .$r['app_id']. "' ";
            $this->db->query($_sql);
        }
        
        echo 'update ok';
    }

    //更新upyun域名
    public function update_domain()
    {
        //首先更新app_material
        $sql = "UPDATE " .DB_PREFIX. "app_material SET host = 'http://upimg.dingdone.com/' WHERE host = 'http://imagedingdone.b0.upaiyun.com/' ";
        $this->db->query($sql);
        
        //更新app_info
        $sql = "SELECT id,icon,startup_pic,startup_pic2,startup_pic3 FROM " .DB_PREFIX."app_info ";
        $q = $this->db->query($sql);
        $_update_app_ids = array();
        while ($r = $this->db->fetch_array($q))
        {
            $update_data = array();
            if($r['icon'] && $icon_arr = @unserialize($r['icon']))
            {
                if($icon_arr['host'] == 'http://imagedingdone.b0.upaiyun.com/')
                {
                    $icon_arr['host'] = 'http://upimg.dingdone.com/';
                    $update_data['icon'] = addslashes(serialize($icon_arr));
                }
            }
            
            if($r['startup_pic'] && $startup_pic_arr = @unserialize($r['startup_pic']))
            {
                if($startup_pic_arr['host'] == 'http://imagedingdone.b0.upaiyun.com/')
                {
                    $startup_pic_arr['host'] = 'http://upimg.dingdone.com/';
                    $update_data['startup_pic'] = addslashes(serialize($startup_pic_arr));
                }
            }
            
            if($r['startup_pic2'] && $startup_pic2_arr = @unserialize($r['startup_pic2']))
            {
                if($startup_pic2_arr['host'] == 'http://imagedingdone.b0.upaiyun.com/')
                {
                    $startup_pic2_arr['host'] = 'http://upimg.dingdone.com/';
                    $update_data['startup_pic2'] = addslashes(serialize($startup_pic2_arr));
                }
            }
            
            if($r['startup_pic3'] && $startup_pic3_arr = @unserialize($r['startup_pic3']))
            {
                if($startup_pic3_arr['host'] == 'http://imagedingdone.b0.upaiyun.com/')
                {
                    $startup_pic3_arr['host'] = 'http://upimg.dingdone.com/';
                    $update_data['startup_pic3'] = addslashes(serialize($startup_pic3_arr));
                }
            }
            
            if($update_data)
            {
                $this->app_info_mode->update($r['id'],$update_data);
                $_update_app_ids[] = $r['id'];
            }
        }
        
        //更新模块
        $sql = "SELECT id,pic,press_pic FROM " .DB_PREFIX. "app_module";
        $q = $this->db->query($sql);
        $_module_ids = array();
        while ($r = $this->db->fetch_array($q))
        {
            $_m_update = array();
            if($r['pic'] && $_pic = @unserialize($r['pic']))
            {
                if($_pic['host'] == 'http://imagedingdone.b0.upaiyun.com/')
                {
                    $_pic['host'] = 'http://upimg.dingdone.com/';
                    $_m_update['pic'] = addslashes(serialize($_pic));
                }
            }
            
            if($r['press_pic'] && $_press_pic = @unserialize($r['press_pic']))
            {
                if($_press_pic['host'] == 'http://imagedingdone.b0.upaiyun.com/')
                {
                    $_press_pic['host'] = 'http://upimg.dingdone.com/';
                    $_m_update['press_pic'] = addslashes(serialize($_press_pic));
                }
            }
            
            if($_m_update)
            {
                $this->app_module->update('app_module',$_m_update,array('id' => $r['id']));
                $_module_ids[] = $r['id'];
            }
        }
        
        //更新content_tpl
        $sql = "SELECT id,img_info FROM " .DB_PREFIX. "content_tpl";
        $q = $this->db->query($sql);
        $_content_tpl_ids = array();
        while ($r = $this->db->fetch_array($q))
        {
            if($r['img_info'] && $_img_info = @unserialize($r['img_info']))
            {
                if($_img_info['host'] == 'http://imagedingdone.b0.upaiyun.com/')
                {
                    $_img_info['host'] = 'http://upimg.dingdone.com/';
                    $_sql = "UPDATE " . DB_PREFIX . "content_tpl SET img_info = '" .addslashes(serialize($_img_info)). "' WHERE id = '" .$r['id']. "' ";
                    $this->db->query($_sql); 
                    $_content_tpl_ids[] = $r['id'];
                }
            }
        }
        
        //更新固化模块
        $sql = "SELECT id,pic FROM " .DB_PREFIX. "solidify_module";
        $q = $this->db->query($sql);
        $_solid_ids = array();
        while ($r = $this->db->fetch_array($q))
        {
            if($r['pic'] && $_img = @unserialize($r['pic']))
            {
                if($_img['host'] == 'http://imagedingdone.b0.upaiyun.com/')
                {
                    $_img['host'] = 'http://upimg.dingdone.com/';
                    $_sql = "UPDATE " . DB_PREFIX . "solidify_module SET pic = '" .addslashes(serialize($_img)). "' WHERE id = '" .$r['id']. "' ";
                    $this->db->query($_sql); 
                    $_solid_ids[] = $r['id'];
                }
            }
        }
        
        //更新属性里面图片单选的style_value
        $sql = "SELECT id,style_value FROM " .DB_PREFIX. "ui_attribute WHERE attr_type_id IN (6,7)";
        $q = $this->db->query($sql);
        $attr_ids = array();
        while($r = $this->db->fetch_array($q))
        {
            if($r['style_value'] && $_style_value = @unserialize($r['style_value']))
            {
                if($_style_value['datasource'] && is_array($_style_value['datasource']))
                {
                    foreach($_style_value['datasource'] AS $k => $v)
                    {
                        if($v['img_info']['host'] == 'http://imagedingdone.b0.upaiyun.com/')
                        {
                            $_style_value['datasource'][$k]['img_info']['host'] = 'http://upimg.dingdone.com/';
                        }
                    }
                    
                    $_sql = "UPDATE " .DB_PREFIX. "ui_attribute SET style_value = '" .addslashes(serialize($_style_value)). "' WHERE id = '" .$r['id']. "' ";
                    $this->db->query($_sql);
                    $attr_ids[] = $r['id'];
                }
            }
        }

        $this->addItem(array(
            'app_info' => implode(',', $_update_app_ids),
            'module'   => implode(',', $_module_ids),
            'cotnent_tpl'   => implode(',', $_content_tpl_ids),
            'solid'   => implode(',', $_solid_ids),
            'attr_ids' => implode(',', $attr_ids) .'=='.  count($attr_ids),       
        ));
        
        $this->output();
    }

    /**
     * 将app_name update进去
     */
    public function updateAllAppname()
    {
    	$ret = $this->developer_mode->getAllNoAppName();
    	if($ret)
    	{
    		foreach ($ret as $k => $v)
    		{
    			$app_info = $this->app->getAppInfoByUserId($v['dingdone_user_id']);
    			$app_name = $app_info['name'];
    			$result = $this->developer_mode->updateAppNameByDingdoneUserId($app_name,$v['dingdone_user_id']);
    			$this->addItem($result);
    		}
    	}
    	$this->output();
    }
    
    //整理出某个ui的数据
    public function exportUIData()
    {
        $ui_id = intval($this->input['ui_id']);
        if(!$ui_id)
        {
            $this->errorOutput(NO_UI_ID);
        }
        
        //attribute_relate 数据
        $_attribute_relate = array();
        $sql = "SELECT * FROM " .DB_PREFIX. "attribute_relate WHERE ui_id = '" . $ui_id . "' ";
        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q))
        {
            $_attribute_relate[] = $r;
        }
        
        //ui_attribute 数据
        $_ui_attribute = array();
        $ui_attr_ids = array();
        $sql = "SELECT * FROM " .DB_PREFIX. "ui_attribute WHERE ui_id = '" . $ui_id . "' ";
        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q))
        {
            $ui_attr_ids[] = $r['id'];
            $_ui_attribute[] = $r;
        }
        
        //ui_attribute_relate 前台属性与后台属性关系数据
        $_ui_attribute_relate = array();
        $sql = "SELECT * FROM " .DB_PREFIX. "ui_attribute_relate WHERE ui_attr_id IN (" .implode(',', $ui_attr_ids). ") ";
        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q))
        {
            $_ui_attribute_relate[] = $r;
        }
        
        $dataDir = CACHE_DIR . 'attr_data/' . $ui_id . '/';
        if(!hg_mkdir($dataDir))
        {
            $this->errorOutput(MKDIR_ERROR);
        }
        
        file_put_contents($dataDir . 'attribute_relate.json', json_encode($_attribute_relate));//保存后台属性
        file_put_contents($dataDir . 'ui_attribute.json', json_encode($_ui_attribute));//保存前台属性
        file_put_contents($dataDir . 'ui_attribute_relate.json', json_encode($_ui_attribute_relate));//保存前台属性与后台属性关系数据
        
        echo 'export ok'; 
    }
    
    //显示ui数据
    public function showUIData()
    {
        header('Content-Type: application/json; charset=utf-8');
        $ui_id = intval($this->input['ui_id']);
        if(!$ui_id)
        {
            $this->errorOutput(NO_UI_ID);
        }
        
        $type = $this->input['type'];
        $dataDir = CACHE_DIR . 'attr_data/' . $ui_id . '/';
        if($type == 1)
        {
            $json = file_get_contents($dataDir . 'attribute_relate.json');
            print_r(json_decode($json,1));
        }
        elseif ($type == 2)
        {
            $json = file_get_contents($dataDir . 'ui_attribute.json');
            print_r(json_decode($json,1));
        }
        elseif ($type == 3)
        {
            $json = file_get_contents($dataDir . 'ui_attribute_relate.json');
            print_r(json_decode($json,1));
        }
        else 
        {
            echo 'Invalid Type';
        }
    }
    
    /**
     * 更新所有的应用数据，增加app_mark
     */
    public function updateAllAppMark()
    {
    	//获取所有的应用信息
    	$allAppInfo = $this->app_info_mode->getALLAppInfo();
    	foreach($allAppInfo as $k => $v)
    	{
    		$app_mark = $this->getRandString(10);
    		$data = array(
    			'app_mark' => $app_mark,
    		);
    		$ret = $this->app_info_mode->update($v['id'],$data);
    	}
    }
    /**
     * 生成随机的10位的app_mark
     * @return string $rand
     */
    private function getRandString($length)
    {
    	$char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    	$char = str_shuffle($char);
    	for($i = 0, $rand = '', $l = strlen($char) - 1; $i < $length; $i ++) {
    		$rand .= $char{mt_rand(0, $l)};
    	}
    	return $rand;
    }
    
    public function updateAllAppGuid()
    {
    	$allAppInfo = $this->app_info_mode->getALLAppInfoGuid();
    	foreach($allAppInfo as $k => $v)
    	{
    			$guidIsExist = true;
    			while($guidIsExist)
    			{
    				$guid = Common::getRandString(10);
    				$guidIsExist = $this->app->validataGuidIsExist($guid);
    			}
    			$data = array(
    					'guid' => $guid,
    			);
    			$ret = $this->app_info_mode->update($v['id'],$data);
    		
    	}
    }
    
}
$out = new getArea();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();