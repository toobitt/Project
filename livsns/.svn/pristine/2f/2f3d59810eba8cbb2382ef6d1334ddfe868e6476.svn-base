<?php

define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH . 'global.php');
define('MOD_UNIQUEID', 'pub_template'); //模块标识

class pub_template extends adminBase
{
    public function __construct()
    {
        parent::__construct();
        include_once(CUR_CONF_PATH.'lib/template.class.php');
        $this->temp = new template();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    //发布版本
    //type:1.数据源2.样式3.……   
    public function publish_version()
    {
        /**
        $this->input['sign'] = '11111111111';
        $this->input['type'] = '2';
        $this->input['title'] = 'testest';
        $this->input['data'] = 'aaaaaaa';
        $this->input['material'] = array(0=>'3.jpg',1=>'css/ie.css',2=>'images/1.jpg');
        $this->input['app_unique'] = 'publishsys';
        $this->input['material_dir'] = 'data/template/1/61/';
         */
        $sign = $this->input['sign'];
        $type = $this->input['type'];
        $title = $this->input['title'];
        $data = $this->input['data'];
        $material = $this->input['material'] ? unserialize($this->input['material']) : array();//array(0=>'images/1.jpg',1=>'css/1.css')
        $app_unique = $this->input['app_unique'];//publishsys
        $material_dir = $this->input['material_dir'];//publishsys
        if(!$sign || !$type || !$title)
        {
            $this->errorOutput('信息不全');
        }
        $sql = "select * from ".DB_PREFIX."templates where type={$type} and sign='{$sign}'";
        $row = $this->db->query_first($sql);
        if($row)
        {
            $sql = "update ".DB_PREFIX."templates set versions=versions+1 where id=".$row['id'];
            $this->db->query($sql); 
            $versions = $row['versions']+1;
        }
        else
        {
            $insert_data = array(
                'type' => $type,
                'title' => $title,
                'sign' => $sign,
                'versions' => 1,
            );
            $this->temp->insert('templates', $insert_data);
            $versions = 1;
        }
        $file = TEMP_DIR.$type.'/'.$sign.'/'.$versions.'/';
        $this->temp->file_in($file, $sign.'.php',$data,true,true);
        
        if($material && is_array($material))
        {
            set_time_limit(0);
            if(!$app_unique || !$this->settings['App_'.$app_unique])
            {
                echo '没有素材路径配置';exit;
            }
            $dir = rtrim($this->settings['App_'.$app_unique]['host'],'/').'/'.
                    trim($this->settings['App_'.$app_unique]['dir'],'/').'/';
            if($material_dir)
            {
                $dir .= trim($material_dir,'/').'/';
            }
            if($material && is_array($material))
            {
            	foreach($material as $k=>$v)
	            {
	                $v_filenamearr = explode('/',$v);
	                $v_filenamearr = array_reverse($v_filenamearr);
	                $v_filename = $v_filenamearr[0];
	                unset($v_filenamearr[0]);
	                $dir_m = $file.'material/';
	                if($v_filenamearr && is_array($v_filenamearr))
	                {
	                    $v_filenamearr = array_reverse($v_filenamearr);
	                    foreach($v_filenamearr as $kk=>$vv)
	                    {
	                        $dir_m .= $vv.'/';
	                    }
	                }
	                $this->temp->file_in($dir_m, $v_filename,file_get_contents('http://'.$dir.$v),true,true);
	            }
            }
            
            if(file_exists($file.'material/'))
            {
                $zip = new PHPZip();
                $zip->Zip('../../../..'.$file.'material/',$file.'material.zip');
            }
        }
    }
    
}

$out    = new pub_template();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'publish_version';
}
$out->$action();
?>
