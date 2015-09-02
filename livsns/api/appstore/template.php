<?php

define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . 'global.php');
require(CUR_CONF_PATH . 'lib/appstore_frm.php');

class index extends appstore_frm
{

    private $temp_dir='/web/publish_product/app_templates/';

    function __construct()
    {
        parent::__construct();
        include_once(CUR_CONF_PATH.'lib/template.class.php');
        $this->temp = new template();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    function show()
    {
        parent::__destruct();
    }
    
    public function get_data_source_info()
    {
        $signs = $this->input['sign'];
        $flag  = $this->input['flag'];
        unset($this->input['appid'], $this->input['appkey']);
        $host  = $this->settings['App_publishsys']['host'];
        $dir   = $this->settings['App_publishsys']['dir'] . 'admin/';
        $curl  = new curl($host, $dir);
        $curl->setSubmitType('post');
        $curl->initPostData();
        $curl->addRequestData('a', 'export_datasource');
        $curl->addRequestData('sign', $signs);
        if ($flag)
        {
            $curl->addRequestData('flag', $flag);
        }

        $data_source_info = $curl->request('data_source.php');
        echo json_encode($data_source_info);
    }
    
    public function get_tem_sort()
    {
        $fid = $this->input['fid'];
        unset($this->input['appid'], $this->input['appkey']);
        $host  = $this->settings['App_publishsys']['host'];
        $dir   = $this->settings['App_publishsys']['dir'] . 'admin/';
        $curl  = new curl($host, $dir);
        $curl->setSubmitType('post');
        $curl->initPostData();
        $curl->addRequestData('a', 'get_tem_sort');
        $curl->addRequestData('fid', fid);

        $tem_sort_info = $curl->request('template.php');
        echo json_encode($tem_sort_info);
    }
    
    //获取列表
    public function get_temp()
    {
        $type = $this->input['type']?intval($this->input['type']):2;
        $offset = $this->input['offset']?intval($this->input['offset']):0;
        $count = $this->input['count']?intval($this->input['count']):10000;
        $sql = "select * from ".DB_PREFIX."templates where type=".$type." order by id desc limit {$offset},{$count}";
        $info = $this->db->query($sql);
        while($row = $this->db->fetch_array($info))
        {
            $ds[] = $row;
            $ds_ids[$row['sign']] = $row['sign'];
        }
        $sql = "select * from ".DB_PREFIX."temp_logs t where id =  (select  id from ".DB_PREFIX."temp_logs where sign = t.sign and customer_id=".$this->user['id']." and type={$type} and sign in ('".implode("','",$ds_ids)."') order by versions desc limit 0,1);";
        $info = $this->db->query($sql);
        while($row = $this->db->fetch_array($info))
        {
            $ds_logs[$row['sign']] = $row['versions'];
        }
        foreach($ds as $k=>$v)
        {
            if($ds_logs[$v['sign']])
            {
                //更新标识
                if($ds_logs[$v['sign']]==$v['versions'])
                {
                    //不变标识
                    $v['chg_status'] = 1;
                }
                else
                {
                    //更新标识
                    $v['chg_status'] = 2;
                }
            }
            else
            {
                //安装标识
                $v['chg_status'] = 0;
            }
            $this->addItem($v);
        }
        $this->output();
    }
    
    //获取文件
    public function get_file()
    {
        $type = $this->input['type']?intval($this->input['type']):1;
        $signs = $this->input['sign'];
        $con = '';
        if($signs)
        {
            $con .= " and sign in ('".implode("','",explode(',',$signs))."')";
        }
        $sql = "select * from ".DB_PREFIX."templates where type={$type} ".$con;
        $info = $this->db->query($sql);
        while($row = $this->db->fetch_array($info))
        {
            $file = TEMP_DIR.$type.'/'.$row['sign'].'/'.$row['versions'].'/'.$row['sign'].'.php';
            if(file_exists($file))
            {
                $result[$row['sign']]['data'] = file_get_contents($file);
                
                $insert_data = array(
                    'customer_id' => $this->user['id'],
                    'type' => $type,
                    'sign' => $row['sign'],
                    'versions' => $row['versions'],
                    'create_time' => time(),
                );
                $this->temp->insert('temp_logs',$insert_data);
            }
            $material_dir = TEMP_DIR.$type.'/'.$row['sign'].'/'.$row['versions'].'/'.'material.zip';
            if(file_exists($material_dir))
            {
                $result[$row['sign']]['material'] = 'upgrade.hogesoft.com:233/product/release/app_templates/'.$type.'/'.$row['sign'].'/'.$row['versions'].'/'.'material.zip';
                $result[$row['sign']]['replace_dir'] = TEMP_DIR.$type.'/'.$row['sign'].'/'.$row['versions'].'/material';
            }
        }
        echo json_encode($result);exit;
    }

}

$module  = 'index';
$$module = new $module();

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
    $func = 'get_temp';
}
$$module->$func();
?>